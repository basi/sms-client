<?php

declare(strict_types=1);

namespace SmsClient\Tests\Http;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use SmsClient\Http\GuzzleHttpClient;

final class GuzzleHttpClientTest extends TestCase
{
    #[Test]
    public function post_sends_request_and_returns_response(): void
    {
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], 'Hello, World'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new GuzzleHttpClient(['handler' => $handlerStack]);

        $response = $client->post('https://example.com', 'body', ['Content-Type' => 'text/plain']);

        $this->assertSame(200, $response->statusCode);
        $this->assertSame('Hello, World', $response->body);
        $this->assertSame(['Bar'], $response->headers['X-Foo']);
    }

    #[Test]
    public function post_throws_exception_on_failure(): void
    {
        $mock = new MockHandler([
            new Response(500, [], 'Internal Server Error'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new GuzzleHttpClient(['handler' => $handlerStack]);

        // Guzzle throws exception on 4xx/5xx by default
        $this->expectException(RuntimeException::class);
        $client->post('https://example.com', 'body', []);
    }

    #[Test]
    public function get_sends_request_and_returns_response(): void
    {
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], 'Hello, World'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new GuzzleHttpClient(['handler' => $handlerStack]);

        $response = $client->get('https://example.com', ['Accept' => 'application/json']);

        $this->assertSame(200, $response->statusCode);
        $this->assertSame('Hello, World', $response->body);
        $this->assertSame(['Bar'], $response->headers['X-Foo']);
    }

    #[Test]
    public function get_throws_exception_on_failure(): void
    {
        $mock = new MockHandler([
            new Response(404, [], 'Not Found'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new GuzzleHttpClient(['handler' => $handlerStack]);

        $this->expectException(RuntimeException::class);
        $client->get('https://example.com', []);
    }
}
