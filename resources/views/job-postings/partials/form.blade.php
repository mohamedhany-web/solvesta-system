@php
    $job = $jobPosting ?? null;
    $themeColor = $themeColor ?? \App\Helpers\SettingsHelper::getThemeColor();
    $inputClass = 'w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:border-transparent';
    $labelClass = 'text-xs font-bold text-gray-600 block mb-1';
@endphp

<form action="{{ $action }}" method="POST" class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
    @csrf
    @if(($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
        <h2 class="font-bold text-gray-900">{{ $job ? 'تعديل بيانات الوظيفة' : 'بيانات الوظيفة الجديدة' }}</h2>
        <p class="text-xs text-gray-500 mt-1">الوظائف ذات الحالة «منشورة» تظهر تلقائياً في صفحة التوظيف العامة</p>
    </div>

    <div class="p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="{{ $labelClass }}">المسمى الوظيفي <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $job?->title) }}" required
                       class="{{ $inputClass }} @error('title') border-red-500 @enderror"
                       style="--tw-ring-color: {{ $themeColor }};">
                @error('title')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">الرابط (slug)</label>
                <input type="text" name="slug" value="{{ old('slug', $job?->slug) }}" dir="ltr"
                       class="{{ $inputClass }} @error('slug') border-red-500 @enderror"
                       placeholder="auto-generated-if-empty"
                       style="--tw-ring-color: {{ $themeColor }};">
                <p class="text-[10px] text-gray-400 mt-1">يُولَّد تلقائياً من العنوان إن تُرك فارغاً</p>
                @error('slug')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">الحالة <span class="text-red-500">*</span></label>
                <select name="status" required class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};">
                    @foreach($statuses as $st)
                        <option value="{{ $st }}" @selected(old('status', $job?->status ?? 'draft') === $st)>
                            {{ $st === 'published' ? 'منشورة (ظاهرة في الموقع)' : ($st === 'closed' ? 'مغلقة' : 'مسودة') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="{{ $labelClass }}">ملخص قصير</label>
                <input type="text" name="summary" value="{{ old('summary', $job?->summary) }}" maxlength="500"
                       class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};"
                       placeholder="سطر واحد يظهر في قائمة الوظائف">
            </div>

            <div class="md:col-span-2">
                <label class="{{ $labelClass }}">وصف الوظيفة <span class="text-red-500">*</span></label>
                <textarea name="description" rows="6" required
                          class="{{ $inputClass }} @error('description') border-red-500 @enderror"
                          style="--tw-ring-color: {{ $themeColor }};">{{ old('description', $job?->description) }}</textarea>
                @error('description')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="{{ $labelClass }}">المتطلبات</label>
                <textarea name="requirements" rows="5" class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};">{{ old('requirements', $job?->requirements) }}</textarea>
            </div>

            <div>
                <label class="{{ $labelClass }}">الموقع</label>
                <input type="text" name="location" value="{{ old('location', $job?->location) }}"
                       class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};"
                       placeholder="مثال: القاهرة / عن بُعد">
            </div>

            <div>
                <label class="{{ $labelClass }}">نوع التوظيف</label>
                <select name="employment_type" class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};">
                    @foreach($employmentTypes as $key => $label)
                        <option value="{{ $key }}" @selected(old('employment_type', $job?->employment_type ?? 'full_time') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="{{ $labelClass }}">القسم (اختياري)</label>
                <select name="department_id" class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};">
                    <option value="">— بدون قسم —</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" @selected((string) old('department_id', $job?->department_id) === (string) $dept->id)>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="{{ $labelClass }}">ترتيب العرض</label>
                <input type="number" name="sort_order" min="0" max="9999" value="{{ old('sort_order', $job?->sort_order ?? 0) }}"
                       class="{{ $inputClass }}" style="--tw-ring-color: {{ $themeColor }};">
            </div>

            <div class="md:col-span-2">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300"
                           style="accent-color: {{ $themeColor }};"
                           @checked(old('is_featured', $job?->is_featured))>
                    <span class="text-sm font-semibold text-gray-700">وظيفة مميزة — تظهر في أعلى القائمة</span>
                </label>
            </div>
        </div>
    </div>

    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex flex-wrap gap-3 justify-end">
        <a href="{{ $cancelUrl }}" class="border border-gray-300 bg-white px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50">إلغاء</a>
        <button type="submit" class="px-6 py-2.5 rounded-xl text-white font-bold text-sm shadow-lg hover:opacity-95"
                style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
            {{ $submitLabel }}
        </button>
    </div>
</form>
