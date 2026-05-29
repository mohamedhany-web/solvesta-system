<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['cash', 'bank', 'transfer', 'other'])->default('cash');
            $table->string('currency', 3)->default('EGP');
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
            $table->enum('direction', ['in', 'out']);
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_after', 15, 2)->default(0);
            $table->string('reference')->nullable();
            $table->string('category')->default('general');
            $table->nullableMorphs('source');
            $table->foreignId('financial_invoice_id')->nullable()->constrained('financial_invoices')->nullOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->text('description')->nullable();
            $table->date('transaction_date');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['wallet_id', 'transaction_date']);
            $table->index('direction');
        });

        if (Schema::hasTable('payments') && ! Schema::hasColumn('payments', 'wallet_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->foreignId('wallet_id')->nullable()->after('bank_account_id')->constrained('wallets')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'wallet_id')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropConstrainedForeignId('wallet_id');
            });
        }

        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('wallets');
    }
};
