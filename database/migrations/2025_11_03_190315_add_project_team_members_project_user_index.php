<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $indexName = 'project_team_members_project_user_index';

        if ($this->indexExists('project_team_members', $indexName)) {
            return;
        }

        Schema::table('project_team_members', function (Blueprint $table) use ($indexName) {
            $table->index(['project_id', 'user_id'], $indexName);
        });
    }

    private function indexExists(string $table, string $index): bool
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            return collect(DB::select("PRAGMA index_list({$table})"))
                ->contains(fn ($row) => $row->name === $index);
        }

        $result = DB::select(
            'SELECT COUNT(*) as c FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ?',
            [$table, $index]
        );

        return ($result[0]->c ?? 0) > 0;
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
