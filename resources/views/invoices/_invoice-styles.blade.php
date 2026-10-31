<style>
*, .invoice-doc, .invoice-doc * { box-sizing: border-box; }

.invoice-page-wrap {
    max-width: 210mm;
    margin: 0 auto;
    padding: 0 8px;
}

.invoice-doc {
    --inv-accent: #0f172a;
    width: 100%;
    max-width: 210mm;
    margin: 0 auto;
    background: #fff;
    border: 1px solid #dbe3ef;
    border-radius: 14px;
    font-family: 'Tajawal', 'Segoe UI', Tahoma, sans-serif;
    color: #0f172a;
    direction: rtl;
    font-size: 13px;
    line-height: 1.45;
    overflow: hidden;
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
}

.inv-hero {
    position: relative;
    display: flex;
    justify-content: space-between;
    gap: 18px;
    padding: 22px 22px 20px;
    background:
        radial-gradient(circle at 15% 20%, rgba(255,255,255,0.16), transparent 40%),
        linear-gradient(135deg, var(--inv-accent) 0%, #111827 55%, #020617 100%);
    color: #fff;
    overflow: hidden;
}

.inv-hero__glow {
    position: absolute;
    inset: auto -40px -60px auto;
    width: 180px;
    height: 180px;
    border-radius: 999px;
    background: rgba(255,255,255,0.12);
    filter: blur(10px);
}

.inv-hero__brand,
.inv-hero__meta {
    position: relative;
    z-index: 1;
}

.inv-hero__brand {
    display: flex;
    align-items: center;
    gap: 14px;
    min-width: 0;
    flex: 1;
}

.inv-hero__logo {
    width: 72px;
    height: 72px;
    object-fit: contain;
    background: #fff;
    border-radius: 14px;
    padding: 8px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.18);
}

.inv-hero__mark {
    width: 72px;
    height: 72px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.14);
    font-size: 1.8rem;
    font-weight: 900;
}

.inv-hero__company {
    margin: 0;
    font-size: 1.15rem;
    font-weight: 900;
    line-height: 1.3;
}

.inv-hero__sub {
    margin: 2px 0 0;
    font-size: 0.78rem;
    opacity: 0.78;
}

.inv-hero__contact {
    margin-top: 8px;
    display: flex;
    flex-wrap: wrap;
    gap: 8px 12px;
    font-size: 0.7rem;
    opacity: 0.85;
}

.inv-hero__meta {
    text-align: left;
    min-width: 210px;
}

.inv-hero__title {
    margin: 0;
    font-size: 1.55rem;
    font-weight: 900;
    letter-spacing: 0.02em;
}

.inv-hero__badge {
    display: inline-block;
    margin-top: 6px;
    padding: 3px 10px;
    border-radius: 999px;
    background: rgba(255,255,255,0.16);
    font-size: 0.68rem;
    font-weight: 800;
}

.inv-hero__number {
    margin: 8px 0 10px;
    font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    font-size: 0.85rem;
    font-weight: 700;
    opacity: 0.92;
}

.inv-hero__dates {
    display: grid;
    gap: 6px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.14);
    border-radius: 12px;
    padding: 10px 12px;
}

.inv-hero__dates div {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    font-size: 0.72rem;
}

.inv-hero__dates span { opacity: 0.7; }
.inv-hero__dates strong { font-weight: 800; }

.inv-parties {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
    padding: 16px 20px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

.inv-party__label {
    font-size: 0.65rem;
    font-weight: 800;
    text-transform: uppercase;
    color: #64748b;
    margin-bottom: 4px;
    letter-spacing: 0.04em;
}

.inv-party {
    font-size: 0.8rem;
    line-height: 1.55;
    word-break: break-word;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px;
}

.inv-party--qr .inv-qr {
    font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
    font-weight: 800;
    font-size: 0.78rem;
    padding: 10px;
    border: 1px dashed #94a3b8;
    border-radius: 8px;
    text-align: center;
}

.inv-project {
    padding: 8px 20px;
    font-size: 0.78rem;
    background: color-mix(in srgb, var(--inv-accent) 8%, white);
    border-bottom: 1px solid #e2e8f0;
}

.inv-main {
    display: flex;
    gap: 14px;
    padding: 16px 20px;
    align-items: flex-start;
}

.inv-items { flex: 1; min-width: 0; }

.inv-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.78rem;
}

