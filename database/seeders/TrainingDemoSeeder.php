<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Training;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingDemoSeeder extends Seeder
{
    public function run(): void
    {
        $department = Department::query()->orderBy('id')->first();
        if (! $department) {
            $this->command->warn('TrainingDemoSeeder: لا توجد أقسام — تم تخطي إنشاء برامج تجريبية.');

            return;
        }

        $instructorId = User::query()
            ->where(function ($q) {
                $q->whereHas('employee')
                    ->orWhereHas('roles', function ($r) {
                        $r->whereIn('name', ['super_admin', 'admin', 'hr', 'project_manager']);
                    });
            })
            ->value('id');

        $base = now()->addWeek()->startOfDay();
        $rows = [
            ['title' => 'برنامج تجريبي — أساسيات التواصل في العمل', 'description' => 'بيانات تجريبية من الـ Seeder.', 'type' => 'workshop', 'status' => 'planned', 's' => 0, 'e' => 2, 'max' => 15, 'cost' => 0],
            ['title' => 'برنامج تجريبي — أمن المعلومات', 'description' => 'بيانات تجريبية من الـ Seeder.', 'type' => 'internal', 'status' => 'planned', 's' => 10, 'e' => 12, 'max' => 20, 'cost' => 0],
            ['title' => 'برنامج تجريبي — أدوات الإنتاجية', 'description' => 'بيانات تجريبية من الـ Seeder.', 'type' => 'online', 'status' => 'ongoing', 's' => 20, 'e' => 22, 'max' => 30, 'cost' => 100],
        ];

        DB::transaction(function () use ($rows, $base, $department, $instructorId) {
            foreach ($rows as $r) {
                Training::create([
                    'title' => $r['title'],
                    'description' => $r['description'],
                    'type' => $r['type'],
                    'status' => $r['status'],
                    'start_date' => $base->copy()->addDays($r['s'])->toDateString(),
                    'end_date' => $base->copy()->addDays($r['e'])->toDateString(),
                    'max_participants' => $r['max'],
                    'cost' => $r['cost'],
                    'department_id' => $department->id,
                    'instructor_id' => $instructorId,
                ]);
            }
        });

        $this->command->info('TrainingDemoSeeder: تم إنشاء 3 برامج تدريبية تجريبية.');
    }
}
