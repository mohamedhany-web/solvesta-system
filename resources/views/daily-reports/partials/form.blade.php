@php
    $themeColor = $themeColor ?? \App\Helpers\SettingsHelper::getThemeColor();
    $inputClass = 'w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:border-transparent';
    $labelClass = 'text-xs font-bold text-gray-600 block mb-1.5';
    $isUpdate = (bool) ($existing ?? null);
@endphp

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if(($method ?? 'POST') !== 'POST')
        @method($method)
    @endif

    @if($isUpdate)
    <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-900">
        <p class="font-bold">تقرير موجود لهذا اليوم</p>
        <p class="mt-1 text-blue-800/90">يمكنك تحديث البيانات أدناه — سيتم استبدال التقرير السابق عند الحفظ.</p>
    </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">تفاصيل التقرير</h2>
            <p class="text-sm text-gray-500 mt-0.5">التاريخ والمشروع والمهمة المرتبطة</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="report_date" class="{{ $labelClass }}">التاريخ <span class="text-red-500">*</span></label>
                    <input type="date" name="report_date" id="report_date"
                           value="{{ old('report_date', $existing?->report_date?->format('Y-m-d') ?? today()->format('Y-m-d')) }}"
                           max="{{ today()->format('Y-m-d') }}" required
                           class="{{ $inputClass }} @error('report_date') border-red-500 @enderror"
                           style="--tw-ring-color: {{ $themeColor }};">
                    @error('report_date')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="hours_worked" class="{{ $labelClass }}">ساعات العمل <span class="text-red-500">*</span></label>
                    <input type="number" step="0.25" min="0.25" max="24" name="hours_worked" id="hours_worked" required
                           value="{{ old('hours_worked', $existing?->hours_worked ?? 8) }}"
                           class="{{ $inputClass }} @error('hours_worked') border-red-500 @enderror"
                           style="--tw-ring-color: {{ $themeColor }};">
                    @error('hours_worked')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="project_id" class="{{ $labelClass }}">المشروع</label>
                    <select name="project_id" id="project_id"
                            class="{{ $inputClass }} @error('project_id') border-red-500 @enderror"
                            style="--tw-ring-color: {{ $themeColor }};">
                        <option value="">— عام / بدون مشروع —</option>
                        @foreach($projects as $p)
                            <option value="{{ $p->id }}" @selected(old('project_id', $existing?->project_id) == $p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                    @error('project_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="task_id" class="{{ $labelClass }}">المهمة (اختياري)</label>
                    <select name="task_id" id="task_id"
                            class="{{ $inputClass }} @error('task_id') border-red-500 @enderror"
                            style="--tw-ring-color: {{ $themeColor }};">
                        <option value="">— بدون مهمة —</option>
                        @foreach($tasks as $t)
                            <option value="{{ $t->id }}" @selected(old('task_id', $existing?->task_id) == $t->id)>
                                {{ $t->project?->name }} — {{ $t->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('task_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">ما أنجزته اليوم</h2>
            <p class="text-sm text-gray-500 mt-0.5">صف العمل بوضوح — ميزات، إصلاحات، اجتماعات</p>
        </div>
        <div class="p-6">
            <label for="work_summary" class="{{ $labelClass }}">ملخص العمل <span class="text-red-500">*</span></label>
            <textarea name="work_summary" id="work_summary" rows="6" required
                      placeholder="مثال: أتممت واجهة تسجيل الدخول، راجعت PR #12، اجتمعت مع فريق التصميم..."
                      class="{{ $inputClass }} @error('work_summary') border-red-500 @enderror"
                      style="--tw-ring-color: {{ $themeColor }};">{{ old('work_summary', $existing?->work_summary) }}</textarea>
            @error('work_summary')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/80">
            <h2 class="font-bold text-lg text-gray-900">العوائق (Blockers)</h2>
        </div>
        <div class="p-6 space-y-4">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="has_blocker" value="1"
                       @checked(old('has_blocker', $existing?->has_blocker))
                       class="h-4 w-4 rounded border-gray-300 focus:ring-2"
                       style="color: {{ $themeColor }}; --tw-ring-color: {{ $themeColor }};"
                       id="has_blocker">
                <span class="text-sm font-semibold text-gray-800">يوجد عائق يعيق إنجاز العمل</span>
            </label>
            <div>
                <label for="blocker_description" class="{{ $labelClass }}">وصف العائق</label>
                <textarea name="blocker_description" id="blocker_description" rows="3"
                          placeholder="ما الذي يمنعك من المتابعة؟ هل تحتاج مساعدة من أحد؟"
                          class="{{ $inputClass }} @error('blocker_description') border-red-500 @enderror"
                          style="--tw-ring-color: {{ $themeColor }};">{{ old('blocker_description', $existing?->blocker_description) }}</textarea>
                @error('blocker_description')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
        <a href="{{ $cancelUrl }}" class="inline-flex items-center justify-center gap-2 border border-gray-300 bg-white px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 shadow-sm">إلغاء</a>
        <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-white text-sm font-bold shadow-sm hover:opacity-95"
                style="background: {{ $themeColor }};">{{ $submitLabel }}</button>
    </div>
</form>
