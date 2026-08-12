<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AI Providers
    |--------------------------------------------------------------------------
    | Each provider: label, env key name, API base URL, available models,
    | and default model. Keys may also be stored per-user in ai_settings
    | (encrypted); env is fallback for global / legacy config.
    */

    'providers' => [

        'openai' => [
            'label'      => 'OpenAI',
            'key_env'    => 'OPENAI_API_KEY',
            'key_column' => 'openai_key',
            'model_column' => 'openai_model',
            'base_url'   => 'https://api.openai.com/v1/chat/completions',
            'type'       => 'openai',
            'models'     => [
                // Frontier — August 2026 (latest)
                'gpt-5.6-sol'          => 'GPT-5.6 Sol — flagship reasoning + coding',
                'gpt-5.6-terra'        => 'GPT-5.6 Terra — balanced intelligence/cost',
                'gpt-5.6-luna'         => 'GPT-5.6 Luna — high-volume / cost-optimized',
                'gpt-5'                => 'GPT-5',
                'gpt-5-mini'           => 'GPT-5 mini',
                'gpt-5-nano'           => 'GPT-5 nano',
                'gpt-4.1'              => 'GPT-4.1',
                'gpt-4.1-mini'         => 'GPT-4.1 mini',
                'o3'                   => 'o3 (reasoning)',
                'o4-mini'              => 'o4-mini (reasoning)',
                'gpt-4o'               => 'GPT-4o',
                'gpt-4o-mini'          => 'GPT-4o mini',
            ],
            'default_model' => 'gpt-5.6-terra',
        ],

        'gemini' => [
            'label'      => 'Gemini',
            'key_env'    => 'GEMINI_API_KEY',
            'key_column' => 'gemini_key',
            'model_column' => 'gemini_model',
            'base_url'   => 'https://generativelanguage.googleapis.com/v1beta/models',
            'type'       => 'gemini',
            'models'     => [
                // Stable — verified Aug 5 2026 from ai.google.dev/gemini-api/docs/models
                'gemini-3.6-flash'      => 'Gemini 3.6 Flash — latest stable (flagship)',
                'gemini-3.5-flash'      => 'Gemini 3.5 Flash — frontier coding/agent',
                'gemini-3.5-flash-lite' => 'Gemini 3.5 Flash-Lite — high-throughput',
                'gemini-3.1-flash-lite' => 'Gemini 3.1 Flash-Lite — most cost-efficient',
                'gemini-2.5-pro'        => 'Gemini 2.5 Pro — deep reasoning',
                'gemini-2.5-flash'      => 'Gemini 2.5 Flash — price/performance',
                'gemini-2.5-flash-lite' => 'Gemini 2.5 Flash-Lite',
            ],
            'default_model' => 'gemini-3.6-flash',
        ],

        'anthropic' => [
            'label'      => 'Claude',
            'key_env'    => 'ANTHROPIC_API_KEY',
            'key_column' => 'anthropic_key',
            'model_column' => 'anthropic_model',
            'base_url'   => 'https://api.anthropic.com/v1/messages',
            'type'       => 'anthropic',
            'models'     => [
                // Verified from platform.claude.com — June 2026
                'claude-fable-5'       => 'Claude Fable 5 — next-gen long-running agents',
                'claude-opus-5'        => 'Claude Opus 5 — complex agentic coding',
                'claude-sonnet-5'      => 'Claude Sonnet 5 — best speed + intelligence',
                'claude-haiku-4-5'     => 'Claude Haiku 4.5 — fastest, near-frontier',
                'claude-opus-4-8'      => 'Claude Opus 4.8 (legacy)',
                'claude-opus-4-7'      => 'Claude Opus 4.7 (legacy)',
                'claude-sonnet-4-6'    => 'Claude Sonnet 4.6 (legacy)',
            ],
            'default_model' => 'claude-sonnet-5',
        ],

        'deepseek' => [
            'label'      => 'DeepSeek',
            'key_env'    => 'DEEPSEEK_API_KEY',
            'key_column' => 'deepseek_key',
            'model_column' => 'deepseek_model',
            'base_url'   => 'https://api.deepseek.com/v1/chat/completions',
            'type'       => 'openai',
            'models'     => [
                // Current — api-docs.deepseek.com Aug 2026
                'deepseek-v4-pro'   => 'DeepSeek V4 Pro (deepseek-v4-pro)',
                'deepseek-v4-flash' => 'DeepSeek V4 Flash (deepseek-v4-flash)',
                'deepseek-chat'     => 'DeepSeek V3 — legacy (deepseek-chat)',
                'deepseek-reasoner' => 'DeepSeek R1 — legacy (deepseek-reasoner)',
            ],
            'default_model' => 'deepseek-v4-flash',
        ],

        'meta' => [
            'label'      => 'Meta',
            'key_env'    => 'META_API_KEY',
            'key_column' => 'meta_key',
            'model_column' => 'meta_model',
            'base_url'   => 'https://api.llama.com/v1/chat/completions',
            'type'       => 'openai',
            'models'     => [
                // Muse Spark (you are using this) + latest Llama 4/3
                'muse-spark-1.2'                         => 'Muse Spark 1.2 (Contributor) — active',
                'Llama-4-Maverick-17B-128E-Instruct-FP8' => 'Llama 4 Maverick 17B',
                'Llama-4-Scout-17B-16E-Instruct-FP8'     => 'Llama 4 Scout 17B',
                'Llama-3.3-70B-Instruct'                => 'Llama 3.3 70B',
                'Llama-3.1-405B-Instruct'               => 'Llama 3.1 405B',
            ],
            'default_model' => 'muse-spark-1.2',
            'alt_base'   => 'https://api.llama-api.com/chat/completions',
        ],
    ],

    // Fallback defaults when DB setting absent
    'default_provider' => env('AI_DEFAULT_PROVIDER', env('AI_PROVIDER', 'openai')),
    'default_model'    => env('AI_DEFAULT_MODEL'),
];
