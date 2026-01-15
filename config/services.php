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
    'midtrans' => [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),

    ],

    
    'anthropic' => [
        'api_key' => env('ANTHROPIC_API_KEY') ?: env('CLAUDE_API_KEY'),
        'base_url' => env('ANTHROPIC_BASE_URL', 'https://api.anthropic.com/v1/messages'),
        
        // FIXED: Use valid model name
        'model' => env('ANTHROPIC_MODEL', 'claude-3-5-sonnet-20240620'),
        
        // Valid models:
        // - claude-3-5-sonnet-20240620 (recommended - stable, proven)
        // - claude-sonnet-4-5-20250929 (newest - most capable)
        // - claude-3-opus-20240229 (most powerful Claude 3)
        // - claude-3-haiku-20240307 (fastest, cheapest)
        
        'max_tokens' => (int) env('ANTHROPIC_MAX_TOKENS', 4096),
        'timeout' => (int) env('ANTHROPIC_TIMEOUT', 300),
    ],

];
