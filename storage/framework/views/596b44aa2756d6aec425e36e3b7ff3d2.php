<style>
*, .invoice-doc, .invoice-doc * {
    box-sizing: border-box;
}

.invoice-page-wrap {
    max-width: 210mm;
    margin: 0 auto;
    padding: 0 8px;
}

.invoice-doc {
    width: 100%;
    max-width: 210mm;
    margin: 0 auto;
    background: #fff;
    border: 1px solid #cbd5e1;
    font-family: 'Tajawal', 'Segoe UI', Tahoma, sans-serif;
    color: #0f172a;
    direction: rtl;
    font-size: 13px;
    line-height: 1.45;
    overflow: hidden;
}

.inv-head {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    min-height: 92px;
    padding: 14px 16px;
    background: #0f172a;
    color: #fff;
}

/* أقصى اليمين (بداية RTL) */
.inv-head__edge--right {
    flex: 0 1 auto;
    max-width: calc(50% - 72px);
    text-align: right;
    z-index: 2;
}

/* أقصى اليسار (نهاية RTL) */
.inv-head__edge--left {
    flex: 0 1 auto;
    max-width: calc(50% - 72px);
    text-align: left;
    z-index: 2;
}

.inv-head__logo-wrap {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    z-index: 1;
    pointer-events: none;
}

.inv-head__label {
    margin: 0 0 4px;
    font-size: 0.62rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: rgba(255, 255, 255, 0.55);
}

.inv-head__company {
    margin: 0;
    font-size: 0.95rem;
    font-weight: 800;
    line-height: 1.35;
    word-break: break-word;
}

.inv-head__lines {
    margin-top: 6px;
    display: flex;
    flex-direction: column;
    gap: 2px;
    font-size: 0.68rem;
    line-height: 1.4;
    color: rgba(255, 255, 255, 0.82);
}

.inv-head__logo {
    display: block;
    max-height: 68px;
    max-width: 130px;
    width: auto;
    height: auto;
    object-fit: contain;
    background: #fff;
    border-radius: 8px;
    padding: 8px 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.inv-head__logo--placeholder {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: 800;
    color: #0f172a;
}

.inv-head__invoice-id {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 2px;
    margin-bottom: 8px;
}

.inv-head__edge--left .inv-head__invoice-id {
    align-items: flex-start;
}

.inv-head__title {
    font-size: 1.2rem;
    font-weight: 800;
    line-height: 1.1;
}

.inv-head__number {
    font-size: 0.78rem;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.88);
    font-family: ui-monospace, 'Courier New', monospace;
}

.inv-head__meta-box {
    display: inline-block;
    min-width: 200px;
    background: rgba(255, 255, 255, 0.07);
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 6px;
    padding: 6px 10px;
}

.inv-head__meta-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    padding: 3px 0;
    font-size: 0.68rem;
    line-height: 1.3;
}

.inv-head__meta-row + .inv-head__meta-row {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.inv-head__meta-key {
    color: rgba(255, 255, 255, 0.65);
    white-space: nowrap;
}

.inv-head__meta-val {
    font-weight: 700;
    color: #fff;
    text-align: left;
    white-space: nowrap;
}

.inv-head__meta-val--status {
    background: rgba(255, 255, 255, 0.15);
    padding: 1px 6px;
    border-radius: 4px;
    font-size: 0.65rem;
}

.inv-parties {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    padding: 12px 20px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.inv-party__label {
    font-size: 0.65rem;
    font-weight: 800;
    text-transform: uppercase;
    color: #64748b;
    margin-bottom: 4px;
}

.inv-party {
    font-size: 0.78rem;
    line-height: 1.5;
    word-break: break-word;
}

.inv-project {
    padding: 6px 20px;
    font-size: 0.75rem;
    background: #eff6ff;
    border-bottom: 1px solid #dbeafe;
}

.inv-main {
    display: flex;
    gap: 14px;
    padding: 12px 20px;
    align-items: flex-start;
}

.inv-items {
    flex: 1;
    min-width: 0;
    overflow: hidden;
}

.inv-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.78rem;
    table-layout: fixed;
}

.inv-table thead th {
    background: #1e293b;
    color: #fff;
    padding: 6px 8px;
    font-weight: 700;
    text-align: right;
    border: 1px solid #1e293b;
}

.inv-table .col-desc { width: auto; }
.inv-table .col-amount {
    width: 120px;
    text-align: center;
    font-weight: 700;
    white-space: nowrap;
}

.inv-table--services .col-desc {
    width: 75%;
}

.inv-table tbody td {
    padding: 5px 8px;
    border: 1px solid #e2e8f0;
    vertical-align: top;
    word-wrap: break-word;
}

.inv-table tbody tr:nth-child(even) {
    background: #f8fafc;
}

.inv-empty {
    text-align: center;
    padding: 20px;
    color: #64748b;
    border: 1px dashed #cbd5e1;
    font-size: 0.8rem;
}

.inv-summary {
    width: 190px;
    flex-shrink: 0;
}

.inv-summary__table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.78rem;
    border: 1px solid #e2e8f0;
}

.inv-summary__table td {
    padding: 5px 8px;
    border-bottom: 1px solid #f1f5f9;
}

.inv-summary__table td:first-child {
    color: #64748b;
    font-weight: 600;
}

.inv-summary__table td:last-child {
    text-align: left;
    font-weight: 700;
    font-variant-numeric: tabular-nums;
}

.inv-summary__grand td {
    background: #0f172a !important;
    color: #fff !important;
    font-weight: 800;
    font-size: 0.85rem;
}

