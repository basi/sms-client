<?php

declare(strict_types=1);

namespace SmsClient\Config;

use SmsClient\Interface\AuthStrategyInterface;
use SmsClient\Interface\PayloadSerializerInterface;

/**
 * プロバイダー設定クラス
 */
class ProviderConfig
{
    /**
     * @param string $baseUrl ベースURL
     * @param string $sendEndpoint メッセージ送信エンドポイント
     * @param string $getReservationsEndpoint 予約取得エンドポイント
     * @param string $cancelEndpoint キャンセルエンドポイント
     * @param PayloadSerializerInterface $serializer ペイロードシリアライザー
     * @param AuthStrategyInterface $authStrategy 認証戦略
     * @param array $defaultHeaders デフォルトHTTPヘッダー
     */
    public function __construct(
        public readonly string $baseUrl,
        public readonly string $sendEndpoint,
        public readonly string $getReservationsEndpoint,
        public readonly string $cancelEndpoint,
        public readonly PayloadSerializerInterface $serializer,
        public readonly AuthStrategyInterface $authStrategy,
        public readonly array $defaultHeaders = []
    ) {}
}
