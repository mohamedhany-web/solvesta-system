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
@endphp

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if(($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    {{-- حساب المستخدم --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">حساب المستخدم</h2>
            <p class="text-sm text-gray-500 mt-0.5">ربط بمستخدم موجود أو إنشاء حساب دخول جديد</p>
        </div>
        <div class="p-6 space-y-5">
            <label class="flex items-start gap-3 p-4 rounded-xl border-2 border-gray-200 cursor-pointer has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50/50 transition">
                <input type="checkbox" name="create_new_user" id="create_new_user" value="1"
                       class="mt-1 w-4 h-4 rounded text-blue-600"
                       {{ old('create_new_user') ? 'checked' : '' }}
                       onchange="toggleUserSelection()">
                <div>
                    <span class="block text-sm font-bold text-gray-900">إنشاء حساب مستخدم جديد تلقائياً</span>
                    <span class="text-xs text-gray-500 mt-0.5 block">يُنشأ حساب دخول بنفس البريد والبيانات المدخلة</span>
                </div>
            </label>

            <div id="user_selection_container">
                <label for="user_id" class="{{ $labelClass }}">اختيار مستخدم موجود <span class="text-red-500">*</span></label>
                <select name="user_id" id="user_id"
                        class="{{ $inputClass }} @error('user_id') border-red-500 @enderror"
                        style="--tw-ring-color: {{ $themeColor }};">
                    <option value="">اختر المستخدم</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>

            <div id="password_fields" class="hidden">
                <div class="rounded-xl border border-amber-200 bg-amber-50/60 p-4 space-y-4">
                    <p class="text-sm font-bold text-amber-900">بيانات حساب المستخدم الجديد</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="{{ $labelClass }}">كلمة المرور <span class="text-red-500">*</span></label>
                            <input type="password" name="password" id="password"
                                   class="{{ $inputClass }} @error('password') border-red-500 @enderror"
                                   style="--tw-ring-color: {{ $themeColor }};" placeholder="••••••••">
                            @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="{{ $labelClass }}">تأكيد كلمة المرور <span class="text-red-500">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};" placeholder="••••••••">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- المعلومات الشخصية --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">المعلومات الشخصية</h2>
            <p class="text-sm text-gray-500 mt-0.5">الاسم والتواصل والعنوان</p>
        </div>
        <div class="p-6 space-y-5">
            <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                الرقم التوظيفي يُولَّد تلقائياً عند الحفظ.
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="{{ $labelClass }}">الاسم الأول <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                           class="{{ $inputClass }} @error('first_name') border-red-500 @enderror"
                           style="--tw-ring-color: {{ $themeColor }};" placeholder="الاسم الأول">
                    @error('first_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="last_name" class="{{ $labelClass }}">اسم العائلة <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required
                           class="{{ $inputClass }} @error('last_name') border-red-500 @enderror"
                           style="--tw-ring-color: {{ $themeColor }};" placeholder="اسم العائلة">
                    @error('last_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="{{ $labelClass }}">البريد الإلكتروني <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required dir="ltr"
                           class="{{ $inputClass }} @error('email') border-red-500 @enderror"
                           style="--tw-ring-color: {{ $themeColor }};" placeholder="name@company.com">
                    @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="phone" class="{{ $labelClass }}">رقم الهاتف <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required dir="ltr"
                           class="{{ $inputClass }} @error('phone') border-red-500 @enderror"
                           style="--tw-ring-color: {{ $themeColor }};" placeholder="+20...">
                    @error('phone')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="address" class="{{ $labelClass }}">العنوان</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}"
                           class="{{ $inputClass }} @error('address') border-red-500 @enderror"
                           style="--tw-ring-color: {{ $themeColor }};" placeholder="العنوان الكامل">
                    @error('address')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- المعلومات الوظيفية --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">المعلومات الوظيفية</h2>
            <p class="text-sm text-gray-500 mt-0.5">القسم، الراتب، ونوع التوظيف</p>
        </div>
        <div class="p-6 space-y-4">
            <div id="dept-profile-box" class="hidden rounded-xl border border-indigo-100 bg-indigo-50/60 p-4 text-sm text-indigo-900">
                <p class="font-bold mb-2">ملف القسم المختار</p>
                <p id="dept-profile-kpi" class="text-xs mb-2"></p>
                <p id="dept-profile-modules" class="text-xs"></p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="department_id" class="{{ $labelClass }}">القسم <span class="text-red-500">*</span></label>
                    <select name="department_id" id="department_id" required
                            class="{{ $inputClass }} @error('department_id') border-red-500 @enderror"
                            style="--tw-ring-color: {{ $themeColor }};" onchange="loadDepartmentProfile(this.value)">
                        <option value="">اختر القسم</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected(old('department_id', $preselectedDepartmentId ?? null) == $department->id)>
                                {{ $department->parent ? $department->parent->name.' › ' : '' }}{{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="system_role" class="{{ $labelClass }}">الدور / الصلاحيات <span class="text-red-500">*</span></label>
                    <select name="system_role" id="system_role" required class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};">
                        @foreach(($assignableRoles ?? []) as $role)
                            <option value="{{ $role }}" @selected(old('system_role') === $role)>
                                {{ ($roleLabels ?? [])[$role] ?? $role }}
                            </option>
                        @endforeach
                    </select>
                    @error('system_role')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="position" class="{{ $labelClass }}">المسمى الوظيفي <span class="text-red-500">*</span></label>
                    <input type="text" name="position" id="position" value="{{ old('position') }}" required
                           class="{{ $inputClass }} @error('position') border-red-500 @enderror"
                           style="--tw-ring-color: {{ $themeColor }};" placeholder="مثال: مبرمج ويب">
                    @error('position')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="career_level" class="{{ $labelClass }}">المستوى الوظيفي (Pipeline)</label>
                    <select name="career_level" id="career_level" class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};">
                        <option value="">يُحدد تلقائياً</option>
                    </select>
                </div>
                <div>
                    <label for="salary" class="{{ $labelClass }}">الراتب الشهري (ج.م) <span class="text-red-500">*</span></label>
                    <input type="number" name="salary" id="salary" value="{{ old('salary') }}" required min="0" step="0.01"
                           class="{{ $inputClass }} @error('salary') border-red-500 @enderror"
                           style="--tw-ring-color: {{ $themeColor }};" placeholder="0.00">
                    @error('salary')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="daily_hours" class="{{ $labelClass }}">عدد الساعات اليومية <span class="text-red-500">*</span></label>
                    <input type="number" name="daily_hours" id="daily_hours" value="{{ old('daily_hours', 8) }}" required min="1" max="12"
                           class="{{ $inputClass }} @error('daily_hours') border-red-500 @enderror"
                           style="--tw-ring-color: {{ $themeColor }};">
                    @error('daily_hours')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="hire_date" class="{{ $labelClass }}">تاريخ التوظيف <span class="text-red-500">*</span></label>
                    <input type="date" name="hire_date" id="hire_date" value="{{ old('hire_date') }}" required
                           class="{{ $inputClass }} @error('hire_date') border-red-500 @enderror"
                           style="--tw-ring-color: {{ $themeColor }};">
                    @error('hire_date')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="employment_type" class="{{ $labelClass }}">نوع التوظيف <span class="text-red-500">*</span></label>
                    <select name="employment_type" id="employment_type" required
                            class="{{ $inputClass }} @error('employment_type') border-red-500 @enderror"
                            style="--tw-ring-color: {{ $themeColor }};">
                        <option value="">اختر نوع التوظيف</option>
                        @foreach($employmentTypes as $value => $label)
                            <option value="{{ $value }}" @selected(old('employment_type') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('employment_type')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- الطوارئ --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">جهة الاتصال للطوارئ</h2>
            <p class="text-sm text-gray-500 mt-0.5">اختياري — للحالات الطارئة</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="emergency_contact" class="{{ $labelClass }}">اسم جهة الاتصال</label>
                    <input type="text" name="emergency_contact" id="emergency_contact" value="{{ old('emergency_contact') }}"
                           class="{{ $inputClass }} @error('emergency_contact') border-red-500 @enderror"
                           style="--tw-ring-color: {{ $themeColor }};">
                    @error('emergency_contact')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="emergency_phone" class="{{ $labelClass }}">رقم هاتف الطوارئ</label>
                    <input type="text" name="emergency_phone" id="emergency_phone" value="{{ old('emergency_phone') }}" dir="ltr"
                           class="{{ $inputClass }} @error('emergency_phone') border-red-500 @enderror"
                           style="--tw-ring-color: {{ $themeColor }};">
                    @error('emergency_phone')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 pt-2">
        <a href="{{ $cancelUrl }}" class="inline-flex items-center justify-center gap-2 border border-gray-300 bg-white px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">
            إلغاء
        </a>
        <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm hover:opacity-95 transition"
                style="background: {{ $themeColor }};">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            {{ $submitLabel }}
        </button>
    </div>
</form>

@push('scripts')
<script>
function toggleUserSelection() {
    const createNewUser = document.getElementById('create_new_user');
    const userSelection = document.getElementById('user_selection_container');
    const passwordFields = document.getElementById('password_fields');
    const userSelect = document.getElementById('user_id');
    const passwordInput = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');

    if (createNewUser.checked) {
        userSelection.classList.add('hidden');
        passwordFields.classList.remove('hidden');
        userSelect.required = false;
        passwordInput.required = true;
        passwordConfirmation.required = true;
        userSelect.value = '';
    } else {
        userSelection.classList.remove('hidden');
        passwordFields.classList.add('hidden');
        userSelect.required = true;
        passwordInput.required = false;
        passwordConfirmation.required = false;
        passwordInput.value = '';
        passwordConfirmation.value = '';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('create_new_user')?.checked) {
        toggleUserSelection();
    }
});
</script>
@endpush
