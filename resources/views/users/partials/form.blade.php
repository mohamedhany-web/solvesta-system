@php
    $isEdit = isset($user);
    $employee = $isEdit ? $user->employee : null;
    $val = fn ($field, $default = '') => old($field, $isEdit && $employee ? ($employee->{$field} ?? $default) : ($field === 'name' || $field === 'email' ? ($isEdit ? $user->{$field} : $default) : $default));
    $userVal = fn ($field) => old($field, $isEdit ? $user->{$field} : '');
    $employmentTypes = ['full_time' => 'دوام كامل', 'part_time' => 'دوام جزئي', 'contract' => 'عقد', 'intern' => 'متدرب'];
@endphp

<form method="POST" action="{{ $action }}" class="space-y-6">
    @csrf
    @if(($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">حساب المستخدم</h2>
            <p class="text-sm text-gray-500 mt-0.5">بيانات الدخول والأدوار</p>
        </div>
        <div class="p-6 space-y-5">
            <div>
                <label class="text-xs font-bold text-gray-600 block mb-1.5">الاسم الكامل *</label>
                <input type="text" name="name" value="{{ $userVal('name') }}" required
                       class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('name') border-red-400 @enderror">
                @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1.5">البريد الإلكتروني *</label>
                    <input type="email" name="email" value="{{ $userVal('email') }}" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror">
                    @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1.5">الأدوار *</label>
                    <select name="roles[]" multiple required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm min-h-[100px] focus:ring-2 focus:ring-blue-500 @error('roles') border-red-400 @enderror">
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}"
                                @selected($isEdit ? $user->hasRole($role->name) : in_array($role->name, old('roles', [])))>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Ctrl / Cmd للاختيار المتعدد</p>
                    @error('roles')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1.5">كلمة المرور {{ $isEdit ? '' : '*' }}</label>
                    <input type="password" name="password" {{ $isEdit ? '' : 'required' }}
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('password') border-red-400 @enderror"
                           placeholder="{{ $isEdit ? 'اتركها فارغة بدون تغيير' : '••••••••' }}">
                    @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1.5">تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation"
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500"
                           placeholder="{{ $isEdit ? 'للتأكيد فقط' : '••••••••' }}">
                </div>
            </div>
        </div>
    </div>

    @if(!$isEdit || $employee)
    <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">بيانات الموظف</h2>
            <p class="text-sm text-gray-500 mt-0.5">الربط بالقسم والمسمى الوظيفي</p>
        </div>
        <div class="p-6 space-y-5">
            @if(!$isEdit)
            <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-800">
                الرقم التوظيفي يُولَّد تلقائياً عند الحفظ.
            </div>
            @elseif($employee?->employee_id)
            <div class="text-sm text-gray-600">الرقم التوظيفي: <strong class="font-mono">{{ $employee->employee_id }}</strong></div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1.5">الاسم الأول *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $employee?->first_name) }}" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('first_name') border-red-400 @enderror">
                    @error('first_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1.5">الاسم الأخير *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $employee?->last_name) }}" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('last_name') border-red-400 @enderror">
                    @error('last_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1.5">الهاتف *</label>
                    <input type="text" name="phone" value="{{ old('phone', $employee?->phone) }}" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('phone') border-red-400 @enderror">
                    @error('phone')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1.5">القسم *</label>
                    <select name="department_id" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('department_id') border-red-400 @enderror">
                        <option value="" disabled @selected(!old('department_id', $employee?->department_id))>اختر القسم...</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" @selected(old('department_id', $employee?->department_id) == $department->id)>{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1.5">المسمى الوظيفي *</label>
                    <input type="text" name="position" value="{{ old('position', $employee?->position) }}" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('position') border-red-400 @enderror">
                    @error('position')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1.5">الراتب (ج.م) *</label>
                    <input type="number" name="salary" value="{{ old('salary', $employee?->salary) }}" step="0.01" min="0" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('salary') border-red-400 @enderror">
                    @error('salary')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                @if(!$isEdit)
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1.5">تاريخ التعيين *</label>
                    <input type="date" name="hire_date" value="{{ old('hire_date', date('Y-m-d')) }}" required
                           class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('hire_date') border-red-400 @enderror">
                    @error('hire_date')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                @endif
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1.5">نوع التوظيف *</label>
                    <select name="employment_type" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                        @foreach($employmentTypes as $value => $label)
                            <option value="{{ $value }}" @selected(old('employment_type', $employee?->employment_type ?? 'full_time') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                @if($isEdit && $employee)
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1.5">حالة الموظف *</label>
                    <select name="status" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500">
                        <option value="active" @selected(old('status', $employee->status) === 'active')>نشط</option>
                        <option value="inactive" @selected(old('status', $employee->status) === 'inactive')>غير نشط</option>
                        <option value="terminated" @selected(old('status', $employee->status) === 'terminated')>مفصول</option>
                    </select>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-white font-bold shadow-md hover:opacity-95"
                style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            {{ $submitLabel }}
        </button>
        <a href="{{ $cancelUrl }}" class="inline-flex items-center px-6 py-2.5 rounded-xl border border-gray-300 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50">إلغاء</a>
    </div>
</form>
