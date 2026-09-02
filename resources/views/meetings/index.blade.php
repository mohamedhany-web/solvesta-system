@extends('layouts.app')

@section('page-title', 'إدارة الاجتماعات والمؤتمرات')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full font-tajawal">
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">جدولة الاجتماعات</h1>
                <p class="text-gray-600">جدول مواعيدك مع تذكير تلقائي بالإيميل قبل 24 ساعة وقبل ساعة</p>
            </div>
            @can('create-meetings')
            <a href="{{ route('meetings.create') }}" class="inline-flex items-center px-6 py-3 rounded-xl text-white font-bold shadow-sm" style="background:{{ $themeColor }};">
                <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                اجتماع جديد
            </a>
            @endcan
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
        <a href="{{ route('meetings.index', ['scope' => 'today'] + request()->except('scope', 'page')) }}"
           class="rounded-2xl border p-4 {{ ($scope ?? '') === 'today' ? 'bg-teal-50 border-teal-300' : 'bg-white border-gray-200' }}">
            <p class="text-xs text-gray-500">اليوم</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['today'] ?? 0 }}</p>
        </a>
        <a href="{{ route('meetings.index', ['scope' => 'upcoming'] + request()->except('scope', 'page')) }}"
           class="rounded-2xl border p-4 {{ ($scope ?? 'upcoming') === 'upcoming' ? 'bg-teal-50 border-teal-300' : 'bg-white border-gray-200' }}">
            <p class="text-xs text-gray-500">القادمة</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['upcoming'] ?? 0 }}</p>
        </a>
        <a href="{{ route('meetings.index', ['scope' => 'all', 'mine' => 1] + request()->except('scope', 'mine', 'page')) }}"
           class="rounded-2xl border p-4 {{ request('mine') === '1' ? 'bg-teal-50 border-teal-300' : 'bg-white border-gray-200' }}">
            <p class="text-xs text-gray-500">مواعيدي القادمة</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['mine'] ?? 0 }}</p>
        </a>
    </div>

    <div class="flex flex-wrap gap-2 mb-4">
        @foreach([
            'upcoming' => 'القادمة',
            'today' => 'اليوم',
            'all' => 'الكل',
        ] as $key => $label)
            <a href="{{ route('meetings.index', ['scope' => $key] + request()->except('scope', 'page')) }}"
               class="px-4 py-2 rounded-full text-sm font-bold border {{ ($scope ?? 'upcoming') === $key ? 'text-white border-transparent' : 'bg-white text-gray-700 border-gray-200' }}"
               @if(($scope ?? 'upcoming') === $key) style="background:{{ $themeColor }};" @endif>
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('meetings.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <input type="hidden" name="scope" value="{{ $scope ?? 'upcoming' }}">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       placeholder="العنوان أو الوصف..."
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">جميع الحالات</option>
                    <option value="scheduled" @selected(request('status')==='scheduled')>مجدول</option>
                    <option value="ongoing" @selected(request('status')==='ongoing')>قيد التنفيذ</option>
                    <option value="completed" @selected(request('status')==='completed')>مكتمل</option>
                    <option value="cancelled" @selected(request('status')==='cancelled')>ملغي</option>
                </select>
            </div>
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">النوع</label>
                <select name="type" id="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <option value="">جميع الأنواع</option>
                    <option value="internal" @selected(request('type')==='internal')>داخلي</option>
                    <option value="external" @selected(request('type')==='external')>خارجي</option>
                    <option value="online" @selected(request('type')==='online')>أونلاين</option>
                    <option value="in-person" @selected(request('type')==='in-person')>حضوري</option>
                    <option value="hybrid" @selected(request('type')==='hybrid')>هجين</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">المنظم</label>
                <label class="flex items-center gap-2 h-[42px] px-3 border rounded-lg cursor-pointer">
                    <input type="checkbox" name="mine" value="1" @checked(request('mine')==='1')>
                    <span class="text-sm">مواعيدي فقط</span>
                </label>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 text-white px-4 py-2 rounded-lg font-bold" style="background:{{ $themeColor }};">تصفية</button>
                <a href="{{ route('meetings.index') }}" class="px-4 py-2 rounded-lg border text-sm font-semibold">مسح</a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @if($meetings->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">العنوان</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">وقت البدء</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المنظم</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموقع</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تذكير إيميل</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($meetings as $meeting)
                        @php $isMine = $meeting->organizer_id === auth()->id(); @endphp
                        <tr class="hover:bg-gray-50 {{ $isMine ? 'bg-teal-50/40' : '' }}">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                    {{ $meeting->title }}
                                    @if($isMine)
                                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-teal-100 text-teal-800 font-bold">لي</span>
                                    @endif
                                </div>
                                @if($meeting->description)
                                    <div class="text-sm text-gray-500 mt-1">{{ \Illuminate\Support\Str::limit($meeting->description, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                {{ $meeting->start_time?->format('Y-m-d H:i') }}
                                <div class="text-xs text-gray-500 font-normal">حتى {{ $meeting->end_time?->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $meeting->organizer->name ?? '—' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $meeting->location ?? ($meeting->meeting_link ? 'أونلاين' : '—') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($meeting->send_email_reminders)
                                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800">مفعّل</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-600">متوقف</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($meeting->status == 'completed') bg-green-100 text-green-800
                                    @elseif($meeting->status == 'ongoing') bg-blue-100 text-blue-800
                                    @elseif($meeting->status == 'cancelled') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    @if($meeting->status == 'scheduled') مجدول
                                    @elseif($meeting->status == 'ongoing') قيد التنفيذ
                                    @elseif($meeting->status == 'completed') مكتمل
                                    @elseif($meeting->status == 'cancelled') ملغي
                                    @else {{ $meeting->status }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('meetings.show', $meeting) }}" class="text-blue-600 hover:text-blue-900">عرض</a>
                                    @can('edit-meetings')
                                    <a href="{{ route('meetings.edit', $meeting) }}" class="text-indigo-600 hover:text-indigo-900">تعديل</a>
                                    @endcan
                                    @can('delete-meetings')
                                    <form action="{{ route('meetings.destroy', $meeting) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا الاجتماع؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">حذف</button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $meetings->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <h3 class="text-lg font-semibold text-gray-900 mb-2">لا توجد اجتماعات في هذا العرض</h3>
                <p class="text-gray-600 mb-6">أنشئ اجتماعاً جديداً وسيصلك تذكير بالإيميل تلقائياً</p>
                @can('create-meetings')
                <a href="{{ route('meetings.create') }}" class="inline-flex px-6 py-3 rounded-lg text-white font-bold" style="background:{{ $themeColor }};">اجتماع جديد</a>
                @endcan
            </div>
        @endif
    </div>
</div>
@endsection
