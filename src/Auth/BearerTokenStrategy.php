<?php

declare(strict_types=1);

namespace SmsClient\Auth;

use SmsClient\Interface\AuthStrategyInterface;

/**
 * Bearer Token認証戦略
 */
class BearerTokenStrategy implements AuthStrategyInterface
{
    /**
     * @param string $token Bearerトークン
     */
    public function __construct(
        private readonly string $token
    ) {}

    /**
     * HTTPヘッダーにBearer Token認証情報を追加する
     *
     * @param array $headers 既存のヘッダー配列
     *
     * @return array 認証情報が追加されたヘッダー配列
     */
    public function applyToHeaders(array $headers): array
    {
        $headers['Authorization'] = "Bearer {$this->token}";
        return $headers;
    }

    /**
     * Bearer Token認証はヘッダーベースなのでペイロードには何も追加しない
     *
     * @param array $payload 既存のペイロード配列
     *
     * @return array そのまま返す
     */
    public function applyToPayload(array $payload): array
    {
        return $payload;
    }
}
