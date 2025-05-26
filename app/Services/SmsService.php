<?php

namespace App\Services;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function send($payload)
    {
        $url = config('services.sms.url') . '/otp';
        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ])->post($url, $payload);

        if (!$response->ok()) {
            $content = json_decode($response->getBody()->getContents());
            Log::error("sms-api-error", $content);
            throw new Exception($content->error . ' in Semaphore Service.', $response->getStatusCode());
        }

        $responseData = json_decode($response->getBody(), true);
        return $responseData;
    }

    public function request_model($user, $otp)
    {
        return [
            'apikey' => config('services.sms.api_key'),
            'sendername' => config('services.sms.sender_name'),
            'number' => $user->country_code . $user->contact_number,
            'code' => $otp->otp_code,
            'message' => 'Your verification code is {otp}. Please enter this OTP to verify your contact number. Do not share this code with anyone.',
        ];
    }
}
