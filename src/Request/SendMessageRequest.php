<?php

declare(strict_types=1);

namespace SmsClient\Request;

use DateTimeInterface;

/**
 * SMSメッセージ送信リクエスト
 *
 * PHP 8.2+ readonly classによるイミュータブルなデータ構造
 */
readonly class SendMessageRequest
{
    /**
     * @param string $to 送信先電話番号
     * @param string $message メッセージ本文
     * @param DateTimeInterface|null $scheduledAt 送信予定日時（nullの場合は即時送信）
     * @param array<string, mixed> $additionalParams 追加パラメータ（プロバイダー固有）
     */
    public function __construct(
        public string $to,
        public string $message,
        public ?DateTimeInterface $scheduledAt = null,
        public array $additionalParams = []
    ) {}
}