.inv-summary__due td {
    background: #fef3c7 !important;
    color: #92400e !important;
    font-weight: 800;
}

.inv-foot {
    padding: 10px 20px 16px;
    border-top: 2px solid #0f172a;
    font-size: 0.72rem;
    color: #475569;
}

.inv-foot__notes {
    margin-bottom: 8px;
    padding: 6px 10px;
    background: #f8fafc;
    border-right: 3px solid #64748b;
    line-height: 1.5;
}

.inv-foot__grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-bottom: 8px;
}

.inv-foot__bar {
    text-align: center;
    padding: 6px;
    background: #0f172a;
    color: #fff;
    font-weight: 600;
    font-size: 0.7rem;
    border-radius: 4px;
}

.inv-payments {
    padding: 10px 20px 12px;
    border-top: 1px solid #e2e8f0;
    background: #f0fdf4;
}

.inv-payments--pending {
    background: #fffbeb;
    border-color: #fde68a;
}

.inv-payments--pending p {
    margin: 0;
    font-size: 0.78rem;
    color: #92400e;
}

.inv-payments__title {
    font-size: 0.75rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #166534;
    margin: 0 0 8px;
}

.inv-payments__table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.75rem;
    background: #fff;
    border: 1px solid #bbf7d0;
}

.inv-payments__table th {
    background: #166534;
    color: #fff;
    padding: 5px 8px;
    text-align: right;
    font-weight: 700;
}

.inv-payments__table td {
    padding: 5px 8px;
    border-bottom: 1px solid #dcfce7;
    text-align: right;
}

.inv-payments__table tfoot td {
    background: #dcfce7;
    font-weight: 800;
    color: #14532d;
}

.inv-payments__amount {
    font-weight: 800;
    color: #166534;
    white-space: nowrap;
}

@media (max-width: 640px) {
    .inv-head {
        flex-direction: column;
        align-items: stretch;
        min-height: 0;
        padding-top: 12px;
    }
    .inv-head__logo-wrap {
        position: static;
        transform: none;
        order: -1;
        display: flex;
        justify-content: center;
        margin-bottom: 8px;
    }
    .inv-head__edge--right,
    .inv-head__edge--left {
        max-width: 100%;
        text-align: center;
    }
    .inv-head__edge--left .inv-head__invoice-id {
        align-items: center;
    }
    .inv-head__meta-box {
        width: 100%;
        min-width: 0;
    }
    .inv-main { flex-direction: column; }
    .inv-summary { width: 100%; }
    .inv-parties { grid-template-columns: 1fr; }
    .inv-foot__grid { grid-template-columns: 1fr; }
}

/* ——— طباعة / PDF ——— */
@media print {
    @page {
        size: A4 portrait;
        margin: 14mm 16mm;
    }

    html, body {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* صفحة الطباعة المستقلة: بدون خدعة visibility */
    body.invoice-print-page .invoice-print-toolbar {
        display: none !important;
    }

    body.invoice-print-page .invoice-page-wrap {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    body.invoice-print-page .invoice-doc {
        width: 100% !important;
        max-width: 100% !important;
        border: none !important;
        font-size: 9pt;
    }

    /* طباعة من داخل التطبيق */
    body:not(.invoice-print-page) .no-print,
    body:not(.invoice-print-page) #sidebar,
    body:not(.invoice-print-page) .sidebar-bg,
    body:not(.invoice-print-page) .sidebar-overlay,
    body:not(.invoice-print-page) header,
    body:not(.invoice-print-page) .main-content-mobile > header {
        display: none !important;
    }

    body:not(.invoice-print-page) * {
        visibility: hidden;
    }

    body:not(.invoice-print-page) #invoice-print-root,
    body:not(.invoice-print-page) #invoice-print-root * {
        visibility: visible;
    }

    body:not(.invoice-print-page) .invoice-page-wrap {
        position: static !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    body:not(.invoice-print-page) #invoice-print-root {
        position: static !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 auto !important;
    }

    .invoice-doc {
        max-width: 100% !important;
        width: 100% !important;
        border: none !important;
        overflow: visible !important;
    }

    .inv-head {
        padding: 10px 12px !important;
        min-height: 84px !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .inv-head__logo-wrap {
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
    }

    .inv-head__logo {
        max-height: 54px !important;
        max-width: 110px !important;
        padding: 6px 10px !important;
    }

    .inv-head__edge--right,
    .inv-head__edge--left {
        max-width: calc(50% - 60px) !important;
    }

    .inv-head__meta-box {
        min-width: 0 !important;
        -webkit-print-color-adjust: exact;
    }

    .inv-parties {
        padding: 8px 12px !important;
        -webkit-print-color-adjust: exact;
    }

    .inv-main {
        flex-direction: column !important;
        padding: 8px 12px !important;
        gap: 10px !important;
    }

    .inv-summary {
        width: 100% !important;
        max-width: 240px !important;
        margin-right: auto !important;
        margin-left: 0 !important;
    }

    .inv-foot,
    .inv-payments {
        padding-left: 12px !important;
        padding-right: 12px !important;
        page-break-inside: avoid;
    }

    .inv-foot {
        padding-bottom: 0 !important;
    }

    .inv-payments__table th {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .inv-table {
        font-size: 8.5pt;
        table-layout: fixed;
        width: 100% !important;
    }

    .inv-table thead th,
    .inv-summary__grand td,
    .inv-foot__bar,
    .inv-head {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    a[href]:after {
        content: none !important;
    }
}
</style>
<?php /**PATH C:\xampp\htdocs\solvesta\resources\views\invoices\_invoice-styles.blade.php ENDPATH**/ ?>