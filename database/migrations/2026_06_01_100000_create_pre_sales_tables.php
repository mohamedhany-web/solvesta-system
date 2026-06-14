<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cost_estimations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->string('reference_code')->unique();
            $table->unsignedSmallInteger('screen_count')->default(0);
            $table->unsignedSmallInteger('developers_count')->default(1);
            $table->decimal('dev_hours', 10, 2)->default(0);
            $table->decimal('design_hours', 10, 2)->default(0);
            $table->decimal('qa_hours', 10, 2)->default(0);
            $table->decimal('pm_hours', 10, 2)->default(0);
            $table->decimal('hourly_rate_dev', 10, 2)->default(500);
            $table->decimal('hourly_rate_design', 10, 2)->default(400);
            $table->decimal('hourly_rate_qa', 10, 2)->default(350);
            $table->decimal('hourly_rate_pm', 10, 2)->default(450);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('margin_percent', 5, 2)->default(15);
            $table->decimal('total_cost', 15, 2)->default(0);
            $table->unsignedSmallInteger('duration_weeks')->default(4);
            $table->text('scope_notes')->nullable();
            $table->text('technical_notes')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved'])->default('draft');
            $table->foreignId('estimated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->foreignId('cost_estimation_id')->nullable()->constrained('cost_estimations')->nullOnDelete();
            $table->string('reference_code')->unique();
            $table->string('title');
            $table->longText('project_description')->nullable();
            $table->longText('scope')->nullable();
            $table->longText('timeline')->nullable();
            $table->longText('pricing_breakdown')->nullable();
            $table->string('payment_terms')->default('50% مقدماً — 50% عند التسليم');
            $table->decimal('total_price', 15, 2)->default(0);
            $table->date('valid_until')->nullable();
            $table->enum('status', ['draft', 'sent', 'accepted', 'rejected', 'expired'])->default('draft');
            $table->longText('generated_content')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposals');
        Schema::dropIfExists('cost_estimations');
    }
};
