

<?php $__env->startSection('page-title', 'لوحة تحكم الموظف'); ?>

<?php $__env->startSection('content'); ?>
<!-- Welcome Section -->
<div class="bg-gradient-to-r from-green-600 to-emerald-700 rounded-xl p-6 text-white mb-6 shadow-lg">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold mb-2">مرحباً، <?php echo e(Auth::user()->name); ?></h1>
            <p class="text-green-100">لوحة تحكم الموظف - <?php echo e(Auth::user()->employee->position ?? 'موظف'); ?></p>
        </div>
        <div class="text-right">
            <p class="text-sm text-green-200"><?php echo e(now()->format('Y/m/d')); ?></p>
            <p class="text-lg font-semibold"><?php echo e(now()->format('H:i')); ?></p>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center ml-3">
                <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">مهامي المعلقة</p>
                <p class="text-xl font-bold text-gray-900"><?php echo e(\App\Models\Task::where('assigned_to', Auth::user()->employee->id ?? 0)->where('status', 'pending')->count()); ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="h-10 w-10 bg-green-100 rounded-lg flex items-center justify-center ml-3">
                <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">مهام مكتملة</p>
                <p class="text-xl font-bold text-gray-900"><?php echo e(\App\Models\Task::where('assigned_to', Auth::user()->employee->id ?? 0)->where('status', 'completed')->count()); ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="h-10 w-10 bg-yellow-100 rounded-lg flex items-center justify-center ml-3">
                <svg class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">قيد التنفيذ</p>
                <p class="text-xl font-bold text-gray-900"><?php echo e(\App\Models\Task::where('assigned_to', Auth::user()->employee->id ?? 0)->where('status', 'in_progress')->count()); ?></p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="h-10 w-10 bg-purple-100 rounded-lg flex items-center justify-center ml-3">
                <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-600">مشاريعي</p>
                <p class="text-xl font-bold text-gray-900"><?php echo e(\App\Models\Project::whereHas('tasks', function($query) { $query->where('assigned_to', Auth::user()->employee->id ?? 0); })->count()); ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- My Tasks -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">مهامي الأخيرة</h3>
            <a href="<?php echo e(route('employee.tasks')); ?>" class="text-sm text-green-600 hover:text-green-700 font-medium">عرض الكل</a>
        </div>
        <div class="space-y-3">
            <?php $__empty_1 = true; $__currentLoopData = \App\Models\Task::where('assigned_to', Auth::id())->latest()->take(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex-1">
                    <p class="font-medium text-gray-900"><?php echo e($task->title); ?></p>
                    <p class="text-sm text-gray-600"><?php echo e(Str::limit($task->description, 50)); ?></p>
                    <p class="text-xs text-gray-500 mt-1">المشروع: <?php echo e($task->project->name ?? 'غير محدد'); ?></p>
                </div>
                <span class="px-2 py-1 text-xs font-medium rounded-full 
                    <?php if($task->status == 'completed'): ?> bg-green-100 text-green-800
                    <?php elseif($task->status == 'in_progress'): ?> bg-blue-100 text-blue-800
                    <?php else: ?> bg-yellow-100 text-yellow-800 <?php endif; ?>">
                    <?php echo e($task->status == 'completed' ? 'مكتملة' : ($task->status == 'in_progress' ? 'قيد التنفيذ' : 'معلقة')); ?>

                </span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-gray-500 text-center py-4">لا توجد مهام مخصصة لك</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">إجراءات سريعة</h3>
        <div class="space-y-3">
            <a href="<?php echo e(route('employee.tasks')); ?>" class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                عرض جميع مهامي
            </a>
            
            <a href="<?php echo e(route('employee.profile')); ?>" class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                ملفي الشخصي
            </a>
            
            <a href="<?php echo e(route('employee.attendance')); ?>" class="w-full flex items-center justify-center px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                سجل الحضور
            </a>
        </div>
    </div>
</div>

<!-- My Projects -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">مشاريعي</h3>
        <a href="<?php echo e(route('employee.projects')); ?>" class="text-sm text-green-600 hover:text-green-700 font-medium">عرض الكل</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php $__empty_1 = true; $__currentLoopData = \App\Models\Project::whereHas('tasks', function($query) { $query->where('assigned_to', Auth::id()); })->latest()->take(6)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="p-4 bg-gray-50 rounded-lg border border-gray-200 hover:shadow-sm transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <h4 class="font-medium text-gray-900"><?php echo e($project->name); ?></h4>
                <span class="px-2 py-1 text-xs font-medium rounded-full 
                    <?php if($project->status == 'active'): ?> bg-green-100 text-green-800
                    <?php elseif($project->status == 'completed'): ?> bg-blue-100 text-blue-800
                    <?php else: ?> bg-yellow-100 text-yellow-800 <?php endif; ?>">
                    <?php echo e($project->status == 'active' ? 'نشط' : ($project->status == 'completed' ? 'مكتمل' : 'معلق')); ?>

                </span>
            </div>
            <p class="text-sm text-gray-600 mb-2"><?php echo e(Str::limit($project->description, 60)); ?></p>
            <div class="flex items-center justify-between text-xs text-gray-500">
                <span>العميل: <?php echo e($project->client->name ?? 'غير محدد'); ?></span>
                <span><?php echo e($project->created_at->format('Y/m/d')); ?></span>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full text-center py-8 text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <p>لا توجد مشاريع مخصصة لك</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\employee\dashboard.blade.php ENDPATH**/ ?>