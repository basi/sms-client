<?php

declare(strict_types=1);

namespace SmsClient\Exception;

/**
 * SMSクライアントのエラーコード定義（Backed Enum）
 * PHP 8.1+の型安全なエラーコード管理
 */
enum ErrorCode: int
{
    case NETWORK = 1001;
    case AUTH = 1002;
    case VALIDATION = 1003;
    case PROVIDER = 1004;
    case TIMEOUT = 1005;
    case RATE_LIMIT = 1006;

    /**
     * エラーコードの説明を取得
     */
    public function description(): string
    {
        return match($this) {
            self::NETWORK => 'Network error',
            self::AUTH => 'Authentication failed',
            self::VALIDATION => 'Validation error',
            self::PROVIDER => 'Provider error',
            self::TIMEOUT => 'Operation timeout',
            self::RATE_LIMIT => 'Rate limit exceeded',
        };
    }
}
