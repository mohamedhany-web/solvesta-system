<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('expenses') && ! Schema::hasColumn('expenses', 'project_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->foreignId('project_id')->nullable()->after('vendor_id')->constrained()->nullOnDelete();
            });
        }

        if (Schema::hasTable('invoices') && ! Schema::hasColumn('invoices', 'invoice_type')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->enum('invoice_type', ['standard', 'deposit', 'delivery'])->default('standard')->after('invoice_number');
            });

            DB::table('invoices')->where('notes', 'like', '%دفعة مقدمة%')->update(['invoice_type' => 'deposit']);
            DB::table('invoices')->where('notes', 'like', '%عند التسليم%')->update(['invoice_type' => 'delivery']);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('invoices') && Schema::hasColumn('invoices', 'invoice_type')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropColumn('invoice_type');
            });
        }

        if (Schema::hasTable('expenses') && Schema::hasColumn('expenses', 'project_id')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->dropConstrainedForeignId('project_id');
            });
        }
    }
};
