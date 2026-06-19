<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('employees')) {
            Schema::table('employees', function (Blueprint $table) {
                if (! Schema::hasColumn('employees', 'supervisor_user_id')) {
                    $table->foreignId('supervisor_user_id')->nullable()->after('department_id')->constrained('users')->nullOnDelete();
                }
                if (! Schema::hasColumn('employees', 'is_team_lead')) {
                    $table->boolean('is_team_lead')->default(false)->after('supervisor_user_id');
                }
            });
        }

        if (Schema::hasTable('daily_reports')) {
            Schema::table('daily_reports', function (Blueprint $table) {
                if (! Schema::hasColumn('daily_reports', 'review_status')) {
                    $table->string('review_status', 32)->default('submitted')->after('blocker_status');
                }
                if (! Schema::hasColumn('daily_reports', 'dept_head_reviewed_by')) {
                    $table->foreignId('dept_head_reviewed_by')->nullable()->after('team_lead_notes')->constrained('users')->nullOnDelete();
                }
                if (! Schema::hasColumn('daily_reports', 'dept_head_reviewed_at')) {
                    $table->timestamp('dept_head_reviewed_at')->nullable()->after('dept_head_reviewed_by');
                }
                if (! Schema::hasColumn('daily_reports', 'dept_head_notes')) {
                    $table->text('dept_head_notes')->nullable()->after('dept_head_reviewed_at');
                }
                if (! Schema::hasColumn('daily_reports', 'executive_acknowledged_by')) {
                    $table->foreignId('executive_acknowledged_by')->nullable()->after('dept_head_notes')->constrained('users')->nullOnDelete();
                }
                if (! Schema::hasColumn('daily_reports', 'executive_acknowledged_at')) {
                    $table->timestamp('executive_acknowledged_at')->nullable()->after('executive_acknowledged_by');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('daily_reports')) {
            Schema::table('daily_reports', function (Blueprint $table) {
                foreach (['executive_acknowledged_at', 'executive_acknowledged_by', 'dept_head_notes', 'dept_head_reviewed_at', 'dept_head_reviewed_by', 'review_status'] as $col) {
                    if (Schema::hasColumn('daily_reports', $col)) {
                        if (str_ends_with($col, '_by')) {
                            $table->dropConstrainedForeignId($col);
                        } else {
                            $table->dropColumn($col);
                        }
                    }
                }
            });
        }

        if (Schema::hasTable('employees')) {
            Schema::table('employees', function (Blueprint $table) {
                if (Schema::hasColumn('employees', 'is_team_lead')) {
                    $table->dropColumn('is_team_lead');
                }
                if (Schema::hasColumn('employees', 'supervisor_user_id')) {
                    $table->dropConstrainedForeignId('supervisor_user_id');
                }
            });
        }
    }
};
