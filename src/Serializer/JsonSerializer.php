<?php

declare(strict_types=1);

namespace SmsClient\Serializer;

use JsonException;
use SmsClient\Interface\PayloadSerializerInterface;

/**
 * application/json形式のシリアライザー
 */
class JsonSerializer implements PayloadSerializerInterface
{
    /**
     * データをJSON形式にシリアライズする
     *
     * @param array $data シリアライズするデータ
     *
     * @return string JSON文字列
     *
     * @throws JsonException JSON変換に失敗した場合
     */
    public function serialize(array $data): string
    {
        return json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Content-Typeヘッダーの値を取得する
     *
     * @return string Content-Type
     */
    public function getContentType(): string
    {
        return 'application/json';
    }
}
