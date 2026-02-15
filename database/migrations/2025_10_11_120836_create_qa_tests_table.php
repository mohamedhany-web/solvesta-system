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
        Schema::create('qa_tests', function (Blueprint $table) {
            $table->id();
            $table->string('test_number')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('project_id')->nullable()->constrained('projects')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('type', ['unit', 'integration', 'functional', 'performance', 'security', 'usability'])->default('functional');
            $table->enum('status', ['pending', 'running', 'passed', 'failed', 'skipped'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->text('test_steps')->nullable();
            $table->text('expected_result')->nullable();
            $table->text('actual_result')->nullable();
            $table->text('preconditions')->nullable();
            $table->text('test_data')->nullable();
            $table->string('environment')->nullable();
            $table->text('notes')->nullable();
            $table->integer('execution_time')->nullable(); // in seconds
            $table->timestamp('executed_at')->nullable();
            $table->timestamps();
            
            $table->index('test_number');
            $table->index('status');
            $table->index('type');
            $table->index('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qa_tests');
    }
};
