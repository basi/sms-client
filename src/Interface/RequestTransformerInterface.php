<?php

declare(strict_types=1);

namespace SmsClient\Interface;

/**
 * 抽象リクエストをプロバイダー固有のパラメータ配列に変換する
 */
interface RequestTransformerInterface
{
    /**
     * リクエストオブジェクトをプロバイダー固有の配列に変換する
     *
     * @param object $request リクエストオブジェクト（例：SendMessageRequest）
     *
     * @return array 変換されたパラメータの連想配列
     */
    public function transform(object $request): array;
}
