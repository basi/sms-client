<?php

declare(strict_types=1);

namespace SmsClient\Provider;

use RuntimeException;
use SmsClient\Interface\SmsProviderInterface;
use SmsClient\Interface\HttpClientInterface;
use SmsClient\Interface\RequestTransformerInterface;
use SmsClient\Interface\ResponseParserInterface;
use SmsClient\Config\ProviderConfig;
use SmsClient\Request\SendMessageRequest;
use SmsClient\Request\GetReservationsRequest;
use SmsClient\Request\CancelRequest;
use SmsClient\Response\SendMessageResponse;
use SmsClient\Response\GetReservationsResponse;
use SmsClient\Response\CancelResponse;
use SmsClient\Http\HttpResponse;

/**
 * HTTP経由で動作する汎用SMSプロバイダー実装
 * DRY原則に基づき、共通処理を抽出してリファクタリング
 */
class HttpSmsProvider implements SmsProviderInterface
{
    /**
     * @param ProviderConfig $config プロバイダー設定
     * @param RequestTransformerInterface $requestTransformer リクエスト変換器
     * @param ResponseParserInterface $responseParser レスポンスパーサー
     * @param HttpClientInterface $httpClient HTTPクライアント
     */
    public function __construct(
        private readonly ProviderConfig $config,
        private readonly RequestTransformerInterface $requestTransformer,
        private readonly ResponseParserInterface $responseParser,
        private readonly HttpClientInterface $httpClient
    ) {}

    /**
     * 共通のリクエスト準備処理
     *
     * @param object $request リクエストオブジェクト
     *
     * @return array<string, mixed> 認証情報が適用されたペイロード
     */
    private function preparePayload(object $request): array
    {
        // リクエストをプロバイダー固有の形式に変換
        $payload = $this->requestTransformer->transform($request);

        // 認証情報をペイロードに適用（必要な場合）
        return $this->config->authStrategy->applyToPayload($payload);
    }

    /**
     * 共通のヘッダー準備処理
     *
     * @param string|null $contentType Content-Type（POSTリクエスト時）
     *
     * @return array<string, string> 認証情報が適用されたヘッダー
     */
    private function prepareHeaders(?string $contentType = null): array
    {
        $headers = $this->config->defaultHeaders;

        if ($contentType !== null) {
            $headers['Content-Type'] = $contentType;
        }

        return $this->config->authStrategy->applyToHeaders($headers);
    }

    /**
     * HTTPレスポンスの検証とエラーハンドリング
     *
     * @param HttpResponse $response HTTPレスポンス
     * @param string $operation 操作名（エラーメッセージ用）
     *
     * @throws RuntimeException レスポンスがエラーの場合
     */
    private function validateResponse(HttpResponse $response, string $operation): void
    {
        if (!$response->isSuccessful()) {
            throw new RuntimeException(
                sprintf(
                    '%s failed with status code %d: %s',
                    $operation,
                    $response->statusCode,
                    $response->body
                )
            );
        }
    }

    /**
     * レスポンスの共通パース処理
     *
     * @param HttpResponse $httpResponse HTTPレスポンス
     * @param object $request 元のリクエスト
     * @param string $operation 操作名
     *
     * @return array<string, mixed> パースされたデータ
     */
    private function parseResponse(HttpResponse $httpResponse, object $request, string $operation): array
    {
        // エラーチェック
        $this->validateResponse($httpResponse, $operation);

        // レスポンスをパース
        return $this->responseParser->parse(
            $httpResponse->body,
            $httpResponse->statusCode,
            $request
        );
    }

