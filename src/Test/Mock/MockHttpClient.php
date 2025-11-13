<?php

declare(strict_types=1);

namespace SmsClient\Test\Mock;

use SmsClient\Http\HttpResponse;
use SmsClient\Interface\HttpClientInterface;

/**
 * テスト用のHTTPクライアントモック
 * レスポンスをプリセットして、実際のHTTP通信なしにテスト可能
 */
class MockHttpClient implements HttpClientInterface
{
    /**
     * @var array リクエストの履歴
     */
    private array $requestHistory = [];

    /**
     * @var array プリセットされたレスポンス
     */
    private array $responses = [];

    /**
     * @var int 現在のレスポンスインデックス
     */
    private int $responseIndex = 0;

    /**
     * レスポンスを追加する
     *
     * @param int $statusCode HTTPステータスコード
     * @param string $body レスポンスボディ
     * @param array $headers レスポンスヘッダー
     *
     * @return self
     */
    public function addResponse(
        int $statusCode,
        string $body,
        array $headers = []
    ): self {
        $this->responses[] = new HttpResponse($statusCode, $body, $headers);
        return $this;
    }

    /**
     * POSTリクエストを送信する（モック）
     *
     * @param string $url リクエストURL
     * @param string $body リクエストボディ
     * @param array $headers HTTPヘッダー
     *
     * @return HttpResponse レスポンス
     *
     * @throws \RuntimeException レスポンスが設定されていない場合
     */
    public function post(string $url, string $body, array $headers): HttpResponse
    {
        // リクエストを記録
        $this->requestHistory[] = [
            'method' => 'POST',
            'url' => $url,
            'body' => $body,
            'headers' => $headers,
            'timestamp' => time()
        ];

        return $this->getNextResponse();
    }

    /**
     * GETリクエストを送信する（モック）
     *
     * @param string $url リクエストURL
     * @param array $headers HTTPヘッダー
     *
     * @return HttpResponse レスポンス
     *
     * @throws \RuntimeException レスポンスが設定されていない場合
     */
    public function get(string $url, array $headers): HttpResponse
    {
        // リクエストを記録
        $this->requestHistory[] = [
            'method' => 'GET',
            'url' => $url,
            'body' => null,
            'headers' => $headers,
            'timestamp' => time()
        ];

        return $this->getNextResponse();
    }

    /**
     * 次のレスポンスを取得する
     *
     * @return HttpResponse
     *
     * @throws \RuntimeException レスポンスが設定されていない場合
     */
    private function getNextResponse(): HttpResponse
    {
        if (!isset($this->responses[$this->responseIndex])) {
            throw new \RuntimeException(
                'No more mock responses available. Add responses using addResponse() method.'
            );
        }

        $response = $this->responses[$this->responseIndex];
        $this->responseIndex++;

        return $response;
    }

    /**
     * リクエスト履歴を取得する
     *
     * @return array リクエストの配列
     */
    public function getRequestHistory(): array
    {
        return $this->requestHistory;
    }

    /**
     * 最後のリクエストを取得する
     *
     * @return array|null 最後のリクエスト、またはnull
     */
    public function getLastRequest(): ?array
    {
        if (empty($this->requestHistory)) {
            return null;
        }

        return end($this->requestHistory);
    }

    /**
     * 指定されたメソッドのリクエスト数を取得する
     *
     * @param string $method HTTPメソッド
     *
     * @return int リクエスト数
     */
    public function getRequestCount(string $method = ''): int
    {
        if ($method === '') {
            return count($this->requestHistory);
        }

        return count(array_filter(
            $this->requestHistory,
            fn($request) => $request['method'] === strtoupper($method)
        ));
    }

    /**
     * リクエスト履歴をリセットする
     *
     * @return self
     */
    public function resetHistory(): self
    {
        $this->requestHistory = [];
        return $this;
    }

    /**
     * レスポンスとインデックスをリセットする
     *
     * @return self
     */
    public function resetResponses(): self
    {
        $this->responses = [];
        $this->responseIndex = 0;
        return $this;
    }

    /**
     * すべてをリセットする
     *
     * @return self
     */
    public function reset(): self
    {
        return $this->resetHistory()->resetResponses();
    }

    /**
     * 指定されたURLへのリクエストがあったかを確認する
     *
     * @param string $url URL
     *
     * @return bool
     */
    public function hasRequestedUrl(string $url): bool
    {
        foreach ($this->requestHistory as $request) {
            if ($request['url'] === $url) {
                return true;
            }
        }
        return false;
    }

    /**
     * 指定されたヘッダーでリクエストがあったかを確認する
     *
     * @param string $headerName ヘッダー名
     * @param string $headerValue ヘッダー値
     *
     * @return bool
     */
    public function hasRequestedWithHeader(string $headerName, string $headerValue): bool
    {
        foreach ($this->requestHistory as $request) {
            if (isset($request['headers'][$headerName]) && 
                $request['headers'][$headerName] === $headerValue) {
                return true;
            }
        }
        return false;
    }
}