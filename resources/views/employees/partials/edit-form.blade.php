@php
    $themeColor = $themeColor ?? \App\Helpers\SettingsHelper::getThemeColor();
    $inputClass = 'w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:border-transparent';
    $labelClass = 'text-xs font-bold text-gray-600 block mb-1.5';
    $employmentTypes = [
        'full_time' => 'دوام كامل',
        'part_time' => 'دوام جزئي',
        'contract' => 'عقد',
        'intern' => 'متدرب',
    ];
    $statuses = [
        'active' => 'نشط',
        'on_leave' => 'في إجازة',
        'inactive' => 'غير نشط',
        'terminated' => 'مفصول',
    ];
    $fullName = trim($employee->first_name.' '.$employee->last_name);
@endphp

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if(($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    @if($employee->user)
    <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-900">
        <p class="font-bold mb-1">المستخدم المرتبط: {{ $employee->user->name }}</p>
        <p class="text-blue-800/90">{{ $employee->user->email }} — يُحدَّث الاسم والبريد تلقائياً عند الحفظ.</p>
        <a href="{{ route('users.show', $employee->user) }}" class="text-xs font-bold mt-2 inline-block hover:underline" style="color: {{ $themeColor }};">عرض حساب المستخدم →</a>
    </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">المعلومات الشخصية</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="employee_id" class="{{ $labelClass }}">رقم الموظف <span class="text-red-500">*</span></label>
                    <input type="text" name="employee_id" id="employee_id" value="{{ old('employee_id', $employee->employee_id) }}" required dir="ltr"
                           class="{{ $inputClass }} font-mono @error('employee_id') border-red-500 @enderror"
                           style="--tw-ring-color: {{ $themeColor }};">
                    @error('employee_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="first_name" class="{{ $labelClass }}">الاسم الأول <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $employee->first_name) }}" required
                           class="{{ $inputClass }} @error('first_name') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                    @error('first_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="last_name" class="{{ $labelClass }}">اسم العائلة <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $employee->last_name) }}" required
                           class="{{ $inputClass }} @error('last_name') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                    @error('last_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="{{ $labelClass }}">البريد الإلكتروني <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $employee->email) }}" required dir="ltr"
                           class="{{ $inputClass }} @error('email') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                    @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="{{ $labelClass }}">رقم الهاتف <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $employee->phone) }}" required dir="ltr"
                           class="{{ $inputClass }} @error('phone') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                    @error('phone')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="address" class="{{ $labelClass }}">العنوان</label>
                    <input type="text" name="address" id="address" value="{{ old('address', $employee->address) }}"
                           class="{{ $inputClass }} @error('address') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                    @error('address')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">المعلومات الوظيفية</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="position" class="{{ $labelClass }}">المسمى الوظيفي <span class="text-red-500">*</span></label>
                    <input type="text" name="position" id="position" value="{{ old('position', $employee->position) }}" required
                           class="{{ $inputClass }} @error('position') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                    @error('position')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="department_id" class="{{ $labelClass }}">القسم <span class="text-red-500">*</span></label>
                    <select name="department_id" id="department_id" required
                            class="{{ $inputClass }} @error('department_id') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                        <option value="">اختر القسم</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected(old('department_id', $employee->department_id) == $department->id)>
                                {{ $department->parent ? $department->parent->name.' › ' : '' }}{{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                @if($employee->user)
                <div>
                    <label for="system_role" class="{{ $labelClass }}">الدور / الصلاحيات</label>
                    <select name="system_role" id="system_role" class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};">
                        @foreach(($assignableRoles ?? []) as $role)
                            <option value="{{ $role }}" @selected(old('system_role', $currentRole ?? '') === $role)>
                                {{ ($roleLabels ?? [])[$role] ?? $role }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div>
                    <label for="salary" class="{{ $labelClass }}">الراتب الشهري (ج.م) <span class="text-red-500">*</span></label>
                    <input type="number" name="salary" id="salary" value="{{ old('salary', $employee->salary) }}" required min="0" step="0.01"
                           class="{{ $inputClass }} @error('salary') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                    @error('salary')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="daily_hours" class="{{ $labelClass }}">ساعات العمل اليومية <span class="text-red-500">*</span></label>
                    <input type="number" name="daily_hours" id="daily_hours" value="{{ old('daily_hours', $employee->daily_hours ?? 8) }}" required min="1" max="12"
                           class="{{ $inputClass }} @error('daily_hours') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                    @error('daily_hours')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="hire_date" class="{{ $labelClass }}">تاريخ التوظيف <span class="text-red-500">*</span></label>
                    <input type="date" name="hire_date" id="hire_date"
                           value="{{ old('hire_date', $employee->hire_date?->format('Y-m-d')) }}" required
                           class="{{ $inputClass }} @error('hire_date') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                    @error('hire_date')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="employment_type" class="{{ $labelClass }}">نوع التوظيف <span class="text-red-500">*</span></label>
                    <select name="employment_type" id="employment_type" required
                            class="{{ $inputClass }} @error('employment_type') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                        @foreach($employmentTypes as $value => $label)
                            <option value="{{ $value }}" @selected(old('employment_type', $employee->employment_type) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('employment_type')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="status" class="{{ $labelClass }}">الحالة <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                            class="{{ $inputClass }} @error('status') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                        @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $employee->status) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">التدرج الوظيفي</h2>
            <p class="text-sm text-gray-500 mt-0.5">من يراجع تقاريره اليومية؟</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="flex items-center gap-2 cursor-pointer mb-4">
                        <input type="checkbox" name="is_team_lead" value="1" @checked(old('is_team_lead', $employee->is_team_lead))
                               class="h-4 w-4 rounded border-gray-300" style="color: {{ $themeColor }};">
                        <span class="text-sm font-semibold text-gray-800">قائد فريق (Team Lead)</span>
                    </label>
                </div>
                <div>
                    <label for="supervisor_user_id" class="{{ $labelClass }}">المشرف المباشر</label>
                    <select name="supervisor_user_id" id="supervisor_user_id"
                            class="{{ $inputClass }} @error('supervisor_user_id') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                        <option value="">رئيس القسم (افتراضي)</option>
                        @foreach(($supervisorOptions ?? collect()) as $supervisor)
                            @if($supervisor->id !== $employee->user_id)
                            <option value="{{ $supervisor->id }}" @selected(old('supervisor_user_id', $employee->supervisor_user_id) == $supervisor->id)>{{ $supervisor->name }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('supervisor_user_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="career_level" class="{{ $labelClass }}">المستوى الوظيفي (Pipeline)</label>
                    <select name="career_level" id="career_level" class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};">
                        <option value="{{ old('career_level', $employee->career_level) }}">{{ old('career_level', $employee->career_level) ?: '—' }}</option>
                        @foreach(($deptProfile['levels'] ?? []) as $level)
                            @if($level !== $employee->career_level)
                            <option value="{{ $level }}">{{ $level }}</option>
                            @endif
                        @endforeach
                    </select>
                    @if($employee->career_track)
                    <p class="text-xs text-gray-500 mt-1">مسار: {{ $employee->career_track }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">جهة الاتصال للطوارئ</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="emergency_contact" class="{{ $labelClass }}">الاسم</label>
                    <input type="text" name="emergency_contact" id="emergency_contact" value="{{ old('emergency_contact', $employee->emergency_contact) }}"
                           class="{{ $inputClass }} @error('emergency_contact') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                    @error('emergency_contact')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="emergency_phone" class="{{ $labelClass }}">الهاتف</label>
                    <input type="text" name="emergency_phone" id="emergency_phone" value="{{ old('emergency_phone', $employee->emergency_phone) }}" dir="ltr"
                           class="{{ $inputClass }} @error('emergency_phone') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                    @error('emergency_phone')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
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
