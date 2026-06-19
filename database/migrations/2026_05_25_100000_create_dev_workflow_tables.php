<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_repositories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 32)->default('github');
            $table->string('owner');
            $table->string('repo_name');
            $table->string('default_branch')->default('main');
            $table->string('repo_url')->nullable();
            $table->unsignedBigInteger('github_repo_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['project_id', 'provider', 'owner', 'repo_name']);
        });

        Schema::create('project_repository_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_repository_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('access_level', 16)->default('write'); // read, write, admin
            $table->timestamps();

            $table->unique(['project_repository_id', 'user_id']);
        });

        Schema::create('user_git_identities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 32)->default('github');
            $table->string('username');
            $table->string('profile_url')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'provider']);
        });

        Schema::create('git_branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_repository_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('branch_type', 16)->default('feature'); // feature, bugfix, hotfix, release
            $table->string('base_branch')->default('main');
            $table->string('status', 16)->default('active'); // active, merged, closed
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('github_sha')->nullable();
            $table->timestamp('merged_at')->nullable();
            $table->timestamps();

            $table->unique(['project_repository_id', 'name']);
        });

        Schema::create('pull_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_repository_id')->constrained()->cascadeOnDelete();
            $table->foreignId('git_branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('task_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('number')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('source_branch');
            $table->string('target_branch')->default('main');
            $table->string('status', 24)->default('open'); // draft, open, changes_requested, approved, merged, closed
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('review_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('merged_at')->nullable();
            $table->unsignedBigInteger('github_pr_id')->nullable();
            $table->string('pr_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pull_requests');
        Schema::dropIfExists('git_branches');
        Schema::dropIfExists('user_git_identities');
        Schema::dropIfExists('project_repository_users');
        Schema::dropIfExists('project_repositories');
    }
};
