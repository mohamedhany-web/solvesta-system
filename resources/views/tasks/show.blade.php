@extends('layouts.app')

@section('page-title', 'تفاصيل المهمة')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('tasks.index') }}" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $task->title }}</h1>
                    <p class="text-gray-600">تفاصيل المهمة ومعلوماتها الكاملة</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @can('edit-tasks')
                <a href="{{ route('tasks.edit', $task) }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition-all duration-200 flex items-center shadow-sm">
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    تعديل
                </a>
                @endcan
                @can('delete-tasks')
                <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه المهمة؟');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-xl hover:bg-red-700 transition-all duration-200 flex items-center shadow-sm">
                        <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        حذف
                    </button>
                </form>
                @endcan
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Task Details Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 ml-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    وصف المهمة
                </h3>
                <div class="prose max-w-none">
                    <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $task->description ?? 'لا يوجد وصف لهذه المهمة' }}</p>
                </div>
            </div>

            <!-- Progress Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 ml-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    التقدم
                </h3>
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">نسبة الإنجاز</span>
                        <span class="text-2xl font-bold text-blue-600">{{ $task->progress_percentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-4 rounded-full transition-all duration-500" style="width: {{ $task->progress_percentage }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Time Tracking -->
            @if($task->estimated_hours || $task->actual_hours)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 ml-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    تتبع الوقت
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    @if($task->estimated_hours)
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">الوقت المقدر</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $task->estimated_hours }}</p>
                        <p class="text-xs text-gray-500">ساعة</p>
                    </div>
                    @endif
                    @if($task->actual_hours)
                    <div class="bg-green-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-1">الوقت الفعلي</p>
                        <p class="text-2xl font-bold text-green-600">{{ $task->actual_hours }}</p>
                        <p class="text-xs text-gray-500">ساعة</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Subtasks -->
            @if($task->subtasks->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 ml-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    المهام الفرعية ({{ $task->subtasks->count() }})
                </h3>
                <div class="space-y-3">
                    @foreach($task->subtasks as $subtask)
                    <a href="{{ route('tasks.show', $subtask) }}" class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full bg-{{ $subtask->status_color }}-500"></div>
                                <span class="font-medium text-gray-900">{{ $subtask->title }}</span>
                            </div>
                            <span class="px-3 py-1 bg-{{ $subtask->status_color }}-100 text-{{ $subtask->status_color }}-800 rounded-full text-xs font-medium">
                                {{ $subtask->progress_percentage }}%
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Comments & Updates Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 ml-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    التحديثات والتعليقات
                </h3>
                
                <!-- Add Comment/Update Form -->
                <form id="commentForm" class="mb-6">
                    @csrf
                    <div class="mb-4">
                        <!-- Type Selection -->
                        <div class="mb-3 flex items-center gap-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="update_type" value="update" id="type_update" class="ml-2 text-blue-600 focus:ring-blue-500" checked>
                                <span class="text-sm font-medium text-gray-700">تحديث</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="update_type" value="comment" id="type_comment" class="ml-2 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm font-medium text-gray-700">تعليق</span>
                            </label>
                        </div>
                        <textarea 
                            id="commentText" 
                            name="comment" 
                            rows="3" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                            placeholder="اكتب تحديث أو تعليق هنا..."
                        ></textarea>
                    </div>
                    <button 
                        type="submit" 
                        id="submitButton"
                        class="bg-blue-600 text-white px-6 py-2.5 rounded-xl hover:bg-blue-700 transition-all duration-200 flex items-center gap-2"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        <span id="buttonText">إضافة تحديث</span>
                    </button>
                </form>

                <!-- Comments List -->
                <div class="space-y-4" id="commentsList">
                    @forelse($task->updates->sortByDesc('created_at') as $update)
                    <div class="border border-gray-200 rounded-xl p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-medium flex-shrink-0 {{ $update->type === 'update' ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-blue-500 to-blue-600' }}">
                                @if($update->type === 'update')
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                @else
                                    {{ substr($update->user->name, 0, 1) }}
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <span class="font-semibold text-gray-900">{{ $update->user->name }}</span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium mr-2 {{ $update->type === 'update' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $update->type === 'update' ? 'تحديث' : 'تعليق' }}
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $update->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <p class="text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $update->comment }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p>لا توجد تعليقات أو تحديثات</p>
                        <p class="text-sm mt-1">كن أول من يعلق</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status & Priority -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">معلومات عامة</h3>
                <div class="space-y-4">
                    <!-- Status -->
                    <div>
                        <label class="text-sm font-medium text-gray-600 block mb-2">الحالة</label>
                        <span class="inline-flex items-center px-4 py-2 bg-{{ $task->status_color }}-100 text-{{ $task->status_color }}-800 rounded-lg text-sm font-medium">
                            <span class="w-2 h-2 bg-{{ $task->status_color }}-500 rounded-full ml-2"></span>
                            @switch($task->status)
                                @case('todo') للتنفيذ @break
                                @case('in_progress') قيد التنفيذ @break
                                @case('review') قيد المراجعة @break
                                @case('completed') مكتمل @break
                                @case('cancelled') ملغي @break
                            @endswitch
                        </span>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="text-sm font-medium text-gray-600 block mb-2">الأولوية</label>
                        <span class="inline-flex items-center px-4 py-2 bg-{{ $task->priority_color }}-100 text-{{ $task->priority_color }}-800 rounded-lg text-sm font-medium">
                            @switch($task->priority)
                                @case('low') منخفضة @break
                                @case('medium') متوسطة @break
                                @case('high') عالية @break
                                @case('urgent') عاجلة @break
                            @endswitch
                        </span>
                    </div>

                    <!-- Project -->
                    <div>
                        <label class="text-sm font-medium text-gray-600 block mb-2">المشروع</label>
                        <a href="{{ route('projects.show', $task->project) }}" class="text-blue-600 hover:text-blue-700 font-medium">
                            {{ $task->project->name }}
                        </a>
                    </div>

                    <!-- Assigned To -->
                    <div>
                        <label class="text-sm font-medium text-gray-600 block mb-2">المكلف بالمهمة</label>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-medium">
                                {{ substr($task->assignedTo->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $task->assignedTo->name }}</p>
                                @if($task->assignedTo->employee)
                                <p class="text-xs text-gray-500">{{ $task->assignedTo->employee->position }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Created By -->
                    <div>
                        <label class="text-sm font-medium text-gray-600 block mb-2">منشئ المهمة</label>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-gray-500 to-gray-600 rounded-full flex items-center justify-center text-white font-medium">
                                {{ substr($task->createdBy->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $task->createdBy->name }}</p>
                                <p class="text-xs text-gray-500">{{ $task->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dates -->
                    @if($task->start_date)
                    <div>
                        <label class="text-sm font-medium text-gray-600 block mb-2">تاريخ البدء</label>
                        <div class="flex items-center gap-2 text-gray-900">
                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $task->start_date->format('Y-m-d') }}
                        </div>
                    </div>
                    @endif

                    @if($task->due_date)
                    <div>
                        <label class="text-sm font-medium text-gray-600 block mb-2">تاريخ الاستحقاق</label>
                        <div class="flex items-center gap-2 {{ $task->is_overdue ? 'text-red-600' : 'text-gray-900' }}">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $task->due_date->format('Y-m-d') }}
                            @if($task->is_overdue)
                            <span class="text-xs font-medium">(متأخر)</span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Tags -->
            @if($task->tags && count($task->tags) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">الوسوم</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($task->tags as $tag)
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                        #{{ $tag }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('commentForm');
    const commentText = document.getElementById('commentText');
    const commentsList = document.getElementById('commentsList');
    const typeUpdate = document.getElementById('type_update');
    const typeComment = document.getElementById('type_comment');
    const buttonText = document.getElementById('buttonText');

    // تحديث نص الزر بناءً على النوع المختار
    function updateButtonText() {
        if (typeUpdate.checked) {
            buttonText.textContent = 'إضافة تحديث';
        } else {
            buttonText.textContent = 'إضافة تعليق';
        }
    }

    typeUpdate.addEventListener('change', updateButtonText);
    typeComment.addEventListener('change', updateButtonText);

    commentForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const comment = commentText.value.trim();
        const updateType = typeUpdate.checked ? 'update' : 'comment';
        
        if (!comment) {
            alert('الرجاء إدخال ' + (updateType === 'update' ? 'تحديث' : 'تعليق'));
            return;
        }

        // Show loading state
        const submitBtn = document.getElementById('submitButton');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>';

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch('{{ route("tasks.updates.store", $task) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ 
                comment: comment,
                type: updateType
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the page to show the new comment/update
                location.reload();
            } else {
                alert(data.error || 'حدث خطأ في الإضافة');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في الإضافة');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });
});
</script>
@endpush

