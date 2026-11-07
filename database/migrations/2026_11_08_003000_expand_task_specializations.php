<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tasks') || ! Schema::hasColumn('tasks', 'specialization')) {
            return;
        }

        // Expand specialty values beyond the original PMO enum without locking to a fixed MySQL ENUM.
        try {
            DB::statement('ALTER TABLE tasks MODIFY specialization VARCHAR(50) NULL');
        } catch (\Throwable $e) {
            // SQLite / already varchar — ignore.
        }
    }

    public function down(): void
    {
        // No-op: keep flexible specialty column.
    }
};
