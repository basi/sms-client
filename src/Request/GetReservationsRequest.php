<?php

declare(strict_types=1);

namespace SmsClient\Request;

use DateTimeInterface;

/**
 * 予約済みメッセージ取得リクエスト
 */
class GetReservationsRequest
{
    /**
     * @param DateTimeInterface|null $startDate 検索開始日時
     * @param DateTimeInterface|null $endDate 検索終了日時
     * @param int|null $limit 取得件数制限
     * @param int|null $offset オフセット
     * @param array $additionalParams 追加パラメータ（プロバイダー固有）
     */
    public function __construct(
        public readonly ?DateTimeInterface $startDate = null,
        public readonly ?DateTimeInterface $endDate = null,
        public readonly ?int $limit = null,
        public readonly ?int $offset = null,
        public readonly array $additionalParams = []
    ) {}
}
