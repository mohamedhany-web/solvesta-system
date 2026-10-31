@php
    $companyName = \App\Helpers\SettingsHelper::getCompanyName();
    $logoPath = \App\Helpers\SettingsHelper::getLogoPath();
    $logoExists = $logoPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoPath);
    $showLogo = \App\Helpers\SettingsHelper::getInvoiceShowLogo();
    $accent = \App\Helpers\SettingsHelper::getInvoiceAccentColor();
    $headerTitle = \App\Helpers\SettingsHelper::getInvoiceHeaderTitle() ?: 'فاتورة';
    $headerSubtitle = \App\Helpers\SettingsHelper::getInvoiceHeaderSubtitle();
    $footerText = \App\Helpers\SettingsHelper::getInvoiceFooterText();
    $showTax = \App\Helpers\SettingsHelper::getInvoiceShowTaxNumber();
    $showQr = \App\Helpers\SettingsHelper::getInvoiceShowQr();
    $taxNumber = \App\Helpers\SettingsHelper::getTaxNumber();
    $commercialReg = \App\Helpers\SettingsHelper::getCommercialRegistration();

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
    $accountHolder = \App\Helpers\SettingsHelper::getBankAccountHolder();

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

    $companyPhone = trim((string) \App\Helpers\SettingsHelper::getCompanyPhone());
    $companyEmail = trim((string) \App\Helpers\SettingsHelper::getCompanyEmail());
    $companyAddress = trim((string) \App\Helpers\SettingsHelper::getCompanyAddress());
    $serviceTitle = optional($invoice->clientService)->title;
    $isMonthly = ($invoice->invoice_type ?? null) === 'service' || ! empty($invoice->client_service_id);
@endphp

