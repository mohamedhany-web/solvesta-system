<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE employees MODIFY COLUMN status ENUM('active', 'inactive', 'terminated', 'on_leave') NOT NULL DEFAULT 'active'");
    }

    public function down(): void
    {
        DB::table('employees')->where('status', 'on_leave')->update(['status' => 'inactive']);

        DB::statement("ALTER TABLE employees MODIFY COLUMN status ENUM('active', 'inactive', 'terminated') NOT NULL DEFAULT 'active'");
    }
};
