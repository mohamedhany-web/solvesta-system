<?php $__env->startSection('page-title', 'إدارة الأجازات'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">إدارة الأجازات</h1>
                <p class="text-sm sm:text-base text-gray-600">إدارة طلبات الأجازات والغياب</p>
            </div>
            <?php if($employee): ?>
            <button id="newLeaveBtn" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-200 flex items-center shadow-sm">
                <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                طلب أجازة جديد
            </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Pending Leaves -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">طلبات معلقة</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['pending_leaves']); ?></p>
                    <p class="text-xs text-orange-600 mt-1">تنتظر الموافقة</p>
                </div>
                <div class="p-4 bg-orange-50 rounded-xl">
                    <svg class="w-8 h-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Approved Leaves -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">أجازات موافق عليها</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['approved_leaves']); ?></p>
                    <p class="text-xs text-green-600 mt-1">هذا الشهر</p>
                </div>
                <div class="p-4 bg-green-50 rounded-xl">
                    <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Rejected Leaves -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">طلبات مرفوضة</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['rejected_leaves']); ?></p>
                    <p class="text-xs text-red-600 mt-1">هذا الشهر</p>
                </div>
                <div class="p-4 bg-red-50 rounded-xl">
                    <svg class="w-8 h-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Leave Days -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">إجمالي أيام الأجازة</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($stats['total_leave_days']); ?></p>
                    <p class="text-xs text-blue-600 mt-1">هذا العام</p>
                </div>
                <div class="p-4 bg-blue-50 rounded-xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Requests Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden w-full">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">طلبات الأجازات</h3>
                <div class="flex items-center gap-2">
                    <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option>جميع الحالات</option>
                        <option value="pending">معلق</option>
                        <option value="approved">موافق عليه</option>
                        <option value="rejected">مرفوض</option>
                    </select>
                    <?php if(auth()->user()->hasRole(['admin', 'hr', 'super_admin']) && $employees->count() > 0): ?>
                    <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option>جميع الموظفين</option>
                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($emp->id); ?>"><?php echo e($emp->first_name); ?> <?php echo e($emp->last_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto w-full">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">الموظف</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">نوع الأجازة</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">تاريخ البداية</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">تاريخ النهاية</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">عدد الأيام</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">الحالة</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $leaves; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $leave): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 w-1/6">
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center ml-3">
                                    <span class="text-sm font-medium text-white">
                                        <?php echo e(substr($leave->employee->first_name ?? 'م', 0, 1)); ?>

                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo e($leave->employee->first_name); ?> <?php echo e($leave->employee->last_name); ?>

                                    </div>
                                    <div class="text-sm text-gray-500"><?php echo e($leave->employee->position); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 w-1/6">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                <?php if($leave->leave_type == 'annual'): ?> bg-green-100 text-green-800
                                <?php elseif($leave->leave_type == 'sick'): ?> bg-blue-100 text-blue-800
                                <?php elseif($leave->leave_type == 'emergency'): ?> bg-red-100 text-red-800
                                <?php elseif($leave->leave_type == 'maternity'): ?> bg-pink-100 text-pink-800
                                <?php elseif($leave->leave_type == 'paternity'): ?> bg-purple-100 text-purple-800
                                <?php elseif($leave->leave_type == 'unpaid'): ?> bg-gray-100 text-gray-800
                                <?php elseif($leave->leave_type == 'compensatory'): ?> bg-yellow-100 text-yellow-800
                                <?php else: ?> bg-gray-100 text-gray-800
                                <?php endif; ?>">
                                <?php echo e($leave->leave_type_name); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 w-1/6 text-sm text-gray-900">
                            <?php echo e($leave->start_date->format('Y/m/d')); ?>

                        </td>
                        <td class="px-6 py-4 w-1/6 text-sm text-gray-900">
                            <?php echo e($leave->end_date->format('Y/m/d')); ?>

                        </td>
                        <td class="px-6 py-4 w-1/6 text-sm text-gray-900">
                            <?php echo e($leave->total_days); ?> أيام
                        </td>
                        <td class="px-6 py-4 w-1/6">
                            <?php if($leave->status == 'pending'): ?>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    معلق
                                </span>
                                <?php if(auth()->user()->hasRole(['admin', 'hr', 'super_admin'])): ?>
                                <div class="flex items-center gap-1">
                                    <button onclick="approveLeave(<?php echo e($leave->id); ?>)" class="p-1 text-green-600 hover:bg-green-50 rounded">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                    <button onclick="rejectLeave(<?php echo e($leave->id); ?>)" class="p-1 text-red-600 hover:bg-red-50 rounded">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php elseif($leave->status == 'approved'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                موافق عليه
                            </span>
                            <?php elseif($leave->status == 'rejected'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                مرفوض
                            </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            لا توجد طلبات أجازات للعرض
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- New Leave Modal -->
<div id="newLeaveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">طلب أجازة جديد</h3>
                <button onclick="closeNewLeaveModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form id="newLeaveForm" class="space-y-4">
                <?php echo csrf_field(); ?>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نوع الأجازة</label>
                    <select name="leave_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">اختر نوع الأجازة</option>
                        <option value="annual">إجازة سنوية</option>
                        <option value="sick">إجازة مرضية</option>
                        <option value="emergency">إجازة طارئة</option>
                        <option value="maternity">إجازة أمومة</option>
                        <option value="paternity">إجازة أبوة</option>
                        <option value="unpaid">إجازة غير مدفوعة</option>
                        <option value="compensatory">إجازة تعويضية</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ البداية</label>
                    <input type="date" name="start_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ النهاية</label>
                    <input type="date" name="end_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">السبب</label>
                    <textarea name="reason" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="اذكر سبب طلب الإجازة"></textarea>
                </div>
                
                <div class="flex items-center justify-end gap-3 pt-4">
                    <button type="button" onclick="closeNewLeaveModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        إلغاء
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        تقديم الطلب
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Leave Modal -->
<div id="rejectLeaveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">رفض طلب الإجازة</h3>
                <button onclick="closeRejectLeaveModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form id="rejectLeaveForm" class="space-y-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="leave_id" id="rejectLeaveId">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">سبب الرفض</label>
                    <textarea name="rejection_reason" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="اذكر سبب رفض طلب الإجازة"></textarea>
                </div>
                
                <div class="flex items-center justify-end gap-3 pt-4">
                    <button type="button" onclick="closeRejectLeaveModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        إلغاء
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        رفض الطلب
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if($employee): ?>
<script>
let isSubmittingLeave = false; // Flag to prevent double submission

document.addEventListener('DOMContentLoaded', function() {
    // New Leave Button
    const newLeaveBtn = document.getElementById('newLeaveBtn');
    if (newLeaveBtn) {
        newLeaveBtn.addEventListener('click', function() {
            document.getElementById('newLeaveModal').classList.remove('hidden');
        });
    }
    
    // New Leave Form - Use once option to prevent multiple listeners
    const newLeaveForm = document.getElementById('newLeaveForm');
    if (newLeaveForm) {
        // Remove any existing listeners by cloning
        const oldForm = newLeaveForm;
        const newForm = oldForm.cloneNode(true);
        oldForm.parentNode.replaceChild(newForm, oldForm);
        
        // Get the new form reference
        const form = document.getElementById('newLeaveForm');
        
        // Add single event listener - check flag to prevent double submission
        form.addEventListener('submit', function submitHandler(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            if (!isSubmittingLeave) {
                submitLeaveRequest();
            }
            return false;
        });
        
        // Also prevent button double click
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.addEventListener('click', function(e) {
                if (isSubmittingLeave) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    return false;
                }
            }, { once: false });
        }
    }
    
    // Reject Leave Form
    const rejectLeaveForm = document.getElementById('rejectLeaveForm');
    if (rejectLeaveForm) {
        rejectLeaveForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            submitRejectLeave();
        });
    }
});

