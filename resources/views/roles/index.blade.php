@extends('layouts.app')

@section('page-title', 'إدارة الأدوار والصلاحيات')

@section('content')
<div class="w-full">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-5 sm:p-6 lg:p-8 border border-indigo-100">
            <div class="flex items-center">
                <div class="h-12 w-12 sm:h-14 sm:w-14 lg:h-16 lg:w-16 bg-gradient-to-br from-indigo-600 to-purple-700 rounded-lg lg:rounded-xl flex items-center justify-center ml-3 sm:ml-4 shadow-lg flex-shrink-0">
                    <svg class="h-6 w-6 sm:h-7 sm:w-7 lg:h-8 lg:w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-1">إدارة الأدوار والصلاحيات</h1>
                    <p class="text-sm sm:text-base lg:text-lg text-gray-600">إدارة الأدوار الوظيفية وصلاحيات المستخدمين</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm sm:text-base">
            {{ session('success') }}
        </div>
    @endif

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-2">إجمالي الأدوار</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $stats['total_roles'] }}</p>
                </div>
                <div class="hidden sm:block p-3 bg-indigo-50 rounded-lg flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-2">إجمالي الصلاحيات</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $stats['total_permissions'] }}</p>
                </div>
                <div class="hidden sm:block p-3 bg-purple-50 rounded-lg flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5 hover:shadow-md transition-all duration-200">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1 sm:mb-2">إجمالي المستخدمين</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                </div>
                <div class="hidden sm:block p-3 bg-blue-50 rounded-lg flex-shrink-0">
                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- الأدوار الوظيفية -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-4 sm:px-6 lg:px-8 py-4 sm:py-5 lg:py-6 border-b border-gray-200">
            <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900">الأدوار الوظيفية</h3>
            <p class="text-xs sm:text-sm text-gray-600">إدارة الأدوار والصلاحيات المرتبطة بها</p>
        </div>
        <div class="p-4 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                @foreach($roles as $role)
                @php
                    $roleColors = [
                        'super_admin' => 'red',
                        'admin' => 'indigo',
                        'project_manager' => 'blue',
                        'employee' => 'green',
                        'hr' => 'purple',
                        'accountant' => 'yellow',
                        'sales_rep' => 'orange',
                        'support' => 'pink',
                        'developer' => 'cyan',
                        'designer' => 'teal',
                    ];
                    $color = $roleColors[$role->name] ?? 'gray';
                    
                    $roleNames = [
                        'super_admin' => 'مدير النظام',
                        'admin' => 'مدير',
                        'project_manager' => 'مدير مشاريع',
                        'employee' => 'موظف',
                        'hr' => 'موارد بشرية',
                        'accountant' => 'محاسب',
                        'sales_rep' => 'موظف مبيعات',
                        'support' => 'دعم فني',
                        'developer' => 'مطور',
                        'designer' => 'مصمم',
                    ];
                @endphp
                <div class="bg-gradient-to-br from-{{ $color }}-50 to-{{ $color }}-100 rounded-lg p-4 sm:p-5 lg:p-6 border border-{{ $color }}-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <div class="h-10 w-10 sm:h-12 sm:w-12 bg-{{ $color }}-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <span class="text-xs sm:text-sm font-semibold text-{{ $color }}-700">{{ $role->permissions->count() }} صلاحية</span>
                    </div>
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1 sm:mb-2">{{ $roleNames[$role->name] ?? $role->name }}</h3>
                    <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4">{{ $role->name }}</p>
                    <div class="flex flex-wrap gap-1 sm:gap-2 mb-3 sm:mb-4">
                        @foreach($role->permissions->take(3) as $permission)
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-white text-{{ $color }}-700 border border-{{ $color }}-200">
                            {{ $permission->name }}
                        </span>
                        @endforeach
                        @if($role->permissions->count() > 3)
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-white text-gray-700">
                            +{{ $role->permissions->count() - 3 }} أخرى
                        </span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- قائمة المستخدمين والأدوار -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-4 sm:px-6 lg:px-8 py-4 sm:py-5 lg:py-6 border-b border-gray-200">
            <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900">المستخدمون والأدوار</h3>
            <p class="text-xs sm:text-sm text-gray-600">عرض وإدارة أدوار المستخدمين</p>
        </div>
        
        <!-- Mobile Cards View -->
        <div class="block lg:hidden">
            @foreach(\App\Models\User::with('roles')->get() as $user)
            <div class="p-4 border-b border-gray-200 hover:bg-gray-50">
                <div class="flex items-start gap-3 mb-3">
                    <div class="h-12 w-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-base font-medium text-white">{{ mb_substr($user->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</p>
                        <p class="text-xs text-gray-600 truncate">{{ $user->email }}</p>
                        <div class="mt-2 flex flex-wrap gap-1">
                            @if($user->roles->first())
                            @php
                                $roleNames = [
                                    'super_admin' => 'مدير النظام',
                                    'admin' => 'مدير',
                                    'project_manager' => 'مدير مشاريع',
                                    'employee' => 'موظف',
                                    'hr' => 'موارد بشرية',
                                    'accountant' => 'محاسب',
                                    'sales_rep' => 'موظف مبيعات',
                                    'support' => 'دعم فني',
                                    'developer' => 'مطور',
                                    'designer' => 'مصمم',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $roleNames[$user->roles->first()->name] ?? $user->roles->first()->name }}
                            </span>
                            @endif
                            <span class="text-xs text-gray-500">{{ $user->getAllPermissions()->count() }} صلاحية</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('roles.user-permissions', $user) }}" class="block w-full px-3 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors text-xs text-center font-medium">
                    إدارة الصلاحيات
                </a>
            </div>
            @endforeach
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المستخدم</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الدور الوظيفي</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الصلاحيات</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach(\App\Models\User::with('roles')->get() as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center ml-3">
                                    <span class="text-sm font-medium text-white">{{ mb_substr($user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->roles->first())
                            @php
                                $roleNames = [
                                    'super_admin' => 'مدير النظام',
                                    'admin' => 'مدير',
                                    'project_manager' => 'مدير مشاريع',
                                    'employee' => 'موظف',
                                    'hr' => 'موارد بشرية',
                                    'accountant' => 'محاسب',
                                    'sales_rep' => 'موظف مبيعات',
                                    'support' => 'دعم فني',
                                    'developer' => 'مطور',
                                    'designer' => 'مصمم',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                {{ $roleNames[$user->roles->first()->name] ?? $user->roles->first()->name }}
                            </span>
                            @else
                            <span class="text-sm text-gray-500">لا يوجد دور</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ $user->getAllPermissions()->count() }} صلاحية</span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('roles.user-permissions', $user) }}" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-colors duration-200 text-xs font-medium">
                                <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                إدارة الصلاحيات
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

