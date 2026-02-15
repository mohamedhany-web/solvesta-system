@extends('layouts.app')

@section('page-title', 'سجل عمليات تسجيل الدخول')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">سجل عمليات تسجيل الدخول</h1>
                <p class="text-sm sm:text-base text-gray-600">عرض جميع عمليات تسجيل الدخول وإرسال الأكواد مع التواريخ والأوقات</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-6 gap-3 sm:gap-6 mb-6">
        <!-- Total -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5 hover:shadow-md transition-all duration-200">
            <div class="flex flex-col">
                <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-2">إجمالي العمليات</p>
                <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            </div>
        </div>

        <!-- Today -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5 hover:shadow-md transition-all duration-200">
            <div class="flex flex-col">
                <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-2">اليوم</p>
                <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-blue-600">{{ $stats['today'] }}</p>
            </div>
        </div>

        <!-- Logins -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5 hover:shadow-md transition-all duration-200">
            <div class="flex flex-col">
                <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-2">تسجيلات دخول</p>
                <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-green-600">{{ $stats['logins'] }}</p>
            </div>
        </div>

        <!-- Codes Sent -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5 hover:shadow-md transition-all duration-200">
            <div class="flex flex-col">
                <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-2">أكواد مرسلة</p>
                <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-purple-600">{{ $stats['codes_sent'] }}</p>
            </div>
        </div>

        <!-- Codes Verified -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5 hover:shadow-md transition-all duration-200">
            <div class="flex flex-col">
                <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-2">أكواد محققة</p>
                <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-indigo-600">{{ $stats['codes_verified'] }}</p>
            </div>
        </div>

        <!-- Failed -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5 hover:shadow-md transition-all duration-200">
            <div class="flex flex-col">
                <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-2">فاشلة</p>
                <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-red-600">{{ $stats['failed'] }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 mb-6">
        <form method="GET" action="{{ route('login-activity.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">نوع العملية</label>
                <select name="type" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="all" {{ request('type') == 'all' || !request('type') ? 'selected' : '' }}>جميع الأنواع</option>
                    <option value="login" {{ request('type') == 'login' ? 'selected' : '' }}>تسجيل دخول</option>
                    <option value="verification_code_sent" {{ request('type') == 'verification_code_sent' ? 'selected' : '' }}>إرسال كود</option>
                    <option value="verification_code_verified" {{ request('type') == 'verification_code_verified' ? 'selected' : '' }}>تحقق من كود</option>
                    <option value="verification_code_resend" {{ request('type') == 'verification_code_resend' ? 'selected' : '' }}>إعادة إرسال كود</option>
                    <option value="logout" {{ request('type') == 'logout' ? 'selected' : '' }}>تسجيل خروج</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>جميع الحالات</option>
                    <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>نجح</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشل</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>في الانتظار</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">إلى تاريخ</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div class="md:col-span-4 flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    تطبيق الفلتر
                </button>
                <a href="{{ route('login-activity.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition-colors">
                    إعادة تعيين
                </a>
            </div>
        </form>
    </div>

    <!-- Activities Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden w-full">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h3 class="text-base sm:text-lg font-semibold text-gray-900">قائمة العمليات</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المستخدم</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع العملية</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكود</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ والوقت</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activities as $activity)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center ml-3">
                                    <span class="text-sm font-medium text-white">{{ substr($activity->user->name ?? 'N', 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $activity->user->name ?? 'غير معروف' }}</div>
                                    <div class="text-sm text-gray-500">{{ $activity->user->email ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $activityTypes = [
                                    'login' => ['text' => 'تسجيل دخول', 'color' => 'green'],
                                    'verification_code_sent' => ['text' => 'إرسال كود', 'color' => 'blue'],
                                    'verification_code_verified' => ['text' => 'تحقق من كود', 'color' => 'indigo'],
                                    'verification_code_resend' => ['text' => 'إعادة إرسال', 'color' => 'purple'],
                                    'logout' => ['text' => 'تسجيل خروج', 'color' => 'gray'],
                                ];
                                $type = $activityTypes[$activity->activity_type] ?? ['text' => $activity->activity_type, 'color' => 'gray'];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $type['color'] }}-100 text-{{ $type['color'] }}-800">
                                {{ $type['text'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($activity->verification_code)
                                <span class="font-mono font-bold">{{ $activity->verification_code }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($activity->email)
                                {{ $activity->email }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($activity->status == 'success')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    نجح
                                </span>
                            @elseif($activity->status == 'failed')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    فشل
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    في الانتظار
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>{{ $activity->activity_at->format('Y-m-d') }}</div>
                            <div class="text-xs text-gray-500">{{ $activity->activity_at->format('H:i:s') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $activity->ip_address ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="mt-2">لا توجد عمليات مسجلة</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
            {{ $activities->links() }}
        </div>
    </div>
</div>
@endsection

