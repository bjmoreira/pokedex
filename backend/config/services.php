<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services / Serviços de Terceiros
    |--------------------------------------------------------------------------
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    | EN: PokeAPI upstream configuration (base URL + cache lifetime).
    | PT: Configuração da PokeAPI (URL base + tempo de cache).
    */
    'pokeapi' => [
        'base_url' => env('POKEAPI_BASE_URL', 'https://pokeapi.co/api/v2'),
        'cache_ttl' => (int) env('POKEAPI_CACHE_TTL', 86400),
    ],

];
