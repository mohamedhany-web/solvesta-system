@php
    $showAdminGroup = $webUser && (
        $webUser->hasAnyRole(['super_admin', 'admin']) ||
        ($deptNav->unrestricted && (
            $webUser->can('view-users') ||
            $webUser->can('view-reports') ||
            $webUser->can('view-departments') ||
            $webUser->can('view-settings') ||
            $webUser->can('manage-roles')
        ))
    );

    $showHrGroup = (
        ($deptNav->canShow('hr') && $webUser && (
            $webUser->can('view-employees') ||
            $webUser->can('view-attendance') ||
            $webUser->can('view-leaves') ||
            $webUser->can('view-salaries') ||
            $webUser->can('view-assets')
        )) ||
        ($webUser && \App\Policies\DepartmentAccess::isDepartmentManager($webUser)) ||
        ($deptNav->canShow('training') && $webUser && $webUser->can('view-training')) ||
        ($deptNav->canShow('jobs') && $webUser && $webUser->can('view-jobs')) ||
        ($deptNav->canShow('meetings') && $webUser && $webUser->can('view-meetings'))
    );

    $showProjectsGroup = ($webUser && (
        ($deptNav->canShow('projects') || $deptNav->canShow('tasks') || $deptNav->canShow('dev_workflow') || $deptNav->canShow('github')) &&
        ($webUser->can('view-own-projects') || $webUser->can('view-all-projects') || $webUser->can('view-own-tasks') || $webUser->can('view-all-tasks') || $webUser->can('view-dev-workflow') || $webUser->can('manage-github-integration') || $webUser->can('view-github-integration'))
    )) || ($webUser && ($webUser->can('view-bugs') || $webUser->can('view-qa'))) || ($webUser && $webUser->can('view-design'));

    $showCrmGroup = !($webUser && $webUser->hasRole('sales_rep') && ! $webUser->can('edit-employees')) && (
        ($deptNav->canShow('clients') && $webUser && ($webUser->can('view-clients') || $webUser->can('view-sales') || $webUser->can('view-contracts') || $webUser->can('view-invoices'))) ||
        ($webUser && $webUser->can('view-marketing'))
    );

    $showAccountingGroup = $deptNav->canShow('accounting') && $webUser && $webUser->can('view-finance');
    $showSupportGroup = $webUser && $webUser->can('view-tickets');
@endphp

{{-- الإدارة والنظام --}}
@if($showAdminGroup)
<div class="mt-6">
    <h3 class="sidebar-section-title px-4">الإدارة والنظام</h3>
    @if(Auth::user()?->hasRole(['super_admin', 'admin']))
    <a href="{{ route('executive.dashboard') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('executive.dashboard') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        Control Center
    </a>
    <a href="{{ route('executive.finance') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('executive.finance') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
        </svg>
        تقارير مالية CEO
    </a>
    @endif
    @can('view-users')
    <a href="{{ route('users.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('users.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
        </svg>
        المستخدمين
    </a>
    @endcan
    @can('manage-roles')
    <a href="{{ route('roles.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('roles.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
        </svg>
        الأدوار والصلاحيات
    </a>
    @endcan
    @can('view-reports')
    <a href="{{ route('reports.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('reports.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        التقارير والتحليل
    </a>
    <a href="{{ route('admin.department-oversight.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.department-oversight.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4V7M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
        </svg>
        متابعة الأقسام
    </a>
    <a href="{{ route('admin.department-reports.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.department-reports.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        تقارير الأقسام
    </a>
    @endcan
    @can('view-departments')
    <a href="{{ route('departments.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('departments.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
        الأقسام
    </a>
    @endcan
    @if($webUser && ($webUser->can('view-users') || $webUser->can('manage-roles')))
    <a href="{{ route('login-activity.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('login-activity.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
        </svg>
        سجل عمليات تسجيل الدخول
    </a>
    <a href="{{ route('system-monitoring.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('system-monitoring.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
        نظام المراقبة الشامل
    </a>
    @endif
    @can('view-settings')
    <a href="{{ route('system-settings.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('system-settings.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        إعدادات النظام
    </a>
    @endcan
</div>
@endif

