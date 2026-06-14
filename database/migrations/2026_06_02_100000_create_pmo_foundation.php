<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('phase_type', ['ui_ux', 'backend', 'frontend', 'testing', 'delivery', 'other'])->default('other');
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->decimal('estimated_hours', 10, 2)->default(0);
            $table->decimal('actual_hours', 10, 2)->default(0);
            $table->unsignedTinyInteger('progress_percentage')->default(0);
            $table->enum('status', ['pending', 'in_progress', 'completed', 'blocked', 'cancelled'])->default('pending');
            $table->foreignId('assigned_lead_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('task_id')->nullable()->constrained()->nullOnDelete();
            $table->date('report_date');
            $table->text('work_summary');
            $table->decimal('hours_worked', 5, 2)->default(0);
            $table->boolean('has_blocker')->default(false);
            $table->text('blocker_description')->nullable();
            $table->enum('blocker_status', ['open', 'resolved'])->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('team_lead_notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'report_date', 'project_id'], 'daily_reports_user_date_project');
        });

        Schema::table('tasks', function (Blueprint $table) {
            if (! Schema::hasColumn('tasks', 'milestone_id')) {
                $table->foreignId('milestone_id')->nullable()->after('project_id')->constrained('project_milestones')->nullOnDelete();
            }
            if (! Schema::hasColumn('tasks', 'specialization')) {
                $table->enum('specialization', ['backend', 'frontend', 'ui_ux', 'design', 'qa', 'pm', 'other'])->nullable()->after('priority');
            }
            if (! Schema::hasColumn('tasks', 'has_blocker')) {
                $table->boolean('has_blocker')->default(false)->after('actual_hours');
            }
            if (! Schema::hasColumn('tasks', 'blocker_description')) {
                $table->text('blocker_description')->nullable()->after('has_blocker');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            foreach (['blocker_description', 'has_blocker', 'specialization'] as $col) {
                if (Schema::hasColumn('tasks', $col)) {
                    $table->dropColumn($col);
                }
            }
            if (Schema::hasColumn('tasks', 'milestone_id')) {
                $table->dropConstrainedForeignId('milestone_id');
            }
        });

        Schema::dropIfExists('daily_reports');
        Schema::dropIfExists('project_milestones');
    }
};
