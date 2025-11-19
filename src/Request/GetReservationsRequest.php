<?php

declare(strict_types=1);

namespace SmsClient\Request;

use DateTimeInterface;

/**
 * 予約済みメッセージ取得リクエスト
 *
 * PHP 8.2+ readonly classによるイミュータブルなデータ構造
 */
readonly class GetReservationsRequest
{
    /**
     * @param DateTimeInterface|null $startDate 検索開始日時
     * @param DateTimeInterface|null $endDate 検索終了日時
     * @param int|null $limit 取得件数制限
     * @param int|null $offset オフセット
     * @param array<string, mixed> $additionalParams 追加パラメータ（プロバイダー固有）
     */
    public function __construct(
        public ?DateTimeInterface $startDate = null,
        public ?DateTimeInterface $endDate = null,
        public ?int $limit = null,
        public ?int $offset = null,
        public array $additionalParams = []
    ) {}
}
