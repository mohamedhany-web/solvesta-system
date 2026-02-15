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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->string('subject');
            $table->text('description');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('category', ['technical', 'billing', 'general', 'bug_report', 'feature_request']);
            $table->enum('status', ['open', 'in_progress', 'pending_client', 'resolved', 'closed'])->default('open');
            $table->integer('sla_hours')->nullable(); // Service Level Agreement hours
            $table->timestamp('first_response_time')->nullable();
            $table->timestamp('resolution_time')->nullable();
            $table->integer('rating')->nullable(); // Client satisfaction rating 1-5
            $table->text('resolution_notes')->nullable();
            $table->json('attachments')->nullable(); // Array of file paths
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
