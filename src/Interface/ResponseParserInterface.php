<?php

declare(strict_types=1);

namespace SmsClient\Interface;

/**
 * プロバイダー固有のレスポンスを配列にパースする
 */
interface ResponseParserInterface
{
    /**
     * 生のレスポンス文字列を配列にパースする
     *
     * @param string $rawResponse プロバイダーからの生のレスポンス
     * @param int $statusCode HTTPステータスコード
     *
     * @return array パースされたレスポンスデータ
     */
    public function parse(string $rawResponse, int $statusCode): array;
}
