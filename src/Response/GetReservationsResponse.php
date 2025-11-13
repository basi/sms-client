<?php

declare(strict_types=1);

namespace SmsClient\Response;

/**
 * 予約済みメッセージ取得レスポンス
 *
 * PHP 8.2+ readonly classによるイミュータブルなデータ構造
 */
readonly class GetReservationsResponse
{
    /**
     * @param bool $success 取得成功したかどうか
     * @param int $count 予約件数
     * @param array<int, array<string, mixed>> $reservations 予約メッセージの配列
     * @param string|null $errorMessage エラーメッセージ（失敗時）
     * @param array<string, mixed> $rawResponse プロバイダーからの生のレスポンスデータ
     */
    public function __construct(
        public bool $success,
        public int $count = 0,
        public array $reservations = [],
        public ?string $errorMessage = null,
        public array $rawResponse = []
    ) {}
}
