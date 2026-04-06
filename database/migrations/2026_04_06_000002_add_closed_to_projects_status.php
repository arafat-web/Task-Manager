<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE projects MODIFY COLUMN status ENUM('not_started','in_progress','completed','closed') NOT NULL DEFAULT 'not_started'");
    }

    public function down(): void
    {
        DB::statement("UPDATE projects SET status = 'completed' WHERE status = 'closed'");
        DB::statement("ALTER TABLE projects MODIFY COLUMN status ENUM('not_started','in_progress','completed') NOT NULL DEFAULT 'not_started'");
    }
};
