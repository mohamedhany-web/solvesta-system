<?php $__env->startSection('title', 'لوحة التحكم الرئيسية'); ?>
<?php $__env->startSection('page-title', 'لوحة التحكم'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $systemName = \App\Helpers\SettingsHelper::getSystemName();
?>

<!-- Enhanced Page Header -->
<div class="mb-4 sm:mb-6 lg:mb-8 px-2 sm:px-0">
    <div class="rounded-2xl p-4 sm:p-6 lg:p-8 shadow-xl border overflow-hidden relative"
         style="background: linear-gradient(135deg, <?php echo e($themeColor); ?>15 0%, <?php echo e($themeColor); ?>05 50%, <?php echo e($themeColor); ?>10 100%); border-color: <?php echo e($themeColor); ?>30;">
        <!-- Decorative Pattern -->
        <div class="absolute top-0 left-0 w-full h-full opacity-5 overflow-hidden pointer-events-none">
            <div class="absolute top-10 right-10 w-64 h-64 rounded-full" style="background: <?php echo e($themeColor); ?>;"></div>
            <div class="absolute bottom-10 left-10 w-48 h-48 rounded-full" style="background: <?php echo e($themeColor); ?>;"></div>
        </div>
        
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-4 mb-4">
                    <div class="h-16 w-16 rounded-2xl flex items-center justify-center shadow-xl flex-shrink-0"
                         style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 font-tajawal">
                                مرحباً، <?php echo e(auth()->user()->name); ?>

                            </h1>
                            <div class="hidden sm:flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium"
                                 style="background: <?php echo e($themeColor); ?>20; color: <?php echo e($themeColor); ?>dd;">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span id="dashboard-time"><?php echo e(now()->format('H:i')); ?></span>
                            </div>
                        </div>
                        <p class="text-gray-700 text-sm sm:text-base font-tajawal">
                            الدور الوظيفي: 
                            <span class="font-bold px-3 py-1.5 rounded-xl text-sm inline-flex items-center gap-1 shadow-sm"
                                  style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%); color: white;">
                                <?php if(isset($user_role)): ?>
                                    <?php echo e(\App\Helpers\RoleHelper::getRoleName($user_role)); ?>

                                <?php else: ?>
                                    موظف
                                <?php endif; ?>
                            </span>
                        </p>
                        <p class="text-xs sm:text-sm text-gray-500 mt-2 font-tajawal">
                            <?php echo e(now()->locale('ar')->translatedFormat('l، d F Y')); ?>

                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats Summary -->
            <div class="flex flex-wrap gap-3 sm:gap-4 lg:gap-6 mt-4 sm:mt-0">
                <?php if(isset($performance_metrics)): ?>
                    <div class="text-center p-3 sm:p-4 rounded-xl bg-white shadow-md hover:shadow-lg transition-all duration-300 border flex-1 sm:flex-none"
                         style="border-color: <?php echo e($themeColor); ?>30; min-width: 90px;">
                        <div class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1" style="color: <?php echo e($themeColor); ?>;"><?php echo e($performance_metrics['project_efficiency']); ?>%</div>
                        <div class="text-xs text-gray-600 font-tajawal">كفاءة المشاريع</div>
                    </div>
                    <div class="text-center p-3 sm:p-4 rounded-xl bg-white shadow-md hover:shadow-lg transition-all duration-300 border flex-1 sm:flex-none"
                         style="border-color: <?php echo e($themeColor); ?>30; min-width: 90px;">
                        <div class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 text-green-600"><?php echo e($performance_metrics['task_efficiency']); ?>%</div>
                        <div class="text-xs text-gray-600 font-tajawal">كفاءة المهام</div>
                    </div>
                    <div class="text-center p-3 sm:p-4 rounded-xl bg-white shadow-md hover:shadow-lg transition-all duration-300 border flex-1 sm:flex-none"
                         style="border-color: <?php echo e($themeColor); ?>30; min-width: 90px;">
                        <div class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 text-purple-600"><?php echo e($performance_metrics['attendance_rate']); ?>%</div>
                        <div class="text-xs text-gray-600 font-tajawal">معدل الحضور</div>
                    </div>
                <?php elseif(isset($my_performance_metrics)): ?>
                    <div class="text-center p-3 sm:p-4 rounded-xl bg-white shadow-md hover:shadow-lg transition-all duration-300 border flex-1 sm:flex-none"
                         style="border-color: <?php echo e($themeColor); ?>30; min-width: 90px;">
                        <div class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 text-green-600"><?php echo e($my_performance_metrics['task_efficiency']); ?>%</div>
                        <div class="text-xs text-gray-600 font-tajawal">كفاءة المهام</div>
                    </div>
                    <div class="text-center p-3 sm:p-4 rounded-xl bg-white shadow-md hover:shadow-lg transition-all duration-300 border flex-1 sm:flex-none"
                         style="border-color: <?php echo e($themeColor); ?>30; min-width: 90px;">
                        <div class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1" style="color: <?php echo e($themeColor); ?>;"><?php echo e($my_performance_metrics['attendance_rate']); ?>%</div>
                        <div class="text-xs text-gray-600 font-tajawal">معدل الحضور</div>
                    </div>
                    <div class="text-center p-3 sm:p-4 rounded-xl bg-white shadow-md hover:shadow-lg transition-all duration-300 border border-red-200 flex-1 sm:flex-none"
                         style="min-width: 90px;">
                        <div class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 text-red-600"><?php echo e($my_performance_metrics['overdue_tasks']); ?></div>
                        <div class="text-xs text-gray-600 font-tajawal">مهام متأخرة</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 lg:gap-6 mb-6 sm:mb-8 px-2 sm:px-0">
    <?php
        $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    ?>
    
    <?php if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'project_manager'])): ?>
        <?php if(isset($total_projects)): ?>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5 sm:p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 transform group mb-4 sm:mb-0">
            <div class="flex items-center justify-between mb-4 gap-3 sm:gap-4">
                <div class="text-right flex-1">
                    <div class="text-xs sm:text-sm text-gray-500 mb-1 font-tajawal">إجمالي المشاريع</div>
                    <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 font-tajawal"><?php echo e($total_projects); ?></div>
                </div>
                <div class="p-3 sm:p-4 rounded-2xl shadow-lg flex-shrink-0 group-hover:scale-110 transition-transform duration-300"
                     style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <div class="flex items-center justify-between text-xs sm:text-sm pt-3 border-t border-gray-100">
                <div class="flex items-center gap-1 text-green-600 font-tajawal">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <span class="font-semibold"><?php echo e($active_projects ?? 0); ?></span>
                    <span class="hidden sm:inline">نشط</span>
                </div>
                <div class="text-gray-600 font-tajawal">
                    <span class="font-bold" style="color: <?php echo e($themeColor); ?>;"><?php echo e($project_completion_rate ?? 0); ?>%</span>
                    <span class="hidden sm:inline"> مكتمل</span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if(isset($active_projects)): ?>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5 sm:p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 transform group mb-4 sm:mb-0">
            <div class="flex items-center justify-between mb-4 gap-3 sm:gap-4">
                <div class="text-right flex-1">
                    <div class="text-xs sm:text-sm text-gray-500 mb-1 font-tajawal">المشاريع النشطة</div>
                    <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 font-tajawal"><?php echo e($active_projects); ?></div>
                </div>
                <div class="p-3 sm:p-4 rounded-2xl shadow-lg flex-shrink-0 group-hover:scale-110 transition-transform duration-300 bg-gradient-to-r from-green-500 to-emerald-600">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-100">
                <div class="flex items-center gap-1 text-green-600 text-xs sm:text-sm font-tajawal">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span class="font-semibold">قيد التنفيذ</span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if(isset($total_employees)): ?>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5 sm:p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 transform group mb-4 sm:mb-0">
            <div class="flex items-center justify-between mb-4 gap-3 sm:gap-4">
                <div class="text-right flex-1">
                    <div class="text-xs sm:text-sm text-gray-500 mb-1 font-tajawal">إجمالي الموظفين</div>
                    <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 font-tajawal"><?php echo e($total_employees); ?></div>
                </div>
                <div class="p-3 sm:p-4 rounded-2xl shadow-lg flex-shrink-0 group-hover:scale-110 transition-transform duration-300 bg-gradient-to-r from-purple-500 to-indigo-600">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-100">
                <div class="flex items-center gap-1 text-purple-600 text-xs sm:text-sm font-tajawal">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-semibold"><?php echo e($active_employees ?? 0); ?></span>
                    <span class="hidden sm:inline">نشطون</span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if(isset($total_clients)): ?>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5 sm:p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 transform group mb-4 sm:mb-0">
            <div class="flex items-center justify-between mb-4 gap-3 sm:gap-4">
                <div class="text-right flex-1">
                    <div class="text-xs sm:text-sm text-gray-500 mb-1 font-tajawal">إجمالي العملاء</div>
                    <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 font-tajawal"><?php echo e($total_clients); ?></div>
                </div>
                <div class="p-3 sm:p-4 rounded-2xl shadow-lg flex-shrink-0 group-hover:scale-110 transition-transform duration-300 bg-gradient-to-r from-orange-500 to-amber-600">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-100">
                <div class="flex items-center gap-1 text-orange-600 text-xs sm:text-sm font-tajawal">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="font-semibold">قاعدة العملاء</span>
                </div>
            </div>
        </div>
        <?php endif; ?>

    
    <?php elseif(auth()->user()->hasAnyRole(['employee', 'developer', 'designer'])): ?>
        <?php if(isset($my_projects)): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-2">مشاريعي</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e($my_projects); ?></p>
                    <p class="text-xs text-blue-600 mt-1">المشاريع المكلف بها</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if(isset($my_active_projects)): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-2">المشاريع النشطة</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e($my_active_projects); ?></p>
                    <p class="text-xs text-green-600 mt-1">قيد التنفيذ</p>
                </div>
                <div class="p-3 bg-green-50 rounded-lg flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if(isset($my_tasks)): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-2">مهامي</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e($my_tasks); ?></p>
                    <p class="text-xs text-purple-600 mt-1">جميع مهامي</p>
                </div>
                <div class="p-3 bg-purple-50 rounded-lg flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if(isset($my_pending_tasks)): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-2">المهام المعلقة</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e($my_pending_tasks); ?></p>
                    <p class="text-xs text-red-600 mt-1">يحتاج للإنجاز</p>
                </div>
                <div class="p-3 bg-red-50 rounded-lg flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <?php endif; ?>

    
    <?php elseif(auth()->user()->hasRole('hr')): ?>
        <?php if(isset($total_employees)): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-2">إجمالي الموظفين</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e($total_employees); ?></p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if(isset($pending_leaves)): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-2">إجازات معلقة</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e($pending_leaves); ?></p>
                </div>
                <div class="p-3 bg-yellow-50 rounded-lg flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <?php endif; ?>

    
    <?php elseif(auth()->user()->hasRole('accountant')): ?>
        <?php if(isset($total_amount)): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-2">إجمالي المصروفات</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900"><?php echo e(number_format($total_amount, 2)); ?> ج.م</p>
                </div>
                <div class="p-3 bg-green-50 rounded-lg flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        <?php endif; ?>

    
    <?php elseif(auth()->user()->hasRole('sales_rep')): ?>
        <?php if(isset($total_clients)): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-2">إجمالي العملاء</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e($total_clients); ?></p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
            </div>
        </div>
        <?php endif; ?>

    
    <?php elseif(auth()->user()->hasRole('support')): ?>
        <?php if(isset($my_tickets)): ?>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-2">تذاكري</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e($my_tickets); ?></p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Additional Stats for Admins -->
        <?php if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'project_manager'])): ?>
            <?php if(isset($total_tasks)): ?>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 mb-2">إجمالي المهام</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e($total_tasks); ?></p>
                        <p class="text-xs text-gray-600 mt-1"><?php echo e($completed_tasks ?? 0); ?> مكتملة</p>
                    </div>
                    <div class="p-3 bg-indigo-50 rounded-lg flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if(isset($total_departments)): ?>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 mb-2">الأقسام النشطة</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e($total_departments); ?></p>
                        <p class="text-xs text-gray-600 mt-1">جميع الأقسام</p>
                    </div>
                    <div class="p-3 bg-cyan-50 rounded-lg flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-cyan-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if(isset($completed_projects)): ?>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 mb-2">مشاريع مكتملة</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e($completed_projects); ?></p>
                        <p class="text-xs text-green-600 mt-1">تم الانتهاء منها</p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-lg flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if(isset($active_employees)): ?>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs sm:text-sm font-medium text-gray-600 mb-2">موظفين نشطين</p>
                        <p class="text-2xl sm:text-3xl font-bold text-gray-900"><?php echo e($active_employees); ?></p>
                        <p class="text-xs text-gray-600 mt-1">من <?php echo e($total_employees ?? 0); ?></p>
                    </div>
                    <div class="p-3 bg-teal-50 rounded-lg flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-4 sm:p-6 lg:p-8 mb-6 sm:mb-8 px-2 sm:px-0">
    <div class="flex items-center gap-3 mb-4 sm:mb-6">
        <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-xl flex items-center justify-center shadow-md"
             style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
        </div>
        <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 font-tajawal">إجراءات سريعة</h3>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-projects')): ?>
        <a href="<?php echo e(route('projects.create')); ?>" 
           class="group flex items-center p-3 sm:p-4 lg:p-5 rounded-2xl transition-all duration-300 hover:shadow-xl hover:-translate-y-1 transform border-2 mb-3 sm:mb-0"
           style="background: linear-gradient(135deg, <?php echo e($themeColor); ?>10 0%, <?php echo e($themeColor); ?>05 100%); border-color: <?php echo e($themeColor); ?>30;">
            <div class="p-2.5 sm:p-3 rounded-xl shadow-lg ml-3 sm:ml-4 flex-shrink-0 group-hover:scale-110 transition-transform duration-300"
                 style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
            </div>
            <div class="flex-1 mr-2 sm:mr-0">
                <p class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 font-tajawal">مشروع جديد</p>
                <p class="text-xs text-gray-600 font-tajawal hidden sm:block">إضافة مشروع جديد</p>
            </div>
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 group-hover:text-gray-600 transition-colors hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-clients')): ?>
        <a href="<?php echo e(route('clients.create')); ?>" 
           class="group flex items-center p-3 sm:p-4 lg:p-5 rounded-2xl transition-all duration-300 hover:shadow-xl hover:-translate-y-1 transform border-2 bg-gradient-to-r from-green-50 to-emerald-50 border-green-200 mb-3 sm:mb-0">
            <div class="p-2.5 sm:p-3 rounded-xl shadow-lg ml-3 sm:ml-4 flex-shrink-0 group-hover:scale-110 transition-transform duration-300 bg-gradient-to-r from-green-600 to-emerald-600">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <div class="flex-1 mr-2 sm:mr-0">
                <p class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 font-tajawal">عميل جديد</p>
                <p class="text-xs text-gray-600 font-tajawal hidden sm:block">إضافة عميل جديد</p>
            </div>
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 group-hover:text-gray-600 transition-colors hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-employees')): ?>
        <a href="<?php echo e(route('employees.create')); ?>" 
           class="group flex items-center p-3 sm:p-4 lg:p-5 rounded-2xl transition-all duration-300 hover:shadow-xl hover:-translate-y-1 transform border-2 bg-gradient-to-r from-purple-50 to-indigo-50 border-purple-200 mb-3 sm:mb-0">
            <div class="p-2.5 sm:p-3 rounded-xl shadow-lg ml-3 sm:ml-4 flex-shrink-0 group-hover:scale-110 transition-transform duration-300 bg-gradient-to-r from-purple-600 to-indigo-600">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <div class="flex-1 mr-2 sm:mr-0">
                <p class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 font-tajawal">موظف جديد</p>
                <p class="text-xs text-gray-600 font-tajawal hidden sm:block">إضافة موظف جديد</p>
            </div>
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 group-hover:text-gray-600 transition-colors hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create-bugs')): ?>
        <a href="<?php echo e(route('bugs.create')); ?>" 
           class="group flex items-center p-3 sm:p-4 lg:p-5 rounded-2xl transition-all duration-300 hover:shadow-xl hover:-translate-y-1 transform border-2 bg-gradient-to-r from-red-50 to-rose-50 border-red-200 mb-3 sm:mb-0">
            <div class="p-2.5 sm:p-3 rounded-xl shadow-lg ml-3 sm:ml-4 flex-shrink-0 group-hover:scale-110 transition-transform duration-300 bg-gradient-to-r from-red-600 to-rose-600">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <div class="flex-1 mr-2 sm:mr-0">
                <p class="text-sm sm:text-base lg:text-lg font-bold text-gray-900 font-tajawal">تقرير خطأ</p>
                <p class="text-xs text-gray-600 font-tajawal hidden sm:block">إبلاغ عن خطأ</p>
            </div>
            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 group-hover:text-gray-600 transition-colors hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Analytics Section for Admins -->
