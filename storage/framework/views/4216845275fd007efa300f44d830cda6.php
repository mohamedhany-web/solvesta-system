

<?php $__env->startSection('page-title', 'بوابة العميل'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full max-w-full">
    <div class="mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 sm:p-8 border border-blue-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 1.656-1.791 3-4 3s-4-1.344-4-3 1.791-3 4-3 4 1.344 4 3zm8 0c0 1.656-1.791 3-4 3s-4-1.344-4-3 1.791-3 4-3 4 1.344 4 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 20v-1a5 5 0 0110 0v1M14 20v-1a5 5 0 0110 0v1" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1 truncate">بوابة العميل</h1>
                        <p class="text-gray-600 text-sm sm:text-base">مرحباً <?php echo e($client->name); ?></p>
                    </div>
                </div>
                <div class="text-sm text-gray-700 bg-white/60 rounded-lg px-4 py-2 border border-blue-100">
                    <span class="font-semibold">الشركة:</span> <?php echo e($client->company_name ?? $client->company ?? '—'); ?>

                </div>
            </div>
        </div>
    </div>

    <?php if($clientAccount && $clientAccount->portalRole() !== 'owner'): ?>
        <div class="mb-6 rounded-xl border border-indigo-200 bg-indigo-50 px-4 py-3 text-sm text-indigo-900">
            <span class="font-semibold">نوع الحساب:</span>
            <?php if($clientAccount->portalRole() === 'billing'): ?> محاسبي — يظهر لك قسم الفواتير والمستندات دون طلبات تقنية.
            <?php elseif($clientAccount->portalRole() === 'technical'): ?> تقني — يظهر لك الدعم وبلاغات الموقع وطلبات الاجتماع دون تفاصيل الفواتير المالية.
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php $needsAction = $ticketsAwaitingClient + $invoicesUnpaidCount + $financialUnpaidCount + $meetingPendingReview; ?>
    <?php if($needsAction > 0): ?>
        <div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 p-5 sm:p-6">
            <h2 class="text-base font-bold text-amber-950 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                يحتاج إجراء منك
            </h2>
            <ul class="space-y-2 text-sm text-amber-950">
                <?php if($ticketsAwaitingClient > 0): ?>
                    <li class="flex flex-wrap items-center justify-between gap-2"><span>تذاكر في انتظار ردك: <strong><?php echo e($ticketsAwaitingClient); ?></strong></span><a href="<?php echo e(route('client.support.tickets')); ?>" class="text-blue-700 font-semibold hover:underline">عرض التذاكر</a></li>
                <?php endif; ?>
                <?php if($clientAccount->canAccessBilling() && ($invoicesUnpaidCount + $financialUnpaidCount) > 0): ?>
                    <li class="flex flex-wrap items-center justify-between gap-2"><span>فواتير غير مدفوعة: <strong><?php echo e($invoicesUnpaidCount + $financialUnpaidCount); ?></strong></span><a href="<?php echo e(route('client.invoices')); ?>" class="text-blue-700 font-semibold hover:underline">الفواتير</a></li>
                <?php endif; ?>
                <?php if($clientAccount->canAccessTechnicalRequests() && $meetingPendingReview > 0): ?>
                    <li class="flex flex-wrap items-center justify-between gap-2"><span>طلبات اجتماع قيد المراجعة: <strong><?php echo e($meetingPendingReview); ?></strong></span><a href="<?php echo e(route('client.meeting-requests.index')); ?>" class="text-blue-700 font-semibold hover:underline">الطلبات</a></li>
                <?php endif; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if($notificationsUnread > 0): ?>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3">
            <span class="text-sm font-semibold text-blue-900">لديك <?php echo e($notificationsUnread); ?> إشعار غير مقروء</span>
            <a href="<?php echo e(route('client.notifications')); ?>" class="text-sm font-bold text-blue-700 hover:underline">فتح الإشعارات</a>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">المشاريع</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($projectsCount); ?></p>
                </div>
                <div class="p-3 bg-blue-100 rounded-xl">
                    <svg class="w-7 h-7 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                    </svg>
                </div>
            </div>
            <a href="<?php echo e(route('client.projects')); ?>" class="mt-4 inline-flex text-sm font-medium text-blue-700 hover:text-blue-900">عرض المشاريع →</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">تذاكر الدعم (مفتوحة)</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($ticketsOpenCount); ?></p>
                </div>
                <div class="p-3 bg-amber-100 rounded-xl">
                    <svg class="w-7 h-7 text-amber-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-1.414-1.414a2 2 0 00-2.828 0L7 11.343V15h3.657l7.707-7.707a2 2 0 000-2.828z" />
                    </svg>
                </div>
            </div>
            <a href="<?php echo e(route('client.support.tickets')); ?>" class="mt-4 inline-flex text-sm font-medium text-blue-700 hover:text-blue-900">عرض التذاكر →</a>
        </div>

        <?php if($clientAccount->canAccessBilling()): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">فواتير (عادية)</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($invoicesCount); ?></p>
                    <p class="text-xs text-gray-500 mt-1">متبقي: <?php echo e(number_format($invoicesUnpaidAmount)); ?></p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-xl">
                    <svg class="w-7 h-7 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <a href="<?php echo e(route('client.invoices')); ?>" class="mt-4 inline-flex text-sm font-medium text-blue-700 hover:text-blue-900">عرض الفواتير →</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">فواتير (مالية)</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($financialInvoicesCount); ?></p>
                    <p class="text-xs text-gray-500 mt-1">متبقي: <?php echo e(number_format($financialInvoicesUnpaidAmount)); ?></p>
                </div>
                <div class="p-3 bg-purple-100 rounded-xl">
                    <svg class="w-7 h-7 text-purple-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
            </div>
            <a href="<?php echo e(route('client.invoices')); ?>" class="mt-4 inline-flex text-sm font-medium text-blue-700 hover:text-blue-900">عرض الفواتير →</a>
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">تقارير الخدمة</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($serviceReportsCount); ?></p>
                </div>
                <div class="p-3 bg-violet-100 rounded-xl">
                    <svg class="w-7 h-7 text-violet-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <a href="<?php echo e(route('client.service-reports')); ?>" class="mt-4 inline-flex text-sm font-medium text-blue-700 hover:text-blue-900">عرض التقارير →</a>
        </div>

        <?php if($clientAccount->canAccessTechnicalRequests()): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">بلاغات الموقع (مفتوحة)</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($websiteIssuesOpenCount); ?></p>
                </div>
                <div class="p-3 bg-orange-100 rounded-xl">
                    <svg class="w-7 h-7 text-orange-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <a href="<?php echo e(route('client.website-issues.index')); ?>" class="mt-4 inline-flex text-sm font-medium text-blue-700 hover:text-blue-900">عرض البلاغات →</a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600 mb-1">طلبات اجتماع (نشطة)</p>
                    <p class="text-3xl font-bold text-gray-900"><?php echo e($meetingRequestsActiveCount); ?></p>
                </div>
                <div class="p-3 bg-cyan-100 rounded-xl">
                    <svg class="w-7 h-7 text-cyan-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <a href="<?php echo e(route('client.meeting-requests.index')); ?>" class="mt-4 inline-flex text-sm font-medium text-blue-700 hover:text-blue-900">عرض الطلبات →</a>
        </div>
        <?php endif; ?>
    </div>

    
    <section class="mb-8" aria-labelledby="client-analytics-heading">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-6">
            <div>
                <h2 id="client-analytics-heading" class="text-xl sm:text-2xl font-bold text-gray-900">تحليلات وإحصائيات</h2>
                <p class="text-sm text-gray-600 mt-1">نظرة تحليلية على نشاطك وخدماتك خلال آخر 6 أشهر</p>
            </div>
            <div class="flex flex-wrap gap-2 text-xs sm:text-sm">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-50 text-blue-800 px-3 py-1.5 font-semibold border border-blue-100">
                    نسبة حل التذاكر: <strong><?php echo e($chartData['kpis']['ticketResolutionRate']); ?>%</strong>
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 text-emerald-800 px-3 py-1.5 font-semibold border border-emerald-100">
                    مشاريع نشطة: <strong><?php echo e($chartData['kpis']['activeProjects']); ?></strong>
                </span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 text-amber-800 px-3 py-1.5 font-semibold border border-amber-100">
                    تذاكر مفتوحة: <strong><?php echo e($chartData['kpis']['openTickets']); ?></strong>
                </span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between gap-4 mb-5">
                <h3 class="text-lg font-bold text-gray-900">النشاط الشهري</h3>
                <p class="text-xs text-gray-500">تذاكر · تقارير · <?php if($clientAccount->canAccessTechnicalRequests()): ?>بلاغات · اجتماعات · ميزات <?php endif; ?></p>
            </div>
            <div class="h-72 sm:h-80">
                <canvas id="clientActivityChart" aria-label="مخطط النشاط الشهري"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">توزيع تذاكر الدعم</h3>
                <div class="h-56">
                    <canvas id="clientTicketsChart"></canvas>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">حالة المشاريع</h3>
                <div class="h-56">
                    <canvas id="clientProjectsChart"></canvas>
                </div>
            </div>
            <?php if($clientAccount->canAccessTechnicalRequests() && $chartData['technical']): ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">بلاغات الموقع</h3>
                <div class="h-56">
                    <canvas id="clientWebsiteIssuesChart"></canvas>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <?php if($clientAccount->canAccessTechnicalRequests() && $chartData['technical']): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">طلبات الاجتماع</h3>
                <div class="h-56">
                    <canvas id="clientMeetingsChart"></canvas>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">ميزات وتحسينات النظام</h3>
                <div class="h-56">
                    <canvas id="clientFeaturesChart"></canvas>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if($clientAccount->canAccessBilling() && $chartData['billing']): ?>
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
            <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
                    <h3 class="text-lg font-bold text-gray-900">الفواتير الشهرية</h3>
                    <div class="flex flex-wrap gap-3 text-xs font-semibold text-gray-600">
                        <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-sm bg-blue-500"></span> عادية (صادرة)</span>
                        <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-sm bg-violet-500"></span> مالية (صادرة)</span>
                        <span class="inline-flex items-center gap-1"><span class="w-3 h-3 rounded-sm bg-emerald-500"></span> محصّل</span>
                    </div>
                </div>
                <div class="h-72">
                    <canvas id="clientBillingChart"></canvas>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-bold text-gray-900 mb-2">حالة الفواتير</h3>
                <p class="text-xs text-gray-500 mb-4">عادية + مالية مجمّعة</p>
                <div class="h-52">
                    <canvas id="clientPaymentChart"></canvas>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3 text-center text-xs">
                    <div class="rounded-lg bg-red-50 border border-red-100 p-3">
                        <p class="text-red-700 font-semibold">متبقي (عادية)</p>
                        <p class="text-lg font-bold text-red-900 mt-1"><?php echo e(number_format($chartData['billing']['outstanding']['regular'])); ?></p>
                    </div>
                    <div class="rounded-lg bg-purple-50 border border-purple-100 p-3">
                        <p class="text-purple-700 font-semibold">متبقي (مالية)</p>
                        <p class="text-lg font-bold text-purple-900 mt-1"><?php echo e(number_format($chartData['billing']['outstanding']['financial'])); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </section>

    <?php if($clientAccount->canAccessBilling() && ($invoicesDueSoon->isNotEmpty() || $financialDueSoon->isNotEmpty())): ?>
        <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">فواتير باستحقاق خلال 7 أيام</h2>
            <div class="space-y-3 text-sm">
                <?php $__currentLoopData = $invoicesDueSoon; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex flex-wrap justify-between gap-2 border-b border-gray-100 pb-2">
                        <span class="font-mono font-semibold"><?php echo e($inv->invoice_number); ?></span>
                        <span class="text-gray-600">استحقاق: <?php echo e($inv->due_date?->format('Y/m/d')); ?></span>
                        <span>متبقي: <?php echo e(number_format($inv->balance_amount)); ?></span>
                        <?php if($inv->payment_link): ?>
                            <a href="<?php echo e($inv->payment_link); ?>" target="_blank" rel="noopener" class="text-blue-600 font-semibold hover:underline">رابط الدفع</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php $__currentLoopData = $financialDueSoon; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex flex-wrap justify-between gap-2 border-b border-gray-100 pb-2">
                        <span class="font-mono font-semibold"><?php echo e($inv->invoice_number); ?> (مالية)</span>
                        <span class="text-gray-600">استحقاق: <?php echo e($inv->due_date?->format('Y/m/d')); ?></span>
                        <span>متبقي: <?php echo e(number_format($inv->balance_due)); ?></span>
                        <?php if($inv->payment_link): ?>
                            <a href="<?php echo e($inv->payment_link); ?>" target="_blank" rel="noopener" class="text-blue-600 font-semibold hover:underline">رابط الدفع</a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <p class="mt-4 text-xs text-gray-500">للاستفسار المحاسبي: <?php echo e(\App\Helpers\SettingsHelper::getCompanyPhone() ?: '—'); ?> — <?php echo e(\App\Helpers\SettingsHelper::getCompanyEmail() ?: '—'); ?></p>
        </div>
    <?php endif; ?>

    <?php if($clientAccount->canAccessTechnicalRequests() && $upcomingMeetings->isNotEmpty()): ?>
        <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between gap-4 mb-4">
                <h2 class="text-lg font-bold text-gray-900">مواعيد مؤكدة قريبة</h2>
                <a href="<?php echo e(route('client.calendar')); ?>" class="text-sm font-semibold text-blue-700 hover:underline">التقويم الكامل</a>
            </div>
            <ul class="space-y-2 text-sm text-gray-800">
                <?php $__currentLoopData = $upcomingMeetings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="flex flex-wrap justify-between gap-2 border-b border-gray-100 pb-2">
                        <span class="font-semibold"><?php echo e($m->title); ?></span>
                        <span class="text-gray-600"><?php echo e($m->scheduled_at?->format('Y/m/d H:i')); ?></span>
                        <a href="<?php echo e(route('client.meeting-requests.show', $m)); ?>" class="text-blue-600 hover:underline">تفاصيل</a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="mb-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">آخر الأنشطة</h2>
            <ul class="space-y-3 text-sm text-gray-700">
                <?php $__empty_1 = true; $__currentLoopData = $activityItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <li class="flex justify-between gap-3 border-b border-gray-50 pb-2">
                        <span><?php echo e($item['label']); ?></span>
                        <span class="text-gray-500 whitespace-nowrap shrink-0"><?php echo e($item['at']->format('m/d H:i')); ?></span>
                        <?php if(!empty($item['url'])): ?>
                            <a href="<?php echo e($item['url']); ?>" class="text-blue-600 shrink-0 hover:underline">فتح</a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <li class="text-gray-500">لا يوجد نشاط حديث بعد.</li>
                <?php endif; ?>
            </ul>
        </div>
        <?php echo $__env->make('client-portal.partials.faq', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">بيانات العميل</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="text-xs text-gray-500 mb-1">الاسم</div>
                <div class="font-semibold text-gray-900"><?php echo e($client->name); ?></div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="text-xs text-gray-500 mb-1">البريد</div>
                <div class="font-semibold text-gray-900"><?php echo e($client->email ?? '—'); ?></div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="text-xs text-gray-500 mb-1">الهاتف</div>
                <div class="font-semibold text-gray-900"><?php echo e($client->phone ?? '—'); ?></div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="text-xs text-gray-500 mb-1">العنوان</div>
                <div class="font-semibold text-gray-900"><?php echo e($client->address ?? '—'); ?></div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartData = <?php echo json_encode($chartData, 15, 512) ?>;
    const rtl = true;

    Chart.defaults.font.family = 'Tajawal, ui-sans-serif, system-ui, sans-serif';
    Chart.defaults.plugins.legend.rtl = rtl;
    Chart.defaults.plugins.legend.labels.usePointStyle = true;

    const baseOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 16, boxWidth: 10, font: { size: 11 } }
            },
            tooltip: {
                rtl: rtl,
                titleAlign: 'right',
                bodyAlign: 'right',
                padding: 12,
                cornerRadius: 8
            }
        }
    };

    function doughnutConfig(payload) {
        return {
            type: 'doughnut',
            data: {
                labels: payload.labels,
                datasets: [{
                    data: payload.data,
                    backgroundColor: payload.colors,
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                ...baseOptions,
                cutout: '62%',
                plugins: {
                    ...baseOptions.plugins,
                    legend: {
                        ...baseOptions.plugins.legend,
                        display: !payload.empty
                    }
                }
            }
        };
    }

    const activityDatasets = [
        {
            label: 'تذاكر الدعم',
            data: chartData.activity.tickets,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.12)',
            tension: 0.35,
            fill: true,
            borderWidth: 2
        },
        {
            label: 'تقارير الخدمة',
            data: chartData.activity.serviceReports,
            borderColor: '#8b5cf6',
            backgroundColor: 'rgba(139, 92, 246, 0.08)',
            tension: 0.35,
            fill: true,
            borderWidth: 2
        }
    ];

    if (chartData.activity.websiteIssues) {
        activityDatasets.push({
            label: 'بلاغات الموقع',
            data: chartData.activity.websiteIssues,
            borderColor: '#f59e0b',
            backgroundColor: 'rgba(245, 158, 11, 0.08)',
            tension: 0.35,
            fill: true,
            borderWidth: 2
        });
    }
    if (chartData.activity.meetings) {
        activityDatasets.push({
            label: 'طلبات اجتماع',
            data: chartData.activity.meetings,
            borderColor: '#06b6d4',
            backgroundColor: 'rgba(6, 182, 212, 0.08)',
            tension: 0.35,
            fill: true,
            borderWidth: 2
        });
    }
    if (chartData.activity.features) {
        activityDatasets.push({
            label: 'ميزات وتحسينات',
            data: chartData.activity.features,
            borderColor: '#10b981',
            backgroundColor: 'rgba(16, 185, 129, 0.08)',
            tension: 0.35,
            fill: true,
            borderWidth: 2
        });
    }

    const activityEl = document.getElementById('clientActivityChart');
    if (activityEl) {
        new Chart(activityEl, {
            type: 'line',
            data: { labels: chartData.months, datasets: activityDatasets },
            options: {
                ...baseOptions,
                interaction: { mode: 'index', intersect: false },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    const ticketsEl = document.getElementById('clientTicketsChart');
    if (ticketsEl) new Chart(ticketsEl, doughnutConfig(chartData.ticketsByStatus));

    const projectsEl = document.getElementById('clientProjectsChart');
    if (projectsEl) new Chart(projectsEl, doughnutConfig(chartData.projectsByStatus));

    if (chartData.technical) {
        const issuesEl = document.getElementById('clientWebsiteIssuesChart');
        if (issuesEl) new Chart(issuesEl, doughnutConfig(chartData.technical.websiteIssues));

        const meetingsEl = document.getElementById('clientMeetingsChart');
        if (meetingsEl) new Chart(meetingsEl, doughnutConfig(chartData.technical.meetings));

        const featuresEl = document.getElementById('clientFeaturesChart');
        if (featuresEl) new Chart(featuresEl, doughnutConfig(chartData.technical.features));
    }

    if (chartData.billing) {
        const billingEl = document.getElementById('clientBillingChart');
        if (billingEl) {
            const paidCombined = chartData.billing.monthlyPaid.regular.map((v, i) =>
                v + chartData.billing.monthlyPaid.financial[i]
            );
            new Chart(billingEl, {
                type: 'bar',
                data: {
                    labels: chartData.months,
                    datasets: [
                        {
                            label: 'فواتير عادية',
                            data: chartData.billing.monthlyIssued.regular,
                            backgroundColor: 'rgba(59, 130, 246, 0.85)',
                            borderRadius: 6,
                            stack: 'issued'
                        },
                        {
                            label: 'فواتير مالية',
                            data: chartData.billing.monthlyIssued.financial,
                            backgroundColor: 'rgba(139, 92, 246, 0.85)',
                            borderRadius: 6,
                            stack: 'issued'
                        },
                        {
                            label: 'محصّل',
                            data: paidCombined,
                            type: 'line',
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.15)',
                            borderWidth: 3,
                            tension: 0.3,
                            fill: false,
                            yAxisID: 'y'
                        }
                    ]
                },
                options: {
                    ...baseOptions,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: {
                                callback: (v) => new Intl.NumberFormat('ar-EG').format(v)
                            }
                        },
                        x: { grid: { display: false } }
                    },
                    plugins: {
                        ...baseOptions.plugins,
                        tooltip: {
                            ...baseOptions.plugins.tooltip,
                            callbacks: {
                                label: (ctx) => {
                                    const val = ctx.parsed.y ?? ctx.parsed;
                                    return ctx.dataset.label + ': ' + new Intl.NumberFormat('ar-EG').format(val);
                                }
                            }
                        }
                    }
                }
            });
        }

        const paymentEl = document.getElementById('clientPaymentChart');
        if (paymentEl) new Chart(paymentEl, doughnutConfig(chartData.billing.paymentOverview));
    }
});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\solvesta\resources\views\client-portal\dashboard.blade.php ENDPATH**/ ?>