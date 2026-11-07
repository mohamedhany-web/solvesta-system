<?php

use App\Services\DepartmentProfileService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('departments')) {
            return;
        }

        $profile = DepartmentProfileService::PROFILES['DES'];
        $now = now();

        $existing = DB::table('departments')->where('code', 'DES')->first();

        $payload = [
            'name' => $profile['name'],
            'description' => $existing->description ?? $profile['name'],
            'sidebar_modules' => json_encode($profile['sidebar_modules'], JSON_UNESCAPED_UNICODE),
            'default_role' => $profile['default_role'],
            'default_position' => $profile['default_position'],
            'kpi_profile' => $profile['kpi_profile'],
            'career_track' => $profile['career_track'],
            'is_active' => true,
            'updated_at' => $now,
        ];

        if ($existing) {
            DB::table('departments')->where('id', $existing->id)->update($payload);
        } else {
            DB::table('departments')->insert(array_merge($payload, [
                'code' => 'DES',
                'created_at' => $now,
            ]));
        }
    }

    public function down(): void
    {
        // Keep department row; only clear profile defaults if needed.
        if (! Schema::hasTable('departments')) {
            return;
        }

        DB::table('departments')->where('code', 'DES')->update([
            'default_role' => 'employee',
            'kpi_profile' => 'default',
            'career_track' => 'general',
            'updated_at' => now(),
        ]);
    }
};