<?php if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'project_manager'])): ?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
    
    <!-- Today's Attendance -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sm:p-6">
        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">حضور اليوم</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-600 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">الحضور</span>
                </div>
                <span class="text-lg font-bold text-green-700"><?php echo e($today_present ?? 0); ?></span>
            </div>
            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-red-600 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">الغياب</span>
                </div>
                <span class="text-lg font-bold text-red-700"><?php echo e($today_absent ?? 0); ?></span>
            </div>
            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-100">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-600 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">إجمالي السجلات</span>
                </div>
                <span class="text-lg font-bold text-blue-700"><?php echo e($today_attendance ?? 0); ?></span>
            </div>
        </div>
    </div>
    
    <!-- Monthly Statistics -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sm:p-6">
        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">إحصائيات هذا الشهر</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-100">
                <span class="text-sm font-medium text-gray-700">مشاريع جديدة</span>
                <span class="text-lg font-bold text-blue-700"><?php echo e($this_month_projects ?? 0); ?></span>
            </div>
            <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg border border-purple-100">
                <span class="text-sm font-medium text-gray-700">مهام جديدة</span>
                <span class="text-lg font-bold text-purple-700"><?php echo e($this_month_tasks ?? 0); ?></span>
            </div>
            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                <span class="text-sm font-medium text-gray-700">موظفين جدد</span>
                <span class="text-lg font-bold text-green-700"><?php echo e($this_month_employees ?? 0); ?></span>
            </div>
        </div>
    </div>
    
