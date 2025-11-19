<?php

declare(strict_types=1);

namespace SmsClient\Interface;

use SmsClient\Request\CancelRequest;
use SmsClient\Response\CancelResponse;
use SmsClient\Request\SendMessageRequest;
use SmsClient\Response\SendMessageResponse;
use SmsClient\Request\GetReservationsRequest;
use SmsClient\Response\GetReservationsResponse;

/**
 * すべてのSMSプロバイダーが実装すべきインターフェース
 */
interface SmsProviderInterface
{
    /**
     * SMSメッセージを送信（または予約）する
     *
     * @param SendMessageRequest $request メッセージリクエスト
     *
     * @return SendMessageResponse プロバイダーからのレスポンス
     */
    public function sendMessage(SendMessageRequest $request): SendMessageResponse;

    /**
     * 予約済みメッセージの件数や内容を取得する
     *
     * @param GetReservationsRequest $request 検索条件を含むリクエスト
     *
     * @return GetReservationsResponse 予約情報レスポンス
     */
    public function getReservations(GetReservationsRequest $request): GetReservationsResponse;

    /**
     * 予約済みメッセージを一括キャンセルする
     *
     * @param CancelRequest $request キャンセルリクエスト
     *
     * @return CancelResponse キャンセルレスポンス
     */
    public function cancelReservations(CancelRequest $request): CancelResponse;
}
