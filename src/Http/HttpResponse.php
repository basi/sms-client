<?php

declare(strict_types=1);

namespace SmsClient\Http;

/**
 * HTTPレスポンスクラス
 */
class HttpResponse
{
    /**
     * @param int $statusCode HTTPステータスコード
     * @param string $body レスポンスボディ
     * @param array $headers レスポンスヘッダー
     */
    public function __construct(
        public readonly int $statusCode,
        public readonly string $body,
        public readonly array $headers = []
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
