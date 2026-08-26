@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $activeContract = $employee->activeEmploymentContract();
    $profileComplete = $employee->isOnboardingProfileComplete();
@endphp

<div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 class="font-bold text-lg text-gray-900">تسكين الموظف — التقدم</h2>
            <p class="text-sm text-gray-500 mt-0.5">البيانات الكاملة → البطاقة → عقد التوظيف → الراتب في المحاسبة</p>
        </div>
    </div>
    <div class="p-6">
        <ol class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
            <li class="rounded-xl border p-4 {{ $profileComplete ? 'border-emerald-200 bg-emerald-50/60' : 'border-amber-200 bg-amber-50/40' }}">
                <p class="font-bold text-gray-900 mb-1">① بيانات HR + البطاقة</p>
                <p class="text-gray-600 text-xs">{{ $profileComplete ? 'مكتمل' : 'مطلوب: الرقم القومي، الاسم الرباعي، صورتي البطاقة' }}</p>
            </li>
            <li class="rounded-xl border p-4 {{ $activeContract ? 'border-emerald-200 bg-emerald-50/60' : 'border-gray-200 bg-gray-50/50' }}">
                <p class="font-bold text-gray-900 mb-1">② عقد التوظيف</p>
                <p class="text-gray-600 text-xs">{{ $activeContract ? 'عقد ساري: '.$activeContract->contract_number : 'لم يُفعّل عقد بعد' }}</p>
            </li>
            <li class="rounded-xl border p-4 {{ $activeContract ? 'border-emerald-200 bg-emerald-50/60' : 'border-gray-200 bg-gray-50/50' }}">
                <p class="font-bold text-gray-900 mb-1">③ الراتب (المحاسبة)</p>
                <p class="text-gray-600 text-xs">{{ $activeContract ? number_format($employee->effectiveSalary(), 2).' ج.م / شهر' : 'يُربط تلقائياً عند تفعيل العقد' }}</p>
            </li>
        </ol>
    </div>
</div>

@if($employee->id_photo_front_path || $employee->id_photo_back_path)
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
        <h2 class="font-bold text-lg text-gray-900">صور بطاقة الرقم القومي</h2>
    </div>
    <div class="p-6 flex flex-wrap gap-6">
        @if($employee->id_photo_front_path)
        <div>
            <p class="text-xs font-bold text-gray-500 mb-2">الوجه</p>
            <a href="{{ asset('storage/'.$employee->id_photo_front_path) }}" target="_blank">
                <img src="{{ asset('storage/'.$employee->id_photo_front_path) }}" alt="وجه البطاقة" class="h-40 rounded-xl border border-gray-200 object-cover shadow-sm hover:opacity-90">
            </a>
        </div>
        @endif
        @if($employee->id_photo_back_path)
        <div>
            <p class="text-xs font-bold text-gray-500 mb-2">الظهر</p>
            <a href="{{ asset('storage/'.$employee->id_photo_back_path) }}" target="_blank">
                <img src="{{ asset('storage/'.$employee->id_photo_back_path) }}" alt="ظهر البطاقة" class="h-40 rounded-xl border border-gray-200 object-cover shadow-sm hover:opacity-90">
            </a>
        </div>
        @endif
    </div>
</div>
@endif

