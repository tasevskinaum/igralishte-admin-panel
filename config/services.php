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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
        'client_id' => '1096899408185258',
        'client_secret' => '4050b89f798247276aa93692a9e6b29b',
        'redirect' => 'http://localhost:8000/api/auth/facebook/callback'
    ],

    'google' => [
        'client_id' => '98901906513-l249tck2eie5ko31v2k7tuolu8bnr59i.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'http://127.0.0.1:8000/api/auth/google/callback'
    ],

];
