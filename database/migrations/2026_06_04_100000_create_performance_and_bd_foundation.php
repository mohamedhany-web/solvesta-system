<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_kpi_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('period_year');
            $table->unsignedTinyInteger('period_month');
            $table->string('role_template')->default('default');
            $table->decimal('adherence_score', 5, 2)->default(0);
            $table->decimal('task_completion_score', 5, 2)->default(0);
            $table->decimal('quality_score', 5, 2)->default(0);
            $table->decimal('team_lead_rating', 5, 2)->nullable();
            $table->decimal('total_score', 5, 2)->default(0);
            $table->decimal('kpi_deductions', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('rated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'period_year', 'period_month']);
        });

        Schema::create('hr_warnings', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['task_delay', 'kpi_deduction', 'attendance', 'conduct', 'other'])->default('task_delay');
            $table->string('reason');
            $table->decimal('kpi_deduction_points', 5, 2)->default(5);
            $table->enum('status', ['active', 'resolved', 'escalated'])->default('active');
            $table->enum('investigation_status', ['none', 'pending', 'in_progress', 'closed'])->default('none');
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->text('hr_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('bd_partners', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code')->unique();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->enum('partner_type', ['agency', 'vendor', 'referrer', 'strategic', 'other'])->default('other');
            $table->string('country')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['prospect', 'active', 'inactive'])->default('prospect');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('bd_opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code')->unique();
            $table->foreignId('partner_id')->nullable()->constrained('bd_partners')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('estimated_value', 15, 2)->nullable();
            $table->enum('status', ['prospecting', 'contacted', 'qualified', 'converted', 'lost'])->default('prospecting');
            $table->foreignId('lead_id')->nullable()->constrained('leads')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->date('expected_close_date')->nullable();
            $table->string('lost_reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bd_opportunities');
        Schema::dropIfExists('bd_partners');
        Schema::dropIfExists('hr_warnings');
        Schema::dropIfExists('employee_kpi_periods');
    }
};
