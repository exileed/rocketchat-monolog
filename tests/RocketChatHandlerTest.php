<?php

declare(strict_types=1);

namespace ExileeD\Monolog\RocketChat\Tests;

use DateTimeImmutable;
use ExileeD\Monolog\RocketChat\RocketChatHandler;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

class RocketChatHandlerTest extends TestCase
{

    public function testOkResponse()
    {
        $mock = new MockHandler(
            [
                new Response(200),
            ]
        );

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $handler = new RocketChatHandler(['https://localhost'], '1234', true, $client);

        $response = $handler->handle($this->getRecord());

        $this::assertIsBool($response);
        $this::assertFalse($response);
    }

    public function testWebhookUrls()
    {
        $defaultUrls = ['https://localhost'];

        $handler = new RocketChatHandler($defaultUrls, '1234');

        $urls = $handler->getWebHookUrls();

        $this::assertIsArray($urls);
        $this::assertEqualsCanonicalizing($urls, $defaultUrls);
    }

    public function testFailResponseDisableException()
    {
        $mock = new MockHandler(
            [
                new RequestException('Error Request Exception', new Request('GET', 'test')),
            ]
        );

        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $handler = new RocketChatHandler(['https://localhost'], '1234', true, $client);

        $response = $handler->handle($this->getRecord());

        $this::assertIsBool($response);
        $this::assertFalse($response);
    }

    /**
     * @param int    $level
     * @param string $message
     * @param array  $context
     *
     * @return array Record
     */
    private function getRecord($level = Logger::WARNING, $message = 'test', array $context = []): array
    {
        return [
            'message' => (string)$message,
            'context' => $context,
            'level' => $level,
            'level_name' => Logger::getLevelName($level),
            'channel' => 'test',
            'datetime' => new DateTimeImmutable('now'),
            'extra' => [],
        ];
    }

    public function testFailResponseEnableException()
    {
        $this->expectException(RequestException::class);
        $mock = new MockHandler(
            [
                new RequestException('Error Request Exception', new Request('GET', 'test')),
            ]
        );
        $handlerStack = HandlerStack::create($mock);
        $client       = new Client(['handler' => $handlerStack]);

        $handler = new RocketChatHandler(['https://localhost'], '1234', false, $client);
        $handler->handle($this->getRecord());
    }
}
