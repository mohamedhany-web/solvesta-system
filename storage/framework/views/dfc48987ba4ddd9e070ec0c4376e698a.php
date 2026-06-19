

<?php $__env->startSection('page-title', 'إنشاء قيد محاسبي جديد'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-6xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">إنشاء قيد محاسبي جديد</h1>
                <p class="text-gray-600">إضافة قيد محاسبي جديد للنظام</p>
            </div>
            <a href="<?php echo e(route('accounting.journal-entries')); ?>" class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-200 flex items-center shadow-sm">
                <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                العودة للقائمة
            </a>
        </div>
    </div>

    <form action="<?php echo e(route('accounting.journal-entries.store')); ?>" method="POST" id="journalEntryForm">
        <?php echo csrf_field(); ?>
        
        <!-- Entry Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">بيانات القيد</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">تاريخ القيد</label>
                    <input type="date" name="date" value="<?php echo e(old('date', date('Y-m-d'))); ?>" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">رقم المرجع</label>
                    <input type="text" name="reference" value="<?php echo e(old('reference')); ?>" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="مثال: JE-2024-001">
                    <?php $__errorArgs = ['reference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">الوصف العام</label>
                    <input type="text" name="description" value="<?php echo e(old('description')); ?>" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="وصف القيد المحاسبي">
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Entry Lines -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">بنود القيد</h3>
                <button type="button" onclick="addEntryLine()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center">
                    <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    إضافة بند
                </button>
            </div>
            
            <div id="entryLines">
                <!-- Entry lines will be added here dynamically -->
            </div>
            
            <!-- Balance Summary -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <div>
                            <span class="text-sm font-medium text-gray-600">إجمالي المدين:</span>
                            <span id="totalDebit" class="text-lg font-bold text-gray-900 mr-2">0.00 ج.م</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">إجمالي الدائن:</span>
                            <span id="totalCredit" class="text-lg font-bold text-gray-900 mr-2">0.00 ج.م</span>
                        </div>
                    </div>
                    <div id="balanceStatus" class="text-sm font-medium">
                        <!-- Balance status will be shown here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="<?php echo e(route('accounting.journal-entries')); ?>" class="px-6 py-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-colors duration-200">
                إلغاء
            </a>
            <button type="submit" class="px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-colors duration-200">
                حفظ القيد
            </button>
        </div>
    </form>
</div>

<!-- Account Selection Modal -->
<div id="accountModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">اختيار الحساب</h3>
                <button onclick="closeAccountModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="space-y-2 max-h-96 overflow-y-auto">
                <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $typeAccounts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border-b border-gray-200 pb-2 mb-2">
                    <h4 class="font-semibold text-gray-800 mb-2"><?php echo e($type); ?></h4>
                    <?php $__currentLoopData = $typeAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center p-2 hover:bg-gray-50 cursor-pointer" onclick="selectAccount(<?php echo e($account->id); ?>, '<?php echo e($account->name); ?>', '<?php echo e($account->code); ?>')">
                        <span class="text-sm font-medium text-gray-700"><?php echo e($account->code); ?> - <?php echo e($account->name); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>

<script>
let entryLineCount = 0;
let currentLineIndex = 0;

// Initialize with two lines
document.addEventListener('DOMContentLoaded', function() {
    addEntryLine();
    addEntryLine();
});

function addEntryLine() {
    const container = document.getElementById('entryLines');
    const lineIndex = entryLineCount++;
    
    const lineDiv = document.createElement('div');
    lineDiv.className = 'entry-line border border-gray-200 rounded-lg p-4 mb-4';
    lineDiv.id = `line-${lineIndex}`;
    
    lineDiv.innerHTML = `
        <div class="flex items-center justify-between mb-3">
            <h4 class="font-medium text-gray-900">البند ${lineIndex + 1}</h4>
            <button type="button" onclick="removeEntryLine(${lineIndex})" class="text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الحساب</label>
                <button type="button" onclick="openAccountModal(${lineIndex})" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-right bg-white hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <span class="account-display text-gray-500">اختر الحساب</span>
                    <input type="hidden" name="lines[${lineIndex}][account_id]" class="account-id">
                </button>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الوصف</label>
                <input type="text" name="lines[${lineIndex}][description]" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">مدين</label>
                <input type="number" name="lines[${lineIndex}][debit]" step="0.01" min="0"
                       class="debit-amount w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       onchange="updateBalances()">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">دائن</label>
                <input type="number" name="lines[${lineIndex}][credit]" step="0.01" min="0"
                       class="credit-amount w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       onchange="updateBalances()">
            </div>
        </div>
    `;
    
    container.appendChild(lineDiv);
}

function removeEntryLine(index) {
    const line = document.getElementById(`line-${index}`);
    if (line) {
        line.remove();
        updateBalances();
    }
}

function openAccountModal(lineIndex) {
    currentLineIndex = lineIndex;
    document.getElementById('accountModal').classList.remove('hidden');
}

function closeAccountModal() {
    document.getElementById('accountModal').classList.add('hidden');
}

function selectAccount(accountId, accountName, accountCode) {
    const line = document.getElementById(`line-${currentLineIndex}`);
    const display = line.querySelector('.account-display');
    const input = line.querySelector('.account-id');
    
    display.textContent = `${accountCode} - ${accountName}`;
    display.className = 'account-display text-gray-900';
    input.value = accountId;
    
    closeAccountModal();
}

function updateBalances() {
    let totalDebit = 0;
    let totalCredit = 0;
    
    document.querySelectorAll('.entry-line').forEach(line => {
        const debitInput = line.querySelector('.debit-amount');
        const creditInput = line.querySelector('.credit-amount');
        
        if (debitInput && creditInput) {
            const debit = parseFloat(debitInput.value) || 0;
            const credit = parseFloat(creditInput.value) || 0;
            
            totalDebit += debit;
            totalCredit += credit;
            
            // Clear the other field when one is filled
            if (debit > 0) {
                creditInput.value = '';
            } else if (credit > 0) {
                debitInput.value = '';
            }
        }
    });
    
    document.getElementById('totalDebit').textContent = totalDebit.toFixed(2) + ' ج.م';
    document.getElementById('totalCredit').textContent = totalCredit.toFixed(2) + ' ج.م';
    
    const difference = Math.abs(totalDebit - totalCredit);
    const balanceStatus = document.getElementById('balanceStatus');
    
    if (difference < 0.01) {
        balanceStatus.innerHTML = '<span class="text-green-600">✓ متوازن</span>';
        balanceStatus.className = 'text-sm font-medium text-green-600';
    } else {
        balanceStatus.innerHTML = `<span class="text-red-600">غير متوازن (الفرق: ${difference.toFixed(2)} ج.م)</span>`;
        balanceStatus.className = 'text-sm font-medium text-red-600';
    }
}

// Form validation
document.getElementById('journalEntryForm').addEventListener('submit', function(e) {
    const totalDebit = parseFloat(document.getElementById('totalDebit').textContent.replace(/[^\d.-]/g, ''));
    const totalCredit = parseFloat(document.getElementById('totalCredit').textContent.replace(/[^\d.-]/g, ''));
    
    if (Math.abs(totalDebit - totalCredit) > 0.01) {
        e.preventDefault();
        alert('القيد غير متوازن. يجب أن يكون مجموع المدين مساوياً لمجموع الدائن.');
        return false;
    }
    
    // Check if at least one line has an account and amount
    let hasValidLine = false;
    document.querySelectorAll('.entry-line').forEach(line => {
        const accountId = line.querySelector('.account-id').value;
        const debit = parseFloat(line.querySelector('.debit-amount').value) || 0;
        const credit = parseFloat(line.querySelector('.credit-amount').value) || 0;
        
        if (accountId && (debit > 0 || credit > 0)) {
            hasValidLine = true;
        }
    });
    
    if (!hasValidLine) {
        e.preventDefault();
        alert('يجب إضافة بند واحد على الأقل مع حساب ومبلغ.');
        return false;
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\accounting\journal-entries\create.blade.php ENDPATH**/ ?>