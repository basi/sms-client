<?php

declare(strict_types=1);

namespace SmsClient\Exception;

use RuntimeException;

/**
 * SMSクライアントの基底例外クラス
 * すべてのSMS関連エラーに対する統一的なエラーハンドリング
 */
class SmsClientException extends RuntimeException
{
    /**
     * エラーコード定義
     */
    public const ERROR_NETWORK = 1001;
    public const ERROR_AUTH = 1002;
    public const ERROR_VALIDATION = 1003;
    public const ERROR_PROVIDER = 1004;
    public const ERROR_TIMEOUT = 1005;
    public const ERROR_RATE_LIMIT = 1006;

    /**
     * @param string $message エラーメッセージ
     * @param int $code エラーコード
     * @param \Throwable|null $previous 前の例外
     * @param array $context 追加のコンテキスト情報
     */
    public function __construct(
        string $message = "",
        int $code = 0,
        ?\Throwable $previous = null,
        private array $context = []
    ) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * エラーコンテキストを取得
     *
     * @return array コンテキスト情報
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * ネットワークエラーを生成
     *
     * @param string $message エラーメッセージ
     * @param \Throwable|null $previous 前の例外
     * @param array $context 追加のコンテキスト
     *
     * @return self
     */
    public static function networkError(
        string $message,
        ?\Throwable $previous = null,
        array $context = []
    ): self {
        return new self(
            "Network error: $message",
            self::ERROR_NETWORK,
            $previous,
            $context
        );
    }

    /**
     * 認証エラーを生成
     *
     * @param string $message エラーメッセージ
     * @param array $context 追加のコンテキスト
     *
     * @return self
     */
    public static function authenticationError(
        string $message,
        array $context = []
    ): self {
        return new self(
            "Authentication failed: $message",
            self::ERROR_AUTH,
            null,
            $context
        );
    }

    /**
     * バリデーションエラーを生成
     *
     * @param string $message エラーメッセージ
     * @param array $context 追加のコンテキスト
     *
     * @return self
     */
    public static function validationError(
        string $message,
        array $context = []
    ): self {
        return new self(
            "Validation error: $message",
            self::ERROR_VALIDATION,
            null,
            $context
        );
    }

    /**
     * プロバイダーエラーを生成
     *
     * @param string $message エラーメッセージ
     * @param int $statusCode HTTPステータスコード
     * @param string $responseBody レスポンスボディ
     *
     * @return self
     */
    public static function providerError(
        string $message,
        int $statusCode,
        string $responseBody
    ): self {
        return new self(
            "Provider error: $message",
            self::ERROR_PROVIDER,
            null,
            [
                'statusCode' => $statusCode,
                'responseBody' => $responseBody
            ]
        );
    }

    /**
     * タイムアウトエラーを生成
     *
     * @param string $operation 操作名
     * @param int $timeout タイムアウト秒数
     *
     * @return self
     */
    public static function timeoutError(
        string $operation,
        int $timeout
    ): self {
        return new self(
            "Operation '$operation' timed out after {$timeout} seconds",
            self::ERROR_TIMEOUT,
            null,
            [
                'operation' => $operation,
                'timeout' => $timeout
            ]
        );
    }

    /**
     * レート制限エラーを生成
     *
     * @param int $retryAfter 再試行可能になるまでの秒数
     *
     * @return self
     */
    public static function rateLimitError(int $retryAfter = 0): self
    {
        $message = $retryAfter > 0 
            ? "Rate limit exceeded. Retry after {$retryAfter} seconds"
            : "Rate limit exceeded";

        return new self(
            $message,
            self::ERROR_RATE_LIMIT,
            null,
            ['retryAfter' => $retryAfter]
        );
    }
}