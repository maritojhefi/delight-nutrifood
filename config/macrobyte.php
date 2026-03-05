<?php

return [
    'open_ai' => [
        'chat_endpoint' => env('OPEN_AI_CHAT_ENDPOINT', '/v1/chat/completions'),
        'api_key' => env('OPENAI_API_KEY'),
        'model' => env('OPEN_AI_MODEL', 'gpt-4o-mini'),
        'base_url' => env('OPEN_AI_URL', 'https://api.openai.com')
    ],
    'deepseek_ia' => [
        'chat_endpoint' => env('DEEPSEEK_CHAT_ENDPOINT', '/chat/completions'),
        'api_key' => env('DEEPSEEK_API_KEY'),
        'model' => env('DEEPSEEK_MODEL', 'deepseek-chat'),
        'base_url' => env('DEEPSEEK_URL', 'https://api.deepseek.com')
    ]
];
