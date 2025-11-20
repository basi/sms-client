<?php

declare(strict_types=1);

namespace SmsClient\Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SmsClient\Client;
use SmsClient\Interface\SmsProviderInterface;
use SmsClient\Request\CancelRequest;
use SmsClient\Request\GetReservationsRequest;
use SmsClient\Request\SendMessageRequest;
use SmsClient\Response\CancelResponse;
use SmsClient\Response\GetReservationsResponse;
use SmsClient\Response\SendMessageResponse;

final class ClientTest extends TestCase
{
    #[Test]
    public function sendMessage_delegates_to_provider(): void
    {
        // Arrange
        $provider = $this->createMock(SmsProviderInterface::class);
        $client = new Client($provider);
        $request = new SendMessageRequest(
            to: '09012345678',
            message: 'Hello, World!'
        );
        $expectedResponse = new SendMessageResponse(
            success: true,
            messageId: 'msg-123'
        );

        $provider->expects($this->once())
            ->method('sendMessage')
            ->with($request)
            ->willReturn($expectedResponse);

        // Act
        $response = $client->sendMessage($request);

        // Assert
        $this->assertSame($expectedResponse, $response);
    }

    #[Test]
    public function getReservations_delegates_to_provider(): void
    {
        // Arrange
        $provider = $this->createMock(SmsProviderInterface::class);
        $client = new Client($provider);
        $request = new GetReservationsRequest(
            limit: 10
        );
        $expectedResponse = new GetReservationsResponse(
            success: true,
            count: 5,
            reservations: []
        );

        $provider->expects($this->once())
            ->method('getReservations')
            ->with($request)
            ->willReturn($expectedResponse);

        // Act
        $response = $client->getReservations($request);

        // Assert
        $this->assertSame($expectedResponse, $response);
    }

    #[Test]
    public function cancelReservations_delegates_to_provider(): void
    {
        // Arrange
        $provider = $this->createMock(SmsProviderInterface::class);
        $client = new Client($provider);
        $request = new CancelRequest(
            messageIds: ['msg-123', 'msg-456']
        );
        $expectedResponse = new CancelResponse(
            success: true,
            canceledCount: 2,
            canceledMessageIds: ['msg-123', 'msg-456']
        );

        $provider->expects($this->once())
            ->method('cancelReservations')
            ->with($request)
            ->willReturn($expectedResponse);

        // Act
        $response = $client->cancelReservations($request);

        // Assert
        $this->assertSame($expectedResponse, $response);
    }
}