function closeNewLeaveModal() {
    isSubmittingLeave = false; // Reset flag
    document.getElementById('newLeaveModal').classList.add('hidden');
    document.getElementById('newLeaveForm').reset();
}

function closeRejectLeaveModal() {
    document.getElementById('rejectLeaveModal').classList.add('hidden');
    document.getElementById('rejectLeaveForm').reset();
}

function submitLeaveRequest() {
    // Prevent double submission
    if (isSubmittingLeave) {
        return false;
    }
    
    isSubmittingLeave = true;
    const form = document.getElementById('newLeaveForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Disable submit button
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'جاري الإرسال...';
    }
    
    const formData = new FormData(form);
    
    fetch('<?php echo e(route("leaves.store")); ?>', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeNewLeaveModal();
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            isSubmittingLeave = false; // Reset on error
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'تقديم الطلب';
            }
            showNotification(data.error || 'حدث خطأ في تقديم طلب الإجازة', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        isSubmittingLeave = false; // Reset on error
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = 'تقديم الطلب';
        }
        showNotification('حدث خطأ في تقديم طلب الإجازة', 'error');
    });
    
    return false;
}

function approveLeave(leaveId) {
    if (!confirm('هل أنت متأكد من الموافقة على هذا الطلب؟')) {
        return;
    }
    
    fetch(`/leaves/${leaveId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification(data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('حدث خطأ في الموافقة على الطلب', 'error');
    });
}

function rejectLeave(leaveId) {
    document.getElementById('rejectLeaveId').value = leaveId;
    document.getElementById('rejectLeaveModal').classList.remove('hidden');
}

function submitRejectLeave() {
    const form = document.getElementById('rejectLeaveForm');
    const formData = new FormData(form);
    const leaveId = formData.get('leave_id');
    
    fetch(`/leaves/${leaveId}/reject`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            closeRejectLeaveModal();
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            showNotification(data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('حدث خطأ في رفض الطلب', 'error');
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\leaves\index.blade.php ENDPATH**/ ?>