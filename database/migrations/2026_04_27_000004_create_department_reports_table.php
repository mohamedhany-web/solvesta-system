<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('department_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();

            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();

            $table->longText('summary');
            $table->json('kpis')->nullable();
            $table->json('attachments')->nullable();

            $table->string('status')->default('draft'); // draft | submitted
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

            $table->index(['department_id', 'status']);
            $table->index(['project_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('department_reports');
    }
};

