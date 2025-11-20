<?php

declare(strict_types=1);

namespace SmsClient\Tests;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SmsClient\Request\CancelRequest;
use SmsClient\Request\GetReservationsRequest;
use SmsClient\Request\SendMessageRequest;
use SmsClient\Response\CancelResponse;
use SmsClient\Response\GetReservationsResponse;
use SmsClient\Response\SendMessageResponse;

final class DtoTest extends TestCase
{
    #[Test]
    public function send_message_request_can_be_instantiated(): void
    {
        $request = new SendMessageRequest(
            to: '09012345678',
            message: 'Hello',
            scheduledAt: new DateTimeImmutable('2024-01-01 10:00:00'),
            additionalParams: ['key' => 'value']
        );

        $this->assertSame('09012345678', $request->to);
        $this->assertSame('Hello', $request->message);
        $this->assertNotNull($request->scheduledAt);
        $this->assertSame(['key' => 'value'], $request->additionalParams);
    }

    #[Test]
    public function send_message_response_can_be_instantiated(): void
    {
        $response = new SendMessageResponse(
            success: true,
            messageId: '123',
            errorMessage: null,
            rawResponse: ['id' => '123']
        );

        $this->assertTrue($response->success);
        $this->assertSame('123', $response->messageId);
        $this->assertNull($response->errorMessage);
        $this->assertSame(['id' => '123'], $response->rawResponse);
    }

    #[Test]
    public function get_reservations_request_can_be_instantiated(): void
    {
        $request = new GetReservationsRequest(
            limit: 10,
            offset: 0
        );

        $this->assertSame(10, $request->limit);
        $this->assertSame(0, $request->offset);
    }

    #[Test]
    public function get_reservations_response_can_be_instantiated(): void
    {
        $response = new GetReservationsResponse(
            success: true,
            count: 5,
            reservations: [['id' => '1']]
        );

        $this->assertTrue($response->success);
        $this->assertSame(5, $response->count);
        $this->assertCount(1, $response->reservations);
    }

    #[Test]
    public function cancel_request_can_be_instantiated(): void
    {
        $request = new CancelRequest(
            messageIds: ['1', '2']
        );

        $this->assertSame(['1', '2'], $request->messageIds);
    }

    #[Test]
    public function cancel_response_can_be_instantiated(): void
    {
        $response = new CancelResponse(
            success: true,
            canceledCount: 2,
            canceledMessageIds: ['1', '2']
        );

        $this->assertTrue($response->success);
        $this->assertSame(2, $response->canceledCount);
        $this->assertSame(['1', '2'], $response->canceledMessageIds);
    }
}
