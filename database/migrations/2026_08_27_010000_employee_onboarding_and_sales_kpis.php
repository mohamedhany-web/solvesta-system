<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('id_photo_front_path')->nullable()->after('national_id_address');
            $table->string('id_photo_back_path')->nullable()->after('id_photo_front_path');
        });

        Schema::create('employment_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('contract_number')->unique();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('base_salary', 12, 2);
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'intern']);
            $table->string('document_path')->nullable();
            $table->enum('status', ['draft', 'active', 'terminated', 'expired'])->default('draft');
            $table->timestamp('signed_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('sales_kpi_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->nullable()->constrained()->nullOnDelete();
            $table->string('metric_key', 64);
            $table->unsignedSmallInteger('period_year');
            $table->unsignedTinyInteger('period_month');
            $table->decimal('target_value', 14, 2);
            $table->unsignedTinyInteger('weight')->default(10);
            $table->text('monthly_duty')->nullable();
            $table->foreignId('set_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(
                ['user_id', 'metric_key', 'period_year', 'period_month'],
                'sales_kpi_user_metric_period'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_kpi_targets');
        Schema::dropIfExists('employment_contracts');

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['id_photo_front_path', 'id_photo_back_path']);
        });
    }
};
