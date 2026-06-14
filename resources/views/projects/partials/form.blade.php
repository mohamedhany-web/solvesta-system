@php

    $isEdit = isset($project);

    $statusLabels = [

        'planning' => 'تخطيط',

        'in_progress' => 'قيد التنفيذ',

        'on_hold' => 'معلق',

        'completed' => 'مكتمل',

        'cancelled' => 'ملغي',

    ];

    $priorityLabels = [

        'low' => 'منخفضة',

        'medium' => 'متوسطة',

        'high' => 'عالية',

        'urgent' => 'عاجلة',

    ];

    $typeLabels = [

        'design' => 'تصميم',

        'marketing' => 'تسويق',

        'development' => 'تطوير',

        'maintenance' => 'صيانة',

    ];

    $val = fn ($field, $default = '') => old($field, $isEdit ? ($project->{$field} ?? $default) : $default);

    $dateVal = fn ($field) => old($field, $isEdit && $project->{$field} ? $project->{$field}->format('Y-m-d') : '');

    $selectedDepartmentId = $val('department_id');

@endphp



<form method="POST" action="{{ $action }}" class="space-y-6" id="project-form">

    @csrf

    @if(($method ?? 'POST') !== 'POST')

        @method($method)

    @endif



    @if(request('type') && !$isEdit)

        <input type="hidden" name="project_type" value="{{ request('type') }}">

    @endif



    <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">

            <h2 class="font-bold text-lg text-gray-900">معلومات المشروع</h2>

            <p class="text-sm text-gray-500 mt-0.5">البيانات الأساسية والعميل والقسم المنفّذ</p>

        </div>

        <div class="p-6 space-y-5">

            <div>

                <label class="text-xs font-bold text-gray-600 block mb-1.5">اسم المشروع *</label>

                <input type="text" name="name" value="{{ $val('name') }}" required

                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror"

                       placeholder="أدخل اسم المشروع">

                @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror

            </div>



            <div>

                <label class="text-xs font-bold text-gray-600 block mb-1.5">وصف المشروع *</label>

                <textarea name="description" rows="4" required

                          class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('description') border-red-400 @enderror"

                          placeholder="وصف واضح لنطاق المشروع والأهداف...">{{ $val('description') }}</textarea>

                @error('description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror

            </div>



            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>

                    <label class="text-xs font-bold text-gray-600 block mb-1.5">العميل *</label>

                    <select name="client_id" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('client_id') border-red-400 @enderror">

                        <option value="" disabled @selected(!$val('client_id'))>اختر العميل...</option>

                        @foreach($clients as $client)

                            <option value="{{ $client->id }}" @selected($val('client_id') == $client->id)>

                                {{ $client->name }}@if($client->company_name) — {{ $client->company_name }}@endif

                            </option>

                        @endforeach

                    </select>

                    @error('client_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror

                </div>

                <div>

                    <label class="text-xs font-bold text-gray-600 block mb-1.5">القسم المنفّذ *</label>

                    <select name="department_id" id="department_id" required

                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('department_id') border-red-400 @enderror">

                        <option value="" disabled @selected(!$selectedDepartmentId)>اختر القسم...</option>

                        @foreach($departments as $department)

                            <option value="{{ $department->id }}" @selected($selectedDepartmentId == $department->id)>{{ $department->name }}</option>

                        @endforeach

                    </select>

                    @error('department_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror

                </div>

            </div>



            <div id="department-preview" class="hidden rounded-xl border border-indigo-100 bg-indigo-50/70 p-4 text-sm text-indigo-900">

                <p class="font-bold mb-2">بعد الحفظ — مسار الإسناد</p>

                <ol class="space-y-1.5 list-decimal list-inside">

                    <li>يصل المشروع إلى <strong id="dept-manager-name">رئيس القسم</strong></li>

                    <li>رئيس القسم يعيّن <strong>قائد الفريق (Team Leader)</strong></li>

                    <li>قائد الفريق يقود فريقاً من <strong id="dept-staff-count">0</strong> موظف في القسم</li>

                </ol>

            </div>



            @if($isEdit && $project->projectManager)

            <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4 text-sm">

                <p class="font-bold text-emerald-900 mb-2">الفريق الحالي (يُدار من رئيس القسم)</p>

                <p class="text-emerald-800">قائد الفريق: <strong>{{ $project->projectManager->name }}</strong></p>

                @if($project->teamMembers->isNotEmpty())

                    <p class="text-emerald-800 mt-1">الأعضاء: {{ $project->teamMembers->pluck('name')->join('، ') }}</p>

                @endif

                <p class="text-xs text-emerald-700 mt-2">لتعديل الفريق، رئيس القسم يستخدم لوحة «مدير القسم».</p>

            </div>

            @elseif($isEdit && $project->department_id)

            <div class="rounded-xl border border-amber-100 bg-amber-50 p-4 text-sm text-amber-900">

                <strong>بانتظار الإسناد:</strong> رئيس قسم «{{ $project->department?->name }}» لم يعيّن قائد الفريق والفريق بعد.

            </div>

            @endif



            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>

                    <label class="text-xs font-bold text-gray-600 block mb-1.5">الحالة *</label>

                    <select name="status" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">

                        @foreach($statusLabels as $value => $label)

                            <option value="{{ $value }}" @selected($val('status', 'planning') === $value)>{{ $label }}</option>

                        @endforeach

                    </select>

                </div>

                <div>

                    <label class="text-xs font-bold text-gray-600 block mb-1.5">الأولوية *</label>

                    <select name="priority" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">

                        @foreach($priorityLabels as $value => $label)

                            <option value="{{ $value }}" @selected($val('priority', 'medium') === $value)>{{ $label }}</option>

                        @endforeach

                    </select>

                </div>

            </div>



            @if(!request('type') || $isEdit)

            <div>

                <label class="text-xs font-bold text-gray-600 block mb-1.5">نوع المشروع</label>

                <select name="project_type" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">

                    <option value="" @selected(!$val('project_type'))>— عام —</option>

                    @foreach($typeLabels as $value => $label)

                        <option value="{{ $value }}" @selected($val('project_type') === $value)>{{ $label }}</option>

                    @endforeach

                </select>

            </div>

            @endif

        </div>

    </div>



    <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">

            <h2 class="font-bold text-lg text-gray-900">الجدول الزمني والميزانية</h2>

        </div>

        <div class="p-6 space-y-5">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>

                    <label class="text-xs font-bold text-gray-600 block mb-1.5">تاريخ البداية *</label>

                    <input type="date" name="start_date" value="{{ $dateVal('start_date') }}" required

                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('start_date') border-red-400 @enderror">

                    @error('start_date')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror

                </div>

                <div>

                    <label class="text-xs font-bold text-gray-600 block mb-1.5">تاريخ الانتهاء المتوقع *</label>

                    <input type="date" name="end_date" value="{{ $dateVal('end_date') }}" required

                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('end_date') border-red-400 @enderror">

                    @error('end_date')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror

                </div>

            </div>

            <div>

                <label class="text-xs font-bold text-gray-600 block mb-1.5">الميزانية (ج.م) *</label>

                <input type="number" name="budget" value="{{ $val('budget') }}" step="0.01" min="0" required

                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('budget') border-red-400 @enderror"

                       placeholder="0.00">

                @error('budget')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror

            </div>

        </div>

    </div>



    <div class="flex flex-wrap gap-3">

        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-white font-bold shadow-md hover:opacity-95"

                style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">

            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>

            {{ $submitLabel }}

        </button>

        <a href="{{ $cancelUrl }}" class="inline-flex items-center px-6 py-2.5 rounded-xl border border-gray-300 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50">إلغاء</a>

    </div>

</form>



@push('scripts')

<script>

(function () {

    const select = document.getElementById('department_id');

    const preview = document.getElementById('department-preview');

    const managerEl = document.getElementById('dept-manager-name');

    const staffEl = document.getElementById('dept-staff-count');

    if (!select || !preview) return;



    const baseUrl = @json(url('/projects/department-staff'));



    async function loadDepartment(id) {

        if (!id) {

            preview.classList.add('hidden');

            return;

        }

        try {

            const res = await fetch(`${baseUrl}/${id}`, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });

            if (!res.ok) throw new Error('failed');

            const data = await res.json();

            managerEl.textContent = data.manager?.name ?? 'رئيس القسم (غير معيّن)';

            staffEl.textContent = data.employees_count ?? 0;

            preview.classList.remove('hidden');

        } catch (e) {

            preview.classList.add('hidden');

        }

    }



    select.addEventListener('change', () => loadDepartment(select.value));

    if (select.value) loadDepartment(select.value);

})();

</script>

@endpush

