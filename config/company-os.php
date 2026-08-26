<?php

return [
    'entities' => [
        'project' => \App\Models\Project::class,
        'employee' => \App\Models\Employee::class,
        'financial_invoice' => \App\Models\FinancialInvoice::class,
    ],

    'workflows' => [
        'project' => [
            'name' => 'دورة حياة المشروع',
            'slug' => 'project-lifecycle',
            'states' => [
                ['key' => 'planning', 'label_ar' => 'تخطيط', 'is_initial' => true, 'sla_hours' => 72, 'severity_if_breached' => 'warning', 'maps_to_status' => 'planning'],
                ['key' => 'development', 'label_ar' => 'تطوير', 'sla_hours' => 168, 'severity_if_breached' => 'warning', 'maps_to_status' => 'in_progress'],
                ['key' => 'qa', 'label_ar' => 'ضمان الجودة', 'sla_hours' => 48, 'severity_if_breached' => 'critical', 'maps_to_status' => 'in_progress'],
                ['key' => 'delivery', 'label_ar' => 'تسليم', 'sla_hours' => 72, 'severity_if_breached' => 'warning', 'maps_to_status' => 'in_progress'],
                ['key' => 'support', 'label_ar' => 'دعم', 'sla_hours' => 720, 'severity_if_breached' => 'warning', 'maps_to_status' => 'in_progress'],
                ['key' => 'completed', 'label_ar' => 'مكتمل', 'is_terminal' => true, 'maps_to_status' => 'completed'],
                ['key' => 'on_hold', 'label_ar' => 'متوقف', 'is_side' => true, 'maps_to_status' => 'on_hold'],
                ['key' => 'cancelled', 'label_ar' => 'ملغي', 'is_terminal' => true, 'is_side' => true, 'maps_to_status' => 'cancelled'],
            ],
            'transitions' => [
                ['from' => 'planning', 'to' => 'development'],
                ['from' => 'development', 'to' => 'qa', 'guard' => 'qa_ready'],
                ['from' => 'qa', 'to' => 'delivery', 'guard' => 'delivery_ready'],
                ['from' => 'delivery', 'to' => 'support'],
                ['from' => 'support', 'to' => 'completed'],
                ['from' => 'on_hold', 'to' => 'development'],
                ['from' => 'on_hold', 'to' => 'planning'],
            ],
            'side_from' => ['planning', 'development', 'qa', 'delivery', 'support'],
            'side_to' => ['on_hold', 'cancelled'],
            'status_map' => [
                'planning' => 'planning',
                'in_progress' => 'development',
                'on_hold' => 'on_hold',
                'completed' => 'completed',
                'cancelled' => 'cancelled',
            ],
        ],

        'employee' => [
            'name' => 'دورة حياة الموظف',
            'slug' => 'employee-lifecycle',
            'states' => [
                ['key' => 'onboarding', 'label_ar' => 'انضمام', 'is_initial' => true, 'sla_hours' => 168, 'severity_if_breached' => 'warning', 'maps_to_status' => 'active'],
                ['key' => 'active', 'label_ar' => 'نشط', 'maps_to_status' => 'active'],
                ['key' => 'performance_review', 'label_ar' => 'تقييم أداء', 'sla_hours' => 336, 'severity_if_breached' => 'warning', 'maps_to_status' => 'active'],
                ['key' => 'improvement', 'label_ar' => 'خطة تحسين', 'sla_hours' => 720, 'severity_if_breached' => 'critical', 'maps_to_status' => 'active'],
                ['key' => 'promotion', 'label_ar' => 'ترقية', 'sla_hours' => 336, 'severity_if_breached' => 'warning', 'maps_to_status' => 'active'],
                ['key' => 'on_leave', 'label_ar' => 'في إجازة', 'is_side' => true, 'maps_to_status' => 'on_leave'],
                ['key' => 'exit', 'label_ar' => 'مغادرة', 'is_terminal' => true, 'is_side' => true, 'maps_to_status' => 'terminated'],
            ],
            'transitions' => [
                ['from' => 'onboarding', 'to' => 'active', 'guard' => 'employee_activate'],
                ['from' => 'active', 'to' => 'performance_review'],
                ['from' => 'performance_review', 'to' => 'active'],
                ['from' => 'performance_review', 'to' => 'improvement'],
                ['from' => 'performance_review', 'to' => 'promotion'],
                ['from' => 'improvement', 'to' => 'active'],
                ['from' => 'improvement', 'to' => 'exit'],
                ['from' => 'promotion', 'to' => 'active'],
                ['from' => 'on_leave', 'to' => 'active'],
            ],
            'side_from' => ['onboarding', 'active', 'performance_review', 'improvement', 'promotion'],
            'side_to' => ['on_leave', 'exit'],
            'status_map' => [
                'active' => 'active',
                'inactive' => 'active',
                'on_leave' => 'on_leave',
                'terminated' => 'exit',
            ],
        ],

        'financial_invoice' => [
            'name' => 'دورة حياة الفاتورة',
            'slug' => 'invoice-lifecycle',
            'states' => [
                ['key' => 'draft', 'label_ar' => 'مسودة', 'is_initial' => true, 'maps_to_status' => 'draft'],
                ['key' => 'pending_approval', 'label_ar' => 'بانتظار الموافقة', 'sla_hours' => 48, 'severity_if_breached' => 'warning', 'maps_to_status' => 'draft'],
                ['key' => 'approved', 'label_ar' => 'معتمدة', 'sla_hours' => 24, 'severity_if_breached' => 'warning', 'maps_to_status' => 'draft'],
                ['key' => 'sent', 'label_ar' => 'مُرسلة', 'sla_hours' => 168, 'severity_if_breached' => 'critical', 'maps_to_status' => 'sent'],
                ['key' => 'paid', 'label_ar' => 'مدفوعة', 'is_terminal' => true, 'maps_to_status' => 'paid'],
                ['key' => 'cancelled', 'label_ar' => 'ملغاة', 'is_terminal' => true, 'is_side' => true, 'maps_to_status' => 'cancelled'],
            ],
            'transitions' => [
                ['from' => 'draft', 'to' => 'pending_approval', 'requires_approval' => true],
                ['from' => 'pending_approval', 'to' => 'approved'],
                ['from' => 'approved', 'to' => 'sent', 'guard' => 'invoice_send'],
                ['from' => 'sent', 'to' => 'paid'],
            ],
            'side_from' => ['draft', 'pending_approval', 'approved', 'sent'],
            'side_to' => ['cancelled'],
            'status_map' => [
                'draft' => 'draft',
                'sent' => 'sent',
                'viewed' => 'sent',
                'partial' => 'sent',
                'paid' => 'paid',
                'overdue' => 'sent',
                'cancelled' => 'cancelled',
            ],
        ],
    ],

    'approval_rules' => [
        [
            'entity_type' => 'financial_invoice',
            'min_amount' => 0,
            'max_amount' => 4999.99,
            'steps' => [['role' => 'manager', 'label' => 'مدير القسم']],
        ],
        [
            'entity_type' => 'financial_invoice',
            'min_amount' => 5000,
            'max_amount' => 25000,
            'steps' => [
                ['role' => 'manager', 'label' => 'مدير القسم'],
                ['role' => 'accountant', 'label' => 'المالية'],
            ],
        ],
        [
            'entity_type' => 'financial_invoice',
            'min_amount' => 25000.01,
            'max_amount' => null,
            'steps' => [
                ['role' => 'manager', 'label' => 'مدير القسم'],
                ['role' => 'accountant', 'label' => 'المالية'],
                ['role' => 'super_admin', 'label' => 'المدير التنفيذي'],
            ],
        ],
    ],
];
