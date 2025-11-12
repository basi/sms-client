<?php

declare(strict_types=1);

namespace SmsClient\Interface;

/**
 * ペイロードをシリアライズするインターフェース
 */
interface PayloadSerializerInterface
{
    /**
     * データ配列をシリアライズする
     *
     * @param array $data シリアライズするデータ
     *
     * @return string シリアライズされた文字列
     */
    public function serialize(array $data): string;

    /**
     * Content-Typeヘッダーの値を取得する
     *
     * @return string Content-Type
     */
    public function getContentType(): string;
}
