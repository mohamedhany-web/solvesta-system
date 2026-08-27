<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE employees MODIFY COLUMN status ENUM('onboarding', 'active', 'inactive', 'terminated', 'on_leave') NOT NULL DEFAULT 'onboarding'");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        DB::table('employees')->where('status', 'onboarding')->update(['status' => 'active']);

        DB::statement("ALTER TABLE employees MODIFY COLUMN status ENUM('active', 'inactive', 'terminated', 'on_leave') NOT NULL DEFAULT 'active'");
    }
};
