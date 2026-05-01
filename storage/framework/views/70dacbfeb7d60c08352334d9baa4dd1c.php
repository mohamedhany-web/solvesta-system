<?php $__env->startSection('page-title', 'الملف الشخصي'); ?>

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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl md:text-3xl font-bold mb-1 md:mb-2 truncate font-tajawal">الملف الشخصي</h1>
                <p class="text-white/90 text-sm md:text-lg font-tajawal">إدارة معلوماتك الشخصية وإعدادات الحساب</p>
            </div>
        </div>
        <a href="<?php echo e(route('dashboard')); ?>" class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 md:px-6 md:py-3 rounded-xl hover:bg-white/30 transition-all duration-200 flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-1 w-full md:w-auto justify-center gap-2 font-tajawal">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="font-semibold text-sm md:text-base">العودة</span>
        </a>
    </div>
</div>

<div class="max-w-5xl mx-auto">

    <form action="<?php echo e(route('profile.update')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <!-- Profile Picture Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden" style="border-color: <?php echo e($themeColor); ?>30;">
            <div class="bg-gradient-to-r px-4 md:px-6 py-3 md:py-4 border-b border-gray-200" style="background: linear-gradient(135deg, <?php echo e($themeColor); ?>15 0%, <?php echo e($themeColor); ?>25 100%);">
                <div class="flex items-center gap-2 md:gap-3">
                    <div class="p-2 rounded-xl shadow-sm" style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
                        <svg class="h-5 w-5 md:h-6 md:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900 font-tajawal">الصورة الشخصية</h3>
                </div>
            </div>
            
            <div class="p-4 md:p-8 space-y-4 md:space-y-6">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 md:gap-6">
                    <div class="relative flex-shrink-0">
                        <?php if($user->profile_picture): ?>
                            <img src="<?php echo e(asset('storage/' . $user->profile_picture)); ?>" alt="Profile Picture" class="h-24 w-24 sm:h-32 sm:w-32 md:h-36 md:w-36 rounded-full object-cover shadow-xl border-4 border-white" style="border-color: <?php echo e($themeColor); ?>20;">
                        <?php else: ?>
                            <div class="h-24 w-24 sm:h-32 sm:w-32 md:h-36 md:w-36 rounded-full flex items-center justify-center shadow-xl border-4 border-white" style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%); border-color: <?php echo e($themeColor); ?>20;">
                                <span class="text-3xl sm:text-4xl md:text-5xl font-bold text-white font-tajawal"><?php echo e(substr($user->name, 0, 1)); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 w-full sm:w-auto">
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-3">
                            <label for="profile_picture" class="cursor-pointer px-4 py-2.5 md:px-6 md:py-3 rounded-xl transition-all duration-200 text-sm md:text-base font-semibold flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-1 font-tajawal"
                                   style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%); color: white;">
                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                تغيير الصورة
                            </label>
                            <?php if($user->profile_picture): ?>
                                <button type="button" onclick="deleteProfilePicture()" class="bg-red-600 text-white px-4 py-2.5 md:px-6 md:py-3 rounded-xl hover:bg-red-700 transition-all duration-200 text-sm md:text-base font-semibold flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-1 font-tajawal">
                                    <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    حذف الصورة
                                </button>
                            <?php endif; ?>
                        </div>
                        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="hidden">
                        <p class="text-xs md:text-sm text-gray-500 mt-3 font-tajawal">JPG, PNG أو GIF. الحد الأقصى 2 ميجابايت</p>
                    </div>
                </div>
            </div>
        </div>

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
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <div>
                        <label for="name" class="block text-sm md:text-base font-bold text-gray-700 mb-3 flex items-center gap-2 font-tajawal">
                            <span class="text-red-500">*</span>
                            <span>الاسم الكامل</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" id="name" name="name" value="<?php echo e(old('name', $user->name)); ?>" required
                                   class="w-full pr-10 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="أدخل الاسم الكامل"
                                   style="focus:ring-color: <?php echo e($themeColor); ?>;">
                        </div>
                        <?php $__errorArgs = ['name'];
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
                            <input type="email" id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required
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
                </div>
            </div>
        </div>

        <!-- Password Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden" style="border-color: <?php echo e($themeColor); ?>30;">
            <div class="bg-gradient-to-r px-4 md:px-6 py-3 md:py-4 border-b border-gray-200" style="background: linear-gradient(135deg, <?php echo e($themeColor); ?>15 0%, <?php echo e($themeColor); ?>25 100%);">
                <div class="flex items-center gap-2 md:gap-3">
                    <div class="p-2 rounded-xl shadow-sm" style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
                        <svg class="h-5 w-5 md:h-6 md:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900 font-tajawal">تغيير كلمة المرور</h3>
                </div>
            </div>
            
            <div class="p-4 md:p-8 space-y-4 md:space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <div>
                        <label for="current_password" class="block text-sm md:text-base font-bold text-gray-700 mb-3 font-tajawal">كلمة المرور الحالية</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="current_password" name="current_password" 
                                   class="w-full pr-10 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="••••••••"
                                   style="focus:ring-color: <?php echo e($themeColor); ?>;">
                        </div>
                        <?php $__errorArgs = ['current_password'];
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
                        <label for="password" class="block text-sm md:text-base font-bold text-gray-700 mb-3 font-tajawal">كلمة المرور الجديدة</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input type="password" id="password" name="password" 
                                   class="w-full pr-10 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   placeholder="••••••••"
                                   style="focus:ring-color: <?php echo e($themeColor); ?>;">
                        </div>
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
                        <label for="password_confirmation" class="block text-sm md:text-base font-bold text-gray-700 mb-3 font-tajawal">تأكيد كلمة المرور</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="w-full pr-10 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:border-transparent transition-all duration-200 text-gray-900 font-medium text-base md:text-lg"
                                   placeholder="••••••••"
                                   style="focus:ring-color: <?php echo e($themeColor); ?>;">
                        </div>
                    </div>
                </div>
                <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
                    <p class="text-xs md:text-sm text-blue-700 font-tajawal flex items-center gap-2">
                        <svg class="h-4 w-4 md:h-5 md:w-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        اترك كلمة المرور فارغة إذا كنت لا تريد تغييرها
                    </p>
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden" style="border-color: <?php echo e($themeColor); ?>30;">
            <div class="bg-gradient-to-r px-4 md:px-6 py-3 md:py-4 border-b border-gray-200" style="background: linear-gradient(135deg, <?php echo e($themeColor); ?>15 0%, <?php echo e($themeColor); ?>25 100%);">
                <div class="flex items-center gap-2 md:gap-3">
                    <div class="p-2 rounded-xl shadow-sm" style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
                        <svg class="h-5 w-5 md:h-6 md:w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900 font-tajawal">معلومات الحساب</h3>
                </div>
            </div>
            
            <div class="p-4 md:p-8 space-y-4 md:space-y-6">
                <div class="bg-gray-50 rounded-xl p-4 md:p-6 border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div>
                            <label class="block text-xs md:text-sm font-semibold text-gray-500 mb-2 font-tajawal">تاريخ إنشاء الحساب</label>
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-sm md:text-base text-gray-900 font-medium font-tajawal"><?php echo e($user->created_at->format('Y/m/d')); ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs md:text-sm font-semibold text-gray-500 mb-2 font-tajawal">آخر تحديث</label>
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm md:text-base text-gray-900 font-medium font-tajawal"><?php echo e($user->updated_at->format('Y/m/d H:i')); ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs md:text-sm font-semibold text-gray-500 mb-2 font-tajawal">الدور</label>
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="text-sm md:text-base text-gray-900 font-medium font-tajawal"><?php echo e($user->roles->first()->name ?? 'لا يوجد دور'); ?></p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs md:text-sm font-semibold text-gray-500 mb-2 font-tajawal">حالة الحساب</label>
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs md:text-sm font-semibold bg-green-100 text-green-800 border border-green-200 font-tajawal">
                                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                نشط
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-4 md:p-6" style="border-color: <?php echo e($themeColor); ?>30;">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 sm:gap-4 pt-4 border-t border-gray-200">
                <a href="<?php echo e(route('dashboard')); ?>" class="px-6 py-3 text-sm md:text-base font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center gap-2 font-tajawal">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-3 text-sm md:text-base font-semibold text-white border border-transparent rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-1 font-tajawal"
                        style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    حفظ التغييرات
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Delete Profile Picture Form -->
<form id="deleteProfilePictureForm" action="<?php echo e(route('profile.delete-picture')); ?>" method="POST" class="hidden">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>

<script>
function deleteProfilePicture() {
    if (confirm('هل أنت متأكد من حذف الصورة الشخصية؟')) {
        document.getElementById('deleteProfilePictureForm').submit();
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\profile\index.blade.php ENDPATH**/ ?>