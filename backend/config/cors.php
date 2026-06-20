<?php

return [

    /*
    | EN: CORS configuration. The frontend normally calls the API through an
    |     nginx reverse-proxy (same origin), but these settings also allow the
    |     Vite dev server to hit the API directly during development.
    | PT: Configuração de CORS. O frontend normalmente chama a API por um
    |     reverse-proxy nginx (mesma origem), mas estas configurações também
    |     permitem que o dev server do Vite acesse a API diretamente em dev.
    */

    'paths' => ['api/*', 'login', 'logout', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => explode(',', (string) env('CORS_ALLOWED_ORIGINS', 'http://localhost:8080,http://localhost:5173')),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
