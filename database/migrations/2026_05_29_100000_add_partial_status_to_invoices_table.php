<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('invoices') || ! Schema::hasColumn('invoices', 'status')) {
            return;
        }

        DB::statement("ALTER TABLE `invoices` MODIFY COLUMN `status` ENUM(
            'draft', 'sent', 'viewed', 'partial', 'paid', 'overdue', 'cancelled'
        ) NOT NULL DEFAULT 'draft'");
    }

    public function down(): void
    {
        if (! Schema::hasTable('invoices')) {
            return;
        }

        DB::table('invoices')->where('status', 'partial')->update(['status' => 'sent']);

        DB::statement("ALTER TABLE `invoices` MODIFY COLUMN `status` ENUM(
            'draft', 'sent', 'viewed', 'paid', 'overdue', 'cancelled'
        ) NOT NULL DEFAULT 'draft'");
    }
};
