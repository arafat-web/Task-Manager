<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('to_do','in_progress','on_hold','in_review','completed') NOT NULL DEFAULT 'to_do'");
    }

    public function down(): void
    {
        // Move any on_hold/in_review tasks back to to_do before shrinking the ENUM
        DB::statement("UPDATE tasks SET status = 'to_do' WHERE status IN ('on_hold','in_review')");
        DB::statement("ALTER TABLE tasks MODIFY COLUMN status ENUM('to_do','in_progress','completed') NOT NULL DEFAULT 'to_do'");
    }
};
