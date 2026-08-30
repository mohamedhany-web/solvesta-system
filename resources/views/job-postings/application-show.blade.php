@extends('layouts.app')

@section('page-title', 'ATS — '.$application->full_name)

@section('content')
@php
    $themeColor = \App\Helpers\SettingsHelper::getThemeColor();
    $scoreMap = $application->atsScores->keyBy('job_ats_criterion_id');
    $scoreColor = match(true) {
        ($application->ats_score ?? 0) >= 85 => '#059669',
        ($application->ats_score ?? 0) >= 70 => '#2563eb',
        ($application->ats_score ?? 0) >= 55 => '#d97706',
        default => '#dc2626',
    };
    $cvViewUrl = $application->cv_path
        ? route('job-postings.applications.cv', [$jobPosting, $application], absolute: false)
        : null;
@endphp

<div class="w-full max-w-full font-tajawal">
    <div class="mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 sm:p-6 border border-blue-100">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="min-w-0">
                    <div class="flex flex-wrap gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-white border">{{ $application->stageLabelAr() }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-white border">{{ $application->statusLabelAr() }}</span>
                        @if($application->ats_grade)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold" style="background:{{ $scoreColor }}20;color:{{ $scoreColor }};">Grade {{ $application->ats_grade }}</span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $application->full_name }}</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ $jobPosting->title }} · {{ $application->email }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('job-postings.applications', $jobPosting) }}" class="px-4 py-2.5 rounded-xl bg-gray-600 text-white text-sm font-bold">العودة للقائمة</a>
                    @can('edit-jobs')
                    <form method="POST" action="{{ route('job-postings.applications.reprocess-ats', [$jobPosting, $application]) }}">
                        @csrf
                        <button class="px-4 py-2.5 rounded-xl text-white text-sm font-bold" style="background:{{ $themeColor }};">إعادة تحليل CV</button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <div class="bg-white rounded-2xl border p-4 text-center shadow-sm">
            <p class="text-xs text-gray-500">درجة ATS</p>
            <p class="text-3xl font-bold" style="color:{{ $scoreColor }};">{{ $application->ats_score ?? '—' }}@if($application->ats_score !== null)%@endif</p>
        </div>
        <div class="bg-white rounded-2xl border p-4 text-center shadow-sm">
            <p class="text-xs text-gray-500">سنوات الخبرة</p>
            <p class="text-3xl font-bold text-gray-900">{{ $application->years_experience ?? '—' }}</p>
        </div>
        <div class="bg-white rounded-2xl border p-4 text-center shadow-sm">
            <p class="text-xs text-gray-500">التعليم</p>
            <p class="text-lg font-bold text-gray-900 mt-2">{{ $application->education_level ?? '—' }}</p>
        </div>
        <div class="bg-white rounded-2xl border p-4 text-center shadow-sm">
            <p class="text-xs text-gray-500">حالة التحليل</p>
            <p class="text-lg font-bold text-gray-900 mt-2">{{ $application->parse_status }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">
        <div class="xl:col-span-3 space-y-6">
            <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
                <div class="px-4 sm:px-5 py-4 border-b bg-gray-50 flex items-center justify-between gap-3">
                    <h2 class="font-bold text-gray-900">عرض السيرة داخل النظام</h2>
                    @if($cvViewUrl)
                        <a href="{{ $cvViewUrl }}" target="_blank" class="text-xs font-bold" style="color:{{ $themeColor }};">فتح / تحميل</a>
                    @endif
                </div>
                <div class="bg-slate-100 relative" id="cv-viewer-wrap">
                    @if($cvViewUrl && $application->isPdfCv())
                        <div id="cv-viewer-loading" class="absolute inset-0 flex items-center justify-center bg-slate-100 z-10">
                            <p class="text-sm text-gray-500 font-semibold">جاري تحميل السيرة...</p>
                        </div>
                        <iframe id="cv-viewer-frame" class="w-full border-0 bg-white" style="min-height:70vh;" title="CV Viewer"></iframe>
                        <div id="cv-viewer-error" class="hidden p-10 text-center">
                            <p class="text-sm text-gray-600 mb-4">تعذّر عرض المعاينة داخل الصفحة. افتح الملف في تاب جديد.</p>
                            <a href="{{ $cvViewUrl }}" target="_blank" rel="noopener" class="inline-flex px-5 py-2.5 rounded-xl text-white text-sm font-bold" style="background:{{ $themeColor }};">فتح / تحميل CV</a>
                        </div>
                        <script>
                            (function () {
                                const url = @json($cvViewUrl);
                                const frame = document.getElementById('cv-viewer-frame');
                                const loading = document.getElementById('cv-viewer-loading');
                                const errorBox = document.getElementById('cv-viewer-error');
                                const showError = () => {
                                    if (loading) loading.classList.add('hidden');
                                    if (frame) frame.classList.add('hidden');
                                    if (errorBox) errorBox.classList.remove('hidden');
                                };
                                fetch(url, { credentials: 'same-origin', headers: { 'Accept': 'application/pdf' } })
                                    .then(function (res) {
                                        if (!res.ok) throw new Error('HTTP ' + res.status);
                                        return res.blob();
                                    })
                                    .then(function (blob) {
                                        if (!blob || blob.size < 100) throw new Error('empty');
                                        const type = blob.type || 'application/pdf';
                                        const pdfBlob = type.includes('pdf') ? blob : new Blob([blob], { type: 'application/pdf' });
                                        frame.src = URL.createObjectURL(pdfBlob);
                                        if (loading) loading.classList.add('hidden');
                                    })
                                    .catch(showError);
                            })();
                        </script>
                    @elseif($cvViewUrl)
                        <div class="p-10 text-center">
                            <p class="text-sm text-gray-600 mb-4">معاينة مباشرة متاحة لملفات PDF. هذا الملف DOC/DOCX — افتحه من الرابط.</p>
                            <a href="{{ $cvViewUrl }}" target="_blank" rel="noopener" class="inline-flex px-5 py-2.5 rounded-xl text-white text-sm font-bold" style="background:{{ $themeColor }};">فتح الملف</a>
                        </div>
                    @else
                        <div class="p-10 text-center text-gray-500">لا يوجد ملف CV</div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
                <div class="px-4 sm:px-5 py-4 border-b bg-gray-50">
                    <h2 class="font-bold text-gray-900">بيانات مستخرجة من الـ CV</h2>
                </div>
                <div class="p-4 sm:p-5 space-y-4">
                    @if($application->parse_error)
                        <div class="rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3">{{ $application->parse_error }}</div>
                    @endif
                    @if(($application->parsed_cv['scanned'] ?? false) || $application->parse_status === 'scanned')
                        <div class="rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-sm px-4 py-3">
                            هذا الملف صورة ممسوحة. لاستخراج مهارات تلقائياً ارفع CV نصي (PDF قابل للنسخ) أو أكمل التقييم اليدوي من بطاقة الـ Scorecard.
                        </div>
                    @endif

                    <div>
                        <p class="text-xs font-bold text-gray-500 mb-2">المهارات</p>
                        <div class="flex flex-wrap gap-2">
                            @forelse($application->extracted_skills ?? [] as $skill)
                                <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-800 text-xs font-semibold">{{ $skill }}</span>
                            @empty
                                <span class="text-sm text-gray-400">لم يتم استخراج مهارات بعد</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div class="rounded-xl bg-gray-50 border px-4 py-3">
                            <p class="text-xs text-gray-500">إيميلات من الـ CV</p>
                            <p class="font-semibold mt-1">{{ implode(' · ', $application->parsed_cv['emails'] ?? []) ?: '—' }}</p>
                        </div>
                        <div class="rounded-xl bg-gray-50 border px-4 py-3">
                            <p class="text-xs text-gray-500">هواتف من الـ CV</p>
                            <p class="font-semibold mt-1">{{ implode(' · ', $application->parsed_cv['phones'] ?? []) ?: '—' }}</p>
                        </div>
                    </div>

                    @if(!empty($application->parsed_cv['links']))
                        <div>
                            <p class="text-xs font-bold text-gray-500 mb-2">روابط مكتشفة</p>
                            <ul class="space-y-1 text-sm">
                                @foreach($application->parsed_cv['links'] as $link)
                                    <li><a href="{{ $link }}" target="_blank" class="text-blue-600 hover:underline break-all">{{ $link }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($application->parsed_cv['summary'] ?? null)
                        <div>
                            <p class="text-xs font-bold text-gray-500 mb-2">مقتطف نصي</p>
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $application->parsed_cv['summary'] }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border shadow-sm overflow-hidden">
                <div class="px-4 sm:px-5 py-4 border-b bg-gray-50">
                    <h2 class="font-bold text-gray-900">بطاقة التقييم (Scorecard)</h2>
                    <p class="text-xs text-gray-500 mt-1">معايير شركات السوفت وير — تلقائي + يدوي</p>
                </div>
                <form method="POST" action="{{ route('job-postings.applications.ats-scores', [$jobPosting, $application]) }}" class="p-4 sm:p-5 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 mb-1">مرحلة الـ Pipeline</label>
                        <select name="stage" class="w-full border rounded-xl px-3 py-2.5 text-sm">
                            @foreach(config('ats.stages') as $code => $label)
                                <option value="{{ $code }}" @selected($application->stage === $code)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    @foreach($criteria as $i => $criterion)
                        @php $score = $scoreMap->get($criterion->id); @endphp
                        <div class="rounded-xl border border-gray-200 p-3 {{ $criterion->type === 'manual' ? 'bg-amber-50/40' : 'bg-gray-50' }}">
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <div>
                                    <p class="font-bold text-sm text-gray-900">{{ $criterion->label }}</p>
                                    <p class="text-[11px] text-gray-500">وزن {{ $criterion->weight }}% · {{ $criterion->type === 'manual' ? 'يدوي' : 'تلقائي' }}</p>
                                </div>
                                <span class="text-sm font-bold" style="color:{{ $themeColor }};">{{ $score?->score ?? 0 }}/100</span>
                            </div>
                            <input type="hidden" name="scores[{{ $i }}][criterion_id]" value="{{ $criterion->id }}">
                            <input type="number" min="0" max="100" name="scores[{{ $i }}][score]"
                                   value="{{ old('scores.'.$i.'.score', $score?->score ?? 0) }}"
                                   class="w-full border rounded-lg px-3 py-2 text-sm mb-2"
                                   @readonly(!auth()->user()->can('edit-jobs'))>
                            <input type="text" name="scores[{{ $i }}][notes]" value="{{ old('scores.'.$i.'.notes', $score?->notes) }}"
                                   class="w-full border rounded-lg px-3 py-2 text-xs" placeholder="ملاحظات">
                            @if(!empty($score?->evidence))
                                <p class="text-[10px] text-gray-400 mt-2 break-words">{{ \Illuminate\Support\Str::limit(json_encode($score->evidence, JSON_UNESCAPED_UNICODE), 160) }}</p>
                            @endif
                        </div>
                    @endforeach

                    @can('edit-jobs')
                    <button class="w-full py-3 rounded-xl text-white text-sm font-bold" style="background:{{ $themeColor }};">حفظ التقييم وإعادة احتساب الدرجة</button>
                    @endcan
                </form>
            </div>

            <div class="bg-white rounded-2xl border shadow-sm p-5 space-y-3 text-sm">
                <h3 class="font-bold text-gray-900">بيانات التقديم</h3>
                <p><span class="text-gray-500">الهاتف:</span> {{ $application->phone ?: '—' }}</p>
                <p><span class="text-gray-500">LinkedIn:</span>
                    @if($application->linkedin_url)
                        <a href="{{ $application->linkedin_url }}" target="_blank" class="text-blue-600 hover:underline">رابط</a>
                    @else — @endif
                </p>
                <p><span class="text-gray-500">Portfolio:</span>
                    @if($application->portfolio_url)
                        <a href="{{ $application->portfolio_url }}" target="_blank" class="text-blue-600 hover:underline">رابط</a>
                    @else — @endif
                </p>
                @if($application->message)
                    <div class="rounded-xl bg-gray-50 border p-3 text-gray-700 whitespace-pre-wrap">{{ $application->message }}</div>
                @endif

                @can('edit-jobs')
                <form method="POST" action="{{ route('job-postings.applications.status', $application) }}" class="pt-3 border-t flex gap-2">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="flex-1 border rounded-xl px-3 py-2 text-sm">
                        @foreach(\App\Models\JobApplication::STATUSES as $st)
                            <option value="{{ $st }}" @selected($application->status === $st)>{{ (new \App\Models\JobApplication(['status'=>$st]))->statusLabelAr() }}</option>
                        @endforeach
                    </select>
                    <button class="px-4 py-2 rounded-xl bg-gray-900 text-white text-sm font-bold">حفظ الحالة</button>
                </form>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
