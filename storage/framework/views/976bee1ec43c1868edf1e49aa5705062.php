<?php $__env->startSection('page-title', 'عرض الفاتورة'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6 no-print">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">عرض الفاتورة</h1>
                <p class="text-sm sm:text-base text-gray-600">تفاصيل الفاتورة رقم <?php echo e($invoice->invoice_number); ?></p>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?php echo e(request()->routeIs('financial-invoices.*') ? route('financial-invoices.index') : route('invoices.index')); ?>" class="bg-gray-600 text-white px-5 py-2.5 sm:px-6 sm:py-3 rounded-lg hover:bg-gray-700 transition-all duration-200 flex items-center justify-center shadow-sm text-sm sm:text-base">
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    العودة للقائمة
                </a>
                <?php if($invoice->status !== 'paid'): ?>
                <button onclick="markAsPaid(<?php echo e($invoice->id); ?>, '<?php echo e(request()->routeIs("financial-invoices.*") ? "financial-invoices" : "invoices"); ?>')" class="bg-green-600 text-white px-5 py-2.5 sm:px-6 sm:py-3 rounded-lg hover:bg-green-700 transition-all duration-200 flex items-center justify-center shadow-sm text-sm sm:text-base">
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    تحديد كمدفوع
                </button>
                <?php endif; ?>
                <button onclick="window.print()" class="bg-blue-600 text-white px-5 py-2.5 sm:px-6 sm:py-3 rounded-lg hover:bg-blue-700 transition-all duration-200 flex items-center justify-center shadow-sm text-sm sm:text-base">
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    طباعة
                </button>
            </div>
        </div>
    </div>

    <!-- Invoice Document -->
    <div class="bg-white overflow-hidden print:shadow-none print:border-0 print:bg-white" style="max-width: 210mm; margin: 0 auto; box-shadow: 0 0 0 1px rgba(0,0,0,0.1);">

        <!-- Professional Invoice Header -->
        <div class="bg-gray-900 text-white py-6 px-10">
            <div class="flex justify-between items-start">
                <!-- Left: Logo and Company -->
                <div class="flex items-start gap-5">
                    <?php
                        $logoPath = \App\Helpers\SettingsHelper::getLogoPath();
                        $companyName = \App\Helpers\SettingsHelper::getCompanyName();
                        $logoExists = $logoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath);
                    ?>
                    <?php if($logoExists): ?>
                        <div class="flex-shrink-0 bg-white p-2 rounded">
                            <img src="<?php echo e(asset('storage/' . $logoPath)); ?>" alt="Logo" class="h-16 w-auto object-contain">
                        </div>
                    <?php else: ?>
                        <div class="h-16 w-16 bg-white p-2 rounded flex items-center justify-center">
                            <svg class="h-10 w-10 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    <?php endif; ?>
                    <div class="flex-1">
                        <h2 class="text-xl font-bold mb-2"><?php echo e($companyName); ?></h2>
                        <div class="flex items-center gap-4 text-sm text-gray-300">
                            <span>فاتورة مالية</span>
                            <span>|</span>
                            <span>Invoice #<?php echo e($invoice->invoice_number); ?></span>
                        </div>
                    </div>
                </div>
                <!-- Right: Total Amount -->
                <div class="text-left bg-white text-gray-900 px-6 py-4 rounded-lg border-2 border-gray-700">
                    <div class="text-xs text-gray-600 mb-1 font-semibold uppercase tracking-wide">المبلغ المستحق</div>
                    <div class="text-3xl font-bold"><?php echo e(number_format($invoice->total_amount, 2)); ?></div>
                    <div class="text-sm text-gray-600 mt-1">ج.م</div>
                </div>
            </div>
        </div>

        <!-- Invoice Info Section -->
        <div class="px-10 py-6 bg-gray-50">
            <div class="grid grid-cols-3 gap-8 mb-6">
                <!-- Company Info -->
                <div>
                    <div class="bg-white border-r-4 border-gray-900 p-4 rounded">
                        <h3 class="text-xs font-bold text-gray-900 mb-3 uppercase tracking-wide">من (From)</h3>
                        <div class="space-y-1.5">
                            <h4 class="font-bold text-gray-900 text-sm"><?php echo e(\App\Helpers\SettingsHelper::getCompanyName()); ?></h4>
                            <p class="text-xs text-gray-600 hide-address-print"><?php echo e(\App\Helpers\SettingsHelper::getCompanyAddress()); ?></p>
                            <p class="text-xs text-gray-700">
                                <span class="font-semibold">الهاتف:</span> <?php echo e(\App\Helpers\SettingsHelper::getCompanyPhone()); ?>

                            </p>
                            <p class="text-xs text-gray-700">
                                <span class="font-semibold">البريد:</span> <?php echo e(\App\Helpers\SettingsHelper::getCompanyEmail()); ?>

                            </p>
                        </div>
                    </div>
                </div>

                <!-- Client Info -->
                <div>
                    <div class="bg-white border-r-4 border-gray-900 p-4 rounded">
                        <h3 class="text-xs font-bold text-gray-900 mb-3 uppercase tracking-wide">إلى (To)</h3>
                        <div class="space-y-1.5">
                            <h4 class="font-bold text-gray-900 text-sm"><?php echo e(optional($invoice->client)->name ?? 'غير محدد'); ?></h4>
                            <p class="text-xs text-gray-600 hide-address-print"><?php echo e(optional($invoice->client)->address ?? ''); ?></p>
                            <p class="text-xs text-gray-700"><?php echo e(optional($invoice->client)->email ?? ''); ?></p>
                            <p class="text-xs text-gray-700"><?php echo e(optional($invoice->client)->phone ?? ''); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Invoice Details -->
                <div>
                    <div class="bg-white border-r-4 border-gray-900 p-4 rounded">
                        <h3 class="text-xs font-bold text-gray-900 mb-3 uppercase tracking-wide">تفاصيل الفاتورة</h3>
                        <div class="space-y-2 text-xs">
                            <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                <span class="text-gray-600">الرقم:</span>
                                <span class="font-bold text-gray-900"><?php echo e($invoice->invoice_number); ?></span>
                            </div>
                            <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                <span class="text-gray-600">تاريخ الإصدار:</span>
                                <span class="font-semibold text-gray-900">
                                    <?php if($invoice->invoice_date): ?>
                                        <?php echo e($invoice->invoice_date instanceof \Carbon\Carbon ? $invoice->invoice_date->format('Y/m/d') : \Carbon\Carbon::parse($invoice->invoice_date)->format('Y/m/d')); ?>

                                    <?php elseif($invoice->issue_date): ?>
                                        <?php echo e($invoice->issue_date instanceof \Carbon\Carbon ? $invoice->issue_date->format('Y/m/d') : \Carbon\Carbon::parse($invoice->issue_date)->format('Y/m/d')); ?>

                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                <span class="text-gray-600">تاريخ الاستحقاق:</span>
                                <span class="font-semibold text-gray-900">
                                    <?php if($invoice->due_date): ?>
                                        <?php echo e($invoice->due_date instanceof \Carbon\Carbon ? $invoice->due_date->format('Y/m/d') : \Carbon\Carbon::parse($invoice->due_date)->format('Y/m/d')); ?>

                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-1">
                                <span class="text-gray-600">الحالة:</span>
                                <span class="font-semibold text-gray-900">
                                    <?php if($invoice->status == 'draft'): ?> مسودة
                                    <?php elseif($invoice->status == 'sent'): ?> مرسلة
                                    <?php elseif($invoice->status == 'paid'): ?> مدفوعة
                                    <?php elseif($invoice->status == 'overdue'): ?> متأخرة
                                    <?php else: ?> <?php echo e($invoice->status); ?> <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Project Info -->
        <?php if($invoice->project): ?>
        <div class="px-10 py-3 bg-blue-50 border-t border-gray-200">
            <div class="flex items-center gap-3">
                <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span class="text-xs font-semibold text-gray-900">المشروع:</span>
                <span class="text-xs text-gray-900 font-medium"><?php echo e($invoice->project->name); ?></span>
                <?php if($invoice->project->description): ?>
                <span class="text-xs text-gray-600">- <?php echo e($invoice->project->description); ?></span>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Contract Info (الفواتير العادية فقط - الفواتير المالية لا تحتوي عقد) -->
        <?php if(method_exists($invoice, 'contract') && $invoice->contract): ?>
        <div class="px-10 py-3 bg-purple-50 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-xs font-semibold text-gray-900">العقد المرتبط:</span>
                    <span class="text-xs text-gray-900 font-medium"><?php echo e($invoice->contract->title); ?></span>
                    <span class="text-xs text-gray-600">(<?php echo e($invoice->contract->contract_number); ?>)</span>
                </div>
                <a href="<?php echo e(route('contracts.show', $invoice->contract)); ?>" class="text-xs text-purple-600 hover:text-purple-800 font-medium">
                    عرض العقد →
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Invoice Items -->
        <div class="px-10 py-5">
            <h3 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wide">بنود الفاتورة / Invoice Items</h3>
            <?php
                $itemsArray = [];
                if ($invoice->items && is_array($invoice->items) && count($invoice->items) > 0) {
                    $itemsArray = $invoice->items;
                } elseif (method_exists($invoice, 'getRelation') && $invoice->relationLoaded('items') && $invoice->items->isNotEmpty()) {
                    $itemsArray = $invoice->items->map(fn($i) => [
                        'description' => $i->description ?? $i->item_name ?? '',
                        'quantity' => $i->quantity,
                        'unit_price' => $i->unit_price,
                        'amount' => $i->amount ?? ($i->quantity * $i->unit_price),
                    ])->toArray();
                }
            ?>
            <?php if(count($itemsArray) > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-900 text-white">
                            <th class="text-right py-3 px-4 text-sm font-bold uppercase tracking-wide">الوصف / Description</th>
                            <th class="text-center py-3 px-4 text-sm font-bold uppercase tracking-wide w-20">الكمية / Qty</th>
                            <th class="text-center py-3 px-4 text-sm font-bold uppercase tracking-wide w-32">سعر الوحدة / Unit Price</th>
                            <th class="text-center py-3 px-4 text-sm font-bold uppercase tracking-wide w-32">المجموع / Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $itemsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-b border-gray-200 <?php echo e($index % 2 == 0 ? 'bg-white' : 'bg-gray-50'); ?>">
                            <td class="py-3 px-4 text-sm text-gray-900 font-medium"><?php echo e($item['description'] ?? ''); ?></td>
                            <td class="py-3 px-4 text-center text-sm text-gray-900"><?php echo e($item['quantity'] ?? 0); ?></td>
                            <td class="py-3 px-4 text-center text-sm text-gray-900"><?php echo e(number_format($item['unit_price'] ?? 0, 2)); ?> ج.م</td>
                            <td class="py-3 px-4 text-center text-sm font-bold text-gray-900"><?php echo e(number_format($item['amount'] ?? (($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0)), 2)); ?> ج.م</td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="py-12 text-center border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-sm text-gray-500 font-medium">لم يتم إضافة أي بنود لهذه الفاتورة.</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Invoice Summary -->
        <div class="px-10 py-5 bg-gray-50 border-t-4 border-gray-900">
            <div class="flex justify-end">
                <div class="w-96">
                    <table class="w-full bg-white border border-gray-300 rounded-lg overflow-hidden">
                        <tbody>
                            <tr class="border-b border-gray-200">
                                <td class="py-3 px-5 text-right text-sm text-gray-700 font-semibold">المجموع الفرعي:</td>
                                <td class="py-3 px-5 text-left text-sm font-bold text-gray-900"><?php echo e(number_format($invoice->subtotal ?? $invoice->amount, 2)); ?> ج.م</td>
                            </tr>
                            <?php if($invoice->tax_amount > 0): ?>
                            <tr class="border-b border-gray-200">
                                <td class="py-3 px-5 text-right text-sm text-gray-700 font-semibold">الضريبة (<?php echo e($invoice->tax_rate ?? 0); ?>%):</td>
                                <td class="py-3 px-5 text-left text-sm font-bold text-gray-900"><?php echo e(number_format($invoice->tax_amount, 2)); ?> ج.م</td>
                            </tr>
                            <?php endif; ?>
                            <?php if($invoice->discount_amount > 0): ?>
                            <tr class="border-b border-gray-200">
                                <td class="py-3 px-5 text-right text-sm text-gray-700 font-semibold">الخصم:</td>
                                <td class="py-3 px-5 text-left text-sm font-bold text-gray-900">- <?php echo e(number_format($invoice->discount_amount, 2)); ?> ج.م</td>
                            </tr>
                            <?php endif; ?>
                            <tr class="bg-gray-900 text-white">
                                <td class="py-4 px-5 text-right text-base font-bold">المجموع الكلي:</td>
                                <td class="py-4 px-5 text-left text-xl font-bold"><?php echo e(number_format($invoice->total_amount, 2)); ?> ج.م</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Notes and Footer -->
        <div class="px-10 py-6 border-t-2 border-gray-300">
            <?php
                $financialNotes = \App\Helpers\SettingsHelper::getInvoiceFinancialNotes();
            ?>
            <?php if($invoice->notes || $financialNotes): ?>
            <div class="mb-5">
                <h3 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wide">ملاحظات إضافية / Additional Notes</h3>
                <div class="bg-gray-50 border-r-4 border-gray-700 p-4 rounded">
                    <?php if($invoice->notes): ?>
                        <p class="text-sm text-gray-700 leading-relaxed mb-2"><?php echo e($invoice->notes); ?></p>
                    <?php endif; ?>
                    <?php if($financialNotes): ?>
                        <p class="text-sm text-gray-700 leading-relaxed"><?php echo e($financialNotes); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Payment Terms -->
            <div class="border-t-2 border-gray-300 pt-5">
                <h3 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wide">شروط الدفع / Payment Terms</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-white border border-gray-200 p-4 rounded">
                        <h4 class="text-xs font-bold text-gray-900 mb-3 uppercase">معلومات الدفع</h4>
                        <div class="space-y-2 text-xs text-gray-700">
                            <?php
                                $paymentMethods = \App\Helpers\SettingsHelper::getPaymentMethods();
                                $defaultPeriod = \App\Helpers\SettingsHelper::getDefaultPaymentPeriod();
                                $invoiceDate = $invoice->invoice_date ? ($invoice->invoice_date instanceof \Carbon\Carbon ? $invoice->invoice_date : \Carbon\Carbon::parse($invoice->invoice_date)) : null;
                                $dueDate = $invoice->due_date ? ($invoice->due_date instanceof \Carbon\Carbon ? $invoice->due_date : \Carbon\Carbon::parse($invoice->due_date)) : null;
                                $days = ($invoiceDate && $dueDate) ? $invoiceDate->diffInDays($dueDate) : $defaultPeriod;
                            ?>
                            <p><span class="font-semibold text-gray-900">طريقة الدفع:</span> <?php echo e($paymentMethods ?: 'تحويل بنكي أو شيك'); ?></p>
                            <p><span class="font-semibold text-gray-900">مدة السداد:</span> <?php echo e($days); ?> يوم من تاريخ الإصدار</p>
                        </div>
                    </div>
                    <div class="bg-white border border-gray-200 p-4 rounded">
                        <h4 class="text-xs font-bold text-gray-900 mb-3 uppercase">معلومات البنك</h4>
                        <div class="space-y-2 text-xs text-gray-700">
                            <?php
                                $bankName = \App\Helpers\SettingsHelper::getBankName();
                                $accountNumber = \App\Helpers\SettingsHelper::getBankAccountNumber();
                                $swiftCode = \App\Helpers\SettingsHelper::getBankSwift();
                                $iban = \App\Helpers\SettingsHelper::getBankIban();
                            ?>
                            <?php if($bankName): ?>
                                <p><span class="font-semibold text-gray-900">البنك:</span> <?php echo e($bankName); ?></p>
                            <?php endif; ?>
                            <?php if($accountNumber): ?>
                                <p><span class="font-semibold text-gray-900">رقم الحساب:</span> <?php echo e($accountNumber); ?></p>
                            <?php endif; ?>
                            <?php if($swiftCode): ?>
                                <p><span class="font-semibold text-gray-900">Swift Code:</span> <?php echo e($swiftCode); ?></p>
                            <?php endif; ?>
                            <?php if($iban): ?>
                                <p><span class="font-semibold text-gray-900">IBAN:</span> <?php echo e($iban); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-6 pt-5 border-t-2 border-gray-300 text-center">
                <div class="bg-gray-900 text-white py-4 px-6 rounded-lg">
                    <p class="text-xs font-semibold mb-2">شكراً لاختياركم خدماتنا</p>
                    <div class="flex justify-center items-center gap-4 text-xs text-gray-300">
                        <span>للاستفسارات: <?php echo e(\App\Helpers\SettingsHelper::getCompanyEmail()); ?></span>
                        <span>|</span>
                        <span><?php echo e(\App\Helpers\SettingsHelper::getCompanyPhone()); ?></span>
                    </div>
                    <p class="text-xs text-gray-400 mt-3">© <?php echo e(date('Y')); ?> <?php echo e(\App\Helpers\SettingsHelper::getCompanyName()); ?>. جميع الحقوق محفوظة.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(session('success')): ?>
<script class="no-print">
    document.addEventListener('DOMContentLoaded', function() {
        showNotification('<?php echo e(session('success')); ?>', 'success');
    });
</script>
<?php endif; ?>

<script class="no-print">
function markAsPaid(invoiceId, basePath) {
    basePath = basePath || 'invoices';
    if (confirm('هل أنت متأكد من تحديد هذه الفاتورة كمدفوعة؟')) {
        fetch(`/${basePath}/${invoiceId}/mark-as-paid`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
        })
        .then(response => {
            // التحقق من نوع الرد
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                // إذا كان الرد redirect HTML، نعيد تحميل الصفحة
                window.location.reload();
                return { success: true };
            }
        })
        .then(data => {
            if (data.success) {
                showNotification('تم تحديث حالة الفاتورة إلى مدفوع', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showNotification(data.message || 'حدث خطأ', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('حدث خطأ في الاتصال: ' + error.message, 'error');
        });
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white font-medium transform translate-x-full transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}
</script>
<?php $__env->stopSection(); ?>

<style>
/* إعدادات الطباعة - تصميم بسيط ومنظم */
@media print {
    /* إخفاء كل شيء ما عدا الفاتورة */
    .no-print,
    body > div.flex > div:first-child,
    .sidebar-bg,
    #sidebar,
    #sidebarOverlay,
    .sidebar-overlay,
    header,
    nav,
    .header-container,
    .main-content-mobile > header {
        display: none !important;
    }
    
    /* إعدادات الصفحة */
    @page {
        size: A4 portrait;
        margin: 8mm 10mm;
    }
    
    /* إعادة تعيين كامل */
    * {
        box-shadow: none !important;
        text-shadow: none !important;
    }
    
    body {
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
        font-family: 'Arial', 'Tahoma', sans-serif !important;
        font-size: 10pt !important;
        line-height: 1.5 !important;
        color: #000 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    /* إظهار المحتوى فقط */
    body > div.flex {
        display: block !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    body > div.flex > div:last-child,
    .main-content-mobile,
    .w-full,
    .max-w-7xl {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: visible !important;
    }
    
    /* حاوية الفاتورة */
    div[style*="max-width: 210mm"] {
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
    }
    
    /* الهيدر - تصميم بسيط */
    .bg-gray-900 {
        background: #2c3e50 !important;
        color: #fff !important;
        padding: 10px 15px !important;
        margin: 0 !important;
        page-break-inside: avoid !important;
    }
    
    .bg-gray-900 * {
        color: #fff !important;
    }
    
    .bg-gray-900 .bg-white {
        background: #fff !important;
        color: #000 !important;
        border: 2px solid #2c3e50 !important;
        padding: 8px 12px !important;
    }
    
    .bg-gray-900 .bg-white * {
        color: #000 !important;
    }
    
    /* معلومات الفاتورة */
    .bg-gray-50 {
        background: #f5f5f5 !important;
        padding: 10px 15px !important;
        margin: 0 !important;
        page-break-inside: avoid !important;
    }
    
    /* تقليل المسافات في Grid */
    .grid-cols-3 {
        margin-bottom: 5px !important;
    }
    
    .grid-cols-3 .mb-6 {
        margin-bottom: 5px !important;
    }
    
    /* البطاقات */
    .bg-white.border-r-4 {
        background: #fff !important;
        border: 1px solid #ddd !important;
        border-right: 4px solid #2c3e50 !important;
        padding: 8px !important;
        margin-bottom: 5px !important;
        page-break-inside: avoid !important;
    }
    
    .bg-white.border-r-4 h3 {
        margin-bottom: 5px !important;
        font-size: 10pt !important;
    }
    
    .bg-white.border-r-4 p,
    .bg-white.border-r-4 span {
        font-size: 9pt !important;
        margin-bottom: 2px !important;
    }
    
    /* Grid - بسيط وواضح */
    .grid {
        display: block !important;
        width: 100% !important;
    }
    
    .grid-cols-3 > div {
        display: inline-block !important;
        width: 32% !important;
        vertical-align: top !important;
        margin-right: 1% !important;
        margin-bottom: 10px !important;
    }
    
    .grid-cols-3 > div:last-child {
        margin-right: 0 !important;
    }
    
    .grid-cols-2 > div {
        display: inline-block !important;
        width: 49% !important;
        vertical-align: top !important;
        margin-right: 1% !important;
        margin-bottom: 10px !important;
    }
    
    .grid-cols-2 > div:last-child {
        margin-right: 0 !important;
    }
    
    /* الجداول */
    table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin: 5px 0 !important;
        page-break-inside: avoid !important;
        font-size: 9pt !important;
    }
    
    table thead tr {
        background: #2c3e50 !important;
        color: #fff !important;
    }
    
    table thead th {
        background: #2c3e50 !important;
        color: #fff !important;
        font-weight: bold !important;
        padding: 8px !important;
        border: 1px solid #1a252f !important;
        text-align: right !important;
    }
    
    table tbody td {
        padding: 6px 8px !important;
        border: 1px solid #ddd !important;
        text-align: right !important;
    }
    
    table tbody tr:nth-child(even) {
        background: #f9f9f9 !important;
    }
    
    /* ملخص الفاتورة */
    .bg-gray-50.border-t-4 {
        background: #f5f5f5 !important;
        border-top: 4px solid #2c3e50 !important;
        padding: 10px 15px !important;
        page-break-inside: avoid !important;
        margin: 0 !important;
    }
    
    .bg-gray-50.border-t-4 table {
        background: #fff !important;
        border: 1px solid #ddd !important;
    }
    
    .bg-gray-50.border-t-4 table tr:last-child {
        background: #2c3e50 !important;
        color: #fff !important;
    }
    
    .bg-gray-50.border-t-4 table tr:last-child td {
        background: #2c3e50 !important;
        color: #fff !important;
        font-weight: bold !important;
        font-size: 11pt !important;
    }
    
    /* الملاحظات والفوتر */
    .border-t-2 {
        border-top: 2px solid #ddd !important;
        padding: 8px 15px !important;
        page-break-inside: avoid !important;
        margin: 0 !important;
    }
    
    .border-t-2 h3 {
        margin-bottom: 5px !important;
        font-size: 11pt !important;
    }
    
    .border-t-2 .mb-5,
    .border-t-2 .mb-4 {
        margin-bottom: 8px !important;
    }
    
    .border-t-2 .pt-5 {
        padding-top: 8px !important;
    }
    
    .bg-gray-900.text-white.py-4 {
        background: #2c3e50 !important;
        color: #fff !important;
        padding: 8px 15px !important;
        page-break-inside: avoid !important;
        margin: 0 !important;
    }
    
    .bg-gray-900.text-white.py-4 p {
        margin: 3px 0 !important;
        font-size: 9pt !important;
    }
    
    .bg-gray-900.text-white.py-4 .mt-3 {
        margin-top: 5px !important;
    }
    
    /* إخفاء العناصر */
    .hide-address-print,
    svg {
        display: none !important;
    }
    
    /* تقليل المسافات للطباعة */
    .py-6 {
        padding-top: 6px !important;
        padding-bottom: 6px !important;
    }
    
    .py-5 {
        padding-top: 5px !important;
        padding-bottom: 5px !important;
    }
    
    .py-4 {
        padding-top: 4px !important;
        padding-bottom: 4px !important;
    }
    
    .py-3 {
        padding-top: 3px !important;
        padding-bottom: 3px !important;
    }
    
    .px-10 {
        padding-left: 12px !important;
        padding-right: 12px !important;
    }
    
    .px-8 {
        padding-left: 10px !important;
        padding-right: 10px !important;
    }
    
    .mb-6 {
        margin-bottom: 5px !important;
    }
    
    .mb-5 {
        margin-bottom: 4px !important;
    }
    
    .mb-4 {
        margin-bottom: 3px !important;
    }
    
    .mb-3 {
        margin-bottom: 2px !important;
    }
    
    .mt-6 {
        margin-top: 5px !important;
    }
    
    .mt-5 {
        margin-top: 4px !important;
    }
    
    .mt-4 {
        margin-top: 3px !important;
    }
    
    .mt-3 {
        margin-top: 2px !important;
    }
    
    .gap-6 {
        gap: 5px !important;
    }
    
    /* العناوين */
    h1 { font-size: 18pt !important; margin: 0 0 8px 0 !important; }
    h2 { font-size: 14pt !important; margin: 0 0 6px 0 !important; }
    h3 { font-size: 12pt !important; margin: 0 0 5px 0 !important; }
    h4 { font-size: 11pt !important; margin: 0 0 4px 0 !important; }
    
    /* إزالة الزوايا المستديرة */
    .rounded-lg, .rounded-xl, .rounded-2xl, .rounded {
        border-radius: 0 !important;
    }
    
    /* الخلفيات */
    .bg-blue-50 {
        background: #e8f4f8 !important;
        padding: 5px 12px !important;
        margin: 0 !important;
    }
    
    /* تقليل المسافات في Project Info */
    .bg-blue-50 .py-3 {
        padding-top: 4px !important;
        padding-bottom: 4px !important;
    }
    
    /* تقليل المسافات في Invoice Items */
    div.px-10:has(> h3) {
        padding-top: 8px !important;
        padding-bottom: 8px !important;
    }
    
    /* منع التقسيم - ضمان صفحة واحدة */
    div[style*="max-width: 210mm"] {
        page-break-inside: avoid !important;
        page-break-after: avoid !important;
    }
    
    /* منع تقسيم الأقسام الرئيسية */
    .bg-gray-900:first-of-type,
    .bg-gray-50,
    .bg-gray-50.border-t-4,
    .border-t-2,
    table {
        page-break-inside: avoid !important;
    }
    
    /* السماح بالتقسيم فقط في نهاية الجداول الطويلة */
    table tbody tr {
        page-break-inside: avoid !important;
    }
    
    /* ضمان أن الفاتورة لا تنقسم */
    body > div.flex > div:last-child > div.w-full > div {
        page-break-inside: avoid !important;
    }
    
    /* إزالة overflow */
    .overflow-x-auto, .overflow-hidden {
        overflow: visible !important;
    }
    
    /* النصوص */
    p, span, div {
        color: #000 !important;
    }
    
    /* الخطوط */
    * {
        font-family: 'Arial', 'Tahoma', sans-serif !important;
    }
}
</style>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\invoices\show.blade.php ENDPATH**/ ?>