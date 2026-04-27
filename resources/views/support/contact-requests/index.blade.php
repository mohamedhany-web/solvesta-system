@extends('layouts.app')

@section('page-title', 'نماذج التواصل')

@section('content')
<div class="w-full max-w-full">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">نماذج التواصل / حجز الاستشارات</h1>
            <p class="text-gray-600">كل الطلبات القادمة من موقع الشركة.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <span class="px-3 py-2 rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-700">جديد: {{ $stats['new'] }}</span>
            <span class="px-3 py-2 rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-700">قيد العمل: {{ $stats['in_progress'] }}</span>
            <span class="px-3 py-2 rounded-xl bg-white border border-gray-200 text-sm font-bold text-gray-700">مغلق: {{ $stats['closed'] }}</span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-5 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="font-bold text-gray-900">الطلبات</div>
            <form class="flex flex-col sm:flex-row gap-2" method="GET" action="{{ route('support.contact-requests.index') }}">
                <select name="type" class="px-3 py-2 rounded-lg border border-gray-200 text-sm">
                    <option value="">كل الأنواع</option>
                    <option value="contact" {{ request('type')==='contact'?'selected':'' }}>تواصل عام</option>
                    <option value="consultation" {{ request('type')==='consultation'?'selected':'' }}>حجز استشارة</option>
                </select>
                <select name="status" class="px-3 py-2 rounded-lg border border-gray-200 text-sm">
                    <option value="">كل الحالات</option>
                    <option value="new" {{ request('status')==='new'?'selected':'' }}>جديد</option>
                    <option value="in_progress" {{ request('status')==='in_progress'?'selected':'' }}>قيد العمل</option>
                    <option value="closed" {{ request('status')==='closed'?'selected':'' }}>مغلق</option>
                </select>
                <button class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-bold">تصفية</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-right px-4 py-3 font-bold">النوع</th>
                        <th class="text-right px-4 py-3 font-bold">الاسم</th>
                        <th class="text-right px-4 py-3 font-bold">الشركة</th>
                        <th class="text-right px-4 py-3 font-bold">الهاتف</th>
                        <th class="text-right px-4 py-3 font-bold">الحالة</th>
                        <th class="text-right px-4 py-3 font-bold">التاريخ</th>
                        <th class="text-right px-4 py-3 font-bold"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($requests as $r)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-bold text-gray-800">
                                {{ $r->type === 'consultation' ? 'حجز استشارة' : 'تواصل' }}
                            </td>
                            <td class="px-4 py-3 text-gray-800">{{ $r->name }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $r->company ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $r->phone ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $badge = match($r->status) {
                                        'in_progress' => 'bg-amber-50 text-amber-800 border-amber-200',
                                        'closed' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                        default => 'bg-blue-50 text-blue-800 border-blue-200',
                                    };
                                    $label = match($r->status) {
                                        'in_progress' => 'قيد العمل',
                                        'closed' => 'مغلق',
                                        default => 'جديد',
                                    };
                                @endphp
                                <span class="inline-flex px-3 py-1 rounded-full border text-xs font-bold {{ $badge }}">{{ $label }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $r->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('support.contact-requests.show', $r) }}" class="text-blue-700 font-bold hover:underline">عرض</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-8 text-center text-gray-500" colspan="7">لا توجد طلبات.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-100">
            {{ $requests->links() }}
        </div>
    </div>
</div>
@endsection

