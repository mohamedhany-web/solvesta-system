<?php

namespace App\Console\Commands;

use App\Services\GitHubOrgSchema;
use App\Services\GitHubService;
use App\Services\GitHubSettings;
use Illuminate\Console\Command;

class SyncGitHubOrgSchemaCommand extends Command
{
    protected $signature = 'solvesta:sync-github-org
                            {--dry-run : عرض الفرق دون إنشاءها على GitHub}';

    protected $description = 'مزامنة سكيما فرق GitHub مع أقسام الـ ERP';

    public function handle(GitHubService $github): int
    {
        $org = GitHubOrgSchema::org();
        GitHubOrgSchema::syncDepartmentSlugs();
        $this->info("منظمة: {$org}");
        $this->info('تم ربط github_team_slug بأقسام النظام.');

        $parentsFirst = collect(GitHubOrgSchema::TEAMS)->sortBy(fn ($t) => $t['parent'] ? 1 : 0);

        if ($this->option('dry-run') || ! GitHubSettings::isConfigured()) {
            foreach ($parentsFirst as $team) {
                $parent = $team['parent'] ? " ← {$team['parent']}" : '';
                $this->line("  · {$team['slug']} ({$team['name_ar']}){$parent}");
            }

            if (! GitHubSettings::isConfigured()) {
                $this->warn('GitHub غير مربوط في النظام — اربط Organization من /github ثم أعد الأمر بدون --dry-run.');
            }

            return self::SUCCESS;
        }

        $existing = $github->listOrgTeams($org);
        $slugToId = [];
        foreach ($existing as $row) {
            if (! empty($row['slug']) && ! empty($row['id'])) {
                $slugToId[$row['slug']] = (int) $row['id'];
            }
        }

        foreach ($parentsFirst as $team) {
            $result = $github->ensureOrgTeam($org, $team, $slugToId);
            $this->{$result['ok'] ? 'info' : 'error'}($result['message']);

            if ($result['ok'] && empty($slugToId[$team['slug']])) {
                $fresh = $github->listOrgTeams($org);
                foreach ($fresh as $row) {
                    if (($row['slug'] ?? '') === $team['slug']) {
                        $slugToId[$team['slug']] = (int) $row['id'];
                    }
                }
            }
        }

        return self::SUCCESS;
    }
}
