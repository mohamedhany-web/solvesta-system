# Solvesta ERP — خطة التدفق التجاري

## الرؤية

Solvesta ليست نظام مهام فقط — بل **ERP داخلي** لشركة برمجة وخدمات تقنية، يربط:

```
Lead → BD → Sales → Pre-Sales → Proposal → Contract → Finance → Project → PMO → Delivery → Collection → Client Success
```

**قاعدة ذهبية:** المشروع **لا يُنشأ ولا يبدأ** قبل تأكيد دفع الدفعة المقدمة.

---

## الهيكل الإداري (الأقسام)

| القسم | الدور في النظام |
|--------|------------------|
| CEO | لوحة تنفيذية — KPIs مجمّعة، لا 100 مهمة |
| Business Development | Leads، شراكات، فرص |
| Sales | Discovery، تأهيل، Pipeline |
| Pre-Sales / PMO | تقدير ساعات، Proposal |
| Development / Design | مهام، تقارير يومية |
| Finance | فواتير، مدفوعات، مستحقات |
| HR | حضور، KPI، عقوبات |
| Marketing | (مرحلة لاحقة) حملات مرتبطة بـ Leads |

---

## المراحل والوحدات

### ✅ المرحلة 1 — التدفق التجاري الأساسي (مُنفَّذ)

| المكوّن | الحالة |
|---------|--------|
| جدول `leads` + نموذج Lead | ✅ |
| تحويل طلب موقع → Lead | ✅ |
| Lead → Sale (إنشاء عميل تلقائي) | ✅ |
| تأهيل / رفض Sale (Qualification) | ✅ |
| إنشاء عقد من Sale مؤهّل | ✅ |
| فاتورة دفعة مقدمة 50% | ✅ |
| إنشاء مشروع **بعد** دفع الدفعة فقط | ✅ |
| لوحة CEO (`/executive`) | ✅ |
| لوحة workflow في صفحة البيع | ✅ |

**مسارات الاستخدام:**

1. `/support/contact-requests/{id}` → «تحويل إلى Lead»
2. `/leads` → إنشاء / متابعة → «تحويل إلى Sale»
3. `/sales/{id}` → تأهيل → عقد → فاتورة مقدمة
4. Finance يؤكد الدفع على الفاتورة → «بدء المشروع»
5. `/executive` — ملخص للـ CEO

---

### ✅ المرحلة 2 — Pre-Sales & Proposal (مُنفَّذ)

| المكوّن | الحالة |
|---------|--------|
| جدول `cost_estimations` | ✅ |
| جدول `proposals` | ✅ |
| `PreSalesService` — حساب ساعات/أسعار/هامش | ✅ |
| إصدار Proposal تلقائي من التقدير | ✅ |
| طابور Pre-Sales `/pre-sales` | ✅ |
| تسجيل إرسال / موافقة / رفض العرض | ✅ |
| ربط Workflow: لا عقد بدون Proposal مقبول | ✅ |
| تصميم موحّد مع لوحة التحكم (`erp-page-header`) | ✅ |

**مسار الاستخدام:**

1. تأهيل الفرصة في `/sales/{id}`
2. `/pre-sales/sales/{id}/estimate` — إدخال الساعات والشاشات
3. اعتماد التقدير → **إصدار Proposal تلقائي**
4. `/pre-sales/proposals/{id}` — طباعة وإرسال للعميل
5. **موافقة العميل** → إنشاء العقد

---

### ✅ المرحلة 3 — PMO & التنفيذ (مُنفَّذ)

| المكوّن | الحالة |
|---------|--------|
| `project_milestones` — 4 Phases افتراضية | ✅ |
| توزيع مهام على المراحل (تخصص + ساعات) | ✅ |
| `daily_reports` — تقرير يومي للموظف | ✅ |
| Blockers على المهام + التقارير | ✅ |
| لوحة PMO `/pmo` | ✅ |
| مراجعة Team Lead للتقارير | ✅ |
| تقدم المشروع من المراحل | ✅ |
| إنشاء Milestones تلقائياً عند بدء المشروع | ✅ |

---

### ✅ المرحلة 4 — Finance متقدم (مُنفَّذ)

| المكوّن | الحالة |
|---------|--------|
| `invoice_type` — deposit / delivery / standard | ✅ |
| فاتورة تسليم 50% تلقائياً عند اكتمال Milestones | ✅ |
| `project_id` على المصروفات | ✅ |
| تسجيل مصروفات من صفحة المشروع | ✅ |
| `ProjectFinanceService` — ربحية المشروع | ✅ |
| `/executive/finance` — تقارير CEO | ✅ |
| ربح تقديري في لوحة CEO | ✅ |

**مسار الاستخدام:**

1. اكتمال كل المراحل → **فاتورة تسليم 50%** تُنشأ تلقائياً
2. Finance يؤكد الدفع على فاتورة التسليم
3. من صفحة المشروع → تسجيل **مصروفات** مرتبطة بالمشروع
4. `/executive/finance` → ربحية كل مشروع + إجماليات

---

### ✅ المرحلة 5 — Performance & HR & BD (مُنفَّذ)

| المكوّن | الحالة |
|---------|--------|
| KPI شهري لكل موظف | ✅ |
| أوزان حسب الدور (مطور 20/40/20/20) | ✅ |
| تحذيرات HR + خصم KPI | ✅ |
| 3 تحذيرات → تحقيق HR | ✅ |
| BD شركاء + فرص → Lead | ✅ |

**ERP مكتمل.**

---

## الوحدات العشر (الخريطة الكاملة)

| # | الوحدة | المرحلة |
|---|--------|---------|
| 1 | CRM (Leads, Clients, Deals) | ✅ |
| 2 | Business Development | 5 ✅ |
| 3 | Sales Pipeline | ✅ |
| 4 | Contracts | ✅ |
| 5 | Finance | ✅ |
| 6 | Project Management | ✅ |
| 7 | Task Management | ✅ |
| 8 | HR | 5 ✅ |
| 9 | Performance & KPI | 5 ✅ |
| 10 | Executive Dashboard | ✅ |

---

## الصلاحيات الحالية (مؤقتة)

- Leads & Workflow: `view-sales`, `create-sales`, `edit-sales`
- لوحة CEO: `super_admin` أو `admin` فقط

---

## صيانة مقترحة

جدولة `hr.warnings.scan-overdue` و `kpi.recalculate` عبر Cron شهرياً.
