<?php $__env->startSection('page-title', 'إضافة موظف جديد'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
?>

<!-- Header Section -->
<div class="rounded-2xl p-6 md:p-8 text-white mb-6 md:mb-8 shadow-xl relative overflow-hidden" style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%); border: 2px solid <?php echo e($themeColor); ?>30;">
    <div class="absolute top-0 left-0 w-full h-full opacity-10">
        <div class="absolute top-10 right-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-10 w-96 h-96 bg-white rounded-full blur-3xl opacity-50"></div>
    </div>
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between relative z-10 gap-4">
        <div class="flex items-center gap-3 md:gap-4 w-full md:w-auto">
            <div class="p-3 md:p-4 bg-white/20 backdrop-blur-sm rounded-xl md:rounded-2xl flex-shrink-0 shadow-lg">
                <svg class="h-8 w-8 md:h-10 md:w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl md:text-3xl font-bold mb-1 md:mb-2 truncate font-tajawal">إضافة موظف جديد</h1>
                <p class="text-white/90 text-sm md:text-lg font-tajawal">قم بملء البيانات المطلوبة لإضافة موظف جديد للشركة</p>
            </div>
        </div>
        <a href="<?php echo e(route('employees.index')); ?>" class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 md:px-6 md:py-3 rounded-xl hover:bg-white/30 transition-all duration-200 flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-1 w-full md:w-auto justify-center gap-2 font-tajawal">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="font-semibold text-sm md:text-base">العودة</span>
        </a>
    </div>
</div>

