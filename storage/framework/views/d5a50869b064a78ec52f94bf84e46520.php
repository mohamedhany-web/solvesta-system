<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>فاتورة <?php echo e($invoice->invoice_number ?? ''); ?> — <?php echo e(\App\Helpers\SettingsHelper::getCompanyName()); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    <?php echo $__env->make('invoices._invoice-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <style>
        body.invoice-print-page {
            margin: 0;
            padding: 20px 24px;
            background: #e2e8f0;
            font-family: 'Tajawal', sans-serif;
        }
        .invoice-print-toolbar {
            max-width: calc(210mm + 32px);
            margin: 0 auto 12px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .invoice-print-toolbar a,
        .invoice-print-toolbar button {
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            border: none;
            font-family: inherit;
        }
        .invoice-print-toolbar .btn-print { background: #2563eb; color: #fff; }
        .invoice-print-toolbar .btn-back { background: #fff; color: #334155; text-decoration: none; border: 1px solid #cbd5e1; }
        @media print {
            body.invoice-print-page {
                background: #fff !important;
                padding: 0 !important;
            }
            .invoice-print-toolbar { display: none !important; }
            .invoice-page-wrap {
                max-width: 100% !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body class="invoice-print-page">
    <div class="invoice-print-toolbar no-print">
        <a href="<?php echo e($backUrl ?? url()->previous()); ?>" class="btn-back">← العودة للفاتورة</a>
        <button type="button" class="btn-print" onclick="window.print()">طباعة / حفظ PDF</button>
    </div>
    <div class="invoice-page-wrap">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <?php if(request()->boolean('auto')): ?>
    <script>window.addEventListener('load', () => setTimeout(() => window.print(), 400));</script>
    <?php endif; ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\layouts\invoice-print.blade.php ENDPATH**/ ?>