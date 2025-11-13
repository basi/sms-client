<?php

declare(strict_types=1);

namespace SmsClient\Response;

/**
 * SMSメッセージ送信レスポンス
 *
 * PHP 8.2+ readonly classによるイミュータブルなデータ構造
 */
readonly class SendMessageResponse
{
    /**
     * @param bool $success 送信成功したかどうか
     * @param string|null $messageId 送信されたメッセージのID
     * @param string|null $errorMessage エラーメッセージ（失敗時）
     * @param array<string, mixed> $rawResponse プロバイダーからの生のレスポンスデータ
     */
    public function __construct(
        public bool $success,
        public ?string $messageId = null,
        public ?string $errorMessage = null,
        public array $rawResponse = []
    ) {}
}
