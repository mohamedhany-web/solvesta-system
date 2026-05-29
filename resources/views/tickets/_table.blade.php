@php
    $statusColors = [
        'open' => 'bg-red-100 text-red-800',
        'in_progress' => 'bg-yellow-100 text-yellow-800',
        'pending_client' => 'bg-orange-100 text-orange-800',
        'resolved' => 'bg-blue-100 text-blue-800',
        'closed' => 'bg-green-100 text-green-800',
    ];
    $priorityColors = [
        'low' => 'bg-green-100 text-green-800',
        'medium' => 'bg-yellow-100 text-yellow-800',
        'high' => 'bg-orange-100 text-orange-800',
        'critical' => 'bg-red-100 text-red-800',
    ];
    $priorityNames = [
        'low' => 'منخفضة',
        'medium' => 'متوسطة',
        'high' => 'عالية',
        'critical' => 'حرجة',
    ];
@endphp
<div class="overflow-x-auto">
    <table class="w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم التذكرة</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الموضوع</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">العميل</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الأولوية</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">المكلف</th>
                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">إجراءات</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            @forelse($tickets as $ticket)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-900">{{ $ticket->ticket_number }}</div>
                    <div class="text-xs text-gray-500">{{ $ticket->created_at->diffForHumans() }}</div>
                </td>
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-900">{{ Str::limit($ticket->subject, 45) }}</div>
                    <div class="text-xs text-gray-500">{{ $ticket->category_name }}</div>
                </td>
                <td class="px-4 py-3 text-gray-900">{{ $ticket->client?->name ?? '—' }}</td>
                <td class="px-4 py-3">
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $priorityColors[$ticket->priority] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $priorityNames[$ticket->priority] ?? $ticket->priority }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $ticket->status_name }}
                    </span>
                </td>
                <td class="px-4 py-3 text-gray-700">{{ $ticket->assignedTo?->name ?? 'غير مكلف' }}</td>
                <td class="px-4 py-3">
                    <div class="flex gap-2">
                        <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600 hover:underline font-semibold">عرض</a>
                        @can('edit-tickets')
                        <a href="{{ route('tickets.edit', $ticket) }}" class="text-green-600 hover:underline font-semibold">تعديل</a>
                        @endcan
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-6 text-center text-gray-500">لا توجد تذاكر في هذا القسم</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
