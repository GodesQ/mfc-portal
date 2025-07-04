<?php

namespace App\Services;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Http;

class PaymayaService
{
    public function __construct()
    {

    }

    public function pay($request_model)
    {
        try {
            $authToken = config('app.env') !== "production" ? base64_encode(config("services.paymaya.test_api_key") . ":") : base64_encode(config("services.paymaya.api_key") . ":");
            $url = config('app.env') !== "production" ? config("services.paymaya.test_url") : config("services.paymaya.url");

            $response = Http::withHeaders([
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'Authorization' => 'Basic ' . $authToken,
            ])->post($url, $request_model);

            $statusCode = $response->getStatusCode();

            if ($statusCode != 200) {
                $content = json_decode($response->getBody()->getContents());
                throw new Exception($content->error . ' in Paymaya Payment Gateway.');
            }

            $responseData = json_decode($response->getBody(), true);

            return $responseData;

        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function createRequestModel($transaction, $paymaya_user_details)
    {
        $total_amount = $transaction->total_amount;

        return [
            "authorizationType" => "NORMAL",
            "totalAmount" => [
                "value" => $total_amount,
                "currency" => "PHP",
            ],
            "buyer" => [
                "firstName" => $paymaya_user_details['firstname'] ?? '',
                "lastName" => $paymaya_user_details['lastname'] ?? '',
            ],
            "redirectUrl" => [
                "success" => route('payments.success') . "?transaction_id=" . $transaction->transaction_code,
                "failed" => route('payments.failed') . "?transaction_id=" . $transaction->transaction_code,
                "canceled" => route('payments.cancelled') . "?transaction_id=" . $transaction->transaction_code,

            ],
            "requestReferenceNumber" => $transaction->reference_code,
        ];
    }



}