    /**
     * SMSメッセージを送信（または予約）する
     *
     * @param SendMessageRequest $request メッセージリクエスト
     *
     * @return SendMessageResponse プロバイダーからのレスポンス
     */
    public function sendMessage(SendMessageRequest $request): SendMessageResponse
    {
        try {
            // ペイロードとヘッダーを準備
            $payload = $this->preparePayload($request);
            $body = $this->config->serializer->serialize($payload);
            $headers = $this->prepareHeaders($this->config->serializer->getContentType());

            // HTTPリクエスト実行
            $httpResponse = $this->httpClient->post(
                $this->config->baseUrl . $this->config->sendEndpoint,
                $body,
                $headers
            );

            // レスポンスをパース
            $parsedData = $this->parseResponse($httpResponse, $request, 'Send message');

            // SendMessageResponseオブジェクトを構築
            return new SendMessageResponse(
                success: $parsedData['success'] ?? $httpResponse->isSuccessful(),
                messageId: $parsedData['messageId'] ?? null,
                errorMessage: $parsedData['errorMessage'] ?? null,
                rawResponse: $parsedData
            );
        } catch (RuntimeException $e) {
            // エラーレスポンスを返す
            return new SendMessageResponse(
                success: false,
                messageId: null,
                errorMessage: $e->getMessage(),
                rawResponse: []
            );
        }
    }

    /**
     * 予約済みメッセージの件数や内容を取得する
     *
     * @param GetReservationsRequest $request 検索条件を含むリクエスト
     *
     * @return GetReservationsResponse 予約情報レスポンス
     */
    public function getReservations(GetReservationsRequest $request): GetReservationsResponse
    {
        try {
            // GETリクエストでは認証情報をペイロード（URLパラメータ）に含めない
            // AuthStrategyの認証情報はヘッダーで送信
            $payload = $this->requestTransformer->transform($request);
            $url = $this->buildGetUrl($this->config->getReservationsEndpoint, $payload);
            $headers = $this->prepareHeaders();

            // HTTPリクエスト実行
            $httpResponse = $this->httpClient->get($url, $headers);

            // レスポンスをパース
            $parsedData = $this->parseResponse($httpResponse, $request, 'Get reservations');

            // GetReservationsResponseオブジェクトを構築
            return new GetReservationsResponse(
                success: $parsedData['success'] ?? $httpResponse->isSuccessful(),
                count: $parsedData['count'] ?? 0,
                reservations: $parsedData['reservations'] ?? [],
                errorMessage: $parsedData['errorMessage'] ?? null,
                rawResponse: $parsedData
            );
        } catch (RuntimeException $e) {
            // エラーレスポンスを返す
            return new GetReservationsResponse(
                success: false,
                count: 0,
                reservations: [],
                errorMessage: $e->getMessage(),
                rawResponse: []
            );
        }
    }

    /**
     * 予約済みメッセージを一括キャンセルする
     *
     * @param CancelRequest $request キャンセルリクエスト
     *
     * @return CancelResponse キャンセルレスポンス
     */
    public function cancelReservations(CancelRequest $request): CancelResponse
    {
        try {
            // GETリクエストでは認証情報をペイロード（URLパラメータ）に含めない
            // AuthStrategyの認証情報はヘッダーで送信
            $payload = $this->requestTransformer->transform($request);
            $url = $this->buildGetUrl($this->config->cancelEndpoint, $payload);
            $headers = $this->prepareHeaders();

            // HTTPリクエスト実行
            $httpResponse = $this->httpClient->get($url, $headers);

            // レスポンスをパース
            $parsedData = $this->parseResponse($httpResponse, $request, 'Cancel reservations');

            // CancelResponseオブジェクトを構築
            return new CancelResponse(
                success: $parsedData['success'] ?? $httpResponse->isSuccessful(),
                canceledCount: $parsedData['canceledCount'] ?? 0,
                canceledMessageIds: $parsedData['canceledMessageIds'] ?? [],
                errorMessage: $parsedData['errorMessage'] ?? null,
                rawResponse: $parsedData
            );
        } catch (RuntimeException $e) {
            // エラーレスポンスを返す
            return new CancelResponse(
                success: false,
                canceledCount: 0,
                canceledMessageIds: [],
                errorMessage: $e->getMessage(),
                rawResponse: []
            );
        }
    }

    /**
     * GETリクエスト用のURLを構築
     *
     * @param string $endpoint エンドポイント
     * @param array<string, mixed> $params クエリパラメータ
     *
     * @return string 完全なURL
     */
    private function buildGetUrl(string $endpoint, array $params): string
    {
        $url = $this->config->baseUrl . $endpoint;
        $queryString = http_build_query($params);

        if ($queryString !== '') {
            $url .= '?' . $queryString;
        }

        return $url;
    }
}