</div>

<!-- Tasks Progress Overview -->
<?php if(isset($total_tasks) && $total_tasks > 0): ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sm:p-6 mb-6 sm:mb-8">
    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">نظرة عامة على المهام</h3>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="text-center p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-600 mb-2">إجمالي المهام</p>
            <p class="text-3xl font-bold text-gray-900"><?php echo e($total_tasks); ?></p>
        </div>
        <div class="text-center p-4 bg-green-50 rounded-lg border border-green-100">
            <p class="text-sm text-gray-600 mb-2">المهام المكتملة</p>
            <p class="text-3xl font-bold text-green-700"><?php echo e($completed_tasks); ?></p>
            <p class="text-xs text-green-600 mt-1"><?php echo e($total_tasks > 0 ? round(($completed_tasks / $total_tasks) * 100) : 0); ?>%</p>
        </div>
        <div class="text-center p-4 bg-yellow-50 rounded-lg border border-yellow-100">
            <p class="text-sm text-gray-600 mb-2">المهام المعلقة</p>
            <p class="text-3xl font-bold text-yellow-700"><?php echo e($pending_tasks); ?></p>
            <p class="text-xs text-yellow-600 mt-1"><?php echo e($total_tasks > 0 ? round(($pending_tasks / $total_tasks) * 100) : 0); ?>%</p>
        </div>
    </div>
    
    <!-- Progress Bar -->
    <div class="mt-4">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-700">نسبة الإنجاز الكلية</span>
            <span class="text-sm font-bold text-gray-900"><?php echo e($total_tasks > 0 ? round(($completed_tasks / $total_tasks) * 100) : 0); ?>%</span>
        </div>
        <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-green-500 to-green-600 transition-all duration-500" 
                 style="width: <?php echo e($total_tasks > 0 ? round(($completed_tasks / $total_tasks) * 100) : 0); ?>%"></div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Recent Tasks -->
