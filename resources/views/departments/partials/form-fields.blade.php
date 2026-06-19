@php
    $selectedModules = old('sidebar_modules', $department->sidebar_modules ?? []);
    $parentDepartments = $parentDepartments ?? collect();
@endphp

<div class="md:col-span-2">
    <label class="block text-sm font-semibold text-gray-700 mb-2">القسم الرئيسي (اختياري)</label>
    <select name="parent_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <option value="">قسم رئيسي مستقل</option>
        @foreach($parentDepartments as $parent)
            <option value="{{ $parent->id }}" @selected(old('parent_id', $department->parent_id ?? null) == $parent->id)>
                {{ $parent->name }} ({{ $parent->code }})
            </option>
        @endforeach
    </select>
    <p class="text-xs text-gray-500 mt-1">مثال: «تطوير الويب» تحت «قسم التطوير»</p>
    @error('parent_id')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
</div>

<div class="md:col-span-2">
    <label class="block text-sm font-semibold text-gray-700 mb-2">ما يظهر لموظفي هذا القسم في القائمة الجانبية</label>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-4 border border-gray-200 rounded-xl bg-gray-50/80">
        @foreach(\App\Models\Department::SIDEBAR_MODULES as $key => $label)
            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                <input type="checkbox" name="sidebar_modules[]" value="{{ $key }}"
                       @checked(in_array($key, (array) $selectedModules, true))
                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <span>{{ $label }}</span>
            </label>
        @endforeach
    </div>
    <p class="text-xs text-gray-500 mt-2">اترك الكل غير محدد لوراثة الإعدادات من القسم الرئيسي. الأقسام التقنية عادة: مشاريع + مهام + بيئة التطوير + GitHub.</p>
    @error('sidebar_modules')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">الدور الافتراضي عند إنشاء موظف</label>
    <select name="default_role" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        <option value="">— يدوي —</option>
        @foreach(\App\Services\DepartmentProfileService::assignableRoles() as $role)
            <option value="{{ $role }}" @selected(old('default_role', $department->default_role ?? null) === $role)>
                {{ (\App\Services\DepartmentProfileService::roleLabels())[$role] ?? $role }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">المسمى الافتراضي (مثال: مبرمج)</label>
    <input type="text" name="default_position" value="{{ old('default_position', $department->default_position ?? '') }}"
           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="مبرمج ويب">
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">قالب KPI</label>
    <select name="kpi_profile" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        <option value="">افتراضي</option>
        @foreach(\App\Services\DepartmentProfileService::kpiProfileLabels() as $key => $label)
            <option value="{{ $key }}" @selected(old('kpi_profile', $department->kpi_profile ?? null) === $key)>{{ $label }}</option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">مسار الترقية (Career Track)</label>
    <input type="text" name="career_track" value="{{ old('career_track', $department->career_track ?? '') }}"
           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="technical, sales, hr...">
</div>
