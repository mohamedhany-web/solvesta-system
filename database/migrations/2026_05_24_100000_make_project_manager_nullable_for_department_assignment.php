<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('projects') || ! Schema::hasColumn('projects', 'project_manager_id')) {
            return;
        }

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['project_manager_id']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('project_manager_id')->nullable()->change();
            $table->foreign('project_manager_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('projects') || ! Schema::hasColumn('projects', 'project_manager_id')) {
            return;
        }

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['project_manager_id']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->foreignId('project_manager_id')->nullable(false)->change();
            $table->foreign('project_manager_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
};
