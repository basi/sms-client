<?php

declare(strict_types=1);

namespace SmsClient;

use SmsClient\Request\CancelRequest;
use SmsClient\Response\CancelResponse;
use SmsClient\Request\SendMessageRequest;
use SmsClient\Response\SendMessageResponse;
use SmsClient\Request\GetReservationsRequest;
use SmsClient\Interface\SmsProviderInterface;
use SmsClient\Response\GetReservationsResponse;

/**
 * SMSクライアントのメインクラス
 */
class Client
{
    /**
     * @param SmsProviderInterface $provider SMSプロバイダー実装
     */
    public function __construct(
        private readonly SmsProviderInterface $provider
    ) {}

    /**
     * SMSメッセージを送信（または予約）する
     *
     * @param SendMessageRequest $request 送信リクエスト
     *
     * @return SendMessageResponse レスポンス
     */
    public function sendMessage(SendMessageRequest $request): SendMessageResponse
    {
        return $this->provider->sendMessage($request);
    }

    /**
     * 予約されたSMSメッセージを取得する
     *
     * @param GetReservationsRequest $request 予約取得リクエスト
     *
     * @return GetReservationsResponse レスポンス
     */
    public function getReservations(GetReservationsRequest $request): GetReservationsResponse
    {
        return $this->provider->getReservations($request);
    }

    /**
     * 予約されたSMSメッセージをキャンセルする
     *
     * @param CancelRequest $request キャンセルリクエスト
     *
     * @return CancelResponse レスポンス
     */
    public function cancelReservations(CancelRequest $request): CancelResponse
    {
        return $this->provider->cancelReservations($request);
    }
}
