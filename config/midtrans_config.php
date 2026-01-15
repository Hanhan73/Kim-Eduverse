<?php

/**
 * MIDTRANS CONFIGURATION
 * 
 * Add this configuration to your config/services.php file
 */

return [
    // ... existing services ...

    'midtrans' => [
        'merchant_id' => env('MIDTRANS_MERCHANT_ID'),
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
        'server_key' => env('MIDTRANS_SERVER_KEY'),
        'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
        'is_sanitized' => true,
        'is_3ds' => true,
    ],
];

/**
 * Add these variables to your .env file:
 * 
 * MIDTRANS_MERCHANT_ID=your_merchant_id
 * MIDTRANS_CLIENT_KEY=your_client_key  
 * MIDTRANS_SERVER_KEY=your_server_key
 * MIDTRANS_IS_PRODUCTION=false
 * 
 * Get your Midtrans credentials from:
 * Sandbox: https://dashboard.sandbox.midtrans.com/
 * Production: https://dashboard.midtrans.com/
 */
