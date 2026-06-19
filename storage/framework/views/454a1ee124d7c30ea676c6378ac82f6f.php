<?php $__env->startSection('page-title', 'إضافة موظف جديد'); ?>

<?php $__env->startSection('content'); ?>
<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full font-tajawal">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'إضافة موظف جديد',
        'subtitle' => 'تسجيل بيانات الموظف وربطه بحساب مستخدم في النظام',
        'icon' => 'users',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex flex-wrap justify-end gap-3 mb-6 -mt-2">
        <a href="<?php echo e(route('employees.index')); ?>" class="inline-flex items-center gap-2 border border-gray-300 bg-white px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            كل الموظفين
        </a>
    </div>

    <?php if(session('error')): ?>
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <p class="font-bold mb-1">يرجى تصحيح الأخطاء:</p>
        <ul class="list-disc list-inside"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8">
            <?php echo $__env->make('employees.partials.form', [
                'action' => route('employees.store'),
                'method' => 'POST',
                'submitLabel' => 'إضافة الموظف',
                'cancelUrl' => route('employees.index'),
                'departments' => $departments,
                'users' => $users,
                'roleLabels' => $roleLabels,
                'assignableRoles' => $assignableRoles,
                'preselectedDepartmentId' => $preselectedDepartmentId ?? null,
                'themeColor' => $themeColor,
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
        <div class="xl:col-span-4">
            <?php echo $__env->make('employees.partials.form-sidebar', [
                'users' => $users,
                'themeColor' => $themeColor,
            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>
</div>
<?php $__env->startPush('scripts'); ?>
<script>
const kpiLabels = <?php echo json_encode(\App\Services\DepartmentProfileService::kpiProfileLabels(), 15, 512) ?>;
async function loadDepartmentProfile(deptId) {
    const box = document.getElementById('dept-profile-box');
    if (!deptId) { box?.classList.add('hidden'); return; }
    const res = await fetch(`<?php echo e(url('employees/department-profile')); ?>/${deptId}`);
    const data = await res.json();
    box?.classList.remove('hidden');
    document.getElementById('dept-profile-kpi').textContent = kpiLabels[data.kpi_profile] || data.kpi_profile;
    document.getElementById('dept-profile-modules').textContent = 'القائمة: ' + (data.sidebar_modules || []).join('، ');
    if (data.default_position) document.getElementById('position').value = data.default_position;
    if (data.default_role) document.getElementById('system_role').value = data.default_role;
    const levelSelect = document.getElementById('career_level');
    levelSelect.innerHTML = '<option value="">يُحدد تلقائياً</option>';
    (data.levels || []).forEach(l => {
        const o = document.createElement('option');
        o.value = l; o.textContent = l;
        levelSelect.appendChild(o);
    });
    if (data.levels?.length) levelSelect.value = data.levels[0];
}
document.addEventListener('DOMContentLoaded', () => {
    const dept = document.getElementById('department_id');
    if (dept?.value) loadDepartmentProfile(dept.value);
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\employees\create.blade.php ENDPATH**/ ?>