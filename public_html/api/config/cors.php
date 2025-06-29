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

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => [
        // Development
        'http://localhost:3000',
        'http://localhost:5173',
        'http://127.0.0.1:3000',
        'http://127.0.0.1:5173',

        // Production domains - Solo Hostinger
        'https://vincenzorocca.com',
        'https://www.vincenzorocca.com',
    ],

    'allowed_origins_patterns' => [
        // Vercel patterns
        '#^https://[a-z0-9\-]+\.vercel\.app$#',
        '#^https://[a-z0-9\-]+-[a-z0-9\-]+\.vercel\.app$#',

        // Hostinger patterns
        '#^https://[a-z0-9\-]+\.hostinger\.com$#',
        '#^https://[a-z0-9\-]+\.000webhostapp\.com$#',
    ],

    'allowed_headers' => [
        'Content-Type',
        'Authorization',
        'X-Requested-With',
        'Accept',
        'Origin',
        'Access-Control-Request-Method',
        'Access-Control-Request-Headers',
        'X-CSRF-TOKEN',
        'X-XSRF-TOKEN'
    ],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
