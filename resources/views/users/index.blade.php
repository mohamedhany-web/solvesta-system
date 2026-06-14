@extends('layouts.app')

@section('page-title', 'إدارة المستخدمين')

@section('content')
@php $themeColor = \App\Helpers\SettingsHelper::getThemeColor(); @endphp
<div class="w-full max-w-full font-tajawal">
    @include('partials.erp-page-header', [
        'title' => 'إدارة المستخدمين',
        'subtitle' => 'حسابات الدخول، الأدوار، وربط الموظفين بالأقسام',
        'icon' => 'users',
    ])

    <div class="flex flex-wrap justify-end gap-3 mb-6">
        @can('create-users')
        <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-bold text-sm shadow-lg hover:opacity-95"
           style="background: linear-gradient(135deg, {{ $themeColor }} 0%, {{ $themeColor }}dd 100%);">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            مستخدم جديد
        </a>
        @endcan
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @foreach([
            ['إجمالي المستخدمين', $stats['total'], $themeColor],
            ['بريد مُفعّل', $stats['verified'], '#059669'],
            ['بانتظار التأكيد', $stats['pending'], '#d97706'],
            ['مديرون', $stats['admins'], '#7c3aed'],
        ] as [$label, $val, $color])
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-5">
            <p class="text-xs text-gray-500">{{ $label }}</p>
            <p class="text-3xl font-bold mt-1" style="color: {{ $color }};">{{ $val }}</p>
        </div>
        @endforeach
    </div>

    <form method="GET" class="bg-white border border-gray-200 rounded-2xl p-4 mb-6 flex flex-wrap gap-3 items-end shadow-sm">
        <div class="flex-1 min-w-[12rem]">
            <label class="text-xs font-bold text-gray-600 block mb-1">بحث</label>
            <input name="search" value="{{ request('search') }}" placeholder="الاسم أو البريد..."
                   class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">الدور</label>
            <select name="role" class="border border-gray-300 rounded-xl px-3 py-2 text-sm min-w-[10rem]">
                <option value="">الكل</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" @selected(request('role') === $role->name)>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-xs font-bold text-gray-600 block mb-1">القسم</label>
            <select name="department_id" class="border border-gray-300 rounded-xl px-3 py-2 text-sm min-w-[10rem]">
                <option value="">الكل</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" @selected(request('department_id') == $department->id)>{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-xl text-sm font-bold">تصفية</button>
        @if(request()->hasAny(['search', 'role', 'department_id']))
        <a href="{{ route('users.index') }}" class="border border-gray-300 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-gray-50">إعادة تعيين</a>
        @endif
    </form>

    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-900">قائمة المستخدمين <span class="text-gray-400 font-normal text-sm">({{ $users->total() }})</span></h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">المستخدم</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">البريد</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">القسم</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">الأدوار</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">الحالة</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600">تاريخ الإنشاء</th>
                        <th class="px-4 py-3 text-right font-semibold text-gray-600"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-blue-50/40 transition-colors align-middle">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl flex items-center justify-center text-white font-bold shrink-0" style="background: {{ $themeColor }};">
                                    {{ mb_substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <a href="{{ route('users.show', $user) }}" class="font-bold text-gray-900 hover:text-blue-700">{{ $user->name }}</a>
                                    @if($user->employee?->position)
                                        <p class="text-xs text-gray-500">{{ $user->employee->position }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->employee?->department?->name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @forelse($user->roles as $role)
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">{{ $role->name }}</span>
                                @empty
                                    <span class="text-gray-400 text-xs">—</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($user->employee)
                                <span class="text-xs font-bold px-2 py-1 rounded-full {{ $user->employee->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $user->employee->status === 'active' ? 'نشط' : ($user->employee->status === 'inactive' ? 'غير نشط' : 'مفصول') }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400">بدون موظف</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ $user->created_at->format('Y/m/d') }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('users.show', $user) }}" class="text-xs font-bold text-blue-600 hover:underline">عرض</a>
                                @can('edit-users')
                                <a href="{{ route('users.edit', $user) }}" class="text-xs font-bold text-gray-600 hover:underline">تعديل</a>
                                @endcan
                                @can('delete-users')
                                @if(!$user->hasRole('super_admin'))
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('حذف المستخدم «{{ $user->name }}»؟');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-red-600 hover:underline">حذف</button>
                                </form>
                                @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-14 text-center text-gray-500">
                            <p class="font-bold text-lg mb-1">لا يوجد مستخدمون</p>
                            @can('create-users')
                            <a href="{{ route('users.create') }}" class="text-blue-600 font-semibold hover:underline">أضف مستخدماً جديداً</a>
                            @endcan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection
