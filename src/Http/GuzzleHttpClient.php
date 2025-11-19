<?php

declare(strict_types=1);

namespace SmsClient\Http;

use RuntimeException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use SmsClient\Interface\HttpClientInterface;

/**
 * Guzzleを使用したHTTPクライアント実装
 */
class GuzzleHttpClient implements HttpClientInterface
{
    private Client $client;

    /**
     * @param array{timeout?: int, verify?: bool, base_uri?: string, headers?: array<string, string>} $config Guzzleクライアントの設定オプション
     */
    public function __construct(array $config = [])
    {
        // HTTPS通信の強制：SSL/TLS証明書の検証を必須に
        // セキュリティのため、verify => true を強制
        $config['verify'] = true;

        $this->client = new Client($config);
    }

    /**
     * POSTリクエストを送信する
     *
     * @param string $url リクエストURL
     * @param string $body リクエストボディ
     * @param array $headers HTTPヘッダー
     *
     * @return HttpResponse レスポンス
     *
     * @throws RuntimeException HTTPリクエストが失敗した場合
     */
    public function post(string $url, string $body, array $headers): HttpResponse
    {
        try {
            $response = $this->client->post($url, [
                'body' => $body,
                'headers' => $headers,
            ]);

            return new HttpResponse(
                statusCode: $response->getStatusCode(),
                body: (string) $response->getBody(),
                headers: $response->getHeaders()
            );
        } catch (GuzzleException $e) {
            throw new RuntimeException("HTTP request failed: {$e->getMessage()}", 0, $e);
        }
    }

    /**
     * GETリクエストを送信する
     *
     * @param string $url リクエストURL
     * @param array $headers HTTPヘッダー
     *
     * @return HttpResponse レスポンス
     *
     * @throws RuntimeException HTTPリクエストが失敗した場合
     */
    public function get(string $url, array $headers): HttpResponse
    {
        try {
            $response = $this->client->get($url, [
                'headers' => $headers,
            ]);

            return new HttpResponse(
                statusCode: $response->getStatusCode(),
                body: (string) $response->getBody(),
                headers: $response->getHeaders()
            );
        } catch (GuzzleException $e) {
            throw new RuntimeException("HTTP request failed: {$e->getMessage()}", 0, $e);
        }
    }
}
