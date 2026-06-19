<?php
    $themeColor = $themeColor ?? \App\Helpers\SettingsHelper::getThemeColor();
    $inputClass = 'w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:border-transparent';
    $labelClass = 'text-xs font-bold text-gray-600 block mb-1.5';
    $employmentTypes = [
        'full_time' => 'دوام كامل',
        'part_time' => 'دوام جزئي',
        'contract' => 'عقد',
        'intern' => 'متدرب',
    ];
?>

<form action="<?php echo e($action); ?>" method="POST" class="space-y-6">
    <?php echo csrf_field(); ?>
    <?php if(($method ?? 'POST') !== 'POST'): ?>
        <?php echo method_field($method); ?>
    <?php endif; ?>

    
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">حساب المستخدم</h2>
            <p class="text-sm text-gray-500 mt-0.5">ربط بمستخدم موجود أو إنشاء حساب دخول جديد</p>
        </div>
        <div class="p-6 space-y-5">
            <label class="flex items-start gap-3 p-4 rounded-xl border-2 border-gray-200 cursor-pointer has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50/50 transition">
                <input type="checkbox" name="create_new_user" id="create_new_user" value="1"
                       class="mt-1 w-4 h-4 rounded text-blue-600"
                       <?php echo e(old('create_new_user') ? 'checked' : ''); ?>

                       onchange="toggleUserSelection()">
                <div>
                    <span class="block text-sm font-bold text-gray-900">إنشاء حساب مستخدم جديد تلقائياً</span>
                    <span class="text-xs text-gray-500 mt-0.5 block">يُنشأ حساب دخول بنفس البريد والبيانات المدخلة</span>
                </div>
            </label>

            <div id="user_selection_container">
                <label for="user_id" class="<?php echo e($labelClass); ?>">اختيار مستخدم موجود <span class="text-red-500">*</span></label>
                <select name="user_id" id="user_id"
                        class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                    <option value="">اختر المستخدم</option>
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($user->id); ?>" <?php if(old('user_id') == $user->id): echo 'selected'; endif; ?>>
                            <?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div id="password_fields" class="hidden">
                <div class="rounded-xl border border-amber-200 bg-amber-50/60 p-4 space-y-4">
                    <p class="text-sm font-bold text-amber-900">بيانات حساب المستخدم الجديد</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="<?php echo e($labelClass); ?>">كلمة المرور <span class="text-red-500">*</span></label>
                            <input type="password" name="password" id="password"
                                   class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   style="--tw-ring-color: <?php echo e($themeColor); ?>;" placeholder="••••••••">
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div>
                            <label for="password_confirmation" class="<?php echo e($labelClass); ?>">تأكيد كلمة المرور <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="<?php echo e($inputClass); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;" placeholder="••••••••">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">المعلومات الشخصية</h2>
            <p class="text-sm text-gray-500 mt-0.5">الاسم والتواصل والعنوان</p>
        </div>
        <div class="p-6 space-y-5">
            <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                الرقم التوظيفي يُولَّد تلقائياً عند الحفظ.
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="<?php echo e($labelClass); ?>">الاسم الأول <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" id="first_name" value="<?php echo e(old('first_name')); ?>" required
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           style="--tw-ring-color: <?php echo e($themeColor); ?>;" placeholder="الاسم الأول">
                    <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="last_name" class="<?php echo e($labelClass); ?>">اسم العائلة <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" id="last_name" value="<?php echo e(old('last_name')); ?>" required
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           style="--tw-ring-color: <?php echo e($themeColor); ?>;" placeholder="اسم العائلة">
                    <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="email" class="<?php echo e($labelClass); ?>">البريد الإلكتروني <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>" required dir="ltr"
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           style="--tw-ring-color: <?php echo e($themeColor); ?>;" placeholder="name@company.com">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="phone" class="<?php echo e($labelClass); ?>">رقم الهاتف <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" id="phone" value="<?php echo e(old('phone')); ?>" required dir="ltr"
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           style="--tw-ring-color: <?php echo e($themeColor); ?>;" placeholder="+20...">
                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="sm:col-span-2">
                    <label for="address" class="<?php echo e($labelClass); ?>">العنوان</label>
                    <input type="text" name="address" id="address" value="<?php echo e(old('address')); ?>"
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           style="--tw-ring-color: <?php echo e($themeColor); ?>;" placeholder="العنوان الكامل">
                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">المعلومات الوظيفية</h2>
            <p class="text-sm text-gray-500 mt-0.5">القسم، الراتب، ونوع التوظيف</p>
        </div>
        <div class="p-6 space-y-4">
            <div id="dept-profile-box" class="hidden rounded-xl border border-indigo-100 bg-indigo-50/60 p-4 text-sm text-indigo-900">
                <p class="font-bold mb-2">ملف القسم المختار</p>
                <p id="dept-profile-kpi" class="text-xs mb-2"></p>
                <p id="dept-profile-modules" class="text-xs"></p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="department_id" class="<?php echo e($labelClass); ?>">القسم <span class="text-red-500">*</span></label>
                    <select name="department_id" id="department_id" required
                            class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            style="--tw-ring-color: <?php echo e($themeColor); ?>;" onchange="loadDepartmentProfile(this.value)">
                        <option value="">اختر القسم</option>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($department->id); ?>" <?php if(old('department_id', $preselectedDepartmentId ?? null) == $department->id): echo 'selected'; endif; ?>>
                                <?php echo e($department->parent ? $department->parent->name.' › ' : ''); ?><?php echo e($department->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="system_role" class="<?php echo e($labelClass); ?>">الدور / الصلاحيات <span class="text-red-500">*</span></label>
                    <select name="system_role" id="system_role" required class="<?php echo e($inputClass); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                        <?php $__currentLoopData = ($assignableRoles ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($role); ?>" <?php if(old('system_role') === $role): echo 'selected'; endif; ?>>
                                <?php echo e(($roleLabels ?? [])[$role] ?? $role); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['system_role'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="position" class="<?php echo e($labelClass); ?>">المسمى الوظيفي <span class="text-red-500">*</span></label>
                    <input type="text" name="position" id="position" value="<?php echo e(old('position')); ?>" required
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           style="--tw-ring-color: <?php echo e($themeColor); ?>;" placeholder="مثال: مبرمج ويب">
                    <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="career_level" class="<?php echo e($labelClass); ?>">المستوى الوظيفي (Pipeline)</label>
                    <select name="career_level" id="career_level" class="<?php echo e($inputClass); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                        <option value="">يُحدد تلقائياً</option>
                    </select>
                </div>
                <div>
                    <label for="salary" class="<?php echo e($labelClass); ?>">الراتب الشهري (ج.م) <span class="text-red-500">*</span></label>
                    <input type="number" name="salary" id="salary" value="<?php echo e(old('salary')); ?>" required min="0" step="0.01"
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['salary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           style="--tw-ring-color: <?php echo e($themeColor); ?>;" placeholder="0.00">
                    <?php $__errorArgs = ['salary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="daily_hours" class="<?php echo e($labelClass); ?>">عدد الساعات اليومية <span class="text-red-500">*</span></label>
                    <input type="number" name="daily_hours" id="daily_hours" value="<?php echo e(old('daily_hours', 8)); ?>" required min="1" max="12"
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['daily_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                    <?php $__errorArgs = ['daily_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="hire_date" class="<?php echo e($labelClass); ?>">تاريخ التوظيف <span class="text-red-500">*</span></label>
                    <input type="date" name="hire_date" id="hire_date" value="<?php echo e(old('hire_date')); ?>" required
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['hire_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                    <?php $__errorArgs = ['hire_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="employment_type" class="<?php echo e($labelClass); ?>">نوع التوظيف <span class="text-red-500">*</span></label>
                    <select name="employment_type" id="employment_type" required
                            class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['employment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                        <option value="">اختر نوع التوظيف</option>
                        <?php $__currentLoopData = $employmentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php if(old('employment_type') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['employment_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">جهة الاتصال للطوارئ</h2>
            <p class="text-sm text-gray-500 mt-0.5">اختياري — للحالات الطارئة</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="emergency_contact" class="<?php echo e($labelClass); ?>">اسم جهة الاتصال</label>
                    <input type="text" name="emergency_contact" id="emergency_contact" value="<?php echo e(old('emergency_contact')); ?>"
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['emergency_contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                    <?php $__errorArgs = ['emergency_contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="emergency_phone" class="<?php echo e($labelClass); ?>">رقم هاتف الطوارئ</label>
                    <input type="text" name="emergency_phone" id="emergency_phone" value="<?php echo e(old('emergency_phone')); ?>" dir="ltr"
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['emergency_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                    <?php $__errorArgs = ['emergency_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-2">
        <a href="<?php echo e($cancelUrl); ?>" class="inline-flex items-center justify-center gap-2 border border-gray-300 bg-white px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            إلغاء
        </a>
        <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm hover:opacity-95 transition"
                style="background: <?php echo e($themeColor); ?>;">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <?php echo e($submitLabel); ?>

        </button>
    </div>
</form>

<?php $__env->startPush('scripts'); ?>
<script>
function toggleUserSelection() {
    const createNewUser = document.getElementById('create_new_user');
    const userSelection = document.getElementById('user_selection_container');
    const passwordFields = document.getElementById('password_fields');
    const userSelect = document.getElementById('user_id');
    const passwordInput = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');

    if (createNewUser.checked) {
        userSelection.classList.add('hidden');
        passwordFields.classList.remove('hidden');
        userSelect.required = false;
        passwordInput.required = true;
        passwordConfirmation.required = true;
        userSelect.value = '';
    } else {
        userSelection.classList.remove('hidden');
        passwordFields.classList.add('hidden');
        userSelect.required = true;
        passwordInput.required = false;
        passwordConfirmation.required = false;
        passwordInput.value = '';
        passwordConfirmation.value = '';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('create_new_user')?.checked) {
        toggleUserSelection();
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\employees\partials\form.blade.php ENDPATH**/ ?>