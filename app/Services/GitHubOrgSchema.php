<?php

namespace App\Services;

use App\Models\Department;
use App\Models\User;

class GitHubOrgSchema
{
    public const ORG = 'Solvesta-Company';

    /**
     * Canonical delivery teams. slug is GitHub team slug.
     * department_codes map to ERP departments.code.
     *
     * @var array<int, array<string, mixed>>
     */
    public const TEAMS = [
        [
            'slug' => 'engineering',
            'name' => 'Engineering',
            'name_ar' => 'التطوير',
            'parent' => null,
            'department_codes' => ['DEV'],
            'roles' => ['developer'],
            'repo_role' => 'push',
            'description' => 'قسم التطوير — ERP: DEV. Cross-domain product & platform.',
        ],
        [
            'slug' => 'web',
            'name' => 'Web',
            'name_ar' => 'تطوير الويب',
            'parent' => 'engineering',
            'department_codes' => ['DEV-WEB'],
            'roles' => ['developer'],
            'repo_role' => 'push',
            'description' => 'تطوير الويب — ERP: DEV-WEB',
        ],
        [
            'slug' => 'mobile',
            'name' => 'Mobile',
            'name_ar' => 'تطوير الموبايل',
            'parent' => 'engineering',
            'department_codes' => ['DEV-MOB'],
            'roles' => ['developer'],
            'repo_role' => 'push',
            'description' => 'تطوير الموبايل — ERP: DEV-MOB',
        ],
        [
            'slug' => 'crm',
            'name' => 'CRM',
            'name_ar' => 'منتج CRM / ERP',
            'parent' => null,
            'department_codes' => ['DEV', 'DEV-WEB'],
            'roles' => ['developer', 'project_manager'],
            'repo_role' => 'admin',
            'description' => 'Solvesta CRM & ERP product — ERP: DEV / PMO',
        ],
        [
            'slug' => 'salla-themes',
            'name' => 'Salla Themes',
            'name_ar' => 'ثيمات سلة',
            'parent' => null,
            'department_codes' => ['DES'],
            'roles' => ['designer', 'developer'],
            'repo_role' => 'admin',
            'description' => 'Salla Twilight themes — ERP: DES',
        ],
        [
            'slug' => 'design',
            'name' => 'Design',
            'name_ar' => 'التصميم',
            'parent' => null,
            'department_codes' => ['DES'],
            'roles' => ['designer'],
            'repo_role' => 'push',
            'description' => 'التصميم الجرافيكي والواجهات — ERP: DES',
        ],
        [
            'slug' => 'qa',
            'name' => 'QA',
            'name_ar' => 'ضمان الجودة',
            'parent' => null,
            'department_codes' => ['QA'],
            'roles' => ['employee'],
            'repo_role' => 'push',
            'description' => 'ضمان الجودة والاختبار — ERP: QA',
        ],
        [
            'slug' => 'pmo',
            'name' => 'PMO',
            'name_ar' => 'إدارة المشاريع',
            'parent' => null,
            'department_codes' => [],
            'roles' => ['project_manager'],
            'repo_role' => 'maintain',
            'description' => 'مديرو المشاريع — مراجعة PR والتسليم',
        ],
        [
            'slug' => 'product',
            'name' => 'Product',
            'name_ar' => 'المنتج',
            'parent' => null,
            'department_codes' => ['ADMIN'],
            'roles' => ['admin', 'project_manager'],
            'repo_role' => 'pull',
            'description' => 'الاستراتيجية والتعبئة والـ go-to-market',
        ],
        [
            'slug' => 'sales',
            'name' => 'Sales',
            'name_ar' => 'المبيعات',
            'parent' => null,
            'department_codes' => ['SALES', 'SAL'],
            'roles' => ['sales_rep'],
            'repo_role' => 'pull',
            'description' => 'المبيعات — ERP: SALES — وصول قراءة عند الحاجة',
        ],
        [
            'slug' => 'support',
            'name' => 'Support',
            'name_ar' => 'الدعم الفني',
            'parent' => null,
            'department_codes' => ['SUP'],
            'roles' => ['support'],
            'repo_role' => 'pull',
            'description' => 'دعم العملاء — ERP: SUP',
        ],
    ];

