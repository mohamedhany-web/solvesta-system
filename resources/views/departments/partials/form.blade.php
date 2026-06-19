@php
    $themeColor = $themeColor ?? \App\Helpers\SettingsHelper::getThemeColor();
    $inputClass = 'w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:border-transparent';
    $labelClass = 'text-xs font-bold text-gray-600 block mb-1.5';
    $selectedModules = old('sidebar_modules', $department->sidebar_modules ?? []);
    $parentDepartments = $parentDepartments ?? collect();
@endphp

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if(($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    {{-- معلومات أساسية --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">المعلومات الأساسية</h2>
            <p class="text-sm text-gray-500 mt-0.5">الاسم، الكود، والوصف</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label for="name" class="{{ $labelClass }}">اسم القسم <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $department->name) }}" required
                           class="{{ $inputClass }} @error('name') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                    @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="code" class="{{ $labelClass }}">كود القسم <span class="text-red-500">*</span></label>
                    <input type="text" name="code" id="code" value="{{ old('code', $department->code) }}" required dir="ltr"
                           class="{{ $inputClass }} font-mono uppercase @error('code') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                    @error('code')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2 cursor-pointer pb-2.5">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               @checked(old('is_active', $department->is_active ?? true))
                               class="h-4 w-4 rounded border-gray-300" style="color: {{ $themeColor }};">
                        <span class="text-sm font-semibold text-gray-800">القسم نشط</span>
                    </label>
                </div>
                <div class="sm:col-span-2">
                    <label for="description" class="{{ $labelClass }}">الوصف</label>
                    <textarea name="description" id="description" rows="3"
                              class="{{ $inputClass }} @error('description') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">{{ old('description', $department->description) }}</textarea>
                    @error('description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- الهيكل التنظيمي --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">الهيكل التنظيمي</h2>
            <p class="text-sm text-gray-500 mt-0.5">القسم الرئيسي ومدير القسم</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="parent_id" class="{{ $labelClass }}">القسم الرئيسي</label>
                    <select name="parent_id" id="parent_id" class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};">
                        <option value="">قسم رئيسي مستقل</option>
                        @foreach($parentDepartments as $parent)
                            <option value="{{ $parent->id }}" @selected(old('parent_id', $department->parent_id ?? null) == $parent->id)>
                                {{ $parent->name }} ({{ $parent->code }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">مثال: «تطوير الويب» تحت «قسم التطوير»</p>
                    @error('parent_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="manager_id" class="{{ $labelClass }}">مدير القسم</label>
                    <select name="manager_id" id="manager_id" class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};">
                        <option value="">بدون مدير محدد</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" @selected(old('manager_id', $department->manager_id) == $employee->id)>
                                {{ $employee->first_name }} {{ $employee->last_name }} — {{ $employee->position }}
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ملف الموظف الافتراضي --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">ملف الموظف الافتراضي</h2>
            <p class="text-sm text-gray-500 mt-0.5">يُطبَّق تلقائياً عند إنشاء موظف في هذا القسم</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="default_role" class="{{ $labelClass }}">الدور / الصلاحيات</label>
                    <select name="default_role" id="default_role" class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};">
                        <option value="">يُحدد يدوياً</option>
                        @foreach(\App\Services\DepartmentProfileService::assignableRoles() as $role)
                            <option value="{{ $role }}" @selected(old('default_role', $department->default_role ?? null) === $role)>
                                {{ (\App\Services\DepartmentProfileService::roleLabels())[$role] ?? $role }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="default_position" class="{{ $labelClass }}">المسمى الافتراضي</label>
                    <input type="text" name="default_position" id="default_position"
                           value="{{ old('default_position', $department->default_position ?? '') }}"
                           class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};" placeholder="مثال: مبرمج ويب">
                </div>
                <div>
                    <label for="kpi_profile" class="{{ $labelClass }}">قالب KPI</label>
                    <select name="kpi_profile" id="kpi_profile" class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};">
                        <option value="">افتراضي</option>
                        @foreach(\App\Services\DepartmentProfileService::kpiProfileLabels() as $key => $label)
                            <option value="{{ $key }}" @selected(old('kpi_profile', $department->kpi_profile ?? null) === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="career_track" class="{{ $labelClass }}">مسار الترقية</label>
                    <input type="text" name="career_track" id="career_track"
                           value="{{ old('career_track', $department->career_track ?? '') }}"
                           class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};" placeholder="technical, sales, hr...">
                </div>
            </div>
        </div>
    </div>

    {{-- صلاحيات السايدبار --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">القائمة الجانبية للموظفين</h2>
            <p class="text-sm text-gray-500 mt-0.5">ما يراه موظفو هذا القسم في النظام</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 p-4 border border-gray-200 rounded-xl bg-gray-50/80">
                @foreach(\App\Models\Department::SIDEBAR_MODULES as $key => $label)
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer rounded-lg px-2 py-1.5 hover:bg-white">
                        <input type="checkbox" name="sidebar_modules[]" value="{{ $key }}"
                               @checked(in_array($key, (array) $selectedModules, true))
                               class="h-4 w-4 rounded border-gray-300" style="color: {{ $themeColor }};">
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            <p class="text-xs text-gray-500 mt-3">اترك الكل غير محدد لوراثة الإعدادات من القسم الرئيسي.</p>
            @error('sidebar_modules')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- التواصل --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">بيانات التواصل</h2>
            <p class="text-sm text-gray-500 mt-0.5">اختياري</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="location" class="{{ $labelClass }}">الموقع</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $department->location) }}"
                           class="{{ $inputClass }} @error('location') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                    @error('location')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="{{ $labelClass }}">الهاتف</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $department->phone) }}" dir="ltr"
                           class="{{ $inputClass }} @error('phone') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                    @error('phone')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="email" class="{{ $labelClass }}">البريد الإلكتروني</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $department->email) }}" dir="ltr"
                           class="{{ $inputClass }} @error('email') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                    @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
        <a href="{{ $cancelUrl }}" class="inline-flex items-center justify-center gap-2 border border-gray-300 bg-white px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">إلغاء</a>
        <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm hover:opacity-95"
                style="background: {{ $themeColor }};">{{ $submitLabel }}</button>
    </div>
</form>