{{-- الموارد البشرية --}}
@if($showHrGroup)
<div class="mt-6">
    <h3 class="sidebar-section-title px-4">الموارد البشرية</h3>
    @if($deptNav->canShow('hr') && $webUser && ($webUser->can('view-employees') || $webUser->can('view-attendance') || $webUser->can('view-leaves') || $webUser->can('view-salaries')))
    @can('view-employees')
    <a href="{{ route('employees.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('employees.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        الموظفين
    </a>
    <a href="{{ route('kpi.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('kpi.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        KPI الموظفين
    </a>
    <a href="{{ route('hr.warnings.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('hr.warnings.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        تحذيرات HR
    </a>
    @if($deptNav->canShow('promotions'))
    <a href="{{ route('hr.promotions.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('hr.promotions.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
        </svg>
        الترقيات والتطوير الوظيفي
    </a>
    @endif
    @endcan
    @can('view-attendance')
    <a href="{{ route('attendances.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        الحضور والانصراف
    </a>
    @endcan
    @can('view-leaves')
    <a href="{{ route('leaves.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('leaves.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        الإجازات
    </a>
    @endcan
    @can('view-salaries')
    <a href="{{ route('salaries.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('salaries.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
        </svg>
        الرواتب
    </a>
    @endcan
    @endif
    @if($webUser && \App\Policies\DepartmentAccess::isDepartmentManager($webUser))
    <a href="{{ route('department-manager.dashboard') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('department-manager.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4V7M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
        </svg>
        لوحة مدير القسم
    </a>
    @endif
    @if($deptNav->canShow('training'))
    @can('view-training')
    <a href="{{ route('training.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('training.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        إدارة التدريب
    </a>
    @endcan
    @endif
    @if($deptNav->canShow('jobs'))
    @can('view-jobs')
    <a href="{{ route('job-postings.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('job-postings.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        الوظائف والتوظيف
    </a>
    @endcan
    @endif
    @if($deptNav->canShow('meetings'))
    @can('view-meetings')
    <a href="{{ route('meetings.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('meetings.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        إدارة الاجتماعات
    </a>
    @endcan
    @endif
    @if($deptNav->canShow('hr') && $webUser && $webUser->can('view-assets'))
    <a href="{{ route('assets.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('assets.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
        إدارة الأصول
    </a>
    @endif
</div>
@endif

{{-- المشاريع والتطوير --}}
@if($showProjectsGroup)
<div class="mt-6">
    <h3 class="sidebar-section-title px-4">المشاريع والتطوير</h3>
    @if($deptNav->canShow('projects') && $webUser && ($webUser->can('view-own-projects') || $webUser->can('view-all-projects')))
    <a href="{{ route('projects.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('projects.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
        المشاريع
    </a>
    <a href="{{ route('pmo.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('pmo.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
        </svg>
        PMO (التنفيذ)
    </a>
    @endif
    @if($webUser && ($webUser->can('view-dev-workflow') || $webUser->can('manage-github-integration')))
    <a href="{{ route('dev-workflow.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('dev-workflow.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
        بيئة التطوير
        @if($webUser->can('manage-github-integration') && ($pendingGitHubAccessCount ?? 0) > 0)
        <span class="mr-auto text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-400 text-amber-950">{{ $pendingGitHubAccessCount }}</span>
        @endif
    </a>
    @endif
    @if($webUser && ($webUser->can('view-github-integration') || $webUser->can('manage-github-integration')))
    <a href="{{ route('github.index', $webUser->can('manage-github-integration') ? ['tab' => 'access'] : []) }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('github.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
        تكامل GitHub
        @if($webUser->can('manage-github-integration'))
        <span class="mr-auto text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-600 text-slate-100">إدارة</span>
        @if(($pendingGitHubAccessCount ?? 0) > 0)
        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-400 text-amber-950">{{ $pendingGitHubAccessCount }} طلب</span>
        @endif
        @endif
    </a>
    @endif
    @if($webUser && ($webUser->can('view-own-tasks') || $webUser->can('view-all-tasks')))
    <a href="{{ route('workspace.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('workspace.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
        </svg>
        مساحة عملي
    </a>
    <a href="{{ route('daily-reports.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('daily-reports.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
        التقارير اليومية
    </a>
    <a href="{{ route('tasks.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
        </svg>
        المهام
    </a>
    @endif
    @can('view-bugs')
    <a href="{{ route('bugs.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('bugs.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        تتبع الأخطاء
    </a>
    @endcan
    @can('view-qa')
    <a href="{{ route('qa.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('qa.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        الجودة والاختبارات
    </a>
    @endcan
    @can('view-design')
    <a href="{{ route('design.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('design.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z" />
        </svg>
        إدارة التصميم
    </a>
    @endcan
</div>
@endif

