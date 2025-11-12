<?php

declare(strict_types=1);

namespace SmsClient\Request;

/**
 * メッセージキャンセルリクエスト
 */
class CancelRequest
{
    /**
     * @param array $messageIds キャンセルするメッセージIDの配列
     * @param array $additionalParams 追加パラメータ（プロバイダー固有）
     */
    public function __construct(
        public readonly array $messageIds,
        public readonly array $additionalParams = []
    ) {}
}
