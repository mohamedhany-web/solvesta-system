@extends('layouts.app')

@section('page-title', 'تفاصيل التذكرة')

@section('content')
<div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl p-6 text-white mb-6 shadow-lg">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <div class="h-16 w-16 bg-white/20 rounded-full flex items-center justify-center ml-4">
                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold mb-2">{{ $ticket->ticket_number }}</h1>
                <p class="text-blue-100">{{ $ticket->subject }}</p>
            </div>
        </div>
        <div class="flex items-center space-x-3 space-x-reverse">
            <a href="{{ route('tickets.edit', $ticket) }}" class="bg-white/20 backdrop-blur-sm text-white px-4 py-2 rounded-lg hover:bg-white/30 transition-all duration-200">
                تعديل
            </a>
            <a href="{{ route('tickets.index') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition-all duration-200">
                العودة
            </a>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">تفاصيل التذكرة</h2>
        <div class="space-y-4">
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-1">الوصف</h3>
                <p class="text-sm text-gray-900">{{ $ticket->description }}</p>
            </div>
            <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                <div class="flex justify-between py-2">
                    <span class="text-sm font-medium text-gray-500">العميل</span>
                    <span class="text-sm text-gray-900">{{ $ticket->client->name ?? 'غير محدد' }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-sm font-medium text-gray-500">الحالة</span>
                    <span class="text-sm text-gray-900">{{ $ticket->status_name }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-sm font-medium text-gray-500">الأولوية</span>
                    <span class="text-sm text-gray-900">{{ $ticket->priority }}</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-sm font-medium text-gray-500">الفئة</span>
                    <span class="text-sm text-gray-900">{{ $ticket->category_name }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