<div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
        <h2 class="font-bold text-lg text-gray-900">عقد التوظيف</h2>
        <p class="text-sm text-gray-500 mt-0.5">العقد السنوي وربط الراتب الأساسي بقسم الرواتب</p>
    </div>
    <div class="p-6 space-y-6">
        @if($activeContract)
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div class="rounded-xl border border-emerald-100 bg-emerald-50/50 p-4">
                <dt class="text-xs font-bold text-emerald-700 mb-1">رقم العقد</dt>
                <dd class="font-mono font-semibold">{{ $activeContract->contract_number }}</dd>
            </div>
            <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                <dt class="text-xs font-bold text-gray-500 mb-1">الحالة</dt>
                <dd class="font-semibold text-emerald-700">{{ $activeContract->statusLabel() }}</dd>
            </div>
            <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                <dt class="text-xs font-bold text-gray-500 mb-1">تاريخ البداية</dt>
                <dd>{{ $activeContract->start_date->format('Y/m/d') }}</dd>
            </div>
            <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                <dt class="text-xs font-bold text-gray-500 mb-1">تاريخ النهاية</dt>
                <dd>{{ $activeContract->end_date?->format('Y/m/d') ?? 'غير محدد (مفتوح)' }}</dd>
            </div>
            <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                <dt class="text-xs font-bold text-gray-500 mb-1">الراتب الأساسي</dt>
                <dd class="font-bold text-gray-900">{{ number_format((float) $activeContract->base_salary, 2) }} ج.م</dd>
            </div>
            <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4">
                <dt class="text-xs font-bold text-gray-500 mb-1">نوع التوظيف</dt>
                <dd>{{ $activeContract->employmentTypeLabel() }}</dd>
            </div>
            @if($activeContract->document_path)
            <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-4 sm:col-span-2">
                <dt class="text-xs font-bold text-gray-500 mb-1">ملف العقد</dt>
                <dd><a href="{{ asset('storage/'.$activeContract->document_path) }}" target="_blank" class="text-blue-600 font-bold hover:underline">تحميل / عرض PDF أو صورة العقد</a></dd>
            </div>
            @endif
        </dl>
        @else
        <p class="text-sm text-amber-800 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3">لا يوجد عقد ساري. أضف عقداً وفعّله لربط الراتب بالمحاسبة.</p>
        @endif

        @if($employee->employmentContracts->where('status', '!=', 'active')->isNotEmpty())
        <div>
            <p class="text-xs font-bold text-gray-500 mb-2">مسودات / عقود سابقة</p>
            <ul class="space-y-2 text-sm">
                @foreach($employee->employmentContracts->where('status', '!=', 'active') as $c)
                <li class="flex flex-wrap items-center justify-between gap-2 rounded-lg border border-gray-100 px-3 py-2">
                    <span class="font-mono">{{ $c->contract_number }}</span>
                    <span class="text-gray-500">{{ $c->statusLabel() }}</span>
                    @can('edit-employees')
                    @if($c->status === 'draft')
                    <form action="{{ route('employees.contracts.activate', [$employee, $c]) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs font-bold px-3 py-1 rounded-lg text-white" style="background: {{ $themeColor }};">تفعيل</button>
                    </form>
                    <form action="{{ route('employees.contracts.destroy', [$employee, $c]) }}" method="POST" class="inline" onsubmit="return confirm('حذف مسودة العقد؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-red-600 hover:underline">حذف</button>
                    </form>
                    @endif
                    @endcan
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        @can('edit-employees')
        @if(! $activeContract)
        <form action="{{ route('employees.contracts.store', $employee) }}" method="POST" enctype="multipart/form-data" class="rounded-xl border border-gray-200 p-5 space-y-4 bg-gray-50/30">
            @csrf
            <p class="font-bold text-gray-900">إضافة عقد توظيف جديد</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1">تاريخ البداية</label>
                    <input type="date" name="start_date" required value="{{ old('start_date', $employee->hire_date?->format('Y-m-d')) }}" class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1">تاريخ النهاية (اختياري)</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1">الراتب الأساسي (ج.م)</label>
                    <input type="number" name="base_salary" required min="0" step="0.01" value="{{ old('base_salary', $employee->salary) }}" class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm">
                </div>
                <div>
                    <label class="text-xs font-bold text-gray-600 block mb-1">نوع التوظيف</label>
                    <select name="employment_type" required class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm">
                        @foreach(['full_time' => 'دوام كامل', 'part_time' => 'دوام جزئي', 'contract' => 'عقد', 'intern' => 'متدرب'] as $k => $l)
                        <option value="{{ $k }}" @selected(old('employment_type', $employee->employment_type) === $k)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="text-xs font-bold text-gray-600 block mb-1">ملف العقد (PDF / صورة)</label>
                    <input type="file" name="contract_document" accept=".pdf,image/*" class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="text-xs font-bold text-gray-600 block mb-1">ملاحظات</label>
                    <textarea name="notes" rows="2" class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <button type="submit" name="activate" value="0" class="px-5 py-2.5 rounded-xl border border-gray-300 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50">حفظ كمسودة</button>
                <button type="submit" name="activate" value="1" class="px-5 py-2.5 rounded-xl text-white text-sm font-bold" style="background: {{ $themeColor }};">حفظ وتفعيل العقد</button>
            </div>
        </form>
        @endif
        @endcan
    </div>
</div>
