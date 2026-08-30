@extends('layouts.app')

@section('page-title', 'رسالة جديدة')

@section('content')
@php $fromEmail = env('MAIL_FROM_ADDRESS', 'info@s0lvesta.com'); @endphp

<div class="w-full max-w-full font-tajawal">
    <div class="mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 sm:p-6 lg:p-8 border border-blue-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center">
                    <div class="h-12 w-12 sm:h-14 sm:w-14 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center ml-3 sm:ml-4 shadow-lg flex-shrink-0">
                        <svg class="h-6 w-6 sm:h-7 sm:w-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-1">رسالة جديدة</h1>
                        <p class="text-gray-600 text-sm sm:text-base">إرسال من البريد الرسمي — {{ $fromEmail }}</p>
                    </div>
                </div>
                <a href="{{ route('official-mail.index') }}"
                   class="w-full sm:w-auto bg-gray-600 text-white px-5 py-2.5 sm:px-6 sm:py-3 rounded-lg hover:bg-gray-700 transition-all duration-200 flex items-center justify-center shadow-sm text-sm sm:text-base">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    العودة للبريد
                </a>
            </div>
        </div>
    </div>

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">{{ session('error') }}</div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        <ul class="list-disc pr-5 space-y-1 text-sm">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden w-full">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 sm:px-6 lg:px-8 py-4 sm:py-5 border-b border-gray-200">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900">تفاصيل الرسالة</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-1">املأ الحقول ثم أرفق الملفات إن لزم</p>
        </div>

        <form method="POST" action="{{ route('official-mail.send') }}" enctype="multipart/form-data" id="compose-form" class="p-4 sm:p-6 lg:p-8 space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                <div class="lg:col-span-2">
                    <label for="to" class="block text-sm font-semibold text-gray-700 mb-2">
                        إلى <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="to" id="to" value="{{ old('to') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('to') border-red-500 @enderror"
                           placeholder="name@example.com" autocomplete="email">
                    @error('to')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cc" class="block text-sm font-semibold text-gray-700 mb-2">نسخة (CC)</label>
                    <input type="text" name="cc" id="cc" value="{{ old('cc') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="عدة عناوين مفصولة بفاصلة">
                </div>

                <div>
                    <label for="bcc" class="block text-sm font-semibold text-gray-700 mb-2">نسخة مخفية (BCC)</label>
                    <input type="text" name="bcc" id="bcc" value="{{ old('bcc') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                           placeholder="عدة عناوين مفصولة بفاصلة">
                </div>

                <div class="lg:col-span-2">
                    <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">
                        الموضوع <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('subject') border-red-500 @enderror"
                           placeholder="موضوع الرسالة">
                    @error('subject')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="body" class="block text-sm font-semibold text-gray-700 mb-2">
                        نص الرسالة <span class="text-red-500">*</span>
                    </label>
                    <textarea name="body" id="body" rows="12" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('body') border-red-500 @enderror"
                              placeholder="اكتب رسالتك هنا...">{{ old('body') }}</textarea>
                    @error('body')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">المرفقات</label>
                    <div id="dropzone" class="border-2 border-dashed border-gray-300 rounded-xl p-5 sm:p-6 bg-gray-50 hover:border-blue-400 hover:bg-blue-50/40 transition-all duration-200">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-800">اسحب الملفات هنا أو اختر من جهازك</p>
                                <p class="text-xs text-gray-500 mt-1">PDF، صور، Office، ZIP — حتى 5 ملفات · 10 ميجا لكل ملف</p>
                            </div>
                            <label class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 cursor-pointer text-sm font-semibold shadow-sm">
                                <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                اختيار ملفات
                                <input type="file" name="attachments[]" id="attachments" multiple class="hidden"
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.webp,.zip,.rar,.txt,.csv">
                            </label>
                        </div>
                        <div id="file-list" class="flex flex-wrap gap-2 mt-4"></div>
                    </div>
                    @error('attachments')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('attachments.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2 border-t border-gray-100">
                <button type="submit" id="send-btn"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all duration-200 flex items-center justify-center shadow-sm text-sm sm:text-base font-semibold">
                    <svg class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    إرسال الرسالة
                </button>
                <a href="{{ route('official-mail.index') }}"
                   class="bg-white text-gray-700 border border-gray-300 px-6 py-3 rounded-lg hover:bg-gray-50 transition-all duration-200 flex items-center justify-center text-sm sm:text-base font-semibold">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    const input = document.getElementById('attachments');
    const list = document.getElementById('file-list');
    const dropzone = document.getElementById('dropzone');
    const form = document.getElementById('compose-form');
    const sendBtn = document.getElementById('send-btn');
    let files = [];

    function renderFiles() {
        list.innerHTML = '';
        files.forEach((file, idx) => {
            const chip = document.createElement('span');
            chip.className = 'inline-flex items-center gap-2 bg-blue-50 text-blue-800 rounded-lg px-3 py-1.5 text-xs font-semibold border border-blue-100';
            const sizeKb = Math.max(1, Math.round(file.size / 1024));
            chip.innerHTML = '<span></span><button type="button" class="text-gray-500 hover:text-red-600 text-base leading-none" title="إزالة">&times;</button>';
            chip.querySelector('span').textContent = file.name + ' (' + sizeKb + ' KB)';
            chip.querySelector('button').addEventListener('click', () => {
                files.splice(idx, 1);
                syncInput();
                renderFiles();
            });
            list.appendChild(chip);
        });
    }

    function syncInput() {
        const dt = new DataTransfer();
        files.slice(0, 5).forEach(f => dt.items.add(f));
        files = Array.from(dt.files);
        input.files = dt.files;
    }

    function addFiles(fileList) {
        Array.from(fileList || []).forEach(f => {
            if (files.length >= 5) return;
            if (f.size > 10 * 1024 * 1024) {
                alert('الملف أكبر من 10 ميجا: ' + f.name);
                return;
            }
            files.push(f);
        });
        syncInput();
        renderFiles();
    }

    input?.addEventListener('change', function () {
        addFiles(this.files);
    });

    ['dragenter', 'dragover'].forEach(evt => {
        dropzone?.addEventListener(evt, e => {
            e.preventDefault();
            dropzone.classList.add('border-blue-500', 'bg-blue-50');
        });
    });
    ['dragleave', 'drop'].forEach(evt => {
        dropzone?.addEventListener(evt, e => {
            e.preventDefault();
            dropzone.classList.remove('border-blue-500', 'bg-blue-50');
        });
    });
    dropzone?.addEventListener('drop', e => addFiles(e.dataTransfer.files));

    form?.addEventListener('submit', function () {
        sendBtn.disabled = true;
        sendBtn.classList.add('opacity-70');
        sendBtn.innerHTML = 'جارٍ الإرسال...';
    });
})();
</script>
@endsection
