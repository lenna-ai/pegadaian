<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://pegadaian.lenna.ai',
        'localhost:3000',
        'localhost:*',
        'http://localhost:3000',
        'http://localhost:3002',
        'localhost:3002',
        'https://pegadaian.lenna.ai',
        'pegadaian.lenna.ai',
        'pegadaian-api.lenna.ai',
        'https://pegadaian.lenna.ai',
        'https://pegadaian.lenna.ai',
        'https://pegadaian.lenna.ai:8012',
        'http://localhost:5173'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
