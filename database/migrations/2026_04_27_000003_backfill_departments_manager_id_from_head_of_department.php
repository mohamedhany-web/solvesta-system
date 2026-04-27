<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('departments', 'manager_id') || !Schema::hasColumn('departments', 'head_of_department')) {
            return;
        }

        // If legacy head_of_department has data, copy it into manager_id when manager_id is null.
        DB::table('departments')
            ->whereNull('manager_id')
            ->whereNotNull('head_of_department')
            ->update([
                'manager_id' => DB::raw('head_of_department'),
            ]);
    }

    public function down(): void
    {
        // non-destructive (no rollback needed)
    }
};

