<?php

declare(strict_types=1);

namespace SmsClient\Auth;

use SmsClient\Interface\AuthStrategyInterface;

/**
 * Basic認証戦略
 */
class BasicAuthStrategy implements AuthStrategyInterface
{
    /**
     * @param string $username ユーザー名
     * @param string $password パスワード
     */
    public function __construct(
        private readonly string $username,
        private readonly string $password
    ) {}

    /**
     * HTTPヘッダーにBasic認証情報を追加する
     *
     * @param array $headers 既存のヘッダー配列
     *
     * @return array 認証情報が追加されたヘッダー配列
     */
    public function applyToHeaders(array $headers): array
    {
        $credentials = base64_encode("{$this->username}:{$this->password}");
        $headers['Authorization'] = "Basic {$credentials}";
        return $headers;
    }

    /**
     * Basic認証はヘッダーベースなのでペイロードには何も追加しない
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
