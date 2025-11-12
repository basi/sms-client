<?php

declare(strict_types=1);

namespace SmsClient\Response;

/**
 * SMSメッセージ送信レスポンス
 */
class SendMessageResponse
{
    /**
     * @param bool $success 送信成功したかどうか
     * @param string|null $messageId 送信されたメッセージのID
     * @param string|null $errorMessage エラーメッセージ（失敗時）
     * @param array $rawResponse プロバイダーからの生のレスポンスデータ
     */
    public function __construct(
        public readonly bool $success,
        public readonly ?string $messageId = null,
        public readonly ?string $errorMessage = null,
        public readonly array $rawResponse = []
    ) {}
}
