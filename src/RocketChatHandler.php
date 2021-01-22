<?php

namespace ExileeD\Monolog\RocketChat;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use GuzzleHttp\ClientInterface;

/**
 * Sends notifications through RocketChat Webhooks
 *
 * @author Dmitriy Kuts <me@exileed.com>
 * @see    https://docs.rocket.chat/guides/administrator-guides/integrations
 */
class RocketChatHandler extends AbstractProcessingHandler
{
    /**
     * Colors for a given log level.
     *
     * @var array
     */
    protected $levelColors = [
        Logger::DEBUG => '#9E9E9E',
        Logger::INFO => '#4CAF50',
        Logger::NOTICE => '#607D8B',
        Logger::WARNING => '#FFEB3B',
        Logger::ERROR => '#F44336',
        Logger::CRITICAL => '#F44336',
        Logger::ALERT => '#F44336',
        Logger::EMERGENCY => '#F44336',
    ];

    /**
     * GuzzleClient
     *
     * @var Client
     */
    private $client;

    /**
     * RocketChat Webhook URLs
     *
     * @var array
     */
    private $webHookUrls;

    /**
     * Disable guzzle exceptions
     * @var bool
     */
    private $disableException;

    /**
     * RocketChat channel (ID)
     *
     * @var string
     */
    private $channel;

    /**
     * RocketChatHandler constructor.
     *
     * @param array $webHookUrls RocketChat Webhook URL
     * @param string $channel RocketChat channel (ID)
     * @param bool $disableException Disable guzzle exceptions
     * @param ClientInterface|null $client Guzzle Client
     * @param int $level The minimum logging level at which this handler will be triggered
     * @param bool $bubble Whether the messages that are handled can bubble up the stack or not
     */
    public function __construct(
        array $webHookUrls,
        string $channel,
        bool $disableException = false,
        ClientInterface $client = null,
        $level = Logger::DEBUG,
        $bubble = true
    ) {
        $this->webHookUrls      = $webHookUrls;
        $this->channel          = $channel;
        $this->disableException = $disableException;
        $this->client           = $client ?? new Client();
        parent::__construct($level, $bubble);
    }

    /**
     * RocketChat Webhook URLs
     *
     * @return array
     */
    public function getWebHookUrls(): array
    {
        return $this->webHookUrls;
    }

    /**
     * @inheritDoc
     */
    protected function write(array $record): void
    {
        $level   = $record[ 'level' ] ?? $this->level;
        $content = [
            'text' => '',
            'channel' => $this->channel,
            'attachments' => [
                [
                    'title' => $record[ 'message' ] ?? '',
                    'text' => $record[ 'context' ][ 'message' ] ?? '',
                    'color' => $this->levelColors[ $level ],
                ],
            ],
        ];

        foreach ($this->webHookUrls as $url) {
            try {
                $this->client->post(
                    $url,
                    [
                        'json' => $content,
                    ]
                );
            } catch (GuzzleException $e) {
                if ($this->disableException === false) {
                    throw $e;
                }
            }
        }
    }
}
