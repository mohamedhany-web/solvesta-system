<?php

/**
 * KPIs لموظف مبيعات B2B Software — معايير صناعة SaaS/Enterprise sales.
 *
 * @see https://www.salesforce.com/blog/sales-kpis/
 * @see https://www.hubspot.com/sales/sales-metrics
 */
return [
    'metrics' => [
        'revenue_won' => [
            'label' => 'إيراد الصفقات المغلقة (Quota)',
            'unit' => 'ج.م',
            'type' => 'currency',
            'description' => 'مجموع قيمة الصفقات Won في الشهر — الهدف الأساسي للـ Quota Attainment.',
        ],
        'deals_closed' => [
            'label' => 'صفقات مغلقة Won',
            'unit' => 'صفقة',
            'type' => 'count',
            'description' => 'عدد الصفقات التي وصلت closed_won — مؤشر إنتاجية المندوب.',
        ],
        'pipeline_value' => [
            'label' => 'قيمة Pipeline المفتوح',
            'unit' => 'ج.م',
            'type' => 'currency',
            'description' => 'مجموع estimated_value للفرص المفتوحة — يُفضّل تغطية 3–4× الـ Quota.',
        ],
        'pipeline_coverage' => [
            'label' => 'تغطية Pipeline (×Quota)',
            'unit' => '×',
            'type' => 'ratio',
            'description' => 'Pipeline ÷ Quota — معيار صناعة B2B software (هدف 3× على الأقل).',
        ],
        'leads_qualified' => [
            'label' => 'Leads مؤهلة (SQL)',
            'unit' => 'lead',
            'type' => 'count',
            'description' => 'Leads بstatus qualified — Sales Qualified Leads للـ enterprise software.',
        ],
        'new_opportunities' => [
            'label' => 'فرص جديدة (Opportunities)',
            'unit' => 'فرصة',
            'type' => 'count',
            'description' => 'صفقات جديدة أُنشئت في CRM خلال الشهر.',
        ],
        'win_rate' => [
            'label' => 'معدل الفوز Win Rate',
            'unit' => '%',
            'type' => 'percent',
            'description' => 'Won ÷ (Won + Lost) — معيار B2B software ~15–30%.',
        ],
        'demos_meetings' => [
            'label' => 'عروض / اجتماعات مؤهلة',
            'unit' => 'اجتماع',
            'type' => 'count',
            'description' => 'Leads تم التواصل معها أو تأهيلها — proxy لـ discovery/demo meetings.',
        ],
        'avg_deal_size' => [
            'label' => 'متوسط حجم الصفقة',
            'unit' => 'ج.م',
            'type' => 'currency',
            'description' => 'متوسط قيمة الصفقات Won — مهم لـ ACV في software sales.',
        ],
    ],

    'default_targets' => [
        'revenue_won' => 150000,
        'deals_closed' => 2,
        'pipeline_value' => 450000,
        'pipeline_coverage' => 3,
        'leads_qualified' => 8,
        'new_opportunities' => 12,
        'win_rate' => 22,
        'demos_meetings' => 15,
        'avg_deal_size' => 75000,
    ],

    'default_weights' => [
        'revenue_won' => 25,
        'deals_closed' => 15,
        'pipeline_value' => 10,
        'pipeline_coverage' => 10,
        'leads_qualified' => 10,
        'new_opportunities' => 8,
        'win_rate' => 10,
        'demos_meetings' => 7,
        'avg_deal_size' => 5,
    ],
];