<?php if(isset($recent_tasks) && $recent_tasks->count() > 0): ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sm:p-6 mb-6 sm:mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-2">
        <h3 class="text-base sm:text-lg font-semibold text-gray-900">أحدث المهام</h3>
        <a href="<?php echo e(route('tasks.index')); ?>" class="text-sm text-blue-600 hover:text-blue-700 font-medium inline-flex items-center">
            عرض الكل
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
    </div>
    <div class="space-y-2 sm:space-y-3">
        <?php $__currentLoopData = $recent_tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
            <div class="flex items-center flex-1 min-w-0">
                <div class="h-8 w-8 sm:h-10 sm:w-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg flex items-center justify-center ml-3 flex-shrink-0">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-900 truncate"><?php echo e($task->title); ?></p>
                    <p class="text-xs text-gray-600 truncate"><?php echo e($task->project->name ?? 'لا يوجد مشروع'); ?></p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <span class="px-2 py-1 text-xs font-medium rounded-lg
                    <?php if($task->status === 'pending'): ?> bg-yellow-100 text-yellow-800
                    <?php elseif($task->status === 'in_progress'): ?> bg-blue-100 text-blue-800
                    <?php elseif($task->status === 'completed'): ?> bg-green-100 text-green-800
                    <?php else: ?> bg-gray-100 text-gray-800
                    <?php endif; ?>">
                    <?php if($task->status === 'pending'): ?> معلقة
                    <?php elseif($task->status === 'in_progress'): ?> قيد التنفيذ
                    <?php elseif($task->status === 'completed'): ?> مكتملة
                    <?php else: ?> <?php echo e($task->status); ?>

                    <?php endif; ?>
                </span>
                <a href="<?php echo e(route('tasks.show', $task)); ?>" class="px-3 py-1 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-xs transition-colors whitespace-nowrap">
                    عرض
                </a>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<!-- Recent Projects -->
