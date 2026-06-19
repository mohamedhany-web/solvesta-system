<?php
    $themeColor = $themeColor ?? \App\Helpers\SettingsHelper::getThemeColor();
    $inputClass = 'w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:border-transparent';
    $labelClass = 'text-xs font-bold text-gray-600 block mb-1.5';
    $selectedModules = old('sidebar_modules', $department->sidebar_modules ?? []);
    $parentDepartments = $parentDepartments ?? collect();
?>

<form action="<?php echo e($action); ?>" method="POST" class="space-y-6">
    <?php echo csrf_field(); ?>
    <?php if(($method ?? 'POST') !== 'POST'): ?>
        <?php echo method_field($method); ?>
    <?php endif; ?>

    
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">المعلومات الأساسية</h2>
            <p class="text-sm text-gray-500 mt-0.5">الاسم، الكود، والوصف</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label for="name" class="<?php echo e($labelClass); ?>">اسم القسم <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="<?php echo e(old('name', $department->name)); ?>" required
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="code" class="<?php echo e($labelClass); ?>">كود القسم <span class="text-red-500">*</span></label>
                    <input type="text" name="code" id="code" value="<?php echo e(old('code', $department->code)); ?>" required dir="ltr"
                           class="<?php echo e($inputClass); ?> font-mono uppercase <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                    <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2 cursor-pointer pb-2.5">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               <?php if(old('is_active', $department->is_active ?? true)): echo 'checked'; endif; ?>
                               class="h-4 w-4 rounded border-gray-300" style="color: <?php echo e($themeColor); ?>;">
                        <span class="text-sm font-semibold text-gray-800">القسم نشط</span>
                    </label>
                </div>
                <div class="sm:col-span-2">
                    <label for="description" class="<?php echo e($labelClass); ?>">الوصف</label>
                    <textarea name="description" id="description" rows="3"
                              class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;"><?php echo e(old('description', $department->description)); ?></textarea>
                    <?php $__errorArgs = ['description'];
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
            <h2 class="font-bold text-lg text-gray-900">الهيكل التنظيمي</h2>
            <p class="text-sm text-gray-500 mt-0.5">القسم الرئيسي ومدير القسم</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="parent_id" class="<?php echo e($labelClass); ?>">القسم الرئيسي</label>
                    <select name="parent_id" id="parent_id" class="<?php echo e($inputClass); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                        <option value="">قسم رئيسي مستقل</option>
                        <?php $__currentLoopData = $parentDepartments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $parent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($parent->id); ?>" <?php if(old('parent_id', $department->parent_id ?? null) == $parent->id): echo 'selected'; endif; ?>>
                                <?php echo e($parent->name); ?> (<?php echo e($parent->code); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">مثال: «تطوير الويب» تحت «قسم التطوير»</p>
                    <?php $__errorArgs = ['parent_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="manager_id" class="<?php echo e($labelClass); ?>">مدير القسم</label>
                    <select name="manager_id" id="manager_id" class="<?php echo e($inputClass); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                        <option value="">بدون مدير محدد</option>
                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($employee->id); ?>" <?php if(old('manager_id', $department->manager_id) == $employee->id): echo 'selected'; endif; ?>>
                                <?php echo e($employee->first_name); ?> <?php echo e($employee->last_name); ?> — <?php echo e($employee->position); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['manager_id'];
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
            <h2 class="font-bold text-lg text-gray-900">ملف الموظف الافتراضي</h2>
            <p class="text-sm text-gray-500 mt-0.5">يُطبَّق تلقائياً عند إنشاء موظف في هذا القسم</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="default_role" class="<?php echo e($labelClass); ?>">الدور / الصلاحيات</label>
                    <select name="default_role" id="default_role" class="<?php echo e($inputClass); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                        <option value="">يُحدد يدوياً</option>
                        <?php $__currentLoopData = \App\Services\DepartmentProfileService::assignableRoles(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($role); ?>" <?php if(old('default_role', $department->default_role ?? null) === $role): echo 'selected'; endif; ?>>
                                <?php echo e((\App\Services\DepartmentProfileService::roleLabels())[$role] ?? $role); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label for="default_position" class="<?php echo e($labelClass); ?>">المسمى الافتراضي</label>
                    <input type="text" name="default_position" id="default_position"
                           value="<?php echo e(old('default_position', $department->default_position ?? '')); ?>"
                           class="<?php echo e($inputClass); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;" placeholder="مثال: مبرمج ويب">
                </div>
                <div>
                    <label for="kpi_profile" class="<?php echo e($labelClass); ?>">قالب KPI</label>
                    <select name="kpi_profile" id="kpi_profile" class="<?php echo e($inputClass); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                        <option value="">افتراضي</option>
                        <?php $__currentLoopData = \App\Services\DepartmentProfileService::kpiProfileLabels(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php if(old('kpi_profile', $department->kpi_profile ?? null) === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label for="career_track" class="<?php echo e($labelClass); ?>">مسار الترقية</label>
                    <input type="text" name="career_track" id="career_track"
                           value="<?php echo e(old('career_track', $department->career_track ?? '')); ?>"
                           class="<?php echo e($inputClass); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;" placeholder="technical, sales, hr...">
                </div>
            </div>
        </div>
    </div>

    
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">القائمة الجانبية للموظفين</h2>
            <p class="text-sm text-gray-500 mt-0.5">ما يراه موظفو هذا القسم في النظام</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 p-4 border border-gray-200 rounded-xl bg-gray-50/80">
                <?php $__currentLoopData = \App\Models\Department::SIDEBAR_MODULES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer rounded-lg px-2 py-1.5 hover:bg-white">
                        <input type="checkbox" name="sidebar_modules[]" value="<?php echo e($key); ?>"
                               <?php if(in_array($key, (array) $selectedModules, true)): echo 'checked'; endif; ?>
                               class="h-4 w-4 rounded border-gray-300" style="color: <?php echo e($themeColor); ?>;">
                        <span><?php echo e($label); ?></span>
                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <p class="text-xs text-gray-500 mt-3">اترك الكل غير محدد لوراثة الإعدادات من القسم الرئيسي.</p>
            <?php $__errorArgs = ['sidebar_modules'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>

    
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">بيانات التواصل</h2>
            <p class="text-sm text-gray-500 mt-0.5">اختياري</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="location" class="<?php echo e($labelClass); ?>">الموقع</label>
                    <input type="text" name="location" id="location" value="<?php echo e(old('location', $department->location)); ?>"
                           class="<?php echo e($inputClass); ?> <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="--tw-ring-color: <?php echo e($themeColor); ?>;">
                    <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="phone" class="<?php echo e($labelClass); ?>">الهاتف</label>
                    <input type="tel" name="phone" id="phone" value="<?php echo e(old('phone', $department->phone)); ?>" dir="ltr"
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
                    <label for="email" class="<?php echo e($labelClass); ?>">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email" value="<?php echo e(old('email', $department->email)); ?>" dir="ltr"
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
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
        <a href="<?php echo e($cancelUrl); ?>" class="inline-flex items-center justify-center gap-2 border border-gray-300 bg-white px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">إلغاء</a>
        <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm hover:opacity-95"
                style="background: <?php echo e($themeColor); ?>;"><?php echo e($submitLabel); ?></button>
    </div>
</form>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\departments\partials\form.blade.php ENDPATH**/ ?>