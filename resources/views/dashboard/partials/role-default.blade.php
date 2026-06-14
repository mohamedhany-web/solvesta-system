@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'مرحباً، ' . auth()->user()->name,
        'subtitle' => 'الدور: ' . (\App\Helpers\RoleHelper::getRoleName($user_role ?? 'employee') ?? 'موظف') . ' — ' . now()->locale('ar')->translatedFormat('l، d F Y'),
        'icon' => 'chart',
    ])

    @if(isset($my_projects) || isset($my_tasks))
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach(array_filter([
            isset($my_projects) ? ['مشاريعي', $my_projects, $themeColor] : null,
            isset($my_active_projects) ? ['نشطة', $my_active_projects, '#059669'] : null,
            isset($my_tasks) ? ['مهامي', $my_tasks, '#7c3aed'] : null,
            isset($my_overdue_tasks) ? ['متأخرة', $my_overdue_tasks, '#dc2626'] : null,
        ]) as $item)
        <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-sm">
            <p class="text-xs text-gray-500">{{ $item[0] }}</p>
            <p class="text-3xl font-bold mt-1" style="color: {{ $item[2] }};">{{ $item[1] }}</p>
        </div>
        @endforeach
    </div>
    @endif

    @if(isset($my_performance_metrics))
    <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-6 shadow-sm">
        <h3 class="font-bold text-sm mb-3">أدائي</h3>
        <div class="grid grid-cols-3 gap-4 text-center text-sm">
            <div><p class="text-gray-500 text-xs">كفاءة المهام</p><p class="text-2xl font-bold text-emerald-600">{{ $my_performance_metrics['task_efficiency'] }}%</p></div>
            <div><p class="text-gray-500 text-xs">مهام متأخرة</p><p class="text-2xl font-bold text-red-600">{{ $my_performance_metrics['overdue_tasks'] }}</p></div>
            <div><p class="text-gray-500 text-xs">معلقة</p><p class="text-2xl font-bold" style="color: {{ $themeColor }};">{{ $my_pending_tasks ?? 0 }}</p></div>
        </div>
    </div>
    @endif

    @if(isset($total_employees))
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">الموظفون</p><p class="text-3xl font-bold" style="color:{{ $themeColor }}">{{ $total_employees }}</p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">إجازات معلقة</p><p class="text-3xl font-bold text-amber-600">{{ $pending_leaves }}</p></div>
    </div>
    @endif

    @if(isset($pending_invoices))
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">مصروفات معتمدة</p><p class="text-2xl font-bold text-emerald-700">{{ number_format($total_amount ?? 0) }}</p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">هذا الشهر</p><p class="text-2xl font-bold" style="color:{{ $themeColor }}">{{ number_format($this_month_expenses ?? 0) }}</p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">فواتير معلقة</p><p class="text-2xl font-bold text-amber-600">{{ $pending_invoices }}</p></div>
    </div>
    @endif

    @if(isset($won_sales))
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">عملاء</p><p class="text-2xl font-bold" style="color:{{ $themeColor }}">{{ $total_clients }}</p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">صفقات رابحة</p><p class="text-2xl font-bold text-emerald-600">{{ $won_sales }}</p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">معدل التحويل</p><p class="text-2xl font-bold text-blue-600">{{ $conversion_rate }}%</p></div>
    </div>
    @endif

    @if(isset($my_tickets))
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">تذاكري</p><p class="text-3xl font-bold" style="color:{{ $themeColor }}">{{ $my_tickets }}</p></div>
        <div class="bg-white rounded-2xl border p-5"><p class="text-xs text-gray-500">مفتوحة</p><p class="text-3xl font-bold text-amber-600">{{ $my_open_tickets }}</p></div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @if(isset($recent_projects) && $recent_projects->count())
        <section class="bg-white rounded-2xl border shadow-sm p-5">
            <h3 class="font-bold text-sm mb-3">مشاريعي الأخيرة</h3>
            <ul class="text-xs space-y-2">
                @foreach($recent_projects as $p)
                <li><a href="{{ route('projects.show', $p) }}" class="font-semibold hover:underline" style="color:{{ $themeColor }}">{{ $p->name }}</a></li>
                @endforeach
            </ul>
        </section>
        @endif
        @if(isset($recent_tasks) && $recent_tasks->count())
        <section class="bg-white rounded-2xl border shadow-sm p-5">
            <h3 class="font-bold text-sm mb-3">مهامي الأخيرة</h3>
            <ul class="text-xs space-y-2">
                @foreach($recent_tasks as $t)
                <li><a href="{{ route('tasks.show', $t) }}" class="font-semibold hover:underline" style="color:{{ $themeColor }}">{{ $t->title }}</a></li>
                @endforeach
            </ul>
        </section>
        @endif
    </div>
</div>
