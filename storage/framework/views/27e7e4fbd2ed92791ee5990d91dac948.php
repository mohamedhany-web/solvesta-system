<?php
    $companyName = \App\Helpers\SettingsHelper::getCompanyName();
    $logoPath = \App\Helpers\SettingsHelper::getLogoPath();
    $logoExists = $logoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath);

    $issueDate = $invoice->invoice_date
        ? ($invoice->invoice_date instanceof \Carbon\Carbon ? $invoice->invoice_date : \Carbon\Carbon::parse($invoice->invoice_date))
        : ($invoice->issue_date ? ($invoice->issue_date instanceof \Carbon\Carbon ? $invoice->issue_date : \Carbon\Carbon::parse($invoice->issue_date)) : null);

    $dueDate = $invoice->due_date
        ? ($invoice->due_date instanceof \Carbon\Carbon ? $invoice->due_date : \Carbon\Carbon::parse($invoice->due_date))
        : null;

    $paid = (float) ($invoice->paid_amount ?? 0);
    $total = (float) ($invoice->total_amount ?? 0);
    $balanceDue = (float) ($invoice->balance_due ?? $invoice->balance_amount ?? max(0, $total - $paid));

    $statusLabel = match ($invoice->status) {
        'draft' => 'مسودة', 'sent' => 'مرسلة', 'viewed' => 'مشاهدة', 'paid' => 'مدفوعة',
        'partial' => 'جزئية', 'overdue' => 'متأخرة', 'cancelled' => 'ملغاة', default => $invoice->status,
    };

    $itemsArray = [];
    if ($invoice->relationLoaded('items') && $invoice->items && $invoice->items->isNotEmpty()) {
        $itemsArray = $invoice->items->map(fn ($i) => [
            'description' => $i->description ?? $i->item_name ?? '—',
            'quantity' => $i->quantity,
            'unit_price' => $i->unit_price,
            'amount' => $i->amount ?? ($i->quantity * $i->unit_price),
        ])->all();
    } elseif (is_array($invoice->items) && count($invoice->items) > 0) {
        $itemsArray = $invoice->items;
    }

    $subtotal = (float) ($invoice->subtotal ?? $total);
    $financialNotes = \App\Helpers\SettingsHelper::getInvoiceFinancialNotes();
    $paymentMethods = \App\Helpers\SettingsHelper::getPaymentMethods();
    $defaultPeriod = \App\Helpers\SettingsHelper::getDefaultPaymentPeriod();
    $days = ($issueDate && $dueDate) ? $issueDate->diffInDays($dueDate) : $defaultPeriod;
    $bankName = \App\Helpers\SettingsHelper::getBankName();
    $accountNumber = \App\Helpers\SettingsHelper::getBankAccountNumber();
    $iban = \App\Helpers\SettingsHelper::getBankIban();

    $paymentsList = ($invoice->relationLoaded('payments') && $invoice->payments)
        ? $invoice->payments->where('status', 'completed')->sortBy('payment_date')
        : collect();

    $paymentMethodLabel = fn ($method) => match ($method) {
        'cash' => 'نقدي',
        'bank_transfer' => 'تحويل بنكي',
        'check' => 'شيك',
        'credit_card' => 'بطاقة ائتمان',
        'online' => 'دفع إلكتروني',
        default => $method ?: '—',
    };
?>

