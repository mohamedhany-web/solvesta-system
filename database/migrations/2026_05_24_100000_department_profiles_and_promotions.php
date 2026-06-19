<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            if (! Schema::hasColumn('departments', 'default_role')) {
                $table->string('default_role', 64)->nullable()->after('sidebar_modules');
            }
            if (! Schema::hasColumn('departments', 'default_position')) {
                $table->string('default_position')->nullable()->after('default_role');
            }
            if (! Schema::hasColumn('departments', 'kpi_profile')) {
                $table->string('kpi_profile', 64)->nullable()->after('default_position');
            }
            if (! Schema::hasColumn('departments', 'career_track')) {
                $table->string('career_track', 64)->nullable()->after('kpi_profile');
            }
        });

        Schema::table('employees', function (Blueprint $table) {
            if (! Schema::hasColumn('employees', 'career_level')) {
                $table->string('career_level', 64)->nullable()->after('is_team_lead');
            }
            if (! Schema::hasColumn('employees', 'career_track')) {
                $table->string('career_track', 64)->nullable()->after('career_level');
            }
        });

        if (! Schema::hasTable('employee_promotions')) {
            Schema::create('employee_promotions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
                $table->string('from_level')->nullable();
                $table->string('to_level');
                $table->string('career_track', 64);
                $table->string('status', 32)->default('pending_team_lead');
                $table->decimal('kpi_score', 5, 2)->nullable();
                $table->text('justification')->nullable();
                $table->text('team_lead_notes')->nullable();
                $table->text('dept_head_notes')->nullable();
                $table->text('hr_notes')->nullable();
                $table->foreignId('proposed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_promotions');

        Schema::table('employees', function (Blueprint $table) {
            foreach (['career_track', 'career_level'] as $col) {
                if (Schema::hasColumn('employees', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('departments', function (Blueprint $table) {
            foreach (['career_track', 'kpi_profile', 'default_position', 'default_role'] as $col) {
                if (Schema::hasColumn('departments', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