.inv-table thead th {
    background: var(--inv-accent);
    color: #fff;
    padding: 8px 10px;
    font-weight: 800;
    text-align: right;
}

.inv-table td {
    padding: 9px 10px;
    border-bottom: 1px solid #e2e8f0;
    vertical-align: top;
}

.inv-table tbody tr:nth-child(even) { background: #f8fafc; }
.inv-table .col-n { width: 36px; color: #64748b; }
.inv-table .col-qty,
.inv-table .col-price { width: 78px; white-space: nowrap; }
.inv-table .col-amount {
    width: 110px;
    text-align: left;
    font-weight: 800;
    white-space: nowrap;
}

.inv-empty {
    margin: 0;
    padding: 24px;
    text-align: center;
    color: #64748b;
    border: 1px dashed #cbd5e1;
    border-radius: 12px;
}

.inv-summary {
    width: 230px;
    flex-shrink: 0;
}

.inv-summary__table {
    width: 100%;
    border-collapse: collapse;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
}

.inv-summary__table td {
    padding: 8px 12px;
    font-size: 0.78rem;
    border-bottom: 1px solid #e2e8f0;
}

.inv-summary__table td:last-child {
    text-align: left;
    font-weight: 800;
    white-space: nowrap;
}

.inv-summary__grand td {
    background: var(--inv-accent);
    color: #fff;
    font-size: 0.9rem;
    font-weight: 900;
}

.inv-summary__due td {
    background: #fff7ed;
    color: #c2410c;
    font-weight: 900;
}

.inv-payments {
    padding: 0 20px 16px;
}

.inv-payments__title {
    margin: 0 0 8px;
    font-size: 0.85rem;
    font-weight: 800;
}

.inv-payments__table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.75rem;
}

.inv-payments__table th,
.inv-payments__table td {
    padding: 7px 8px;
    border-bottom: 1px solid #e2e8f0;
    text-align: right;
}

.inv-payments__table thead th {
    background: #f1f5f9;
    font-weight: 800;
}

.inv-payments__amount { font-weight: 800; }
.inv-payments--pending {
    margin: 0 20px 16px;
    padding: 12px 14px;
    border-radius: 12px;
    background: #fff7ed;
    border: 1px solid #fed7aa;
    color: #9a3412;
    font-size: 0.8rem;
}

.inv-foot {
    border-top: 1px solid #e2e8f0;
    padding: 14px 20px 0;
}

.inv-foot__notes {
    margin-bottom: 10px;
    font-size: 0.78rem;
    color: #475569;
    line-height: 1.6;
}

.inv-foot__grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    font-size: 0.76rem;
    color: #334155;
    padding-bottom: 12px;
}

.inv-foot__bar {
    margin: 0 -20px;
    padding: 12px 20px;
    background: var(--inv-accent);
    color: #fff;
    text-align: center;
    font-size: 0.78rem;
    font-weight: 800;
}

@media print {
    .invoice-page-wrap { max-width: none; padding: 0; }
    .invoice-doc {
        border: none;
        border-radius: 0;
        box-shadow: none;
        max-width: none;
    }
    .no-print { display: none !important; }
}

@media (max-width: 720px) {
    .inv-hero { flex-direction: column; }
    .inv-hero__meta { text-align: right; min-width: 0; }
    .inv-main { flex-direction: column; }
    .inv-summary { width: 100%; }
    .inv-foot__grid { grid-template-columns: 1fr; }
}
</style>
