<?php

declare(strict_types=1);

namespace SmsClient\Interface;

/**
 * 認証戦略のインターフェース
 */
interface AuthStrategyInterface
{
    /**
     * HTTPヘッダーに認証情報を適用する
     *
     * @param array $headers 既存のヘッダー配列
     *
     * @return array 認証情報が追加されたヘッダー配列
     */
    public function applyToHeaders(array $headers): array;

    /**
     * ペイロードに認証情報を適用する
     *
     * @param array $payload 既存のペイロード配列
     *
     * @return array 認証情報が追加されたペイロード配列
     */
    public function applyToPayload(array $payload): array;
}
