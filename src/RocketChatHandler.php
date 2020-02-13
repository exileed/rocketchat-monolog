<?php

namespace ExileeD\Monolog\RocketChat;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use GuzzleHttp\ClientInterface;

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
     * @var Client;
     */
    private $client;

    /**
     * @var array
     */
    private $webHookUrls;

    /**
     * @var bool
     */
    private $disableException;

    /**
     * @var string
     */
    private $channel;

    /**
     * RocketChatHandler constructor.
     *
     * @param array                $webHookUrls
     * @param string               $channel
     * @param bool                 $disableException
     * @param ClientInterface|null $client
     * @param int                  $level
     * @param bool                 $bubble
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
     * @inheritDoc
     */
    protected function write(array $record)
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
