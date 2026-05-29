<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contract_id')->nullable()->constrained()->nullOnDelete();
            $table->string('service_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('monthly_amount', 15, 2);
            $table->unsignedTinyInteger('billing_day')->default(1);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->string('currency', 3)->default('EGP');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->date('next_billing_date')->nullable();
            $table->unsignedSmallInteger('payment_terms_days')->default(14);
            $table->enum('status', ['draft', 'active', 'paused', 'ended'])->default('draft');
            $table->boolean('auto_invoice')->default(true);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'next_billing_date']);
            $table->index('client_id');
        });

        if (Schema::hasTable('financial_invoices')) {
            Schema::table('financial_invoices', function (Blueprint $table) {
                if (! Schema::hasColumn('financial_invoices', 'client_service_id')) {
                    $table->foreignId('client_service_id')->nullable()->after('project_id')
                        ->constrained('client_services')->nullOnDelete();
                }
                if (! Schema::hasColumn('financial_invoices', 'contract_id')) {
                    $table->foreignId('contract_id')->nullable()->after('client_service_id')
                        ->constrained('contracts')->nullOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('financial_invoices')) {
            Schema::table('financial_invoices', function (Blueprint $table) {
                if (Schema::hasColumn('financial_invoices', 'client_service_id')) {
                    $table->dropConstrainedForeignId('client_service_id');
                }
                if (Schema::hasColumn('financial_invoices', 'contract_id')) {
                    $table->dropConstrainedForeignId('contract_id');
                }
            });
        }

        Schema::dropIfExists('client_services');
    }
};
