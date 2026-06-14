@extends('layouts.app')

@section('page-title', $lead->reference_code)

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full px-2 sm:px-0">
    @include('partials.erp-page-header', [
        'title' => $lead->name,
        'subtitle' => $lead->reference_code.' · '.$lead->company.' — '.$lead->service_interest,
        'icon' => 'users',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        <a href="{{ route('leads.index') }}" class="border border-gray-200 px-4 py-2 rounded-xl text-sm font-semibold bg-white hover:shadow-md transition">القائمة</a>
    </div>

    @if(session('success'))<div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>@endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                <h2 class="font-bold mb-3">التفاصيل</h2>
                <dl class="grid grid-cols-2 gap-3 text-sm">
                    <div><dt class="text-gray-500">الحالة</dt><dd class="font-bold">{{ $lead->status_label }}</dd></div>
                    <div><dt class="text-gray-500">المصدر</dt><dd>{{ $lead->source_label }}</dd></div>
                    <div><dt class="text-gray-500">البريد</dt><dd>{{ $lead->email ?? '—' }}</dd></div>
                    <div><dt class="text-gray-500">الهاتف</dt><dd>{{ $lead->phone ?? '—' }}</dd></div>
                    <div><dt class="text-gray-500">الميزانية</dt><dd>{{ $lead->estimated_budget ? number_format($lead->estimated_budget) : '—' }}</dd></div>
                    <div><dt class="text-gray-500">مسند إلى</dt><dd>{{ $lead->assignee?->name ?? '—' }}</dd></div>
                </dl>
                @if($lead->notes)<p class="mt-4 text-sm text-gray-700 whitespace-pre-wrap">{{ $lead->notes }}</p>@endif
            </div>
        </div>

        <div class="space-y-4">
            @if($lead->status !== 'converted')
            <form method="POST" action="{{ route('leads.update-status', $lead) }}" class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5 space-y-3">
                @csrf
                <h3 class="font-bold">تحديث الحالة</h3>
                <select name="status" class="w-full border rounded-lg px-3 py-2 text-sm">
                    @foreach(['new','contacted','qualified','lost','on_hold'] as $st)
                        <option value="{{ $st }}" @selected($lead->status===$st)>{{ (new \App\Models\Lead(['status'=>$st]))->status_label }}</option>
                    @endforeach
                </select>
                <input name="lost_reason" placeholder="سبب الفقد (إن وُجد)" class="w-full border rounded-lg px-3 py-2 text-sm">
                <button class="w-full bg-gray-900 text-white py-2 rounded-lg text-sm font-bold">حفظ</button>
            </form>

            @can('create-sales')
            <form method="POST" action="{{ route('leads.convert-to-sale', $lead) }}" class="rounded-2xl shadow-lg border p-5 space-y-3" style="background: {{ $themeColor }}08; border-color: {{ $themeColor }}30;">
                @csrf
                <h3 class="font-bold text-blue-900">تحويل إلى Sales</h3>
                <p class="text-xs text-blue-800">ينشئ عميلاً وفرصة مبيعات تلقائياً.</p>
                <select name="assigned_to" required class="w-full border rounded-lg px-3 py-2 text-sm">
                    @foreach($users as $u)<option value="{{ $u->id }}">{{ $u->name }}</option>@endforeach
                </select>
                <button class="w-full py-2.5 rounded-xl font-bold text-white" style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">تحويل لـ Sales →</button>
            </form>
            @endcan
            @else
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5 text-sm">
                <p class="font-bold text-emerald-900">تم التحويل</p>
                @if($lead->convertedSale)
                    <a href="{{ route('sales.show', $lead->convertedSale) }}" class="text-blue-600 font-bold mt-2 inline-block">فتح فرصة المبيعات</a>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
