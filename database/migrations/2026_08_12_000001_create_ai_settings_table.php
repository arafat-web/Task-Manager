<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->unique();
            $table->string('default_provider')->nullable();
            $table->string('default_model')->nullable();
            // encrypted API keys (TEXT to hold encrypted payload)
            $table->text('openai_key')->nullable();
            $table->text('gemini_key')->nullable();
            $table->text('anthropic_key')->nullable();
            $table->text('deepseek_key')->nullable();
            $table->text('meta_key')->nullable();
            // per-provider model preference
            $table->string('openai_model')->nullable();
            $table->string('gemini_model')->nullable();
            $table->string('anthropic_model')->nullable();
            $table->string('deepseek_model')->nullable();
            $table->string('meta_model')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_settings');
    }
};