<article class="invoice-doc" id="invoice-print-root" style="--inv-accent: {{ $accent }};">
    <header class="inv-hero">
        <div class="inv-hero__glow"></div>
        <div class="inv-hero__brand">
            @if($showLogo && $logoExists)
                <img src="{{ asset('storage/' . $logoPath) }}" alt="{{ $companyName }}" class="inv-hero__logo">
            @elseif($showLogo)
                <div class="inv-hero__mark">{{ mb_substr($companyName ?: 'S', 0, 1) }}</div>
            @endif
            <div>
                <p class="inv-hero__company">{{ $companyName }}</p>
                @if($headerSubtitle)<p class="inv-hero__sub">{{ $headerSubtitle }}</p>@endif
                <div class="inv-hero__contact">
                    @if($companyPhone)<span>{{ $companyPhone }}</span>@endif
                    @if($companyEmail)<span>{{ $companyEmail }}</span>@endif
                    @if($showTax && $taxNumber)<span>ضريبي: {{ $taxNumber }}</span>@endif
                    @if($commercialReg)<span>س.ت: {{ $commercialReg }}</span>@endif
                </div>
            </div>
        </div>
        <div class="inv-hero__meta">
            <p class="inv-hero__title">{{ $headerTitle }}</p>
            @if($isMonthly)<span class="inv-hero__badge">فاتورة شهرية</span>@endif
            <p class="inv-hero__number">{{ $invoice->invoice_number }}</p>
            <div class="inv-hero__dates">
                <div><span>الإصدار</span><strong>{{ $issueDate ? $issueDate->format('Y/m/d') : '—' }}</strong></div>
                <div><span>الاستحقاق</span><strong>{{ $dueDate ? $dueDate->format('Y/m/d') : '—' }}</strong></div>
                <div><span>الحالة</span><strong>{{ $statusLabel }}</strong></div>
            </div>
        </div>
    </header>

    <section class="inv-parties">
        <div class="inv-party">
            <div class="inv-party__label">من</div>
            <strong>{{ $companyName }}</strong>
            @if($companyAddress)<div>{{ $companyAddress }}</div>@endif
        </div>
        <div class="inv-party">
            <div class="inv-party__label">إلى</div>
            <strong>{{ optional($invoice->client)->name ?? 'غير محدد' }}</strong>
            @if(optional($invoice->client)->company)<div>{{ $invoice->client->company }}</div>@endif
            @if(optional($invoice->client)->phone)<div>{{ $invoice->client->phone }}</div>@endif
            @if(optional($invoice->client)->email)<div>{{ $invoice->client->email }}</div>@endif
        </div>
        @if($showQr)
        <div class="inv-party inv-party--qr">
            <div class="inv-party__label">مرجع</div>
            <div class="inv-qr">{{ $invoice->invoice_number }}</div>
        </div>
        @endif
    </section>

    @if($serviceTitle || $invoice->project)
    <div class="inv-project">
        @if($serviceTitle)الخدمة: <strong>{{ $serviceTitle }}</strong>@endif
        @if($invoice->project){{ $serviceTitle ? ' · ' : '' }}المشروع: <strong>{{ $invoice->project->name }}</strong>@endif
    </div>
    @endif

    <section class="inv-main">
        <div class="inv-items">
            @if(count($itemsArray) > 0)
            <table class="inv-table inv-table--services">
                <thead>
                    <tr>
                        <th class="col-n">#</th>
                        <th class="col-desc">الوصف</th>
                        <th class="col-qty">الكمية</th>
                        <th class="col-price">السعر</th>
                        <th class="col-amount">المبلغ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($itemsArray as $idx => $item)
                    @php
                        $lineAmount = $item['amount'] ?? (($item['quantity'] ?? 1) * ($item['unit_price'] ?? 0));
                    @endphp
                    <tr>
                        <td class="col-n">{{ $idx + 1 }}</td>
                        <td class="col-desc">{{ $item['description'] ?? '—' }}</td>
                        <td class="col-qty">{{ number_format((float) ($item['quantity'] ?? 1), 2) }}</td>
                        <td class="col-price">{{ number_format((float) ($item['unit_price'] ?? $lineAmount), 2) }}</td>
                        <td class="col-amount">{{ number_format($lineAmount, 2) }} ج.م</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="inv-empty">لا توجد بنود على هذه الفاتورة.</p>
            @endif
        </div>
        <div class="inv-summary">
            <table class="inv-summary__table">
                <tr><td>المجموع الفرعي</td><td>{{ number_format($subtotal, 2) }} ج.م</td></tr>
                @if(($invoice->tax_amount ?? 0) > 0)
                <tr><td>الضريبة ({{ $invoice->tax_rate ?? 0 }}%)</td><td>{{ number_format($invoice->tax_amount, 2) }} ج.م</td></tr>
                @endif
                @if(($invoice->discount_amount ?? 0) > 0)
                <tr><td>الخصم</td><td>− {{ number_format($invoice->discount_amount, 2) }} ج.م</td></tr>
                @endif
                <tr class="inv-summary__grand"><td>الإجمالي</td><td>{{ number_format($total, 2) }} ج.م</td></tr>
                @if($paid > 0)
                <tr><td>المدفوع</td><td>{{ number_format($paid, 2) }} ج.م</td></tr>
                @endif
                @if($balanceDue > 0.01)
                <tr class="inv-summary__due"><td>المستحق</td><td>{{ number_format($balanceDue, 2) }} ج.م</td></tr>
                @endif
            </table>
        </div>
    </section>

    @if($paymentsList->isNotEmpty())
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
                @foreach($paymentsList as $payment)
                <tr>
                    <td>{{ $payment->payment_date?->format('Y/m/d') ?? '—' }}</td>
                    <td class="inv-payments__amount">{{ number_format($payment->amount, 2) }} ج.م</td>
                    <td>{{ $paymentMethodLabel($payment->payment_method) }}</td>
                    <td>{{ $payment->wallet?->name ?? '—' }}</td>
                    <td>{{ $payment->reference_number ?: $payment->payment_number }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td><strong>إجمالي المدفوع</strong></td>
                    <td colspan="4"><strong>{{ number_format($paymentsList->sum('amount'), 2) }} ج.م</strong></td>
                </tr>
            </tfoot>
        </table>
    </section>
    @elseif($paid <= 0.01)
    <section class="inv-payments inv-payments--pending">
        <p><strong>حالة الدفع:</strong> لم يتم استلام أي مبلغ بعد — يرجى التحويل حسب بيانات الحساب أدناه.</p>
    </section>
    @endif

    <footer class="inv-foot">
        @if($invoice->notes || $financialNotes)
        <div class="inv-foot__notes">
            @if($invoice->notes)<strong>ملاحظات:</strong> {{ $invoice->notes }}@endif
            @if($financialNotes) {{ $financialNotes }} @endif
        </div>
        @endif
        <div class="inv-foot__grid">
            <div>
                <strong>شروط الدفع:</strong> {{ $paymentMethods ?: 'تحويل بنكي' }} — {{ $days }} يوم
            </div>
            <div>
                @if($bankName || $accountNumber || $iban)
                <strong>التحويل:</strong>
                @if($accountHolder) {{ $accountHolder }} · @endif
                @if($bankName) {{ $bankName }} @endif
                @if($accountNumber) · حساب {{ $accountNumber }} @endif
                @if($iban) · {{ $iban }} @endif
                @endif
            </div>
        </div>
        <div class="inv-foot__bar">
            {{ $footerText !== '' ? $footerText : ('شكراً لتعاملكم معنا — '.$companyName.' — '.date('Y')) }}
        </div>
    </footer>
</article>
