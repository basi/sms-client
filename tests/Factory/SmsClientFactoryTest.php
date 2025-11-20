<?php

declare(strict_types=1);

namespace SmsClient\Tests\Factory;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SmsClient\Client;
use SmsClient\Config\ProviderConfig;
use SmsClient\Factory\SmsClientFactory;
use SmsClient\Interface\HttpClientInterface;
use SmsClient\Interface\RequestTransformerInterface;
use SmsClient\Interface\ResponseParserInterface;
use SmsClient\Interface\SmsProviderInterface;

final class SmsClientFactoryTest extends TestCase
{
    #[Test]
    public function createHttpClient_creates_client_with_default_http_client(): void
    {
        $config = $this->createMock(ProviderConfig::class);
        $transformer = $this->createMock(RequestTransformerInterface::class);
        $parser = $this->createMock(ResponseParserInterface::class);

        $client = SmsClientFactory::createHttpClient(
            config: $config,
            requestTransformer: $transformer,
            responseParser: $parser
        );

        $this->assertInstanceOf(Client::class, $client);
    }

    #[Test]
    public function createHttpClient_creates_client_with_provided_http_client(): void
    {
        $config = $this->createMock(ProviderConfig::class);
        $transformer = $this->createMock(RequestTransformerInterface::class);
        $parser = $this->createMock(ResponseParserInterface::class);
        $httpClient = $this->createMock(HttpClientInterface::class);

        $client = SmsClientFactory::createHttpClient(
            config: $config,
            requestTransformer: $transformer,
            responseParser: $parser,
            httpClient: $httpClient
        );

        $this->assertInstanceOf(Client::class, $client);
    }

    #[Test]
    public function createWithProvider_creates_client_with_provider(): void
    {
        $provider = $this->createMock(SmsProviderInterface::class);

        $client = SmsClientFactory::createWithProvider($provider);

        $this->assertInstanceOf(Client::class, $client);
    }
}
