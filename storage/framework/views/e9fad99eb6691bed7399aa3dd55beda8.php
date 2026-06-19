<?php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); ?>
<div class="w-full max-w-full font-tajawal">
    <?php echo $__env->make('partials.erp-page-header', [
        'title' => 'مرحباً، ' . auth()->user()->name,
        'subtitle' => 'الدور: ' . (\App\Helpers\RoleHelper::getRoleName($user_role ?? 'employee') ?? 'موظف') . ' — ' . now()->locale('ar')->translatedFormat('l، d F Y'),
        'icon' => 'chart',
    ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php if(isset($my_projects) || isset($my_tasks)): ?>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <?php $__currentLoopData = array_filter([
            isset($my_projects) ? ['مشاريعي', $my_projects, $themeColor] : null,
            isset($my_active_projects) ? ['نشطة', $my_active_projects, '#059669'] : null,
            isset($my_tasks) ? ['مهامي', $my_tasks, '#7c3aed'] : null,
            isset($my_overdue_tasks) ? ['متأخرة', $my_overdue_tasks, '#dc2626'] : null,
        ]); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
            <p class="text-xs text-gray-500"><?php echo e($item[0]); ?></p>
            <p class="text-3xl font-bold mt-1" style="color: <?php echo e($item[2]); ?>;"><?php echo e($item[1]); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>

    <?php if(isset($my_performance_metrics)): ?>
    <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-6 shadow-sm">
        <h3 class="font-bold text-sm mb-3">أدائي</h3>
        <div class="grid grid-cols-3 gap-4 text-center text-sm">
            <div><p class="text-gray-500 text-xs">كفاءة المهام</p><p class="text-2xl font-bold text-emerald-600"><?php echo e($my_performance_metrics['task_efficiency']); ?>%</p></div>
            <div><p class="text-gray-500 text-xs">مهام متأخرة</p><p class="text-2xl font-bold text-red-600"><?php echo e($my_performance_metrics['overdue_tasks']); ?></p></div>
            <div><p class="text-gray-500 text-xs">معلقة</p><p class="text-2xl font-bold" style="color: <?php echo e($themeColor); ?>;"><?php echo e($my_pending_tasks ?? 0); ?></p></div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(isset($total_employees)): ?>
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">الموظفون</p><p class="text-3xl font-bold" style="color:<?php echo e($themeColor); ?>"><?php echo e($total_employees); ?></p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">إجازات معلقة</p><p class="text-3xl font-bold text-amber-600"><?php echo e($pending_leaves); ?></p></div>
    </div>
    <?php endif; ?>

    <?php if(isset($pending_invoices)): ?>
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">مصروفات معتمدة</p><p class="text-2xl font-bold text-emerald-700"><?php echo e(number_format($total_amount ?? 0)); ?></p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">هذا الشهر</p><p class="text-2xl font-bold" style="color:<?php echo e($themeColor); ?>"><?php echo e(number_format($this_month_expenses ?? 0)); ?></p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">فواتير معلقة</p><p class="text-2xl font-bold text-amber-600"><?php echo e($pending_invoices); ?></p></div>
    </div>
    <?php endif; ?>

    <?php if(isset($won_sales)): ?>
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">عملاء</p><p class="text-2xl font-bold" style="color:<?php echo e($themeColor); ?>"><?php echo e($total_clients); ?></p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">صفقات رابحة</p><p class="text-2xl font-bold text-emerald-600"><?php echo e($won_sales); ?></p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">معدل التحويل</p><p class="text-2xl font-bold text-blue-600"><?php echo e($conversion_rate); ?>%</p></div>
    </div>
    <?php endif; ?>

    <?php if(isset($my_tickets)): ?>
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">تذاكري</p><p class="text-3xl font-bold" style="color:<?php echo e($themeColor); ?>"><?php echo e($my_tickets); ?></p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">مفتوحة</p><p class="text-3xl font-bold text-amber-600"><?php echo e($my_open_tickets); ?></p></div>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <?php if(isset($recent_projects) && $recent_projects->count()): ?>
        <section class="bg-white rounded-2xl border shadow-sm p-5">
            <h3 class="font-bold text-sm mb-3">مشاريعي الأخيرة</h3>
            <ul class="text-xs space-y-2">
                <?php $__currentLoopData = $recent_projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><a href="<?php echo e(route('projects.show', $p)); ?>" class="font-semibold hover:underline" style="color:<?php echo e($themeColor); ?>"><?php echo e($p->name); ?></a></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </section>
        <?php endif; ?>
        <?php if(isset($recent_tasks) && $recent_tasks->count()): ?>
        <section class="bg-white rounded-2xl border shadow-sm p-5">
            <h3 class="font-bold text-sm mb-3">مهامي الأخيرة</h3>
            <ul class="text-xs space-y-2">
                <?php $__currentLoopData = $recent_tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><a href="<?php echo e(route('tasks.show', $t)); ?>" class="font-semibold hover:underline" style="color:<?php echo e($themeColor); ?>"><?php echo e($t->title); ?></a></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </section>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\dashboard\partials\role-default.blade.php ENDPATH**/ ?>