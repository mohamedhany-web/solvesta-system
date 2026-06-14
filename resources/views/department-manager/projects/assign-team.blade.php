@extends('layouts.app')

@section('page-title', 'تعيين فريق المشروع')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full font-tajawal">
    <div class="mb-6 flex flex-wrap justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">قسم: {{ $project->department?->name }}</p>
            <h1 class="text-2xl font-bold text-gray-900">تعيين فريق: {{ $project->name }}</h1>
            <p class="text-sm text-gray-600 mt-1">العميل: {{ $project->client?->name ?? '—' }}</p>
        </div>
        <a href="{{ route('department-manager.dashboard') }}" class="border border-gray-300 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50">← لوحة القسم</a>
    </div>

    @if(session('error'))
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8">
            <form method="POST" action="{{ route('department-manager.projects.assign-team.update', $project) }}" class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                @csrf
                @method('PUT')

                <div class="px-6 py-4 border-b bg-gray-50/80">
                    <h2 class="font-bold text-lg">إسناد الفريق</h2>
                    <p class="text-sm text-gray-500 mt-0.5">اختر قائد الفريق (Team Leader) ثم أعضاء الفريق من موظفي القسم</p>
                </div>

                <div class="p-6 space-y-5">
                    <div>
                        <label class="text-xs font-bold text-gray-600 block mb-1.5">قائد الفريق (Team Leader) *</label>
                        <select name="project_manager_id" required class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 @error('project_manager_id') border-red-400 @enderror">
                            <option value="" disabled @selected(!old('project_manager_id', $project->project_manager_id))>اختر قائد الفريق...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(old('project_manager_id', $project->project_manager_id) == $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('project_manager_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-600 block mb-1.5">أعضاء الفريق</label>
                        @php $selected = old('team_members', $project->teamMembers->pluck('id')->all()); @endphp
                        <select name="team_members[]" multiple class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm min-h-[200px] focus:ring-2 focus:ring-blue-500">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(is_array($selected) && in_array($user->id, $selected))>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-2">لا حاجة لإضافة قائد الفريق هنا — يُستبعد تلقائياً. للاختيار المتعدد: Ctrl / Cmd.</p>
                        @error('team_members')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="px-6 py-4 border-t bg-gray-50/50 flex flex-wrap gap-3">
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-white font-bold shadow-md"
                            style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
                        حفظ تعيين الفريق
                    </button>
                    <a href="{{ route('projects.show', $project) }}" class="px-6 py-2.5 rounded-xl border text-sm font-semibold hover:bg-gray-50">عرض المشروع</a>
                </div>
            </form>
        </div>

        <div class="xl:col-span-4 space-y-4">
            <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5 text-sm text-blue-900">
                <h3 class="font-bold mb-2">مسار العمل</h3>
                <ol class="space-y-2 list-decimal list-inside">
                    <li>أنت كرئيس قسم تستلم المشروع</li>
                    <li>تعيّن <strong>Team Leader</strong> مسؤولاً عن التنفيذ</li>
                    <li>تضيف باقي الفريق من موظفي قسمك</li>
                    <li>توزّع المهام من «إنشاء مهمة + إسناد»</li>
                </ol>
            </div>
            @if($users->isEmpty())
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm text-amber-900">
                لا يوجد موظفون نشطون مرتبطون بحسابات مستخدمين في هذا القسم.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
