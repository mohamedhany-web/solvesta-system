@extends('layouts.app')

@section('page-title', 'تفاصيل الخطأ')

@section('content')
<!-- Header Section -->
<div class="bg-gradient-to-r from-red-600 to-red-700 rounded-xl p-6 text-white mb-6 shadow-lg">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <div class="h-16 w-16 bg-white/20 rounded-full flex items-center justify-center ml-4">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold mb-2">{{ $bug->bug_number }}</h1>
                <p class="text-red-100">{{ $bug->title }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-3 space-x-reverse">
            <a href="{{ route('bugs.edit', $bug) }}" class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-all duration-200">
                تعديل
            </a>
            <a href="{{ route('bugs.index') }}" class="bg-white text-red-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition-all duration-200">
                العودة
            </a>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Bug Details -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">تفاصيل الخطأ</h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">الوصف</h3>
                        <p class="text-sm text-gray-900">{{ $bug->description }}</p>
                    </div>

                    @if($bug->expected_result)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">النتيجة المتوقعة</h3>
                        <p class="text-sm text-gray-900">{{ $bug->expected_result }}</p>
                    </div>
                    @endif

                    @if($bug->actual_result)
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">النتيجة الفعلية</h3>
                        <p class="text-sm text-gray-900">{{ $bug->actual_result }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t">
                        <div class="flex justify-between py-2">
                            <span class="text-sm font-medium text-gray-500">المشروع</span>
                            <span class="text-sm text-gray-900">{{ $bug->project->name ?? 'غير محدد' }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-sm font-medium text-gray-500">الحالة</span>
                            @php
                                $statusColors = [
                                    'open' => 'bg-red-100 text-red-800',
                                    'in_progress' => 'bg-yellow-100 text-yellow-800',
                                    'testing' => 'bg-blue-100 text-blue-800',
                                    'resolved' => 'bg-green-100 text-green-800',
                                    'closed' => 'bg-gray-100 text-gray-800',
                                    'duplicate' => 'bg-orange-100 text-orange-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$bug->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $bug->status_name }}
                            </span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-sm font-medium text-gray-500">درجة الخطورة</span>
                            @php
                                $severityColors = [
                                    'low' => 'bg-green-100 text-green-800',
                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                    'high' => 'bg-orange-100 text-orange-800',
                                    'critical' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $severityColors[$bug->severity] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $bug->severity_name }}
                            </span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-sm font-medium text-gray-500">الأولوية</span>
                            @php
                                $priorityColors = [
                                    'low' => 'bg-green-100 text-green-800',
                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                    'high' => 'bg-orange-100 text-orange-800',
                                    'urgent' => 'bg-red-100 text-red-800',
                                ];
                                $priorityNames = [
                                    'low' => 'منخفضة',
                                    'medium' => 'متوسطة',
                                    'high' => 'عالية',
                                    'urgent' => 'عاجلة',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityColors[$bug->priority] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $priorityNames[$bug->priority] ?? $bug->priority }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Environment Information -->
            @if($bug->environment || $bug->browser || $bug->operating_system)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">بيئة التشغيل</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @if($bug->environment)
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">البيئة</span>
                        <span class="text-sm text-gray-900">{{ $bug->environment }}</span>
                    </div>
                    @endif
                    @if($bug->browser)
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">المتصفح</span>
                        <span class="text-sm text-gray-900">{{ $bug->browser }}</span>
                    </div>
                    @endif
                    @if($bug->operating_system)
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-500">نظام التشغيل</span>
                        <span class="text-sm text-gray-900">{{ $bug->operating_system }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Resolution Notes -->
            @if($bug->resolution_notes)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">ملاحظات الحل</h2>
                <p class="text-sm text-gray-900">{{ $bug->resolution_notes }}</p>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">حالة الخطأ</h3>
                <div class="text-center">
                    <div class="h-20 w-20 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900">{{ $bug->bug_number }}</h4>
                    <p class="text-sm text-gray-600">{{ $bug->project->name ?? 'غير محدد' }}</p>
                    <div class="mt-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$bug->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $bug->status_name }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Team Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">الفريق</h3>
                <div class="space-y-3">
                    @if($bug->reportedBy)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="h-10 w-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center ml-3">
                            <span class="text-sm font-medium text-white">{{ mb_substr($bug->reportedBy->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $bug->reportedBy->name }}</p>
                            <p class="text-xs text-gray-500">المبلغ</p>
                        </div>
                    </div>
                    @endif

                    @if($bug->assignedTo)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="h-10 w-10 bg-gradient-to-r from-purple-500 to-purple-600 rounded-full flex items-center justify-center ml-3">
                            <span class="text-sm font-medium text-white">{{ mb_substr($bug->assignedTo->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $bug->assignedTo->name }}</p>
                            <p class="text-xs text-gray-500">المكلف بالإصلاح</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">الجدول الزمني</h3>
                <div class="space-y-3">
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="h-2 w-2 bg-green-500 rounded-full ml-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">تاريخ الإبلاغ</p>
                            <p class="text-xs text-gray-500">{{ $bug->created_at->format('Y/m/d H:i') }}</p>
                            <p class="text-xs text-gray-400">{{ $bug->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    @if($bug->resolution_date)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="h-2 w-2 bg-blue-500 rounded-full ml-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">تاريخ الحل</p>
                            <p class="text-xs text-gray-500">{{ $bug->resolution_date->format('Y/m/d H:i') }}</p>
                            <p class="text-xs text-gray-400">تم الحل في {{ $bug->resolution_time }} ساعة</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">إجراءات سريعة</h3>
                <div class="space-y-3">
                    <a href="{{ route('bugs.edit', $bug) }}" class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        تعديل الخطأ
                    </a>
                    <form action="{{ route('bugs.destroy', $bug) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الخطأ؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            حذف الخطأ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

