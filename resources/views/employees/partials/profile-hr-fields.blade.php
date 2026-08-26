@php
    $employee = $employee ?? null;
    $themeColor = $themeColor ?? \App\Helpers\SettingsHelper::getThemeColor();
    $inputClass = $inputClass ?? 'w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:border-transparent';
    $labelClass = $labelClass ?? 'text-xs font-bold text-gray-600 block mb-1.5';
    $v = fn (string $key, $default = '') => old($key, $employee?->{$key} ?? $default);
@endphp

{{-- بطاقة الرقم القومي --}}
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
        <h2 class="font-bold text-lg text-gray-900">بيانات بطاقة الرقم القومي</h2>
        <p class="text-sm text-gray-500 mt-0.5">الاسم الرباعي والرقم القومي كما في البطاقة — يُستخرج تاريخ الميلاد والجنس تلقائياً</p>
    </div>
    <div class="p-6 space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label for="full_name_ar" class="{{ $labelClass }}">الاسم الرباعي (كما في البطاقة)</label>
                <input type="text" name="full_name_ar" id="full_name_ar" value="{{ $v('full_name_ar') }}"
                       class="{{ $inputClass }} @error('full_name_ar') border-red-500 @enderror"
                       style="--tw-ring-color: {{ $themeColor }};" placeholder="الاسم الأول · الأب · الجد · العائلة">
                @error('full_name_ar')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="national_id" class="{{ $labelClass }}">الرقم القومي (14 رقم)</label>
                <input type="text" name="national_id" id="national_id" value="{{ $v('national_id') }}" maxlength="14" inputmode="numeric" dir="ltr"
                       class="{{ $inputClass }} font-mono @error('national_id') border-red-500 @enderror"
                       style="--tw-ring-color: {{ $themeColor }};" placeholder="2xxxxxxxxxxxxxx" oninput="fillFromNationalId(this.value)">
                @error('national_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="nationality" class="{{ $labelClass }}">الجنسية</label>
                <input type="text" name="nationality" id="nationality" value="{{ $v('nationality', 'مصري') }}"
                       class="{{ $inputClass }} @error('nationality') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                @error('nationality')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="date_of_birth" class="{{ $labelClass }}">تاريخ الميلاد</label>
                <input type="date" name="date_of_birth" id="date_of_birth"
                       value="{{ old('date_of_birth', $employee?->date_of_birth?->format('Y-m-d')) }}"
                       class="{{ $inputClass }} @error('date_of_birth') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                @error('date_of_birth')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="gender" class="{{ $labelClass }}">النوع</label>
                <select name="gender" id="gender" class="{{ $inputClass }} @error('gender') border-red-500 @enderror" style="--tw-ring-color: {{ $themeColor }};">
                    <option value="">اختر</option>
                    @foreach(\App\Models\Employee::genderLabels() as $value => $label)
                        <option value="{{ $value }}" @selected($v('gender') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('gender')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="religion" class="{{ $labelClass }}">الديانة</label>
                <select name="religion" id="religion" class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};">
                    <option value="">اختر</option>
                    @foreach(\App\Models\Employee::religionLabels() as $value => $label)
                        <option value="{{ $value }}" @selected($v('religion') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="marital_status" class="{{ $labelClass }}">الحالة الاجتماعية</label>
                <select name="marital_status" id="marital_status" class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};">
                    <option value="">اختر</option>
                    @foreach(\App\Models\Employee::maritalStatusLabels() as $value => $label)
                        <option value="{{ $value }}" @selected($v('marital_status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="birth_place" class="{{ $labelClass }}">محل الميلاد</label>
                <input type="text" name="birth_place" id="birth_place" value="{{ $v('birth_place') }}"
                       class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};" placeholder="المحافظة / المركز">
            </div>
            <div>
                <label for="military_status" class="{{ $labelClass }}">الموقف من التجنيد</label>
                <select name="military_status" id="military_status" class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};">
                    <option value="">اختر</option>
                    @foreach(\App\Models\Employee::militaryStatusLabels() as $value => $label)
                        <option value="{{ $value }}" @selected($v('military_status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="id_issue_date" class="{{ $labelClass }}">تاريخ إصدار البطاقة</label>
                <input type="date" name="id_issue_date" id="id_issue_date"
                       value="{{ old('id_issue_date', $employee?->id_issue_date?->format('Y-m-d')) }}"
                       class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};">
            </div>
            <div>
                <label for="id_expiry_date" class="{{ $labelClass }}">تاريخ انتهاء البطاقة</label>
                <input type="date" name="id_expiry_date" id="id_expiry_date"
                       value="{{ old('id_expiry_date', $employee?->id_expiry_date?->format('Y-m-d')) }}"
                       class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};">
            </div>
            <div class="sm:col-span-2">
                <label for="national_id_address" class="{{ $labelClass }}">العنوان في البطاقة</label>
                <textarea name="national_id_address" id="national_id_address" rows="2"
                          class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};"
                          placeholder="العنوان المدوّن على بطاقة الرقم القومي">{{ $v('national_id_address') }}</textarea>
            </div>
            <div>
                <label for="id_photo_front" class="{{ $labelClass }}">صورة وجه البطاقة</label>
                @if($employee?->id_photo_front_path)
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$employee->id_photo_front_path) }}" alt="وجه البطاقة" class="h-24 rounded-lg border border-gray-200 object-cover">
                </div>
                @endif
                <input type="file" name="id_photo_front" id="id_photo_front" accept="image/jpeg,image/png,image/jpg"
                       class="{{ $inputClass }} @error('id_photo_front') border-red-500 @enderror">
                @error('id_photo_front')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="id_photo_back" class="{{ $labelClass }}">صورة ظهر البطاقة</label>
                @if($employee?->id_photo_back_path)
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$employee->id_photo_back_path) }}" alt="ظهر البطاقة" class="h-24 rounded-lg border border-gray-200 object-cover">
                </div>
                @endif
                <input type="file" name="id_photo_back" id="id_photo_back" accept="image/jpeg,image/png,image/jpg"
                       class="{{ $inputClass }} @error('id_photo_back') border-red-500 @enderror">
                @error('id_photo_back')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>
