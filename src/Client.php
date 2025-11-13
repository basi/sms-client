<?php

declare(strict_types=1);

namespace SmsClient;

use DateTimeInterface;
use SmsClient\Request\SendMessageRequest;
use SmsClient\Response\SendMessageResponse;
use SmsClient\Interface\SmsProviderInterface;

/**
 * SMSクライアントのメインクラス
 */
class Client
{
    /**
     * @param SmsProviderInterface $provider SMSプロバイダー実装
     */
    public function __construct(
        private readonly SmsProviderInterface $provider
    ) {}

    /**
     * SMSメッセージを送信（または予約）する
     *
     * @param string $to 送信先電話番号
     * @param string $message メッセージ本文
     * @param DateTimeInterface|null $scheduledAt 送信予定日時（nullの場合は即時送信）
     * @param array<string, mixed> $additionalParams 追加パラメータ（プロバイダー固有）
     *
     * @return SendMessageResponse レスポンス
     */
    public function sendMessage(
        string $to,
        string $message,
        ?DateTimeInterface $scheduledAt = null,
        array $additionalParams = []
    ): SendMessageResponse {
        $request = new SendMessageRequest(
            to: $to,
            message: $message,
            scheduledAt: $scheduledAt,
            additionalParams: $additionalParams
        );

        return $this->provider->sendMessage($request);
    }
}
