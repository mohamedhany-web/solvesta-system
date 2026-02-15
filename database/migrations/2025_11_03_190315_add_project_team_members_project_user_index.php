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
        Schema::table('project_team_members', function (Blueprint $table) {
            // Index optimization for project membership queries used by messaging
            $table->index(['project_id', 'user_id'], 'project_team_members_project_user_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_team_members', function (Blueprint $table) {
            $table->dropIndex('project_team_members_project_user_index');
        });
    }
};
