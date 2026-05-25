<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\ContactRequest as ContactRequestModel;
use App\Models\Training;
use App\Models\InternshipApplication;
use App\Models\JobPosting;
use App\Models\JobApplication;

class WebsiteController extends Controller
{
    private function caseStudiesData(): array
    {
        // Marketing demo data (replace with DB later if needed)
        return [
            [
                'slug' => 'construction-erp-workflows-ai-risk',
                'sector' => 'المقاولات',
                'title' => 'ERP تشغيلي للمشاريع + اعتمادات + تنبؤ مخاطر',
                'excerpt' => 'حوّلنا فوضى متابعة المشاريع إلى نظام تشغيل واضح مع AI للتنبؤ بالتأخير وتجاوز الميزانية.',
                'problem' => 'شركة مقاولات كانت تتابع المشاريع عبر Excel وWhatsApp. التأخيرات تُكتشف متأخرًا، ومسارات الاعتماد غير واضحة، والتكاليف تتجاوز الميزانية بدون إنذار مبكر.',
                'built' => [
                    'إدارة مشاريع ومهام ومراحل (Milestones)',
                    'مسارات اعتمادات للمشتريات والصرفيات',
                    'ربط الموردين + أوامر شراء + مخزون',
                    'لوحات مؤشرات مالية وتشغيلية (KPIs)',
                    'بوابة عميل لمتابعة التقدم والتقارير',
                ],
                'ai' => [
                    'Risk Scoring للمشاريع بناءً على الانحرافات (الوقت/التكلفة/التغيير)',
                    'تلخيص يومي آلي للتقدم من تحديثات الفريق',
                    'تنبيهات استباقية عند احتمالية التأخير أو تجاوز الميزانية',
                ],
                'outcomes' => [
                    'تقليل زمن الاعتماد الداخلي',
                    'رفع وضوح المسؤوليات ومسار القرار',
                    'تحسين دقة التوقعات وتقليل المفاجآت',
                ],
            ],
            [
                'slug' => 'retail-crm-invoicing-support-ai-assistant',
                'sector' => 'التجارة',
                'title' => 'CRM + فواتير + دعم ما بعد البيع مع AI مساعد',
                'excerpt' => 'ربطنا المبيعات بالفواتير والدعم، وبنينا مساعد AI لتصنيف البلاغات واقتراح الحلول.',
                'problem' => 'شركة تجارة B2B تعاني من تأخير التحصيل وتكرار الأخطاء بين المبيعات والحسابات، مع ضغط كبير على الدعم الفني بعد التسليم.',
                'built' => [
                    'إدارة العملاء والفرص والمراحل (Pipeline)',
                    'عروض أسعار + عقود + فواتير + تحصيل',
                    'تذاكر دعم مرتبطة بالعميل والفاتورة/العقد',
                    'تنبيهات WhatsApp/Email للمدفوعات والتذاكر',
                ],
                'ai' => [
                    'تصنيف التذاكر تلقائيًا حسب النوع والأولوية',
                    'اقتراح ردود/خطوات حل من قاعدة معرفة داخلية',
                    'اكتشاف العملاء المعرضين للتأخير في التحصيل (Churn/Risk)',
                ],
                'outcomes' => [
                    'تحصيل أسرع وتقليل الأخطاء',
                    'تقليل زمن الاستجابة للتذاكر',
                    'توحيد البيانات بين الأقسام',
                ],
            ],
            [
                'slug' => 'healthcare-governance-identity-audit-ai-anomaly',
                'sector' => 'الصحة',
                'title' => 'حوكمة صلاحيات + سجل تدقيق + AI لاكتشاف الشذوذ',
                'excerpt' => 'ثبّتنا الحوكمة والأمان: RBAC واعتمادات وسجل تدقيق، مع AI لاكتشاف أنماط وصول غير طبيعية.',
                'problem' => 'مخاطر وصول غير منضبط للبيانات الحساسة، وعدم وجود سجل تغييرات واضح، مع صعوبة تتبع من فعل ماذا ومتى.',
                'built' => [
                    'صلاحيات دقيقة (RBAC) حسب الدور والقسم',
                    'اعتمادات متعددة المستويات للعمليات الحساسة',
                    'Audit Log كامل للتغييرات والوصول',
                    'لوحات امتثال وتقارير دورية',
                ],
                'ai' => [
                    'Anomaly Detection لسلوكيات الوصول (Access Patterns)',
                    'تنبيهات عند محاولات وصول غير معتادة',
                    'تلخيص تقارير الامتثال تلقائيًا',
                ],
                'outcomes' => [
                    'تقليل المخاطر وتعزيز الامتثال',
                    'تتبع كامل لأي تغيير/وصول',
                    'شفافية أعلى للإدارة',
                ],
            ],
            [
                'slug' => 'hr-onboarding-attendance-payroll-ai-workforce',
                'sector' => 'الموارد البشرية',
                'title' => 'HR متكامل: تعيين + حضور + رواتب مع AI للتخطيط',
                'excerpt' => 'بنينا دورة موظف كاملة وربطناها بالتشغيل مع ذكاء اصطناعي لتوقع الضغط وتوزيع الموارد.',
                'problem' => 'توظيف وحضور ورواتب موزعة على أدوات متعددة، مما يسبب أخطاء في الرواتب وصعوبة تقييم الأداء والتخطيط.',
                'built' => [
                    'Onboarding + ملفات موظفين + صلاحيات',
                    'حضور وانصراف + إجازات + تنبيهات',
                    'رواتب واستحقاقات وخصومات وتقارير',
                    'لوحات أداء وربطها بالمشاريع والمهام',
                ],
                'ai' => [
                    'توقع الضغط التشغيلي بناءً على الطلب والمشاريع',
                    'اقتراح توزيع الموارد بين الفرق',
                    'ملخصات أداء أسبوعية تلقائية',
                ],
                'outcomes' => [
                    'تقليل أخطاء الرواتب',
                    'وضوح أعلى لتوزيع الموارد',
                    'قرارات أسرع مبنية على بيانات',
                ],
            ],
            [
                'slug' => 'finance-collections-approval-ai-cashflow',
                'sector' => 'المالية',
                'title' => 'تحصيل + اعتمادات + AI لتوقع التدفق النقدي',
                'excerpt' => 'ربطنا الفواتير بالتحصيل والاعتمادات وبنينا توقع Cashflow وتحذيرات مبكرة.',
                'problem' => 'عدم وضوح المتأخرات وتوقعات التدفق النقدي، مع اعتماد يدوي بطيء للمصروفات.',
                'built' => [
                    'فواتير + دفعات + أرصدة + متابعة متأخرات',
                    'اعتمادات مصروفات وربطها بالمشاريع',
                    'تقارير مالية ولوحات مؤشرات',
                ],
                'ai' => [
                    'توقع التدفق النقدي من الأنماط التاريخية',
                    'تحذير مبكر لمخاطر العجز',
                    'تصنيف العملاء حسب مخاطر التأخير',
                ],
                'outcomes' => [
                    'قرارات مالية أسرع',
                    'خفض التأخير في التحصيل',
                    'شفافية أعلى للمتأخرات',
                ],
            ],

            // ─────────────────────────────────────────────
            // More examples (enterprise rebuild scenarios)
            // ─────────────────────────────────────────────
            [
                'slug' => 'manufacturing-mrp-quality-ai-forecast',
                'sector' => 'التصنيع',
                'title' => 'MRP + جودة + صيانة مع AI للتنبؤ بالطلب',
                'excerpt' => 'حوّلنا تشغيل مصنع إلى منظومة تخطيط وإنتاج وجودة تربط الخطوط بالمخزون والطلبات.',
                'problem' => 'تضارب بين المبيعات والإنتاج والمخزون، وهدر في المواد بسبب ضعف التخطيط وغياب مؤشرات الجودة والصيانة.',
                'built' => [
                    'تخطيط مواد وإنتاج (MRP) وربطها بأوامر البيع',
                    'تتبع المخزون والدفعات (Batches) والصلاحية',
                    'إدارة الجودة (QC) ونقاط فحص على خطوط الإنتاج',
                    'صيانة وقائية + أعطال + قطع غيار',
                    'لوحات OEE ومؤشرات الأداء',
                ],
                'ai' => [
                    'تنبؤ الطلب Forecasting حسب الموسم والعملاء',
                    'توقع أعطال المعدات من سجلات الصيانة والاستهلاك',
                    'كشف انحرافات الجودة (Quality Anomalies) وتنبيهات',
                ],
                'outcomes' => [
                    'خفض الهدر وتحسين التخطيط',
                    'تحسين جودة المنتج وتقليل المرتجعات',
                    'رفع جاهزية المعدات وتقليل التوقفات',
                ],
            ],
            [
                'slug' => 'logistics-wms-route-ai-eta',
                'sector' => 'اللوجستيات',
                'title' => 'WMS + تتبع شحنات + تحسين مسارات مع AI',
                'excerpt' => 'أعدنا بناء تشغيل المخازن والتوصيل وربطنا كل شحنة بزمن متوقع وSLA.',
                'problem' => 'تأخير تسليمات بسبب مسارات غير محسوبة، وأخطاء مخزون، وعدم وضوح حالة الشحنات للعملاء.',
                'built' => [
                    'إدارة مخازن (WMS) مواقع/رفوف/استلام/صرف',
                    'تجهيز وشحن (Pick/Pack/Ship) مع باركود',
                    'تتبع شحنات وحالات (Tracking) + بوابة عميل',
                    'SLA للتسليم + تقارير تأخير',
                ],
                'ai' => [
                    'توقع ETA حسب حركة المرور والبيانات التاريخية',
                    'تحسين المسارات Route Optimization لتقليل الوقت والتكلفة',
                    'اكتشاف الشحنات المعرضة للفشل وإرسال تنبيه مبكر',
                ],
                'outcomes' => [
                    'رفع الالتزام بمواعيد التسليم',
                    'تقليل أخطاء المخزون والتجهيز',
                    'شفافية أعلى للعملاء عبر بوابة التتبع',
                ],
            ],
            [
                'slug' => 'realestate-crm-contracts-ai-leadscore',
                'sector' => 'العقارات',
                'title' => 'CRM عقاري + عقود + AI لتقييم العملاء المحتملين',
                'excerpt' => 'ربطنا التسويق بالمبيعات والعقود والتحصيل في نظام واحد مع Lead Scoring ذكي.',
                'problem' => 'ضياع فرص بسبب عدم تنظيم العملاء المحتملين وتكرار التواصل، وتشتت العقود والتحصيل بين عدة أدوات.',
                'built' => [
                    'إدارة Leads وقنوات التسويق والمراحل',
                    'حجوزات + عقود + دفعات وجدولة تحصيل',
                    'إدارة وحدات ومخزون عقاري (Availability)',
                    'تقارير أداء فرق المبيعات',
                ],
                'ai' => [
                    'Lead Scoring للتنبؤ بأفضل فرص الإغلاق',
                    'تلخيص محادثات العملاء واقتراح الخطوة التالية',
                    'اكتشاف أسباب فقدان الصفقة (Lost Reasons) وتحليلها',
                ],
                'outcomes' => [
                    'رفع جودة الفرص وتركيز الفريق',
                    'تحصيل أكثر انضباطًا',
                    'تقارير واضحة لمؤشرات المبيعات',
                ],
            ],
            [
                'slug' => 'hospitality-ops-guest-portal-ai-sentiment',
                'sector' => 'الضيافة',
                'title' => 'تشغيل الفنادق + بوابة ضيوف + AI لتحليل رضا العملاء',
                'excerpt' => 'حوّلنا خدمة الضيف والتشغيل الداخلي إلى منظومة تذاكر وتنبيهات وترتيب أولويات.',
                'problem' => 'طلبات الضيوف لا تُدار بشكل موحد، وتأخير في الاستجابة، وعدم وجود رؤية على جودة الخدمة بين الأقسام.',
                'built' => [
                    'طلبات خدمة الضيف (Service Requests) وتذاكر للأقسام',
                    'Housekeeping + صيانة + متابعة SLA',
                    'بوابة/QR للضيف لطلب الخدمات وتتبعها',
                    'لوحات رضا ومؤشرات الاستجابة',
                ],
                'ai' => [
                    'تحليل مشاعر (Sentiment) من تقييمات الضيوف',
                    'تصنيف الشكاوى واقتراح تعويض/حل مناسب',
                    'توقع فترات الضغط لتوزيع الموارد',
                ],
                'outcomes' => [
                    'استجابة أسرع وتحسن تجربة الضيف',
                    'تقليل تكرار الأعطال عبر الصيانة الوقائية',
                    'رؤية شاملة للجودة على مستوى الأقسام',
                ],
            ],
            [
                'slug' => 'education-sis-lms-ai-personalization',
                'sector' => 'التعليم',
                'title' => 'SIS + LMS عربي مع AI للتخصيص والتحليلات',
                'excerpt' => 'بناء منظومة تعليم كاملة: إدارة طلبة/مقررات/اختبارات + تحليلات تقدم ذكية.',
                'problem' => 'صعوبة متابعة الطلبة والمحتوى والاختبارات، وغياب مؤشرات مبكرة للتعثر.',
                'built' => [
                    'إدارة طلاب/صفوف/جداول/حضور',
                    'مقررات ومحتوى وواجبات واختبارات',
                    'بوابة ولي الأمر وتقارير دورية',
                    'تكاملات تنبيهات ورسائل',
                ],
                'ai' => [
                    'كشف مبكر للمتعثرين (Early Warning)',
                    'توصيات تعلم (Learning Recommendations)',
                    'تلخيص أداء الطالب للمعلم وولي الأمر',
                ],
                'outcomes' => [
                    'رفع نسبة الالتزام والمتابعة',
                    'قرارات أسرع للمعلمين والإدارة',
                    'تقارير واضحة لأولياء الأمور',
                ],
            ],
            [
                'slug' => 'government-workflow-approvals-ai-docs',
                'sector' => 'القطاع الحكومي',
                'title' => 'إجراءات ومعاملات + اعتمادات + AI لأرشفة وفهم المستندات',
                'excerpt' => 'رقمنة معاملات متعددة الأقسام مع أرشفة ذكية وتقارير امتثال.',
                'problem' => 'معاملات ورقية، بطء في اعتماد الطلبات، وصعوبة تتبع أين توقفت المعاملة.',
                'built' => [
                    'نماذج معاملات + مسارات اعتمادات متعددة المستويات',
                    'أرشفة مستندات وربطها بالمعاملات',
                    'تتبع حالة المعاملة ومؤشرات زمن المعالجة',
                    'صلاحيات دقيقة وسجل تدقيق',
                ],
                'ai' => [
                    'OCR واستخراج بيانات من المستندات تلقائيًا',
                    'تصنيف ملفات وأرشفة ذكية',
                    'تلخيص المعاملة وإبراز المخاطر/النواقص',
                ],
                'outcomes' => [
                    'خفض زمن المعاملة',
                    'شفافية في التتبع والمسؤولية',
                    'تقليل أخطاء الإدخال اليدوي',
                ],
            ],
            [
                'slug' => 'customer-support-knowledge-ai-rag',
                'sector' => 'خدمة العملاء',
                'title' => 'مركز دعم + قاعدة معرفة + AI (RAG) لاقتراح الحلول',
                'excerpt' => 'بنينا دعمًا منظمًا يسرّع الحل ويقلل الأخطاء عبر معرفة داخلية ذكية.',
                'problem' => 'تذاكر كثيرة، حلول متكررة، اعتماد على أفراد محددين، وتفاوت جودة الردود.',
                'built' => [
                    'تذاكر متعددة القنوات (Email/WhatsApp/Web)',
                    'تصنيف وأولوية وربط التذكرة بالعميل/الخدمة',
                    'قاعدة معرفة (Knowledge Base) وإجراءات حل',
                    'تقارير SLA وأداء الفريق',
                ],
                'ai' => [
                    'RAG لاقتراح خطوات حل من وثائق الشركة والتذاكر السابقة',
                    'تلخيص المحادثة وكتابة رد جاهز قابل للتحرير',
                    'كشف التذاكر المتشابهة ودمجها',
                ],
                'outcomes' => [
                    'تقليل زمن الحل',
                    'رفع اتساق جودة الدعم',
                    'تخفيف الضغط على الخبراء',
                ],
            ],
            [
                'slug' => 'ecommerce-returns-fraud-ai',
                'sector' => 'التجارة الإلكترونية',
                'title' => 'إدارة مرتجعات + سياسات + AI لاكتشاف الاحتيال',
                'excerpt' => 'أعدنا بناء دورة المرتجعات وربطناها بالمخزون والمالية مع تحذيرات احتيال.',
                'problem' => 'مرتجعات غير منضبطة، فقد مخزون، واحتيال في الاسترجاع يسبب خسائر.',
                'built' => [
                    'RMA (طلبات مرتجع) + سياسات استرجاع',
                    'ربط المرتجع بالمخزون والفاتورة والعميل',
                    'أتمتة حالات الموافقة/الرفض وإشعارات',
                    'تقارير خسائر وأسباب المرتجعات',
                ],
                'ai' => [
                    'Fraud Signals لاكتشاف أنماط استرجاع مشبوهة',
                    'تصنيف أسباب المرتجع تلقائيًا',
                    'توقع المنتجات الأكثر عرضة للمرتجع',
                ],
                'outcomes' => [
                    'تقليل خسائر المرتجعات',
                    'سرعة معالجة الطلبات',
                    'رؤية أوضح لجودة المنتجات',
                ],
            ],
            [
                'slug' => 'field-service-maintenance-ai-dispatch',
                'sector' => 'الخدمات الميدانية',
                'title' => 'إدارة فرق ميدانية + جدولة + AI لتوزيع البلاغات',
                'excerpt' => 'حوّلنا بلاغات الأعطال إلى تشغيل ميداني منظم مع جدولة وتوجيه ذكي.',
                'problem' => 'تأخر وصول الفنيين، توزيع غير عادل للبلاغات، وعدم وجود تاريخ أعطال منظم لكل موقع.',
                'built' => [
                    'بلاغات أعطال مرتبطة بالموقع والعميل',
                    'جدولة زيارات + خرائط + حالات',
                    'سجل صيانة لكل أصل/معدة',
                    'تقارير SLA ومناطق الضغط',
                ],
                'ai' => [
                    'Dispatch Optimization لتوجيه الفني الأنسب حسب الموقع والمهارة',
                    'توقع الأعطال المتكررة واقتراح صيانة وقائية',
                    'تلخيص التقرير الفني تلقائيًا للعميل',
                ],
                'outcomes' => [
                    'استجابة أسرع للبلاغات',
                    'رفع إنتاجية الفرق الميدانية',
                    'تقارير واضحة للعميل',
                ],
            ],
            [
                'slug' => 'procurement-spend-control-ai-policy',
                'sector' => 'المشتريات',
                'title' => 'مشتريات + سياسات إنفاق + AI لاكتشاف الانحراف',
                'excerpt' => 'بناء دورة مشتريات منضبطة تمنع الإنفاق العشوائي وتزيد الشفافية.',
                'problem' => 'مشتريات خارج السياسة، أسعار غير تنافسية، وفقد مستندات واعتمادات.',
                'built' => [
                    'طلبات شراء + عروض + مقارنة أسعار',
                    'اعتمادات حسب السقف المالي والقسم',
                    'إدارة موردين وتقييم أداء',
                    'أرشفة مستندات وإثباتات',
                ],
                'ai' => [
                    'كشف انحرافات الإنفاق عن السياسة (Spend Anomalies)',
                    'اقتراح موردين بدائل من بيانات سابقة',
                    'تلخيص بنود العروض ومخاطرها',
                ],
                'outcomes' => [
                    'انضباط أعلى للإنفاق',
                    'خفض الهدر وتحسين التفاوض',
                    'تتبع كامل للمستندات والاعتمادات',
                ],
            ],
            [
                'slug' => 'cyber-iam-sso-ai-phishing',
                'sector' => 'الأمن السيبراني',
                'title' => 'IAM + SSO + سياسات وصول مع AI لمخاطر التصيّد',
                'excerpt' => 'بناء هوية وصلاحيات مؤسسية مع تقارير ومؤشرات لمخاطر الوصول.',
                'problem' => 'حسابات متعددة، كلمات مرور ضعيفة، وعدم توحيد الوصول للتطبيقات، مع مخاطر تصيّد.',
                'built' => [
                    'هوية مركزية + أدوار وصلاحيات',
                    'SSO للتطبيقات الداخلية',
                    'سياسات MFA وتسجيل الدخول',
                    'تقارير وصول وتغييرات',
                ],
                'ai' => [
                    'كشف محاولات تسجيل دخول مشبوهة',
                    'تقييم رسائل/روابط محتملة للتصيّد (Security Signals)',
                    'تنبيهات سياقية للإدارة',
                ],
                'outcomes' => [
                    'خفض مخاطر الوصول غير المصرح',
                    'توحيد الهوية والصلاحيات',
                    'رؤية أوضح للأمن',
                ],
            ],
        ];
    }

