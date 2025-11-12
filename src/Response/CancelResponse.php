<?php

declare(strict_types=1);

namespace SmsClient\Response;

/**
 * メッセージキャンセルレスポンス
 */
class CancelResponse
{
    /**
     * @param bool $success キャンセル成功したかどうか
     * @param int $canceledCount キャンセルされたメッセージ数
     * @param array $canceledMessageIds キャンセルされたメッセージIDの配列
     * @param string|null $errorMessage エラーメッセージ（失敗時）
     * @param array $rawResponse プロバイダーからの生のレスポンスデータ
     */
    public function __construct(
        public readonly bool $success,
        public readonly int $canceledCount = 0,
        public readonly array $canceledMessageIds = [],
        public readonly ?string $errorMessage = null,
        public readonly array $rawResponse = []
    ) {}
}
