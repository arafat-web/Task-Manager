<?php

namespace App\Services;

use App\Models\AiSetting;
use Illuminate\Support\Facades\Auth;

class AiProviderService
{
    public function allProviders(): array
    {
        return config('ai.providers', []);
    }

    public function providerIds(): array
    {
        return array_keys($this->allProviders());
    }

    public function providerConfig(string $provider): ?array
    {
        return config("ai.providers.{$provider}");
    }

    /**
     * Get the stored AiSetting for user (or null).
     */
    public function setting($user): ?AiSetting
    {
        if (!$user) return null;
        return AiSetting::where('user_id', $user->id)->first();
    }

    /**
     * Does this provider have an API key? Checks encrypted DB column then env fallback.
     */
    public function hasKey($user, string $provider): bool
    {
        return (bool) $this->getKey($user, $provider);
    }

    public function getKey($user, string $provider): ?string
    {
        $cfg = $this->providerConfig($provider);
        if (!$cfg) return null;

        // DB first (encrypted)
        $setting = $this->setting($user);
        $col = $cfg['key_column'] ?? null;
        if ($setting && $col && !empty($setting->{$col})) {
            return $setting->{$col};
        }

        // Env fallback (global)
        $envKey = $cfg['key_env'] ?? null;
        if ($envKey) {
            $val = config("services.{$provider}.key") ?: env($envKey);
            if (!empty($val)) return $val;
        }

        return null;
    }

    public function getModel($user, string $provider): string
    {
        $cfg = $this->providerConfig($provider);
        $default = $cfg['default_model'] ?? 'gpt-4o-mini';

        $setting = $this->setting($user);
        $col = $cfg['model_column'] ?? null;
        if ($setting && $col && !empty($setting->{$col})) {
            // validate still exists in config
            if (isset($cfg['models'][$setting->{$col}])) {
                return $setting->{$col};
            }
        }
        // per-user default_model override if matches this provider?
        // otherwise config default
        return $default;
    }

    /**
     * All providers with whether they are enabled (have key).
     * Returns [provider => bool]
     */
    public function enabledMap($user): array
    {
        $map = [];
        foreach ($this->allProviders() as $id => $cfg) {
            $map[$id] = $this->hasKey($user, $id);
        }
        return $map;
    }

    /**
     * List of provider ids that currently have a key.
     */
    public function enabledProviders($user): array
    {
        return array_keys(array_filter($this->enabledMap($user)));
    }

    public function isConfigured($user): bool
    {
        return !empty($this->enabledProviders($user));
    }

    /**
     * Resolve which provider/model/key to actually use for chat.
     * Returns ['provider'=>..., 'key'=>..., 'model'=>..., 'type'=>..., 'config'=>...] or null if none.
     */
    public function resolve($user): ?array
    {
        $setting = $this->setting($user);

        // 1) Explicit default_provider if it has a key
        $defaultProvider = $setting?->default_provider ?: config('ai.default_provider');
        if ($defaultProvider && isset($this->allProviders()[$defaultProvider])) {
            $key = $this->getKey($user, $defaultProvider);
            if ($key) {
                return $this->buildResolved($user, $defaultProvider, $key);
            }
        }

        // 2) Auto-detect: first provider in config order that has a key
        foreach ($this->allProviders() as $id => $cfg) {
            $key = $this->getKey($user, $id);
            if ($key) {
                return $this->buildResolved($user, $id, $key);
            }
        }

        return null;
    }

    private function buildResolved($user, string $provider, string $key): array
    {
        $cfg = $this->providerConfig($provider);
        $model = $this->getModel($user, $provider);
        // If user set a global default_model that belongs to this provider, prefer it
        $setting = $this->setting($user);
        if ($setting && !empty($setting->default_model) && $setting->default_provider === $provider) {
            if (isset($cfg['models'][$setting->default_model])) {
                $model = $setting->default_model;
            }
        }

        return [
            'provider' => $provider,
            'key'      => $key,
            'model'    => $model,
            'type'     => $cfg['type'] ?? 'openai',
            'config'   => $cfg,
        ];
    }

    /**
     * Resolve per-provider model — validates that model exists, else default.
     */
    public function validateModel(string $provider, string $model): bool
    {
        $cfg = $this->providerConfig($provider);
        if (!$cfg) return false;
        return isset($cfg['models'][$model]);
    }

    // ── HTTP helpers ────────────────────────────────────────────────

    public function openAiPayload(array $messages, string $model, bool $stream = false): array
    {
        $payload = [
            'model'       => $model,
            'messages'    => $messages,
            'max_tokens'  => 2048,
            'temperature' => 0.7,
        ];
        if ($stream) $payload['stream'] = true;
        return $payload;
    }

    /**
     * Convert OpenAI-style messages to Gemini contents.
     */
    public function toGeminiContents(array $messages): array
    {
        $contents = [];
        $systemText = null;

        foreach ($messages as $m) {
            if ($m['role'] === 'system') {
                $systemText = $m['content'];
                continue;
            }
            $role = $m['role'] === 'assistant' ? 'model' : 'user';
            $contents[] = [
                'role'  => $role,
                'parts' => [['text' => $m['content']]],
            ];
        }

        // Gemini may also accept systemInstruction; we prepend as first user hint if needed via config
        return [$contents, $systemText];
    }

    /**
     * Convert OpenAI-style messages to Anthropic format.
     * Returns [system, messages]
     */
    public function toAnthropicMessages(array $messages): array
    {
        $system = null;
        $out = [];
        foreach ($messages as $m) {
            if ($m['role'] === 'system') {
                $system = $m['content'];
                continue;
            }
            $out[] = ['role' => $m['role'], 'content' => $m['content']];
        }
        return [$system, $out];
    }
}
