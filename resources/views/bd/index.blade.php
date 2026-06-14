@extends('layouts.app')

@section('page-title', 'Business Development')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full px-2 sm:px-0">
    @include('partials.erp-page-header', [
        'title' => 'Business Development',
        'subtitle' => 'شركاء · فرص · تحويل إلى Leads',
        'icon' => 'users',
    ])

    <div class="flex flex-wrap gap-3 mb-6">
        @can('create-sales')
        <a href="{{ route('bd.partners.create') }}" class="px-5 py-2.5 rounded-xl text-white font-bold text-sm" style="background:linear-gradient(135deg,{{ $themeColor }} 0%,{{ $themeColor }}dd 100%);">+ شريك</a>
        @endcan
        <a href="{{ route('leads.index') }}" class="px-4 py-2 rounded-xl border bg-white text-sm font-bold">Leads</a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach([['شركاء',$stats['partners']],['فرص مفتوحة',$stats['opportunities']],['تحوّلت لـ Lead',$stats['converted']],['قيمة Pipeline',number_format($stats['pipeline_value'])]] as $i => [$l,$v])
        <div class="bg-white rounded-2xl shadow-lg border p-4 text-center">
            <p class="text-xs text-gray-500 font-tajawal">{{ $l }}</p>
            <p class="text-2xl font-bold mt-1" style="color:{{ $i===3?$themeColor:'#111' }};">{{ $v }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-lg border overflow-hidden">
            <div class="px-5 py-4 border-b font-bold font-tajawal" style="background:{{ $themeColor }}08;">الشركاء</div>
            <ul class="divide-y text-sm font-tajawal">
                @forelse($partners as $p)
                <li class="px-4 py-3 flex justify-between hover:bg-gray-50">
                    <div>
                        <a href="{{ route('bd.partners.show', $p) }}" class="font-bold" style="color:{{ $themeColor }};">{{ $p->name }}</a>
                        <p class="text-xs text-gray-500">{{ $p->company }} · {{ $p->partner_type_label }}</p>
                    </div>
                    <span class="text-xs">{{ $p->opportunities_count }} فرصة</span>
                </li>
                @empty
                <li class="px-4 py-8 text-center text-gray-500">لا يوجد شركاء بعد.</li>
                @endforelse
            </ul>
            <div class="px-4 py-2">{{ $partners->links('pagination::tailwind', ['pageName'=>'partners_page']) }}</div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border overflow-hidden">
            <div class="px-5 py-4 border-b font-bold font-tajawal">الفرص (Opportunities)</div>
            @can('create-sales')
            <form method="POST" action="{{ route('bd.opportunities.store') }}" class="p-4 border-b bg-gray-50 space-y-2 text-sm">
                @csrf
                <input name="title" required placeholder="عنوان الفرصة..." class="w-full border rounded-lg px-3 py-2">
                <div class="grid grid-cols-2 gap-2">
                    <select name="partner_id" class="border rounded-lg px-3 py-2">
                        <option value="">شريك (اختياري)</option>
                        @foreach(\App\Models\BdPartner::orderBy('name')->get() as $ptr)
                            <option value="{{ $ptr->id }}">{{ $ptr->name }}</option>
                        @endforeach
                    </select>
                    <input name="estimated_value" type="number" placeholder="القيمة التقديرية" class="border rounded-lg px-3 py-2">
                </div>
                <button class="w-full py-2 rounded-lg text-white font-bold" style="background:{{ $themeColor }};">+ فرصة</button>
            </form>
            @endcan
            <ul class="divide-y text-sm font-tajawal">
                @forelse($opportunities as $o)
                <li class="px-4 py-3">
                    <div class="flex justify-between gap-2">
                        <div>
                            <strong>{{ $o->title }}</strong>
                            <p class="text-xs text-gray-500">{{ $o->reference_code }} · {{ $o->status_label }}</p>
                        </div>
                        <span class="font-bold">{{ $o->estimated_value ? number_format($o->estimated_value) : '—' }}</span>
                    </div>
                    @if($o->status!=='converted' && $o->status!=='lost')
                    @can('create-sales')
                    <form method="POST" action="{{ route('bd.opportunities.convert', $o) }}" class="mt-2">@csrf
                        <button class="text-xs font-bold text-blue-600">تحويل إلى Lead →</button>
                    </form>
                    @endcan
                    @elseif($o->lead_id)
                    <a href="{{ route('leads.show', $o->lead_id) }}" class="text-xs text-emerald-600 font-bold">عرض Lead</a>
                    @endif
                </li>
                @empty
                <li class="px-4 py-8 text-center text-gray-500">لا توجد فرص.</li>
                @endforelse
            </ul>
            <div class="px-4 py-2">{{ $opportunities->links('pagination::tailwind', ['pageName'=>'opportunities_page']) }}</div>
        </div>
    </div>
</div>
@endsection
