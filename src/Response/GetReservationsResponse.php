<?php

declare(strict_types=1);

namespace SmsClient\Response;

/**
 * 予約済みメッセージ取得レスポンス
 */
class GetReservationsResponse
{
    /**
     * @param bool $success 取得成功したかどうか
     * @param int $count 予約件数
     * @param array $reservations 予約メッセージの配列
     * @param string|null $errorMessage エラーメッセージ（失敗時）
     * @param array $rawResponse プロバイダーからの生のレスポンスデータ
     */
    public function __construct(
        public readonly bool $success,
        public readonly int $count = 0,
        public readonly array $reservations = [],
        public readonly ?string $errorMessage = null,
        public readonly array $rawResponse = []
    ) {}
}
