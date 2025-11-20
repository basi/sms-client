<?php

declare(strict_types=1);

namespace SmsClient\Tests\Config;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SmsClient\Config\ProviderConfig;
use SmsClient\Interface\AuthStrategyInterface;
use SmsClient\Interface\PayloadSerializerInterface;

final class ProviderConfigTest extends TestCase
{
    #[Test]
    public function can_be_instantiated_and_accessed(): void
    {
        $serializer = $this->createMock(PayloadSerializerInterface::class);
        $authStrategy = $this->createMock(AuthStrategyInterface::class);
        
        $config = new ProviderConfig(
            baseUrl: 'https://api.example.com',
            sendEndpoint: '/send',
            getReservationsEndpoint: '/reservations',
            cancelEndpoint: '/cancel',
            serializer: $serializer,
            authStrategy: $authStrategy,
            defaultHeaders: ['X-Custom' => 'Value']
        );

        $this->assertSame('https://api.example.com', $config->baseUrl);
        $this->assertSame('/send', $config->sendEndpoint);
        $this->assertSame('/reservations', $config->getReservationsEndpoint);
        $this->assertSame('/cancel', $config->cancelEndpoint);
        $this->assertSame($serializer, $config->serializer);
        $this->assertSame($authStrategy, $config->authStrategy);
        $this->assertSame(['X-Custom' => 'Value'], $config->defaultHeaders);
    }
}
