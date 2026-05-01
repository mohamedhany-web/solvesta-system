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
        // إنشاء جدول العملاء
        if (!Schema::hasTable('clients')) {
            Schema::create('clients', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('company')->nullable();
                $table->text('address')->nullable();
                $table->string('status')->default('active'); // active, inactive, potential
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // إنشاء جدول المبيعات
        if (!Schema::hasTable('sales')) {
            Schema::create('sales', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade'); // مندوب المبيعات
                $table->string('title');
                $table->text('description')->nullable();
                $table->decimal('amount', 15, 2);
                $table->string('status')->default('pending'); // pending, approved, completed, cancelled
                $table->date('sale_date');
                $table->date('expected_close_date')->nullable();
                $table->timestamps();
            });
        }

        // إنشاء جدول العقود
        if (!Schema::hasTable('contracts')) {
            Schema::create('contracts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->constrained()->onDelete('cascade');
                $table->foreignId('sale_id')->nullable()->constrained()->onDelete('set null');
                $table->string('contract_number')->unique();
                $table->string('title');
                $table->text('description')->nullable();
                $table->decimal('value', 15, 2);
                $table->date('start_date');
                $table->date('end_date');
                $table->string('status')->default('active'); // active, expired, terminated
                $table->text('terms')->nullable();
                $table->timestamps();
            });
        }

        // إنشاء جدول الفواتير
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->constrained()->onDelete('cascade');
                $table->foreignId('contract_id')->nullable()->constrained()->onDelete('set null');
                $table->string('invoice_number')->unique();
                $table->decimal('amount', 15, 2);
                $table->decimal('tax_amount', 15, 2)->default(0);
                $table->decimal('total_amount', 15, 2);
                $table->string('status')->default('draft'); // draft, sent, paid, overdue
                $table->date('issue_date');
                $table->date('due_date');
                $table->date('paid_date')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // إنشاء جدول التدريب
        if (!Schema::hasTable('trainings')) {
            Schema::create('trainings', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description');
                $table->string('type'); // internal, external, online, workshop
                $table->string('status')->default('planned'); // planned, ongoing, completed, cancelled
                $table->date('start_date');
                $table->date('end_date');
                $table->integer('max_participants')->nullable();
                $table->decimal('cost', 10, 2)->nullable();
                $table->foreignId('instructor_id')->nullable()->constrained('users')->onDelete('set null');
                $table->timestamps();
            });
        }

        // إنشاء جدول مشاركة الموظفين في التدريب
        if (!Schema::hasTable('training_participants')) {
            Schema::create('training_participants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('training_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('status')->default('registered'); // registered, attended, completed, failed
                $table->integer('score')->nullable();
                $table->text('feedback')->nullable();
                $table->timestamps();
            });
        }

        // إنشاء جدول الاجتماعات
        if (!Schema::hasTable('meetings')) {
            Schema::create('meetings', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('type'); // internal, external, client, team
                $table->string('status')->default('scheduled'); // scheduled, ongoing, completed, cancelled
                $table->datetime('start_time');
                $table->datetime('end_time');
                $table->string('location')->nullable();
                $table->string('meeting_link')->nullable(); // للاجتماعات الافتراضية
                $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
                $table->timestamps();
            });
        }

        // إنشاء جدول مشاركة المستخدمين في الاجتماعات
        if (!Schema::hasTable('meeting_participants')) {
            Schema::create('meeting_participants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('meeting_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('status')->default('invited'); // invited, accepted, declined, attended
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // إنشاء جدول الأصول
        if (!Schema::hasTable('assets')) {
            Schema::create('assets', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('category'); // equipment, furniture, vehicle, technology, other
                $table->string('asset_tag')->unique();
                $table->text('description')->nullable();
                $table->decimal('purchase_price', 15, 2)->nullable();
                $table->date('purchase_date')->nullable();
                $table->string('status')->default('active'); // active, maintenance, retired, lost
                $table->string('location')->nullable();
                $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // إنشاء جدول صيانة الأصول
        if (!Schema::hasTable('asset_maintenance')) {
            Schema::create('asset_maintenance', function (Blueprint $table) {
                $table->id();
                $table->foreignId('asset_id')->constrained()->onDelete('cascade');
                $table->string('type'); // preventive, corrective, emergency
                $table->text('description');
                $table->date('scheduled_date');
                $table->date('completed_date')->nullable();
                $table->decimal('cost', 10, 2)->nullable();
                $table->string('status')->default('scheduled'); // scheduled, in_progress, completed, cancelled
                $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_maintenance');
        Schema::dropIfExists('assets');
        Schema::dropIfExists('meeting_participants');
        Schema::dropIfExists('meetings');
        Schema::dropIfExists('training_participants');
        Schema::dropIfExists('trainings');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('sales');
        Schema::dropIfExists('clients');
    }
};