<?php if(isset($recent_projects) && $recent_projects->count() > 0): ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sm:p-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-2">
        <h3 class="text-base sm:text-lg font-semibold text-gray-900">
            <?php if(auth()->user()->hasAnyRole(['employee', 'developer', 'designer'])): ?>
                مشاريعي الأخيرة
            <?php else: ?>
                أحدث المشاريع
            <?php endif; ?>
        </h3>
        <a href="<?php echo e(route('projects.index')); ?>" class="text-sm text-blue-600 hover:text-blue-700 font-medium inline-flex items-center">
            عرض الكل
            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
    </div>
    <div class="space-y-3 sm:space-y-4">
        <?php $__currentLoopData = $recent_projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3 sm:p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
            <div class="flex items-center flex-1">
                <div class="h-10 w-10 sm:h-12 sm:w-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center ml-3 sm:ml-4 flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm sm:text-base font-semibold text-gray-900 truncate"><?php echo e($project->name); ?></p>
                    <p class="text-xs sm:text-sm text-gray-600 truncate"><?php echo e($project->client->name ?? 'لا يوجد عميل'); ?></p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-1 text-xs font-medium rounded-lg
                    <?php if($project->status === 'planning'): ?> bg-gray-100 text-gray-800
                    <?php elseif($project->status === 'in_progress'): ?> bg-blue-100 text-blue-800
                    <?php elseif($project->status === 'completed'): ?> bg-green-100 text-green-800
                    <?php else: ?> bg-gray-100 text-gray-800
                    <?php endif; ?>">
                    <?php if($project->status === 'planning'): ?> التخطيط
                    <?php elseif($project->status === 'in_progress'): ?> قيد التنفيذ
                    <?php elseif($project->status === 'completed'): ?> مكتمل
                    <?php else: ?> <?php echo e($project->status); ?>

                    <?php endif; ?>
                </span>
                <a href="<?php echo e(route('projects.show', $project)); ?>" class="px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-xs sm:text-sm transition-colors whitespace-nowrap">
                    عرض
                </a>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>