<div class="max-w-5xl mx-auto">
    <?php if(session('error')): ?>
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        <?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="mb-6 bg-yellow-50 border border-yellow-200 text-yellow-900 px-4 py-3 rounded-lg">
        <div class="font-bold mb-1">تعذر إضافة الموظف بسبب الأخطاء التالية:</div>
        <ul class="list-disc pr-5 space-y-1">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <form action="<?php echo e(route('employees.store')); ?>" method="POST" class="space-y-6">
        <?php echo csrf_field(); ?>

        <!-- Personal Information -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden" style="border-color: <?php echo e($themeColor); ?>30;">
            <div class="bg-gradient-to-r px-4 md:px-6 py-3 md:py-4 border-b border-gray-200" style="background: linear-gradient(135deg, <?php echo e($themeColor); ?>15 0%, <?php echo e($themeColor); ?>25 100%);">
                <div class="flex items-center gap-2 md:gap-3">
                    <div class="p-2 rounded-xl shadow-sm" style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
                        <svg class="h-5 w-5 md:h-6 md:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900 font-tajawal">المعلومات الشخصية</h3>
                </div>
            </div>
            <div class="p-4 md:p-8 space-y-4 md:space-y-6">
                <!-- Create New User Option -->
                <div class="md:col-span-2">
                    <div class="rounded-xl p-4 md:p-5 border-2 shadow-sm transition-all duration-200" style="background: linear-gradient(135deg, <?php echo e($themeColor); ?>08 0%, <?php echo e($themeColor); ?>15 100%); border-color: <?php echo e($themeColor); ?>30;">
                        <label class="flex items-start cursor-pointer gap-3">
                            <input type="checkbox" name="create_new_user" id="create_new_user" value="1" 
                                   class="w-5 h-5 mt-0.5 rounded focus:ring-2 focus:ring-offset-0 transition-all duration-200"
                                   style="accent-color: <?php echo e($themeColor); ?>;"
                                   <?php echo e(old('create_new_user') ? 'checked' : ''); ?>

                                   onchange="toggleUserSelection()">
                            <div class="flex-1">
                                <span class="block text-sm md:text-base font-semibold text-gray-900 mb-1 font-tajawal">إنشاء حساب مستخدم جديد تلقائياً</span>
                                <p class="text-xs md:text-sm text-gray-600 font-tajawal">سيتم إنشاء حساب مستخدم تلقائياً برقم الموظف والبيانات المدخلة</p>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">

                    <!-- User Selection -->
                    <div class="md:col-span-2" id="user_selection_container">
                        <label for="user_id" class="block text-sm md:text-base font-bold text-gray-700 mb-3 flex items-center gap-2 font-tajawal">
                            <span class="text-red-500">*</span>
                            <span>اختيار المستخدم</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <select name="user_id" id="user_id" 
                                    class="w-full pr-10 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    style="focus:ring-color: <?php echo e($themeColor); ?>;">
                                <option value="">اختر المستخدم</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" <?php echo e(old('user_id') == $user->id ? 'selected' : ''); ?>>
                                        <?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1 font-tajawal">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Password Fields (shown when creating new user) -->
                    <div class="md:col-span-2" id="password_fields" style="display: none;">
                        <div class="rounded-xl p-4 md:p-5 border-2 shadow-sm" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-color: #f59e0b;">
                            <h4 class="text-sm md:text-base font-bold text-gray-900 mb-4 flex items-center gap-2 font-tajawal">
                                <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                بيانات حساب المستخدم الجديد
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2 font-tajawal">
                                        <span class="text-red-500">*</span>
                                        <span>كلمة المرور</span>
                                    </label>
                                    <input type="password" name="password" id="password" 
                                           class="w-full px-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           placeholder="••••••••"
                                           style="focus:ring-color: <?php echo e($themeColor); ?>;">
                                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1 font-tajawal">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            <?php echo e($message); ?>

                                        </p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2 font-tajawal">
                                        <span class="text-red-500">*</span>
                                        <span>تأكيد كلمة المرور</span>
                                    </label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" 
                                           class="w-full px-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg"
                                           placeholder="••••••••"
                                           style="focus:ring-color: <?php echo e($themeColor); ?>;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm md:text-base font-bold text-gray-700 mb-3 flex items-center gap-2 font-tajawal">
                            <span class="text-red-500">*</span>
                            <span>الاسم الأول</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" name="first_name" id="first_name" value="<?php echo e(old('first_name')); ?>" required
                                   class="w-full pr-10 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="أدخل الاسم الأول"
                                   style="focus:ring-color: <?php echo e($themeColor); ?>;">
                        </div>
                        <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1 font-tajawal">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm md:text-base font-bold text-gray-700 mb-3 flex items-center gap-2 font-tajawal">
                            <span class="text-red-500">*</span>
                            <span>اسم العائلة</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" name="last_name" id="last_name" value="<?php echo e(old('last_name')); ?>" required
                                   class="w-full pr-10 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="أدخل اسم العائلة"
                                   style="focus:ring-color: <?php echo e($themeColor); ?>;">
                        </div>
                        <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1 font-tajawal">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Employee ID (Auto-generated) -->
                    <div>
                        <label class="block text-sm md:text-base font-bold text-gray-700 mb-3 font-tajawal">رقم الموظف</label>
                        <div class="w-full px-4 py-3 md:py-4 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-600 flex items-center gap-3 font-medium text-base md:text-lg">
                            <svg class="w-5 h-5 flex-shrink-0" style="color: <?php echo e($themeColor); ?>;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            <span class="font-tajawal">سيتم توليد الرقم التوظيفي تلقائياً</span>
                        </div>
                        <p class="mt-2 text-xs md:text-sm text-gray-500 font-tajawal">سيتم إنشاء رقم توظيفي فريد تلقائياً</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm md:text-base font-bold text-gray-700 mb-3 flex items-center gap-2 font-tajawal">
                            <span class="text-red-500">*</span>
                            <span>البريد الإلكتروني</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required
                                   class="w-full pr-10 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="example@email.com"
                                   style="focus:ring-color: <?php echo e($themeColor); ?>;">
                        </div>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1 font-tajawal">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm md:text-base font-bold text-gray-700 mb-3 flex items-center gap-2 font-tajawal">
                            <span class="text-red-500">*</span>
                            <span>رقم الهاتف</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <input type="text" name="phone" id="phone" value="<?php echo e(old('phone')); ?>" required
                                   class="w-full pr-10 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="+966501234567"
                                   style="focus:ring-color: <?php echo e($themeColor); ?>;">
                        </div>
                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1 font-tajawal">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm md:text-base font-bold text-gray-700 mb-3 font-tajawal">العنوان</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 pt-3 pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <input type="text" name="address" id="address" value="<?php echo e(old('address')); ?>" 
                                   class="w-full pr-10 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="أدخل العنوان الكامل"
                                   style="focus:ring-color: <?php echo e($themeColor); ?>;">
                        </div>
                        <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1 font-tajawal">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Work Information -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden" style="border-color: <?php echo e($themeColor); ?>30;">
            <div class="bg-gradient-to-r px-4 md:px-6 py-3 md:py-4 border-b border-gray-200" style="background: linear-gradient(135deg, <?php echo e($themeColor); ?>15 0%, <?php echo e($themeColor); ?>25 100%);">
                <div class="flex items-center gap-2 md:gap-3">
                    <div class="p-2 rounded-xl shadow-sm" style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
                        <svg class="h-5 w-5 md:h-6 md:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900 font-tajawal">المعلومات الوظيفية</h3>
                </div>
            </div>
            
            <div class="p-4 md:p-8 space-y-4 md:space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <!-- Position -->
                    <div>
                        <label for="position" class="block text-sm md:text-base font-bold text-gray-700 mb-3 flex items-center gap-2 font-tajawal">
                            <span class="text-red-500">*</span>
                            <span>المنصب</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="text" name="position" id="position" value="<?php echo e(old('position')); ?>" required
                                   class="w-full pr-10 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="أدخل المنصب"
                                   style="focus:ring-color: <?php echo e($themeColor); ?>;">
                        </div>
                        <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1 font-tajawal">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department_id" class="block text-sm md:text-base font-bold text-gray-700 mb-3 flex items-center gap-2 font-tajawal">
                            <span class="text-red-500">*</span>
                            <span>القسم</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <select name="department_id" id="department_id" required
                                    class="w-full pr-10 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    style="focus:ring-color: <?php echo e($themeColor); ?>;">
                                <option value="">اختر القسم</option>
                                <?php $__currentLoopData = \App\Models\Department::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($department->id); ?>" <?php echo e(old('department_id') == $department->id ? 'selected' : ''); ?>>
                                        <?php echo e($department->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1 font-tajawal">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Salary -->
                    <div>
                        <label for="salary" class="block text-sm md:text-base font-bold text-gray-700 mb-3 flex items-center gap-2 font-tajawal">
                            <span class="text-red-500">*</span>
                            <span>الراتب الشهري (ج.م)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                            </div>
                            <input type="number" name="salary" id="salary" value="<?php echo e(old('salary')); ?>" required min="0" step="0.01"
                                   class="w-full pr-10 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg <?php $__errorArgs = ['salary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="0.00"
                                   style="focus:ring-color: <?php echo e($themeColor); ?>;">
                        </div>
                        <?php $__errorArgs = ['salary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1 font-tajawal">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Daily Hours -->
                    <div>
                        <label for="daily_hours" class="block text-sm md:text-base font-bold text-gray-700 mb-3 flex items-center gap-2 font-tajawal">
                            <span class="text-red-500">*</span>
                            <span>عدد الساعات اليومية</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <input type="number" name="daily_hours" id="daily_hours" value="<?php echo e(old('daily_hours', 8)); ?>" required min="1" max="12"
                                   class="w-full pr-10 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg <?php $__errorArgs = ['daily_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="8"
                                   style="focus:ring-color: <?php echo e($themeColor); ?>;">
                        </div>
                        <?php $__errorArgs = ['daily_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1 font-tajawal">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Hire Date -->
                    <div>
                        <label for="hire_date" class="block text-sm md:text-base font-bold text-gray-700 mb-3 flex items-center gap-2 font-tajawal">
                            <span class="text-red-500">*</span>
                            <span>تاريخ التوظيف</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="date" name="hire_date" id="hire_date" value="<?php echo e(old('hire_date')); ?>" required
                                   class="w-full pr-10 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg <?php $__errorArgs = ['hire_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   style="focus:ring-color: <?php echo e($themeColor); ?>;">
                        </div>
                        <?php $__errorArgs = ['hire_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1 font-tajawal">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Employment Type -->
                    <div>
                        <label for="employment_type" class="block text-sm md:text-base font-bold text-gray-700 mb-3 flex items-center gap-2 font-tajawal">
                            <span class="text-red-500">*</span>
                            <span>نوع التوظيف</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <select name="employment_type" id="employment_type" required
                                    class="w-full pr-10 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg <?php $__errorArgs = ['employment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    style="focus:ring-color: <?php echo e($themeColor); ?>;">
                                <option value="">اختر نوع التوظيف</option>
                                <option value="full_time" <?php echo e(old('employment_type') == 'full_time' ? 'selected' : ''); ?>>دوام كامل</option>
                                <option value="part_time" <?php echo e(old('employment_type') == 'part_time' ? 'selected' : ''); ?>>دوام جزئي</option>
                                <option value="contract" <?php echo e(old('employment_type') == 'contract' ? 'selected' : ''); ?>>عقد</option>
                                <option value="intern" <?php echo e(old('employment_type') == 'intern' ? 'selected' : ''); ?>>متدرب</option>
                            </select>
                        </div>
                        <?php $__errorArgs = ['employment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1 font-tajawal">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden" style="border-color: <?php echo e($themeColor); ?>30;">
            <div class="bg-gradient-to-r px-4 md:px-6 py-3 md:py-4 border-b border-gray-200" style="background: linear-gradient(135deg, <?php echo e($themeColor); ?>15 0%, <?php echo e($themeColor); ?>25 100%);">
                <div class="flex items-center gap-2 md:gap-3">
                    <div class="p-2 rounded-xl shadow-sm" style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
                        <svg class="h-5 w-5 md:h-6 md:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900 font-tajawal">جهة الاتصال في حالات الطوارئ</h3>
                </div>
            </div>
            
            <div class="p-4 md:p-8 space-y-4 md:space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <div>
                        <label for="emergency_contact" class="block text-sm md:text-base font-bold text-gray-700 mb-3 font-tajawal">اسم جهة الاتصال</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <input type="text" name="emergency_contact" id="emergency_contact" value="<?php echo e(old('emergency_contact')); ?>" 
                                   class="w-full pr-10 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg <?php $__errorArgs = ['emergency_contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="أدخل اسم جهة الاتصال"
                                   style="focus:ring-color: <?php echo e($themeColor); ?>;">
                        </div>
                        <?php $__errorArgs = ['emergency_contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1 font-tajawal">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="emergency_phone" class="block text-sm md:text-base font-bold text-gray-700 mb-3 font-tajawal">رقم هاتف الطوارئ</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <input type="text" name="emergency_phone" id="emergency_phone" value="<?php echo e(old('emergency_phone')); ?>" 
                                   class="w-full pr-10 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg <?php $__errorArgs = ['emergency_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="+966501234567"
                                   style="focus:ring-color: <?php echo e($themeColor); ?>;">
                        </div>
                        <?php $__errorArgs = ['emergency_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1 font-tajawal">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo e($message); ?>

                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-4 md:p-6" style="border-color: <?php echo e($themeColor); ?>30;">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 sm:gap-4 pt-4 border-t border-gray-200">
                <a href="<?php echo e(route('employees.index')); ?>" class="px-6 py-3 text-sm md:text-base font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center gap-2 font-tajawal">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-3 text-sm md:text-base font-semibold text-white border border-transparent rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-1 font-tajawal"
                        style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    إضافة الموظف
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function toggleUserSelection() {
    const createNewUser = document.getElementById('create_new_user');
    const userSelection = document.getElementById('user_selection_container');
    const passwordFields = document.getElementById('password_fields');
    const userSelect = document.getElementById('user_id');
    const passwordInput = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    if (createNewUser.checked) {
        // إخفاء اختيار المستخدم وإظهار حقول كلمة المرور
        userSelection.style.display = 'none';
        passwordFields.style.display = 'block';
        userSelect.required = false;
        passwordInput.required = true;
        passwordConfirmation.required = true;
        userSelect.value = '';
    } else {
        // إظهار اختيار المستخدم وإخفاء حقول كلمة المرور
        userSelection.style.display = 'block';
        passwordFields.style.display = 'none';
        userSelect.required = true;
        passwordInput.required = false;
        passwordConfirmation.required = false;
        passwordInput.value = '';
        passwordConfirmation.value = '';
    }
}

// تنفيذ الدالة عند تحميل الصفحة في حالة وجود قيمة محفوظة
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('create_new_user').checked) {
        toggleUserSelection();
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\employees\create.blade.php ENDPATH**/ ?>