    /**
     * @var array<string, string>
     */
    public const REPO_OWNERS = [
        'solvestacrm' => 'crm',
        'solvesta-themes' => 'salla-themes',
        'luxora-fashion' => 'salla-themes',
        'electronics' => 'salla-themes',
        '.github' => 'engineering',
        'solvesta-system' => 'crm',
    ];

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function deliveryStages(): array
    {
        return [
            ['key' => 'task', 'title' => 'مهمة في النظام', 'detail' => 'PMO ينشئ Task ويعين المطوّر من قسمه.'],
            ['key' => 'branch', 'title' => 'فرع GitHub', 'detail' => 'من صفحة المهمة: feature/… أو bugfix/… على مستودع المشروع.'],
            ['key' => 'pr', 'title' => 'Pull Request', 'detail' => 'المطوّر يفتح PR بالقالب — يُراجع من فريق المنتج المالك.'],
            ['key' => 'review', 'title' => 'مراجعة الكود', 'detail' => 'موافقة واحدة على الأقل + CODEOWNERS. لا دمج مباشر على main/develop.'],
            ['key' => 'qa', 'title' => 'QA', 'detail' => 'بعد الدمج تنتقل المهمة إلى المراجعة/الجودة في النظام.'],
            ['key' => 'release', 'title' => 'تسليم', 'detail' => 'نشر بإشراف PMO — الحالة تُحدَّث على المشروع في الـ ERP.'],
        ];
    }

    public static function org(): string
    {
        return GitHubSettings::organization() ?: config('services.github.org') ?: self::ORG;
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function teamBySlug(string $slug): ?array
    {
        foreach (self::TEAMS as $team) {
            if ($team['slug'] === $slug) {
                return $team;
            }
        }

        return null;
    }

    /**
     * GitHub team slug for an ERP department (and optional user role).
     */
    public static function teamSlugFor(?Department $department, ?User $user = null): ?string
    {
        $code = strtoupper(trim((string) ($department?->code ?? '')));

        if ($department?->github_team_slug) {
            return $department->github_team_slug;
        }

        if ($code !== '') {
            foreach (self::TEAMS as $team) {
                if (in_array($code, $team['department_codes'], true)) {
                    return $team['slug'];
                }
            }

            if (str_starts_with($code, 'DEV')) {
                return 'engineering';
            }
        }

        if ($user) {
            foreach (self::TEAMS as $team) {
                if ($user->hasAnyRole($team['roles'])) {
                    return $team['slug'];
                }
            }
        }

        return null;
    }

    /**
     * @return list<string>
     */
    public static function teamSlugsForUser(User $user): array
    {
        $user->loadMissing('employee.department');
        $slugs = [];

        $primary = self::teamSlugFor($user->employee?->department, $user);
        if ($primary) {
            $slugs[] = $primary;
        }

        $parent = self::teamBySlug($primary ?? '');
        if ($parent && ! empty($parent['parent'])) {
            $slugs[] = $parent['parent'];
        }

        if ($user->hasRole('project_manager') && ! in_array('pmo', $slugs, true)) {
            $slugs[] = 'pmo';
        }

        return array_values(array_unique($slugs));
    }

    /**
     * @param  array<int, array<string, mixed>>  $githubTeams
     * @return array<int, array<string, mixed>>
     */
    public static function blueprint(array $githubTeams = []): array
    {
        $bySlug = [];
        foreach ($githubTeams as $team) {
            if (! empty($team['slug'])) {
                $bySlug[$team['slug']] = $team;
            }
        }

        $departments = Department::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'code', 'github_team_slug', 'parent_id']);

        $rows = [];
        foreach (self::TEAMS as $team) {
            $gh = $bySlug[$team['slug']] ?? null;
            $mapped = $departments->filter(function ($d) use ($team) {
                $code = strtoupper((string) $d->code);
                return $d->github_team_slug === $team['slug']
                    || in_array($code, $team['department_codes'], true);
            })->values();

            $rows[] = array_merge($team, [
                'exists_on_github' => (bool) $gh,
                'github' => $gh,
                'departments' => $mapped,
                'html_url' => $gh['html_url'] ?? 'https://github.com/orgs/'.self::org().'/teams/'.$team['slug'],
            ]);
        }

        return $rows;
    }

    public static function syncDepartmentSlugs(): int
    {
        $updated = 0;

        foreach (self::TEAMS as $team) {
            if ($team['department_codes'] === []) {
                continue;
            }

            $updated += Department::query()
                ->whereIn('code', $team['department_codes'])
                ->where(function ($q) use ($team) {
                    $q->whereNull('github_team_slug')
                        ->orWhere('github_team_slug', '!=', $team['slug']);
                })
                ->update(['github_team_slug' => $team['slug']]);
        }

        Department::query()
            ->where('code', 'like', 'DEV%')
            ->whereNull('github_team_slug')
            ->update(['github_team_slug' => 'engineering']);

        return $updated;
    }
}
