<?php

use App\Services\GitHubOrgSchema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('departments', 'github_team_slug')) {
            Schema::table('departments', function (Blueprint $table) {
                $table->string('github_team_slug', 80)->nullable()->after('code');
            });
        }

        GitHubOrgSchema::syncDepartmentSlugs();
    }

    public function down(): void
    {
        if (Schema::hasColumn('departments', 'github_team_slug')) {
            Schema::table('departments', function (Blueprint $table) {
                $table->dropColumn('github_team_slug');
            });
        }
    }
};
