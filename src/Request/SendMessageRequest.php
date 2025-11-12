<?php

declare(strict_types=1);

namespace SmsClient\Request;

use DateTimeInterface;

/**
 * SMSメッセージ送信リクエスト
 */
class SendMessageRequest
{
    /**
     * @param string $to 送信先電話番号
     * @param string $message メッセージ本文
     * @param DateTimeInterface|null $scheduledAt 送信予定日時（nullの場合は即時送信）
     * @param array $additionalParams 追加パラメータ（プロバイダー固有）
     */
    public function __construct(
        public readonly string $to,
        public readonly string $message,
        public readonly ?DateTimeInterface $scheduledAt = null,
        public readonly array $additionalParams = []
    ) {}
}
