<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('financial_invoice_items', function (Blueprint $table) {
            if (! Schema::hasColumn('financial_invoice_items', 'billable_type')) {
                $table->nullableMorphs('billable');
            }
        });
    }

    public function down(): void
    {
        Schema::table('financial_invoice_items', function (Blueprint $table) {
            if (Schema::hasColumn('financial_invoice_items', 'billable_type')) {
                $table->dropMorphs('billable');
            }
        });
    }
};