</div>

{{-- أرقام الهواتف --}}
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
        <h2 class="font-bold text-lg text-gray-900">أرقام الهواتف</h2>
        <p class="text-sm text-gray-500 mt-0.5">رقم أساسي + احتياطي + واتساب</p>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="phone_secondary" class="{{ $labelClass }}">هاتف إضافي</label>
                <input type="text" name="phone_secondary" id="phone_secondary" value="{{ $v('phone_secondary') }}" dir="ltr"
                       class="{{ $inputClass }} @error('phone_secondary') border-red-500 @enderror"
                       style="--tw-ring-color: {{ $themeColor }};" placeholder="01xxxxxxxxx">
                @error('phone_secondary')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="whatsapp_phone" class="{{ $labelClass }}">واتساب</label>
                <input type="text" name="whatsapp_phone" id="whatsapp_phone" value="{{ $v('whatsapp_phone') }}" dir="ltr"
                       class="{{ $inputClass }} @error('whatsapp_phone') border-red-500 @enderror"
                       style="--tw-ring-color: {{ $themeColor }};" placeholder="إن اختلف عن الرقم الأساسي">
                @error('whatsapp_phone')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>
</div>

{{-- التعليم --}}
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
        <h2 class="font-bold text-lg text-gray-900">المؤهل الدراسي</h2>
        <p class="text-sm text-gray-500 mt-0.5">خريج أي جامعة / كلية / تخصص</p>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="education_level" class="{{ $labelClass }}">المؤهل</label>
                <select name="education_level" id="education_level" class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};">
                    <option value="">اختر</option>
                    @foreach(\App\Models\Employee::educationLevelLabels() as $value => $label)
                        <option value="{{ $value }}" @selected($v('education_level') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="graduation_year" class="{{ $labelClass }}">سنة التخرج</label>
                <input type="number" name="graduation_year" id="graduation_year" value="{{ $v('graduation_year') }}" min="1970" max="{{ now()->year + 1 }}"
                       class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};" placeholder="{{ now()->year }}">
            </div>
            <div>
                <label for="university" class="{{ $labelClass }}">الجامعة / المعهد</label>
                <input type="text" name="university" id="university" value="{{ $v('university') }}"
                       class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};" placeholder="مثال: جامعة القاهرة">
            </div>
            <div>
                <label for="faculty" class="{{ $labelClass }}">الكلية</label>
                <input type="text" name="faculty" id="faculty" value="{{ $v('faculty') }}"
                       class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};" placeholder="مثال: حاسبات ومعلومات">
            </div>
            <div class="sm:col-span-2">
                <label for="major" class="{{ $labelClass }}">التخصص</label>
                <input type="text" name="major" id="major" value="{{ $v('major') }}"
                       class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};" placeholder="مثال: علوم الحاسب">
            </div>
            <div class="sm:col-span-2">
                <label for="education_notes" class="{{ $labelClass }}">ملاحظات تعليمية</label>
                <textarea name="education_notes" id="education_notes" rows="2"
                          class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};"
                          placeholder="شهادات إضافية، تقدير، دورات…">{{ $v('education_notes') }}</textarea>
            </div>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
function fillFromNationalId(raw) {
    const nid = String(raw || '').replace(/\D/g, '');
    if (nid.length !== 14 || !/^[23]/.test(nid)) return;
    const century = nid[0] === '2' ? 1900 : 2000;
    const year = century + parseInt(nid.slice(1, 3), 10);
    const month = nid.slice(3, 5);
    const day = nid.slice(5, 7);
    const dob = `${year}-${month}-${day}`;
    const dobInput = document.getElementById('date_of_birth');
    const genderInput = document.getElementById('gender');
    if (dobInput && !dobInput.value) dobInput.value = dob;
    if (genderInput && !genderInput.value) {
        genderInput.value = parseInt(nid[12], 10) % 2 === 1 ? 'male' : 'female';
    }
}
</script>
@endpush
@endonce
