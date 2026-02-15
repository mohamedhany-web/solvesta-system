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
            if (!Schema::hasColumn('invoices', 'contract_id')) {
                // SQLite doesn't support adding foreign keys directly
                if (config('database.default') === 'sqlite') {
                    $table->unsignedBigInteger('contract_id')->nullable()->after('sale_id');
                } else {
                    $table->foreignId('contract_id')->nullable()->after('sale_id')->constrained('contracts')->onDelete('set null');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'contract_id')) {
                if (config('database.default') !== 'sqlite') {
                    $table->dropForeign(['contract_id']);
                }
                $table->dropColumn('contract_id');
            }
        });
    }
};