<!-- Charts Section -->
<?php if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'project_manager'])): ?>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Project Timeline Chart -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">تطور المشاريع (30 يوم)</h3>
            <div class="flex items-center text-sm text-gray-500">
                <div class="w-3 h-3 bg-blue-500 rounded-full ml-2"></div>
                مشاريع جديدة
            </div>
        </div>
        <div class="h-64">
            <canvas id="projectTimelineChart"></canvas>
        </div>
    </div>

    <!-- Task Distribution Chart -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">توزيع المهام</h3>
            <div class="flex items-center gap-4 text-sm">
                <div class="flex items-center text-green-600">
                    <div class="w-3 h-3 bg-green-500 rounded-full ml-2"></div>
                    مكتملة
                </div>
                <div class="flex items-center text-yellow-600">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full ml-2"></div>
                    قيد التنفيذ
                </div>
                <div class="flex items-center text-red-600">
                    <div class="w-3 h-3 bg-red-500 rounded-full ml-2"></div>
                    متأخرة
                </div>
            </div>
        </div>
        <div class="h-64">
            <canvas id="taskDistributionChart"></canvas>
        </div>
    </div>
</div>

<!-- Department Performance -->
<?php if(isset($department_stats) && $department_stats->count() > 0): ?>
<div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">أداء الأقسام</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php $__currentLoopData = $department_stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
                <h4 class="font-medium text-gray-900"><?php echo e($dept['name']); ?></h4>
                <span class="text-xs px-2 py-1 rounded-full <?php echo e($dept['efficiency'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                    <?php echo e($dept['efficiency'] > 0 ? 'عالية' : 'منخفضة'); ?>

                </span>
            </div>
            <div class="flex items-center justify-between text-sm text-gray-600">
                <span><?php echo e($dept['employees_count']); ?> موظف</span>
                <span><?php echo e($dept['projects_count']); ?> مشروع</span>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Project Timeline Chart
    <?php if(isset($project_timeline) && $project_timeline->count() > 0): ?>
    const projectCtx = document.getElementById('projectTimelineChart').getContext('2d');
    new Chart(projectCtx, {
        type: 'line',
        data: {
            labels: <?php echo $project_timeline->pluck('date')->toJson(); ?>,
            datasets: [{
                label: 'مشاريع جديدة',
                data: <?php echo $project_timeline->pluck('count')->toJson(); ?>,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    <?php endif; ?>

    // Task Distribution Chart
    <?php if(isset($total_tasks)): ?>
    const taskCtx = document.getElementById('taskDistributionChart').getContext('2d');
    new Chart(taskCtx, {
        type: 'doughnut',
        data: {
            labels: ['مكتملة', 'قيد التنفيذ', 'متأخرة', 'معلقة'],
            datasets: [{
                data: [
                    <?php echo e($completed_tasks ?? 0); ?>,
                    <?php echo e($in_progress_tasks ?? 0); ?>,
                    <?php echo e($overdue_tasks ?? 0); ?>,
                    <?php echo e($pending_tasks ?? 0); ?>

                ],
                backgroundColor: [
                    'rgb(34, 197, 94)',
                    'rgb(251, 191, 36)',
                    'rgb(239, 68, 68)',
                    'rgb(156, 163, 175)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            }
        }
    });
    <?php endif; ?>
    
    // Update dashboard time every second
    function updateDashboardTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const timeElement = document.getElementById('dashboard-time');
        if (timeElement) {
            timeElement.textContent = `${hours}:${minutes}:${seconds}`;
        }
    }
    
    // Update time immediately and then every second
    updateDashboardTime();
    setInterval(updateDashboardTime, 1000);
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views/dashboard.blade.php ENDPATH**/ ?>