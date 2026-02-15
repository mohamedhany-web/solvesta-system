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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('lead_source')->nullable(); // website, referral, cold_call, etc.
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade');
            $table->string('product_service');
            $table->decimal('estimated_value', 15, 2);
            $table->decimal('actual_value', 15, 2)->nullable();
            $table->enum('stage', ['lead', 'prospect', 'proposal', 'negotiation', 'closed_won', 'closed_lost'])->default('lead');
            $table->integer('probability_percentage')->default(0);
            $table->date('expected_close_date')->nullable();
            $table->date('actual_close_date')->nullable();
            $table->text('notes')->nullable();
            $table->json('competitors')->nullable(); // Array of competitor names
            $table->json('decision_makers')->nullable(); // Array of decision makers info
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
