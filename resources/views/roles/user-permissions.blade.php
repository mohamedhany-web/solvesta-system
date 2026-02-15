@extends('layouts.app')

@section('page-title', 'إدارة صلاحيات المستخدم')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-5 sm:p-6 lg:p-8 border border-indigo-100">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="h-12 w-12 sm:h-14 sm:w-14 lg:h-16 lg:w-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-lg sm:text-xl lg:text-2xl font-bold text-white">{{ mb_substr($user->name, 0, 1) }}</span>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-1 truncate">{{ $user->name }}</h1>
                        <p class="text-sm sm:text-base text-gray-600 truncate">{{ $user->email }}</p>
                    </div>
                </div>
                <a href="{{ route('roles.index') }}" class="bg-white text-indigo-600 px-5 py-2.5 rounded-lg hover:bg-gray-50 transition-all duration-200 shadow-sm text-sm text-center">
                    العودة
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm sm:text-base">
            {{ session('success') }}
        </div>
    @endif

    <!-- تعيين الدور الوظيفي -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-4 sm:px-6 lg:px-8 py-4 sm:py-5 lg:py-6 border-b border-gray-200">
            <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900">الدور الوظيفي</h3>
            <p class="text-xs sm:text-sm text-gray-600">اختر الدور الوظيفي للمستخدم - سيتم تطبيق الصلاحيات تلقائياً</p>
        </div>
        <div class="p-4 sm:p-6">
            <form action="{{ route('roles.assign-role', $user) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 mb-6">
                    @php
                        $roleNames = [
                            'super_admin' => ['name' => 'مدير النظام', 'desc' => 'صلاحيات كاملة على جميع أجزاء النظام', 'color' => 'red'],
                            'admin' => ['name' => 'مدير', 'desc' => 'صلاحيات إدارية شاملة', 'color' => 'indigo'],
                            'project_manager' => ['name' => 'مدير مشاريع', 'desc' => 'إدارة المشاريع والفرق', 'color' => 'blue'],
                            'employee' => ['name' => 'موظف', 'desc' => 'صلاحيات أساسية للموظفين', 'color' => 'green'],
                            'hr' => ['name' => 'موارد بشرية', 'desc' => 'إدارة الموظفين والموارد البشرية', 'color' => 'purple'],
                            'accountant' => ['name' => 'محاسب', 'desc' => 'إدارة الحسابات والمالية', 'color' => 'yellow'],
                            'sales_rep' => ['name' => 'موظف مبيعات', 'desc' => 'إدارة المبيعات والعملاء', 'color' => 'orange'],
                            'support' => ['name' => 'دعم فني', 'desc' => 'إدارة تذاكر الدعم', 'color' => 'pink'],
                            'developer' => ['name' => 'مطور', 'desc' => 'المشاريع والأخطاء البرمجية', 'color' => 'cyan'],
                            'designer' => ['name' => 'مصمم', 'desc' => 'مشاريع التصميم', 'color' => 'teal'],
                        ];
                    @endphp
                    @foreach($roles as $role)
                    @php
                        $roleInfo = $roleNames[$role->name] ?? ['name' => $role->name, 'desc' => '', 'color' => 'gray'];
                        $isSelected = $userRole && $userRole->name == $role->name;
                    @endphp
                    <label class="relative flex items-start p-3 sm:p-4 border-2 rounded-lg cursor-pointer hover:border-{{ $roleInfo['color'] }}-400 transition-all {{ $isSelected ? 'border-' . $roleInfo['color'] . '-500 bg-' . $roleInfo['color'] . '-50' : 'border-gray-200' }}">
                        <input type="radio" name="role" value="{{ $role->name }}" class="mt-1 ml-2 sm:ml-3 flex-shrink-0" {{ $isSelected ? 'checked' : '' }} onchange="this.form.submit()">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center">
                                <div class="h-7 w-7 sm:h-8 sm:w-8 bg-{{ $roleInfo['color'] }}-600 rounded-lg flex items-center justify-center ml-2 flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <p class="text-xs sm:text-sm font-semibold text-gray-900">{{ $roleInfo['name'] }}</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 mr-9 sm:mr-10">{{ $roleInfo['desc'] }}</p>
                            <p class="text-xs text-{{ $roleInfo['color'] }}-600 mt-1 mr-9 sm:mr-10">{{ $role->permissions->count() }} صلاحية</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </form>
        </div>
    </div>

    <!-- الصلاحيات الحالية للمستخدم -->
    @if($userRole)
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-8 py-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">صلاحيات الدور الحالي</h3>
                    <p class="text-sm text-gray-600">الصلاحيات الممنوحة تلقائياً من دور: <span class="font-semibold text-green-700">{{ \App\Helpers\RoleHelper::getRoleName($userRole->name) }}</span></p>
                </div>
                <span class="bg-green-100 text-green-800 px-4 py-2 rounded-lg text-sm font-semibold">
                    {{ $userRole->permissions->count() }} صلاحية نشطة
                </span>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($userRole->permissions as $permission)
                <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                    <svg class="w-5 h-5 text-green-600 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-medium text-green-900">{{ $permission->name }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- الصلاحيات المخصصة -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-8 py-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900">تخصيص الصلاحيات</h3>
            <p class="text-sm text-gray-600 mb-3">يمكنك إضافة صلاحيات إضافية أو إزالة صلاحيات من الدور الأساسي</p>
            <div class="bg-blue-100 border border-blue-300 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-xs text-blue-800">
                        <p class="font-semibold mb-1">💡 ملاحظة مهمة:</p>
                        <p>• يمكنك إزالة علامة الصح من <strong>أي صلاحية</strong> حتى لو كانت من الدور الوظيفي الافتراضي</p>
                        <p>• الصلاحيات التي تلغيها ستُحفظ كـ "معطلة" وتتجاوز صلاحيات الدور</p>
                        <p>• الصلاحيات المخصصة لها الأولوية على صلاحيات الدور</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-6">
            <form action="{{ route('roles.assign-permissions', $user) }}" method="POST">
                @csrf
                
                <!-- شريط البحث -->
                <div class="mb-6">
                    <input type="text" id="permissionSearch" placeholder="ابحث عن صلاحية..." 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                           onkeyup="filterPermissions()">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6" id="permissionsGrid">
                    @foreach($permissions as $permission)
                    @php
                        $isChecked = in_array($permission->name, $userPermissions);
                        $isFromRole = in_array($permission->name, $rolePermissions ?? []);
                        $hasCustomOverride = isset($customPermissionsMap[$permission->name]);
                        $isExplicitlyDisabled = $hasCustomOverride && !$customPermissionsMap[$permission->name];
                    @endphp
                    <label class="flex items-start p-4 rounded-lg cursor-pointer transition-all permission-item
                        {{ $isChecked ? ($isFromRole && !$hasCustomOverride ? 'bg-green-50 border-2 border-green-300 hover:bg-green-100' : 'bg-purple-50 border-2 border-purple-300 hover:bg-purple-100') : ($isExplicitlyDisabled ? 'bg-red-50 border-2 border-red-300 hover:bg-red-100' : 'bg-gray-50 border-2 border-gray-200 hover:bg-gray-100') }}">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                               {{ $isChecked ? 'checked' : '' }}
                               class="mt-1 ml-3 h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900 permission-name">
                                    {{ \App\Helpers\RoleHelper::getPermissionName($permission->name) }}
                                </p>
                                @if($isFromRole && !$hasCustomOverride)
                                <span class="text-xs bg-green-200 text-green-800 px-2 py-1 rounded-full">من الدور</span>
                                @elseif($hasCustomOverride && $customPermissionsMap[$permission->name])
                                <span class="text-xs bg-purple-200 text-purple-800 px-2 py-1 rounded-full">✓ مضافة</span>
                                @elseif($isExplicitlyDisabled)
                                <span class="text-xs bg-red-200 text-red-800 px-2 py-1 rounded-full">✗ معطلة</span>
                                @endif
                            </div>
                            @if($isFromRole && !$hasCustomOverride)
                            <p class="text-xs text-green-600 mt-1">من الدور - يمكنك إزالتها</p>
                            @elseif($hasCustomOverride && $customPermissionsMap[$permission->name])
                            <p class="text-xs text-purple-600 mt-1">صلاحية إضافية</p>
                            @elseif($isExplicitlyDisabled)
                            <p class="text-xs text-red-600 mt-1">تم تعطيلها رغم وجودها في الدور</p>
                            @endif
                        </div>
                    </label>
                    @endforeach
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <div class="flex gap-4">
                        <button type="button" onclick="selectAll()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm">
                            تحديد الكل
                        </button>
                        <button type="button" onclick="deselectAll()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm">
                            إلغاء الكل
                        </button>
                    </div>
                    <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
                        <svg class="h-5 w-5 inline ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        حفظ الصلاحيات المخصصة
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function filterPermissions() {
    const searchValue = document.getElementById('permissionSearch').value.toLowerCase();
    const items = document.querySelectorAll('.permission-item');
    
    items.forEach(item => {
        const permissionName = item.querySelector('.permission-name').textContent.toLowerCase();
        if (permissionName.includes(searchValue)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

function selectAll() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAll() {
    document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
        checkbox.checked = false;
    });
}
</script>
</div>
@endsection

