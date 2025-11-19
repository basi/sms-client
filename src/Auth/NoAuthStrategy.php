<?php

declare(strict_types=1);

namespace SmsClient\Auth;

use SmsClient\Interface\AuthStrategyInterface;

/**
 * 認証なし - Bearer TokenやBasic Auth ではない場合
 */
class NoAuthStrategy implements AuthStrategyInterface
{
    /**
     * ヘッダーには何も追加しない
     *
     * @param array $headers 既存のヘッダー配列
     *
     * @return array そのまま返す
     */
    public function applyToHeaders(array $headers): array
    {
        return $headers;
    }

    /**
     * ペイロードには何も追加しない
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
