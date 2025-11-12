<?php

declare(strict_types=1);

namespace SmsClient\Provider;

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

/**
 * HTTP経由で動作する汎用SMSプロバイダー実装
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
     * SMSメッセージを送信（または予約）する
     *
     * @param SendMessageRequest $request メッセージリクエスト
     *
     * @return SendMessageResponse プロバイダーからのレスポンス
     */
    public function sendMessage(SendMessageRequest $request): SendMessageResponse
    {
        // リクエストをプロバイダー固有の形式に変換
        $payload = $this->requestTransformer->transform($request);

        // 認証情報をペイロードに適用（必要な場合）
        $payload = $this->config->authStrategy->applyToPayload($payload);

        // ペイロードをシリアライズ
        $body = $this->config->serializer->serialize($payload);

        // ヘッダーを準備
        $headers = $this->config->defaultHeaders;
        $headers['Content-Type'] = $this->config->serializer->getContentType();
        $headers = $this->config->authStrategy->applyToHeaders($headers);

        // HTTPリクエスト実行
        $httpResponse = $this->httpClient->post(
            $this->config->baseUrl . $this->config->sendEndpoint,
            $body,
            $headers
        );

        // レスポンスをパース
        $parsedData = $this->responseParser->parse(
            $httpResponse->body,
            $httpResponse->statusCode
        );

        // SendMessageResponseオブジェクトを構築
        return new SendMessageResponse(
            success: $parsedData['success'] ?? $httpResponse->isSuccessful(),
            messageId: $parsedData['messageId'] ?? null,
            errorMessage: $parsedData['errorMessage'] ?? null,
            rawResponse: $parsedData
        );
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
        // リクエストをプロバイダー固有の形式に変換
        $payload = $this->requestTransformer->transform($request);

        // 認証情報をペイロードに適用（必要な場合）
        $payload = $this->config->authStrategy->applyToPayload($payload);

        // GETリクエストの場合はクエリパラメータに変換
        $queryString = http_build_query($payload);
        $url = $this->config->baseUrl . $this->config->getReservationsEndpoint;
        if ($queryString !== '') {
            $url .= '?' . $queryString;
        }

        // ヘッダーを準備
        $headers = $this->config->defaultHeaders;
        $headers = $this->config->authStrategy->applyToHeaders($headers);

        // HTTPリクエスト実行
        $httpResponse = $this->httpClient->get($url, $headers);

        // レスポンスをパース
        $parsedData = $this->responseParser->parse(
            $httpResponse->body,
            $httpResponse->statusCode
        );

        // GetReservationsResponseオブジェクトを構築
        return new GetReservationsResponse(
            success: $parsedData['success'] ?? $httpResponse->isSuccessful(),
            count: $parsedData['count'] ?? 0,
            reservations: $parsedData['reservations'] ?? [],
            errorMessage: $parsedData['errorMessage'] ?? null,
            rawResponse: $parsedData
        );
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
        // リクエストをプロバイダー固有の形式に変換
        $payload = $this->requestTransformer->transform($request);

        // 認証情報をペイロードに適用（必要な場合）
        $payload = $this->config->authStrategy->applyToPayload($payload);

        // GETリクエストの場合はクエリパラメータに変換
        $queryString = http_build_query($payload);
        $url = $this->config->baseUrl . $this->config->cancelEndpoint;
        if ($queryString !== '') {
            $url .= '?' . $queryString;
        }

        // ヘッダーを準備
        $headers = $this->config->defaultHeaders;
        $headers = $this->config->authStrategy->applyToHeaders($headers);

        // HTTPリクエスト実行
        $httpResponse = $this->httpClient->get($url, $headers);

        // レスポンスをパース
        $parsedData = $this->responseParser->parse(
            $httpResponse->body,
            $httpResponse->statusCode
        );

        // CancelResponseオブジェクトを構築
        return new CancelResponse(
            success: $parsedData['success'] ?? $httpResponse->isSuccessful(),
            canceledCount: $parsedData['canceledCount'] ?? 0,
            canceledMessageIds: $parsedData['canceledMessageIds'] ?? [],
            errorMessage: $parsedData['errorMessage'] ?? null,
            rawResponse: $parsedData
        );
    }
}
