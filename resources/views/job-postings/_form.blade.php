@php
  $job = $jobPosting ?? null;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
  <div class="md:col-span-2">
    <label class="block text-sm font-semibold text-gray-700 mb-2">المسمى الوظيفي <span class="text-red-500">*</span></label>
    <input type="text" name="title" value="{{ old('title', $job?->title) }}" required
           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror">
    @error('title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
  </div>

  <div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">الرابط (slug)</label>
    <input type="text" name="slug" value="{{ old('slug', $job?->slug) }}" dir="ltr"
           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('slug') border-red-500 @enderror"
           placeholder="يُولَّد تلقائيًا من العنوان إن تُرك فارغًا">
    @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
  </div>

  <div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">الحالة <span class="text-red-500">*</span></label>
    <select name="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
      @foreach($statuses as $st)
        <option value="{{ $st }}" @selected(old('status', $job?->status ?? 'draft') === $st)>
          {{ $st === 'published' ? 'منشورة (ظاهرة في الموقع)' : ($st === 'closed' ? 'مغلقة' : 'مسودة') }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="md:col-span-2">
    <label class="block text-sm font-semibold text-gray-700 mb-2">ملخص قصير</label>
    <input type="text" name="summary" value="{{ old('summary', $job?->summary) }}" maxlength="500"
           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
  </div>

  <div class="md:col-span-2">
    <label class="block text-sm font-semibold text-gray-700 mb-2">وصف الوظيفة <span class="text-red-500">*</span></label>
    <textarea name="description" rows="6" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $job?->description) }}</textarea>
    @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
  </div>

  <div class="md:col-span-2">
    <label class="block text-sm font-semibold text-gray-700 mb-2">المتطلبات</label>
    <textarea name="requirements" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('requirements', $job?->requirements) }}</textarea>
  </div>

  <div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">الموقع</label>
    <input type="text" name="location" value="{{ old('location', $job?->location) }}"
           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="مثال: القاهرة / عن بُعد">
  </div>

  <div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">نوع التوظيف</label>
    <select name="employment_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
      @foreach($employmentTypes as $key => $label)
        <option value="{{ $key }}" @selected(old('employment_type', $job?->employment_type ?? 'full_time') === $key)>{{ $label }}</option>
      @endforeach
    </select>
  </div>

  <div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">القسم (اختياري)</label>
    <select name="department_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
      <option value="">— بدون قسم —</option>
      @foreach($departments as $dept)
        <option value="{{ $dept->id }}" @selected((string) old('department_id', $job?->department_id) === (string) $dept->id)>{{ $dept->name }}</option>
      @endforeach
    </select>
  </div>

  <div>
    <label class="block text-sm font-semibold text-gray-700 mb-2">ترتيب العرض</label>
    <input type="number" name="sort_order" min="0" max="9999" value="{{ old('sort_order', $job?->sort_order ?? 0) }}"
           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
  </div>

  <div class="md:col-span-2 flex items-center gap-2">
    <input type="checkbox" name="is_featured" value="1" id="is_featured" class="rounded border-gray-300 text-blue-600"
           @checked(old('is_featured', $job?->is_featured))>
    <label for="is_featured" class="text-sm font-semibold text-gray-700">وظيفة مميزة (تظهر في أعلى القائمة)</label>
  </div>
</div>
