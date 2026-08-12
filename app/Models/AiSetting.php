<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiSetting extends Model
{
    protected $fillable = [
        'user_id',
        'default_provider',
        'default_model',
        'openai_key',
        'gemini_key',
        'anthropic_key',
        'deepseek_key',
        'meta_key',
        'openai_model',
        'gemini_model',
        'anthropic_model',
        'deepseek_model',
        'meta_model',
    ];

    protected $casts = [
        'openai_key'    => 'encrypted',
        'gemini_key'    => 'encrypted',
        'anthropic_key' => 'encrypted',
        'deepseek_key'  => 'encrypted',
        'meta_key'      => 'encrypted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
