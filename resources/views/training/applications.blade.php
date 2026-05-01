@extends('layouts.app')

@section('page-title', 'طلبات التدريب')

@section('content')
<div class="w-full max-w-7xl mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">طلبات التدريب</h1>
            <p class="text-gray-600 mt-2">
                البرنامج: <span class="font-semibold text-gray-900">{{ $training->title }}</span>
            </p>
        </div>
        <a href="{{ route('training.show', $training) }}" class="bg-gray-600 text-white px-6 py-3 rounded-xl hover:bg-gray-700 transition-all duration-200 shadow-sm">
            رجوع للبرنامج
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">الاسم</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">البريد</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">الهاتف</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">الجامعة/التخصص</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">CV</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">الحالة</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($applications as $a)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $a->full_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $a->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $a->phone ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $a->university ?? '—' }}
                                @if($a->major)
                                    <div class="text-xs text-gray-500 mt-1">{{ $a->major }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($a->cv_path)
                                    <a class="text-blue-600 font-bold hover:underline" href="{{ asset('storage/' . $a->cv_path) }}" target="_blank" rel="noopener">تحميل</a>
                                @else
                                    <span class="text-gray-500">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    $badge = match($a->status) {
                                        'approved' => ['bg'=>'bg-green-100','text'=>'text-green-800','label'=>'مقبول'],
                                        'rejected' => ['bg'=>'bg-red-100','text'=>'text-red-800','label'=>'مرفوض'],
                                        default => ['bg'=>'bg-gray-100','text'=>'text-gray-800','label'=>'قيد المراجعة'],
                                    };
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $badge['bg'] }} {{ $badge['text'] }}">
                                    {{ $badge['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-left">
                                <form method="POST" action="{{ route('training.applications.status', $a) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="px-3 py-2 rounded-lg border border-gray-200 text-sm">
                                        <option value="pending" @selected($a->status === 'pending')>قيد المراجعة</option>
                                        <option value="approved" @selected($a->status === 'approved')>مقبول</option>
                                        <option value="rejected" @selected($a->status === 'rejected')>مرفوض</option>
                                    </select>
                                    <button class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-bold hover:bg-black transition">حفظ</button>
                                </form>
                            </td>
                        </tr>
                        @if($a->message)
                            <tr class="bg-white">
                                <td colspan="7" class="px-6 pb-5 text-sm text-gray-700">
                                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                                        <div class="font-bold text-gray-900 mb-2">رسالة المتقدم</div>
                                        <div class="whitespace-pre-wrap">{{ $a->message }}</div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-gray-600">
                                <p class="font-bold text-gray-800 mb-2">لا توجد طلبات بعد من صفحة الأكاديمية على الموقع.</p>
                                <p class="text-sm text-gray-500 mb-4">رابط البرنامج للمتقدمين: <a class="text-blue-600 font-bold hover:underline" href="{{ route('website.training.show', $training) }}" target="_blank" rel="noopener">{{ route('website.training.show', $training) }}</a></p>
                                <p class="text-sm text-gray-500">بعد أول تسجيل سيظهر الطلب هنا.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6">
            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection

