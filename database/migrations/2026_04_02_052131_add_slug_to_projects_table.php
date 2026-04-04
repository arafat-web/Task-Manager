<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        // Backfill slugs for existing projects
        DB::table('projects')->orderBy('id')->each(function ($project) {
            $base = Str::slug($project->name);
            $slug = $base;
            $i = 2;
            while (DB::table('projects')->where('slug', $slug)->where('id', '!=', $project->id)->exists()) {
                $slug = $base . '-' . $i++;
            }
            DB::table('projects')->where('id', $project->id)->update(['slug' => $slug]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
