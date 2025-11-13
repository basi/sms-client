<?php

declare(strict_types=1);

namespace SmsClient\Request;

/**
 * メッセージキャンセルリクエスト
 *
 * PHP 8.2+ readonly classによるイミュータブルなデータ構造
 */
readonly class CancelRequest
{
    /**
     * @param array<int, string> $messageIds キャンセルするメッセージIDの配列
     * @param array<string, mixed> $additionalParams 追加パラメータ（プロバイダー固有）
     */
    public function __construct(
        public array $messageIds,
        public array $additionalParams = []
    ) {}
}
