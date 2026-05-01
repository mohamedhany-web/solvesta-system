<?php $__env->startSection('page-title', 'تفاصيل الموظف'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header Section -->
<div class="bg-gradient-to-r from-green-600 to-emerald-700 rounded-xl p-6 text-white mb-6 shadow-lg">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <div class="h-16 w-16 bg-white/20 rounded-full flex items-center justify-center ml-4">
                <span class="text-2xl font-bold"><?php echo e(substr($employee->name, 0, 1)); ?></span>
            </div>
            <div>
                <h1 class="text-2xl font-bold mb-2"><?php echo e($employee->name); ?></h1>
                <p class="text-green-100"><?php echo e($employee->position); ?> - <?php echo e($employee->department->name ?? 'غير محدد'); ?></p>
            </div>
        </div>
        <div class="flex items-center space-x-3 space-x-reverse">
            <a href="<?php echo e(route('employees.edit', $employee)); ?>" class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-all duration-200">
                تعديل
            </a>
            <a href="<?php echo e(route('employees.index')); ?>" class="bg-white text-green-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition-all duration-200">
                العودة
            </a>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">المعلومات الشخصية</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">الاسم الكامل</span>
                        <span class="text-sm text-gray-900"><?php echo e($employee->name); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">البريد الإلكتروني</span>
                        <span class="text-sm text-gray-900"><?php echo e($employee->email); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">رقم الهاتف</span>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-900"><?php echo e($employee->phone ?? 'غير محدد'); ?></span>
                            <?php if($employee->phone): ?>
                            <button onclick="openWhatsAppContact('<?php echo e($employee->phone); ?>', '<?php echo e(addslashes($employee->name)); ?>')" 
                                    class="text-green-600 hover:text-green-700 transition-colors p-1 rounded hover:bg-green-50"
                                    title="فتح واتساب">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                </svg>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">العنوان</span>
                        <span class="text-sm text-gray-900"><?php echo e($employee->address ?? 'غير محدد'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Work Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">المعلومات الوظيفية</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">المنصب</span>
                        <span class="text-sm text-gray-900"><?php echo e($employee->position ?? 'غير محدد'); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">القسم</span>
                        <span class="text-sm text-gray-900"><?php echo e($employee->department->name ?? 'غير محدد'); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">الراتب</span>
                        <span class="text-sm text-gray-900"><?php echo e($employee->salary ? number_format($employee->salary) . ' ج.م' : 'غير محدد'); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">تاريخ التوظيف</span>
                        <span class="text-sm text-gray-900"><?php echo e($employee->hire_date ? \Carbon\Carbon::parse($employee->hire_date)->format('Y/m/d') : 'غير محدد'); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">الحالة</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            <?php if($employee->status == 'active'): ?> bg-green-100 text-green-800
                            <?php elseif($employee->status == 'on_leave'): ?> bg-yellow-100 text-yellow-800
                            <?php else: ?> bg-red-100 text-red-800 <?php endif; ?>">
                            <?php if($employee->status == 'active'): ?> نشط
                            <?php elseif($employee->status == 'on_leave'): ?> في إجازة
                            <?php else: ?> غير نشط <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Employee Status Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">حالة الموظف</h3>
                <div class="text-center">
                    <div class="h-20 w-20 bg-gradient-to-r from-green-500 to-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl font-bold text-white"><?php echo e(substr($employee->name, 0, 1)); ?></span>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900"><?php echo e($employee->name); ?></h4>
                    <p class="text-sm text-gray-600"><?php echo e($employee->position); ?></p>
                    <div class="mt-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            <?php if($employee->status == 'active'): ?> bg-green-100 text-green-800
                            <?php elseif($employee->status == 'on_leave'): ?> bg-yellow-100 text-yellow-800
                            <?php else: ?> bg-red-100 text-red-800 <?php endif; ?>">
                            <?php if($employee->status == 'active'): ?> نشط
                            <?php elseif($employee->status == 'on_leave'): ?> في إجازة
                            <?php else: ?> غير نشط <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">إجراءات سريعة</h3>
                <div class="space-y-3">
                    <a href="<?php echo e(route('employees.edit', $employee)); ?>" class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        تعديل البيانات
                    </a>
                    <a href="<?php echo e(route('attendances.index', ['employee' => $employee->id])); ?>" class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        سجل الحضور
                    </a>
                    <a href="<?php echo e(route('salaries.index', ['employee' => $employee->id])); ?>" class="w-full flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                        </svg>
                        سجل المرتبات
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">النشاط الأخير</h3>
                <div class="space-y-3">
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="h-2 w-2 bg-green-500 rounded-full ml-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">تم إنشاء الملف</p>
                            <p class="text-xs text-gray-500"><?php echo e($employee->created_at->diffForHumans()); ?></p>
                        </div>
                    </div>
                    <?php if($employee->updated_at != $employee->created_at): ?>
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="h-2 w-2 bg-blue-500 rounded-full ml-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">آخر تحديث</p>
                            <p class="text-xs text-gray-500"><?php echo e($employee->updated_at->diffForHumans()); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\employees\show.blade.php ENDPATH**/ ?>