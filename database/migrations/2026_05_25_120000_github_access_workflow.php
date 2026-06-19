<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_git_identities', function (Blueprint $table) {
            if (! Schema::hasColumn('user_git_identities', 'email')) {
                $table->string('email')->nullable()->after('username');
            }
            if (! Schema::hasColumn('user_git_identities', 'status')) {
                $table->string('status', 32)->default('pending')->after('email');
            }
            if (! Schema::hasColumn('user_git_identities', 'employee_note')) {
                $table->text('employee_note')->nullable()->after('status');
            }
            if (! Schema::hasColumn('user_git_identities', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('employee_note');
            }
            if (! Schema::hasColumn('user_git_identities', 'reviewed_by')) {
                $table->foreignId('reviewed_by')->nullable()->after('admin_notes')->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('user_git_identities', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            }
        });

        Schema::table('project_repository_users', function (Blueprint $table) {
            if (! Schema::hasColumn('project_repository_users', 'invite_status')) {
                $table->string('invite_status', 24)->default('pending')->after('access_level');
            }
            if (! Schema::hasColumn('project_repository_users', 'invited_at')) {
                $table->timestamp('invited_at')->nullable()->after('invite_status');
            }
            if (! Schema::hasColumn('project_repository_users', 'invite_error')) {
                $table->string('invite_error')->nullable()->after('invited_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('project_repository_users', function (Blueprint $table) {
            foreach (['invite_error', 'invited_at', 'invite_status'] as $col) {
                if (Schema::hasColumn('project_repository_users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        Schema::table('user_git_identities', function (Blueprint $table) {
            if (Schema::hasColumn('user_git_identities', 'reviewed_by')) {
                $table->dropConstrainedForeignId('reviewed_by');
            }
            foreach (['reviewed_at', 'admin_notes', 'employee_note', 'status', 'email'] as $col) {
                if (Schema::hasColumn('user_git_identities', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