    public function home(Request $request)
    {
        return view('website.home', [
            'cinematicServices' => [
                ['title' => 'AI Automation Systems', 'desc' => 'Workflows that learn, route, and execute across your entire enterprise stack.', 'accent' => 'blue'],
                ['title' => 'Enterprise Software Development', 'desc' => 'Custom platforms built for scale, security, and data sovereignty.', 'accent' => 'orange'],
                ['title' => 'CRM & ERP Systems', 'desc' => 'Unify sales, finance, inventory, and operations in one system.', 'accent' => 'blue'],
                ['title' => 'AI Agents & Chatbots', 'desc' => 'Copilots trained on your policies, data, and support playbooks.', 'accent' => 'orange'],
                ['title' => 'Data Intelligence Platforms', 'desc' => 'Dashboards, forecasting, and live decision layers on operational data.', 'accent' => 'blue'],
                ['title' => 'Workflow Automation', 'desc' => 'Approvals, triggers, and cross-department orchestration without manual bottlenecks.', 'accent' => 'orange'],
                ['title' => 'Infrastructure Engineering', 'desc' => 'Cloud, DevOps, observability, and resilient deployment pipelines.', 'accent' => 'blue'],
                ['title' => 'Digital Transformation Consulting', 'desc' => 'Roadmaps that rebuild how your company operates — not just its tools.', 'accent' => 'orange'],
            ],
            'processSteps' => [
                ['n' => '01', 'title' => 'Analyze Business Systems', 'desc' => 'Map pain points, data flows, and operational bottlenecks across departments.'],
                ['n' => '02', 'title' => 'Design AI Architecture', 'desc' => 'Define permissions, integrations, and intelligence layers tied to business goals.'],
                ['n' => '03', 'title' => 'Build Scalable Infrastructure', 'desc' => 'Phased delivery with enterprise-grade quality and clear testing.'],
                ['n' => '04', 'title' => 'Integrate Automation & AI', 'desc' => 'Connect AI, APIs, and teams into one operating model.'],
                ['n' => '05', 'title' => 'Deploy & Optimize', 'desc' => 'Measure performance, SLA, and continuous improvement from real usage.'],
            ],
            'pillars' => [
                ['title' => 'We Build Operations', 'desc' => 'Documented processes, approval paths, and clear ownership — not daily chaos.'],
                ['title' => 'We Connect Departments', 'desc' => 'Unified data, live reports, and integration with your existing systems.'],
                ['title' => 'We Ensure Continuity', 'desc' => 'Phased rollout, training, support, and optimization — the system grows with you.'],
            ],
            'sectors' => ['Construction', 'Retail', 'Healthcare', 'Finance', 'Manufacturing', 'Education', 'Logistics', 'Professional Services'],
            'platformModules' => [
                ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'title' => 'Projects & Tasks', 'desc' => 'Planning, milestones, team assignments, and real-time progress tracking.'],
                ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'Human Resources', 'desc' => 'Employees, attendance, leave, payroll, and performance tied to operations.'],
                ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1', 'title' => 'Accounting & Invoicing', 'desc' => 'Invoices, collections, journal entries, and financial reports.'],
                ['icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'title' => 'Sales & CRM', 'desc' => 'Clients, pipeline stages, deals, and contract-to-invoice linkage.'],
                ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'title' => 'Client Portal', 'desc' => 'Projects, invoices, support tickets, website issues, and meeting requests.'],
                ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Permissions & Audit', 'desc' => 'Granular roles, approvals, and audit logs for every sensitive action.'],
            ],
        ]);
    }

    public function about()
    {
        return view('website.about', [
            'pillars' => [
                ['title' => 'We Build Operations', 'desc' => 'Documented processes, approval paths, and clear ownership — not daily chaos.'],
                ['title' => 'We Connect Departments', 'desc' => 'Unified data, live reports, and integration with your existing systems.'],
                ['title' => 'We Ensure Continuity', 'desc' => 'Phased rollout, training, support, and optimization — the system grows with you.'],
            ],
            'capabilities' => [
                ['label' => 'Governance & Access', 'title' => 'Roles + Approvals + Audit', 'desc' => 'Granular permissions and decision paths that reduce risk and increase discipline.'],
                ['label' => 'Data & Reporting', 'title' => 'Live, Measurable KPIs', 'desc' => 'Dashboards that show operational truth — not guesswork.'],
                ['label' => 'Integrations & Ops', 'title' => 'One Connected Loop', 'desc' => 'Sales, finance, support, and client portal in a single flow.'],
                ['label' => 'AI in Execution', 'title' => 'Classify · Predict · Summarize', 'desc' => 'Intelligence that serves decisions and operations — not a checkbox feature.'],
            ],
            'principles' => [
                ['label' => 'Enterprise delivery', 'title' => 'Roadmap + Phased Launch', 'desc' => 'Ship fast, then expand in stages — without disrupting critical operations.'],
                ['label' => 'Clear measurement', 'title' => 'KPIs + Reporting', 'desc' => 'Every decision and deliverable is trackable and accountable.'],
                ['label' => 'Continuity', 'title' => 'SLA + Post-Delivery Support', 'desc' => 'Structured tickets, department routing, and professional reporting through closure.'],
            ],
        ]);
    }

    public function services()
    {
        return view('website.services', [
            'coreServices' => [
                ['title' => 'AI Automation Systems', 'desc' => 'Workflows that learn, route, and execute across your entire enterprise stack.', 'accent' => 'blue'],
                ['title' => 'Enterprise Software Development', 'desc' => 'Custom platforms built for scale, security, and data sovereignty.', 'accent' => 'orange'],
                ['title' => 'CRM & ERP Systems', 'desc' => 'Unify sales, finance, inventory, and operations in one system.', 'accent' => 'blue'],
                ['title' => 'AI Agents & Chatbots', 'desc' => 'Copilots trained on your policies, data, and support playbooks.', 'accent' => 'orange'],
                ['title' => 'Data Intelligence Platforms', 'desc' => 'Dashboards, forecasting, and live decision layers on operational data.', 'accent' => 'blue'],
                ['title' => 'Workflow Automation', 'desc' => 'Approvals, triggers, and cross-department orchestration without manual bottlenecks.', 'accent' => 'orange'],
                ['title' => 'Infrastructure Engineering', 'desc' => 'Cloud, DevOps, observability, and resilient deployment pipelines.', 'accent' => 'blue'],
                ['title' => 'Digital Transformation Consulting', 'desc' => 'Roadmaps that rebuild how your company operates — not just its tools.', 'accent' => 'orange'],
            ],
            'engineeringServices' => [
                ['title' => 'Digital Transformation & Architecture', 'desc' => 'Realistic diagnostics, roadmaps, HLD/LLD design, and integration standards aligned with your policies and org structure.', 'icon' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z'],
                ['title' => 'Enterprise Apps (Web, Portals, Field Mobile)', 'desc' => 'Interfaces for staff, partners, and clients — performance under load, clear UX, and field teams when needed.', 'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ['title' => 'APIs & System Integration', 'desc' => 'Connect ERP/CRM/finance/inventory/payment gateways — less duplicate entry, unified data flow, cross-system monitoring.', 'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1'],
                ['title' => 'Modernization & Data Migration', 'desc' => 'Move off paper, Excel, and fragile apps with controlled migration, data cleansing, and phased sync without stopping critical ops.', 'icon' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                ['title' => 'Cloud, DevOps & Observability', 'desc' => 'Production and staging environments, CI/CD, backups, scaling, live monitoring, and recovery policies for your scale.', 'icon' => 'M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01'],
                ['title' => 'Security, Permissions & Compliance', 'desc' => 'Roles, approval paths, encryption, access controls, audit logs — supporting internal teams, auditors, and partners.', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
            ],
            'aiServices' => [
                ['title' => 'AI Strategy & Governance', 'desc' => 'High-ROI use cases, training/usage data policies, explainability where required, and safe model boundaries inside your org.', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                ['title' => 'Document Intelligence (NLP / OCR)', 'desc' => 'Extract fields from contracts, invoices, and requests — classify, index, and match to your systems to cut manual work and errors.', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                ['title' => 'Enterprise Copilots', 'desc' => 'Assistants on your data and policies: cited answers, drafts, summaries, and procedural guidance — not generic chat divorced from work.', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
                ['title' => 'Predictive & Classification Models', 'desc' => 'Demand, risk, quality, or operations models embedded in dashboards and workflows — not isolated reports.', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-4 0a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ['title' => 'Intelligent Support & Service', 'desc' => 'Auto-classification, routing, policy-bound first responses, and continuous improvement via satisfaction and resolution metrics.', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                ['title' => 'Data Platforms, BI & Executive Reporting', 'desc' => 'Warehouses, unified metrics, leadership dashboards, and exports to governance systems — one source of truth.', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
            ],
            'transformationSteps' => [
                ['n' => '01', 'title' => 'Realistic Diagnostics', 'text' => 'Map what happens today: who does what, where information is lost, and operational/financial risks.'],
                ['n' => '02', 'title' => 'Short-Term Priorities', 'text' => 'Start with one high-impact path (e.g. invoicing, inventory, or client tracking) — not digitizing everything at once.'],
                ['n' => '03', 'title' => 'Clear Foundation System', 'text' => 'Database, permissions, and audit trail — legacy data preserved and referenced in an organized way.'],
                ['n' => '04', 'title' => 'Training & Habits', 'text' => 'The system lives with staff; simple sessions and support so teams don\'t revert to paper because tools feel hard.'],
                ['n' => '05', 'title' => 'Expand & Add Intelligence', 'text' => 'After stability, more integrations or AI layers where they improve productivity and quality.'],
            ],
            'platformModules' => [
                ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'title' => 'Projects & Tasks', 'desc' => 'Progress tracking, task assignment, performance reports, and role-based access.'],
                ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'Human Resources', 'desc' => 'Employees, attendance, leave, payroll, and HR analytics.'],
                ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1', 'title' => 'Invoices & Payments', 'desc' => 'Standard and financial invoices, balance tracking, and payment status updates.'],
                ['icon' => 'M12 11c0 1.656-1.791 3-4 3s-4-1.344-4-3 1.791-3 4-3 4 1.344 4 3zm8 0c0 1.656-1.791 3-4 3s-4-1.344-4-3 1.791-3 4-3 4 1.344 4 3z', 'title' => 'Client Portal', 'desc' => 'Clients view their data, projects, invoices, and submit support requests.'],
                ['icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z', 'title' => 'Post-Sale Support', 'desc' => 'Tickets, staff assignment, and structured client communication.'],
                ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-4 0a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 012-2h2a2 2 0 012 2v12a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'Reports & Analytics', 'desc' => 'Operational and financial KPIs — printable and exportable reports.'],
            ],
        ]);
    }

    public function pricing()
    {
        return view('website.pricing', [
            'tracks' => [
                ['title' => 'Operations System (ERP/CRM)', 'desc' => 'Projects, tasks, teams, approvals, and operational reporting.'],
                ['title' => 'Finance & Collections', 'desc' => 'Invoices, payments, balances, reports, and overdue tracking.'],
                ['title' => 'Client Portal', 'desc' => 'Secure access for clients to follow projects, invoices, and support.'],
                ['title' => 'Post-Sale Support + SLA', 'desc' => 'Structured tickets, department routing, reporting through closure.'],
                ['title' => 'Governance & Permissions', 'desc' => 'RBAC, audit trail, access policies, and decision paths.'],
                ['title' => 'AI Inside the System', 'desc' => 'Classification, summarization, forecasting, and proactive alerts.'],
            ],
            'infrastructure' => [
                ['title' => 'Identity & Access', 'desc' => 'RBAC, granular permissions, and audit logs.'],
                ['title' => 'Data Model', 'desc' => 'Single source of truth for client, project, invoice, and ticket.'],
                ['title' => 'Integrations', 'desc' => 'APIs, webhooks, WhatsApp/Email/ERP/CRM connections.'],
                ['title' => 'Operations', 'desc' => 'Monitoring, backups, deployment and update policies.'],
            ],
            'deliverables' => [
                ['bold' => 'System Map', 'text' => 'What to keep, integrate, or build from scratch.'],
                ['bold' => 'Simplified Data Model', 'text' => 'Core entities and relationships (Client/Project/Invoice/Ticket).'],
                ['bold' => 'Permissions & Policies', 'text' => 'Who sees what, who approves what — realistic decision paths.'],
                ['bold' => 'Initial KPI Dashboard', 'text' => 'Operations, finance, and support metrics to measure early.'],
            ],
            'methodology' => [
                ['bold' => 'Discovery & diagnostics', 'text' => 'Quick interviews, process analysis, bottleneck mapping (time/cost/risk).'],
                ['bold' => 'Architecture & operations design', 'text' => 'Domain model, RBAC, integration specs, data plan (migration/cleanup).'],
                ['bold' => 'Phased build & launch', 'text' => 'Real-world MVP, then sprint-based expansion with training and measurement.'],
                ['bold' => 'Run & optimize', 'text' => 'Monitoring, SLA, management reporting, data-driven improvements.'],
            ],
            'examples' => [
                ['t' => 'Approval Workflows', 'd' => 'Purchases, expenses, and scope changes with a clear decision log.'],
                ['t' => 'B2B Client Portal', 'd' => 'Progress, reports, invoices, and ticket creation in seconds with SLA.'],
                ['t' => 'Ops–Finance Bridge', 'd' => 'Link projects to spend and collections for live profitability.'],
                ['t' => 'Critical Alert Integrations', 'd' => 'WhatsApp/Email for alerts, approvals, and status updates.'],
                ['t' => 'Governance & Security', 'd' => 'RBAC, audit, department/project access, compliance reporting.'],
                ['t' => 'AI for Faster Decisions', 'd' => 'Team update summaries, ticket classification, proactive delay alerts.'],
            ],
        ]);
    }

    /**
     * Public training / internship landing page (marketing).
     */
    public function training()
    {
        $trainings = Training::query()
            ->whereIn('status', ['planned', 'ongoing'])
            ->orderBy('start_date')
            ->get();

        return view('website.training', compact('trainings'));
    }

    public function trainingShow(Training $training)
    {
        abort_unless(in_array($training->status, ['planned', 'ongoing'], true), 404);
        $training->load(['department', 'instructor']);

        return view('website.training-show', compact('training'));
    }

    public function trainingApply(Request $request, Training $training)
    {
        abort_unless(in_array($training->status, ['planned', 'ongoing'], true), 404);

        $data = $request->validate([
            'full_name' => 'required|string|max:190',
            'email' => [
                'required',
                'email',
                'max:190',
                Rule::unique('internship_applications', 'email')->where('training_id', $training->id),
            ],
            'phone' => 'nullable|string|max:60',
            'university' => 'nullable|string|max:190',
            'major' => 'nullable|string|max:190',
            'level' => 'nullable|string|max:190',
            'linkedin_url' => 'nullable|string|max:500',
            'portfolio_url' => 'nullable|string|max:500',
            'message' => 'nullable|string|max:5000',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ], [
            'email.unique' => 'An application with this email already exists for this program.',
        ]);

        foreach (['linkedin_url', 'portfolio_url'] as $urlField) {
            $v = isset($data[$urlField]) ? trim((string) $data[$urlField]) : '';
            if ($v === '') {
                $data[$urlField] = null;
            } elseif (! filter_var($v, FILTER_VALIDATE_URL)) {
                return back()->withInput()->withErrors([$urlField => 'يرجى إدخال رابط صحيح يبدأ بـ http:// أو https:// أو ترك الحقل فارغًا.']);
            } else {
                $data[$urlField] = $v;
            }
        }

        $cvPath = null;
        if ($request->hasFile('cv') && $request->file('cv')->isValid()) {
            $cvPath = $request->file('cv')->store('internships/cv', 'public');
        }

        InternshipApplication::create([
            'training_id' => $training->id,
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'university' => $data['university'] ?? null,
            'major' => $data['major'] ?? null,
            'level' => $data['level'] ?? null,
            'linkedin_url' => $data['linkedin_url'] ?? null,
            'portfolio_url' => $data['portfolio_url'] ?? null,
            'message' => $data['message'] ?? null,
            'cv_path' => $cvPath,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your application was received. We will contact you shortly.');
    }

    public function careers()
    {
        $jobs = JobPosting::query()
            ->published()
            ->with('department')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->get();

        return view('website.careers', compact('jobs'));
    }

    public function careerShow(Request $request, string $slug)
    {
        $job = JobPosting::query()
            ->where('slug', $slug)
            ->published()
            ->with('department')
            ->firstOrFail();

        $appliedCookie = 'career_applied_'.$job->id;
        $hideApplicationForm = session($appliedCookie)
            || $request->cookie($appliedCookie) === '1'
            || session('application_submitted_'.$job->id);

        return view('website.career-show', compact('job', 'hideApplicationForm'));
    }

    private function careerAppliedCookieKey(int $jobPostingId): string
    {
        return 'career_applied_'.$jobPostingId;
    }

    public function careerApply(Request $request, string $slug)
    {
        $job = JobPosting::query()
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        $data = $request->validate([
            'full_name' => 'required|string|max:190',
            'email' => [
                'required',
                'email',
                'max:190',
                Rule::unique('job_applications', 'email')->where('job_posting_id', $job->id),
            ],
            'phone' => 'nullable|string|max:60',
            'linkedin_url' => 'nullable|string|max:500',
            'portfolio_url' => 'nullable|string|max:500',
            'message' => 'nullable|string|max:5000',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ], [
            'email.unique' => 'An application with this email already exists for this position.',
        ]);

        foreach (['linkedin_url', 'portfolio_url'] as $urlField) {
            $v = isset($data[$urlField]) ? trim((string) $data[$urlField]) : '';
            if ($v === '') {
                $data[$urlField] = null;
            } elseif (! filter_var($v, FILTER_VALIDATE_URL)) {
                return back()->withInput()->withErrors([$urlField => 'Please enter a valid URL or leave the field empty.']);
            } else {
                $data[$urlField] = $v;
            }
        }

        $cvPath = null;
        if ($request->hasFile('cv') && $request->file('cv')->isValid()) {
            $cvPath = $request->file('cv')->store('careers/cv', 'public');
        }

        JobApplication::create([
            'job_posting_id' => $job->id,
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'linkedin_url' => $data['linkedin_url'] ?? null,
            'portfolio_url' => $data['portfolio_url'] ?? null,
            'message' => $data['message'] ?? null,
            'cv_path' => $cvPath,
            'status' => 'pending',
        ]);

        $cookieKey = $this->careerAppliedCookieKey($job->id);
        session([
            $cookieKey => true,
            'application_submitted_'.$job->id => true,
        ]);

        return redirect()
            ->route('website.careers.show', $job->slug)
            ->with('success', 'Your application was received. We will contact you shortly.')
            ->withCookie(cookie($cookieKey, '1', 60 * 24 * 90));
    }

    public function contact()
    {
        return view('website.contact');
    }

    public function submitContact(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:contact,consultation',
            'name' => 'required|string|max:190',
            'email' => 'nullable|email|max:190',
            'phone' => 'nullable|string|max:60',
            'company' => 'nullable|string|max:190',
            'subject' => 'nullable|string|max:190',
            'message' => 'required|string|max:5000',
        ]);

        ContactRequestModel::create([
            ...$data,
            'status' => 'new',
            'source_url' => $request->headers->get('referer'),
            'ip' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        return back()->with('success', 'Your request was received. The Solvesta team will contact you shortly.');
    }

    public function caseStudies()
    {
        return view('website.case-studies.index', [
            'caseStudies' => $this->caseStudiesData(),
        ]);
    }

    public function caseStudyShow(string $slug)
    {
        $case = collect($this->caseStudiesData())->firstWhere('slug', $slug);
        abort_unless($case, 404);

        return view('website.case-studies.show', [
            'case' => $case,
            'caseStudies' => $this->caseStudiesData(),
        ]);
    }
}

