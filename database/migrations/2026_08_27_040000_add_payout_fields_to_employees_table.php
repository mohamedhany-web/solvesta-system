<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('preferred_payout_method', 32)->nullable()->after('salary');
            $table->string('bank_name')->nullable()->after('preferred_payout_method');
            $table->string('bank_account_number', 64)->nullable()->after('bank_name');
            $table->string('bank_account_holder')->nullable()->after('bank_account_number');
            $table->string('vodafone_cash_number', 20)->nullable()->after('bank_account_holder');
            $table->string('instapay_identifier', 128)->nullable()->after('vodafone_cash_number');
            $table->text('payout_notes')->nullable()->after('instapay_identifier');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'preferred_payout_method',
                'bank_name',
                'bank_account_number',
                'bank_account_holder',
                'vodafone_cash_number',
                'instapay_identifier',
                'payout_notes',
            ]);
        });
    }
};
