<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium')->after('description');
            $table->string('category')->nullable()->after('priority');
            $table->boolean('is_recurring')->default(false)->after('category');
            $table->enum('recurrence_type', ['none', 'daily', 'weekly', 'monthly', 'yearly'])->default('none')->after('is_recurring');
            $table->integer('recurrence_interval')->default(1)->after('recurrence_type');
            $table->boolean('is_completed')->default(false)->after('recurrence_interval');
            $table->timestamp('completed_at')->nullable()->after('is_completed');
            $table->timestamp('snooze_until')->nullable()->after('completed_at');
            $table->boolean('notification_sent')->default(false)->after('snooze_until');
            $table->string('location')->nullable()->after('notification_sent');
            $table->json('tags')->nullable()->after('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->dropColumn([
                'priority', 'category', 'is_recurring', 'recurrence_type',
                'recurrence_interval', 'is_completed', 'completed_at',
                'snooze_until', 'notification_sent', 'location', 'tags'
            ]);
        });
    }
};
