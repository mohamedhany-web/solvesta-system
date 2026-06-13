<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('client_system_projects')) {
        Schema::create('client_system_projects', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code')->unique();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status', 32)->default('active'); // active, on_hold, completed, archived
            $table->text('admin_notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by_client_account_id')->nullable()->constrained('client_accounts')->nullOnDelete();
            $table->timestamps();

            $table->index(['client_id', 'status']);
        });
        }

        if (! Schema::hasTable('client_system_features')) {
        Schema::create('client_system_features', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code')->unique();
            $table->foreignId('client_system_project_id')->constrained()->cascadeOnDelete();
            $table->string('type', 32)->default('feature'); // feature, bug, improvement
            $table->string('title');
            $table->text('description');
            $table->string('status', 32)->default('submitted');
            $table->string('priority', 16)->default('medium');
            $table->foreignId('submitted_by_client_account_id')->nullable()->constrained('client_accounts')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['client_system_project_id', 'status']);
        });
        }

        if (! Schema::hasTable('client_system_feature_updates')) {
        Schema::create('client_system_feature_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_system_feature_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('body');
            $table->string('visibility', 16)->default('client');
            $table->unsignedBigInteger('created_by_user_id')->nullable();
            $table->unsignedBigInteger('created_by_client_account_id')->nullable();
            $table->timestamps();

            $table->foreign('created_by_user_id', 'csfu_user_fk')->references('id')->on('users')->nullOnDelete();
            $table->foreign('created_by_client_account_id', 'csfu_client_acct_fk')->references('id')->on('client_accounts')->nullOnDelete();
            $table->index(['client_system_feature_id', 'visibility']);
        });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('client_system_feature_updates');
        Schema::dropIfExists('client_system_features');
        Schema::dropIfExists('client_system_projects');
    }
};
