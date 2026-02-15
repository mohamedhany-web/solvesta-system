<?php $__env->startSection('page-title', 'إضافة مشروع جديد'); ?>

<?php $__env->startSection('content'); ?>
<!-- Header Section -->
<div class="bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-700 rounded-2xl p-6 md:p-8 text-white mb-6 md:mb-8 shadow-xl relative overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-full opacity-10">
        <div class="absolute top-10 right-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-10 w-96 h-96 bg-blue-300 rounded-full blur-3xl"></div>
    </div>
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between relative z-10 gap-4">
        <div class="flex items-center gap-3 md:gap-4 w-full md:w-auto">
            <div class="p-3 md:p-4 bg-white/20 backdrop-blur-sm rounded-xl md:rounded-2xl flex-shrink-0">
                <svg class="h-8 w-8 md:h-10 md:w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl md:text-3xl font-bold mb-1 md:mb-2 truncate">إضافة مشروع جديد</h1>
                <p class="text-purple-100 text-sm md:text-lg">أنشئ مشروعاً جديداً وابدأ في تنفيذه</p>
            </div>
        </div>
        <a href="<?php echo e(route('projects.index')); ?>" class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 md:px-6 md:py-3 rounded-xl hover:bg-white/30 transition-all duration-200 flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-1 w-full md:w-auto justify-center gap-2">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="font-semibold text-sm md:text-base">العودة</span>
        </a>
    </div>
</div>

