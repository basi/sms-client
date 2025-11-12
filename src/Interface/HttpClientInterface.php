<?php

declare(strict_types=1);

namespace SmsClient\Interface;

use SmsClient\Http\HttpResponse;

/**
 * HTTPクライアントインターフェース
 */
interface HttpClientInterface
{
    /**
     * POSTリクエストを送信する
     *
     * @param string $url リクエストURL
     * @param string $body リクエストボディ
     * @param array $headers HTTPヘッダー
     *
     * @return HttpResponse レスポンス
     */
    public function post(string $url, string $body, array $headers): HttpResponse;

    /**
     * GETリクエストを送信する
     *
     * @param string $url リクエストURL
     * @param array $headers HTTPヘッダー
     *
     * @return HttpResponse レスポンス
     */
    public function get(string $url, array $headers): HttpResponse;
}
