<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Claude AI Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi untuk integrasi dengan Anthropic Claude API
    |
    */

    'api_key' => env('CLAUDE_API_KEY'),
    
    // FIXED: Gunakan model yang valid
    'model' => env('CLAUDE_MODEL', 'claude-3-5-sonnet-20241022'),
    
    // Available models:
    // - claude-3-5-sonnet-20241022 (recommended - fast, smart, cost-effective)
    // - claude-sonnet-4-20250514 (newest, most capable)
    // - claude-3-opus-20240229 (most powerful, higher cost)
    
    'max_tokens' => (int) env('CLAUDE_MAX_TOKENS', 4096),
    
    'timeout' => (int) env('CLAUDE_TIMEOUT', 300), // 5 minutes for complex analysis
    
    // Cache AI results to save API calls
    'cache_enabled' => env('CLAUDE_CACHE_ENABLED', true),
    'cache_ttl' => env('CLAUDE_CACHE_TTL', 86400), // 24 hours
];