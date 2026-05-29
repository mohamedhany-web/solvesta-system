<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payments') && ! Schema::hasColumn('payments', 'project_invoice_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->foreignId('project_invoice_id')->nullable()->after('invoice_id')
                    ->constrained('invoices')->nullOnDelete();
            });
        }

        if (Schema::hasTable('wallet_transactions') && ! Schema::hasColumn('wallet_transactions', 'project_invoice_id')) {
            Schema::table('wallet_transactions', function (Blueprint $table) {
                $table->foreignId('project_invoice_id')->nullable()->after('financial_invoice_id')
                    ->constrained('invoices')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        // لا حذف تلقائي — العمود قد يكون مستخدماً
    }
};
