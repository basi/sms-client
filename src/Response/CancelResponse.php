<?php

declare(strict_types=1);

namespace SmsClient\Response;

/**
 * メッセージキャンセルレスポンス
 *
 * PHP 8.2+ readonly classによるイミュータブルなデータ構造
 */
readonly class CancelResponse
{
    /**
     * @param bool $success キャンセル成功したかどうか
     * @param int $canceledCount キャンセルされたメッセージ数
     * @param array<int, string> $canceledMessageIds キャンセルされたメッセージIDの配列
     * @param string|null $errorMessage エラーメッセージ（失敗時）
     * @param array<string, mixed> $rawResponse プロバイダーからの生のレスポンスデータ
     */
    public function __construct(
        public bool $success,
        public int $canceledCount = 0,
        public array $canceledMessageIds = [],
        public ?string $errorMessage = null,
        public array $rawResponse = []
    ) {}
}
