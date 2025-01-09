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

    'vn_pay' => [
        'url' => env('VN_PAY_URL'),
        'tmn_code' => env('VN_PAY_TMN_CODE'),
        'hash_secret' => env('VN_PAY_HASH_SECRET'),
        'return_url' => env('VN_PAY_RETURN_URL'),
        'notify_url' => env('VN_PAY_NOTIFY_URL'),
        'version' => env('VN_PAY_VERSION'),
    ],
];
