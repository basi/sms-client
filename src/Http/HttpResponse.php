<?php

declare(strict_types=1);

namespace SmsClient\Http;

/**
 * HTTPレスポンスクラス
 *
 * PHP 8.2+ readonly classによるイミュータブルなデータ構造
 */
readonly class HttpResponse
{
    /**
     * @param int $statusCode HTTPステータスコード
     * @param string $body レスポンスボディ
     * @param array<string, array<int, string>> $headers レスポンスヘッダー
     */
    public function __construct(
        public int $statusCode,
        public string $body,
        public array $headers = []
    ) {}

    /**
     * レスポンスが成功したかどうか
     *
     * @return bool 2xxの場合true
     */
    public function isSuccessful(): bool
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }
}
