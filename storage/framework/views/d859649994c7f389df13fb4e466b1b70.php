<?php $__env->startSection('page-title', 'تفاصيل القسم - '.$department->name); ?>

<?php $__env->startSection('content'); ?>
<?php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $modules = $department->effectiveSidebarModules();
    $deptHead = $department->manager?->user;
?>
<div class="w-full max-w-full font-tajawal">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => $department->name,
        'subtitle' => $department->code.($department->parent ? ' · تابع لـ '.$department->parent->name : ''),
        'icon' => 'briefcase',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap justify-end gap-3 mb-6 -mt-2">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-departments')): ?>
        <a href="<?php echo e(route('departments.edit', $department)); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm" style="background: <?php echo e($themeColor); ?>">
            تعديل إعدادات القسم
        </a>
        <?php endif; ?>
        <?php if($deptHead): ?>
        <a href="<?php echo e(route('department-manager.dashboard')); ?>" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            لوحة رئيس القسم
        </a>
        <?php endif; ?>
        <a href="<?php echo e(route('departments.index')); ?>" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            كل الأقسام
        </a>
    </div>

    <?php if(session('success')): ?>
    <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800"><?php echo e(session('error')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <ul class="list-disc list-inside"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-6">
        <?php $__currentLoopData = [
            ['الموظفون', $stats['total_employees'], 'text-blue-700'],
            ['قادة الفرق', $stats['team_leads'], 'text-emerald-700'],
            ['مشاريع نشطة', $stats['active_projects'], 'text-indigo-700'],
            ['بانتظار فريق', $stats['pending_team_projects'], 'text-amber-700'],
            ['مهام معلقة', $stats['pending_tasks'], 'text-orange-700'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm text-center">
            <p class="text-xs text-gray-500 mb-1"><?php echo e($label); ?></p>
            <p class="text-2xl font-bold <?php echo e($color); ?>"><?php echo e($value); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-departments')): ?>
    <div class="rounded-2xl border border-indigo-100 bg-indigo-50/60 p-5 mb-6 text-sm text-indigo-900">
        <p class="font-bold mb-1">إعداد القسم للإدارة</p>
        <p>من هنا تُضاف الموظفون، يُعيَّن <strong>رئيس القسم</strong>، ويُبنى <strong>هيكل الفريق</strong> وفِرق المشاريع. رئيس القسم يستلم كل شيء جاهزاً ويبدأ بتوزيع المهام من لوحته.</p>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8 space-y-6">

            
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="font-bold text-lg text-gray-900">هيكل الفريق والتدرج الوظيفي</h2>
                        <p class="text-sm text-gray-500 mt-0.5">موظف → Team Lead → رئيس القسم → الإدارة العليا</p>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700"><?php echo e($department->employees->count()); ?> موظف</span>
                </div>

                <?php if($department->employees->isEmpty()): ?>
                <div class="p-10 text-center text-gray-500">لا يوجد موظفون — أضف موظفين من اللوحة الجانبية.</div>
                <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50/80">
                            <tr>
                                <th class="px-4 py-3 text-right font-bold text-gray-600">الموظف</th>
                                <th class="px-4 py-3 text-right font-bold text-gray-600">المسمى</th>
                                <th class="px-4 py-3 text-right font-bold text-gray-600">Team Lead</th>
                                <th class="px-4 py-3 text-right font-bold text-gray-600">المشرف</th>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-departments')): ?><th class="px-4 py-3 text-right font-bold text-gray-600">إعداد</th><?php endif; ?>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php $__currentLoopData = $department->employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-900"><?php echo e($emp->first_name); ?> <?php echo e($emp->last_name); ?></p>
                                    <p class="text-xs text-gray-500"><?php echo e($emp->career_level ?? '—'); ?></p>
                                </td>
                                <td class="px-4 py-3 text-gray-600"><?php echo e($emp->position); ?></td>
                                <td class="px-4 py-3">
                                    <?php if($emp->is_team_lead): ?>
                                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-1 rounded-lg">قائد فريق</span>
                                    <?php else: ?>
                                    <span class="text-gray-400">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-gray-700"><?php echo e($emp->supervisor?->name ?? ($deptHead?->name ?? 'رئيس القسم')); ?></td>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-departments')): ?>
                                <td class="px-4 py-3">
                                    <form method="POST" action="<?php echo e(route('departments.team.update', [$department, $emp])); ?>" class="flex flex-wrap items-center gap-2">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                        <label class="inline-flex items-center gap-1 text-xs whitespace-nowrap">
                                            <input type="checkbox" name="is_team_lead" value="1" <?php if($emp->is_team_lead): echo 'checked'; endif; ?> class="rounded">
                                            TL
                                        </label>
                                        <select name="supervisor_user_id" class="border border-gray-200 rounded-lg px-2 py-1 text-xs min-w-[120px]">
                                            <option value="">رئيس القسم</option>
                                            <?php $__currentLoopData = $supervisorOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($u->id !== $emp->user_id): ?>
                                                <option value="<?php echo e($u->id); ?>" <?php if($emp->supervisor_user_id == $u->id): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <button type="submit" class="text-xs font-bold px-2 py-1 rounded-lg text-white" style="background:<?php echo e($themeColor); ?>">حفظ</button>
                                    </form>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>

            
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
                    <h2 class="font-bold text-lg text-gray-900">مشاريع القسم وفِرق التنفيذ</h2>
                    <p class="text-sm text-gray-500 mt-0.5">تعيين Team Leader وأعضاء كل مشروع — يصل الجاهز لرئيس القسم للتوزيع</p>
                </div>
                <div class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $department->projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-5">
                        <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
                            <div>
                                <a href="<?php echo e(route('projects.show', $project)); ?>" class="font-bold text-gray-900 hover:underline"><?php echo e($project->name); ?></a>
                                <p class="text-xs text-gray-500 mt-1"><?php echo e($project->client?->name ?? 'بدون عميل'); ?> · <?php echo e($project->tasks->count()); ?> مهمة</p>
                            </div>
                            <?php if($project->project_manager_id): ?>
                            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-1 rounded-lg">فريق مُعيَّن</span>
                            <?php else: ?>
                            <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2 py-1 rounded-lg">بانتظار تعيين الفريق</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">
                            قائد الفريق: <strong><?php echo e($project->projectManager?->name ?? '—'); ?></strong>
                            <?php if($project->teamMembers->isNotEmpty()): ?>
                            · الأعضاء: <?php echo e($project->teamMembers->pluck('name')->join('، ')); ?>

                            <?php endif; ?>
                        </p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-departments')): ?>
                        <details class="mt-2">
                            <summary class="text-xs font-bold cursor-pointer" style="color:<?php echo e($themeColor); ?>">تعيين / تعديل فريق المشروع</summary>
                            <form method="POST" action="<?php echo e(route('departments.projects.team', [$department, $project])); ?>" class="grid sm:grid-cols-2 gap-3 mt-3 p-4 rounded-xl bg-gray-50 border">
                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                <div class="sm:col-span-2">
                                    <label class="text-xs font-bold text-gray-600 block mb-1">قائد الفريق (Team Leader)</label>
                                    <select name="project_manager_id" required class="w-full border rounded-xl px-3 py-2 text-sm">
                                        <option value="">اختر...</option>
                                        <?php $__currentLoopData = $supervisorOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($u->id); ?>" <?php if($project->project_manager_id == $u->id): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="text-xs font-bold text-gray-600 block mb-1">أعضاء الفريق</label>
                                    <?php $selected = $project->teamMembers->pluck('id')->all(); ?>
                                    <select name="team_members[]" multiple class="w-full border rounded-xl px-3 py-2 text-sm min-h-[100px]">
                                        <?php $__currentLoopData = $supervisorOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($u->id); ?>" <?php if(in_array($u->id, $selected)): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <button type="submit" class="sm:col-span-2 py-2 rounded-xl text-white text-sm font-bold" style="background:<?php echo e($themeColor); ?>">حفظ فريق المشروع</button>
                            </form>
                        </details>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-10 text-center text-gray-500">لا توجد مشاريع مرتبطة بهذا القسم.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 space-y-4">
            
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-4">رئيس القسم</h3>
                <?php if($department->manager): ?>
                <div class="text-center mb-4">
                    <div class="h-16 w-16 mx-auto rounded-2xl flex items-center justify-center text-white text-xl font-bold mb-2" style="background:<?php echo e($themeColor); ?>">
                        <?php echo e(mb_substr($department->manager->first_name, 0, 1)); ?>

                    </div>
                    <p class="font-bold"><?php echo e($department->manager->first_name); ?> <?php echo e($department->manager->last_name); ?></p>
                    <p class="text-sm text-gray-500"><?php echo e($department->manager->position); ?></p>
                </div>
                <?php else: ?>
                <p class="text-sm text-amber-700 bg-amber-50 rounded-xl p-3 mb-4">لم يُعيَّن رئيس قسم بعد — عيّنه لتسليم الفريق له.</p>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-departments')): ?>
                <form method="POST" action="<?php echo e(route('departments.set-manager', $department)); ?>" class="space-y-2">
                    <?php echo csrf_field(); ?>
                    <select name="manager_id" class="w-full border rounded-xl px-3 py-2 text-sm">
                        <option value="">بدون رئيس قسم</option>
                        <?php $__currentLoopData = $department->employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($emp->id); ?>" <?php if($department->manager_id == $emp->id): echo 'selected'; endif; ?>><?php echo e($emp->first_name); ?> <?php echo e($emp->last_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="submit" class="w-full py-2 rounded-xl text-white text-sm font-bold" style="background:<?php echo e($themeColor); ?>">تعيين رئيس القسم</button>
                </form>
                <?php endif; ?>
            </div>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-departments')): ?>
            
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-3">إضافة موظف للقسم</h3>
                <form method="POST" action="<?php echo e(route('departments.assign-employee', $department)); ?>" class="space-y-3">
                    <?php echo csrf_field(); ?>
                    <select name="employee_id" required class="w-full border rounded-xl px-3 py-2 text-sm">
                        <option value="">اختر موظفاً...</option>
                        <?php $__empty_1 = true; $__currentLoopData = $availableEmployees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <option value="<?php echo e($emp->id); ?>"><?php echo e($emp->first_name); ?> <?php echo e($emp->last_name); ?> — <?php echo e($emp->department?->name ?? 'بدون قسم'); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <option value="" disabled>كل الموظفين النشطين في أقسام أخرى أو أضف موظفاً جديداً</option>
                        <?php endif; ?>
                    </select>
                    <button type="submit" class="w-full py-2 rounded-xl text-white text-sm font-bold" style="background:<?php echo e($themeColor); ?>">إضافة للقسم</button>
                </form>
                <a href="<?php echo e(route('employees.create', ['department_id' => $department->id])); ?>" class="block text-center text-xs font-bold mt-3 hover:underline" style="color:<?php echo e($themeColor); ?>">+ إنشاء موظف جديد</a>
            </div>

            <?php if($unassignedProjects->isNotEmpty()): ?>
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-3">ربط مشروع بالقسم</h3>
                <form method="POST" action="<?php echo e(route('departments.assign-project', $department)); ?>" class="space-y-3">
                    <?php echo csrf_field(); ?>
                    <select name="project_id" required class="w-full border rounded-xl px-3 py-2 text-sm">
                        <option value="">اختر مشروعاً...</option>
                        <?php $__currentLoopData = $unassignedProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($p->id); ?>"><?php echo e($p->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="submit" class="w-full py-2 rounded-xl border text-sm font-bold hover:bg-gray-50">ربط المشروع</button>
                </form>
            </div>
            <?php endif; ?>
            <?php endif; ?>

            
            <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm text-sm space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-500">الحالة</span>
                    <span class="font-bold <?php echo e($department->is_active ? 'text-green-700' : 'text-red-700'); ?>"><?php echo e($department->is_active ? 'نشط' : 'متوقف'); ?></span>
                </div>
                <?php if($department->default_position): ?>
                <div class="flex justify-between"><span class="text-gray-500">المسمى الافتراضي</span><span class="font-semibold"><?php echo e($department->default_position); ?></span></div>
                <?php endif; ?>
                <?php if($department->kpi_profile): ?>
                <div class="flex justify-between gap-2"><span class="text-gray-500 shrink-0">KPI</span><span class="text-xs font-semibold text-left"><?php echo e((\App\Services\DepartmentProfileService::kpiProfileLabels())[$department->kpi_profile] ?? $department->kpi_profile); ?></span></div>
                <?php endif; ?>
                <?php if(count($modules) > 0): ?>
                <div class="pt-2 border-t">
                    <p class="text-xs font-bold text-gray-500 mb-2">السايدبار</p>
                    <div class="flex flex-wrap gap-1">
                        <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="text-xs px-2 py-0.5 rounded bg-slate-100"><?php echo e(\App\Models\Department::SIDEBAR_MODULES[$m] ?? $m); ?></span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-departments')): ?>
            <?php if($department->employees->isNotEmpty() && ($otherDepartments ?? collect())->isNotEmpty()): ?>
            <div class="rounded-2xl border border-red-100 bg-red-50/40 p-5 text-sm">
                <p class="font-bold text-red-800 mb-2">نقل موظف من القسم</p>
                <form method="POST" action="<?php echo e(route('departments.remove-employee', [$department, $department->employees->first()])); ?>" id="remove-emp-form" class="space-y-2" onsubmit="return confirm('نقل الموظف من القسم؟')">
                    <?php echo csrf_field(); ?>
                    <select class="w-full border rounded-xl px-3 py-2 text-sm" onchange="document.getElementById('remove-emp-form').action=this.value">
                        <?php $__currentLoopData = $department->employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e(route('departments.remove-employee', [$department, $emp])); ?>"><?php echo e($emp->first_name); ?> <?php echo e($emp->last_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <select name="target_department_id" required class="w-full border rounded-xl px-3 py-2 text-sm">
                        <?php $__currentLoopData = $otherDepartments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($d->id); ?>"><?php echo e($d->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="submit" class="w-full py-2 rounded-xl text-sm font-bold text-red-700 border border-red-200 hover:bg-red-50">نقل لقسم آخر</button>
                </form>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\departments\show.blade.php ENDPATH**/ ?>