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
    $statuses = [
        'active' => 'نشط',
        'on_leave' => 'في إجازة',
        'inactive' => 'غير نشط',
        'terminated' => 'مفصول',
    ];
    $fullName = trim($employee->first_name.' '.$employee->last_name);
?>

<form action="<?php echo e($action); ?>" method="POST" class="space-y-6">
    <?php echo csrf_field(); ?>
    <?php if(($method ?? 'POST') !== 'POST'): ?>
        <?php echo method_field($method); ?>
    <?php endif; ?>

    <?php if($employee->user): ?>
    <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-900">
        <p class="font-bold mb-1">المستخدم المرتبط: <?php echo e($employee->user->name); ?></p>
        <p class="text-blue-800/90"><?php echo e($employee->user->email); ?> — يُحدَّث الاسم والبريد تلقائياً عند الحفظ.</p>
        <a href="<?php echo e(route('users.show', $employee->user)); ?>" class="text-xs font-bold mt-2 inline-block hover:underline" style="color: <?php echo e($themeColor); ?>;">عرض حساب المستخدم →</a>
    </div>
    <?php endif; ?>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">المعلومات الشخصية</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="employee_id" class="<?php echo e($labelClass); ?>">رقم الموظف <span class="text-red-500">*</span></label>
                    <input type="text" name="employee_id" id="employee_id" value="<?php echo e(old('employee_id', $employee->employee_id)); ?>" required dir="ltr"
                           class="<?php echo e($inputClass); ?> font-mono <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                    <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="first_name" class="<?php echo e($labelClass); ?>">الاسم الأول <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" id="first_name" value="<?php echo e(old('first_name', $employee->first_name)); ?>" required
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
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
                    <input type="text" name="last_name" id="last_name" value="<?php echo e(old('last_name', $employee->last_name)); ?>" required
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
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
                    <input type="email" name="email" id="email" value="<?php echo e(old('email', $employee->email)); ?>" required dir="ltr"
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
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
                    <input type="text" name="phone" id="phone" value="<?php echo e(old('phone', $employee->phone)); ?>" required dir="ltr"
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
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
                    <input type="text" name="address" id="address" value="<?php echo e(old('address', $employee->address)); ?>"
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
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
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="position" class="<?php echo e($labelClass); ?>">المسمى الوظيفي <span class="text-red-500">*</span></label>
                    <input type="text" name="position" id="position" value="<?php echo e(old('position', $employee->position)); ?>" required
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['position'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
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
                    <label for="department_id" class="<?php echo e($labelClass); ?>">القسم <span class="text-red-500">*</span></label>
                    <select name="department_id" id="department_id" required
                            class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                        <option value="">اختر القسم</option>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($department->id); ?>" <?php if(old('department_id', $employee->department_id) == $department->id): echo 'selected'; endif; ?>>
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
                <?php if($employee->user): ?>
                <div>
                    <label for="system_role" class="<?php echo e($labelClass); ?>">الدور / الصلاحيات</label>
                    <select name="system_role" id="system_role" class="<?php echo e($inputClass); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                        <?php $__currentLoopData = ($assignableRoles ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($role); ?>" <?php if(old('system_role', $currentRole ?? '') === $role): echo 'selected'; endif; ?>>
                                <?php echo e(($roleLabels ?? [])[$role] ?? $role); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <?php endif; ?>
                <div>
                    <label for="salary" class="<?php echo e($labelClass); ?>">الراتب الشهري (ج.م) <span class="text-red-500">*</span></label>
                    <input type="number" name="salary" id="salary" value="<?php echo e(old('salary', $employee->salary)); ?>" required min="0" step="0.01"
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['salary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
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
                    <label for="daily_hours" class="<?php echo e($labelClass); ?>">ساعات العمل اليومية <span class="text-red-500">*</span></label>
                    <input type="number" name="daily_hours" id="daily_hours" value="<?php echo e(old('daily_hours', $employee->daily_hours ?? 8)); ?>" required min="1" max="12"
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['daily_hours'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
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
                    <input type="date" name="hire_date" id="hire_date"
                           value="<?php echo e(old('hire_date', $employee->hire_date?->format('Y-m-d'))); ?>" required
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['hire_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
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
unset($__errorArgs, $__bag); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                        <?php $__currentLoopData = $employmentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php if(old('employment_type', $employee->employment_type) === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
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
                <div>
                    <label for="status" class="<?php echo e($labelClass); ?>">الحالة <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                            class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($value); ?>" <?php if(old('status', $employee->status) === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['status'];
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
            <h2 class="font-bold text-lg text-gray-900">التدرج الوظيفي</h2>
            <p class="text-sm text-gray-500 mt-0.5">من يراجع تقاريره اليومية؟</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="flex items-center gap-2 cursor-pointer mb-4">
                        <input type="checkbox" name="is_team_lead" value="1" <?php if(old('is_team_lead', $employee->is_team_lead)): echo 'checked'; endif; ?>
                               class="h-4 w-4 rounded border-gray-300" style="color: <?php echo e($themeColor); ?>;">
                        <span class="text-sm font-semibold text-gray-800">قائد فريق (Team Lead)</span>
                    </label>
                </div>
                <div>
                    <label for="supervisor_user_id" class="<?php echo e($labelClass); ?>">المشرف المباشر</label>
                    <select name="supervisor_user_id" id="supervisor_user_id"
                            class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['supervisor_user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                        <option value="">رئيس القسم (افتراضي)</option>
                        <?php $__currentLoopData = ($supervisorOptions ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supervisor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($supervisor->id !== $employee->user_id): ?>
                            <option value="<?php echo e($supervisor->id); ?>" <?php if(old('supervisor_user_id', $employee->supervisor_user_id) == $supervisor->id): echo 'selected'; endif; ?>><?php echo e($supervisor->name); ?></option>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['supervisor_user_id'];
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
                        <option value="<?php echo e(old('career_level', $employee->career_level)); ?>"><?php echo e(old('career_level', $employee->career_level) ?: '—'); ?></option>
                        <?php $__currentLoopData = ($deptProfile['levels'] ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($level !== $employee->career_level): ?>
                            <option value="<?php echo e($level); ?>"><?php echo e($level); ?></option>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php if($employee->career_track): ?>
                    <p class="text-xs text-gray-500 mt-1">مسار: <?php echo e($employee->career_track); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">جهة الاتصال للطوارئ</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="emergency_contact" class="<?php echo e($labelClass); ?>">الاسم</label>
                    <input type="text" name="emergency_contact" id="emergency_contact" value="<?php echo e(old('emergency_contact', $employee->emergency_contact)); ?>"
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['emergency_contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
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
                    <label for="emergency_phone" class="<?php echo e($labelClass); ?>">الهاتف</label>
                    <input type="text" name="emergency_phone" id="emergency_phone" value="<?php echo e(old('emergency_phone', $employee->emergency_phone)); ?>" dir="ltr"
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['emergency_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
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

    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
        <a href="<?php echo e($cancelUrl); ?>" class="inline-flex items-center justify-center gap-2 border border-gray-300 bg-white px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">إلغاء</a>
        <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm hover:opacity-95"
                style="background: <?php echo e($themeColor); ?>;"><?php echo e($submitLabel); ?></button>
    </div>
</form>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\employees\partials\edit-form.blade.php ENDPATH**/ ?>