<div class="max-w-5xl mx-auto">
    <form action="<?php echo e(route('projects.store')); ?>" method="POST" class="space-y-6" x-data="{ activeTab: 'info' }">
        <?php echo csrf_field(); ?>
        
        <?php if(request('type')): ?>
        <input type="hidden" name="project_type" value="<?php echo e(request('type')); ?>">
        <?php endif; ?>
        
        <!-- Progress Indicator -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 md:p-6 mb-6">
            <div class="flex items-center justify-center gap-2 md:gap-4 overflow-x-auto">
                <div class="flex items-center gap-1 md:gap-2 flex-shrink-0">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white flex items-center justify-center font-bold shadow-md text-sm md:text-base">1</div>
                    <span class="font-semibold text-gray-700 text-xs md:text-sm whitespace-nowrap">معلومات المشروع</span>
                </div>
                <div class="w-8 md:w-16 h-1 bg-gray-300 rounded flex-shrink-0"></div>
                <div class="flex items-center gap-1 md:gap-2 flex-shrink-0">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-sm md:text-base">2</div>
                    <span class="font-medium text-gray-500 text-xs md:text-sm whitespace-nowrap hidden sm:inline">الجدول والميزانية</span>
                    <span class="font-medium text-gray-500 text-xs whitespace-nowrap sm:hidden">الميزانية</span>
                </div>
                <div class="w-8 md:w-16 h-1 bg-gray-300 rounded flex-shrink-0"></div>
                <div class="flex items-center gap-1 md:gap-2 flex-shrink-0">
                    <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center font-bold text-sm md:text-base">3</div>
                    <span class="font-medium text-gray-500 text-xs md:text-sm whitespace-nowrap">فريق العمل</span>
                </div>
            </div>
        </div>
        
        <!-- Project Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-4 md:px-6 py-3 md:py-4 border-b border-gray-200">
                <div class="flex items-center gap-2 md:gap-3">
                    <svg class="h-5 w-5 md:h-6 md:w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900">معلومات المشروع الأساسية</h3>
                </div>
            </div>
            
            <div class="p-4 md:p-8 space-y-4 md:space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <span class="text-red-500">*</span>
                        <span>اسم المشروع</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <input type="text" name="name" id="name" value="<?php echo e(old('name')); ?>" required
                               class="w-full pr-10 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 text-gray-900 font-medium text-base md:text-lg <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="أدخل اسم المشروع">
                    </div>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
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

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <span class="text-red-500">*</span>
                        <span>وصف المشروع</span>
                    </label>
                    <textarea name="description" id="description" rows="4" required
                              class="w-full px-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 text-gray-900 resize-none text-base md:text-lg <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                              placeholder="أدخل وصفاً مفصلاً للمشروع..."><?php echo e(old('description')); ?></textarea>
                    <div class="flex items-center justify-between mt-2">
                        <p class="text-xs text-gray-500">قم بوصف المشروع بشكل واضح ومفصل</p>
                    </div>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <!-- Client -->
                    <div>
                        <label for="client_id" class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <span class="text-red-500">*</span>
                            <span>العميل</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <select name="client_id" id="client_id" required
                                    class="w-full pr-10 pl-4 py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 appearance-none bg-white cursor-pointer <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">اختر العميل</option>
                                <?php $__currentLoopData = \App\Models\Client::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($client->id); ?>" <?php echo e(old('client_id') == $client->id ? 'selected' : ''); ?>>
                                        <?php echo e($client->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
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

                    <!-- Project Manager -->
                    <div>
                        <label for="project_manager_id" class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <span class="text-red-500">*</span>
                            <span>مدير المشروع</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <select name="project_manager_id" id="project_manager_id" required
                                    class="w-full pr-10 pl-4 py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 appearance-none bg-white cursor-pointer <?php $__errorArgs = ['project_manager_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">اختر مدير المشروع</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" <?php echo e(old('project_manager_id') == $user->id ? 'selected' : ''); ?>>
                                        <?php echo e($user->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <?php $__errorArgs = ['project_manager_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-bold text-gray-700 mb-3">الحالة</label>
                        <select name="status" id="status" 
                                class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 appearance-none bg-white cursor-pointer <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="planning" <?php echo e(old('status') == 'planning' ? 'selected' : ''); ?>>🟡 تخطيط</option>
                            <option value="in_progress" <?php echo e(old('status') == 'in_progress' ? 'selected' : ''); ?>>🔵 قيد التنفيذ</option>
                            <option value="on_hold" <?php echo e(old('status') == 'on_hold' ? 'selected' : ''); ?>>🟠 معلق</option>
                            <option value="completed" <?php echo e(old('status') == 'completed' ? 'selected' : ''); ?>>🟢 مكتمل</option>
                            <option value="cancelled" <?php echo e(old('status') == 'cancelled' ? 'selected' : ''); ?>>🔴 ملغي</option>
                        </select>
                    </div>
                    
                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-sm font-bold text-gray-700 mb-3">الأولوية</label>
                        <select name="priority" id="priority" 
                                class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 appearance-none bg-white cursor-pointer <?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="low" <?php echo e(old('priority') == 'low' ? 'selected' : ''); ?>>🟢 منخفضة</option>
                            <option value="medium" <?php echo e(old('priority') == 'medium' ? 'selected' : ''); ?>>🟡 متوسطة</option>
                            <option value="high" <?php echo e(old('priority') == 'high' ? 'selected' : ''); ?>>🟠 عالية</option>
                            <option value="urgent" <?php echo e(old('priority') == 'urgent' ? 'selected' : ''); ?>>🔴 عاجلة</option>
                        </select>
                    </div>
                </div>

                <!-- Project Type -->
                <?php if(!request('type')): ?>
                <div>
                    <label for="project_type" class="block text-sm font-bold text-gray-700 mb-3">نوع المشروع (اختياري)</label>
                    <select name="project_type" id="project_type" 
                            class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200 appearance-none bg-white cursor-pointer">
                        <option value="">اختر النوع</option>
                        <option value="design" <?php echo e(old('project_type') == 'design' ? 'selected' : ''); ?>>🎨 تصميم</option>
                        <option value="marketing" <?php echo e(old('project_type') == 'marketing' ? 'selected' : ''); ?>>📢 تسويق</option>
                        <option value="development" <?php echo e(old('project_type') == 'development' ? 'selected' : ''); ?>>💻 تطوير</option>
                        <option value="maintenance" <?php echo e(old('project_type') == 'maintenance' ? 'selected' : ''); ?>>🔧 صيانة</option>
                    </select>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Project Timeline & Budget -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-4 md:px-6 py-3 md:py-4 border-b border-gray-200">
                <div class="flex items-center gap-2 md:gap-3">
                    <svg class="h-5 w-5 md:h-6 md:w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900">الجدول الزمني والميزانية</h3>
                </div>
            </div>
            
            <div class="p-4 md:p-8 space-y-4 md:space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>تاريخ البداية</span>
                        </label>
                        <input type="date" name="start_date" id="start_date" value="<?php echo e(old('start_date')); ?>" 
                               class="w-full px-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 text-base md:text-lg <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                            <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>تاريخ الانتهاء المتوقع</span>
                        </label>
                        <input type="date" name="end_date" id="end_date" value="<?php echo e(old('end_date')); ?>" 
                               class="w-full px-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 text-base md:text-lg <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <!-- Budget -->
                <div>
                    <label for="budget" class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>الميزانية (جنيه مصري)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 font-bold">ج.م</span>
                        </div>
                        <input type="number" name="budget" id="budget" value="<?php echo e(old('budget')); ?>" step="0.01"
                               class="w-full pr-14 pl-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-900 text-base md:text-lg <?php $__errorArgs = ['budget'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="0.00">
                    </div>
                    <?php $__errorArgs = ['budget'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>
        
        <!-- Team Members -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-4 md:px-6 py-3 md:py-4 border-b border-gray-200">
                <div class="flex items-center gap-2 md:gap-3">
                    <svg class="h-5 w-5 md:h-6 md:w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="text-lg md:text-xl font-bold text-gray-900">فريق العمل (اختياري)</h3>
                </div>
            </div>
            
            <div class="p-4 md:p-8">
                <div>
                    <label for="team_members" class="block text-sm font-bold text-gray-700 mb-3">أعضاء الفريق</label>
                    <select name="team_members[]" id="team_members" multiple
                            class="w-full px-4 py-4 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 text-gray-900 min-h-[200px] <?php $__errorArgs = ['team_members'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php echo e((is_array(old('team_members')) && in_array($user->id, old('team_members'))) ? 'selected' : ''); ?>>
                                👤 <?php echo e($user->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <p class="mt-3 text-sm text-gray-600 bg-gray-50 border border-gray-200 rounded-lg p-3 flex items-start gap-2">
                        <svg class="h-5 w-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>للاختيار المتعدد: في Windows اضغط <kbd class="px-2 py-1 bg-gray-200 rounded font-mono text-xs font-bold">Ctrl</kbd>، في Mac اضغط <kbd class="px-2 py-1 bg-gray-200 rounded font-mono text-xs font-bold">Cmd</kbd></span>
                    </p>
                    <?php $__errorArgs = ['team_members'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between bg-white rounded-2xl shadow-lg border border-gray-200 p-6 gap-6">
            <div class="flex items-center gap-2 md:gap-3 text-gray-600 justify-center md:justify-start order-2 md:order-1">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium text-center md:text-right">جميع الحقول المميزة بـ <span class="text-red-500 font-bold">*</span> إجبارية</span>
            </div>
            <div class="flex flex-col md:flex-row items-stretch md:items-center gap-3 w-full md:w-auto order-1 md:order-2">
                <a href="<?php echo e(route('projects.index')); ?>" class="flex-1 md:flex-none px-6 py-3.5 text-base font-semibold text-gray-700 bg-white border-2 border-gray-300 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-all duration-200 transform hover:-translate-y-0.5 shadow-sm hover:shadow-md text-center">
                    إلغاء
                </a>
                <button type="submit" class="flex-1 md:flex-none px-6 md:px-8 py-3.5 text-base font-bold text-white bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-700 border border-transparent rounded-xl hover:from-purple-700 hover:via-indigo-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 transform hover:-translate-y-1 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>إنشاء المشروع</span>
                </button>
            </div>
        </div>
    </form>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    select:focus {
        outline: none;
    }
    
    kbd {
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    
    /* Smooth transitions for all inputs */
    input, select, textarea {
        transition: all 0.2s ease-in-out;
    }
    
    /* Custom scrollbar for select multiple */
    select[multiple] {
        scrollbar-width: thin;
        scrollbar-color: #9333ea #f3f4f6;
    }
    
    select[multiple]::-webkit-scrollbar {
        width: 8px;
    }
    
    select[multiple]::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 8px;
    }
    
    select[multiple]::-webkit-scrollbar-thumb {
        background: #9333ea;
        border-radius: 8px;
    }
    
    select[multiple]::-webkit-scrollbar-thumb:hover {
        background: #7e22ce;
    }
    
    /* Mobile optimizations */
    @media (max-width: 768px) {
        /* Ensure inputs are readable on mobile */
        input, select, textarea {
            font-size: 16px !important; /* Prevents zoom on iOS */
        }
        
        /* Better touch targets */
        button, a {
            min-height: 44px;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views/projects/create.blade.php ENDPATH**/ ?>