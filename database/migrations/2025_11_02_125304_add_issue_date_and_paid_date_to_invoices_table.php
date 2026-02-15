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
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'issue_date')) {
                $table->date('issue_date')->nullable()->after('invoice_date');
            }
            if (!Schema::hasColumn('invoices', 'paid_date')) {
                $table->date('paid_date')->nullable()->after('due_date');
            }
            // إضافة عمود amount إذا كان مفقوداً
            if (!Schema::hasColumn('invoices', 'amount')) {
                $table->decimal('amount', 15, 2)->nullable()->after('subtotal');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'issue_date')) {
                $table->dropColumn('issue_date');
            }
            if (Schema::hasColumn('invoices', 'paid_date')) {
                $table->dropColumn('paid_date');
            }
            if (Schema::hasColumn('invoices', 'amount')) {
                $table->dropColumn('amount');
            }
        });
    }
};
