<?php

declare(strict_types=1);

namespace SmsClient\Tests\Provider;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use SmsClient\Config\ProviderConfig;
use SmsClient\Http\HttpResponse;
use SmsClient\Interface\AuthStrategyInterface;
use SmsClient\Interface\HttpClientInterface;
use SmsClient\Interface\PayloadSerializerInterface;
use SmsClient\Interface\RequestTransformerInterface;
use SmsClient\Interface\ResponseParserInterface;
use SmsClient\Provider\HttpSmsProvider;
use SmsClient\Request\CancelRequest;
use SmsClient\Request\GetReservationsRequest;
use SmsClient\Request\SendMessageRequest;

final class HttpSmsProviderTest extends TestCase
{
    private ProviderConfig $config;
    private RequestTransformerInterface $requestTransformer;
    private ResponseParserInterface $responseParser;
    private HttpClientInterface $httpClient;
    private AuthStrategyInterface $authStrategy;
    private PayloadSerializerInterface $serializer;
    private HttpSmsProvider $provider;

    protected function setUp(): void
    {
        $this->authStrategy = $this->createMock(AuthStrategyInterface::class);
        $this->serializer = $this->createMock(PayloadSerializerInterface::class);
        $this->config = new ProviderConfig(
            baseUrl: 'https://api.example.com',
            sendEndpoint: '/send',
            getReservationsEndpoint: '/reservations',
            cancelEndpoint: '/cancel',
            serializer: $this->serializer,
            authStrategy: $this->authStrategy,
            defaultHeaders: ['X-Default' => 'Value']
        );
        $this->requestTransformer = $this->createMock(RequestTransformerInterface::class);
        $this->responseParser = $this->createMock(ResponseParserInterface::class);
        $this->httpClient = $this->createMock(HttpClientInterface::class);

        $this->provider = new HttpSmsProvider(
            config: $this->config,
            requestTransformer: $this->requestTransformer,
            responseParser: $this->responseParser,
            httpClient: $this->httpClient
        );
    }

    #[Test]
    public function sendMessage_sends_request_and_returns_response(): void
    {
        // Arrange
        $request = new SendMessageRequest(to: '09012345678', message: 'test');
        $transformedPayload = ['to' => '09012345678', 'body' => 'test'];
        $authPayload = ['to' => '09012345678', 'body' => 'test', 'token' => 'abc'];
        $serializedBody = '{"to":"09012345678","body":"test","token":"abc"}';
        $headers = ['X-Default' => 'Value', 'Content-Type' => 'application/json'];
        $authHeaders = ['X-Default' => 'Value', 'Content-Type' => 'application/json', 'Authorization' => 'Bearer abc'];
        $httpResponse = new HttpResponse(200, '{"id":"123"}');
        $parsedResponse = ['success' => true, 'messageId' => '123'];

        $this->requestTransformer->method('transform')->with($request)->willReturn($transformedPayload);
        $this->authStrategy->method('applyToPayload')->with($transformedPayload)->willReturn($authPayload);
        $this->serializer->method('serialize')->with($authPayload)->willReturn($serializedBody);
        $this->serializer->method('getContentType')->willReturn('application/json');
        $this->authStrategy->method('applyToHeaders')->with($headers)->willReturn($authHeaders);

        $this->httpClient->expects($this->once())
            ->method('post')
            ->with('https://api.example.com/send', $serializedBody, $authHeaders)
            ->willReturn($httpResponse);

        $this->responseParser->method('parse')
            ->with('{"id":"123"}', 200, $request)
            ->willReturn($parsedResponse);

        // Act
        $response = $this->provider->sendMessage($request);

        // Assert
        $this->assertTrue($response->success);
        $this->assertSame('123', $response->messageId);
    }

    #[Test]
    public function sendMessage_handles_http_exception(): void
    {
        // Arrange
        $request = new SendMessageRequest(to: '09012345678', message: 'test');
        
        // Mock minimal required calls to reach httpClient->post
        $this->requestTransformer->method('transform')->willReturn([]);
        $this->authStrategy->method('applyToPayload')->willReturn([]);
        $this->serializer->method('serialize')->willReturn('');
        $this->serializer->method('getContentType')->willReturn('');
        $this->authStrategy->method('applyToHeaders')->willReturn([]);

        $this->httpClient->method('post')
            ->willThrowException(new RuntimeException('Connection error'));

        // Act
        $response = $this->provider->sendMessage($request);

        // Assert
        $this->assertFalse($response->success);
        $this->assertSame('Connection error', $response->errorMessage);
    }

    #[Test]
    public function getReservations_sends_get_request_and_returns_response(): void
    {
        // Arrange
        $request = new GetReservationsRequest(limit: 10);
        $transformedPayload = ['limit' => 10];
        // GET request does not use applyToPayload for auth, only headers
        $headers = ['X-Default' => 'Value'];
        $authHeaders = ['X-Default' => 'Value', 'Authorization' => 'Bearer abc'];
        $httpResponse = new HttpResponse(200, '{"items":[]}');
        $parsedResponse = ['success' => true, 'count' => 0, 'reservations' => []];

        $this->requestTransformer->method('transform')->with($request)->willReturn($transformedPayload);
        $this->authStrategy->method('applyToHeaders')->with($headers)->willReturn($authHeaders);

        $this->httpClient->expects($this->once())
            ->method('get')
            ->with('https://api.example.com/reservations?limit=10', $authHeaders)
            ->willReturn($httpResponse);

        $this->responseParser->method('parse')
            ->with('{"items":[]}', 200, $request)
            ->willReturn($parsedResponse);

        // Act
        $response = $this->provider->getReservations($request);

        // Assert
        $this->assertTrue($response->success);
        $this->assertSame(0, $response->count);
    }

    #[Test]
    public function cancelReservations_sends_get_request_and_returns_response(): void
    {
        // Arrange
        $request = new CancelRequest(messageIds: ['1', '2']);
        $transformedPayload = ['ids' => '1,2'];
        $headers = ['X-Default' => 'Value'];
        $authHeaders = ['X-Default' => 'Value', 'Authorization' => 'Bearer abc'];
        $httpResponse = new HttpResponse(200, '{"canceled":2}');
        $parsedResponse = ['success' => true, 'canceledCount' => 2];

        $this->requestTransformer->method('transform')->with($request)->willReturn($transformedPayload);
        $this->authStrategy->method('applyToHeaders')->with($headers)->willReturn($authHeaders);

        $this->httpClient->expects($this->once())
            ->method('get')
            ->with('https://api.example.com/cancel?ids=1%2C2', $authHeaders)
            ->willReturn($httpResponse);

        $this->responseParser->method('parse')
            ->with('{"canceled":2}', 200, $request)
            ->willReturn($parsedResponse);

        // Act
        $response = $this->provider->cancelReservations($request);

        // Assert
        $this->assertTrue($response->success);
        $this->assertSame(2, $response->canceledCount);
    }
}
