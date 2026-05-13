@extends('layouts.app')

@section('page-title', 'المستندات المشتركة')

@section('content')
<div class="w-full max-w-full">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">المستندات المشتركة</h1>
            <p class="text-gray-600 text-sm">عقود، عروض، ملفات تسليم — للقراءة والتنزيل فقط.</p>
        </div>
        <a href="{{ route('client.dashboard') }}" class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800">العودة</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right font-semibold text-gray-700">العنوان</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-700">النوع</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-700">التاريخ</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">تنزيل</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($documents as $doc)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $doc->title }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $doc->document_type }}</td>
                            <td class="px-6 py-4 text-gray-600 whitespace-nowrap">{{ $doc->created_at->format('Y/m/d') }}</td>
                            <td class="px-6 py-4 text-left">
                                <a href="{{ route('client.documents.download', $doc) }}" class="text-blue-600 font-semibold hover:underline">تنزيل</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">لا توجد مستندات بعد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t bg-gray-50">{{ $documents->links() }}</div>
    </div>
</div>
@endsection