{{-- المبيعات والعملاء --}}
@if($showCrmGroup)
<div class="mt-6">
    <h3 class="sidebar-section-title px-4">المبيعات والعملاء</h3>
    @if($deptNav->canShow('clients') && $webUser && ($webUser->can('view-clients') || $webUser->can('view-sales') || $webUser->can('view-contracts') || $webUser->can('view-invoices')))
    @can('view-clients')
    <a href="{{ route('clients.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('clients.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        العملاء
    </a>
    <a href="{{ route('client-service-reports.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('client-service-reports.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        تقارير العملاء
    </a>
    <a href="{{ route('client-shared-documents.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('client-shared-documents.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg>
        مستندات العملاء (بوابة)
    </a>
    @endcan
    <a href="{{ route('client-accounts.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('client-accounts.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 1.656-1.791 3-4 3s-4-1.344-4-3 1.791-3 4-3 4 1.344 4 3zm8 0c0 1.656-1.791 3-4 3s-4-1.344-4-3 1.791-3 4-3 4 1.344 4 3z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 20v-1a5 5 0 0110 0v1M14 20v-1a5 5 0 0110 0v1" />
        </svg>
        حسابات العملاء
    </a>
    @can('view-sales')
    @if(auth()->user()->hasRole('sales_rep') || auth()->user()->can('edit-employees'))
    <a href="{{ route('sales.dashboard') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('sales.dashboard') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        لوحة موظف المبيعات
    </a>
    @endif
    @can('edit-employees')
    <a href="{{ route('sales.kpi-targets.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('sales.kpi-targets.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
        </svg>
        أهداف KPI — المبيعات
    </a>
    @endcan
    <a href="{{ route('leads.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('leads.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        </svg>
        Leads (تطوير الأعمال)
    </a>
    <a href="{{ route('bd.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('bd.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        BD — شركاء وفرص
    </a>
    <a href="{{ route('sales.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('sales.index') || request()->routeIs('sales.show') || request()->routeIs('sales.create') || request()->routeIs('sales.edit') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
        </svg>
        المبيعات
    </a>
    <a href="{{ route('pre-sales.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('pre-sales.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Pre-Sales (تقدير وعروض)
    </a>
    @endcan
    @can('view-contracts')
    <a href="{{ route('contracts.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('contracts.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        العقود
    </a>
    @endcan
    @canany(['view-finance', 'view-invoices'])
    <a href="{{ route('invoices.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('invoices.*') && !request()->routeIs('financial-invoices.*') && !request()->routeIs('project-invoices.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg>
        الفواتير الشهرية
    </a>
    @endcanany
    @endif
    @can('view-marketing')
    <a href="{{ route('marketing.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('marketing.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
        </svg>
        إدارة التسويق
    </a>
    @endcan
</div>
@endif

{{-- المالية والمحاسبة --}}
@if($showAccountingGroup)
<div class="mt-6">
    <h3 class="sidebar-section-title px-4">المالية والمحاسبة</h3>
    <a href="{{ route('accounting.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('accounting.index') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        لوحة التحكم المالية
    </a>
    <a href="{{ route('accounting.accounts') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('accounting.accounts') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
        </svg>
        شجرة الحسابات
    </a>
    <a href="{{ route('accounting.wallets.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('accounting.wallets.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        المحافظ والمعاملات
    </a>
    <a href="{{ route('accounting.client-services.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('accounting.client-services.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        خدمات ما بعد البيع
    </a>
    <a href="{{ route('accounting.journal-entries') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('accounting.journal-entries') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        القيود المحاسبية
    </a>
    <a href="{{ route('invoices.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('invoices.*') && !request()->routeIs('financial-invoices.*') && !request()->routeIs('project-invoices.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        الفواتير الشهرية
    </a>
    <a href="{{ route('financial-invoices.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('financial-invoices.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        الفواتير المالية
    </a>
    <a href="{{ route('payments.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('payments.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        المدفوعات
    </a>
    <a href="{{ route('expenses.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        المصروفات
    </a>
    <a href="{{ route('accounting.guide') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('accounting.guide') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        دليل نظام المحاسبة
    </a>
    <a href="{{ route('accounting.reports.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('accounting.reports.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        التقارير المالية
    </a>
    <a href="{{ route('project-invoices.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('project-invoices.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg>
        فواتير المشاريع
    </a>
</div>
@endif

{{-- الدعم وخدمة العملاء --}}
@if($showSupportGroup)
<div class="mt-6">
    <h3 class="sidebar-section-title px-4">الدعم وخدمة العملاء</h3>
    <a href="{{ route('tickets.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
        تذاكر الدعم
    </a>
    <a href="{{ route('client-system-projects.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('client-system-projects.*', 'client-system-features.show', 'client-system-features.update', 'client-system-features.updates.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
        </svg>
        ميزات وتحسينات العملاء
    </a>
    <a href="{{ route('client-website-issues.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('client-website-issues.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        بلاغات عملاء الموقع
    </a>
    <a href="{{ route('client-meeting-requests.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('client-meeting-requests.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        طلبات اجتماعات العملاء
    </a>
    <a href="{{ route('support.contact-requests.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('support.contact-requests.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
        </svg>
        نماذج التواصل
    </a>
</div>
@endif
