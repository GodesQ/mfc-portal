<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'paymaya' => [
        'url' => 'https://pg.maya.ph/checkout/v1/checkouts',
        'test_url' => 'https://pg-sandbox.paymaya.com/checkout/v1/checkouts',
        'api_key' => env('PAYMAYA_API_KEY'),
        'secret_key' => env('PAYMAYA_SECRET_KEY'),
        'test_api_key' => env('PAYMAYA_TEST_API_KEY'),
        'test_secret_key' => env('PAYMAYA_TEST_SECRET_KEY')
    ]

];
