<?php

    $isEdit = isset($project);

    $statusLabels = [

        'planning' => 'تخطيط',

        'in_progress' => 'قيد التنفيذ',

        'on_hold' => 'معلق',

        'completed' => 'مكتمل',

        'cancelled' => 'ملغي',

    ];

    $priorityLabels = [

        'low' => 'منخفضة',

        'medium' => 'متوسطة',

        'high' => 'عالية',

        'urgent' => 'عاجلة',

    ];

    $typeLabels = [

        'design' => 'تصميم',

        'marketing' => 'تسويق',

        'development' => 'تطوير',

        'maintenance' => 'صيانة',

    ];

    $val = fn ($field, $default = '') => old($field, $isEdit ? ($project->{$field} ?? $default) : $default);

    $dateVal = fn ($field) => old($field, $isEdit && $project->{$field} ? $project->{$field}->format('Y-m-d') : '');

    $selectedDepartmentId = $val('department_id');

?>



<form method="POST" action="<?php echo e($action); ?>" class="space-y-6" id="project-form">

    <?php echo csrf_field(); ?>

    <?php if(($method ?? 'POST') !== 'POST'): ?>

        <?php echo method_field($method); ?>

    <?php endif; ?>



    <?php if(request('type') && !$isEdit): ?>

        <input type="hidden" name="project_type" value="<?php echo e(request('type')); ?>">

    <?php endif; ?>



    <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">

            <h2 class="font-bold text-lg text-gray-900">معلومات المشروع</h2>

            <p class="text-sm text-gray-500 mt-0.5">البيانات الأساسية والعميل والقسم المنفّذ</p>

        </div>

        <div class="p-6 space-y-5">

            <div>

                <label class="text-xs font-bold text-gray-600 block mb-1.5">اسم المشروع *</label>

                <input type="text" name="name" value="<?php echo e($val('name')); ?>" required

                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"

                       placeholder="أدخل اسم المشروع">

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

                <label class="text-xs font-bold text-gray-600 block mb-1.5">وصف المشروع *</label>

                <textarea name="description" rows="4" required

                          class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"

                          placeholder="وصف واضح لنطاق المشروع والأهداف..."><?php echo e($val('description')); ?></textarea>

                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            </div>



            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>

                    <label class="text-xs font-bold text-gray-600 block mb-1.5">العميل *</label>

                    <select name="client_id" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                        <option value="" disabled <?php if(!$val('client_id')): echo 'selected'; endif; ?>>اختر العميل...</option>

                        <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <option value="<?php echo e($client->id); ?>" <?php if($val('client_id') == $client->id): echo 'selected'; endif; ?>>

                                <?php echo e($client->name); ?><?php if($client->company_name): ?> — <?php echo e($client->company_name); ?><?php endif; ?>

                            </option>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>

                    <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                </div>

                <div>

                    <label class="text-xs font-bold text-gray-600 block mb-1.5">القسم المنفّذ *</label>

                    <select name="department_id" id="department_id" required

                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['department_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                        <option value="" disabled <?php if(!$selectedDepartmentId): echo 'selected'; endif; ?>>اختر القسم...</option>

                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <option value="<?php echo e($department->id); ?>" <?php if($selectedDepartmentId == $department->id): echo 'selected'; endif; ?>><?php echo e($department->name); ?></option>

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

            </div>



            <div id="department-preview" class="hidden rounded-xl border border-indigo-100 bg-indigo-50/70 p-4 text-sm text-indigo-900">

                <p class="font-bold mb-2">بعد الحفظ — مسار الإسناد</p>

                <ol class="space-y-1.5 list-decimal list-inside">

                    <li>يصل المشروع إلى <strong id="dept-manager-name">رئيس القسم</strong></li>

                    <li>رئيس القسم يعيّن <strong>قائد الفريق (Team Leader)</strong></li>

                    <li>قائد الفريق يقود فريقاً من <strong id="dept-staff-count">0</strong> موظف في القسم</li>

                </ol>

            </div>



            <?php if($isEdit && $project->projectManager): ?>

            <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4 text-sm">

                <p class="font-bold text-emerald-900 mb-2">الفريق الحالي (يُدار من رئيس القسم)</p>

                <p class="text-emerald-800">قائد الفريق: <strong><?php echo e($project->projectManager->name); ?></strong></p>

                <?php if($project->teamMembers->isNotEmpty()): ?>

                    <p class="text-emerald-800 mt-1">الأعضاء: <?php echo e($project->teamMembers->pluck('name')->join('، ')); ?></p>

                <?php endif; ?>

                <p class="text-xs text-emerald-700 mt-2">لتعديل الفريق، رئيس القسم يستخدم لوحة «مدير القسم».</p>

            </div>

            <?php elseif($isEdit && $project->department_id): ?>

            <div class="rounded-xl border border-amber-100 bg-amber-50 p-4 text-sm text-amber-900">

                <strong>بانتظار الإسناد:</strong> رئيس قسم «<?php echo e($project->department?->name); ?>» لم يعيّن قائد الفريق والفريق بعد.

            </div>

            <?php endif; ?>



            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>

                    <label class="text-xs font-bold text-gray-600 block mb-1.5">الحالة *</label>

                    <select name="status" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">

                        <?php $__currentLoopData = $statusLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <option value="<?php echo e($value); ?>" <?php if($val('status', 'planning') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>

                </div>

                <div>

                    <label class="text-xs font-bold text-gray-600 block mb-1.5">الأولوية *</label>

                    <select name="priority" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">

                        <?php $__currentLoopData = $priorityLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <option value="<?php echo e($value); ?>" <?php if($val('priority', 'medium') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    </select>

                </div>

            </div>



            <?php if(!request('type') || $isEdit): ?>

            <div>

                <label class="text-xs font-bold text-gray-600 block mb-1.5">نوع المشروع</label>

                <select name="project_type" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">

                    <option value="" <?php if(!$val('project_type')): echo 'selected'; endif; ?>>— عام —</option>

                    <?php $__currentLoopData = $typeLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                        <option value="<?php echo e($value); ?>" <?php if($val('project_type') === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </select>

            </div>

            <?php endif; ?>

        </div>

    </div>



    <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">

            <h2 class="font-bold text-lg text-gray-900">الجدول الزمني والميزانية</h2>

        </div>

        <div class="p-6 space-y-5">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>

                    <label class="text-xs font-bold text-gray-600 block mb-1.5">تاريخ البداية *</label>

                    <input type="date" name="start_date" value="<?php echo e($dateVal('start_date')); ?>" required

                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                    <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                </div>

                <div>

                    <label class="text-xs font-bold text-gray-600 block mb-1.5">تاريخ الانتهاء المتوقع *</label>

                    <input type="date" name="end_date" value="<?php echo e($dateVal('end_date')); ?>" required

                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">

                    <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-red-600 mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                </div>

            </div>

            <div>

                <label class="text-xs font-bold text-gray-600 block mb-1.5">الميزانية (ج.م) *</label>

                <input type="number" name="budget" value="<?php echo e($val('budget')); ?>" step="0.01" min="0" required

                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['budget'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"

                       placeholder="0.00">

                <?php $__errorArgs = ['budget'];
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



    <div class="flex flex-wrap gap-3">

        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-white font-bold shadow-md hover:opacity-95"

                style="background: linear-gradient(135deg, <?php echo e($themeColor); ?> 0%, <?php echo e($themeColor); ?>dd 100%);">

            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>

            <?php echo e($submitLabel); ?>


        </button>

        <a href="<?php echo e($cancelUrl); ?>" class="inline-flex items-center px-6 py-2.5 rounded-xl border border-gray-300 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50">إلغاء</a>

    </div>

</form>



<?php $__env->startPush('scripts'); ?>

<script>

(function () {

    const select = document.getElementById('department_id');

    const preview = document.getElementById('department-preview');

    const managerEl = document.getElementById('dept-manager-name');

    const staffEl = document.getElementById('dept-staff-count');

    if (!select || !preview) return;



    const baseUrl = <?php echo json_encode(url('/projects/department-staff'), 15, 512) ?>;



    async function loadDepartment(id) {

        if (!id) {

            preview.classList.add('hidden');

            return;

        }

        try {

            const res = await fetch(`${baseUrl}/${id}`, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });

            if (!res.ok) throw new Error('failed');

            const data = await res.json();

            managerEl.textContent = data.manager?.name ?? 'رئيس القسم (غير معيّن)';

            staffEl.textContent = data.employees_count ?? 0;

            preview.classList.remove('hidden');

        } catch (e) {

            preview.classList.add('hidden');

        }

    }



    select.addEventListener('change', () => loadDepartment(select.value));

    if (select.value) loadDepartment(select.value);

})();

</script>

<?php $__env->stopPush(); ?>

<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\projects\partials\form.blade.php ENDPATH**/ ?>