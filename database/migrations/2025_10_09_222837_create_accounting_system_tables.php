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
        // جدول الحسابات المحاسبية (Chart of Accounts)
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_code')->unique();
            $table->string('account_name');
            $table->enum('account_type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->enum('account_category', [
                'current_asset', 'fixed_asset', 'current_liability', 'long_term_liability',
                'capital', 'retained_earnings', 'operating_revenue', 'other_revenue',
                'operating_expense', 'other_expense'
            ]);
            $table->foreignId('parent_account_id')->nullable()->constrained('accounts')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('account_type');
            $table->index('account_category');
        });

        // جدول القيود المحاسبية (Journal Entries)
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_number')->unique();
            $table->date('entry_date');
            $table->enum('entry_type', ['manual', 'auto', 'adjustment', 'closing']);
            $table->string('reference_type')->nullable(); // invoice, payment, salary, etc.
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description');
            $table->decimal('total_debit', 15, 2);
            $table->decimal('total_credit', 15, 2);
            $table->enum('status', ['draft', 'posted', 'approved', 'cancelled'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->index('entry_date');
            $table->index('status');
            $table->index(['reference_type', 'reference_id']);
        });

        // جدول تفاصيل القيود المحاسبية (Journal Entry Lines)
        Schema::create('journal_entry_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->decimal('debit_amount', 15, 2)->default(0);
            $table->decimal('credit_amount', 15, 2)->default(0);
            $table->timestamps();
            
            $table->index('journal_entry_id');
            $table->index('account_id');
        });

        // جدول الفواتير المالية (Financial Invoices)
        Schema::create('financial_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->enum('invoice_type', ['sales', 'purchase', 'service']);
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->date('invoice_date');
            $table->date('due_date');
            $table->text('description')->nullable();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('balance_due', 15, 2);
            $table->enum('status', ['draft', 'sent', 'partial', 'paid', 'overdue', 'cancelled'])->default('draft');
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->string('currency', 3)->default('SAR');
            $table->text('notes')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('invoice_date');
            $table->index('due_date');
            $table->index('status');
            $table->index('payment_status');
        });

        // جدول بنود الفاتورة (Invoice Items)
        Schema::create('financial_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('financial_invoices')->onDelete('cascade');
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('amount', 15, 2);
            $table->timestamps();
            
            $table->index('invoice_id');
        });

        // جدول المدفوعات (Payments)
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->enum('payment_type', ['invoice', 'salary', 'expense', 'other']);
            $table->foreignId('invoice_id')->nullable()->constrained('financial_invoices')->onDelete('set null');
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null');
            $table->date('payment_date');
            $table->decimal('amount', 15, 2);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'check', 'credit_card', 'online']);
            $table->string('reference_number')->nullable();
            $table->foreignId('bank_account_id')->nullable()->constrained('accounts')->onDelete('set null');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('completed');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('payment_date');
            $table->index('payment_type');
            $table->index('status');
        });

        // جدول المصروفات (Expenses)
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_number')->unique();
            $table->enum('expense_category', [
                'office_supplies', 'utilities', 'rent', 'salaries',
                'marketing', 'travel', 'maintenance', 'software',
                'professional_fees', 'insurance', 'taxes', 'other'
            ]);
            $table->foreignId('vendor_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->date('expense_date');
            $table->decimal('amount', 15, 2);
            $table->string('description');
            $table->text('notes')->nullable();
            $table->enum('payment_method', ['cash', 'bank_transfer', 'check', 'credit_card']);
            $table->string('receipt_number')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('status', ['pending', 'approved', 'paid', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('expense_date');
            $table->index('expense_category');
            $table->index('status');
        });

        // جدول الميزانية (Budgets)
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->string('budget_name');
            $table->enum('budget_type', ['annual', 'quarterly', 'monthly', 'project']);
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_budget', 15, 2);
            $table->decimal('allocated_amount', 15, 2)->default(0);
            $table->decimal('spent_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2);
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('budget_type');
            $table->index('status');
            $table->index(['start_date', 'end_date']);
        });

        // جدول بنود الميزانية (Budget Items)
        Schema::create('budget_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_id')->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->decimal('allocated_amount', 15, 2);
            $table->decimal('spent_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2);
            $table->timestamps();
            
            $table->index('budget_id');
            $table->index('account_id');
        });

        // جدول التسويات البنكية (Bank Reconciliations)
        Schema::create('bank_reconciliations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_account_id')->constrained('accounts')->onDelete('cascade');
            $table->date('reconciliation_date');
            $table->decimal('bank_statement_balance', 15, 2);
            $table->decimal('book_balance', 15, 2);
            $table->decimal('outstanding_deposits', 15, 2)->default(0);
            $table->decimal('outstanding_checks', 15, 2)->default(0);
            $table->decimal('bank_charges', 15, 2)->default(0);
            $table->decimal('interest_earned', 15, 2)->default(0);
            $table->decimal('adjusted_balance', 15, 2);
            $table->decimal('difference', 15, 2)->default(0);
            $table->enum('status', ['in_progress', 'reconciled', 'unreconciled'])->default('in_progress');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('reconciliation_date');
            $table->index('status');
        });

        // جدول الأصول الثابتة (Fixed Assets)
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_code')->unique();
            $table->string('asset_name');
            $table->enum('asset_category', ['building', 'equipment', 'vehicle', 'furniture', 'computer', 'other']);
            $table->date('purchase_date');
            $table->decimal('purchase_cost', 15, 2);
            $table->decimal('salvage_value', 15, 2)->default(0);
            $table->integer('useful_life_years');
            $table->enum('depreciation_method', ['straight_line', 'declining_balance', 'units_of_production']);
            $table->decimal('depreciation_rate', 5, 2);
            $table->decimal('accumulated_depreciation', 15, 2)->default(0);
            $table->decimal('book_value', 15, 2);
            $table->string('location')->nullable();
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['active', 'disposed', 'under_repair', 'retired'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('asset_category');
            $table->index('status');
        });

        // جدول الضرائب (Tax Records)
        Schema::create('tax_records', function (Blueprint $table) {
            $table->id();
            $table->enum('tax_type', ['vat', 'income_tax', 'withholding_tax', 'other']);
            $table->string('tax_period'); // Q1-2024, 2024-01, etc.
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('taxable_amount', 15, 2);
            $table->decimal('tax_rate', 5, 2);
            $table->decimal('tax_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->enum('status', ['calculated', 'filed', 'paid', 'overdue'])->default('calculated');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('tax_type');
            $table->index('tax_period');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_records');
        Schema::dropIfExists('fixed_assets');
        Schema::dropIfExists('bank_reconciliations');
        Schema::dropIfExists('budget_items');
        Schema::dropIfExists('budgets');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('financial_invoice_items');
        Schema::dropIfExists('financial_invoices');
        Schema::dropIfExists('journal_entry_lines');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('accounts');
    }
};
