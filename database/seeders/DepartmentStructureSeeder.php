<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Services\DepartmentProfileService;
use Illuminate\Database\Seeder;

class DepartmentStructureSeeder extends Seeder
{
    public function run(): void
    {
        $roots = [];

        foreach (DepartmentProfileService::PROFILES as $code => $profile) {
            if (str_starts_with($code, 'DEV-')) {
                continue;
            }

            $dept = Department::updateOrCreate(
                ['code' => $code],
                [
                    'name' => $profile['name'],
                    'description' => $profile['name'],
                    'parent_id' => null,
                    'sidebar_modules' => $profile['sidebar_modules'],
                    'default_role' => $profile['default_role'],
                    'default_position' => $profile['default_position'],
                    'kpi_profile' => $profile['kpi_profile'],
                    'career_track' => $profile['career_track'],
                    'is_active' => true,
                ]
            );

            $roots[$code] = $dept->id;
        }

        $devParent = Department::updateOrCreate(
            ['code' => 'DEV'],
            [
                'name' => DepartmentProfileService::PROFILES['DEV']['name'],
                'description' => 'القسم الرئيسي للمبرمجين',
                'parent_id' => null,
                'sidebar_modules' => DepartmentProfileService::PROFILES['DEV']['sidebar_modules'],
                'default_role' => 'developer',
                'default_position' => 'مبرمج',
                'kpi_profile' => 'developer_strict',
                'career_track' => 'technical',
                'is_active' => true,
            ]
        );

        foreach (['DEV-WEB', 'DEV-MOB'] as $childCode) {
            $p = DepartmentProfileService::PROFILES[$childCode];
            Department::updateOrCreate(
                ['code' => $childCode],
                [
                    'name' => $p['name'],
                    'description' => $p['name'],
                    'parent_id' => $devParent->id,
                    'sidebar_modules' => $p['sidebar_modules'],
                    'default_role' => $p['default_role'],
                    'default_position' => $p['default_position'],
                    'kpi_profile' => $p['kpi_profile'],
                    'career_track' => $p['career_track'],
                    'is_active' => true,
                ]
            );
        }
    }
}
