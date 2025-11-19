<?php

declare(strict_types=1);

namespace SmsClient\Exception;

use RuntimeException;

/**
 * SMSクライアントの基底例外クラス
 * すべてのSMS関連エラーに対する統一的なエラーハンドリング
 *
 * PHP 8.1+ Backed Enumを使用した型安全なエラーコード管理
 */
class SmsClientException extends RuntimeException
{
    /**
     * @param string $message エラーメッセージ
     * @param ErrorCode $code エラーコード（Enum）
     * @param \Throwable|null $previous 前の例外
     * @param array<string, mixed> $context 追加のコンテキスト情報
     */
    public function __construct(
        string $message = "",
        ErrorCode $code = ErrorCode::PROVIDER,
        ?\Throwable $previous = null,
        private readonly array $context = []
    ) {
        parent::__construct($message, $code->value, $previous);
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
     * @param array<string, mixed> $context 追加のコンテキスト
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
            ErrorCode::NETWORK,
            $previous,
            $context
        );
    }

    /**
     * 認証エラーを生成
     *
     * @param string $message エラーメッセージ
     * @param array<string, mixed> $context 追加のコンテキスト
     *
     * @return self
     */
    public static function authenticationError(
        string $message,
        array $context = []
    ): self {
        return new self(
            "Authentication failed: $message",
            ErrorCode::AUTH,
            null,
            $context
        );
    }

    /**
     * バリデーションエラーを生成
     *
     * @param string $message エラーメッセージ
     * @param array<string, mixed> $context 追加のコンテキスト
     *
     * @return self
     */
    public static function validationError(
        string $message,
        array $context = []
    ): self {
        return new self(
            "Validation error: $message",
            ErrorCode::VALIDATION,
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
            ErrorCode::PROVIDER,
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
            ErrorCode::TIMEOUT,
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
            ErrorCode::RATE_LIMIT,
            null,
            ['retryAfter' => $retryAfter]
        );
    }
}