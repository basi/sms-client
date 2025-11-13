<?php

declare(strict_types=1);

namespace SmsClient\Factory;

use SmsClient\Client;
use SmsClient\Config\ProviderConfig;
use SmsClient\Http\GuzzleHttpClient;
use SmsClient\Interface\HttpClientInterface;
use SmsClient\Interface\RequestTransformerInterface;
use SmsClient\Interface\ResponseParserInterface;
use SmsClient\Interface\SmsProviderInterface;
use SmsClient\Provider\HttpSmsProvider;

/**
 * SMSクライアントのファクトリークラス
 * 依存性注入を管理し、クライアントの生成を簡素化
 */
class SmsClientFactory
{
    /**
     * デフォルト設定でHTTPベースのSMSクライアントを作成
     *
     * @param ProviderConfig $config プロバイダー設定
     * @param RequestTransformerInterface $requestTransformer リクエスト変換器
     * @param ResponseParserInterface $responseParser レスポンスパーサー
     * @param HttpClientInterface|null $httpClient HTTPクライアント（省略時はGuzzle使用）
     * @param array{timeout?: int, verify?: bool, base_uri?: string, headers?: array<string, string>} $httpClientConfig HTTPクライアントの設定
     *
     * @return Client 構成済みのSMSクライアント
     */
    public static function createHttpClient(
        ProviderConfig $config,
        RequestTransformerInterface $requestTransformer,
        ResponseParserInterface $responseParser,
        ?HttpClientInterface $httpClient = null,
        array $httpClientConfig = []
    ): Client {
        // HTTPクライアントが提供されていない場合はデフォルトを使用
        $httpClient ??= new GuzzleHttpClient($httpClientConfig);

        // プロバイダーを作成
        $provider = new HttpSmsProvider(
            config: $config,
            requestTransformer: $requestTransformer,
            responseParser: $responseParser,
            httpClient: $httpClient
        );

        return new Client($provider);
    }

    /**
     * カスタムプロバイダーでSMSクライアントを作成
     *
     * @param SmsProviderInterface $provider SMSプロバイダー実装
     *
     * @return Client 構成済みのSMSクライアント
     */
    public static function createWithProvider(SmsProviderInterface $provider): Client
    {
        return new Client($provider);
    }
}