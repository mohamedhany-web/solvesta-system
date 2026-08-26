<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workflow_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type', 64)->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('workflow_states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_definition_id')->constrained()->cascadeOnDelete();
            $table->string('key', 64);
            $table->string('label_ar');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_initial')->default(false);
            $table->boolean('is_terminal')->default(false);
            $table->boolean('is_side')->default(false);
            $table->unsignedSmallInteger('sla_hours')->nullable();
            $table->string('severity_if_breached', 20)->nullable();
            $table->string('maps_to_status', 64)->nullable();
            $table->timestamps();

            $table->unique(['workflow_definition_id', 'key']);
        });

        Schema::create('workflow_transitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_definition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('from_state_id')->constrained('workflow_states')->cascadeOnDelete();
            $table->foreignId('to_state_id')->constrained('workflow_states')->cascadeOnDelete();
            $table->string('required_permission')->nullable();
            $table->boolean('requires_approval')->default(false);
            $table->string('guard_key', 64)->nullable();
            $table->timestamps();

            $table->unique(['from_state_id', 'to_state_id']);
        });

        Schema::create('entity_lifecycles', function (Blueprint $table) {
            $table->id();
            $table->morphs('subject');
            $table->foreignId('workflow_definition_id')->constrained()->cascadeOnDelete();
            $table->foreignId('current_state_id')->constrained('workflow_states');
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('entered_at')->nullable();
            $table->timestamp('sla_due_at')->nullable();
            $table->timestamp('sla_breached_at')->nullable();
            $table->timestamps();

            $table->unique(['subject_type', 'subject_id']);
        });

        Schema::create('company_events', function (Blueprint $table) {
            $table->id();
            $table->string('actor_type', 20)->default('system');
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->morphs('subject');
            $table->string('event', 80);
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['event', 'created_at']);
        });

        Schema::create('work_items', function (Blueprint $table) {
            $table->id();
            $table->string('type', 40);
            $table->string('title');
            $table->text('description')->nullable();
            $table->morphs('subject');
            $table->nullableMorphs('related');
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assignee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('priority', 20)->default('medium');
            $table->string('status', 20)->default('open');
            $table->string('blocks_state', 64)->nullable();
            $table->boolean('is_required')->default(true);
            $table->timestamp('due_at')->nullable();
            $table->timestamp('sla_due_at')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('work_items')->nullOnDelete();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['status', 'blocks_state']);
        });

        Schema::create('approval_rules', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type', 64);
            $table->decimal('min_amount', 15, 2)->default(0);
            $table->decimal('max_amount', 15, 2)->nullable();
            $table->json('steps');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();
            $table->morphs('subject');
            $table->foreignId('rule_id')->nullable()->constrained('approval_rules')->nullOnDelete();
            $table->decimal('amount', 15, 2)->default(0);
            $table->string('status', 20)->default('pending');
            $table->unsignedTinyInteger('current_step')->default(0);
            $table->json('steps')->nullable();
            $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('decided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('decided_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });

        Schema::create('company_exceptions', function (Blueprint $table) {
            $table->id();
            $table->string('severity', 20);
            $table->string('code', 64);
            $table->morphs('subject');
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('message')->nullable();
            $table->boolean('is_open')->default(true);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['is_open', 'severity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_exceptions');
        Schema::dropIfExists('approval_requests');
        Schema::dropIfExists('approval_rules');
        Schema::dropIfExists('work_items');
        Schema::dropIfExists('company_events');
        Schema::dropIfExists('entity_lifecycles');
        Schema::dropIfExists('workflow_transitions');
        Schema::dropIfExists('workflow_states');
        Schema::dropIfExists('workflow_definitions');
    }
};