<article class="invoice-doc" id="invoice-print-root">
    <?php
        $companyPhone = trim((string) \App\Helpers\SettingsHelper::getCompanyPhone());
        $companyEmail = trim((string) \App\Helpers\SettingsHelper::getCompanyEmail());
    ?>
    <header class="inv-head">
        <div class="inv-head__edge inv-head__edge--right">
            <p class="inv-head__label">من</p>
            <p class="inv-head__company"><?php echo e($companyName); ?></p>
            <?php if($companyPhone || $companyEmail): ?>
            <div class="inv-head__lines">
                <?php if($companyPhone): ?><span><?php echo e($companyPhone); ?></span><?php endif; ?>
                <?php if($companyEmail): ?><span><?php echo e($companyEmail); ?></span><?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="inv-head__logo-wrap">
            <?php if($logoExists): ?>
                <img src="<?php echo e(asset('storage/' . $logoPath)); ?>" alt="<?php echo e($companyName); ?>" class="inv-head__logo">
            <?php else: ?>
                <div class="inv-head__logo inv-head__logo--placeholder"><?php echo e(mb_substr($companyName, 0, 1)); ?></div>
            <?php endif; ?>
        </div>

        <div class="inv-head__edge inv-head__edge--left">
            <div class="inv-head__invoice-id">
                <span class="inv-head__title">فاتورة</span>
                <span class="inv-head__number"><?php echo e($invoice->invoice_number); ?></span>
            </div>
            <div class="inv-head__meta-box">
                <div class="inv-head__meta-row">
                    <span class="inv-head__meta-key">تاريخ الإصدار</span>
                    <span class="inv-head__meta-val"><?php echo e($issueDate ? $issueDate->format('Y/m/d') : '—'); ?></span>
                </div>
                <div class="inv-head__meta-row">
                    <span class="inv-head__meta-key">تاريخ الاستحقاق</span>
                    <span class="inv-head__meta-val"><?php echo e($dueDate ? $dueDate->format('Y/m/d') : '—'); ?></span>
                </div>
                <div class="inv-head__meta-row">
                    <span class="inv-head__meta-key">الحالة</span>
                    <span class="inv-head__meta-val inv-head__meta-val--status"><?php echo e($statusLabel); ?></span>
                </div>
            </div>
        </div>
    </header>

    <section class="inv-parties">
        <div class="inv-party">
            <div class="inv-party__label">من</div>
            <strong><?php echo e($companyName); ?></strong>
            <?php if(\App\Helpers\SettingsHelper::getCompanyAddress()): ?><div><?php echo e(\App\Helpers\SettingsHelper::getCompanyAddress()); ?></div><?php endif; ?>
        </div>
        <div class="inv-party">
            <div class="inv-party__label">إلى</div>
            <strong><?php echo e(optional($invoice->client)->name ?? 'غير محدد'); ?></strong>
            <?php if(optional($invoice->client)->company): ?><div><?php echo e($invoice->client->company); ?></div><?php endif; ?>
            <?php if(optional($invoice->client)->phone): ?><div><?php echo e($invoice->client->phone); ?></div><?php endif; ?>
            <?php if(optional($invoice->client)->email): ?><div><?php echo e($invoice->client->email); ?></div><?php endif; ?>
        </div>
    </section>

    <?php if($invoice->project): ?>
    <div class="inv-project">المشروع: <strong><?php echo e($invoice->project->name); ?></strong></div>
    <?php endif; ?>

    <section class="inv-main">
        <div class="inv-items">
            <?php if(count($itemsArray) > 0): ?>
            <table class="inv-table inv-table--services">
                <thead>
                    <tr>
                        <th class="col-desc">وصف الخدمة</th>
                        <th class="col-amount">المبلغ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $itemsArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $lineAmount = $item['amount'] ?? (($item['quantity'] ?? 1) * ($item['unit_price'] ?? 0));
                    ?>
                    <tr>
                        <td class="col-desc"><?php echo e($item['description'] ?? '—'); ?></td>
                        <td class="col-amount"><?php echo e(number_format($lineAmount, 2)); ?> ج.م</td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php else: ?>
            <p class="inv-empty">لا توجد بنود على هذه الفاتورة.</p>
            <?php endif; ?>
        </div>
        <div class="inv-summary">
            <table class="inv-summary__table">
                <tr><td>المجموع الفرعي</td><td><?php echo e(number_format($subtotal, 2)); ?> ج.م</td></tr>
                <?php if(($invoice->tax_amount ?? 0) > 0): ?>
                <tr><td>الضريبة (<?php echo e($invoice->tax_rate ?? 0); ?>%)</td><td><?php echo e(number_format($invoice->tax_amount, 2)); ?> ج.م</td></tr>
                <?php endif; ?>
                <?php if(($invoice->discount_amount ?? 0) > 0): ?>
                <tr><td>الخصم</td><td>− <?php echo e(number_format($invoice->discount_amount, 2)); ?> ج.م</td></tr>
                <?php endif; ?>
                <tr class="inv-summary__grand"><td>الإجمالي</td><td><?php echo e(number_format($total, 2)); ?> ج.م</td></tr>
                <?php if($paid > 0): ?>
                <tr><td>المدفوع</td><td><?php echo e(number_format($paid, 2)); ?> ج.م</td></tr>
                <?php endif; ?>
                <?php if($balanceDue > 0.01): ?>
                <tr class="inv-summary__due"><td>المستحق</td><td><?php echo e(number_format($balanceDue, 2)); ?> ج.م</td></tr>
                <?php endif; ?>
            </table>
        </div>
    </section>

    <?php if($paymentsList->isNotEmpty()): ?>
    <section class="inv-payments">
        <h3 class="inv-payments__title">سجل التحويلات والدفعات المستلمة</h3>
        <table class="inv-payments__table">
            <thead>
                <tr>
                    <th>التاريخ</th>
                    <th>المبلغ</th>
                    <th>طريقة الدفع</th>
                    <th>المحفظة / الحساب</th>
                    <th>المرجع</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $paymentsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($payment->payment_date?->format('Y/m/d') ?? '—'); ?></td>
                    <td class="inv-payments__amount"><?php echo e(number_format($payment->amount, 2)); ?> ج.م</td>
                    <td><?php echo e($paymentMethodLabel($payment->payment_method)); ?></td>
                    <td><?php echo e($payment->wallet?->name ?? '—'); ?></td>
                    <td><?php echo e($payment->reference_number ?: $payment->payment_number); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
                <tr>
                    <td><strong>إجمالي المدفوع</strong></td>
                    <td colspan="4"><strong><?php echo e(number_format($paymentsList->sum('amount'), 2)); ?> ج.م</strong></td>
                </tr>
            </tfoot>
        </table>
    </section>
    <?php elseif($paid <= 0.01): ?>
    <section class="inv-payments inv-payments--pending">
        <p><strong>حالة الدفع:</strong> لم يتم استلام أي مبلغ بعد — يرجى التحويل حسب بيانات الحساب أدناه.</p>
    </section>
    <?php endif; ?>

    <footer class="inv-foot">
        <?php if($invoice->notes || $financialNotes): ?>
        <div class="inv-foot__notes">
            <?php if($invoice->notes): ?><strong>ملاحظات:</strong> <?php echo e($invoice->notes); ?><?php endif; ?>
            <?php if($financialNotes): ?> <?php echo e($financialNotes); ?> <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="inv-foot__grid">
            <div>
                <strong>شروط الدفع:</strong> <?php echo e($paymentMethods ?: 'تحويل بنكي'); ?> — <?php echo e($days); ?> يوم
            </div>
            <div>
                <?php if($bankName || $accountNumber || $iban): ?>
                <strong>التحويل:</strong>
                <?php if($bankName): ?> <?php echo e($bankName); ?> <?php endif; ?>
                <?php if($accountNumber): ?> · حساب <?php echo e($accountNumber); ?> <?php endif; ?>
                <?php if($iban): ?> · <?php echo e($iban); ?> <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="inv-foot__bar">شكراً لتعاملكم معنا — <?php echo e($companyName); ?> — <?php echo e(date('Y')); ?></div>
    </footer>
</article>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\invoices\_document.blade.php ENDPATH**/ ?>