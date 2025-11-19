<?php

declare(strict_types=1);

namespace SmsClient\Config;

use SmsClient\Interface\AuthStrategyInterface;
use SmsClient\Interface\PayloadSerializerInterface;

/**
 * プロバイダー設定クラス
 *
 * PHP 8.2+ readonly classによるイミュータブルなデータ構造
 */
readonly class ProviderConfig
{
    /**
     * @param string $baseUrl ベースURL
     * @param string $sendEndpoint メッセージ送信エンドポイント
     * @param string $getReservationsEndpoint 予約取得エンドポイント
     * @param string $cancelEndpoint キャンセルエンドポイント
     * @param PayloadSerializerInterface $serializer ペイロードシリアライザー
     * @param AuthStrategyInterface $authStrategy 認証戦略
     * @param array<string, string> $defaultHeaders デフォルトHTTPヘッダー
     */
    public function __construct(
        public string $baseUrl,
        public string $sendEndpoint,
        public string $getReservationsEndpoint,
        public string $cancelEndpoint,
        public PayloadSerializerInterface $serializer,
        public AuthStrategyInterface $authStrategy,
        public array $defaultHeaders = []
    ) {}
}
