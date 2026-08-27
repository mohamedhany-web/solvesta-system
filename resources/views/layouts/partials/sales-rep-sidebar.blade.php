<div class="mt-2">
    <h3 class="sidebar-section-title px-4">مساحة المبيعات</h3>

    @can('view-sales')
    <a href="{{ route('sales.dashboard') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('sales.dashboard') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        لوحة المبيعات
    </a>
    <a href="{{ route('leads.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('leads.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        </svg>
        Leads — العملاء المحتملون
    </a>
    <a href="{{ route('sales.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('sales.index', 'sales.show', 'sales.create', 'sales.edit') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
        </svg>
        Pipeline — الفرص
    </a>
    <a href="{{ route('pre-sales.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('pre-sales.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Pre-Sales — العروض
    </a>
    @endcan

    @can('view-clients')
    <a href="{{ route('clients.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('clients.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        عملائي
    </a>
    @endcan

    @can('view-sales')
    <a href="{{ route('sales.guide') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('sales.guide') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        دليل النظام
    </a>
    @endcan
</div>

<div class="mt-6">
    <h3 class="sidebar-section-title px-4">حسابي</h3>
    <a href="{{ route('messages.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('messages.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        الرسائل
        <span id="unread-messages-count" class="mr-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 hidden">0</span>
    </a>
    <a href="{{ route('notifications.index') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        الإشعارات
        <span id="unread-notifications-count" class="mr-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 hidden">0</span>
    </a>
    <a href="{{ route('profile.edit') }}"
       class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        الملف الشخصي
    </a>
</div>
