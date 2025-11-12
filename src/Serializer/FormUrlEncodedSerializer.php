<?php

declare(strict_types=1);

namespace SmsClient\Serializer;

use SmsClient\Interface\PayloadSerializerInterface;

/**
 * application/x-www-form-urlencoded形式のシリアライザー
 */
class FormUrlEncodedSerializer implements PayloadSerializerInterface
{
    /**
     * データをURL-encoded形式にシリアライズする
     *
     * @param array $data シリアライズするデータ
     *
     * @return string URL-encoded文字列
     */
    public function serialize(array $data): string
    {
        return http_build_query($data, '', '&', PHP_QUERY_RFC3986);
    }

    /**
     * Content-Typeヘッダーの値を取得する
     *
     * @return string Content-Type
     */
    public function getContentType(): string
    {
        return 'application/x-www-form-urlencoded';
    }
}
