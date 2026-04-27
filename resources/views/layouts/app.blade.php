<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8mb4">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title', \App\Helpers\SettingsHelper::getSystemName()) - {{ \App\Helpers\SettingsHelper::getCompanyName() }}</title>
    
    <!-- Favicon -->
    @php $faviconUrl = \App\Helpers\SettingsHelper::getFaviconUrl(); @endphp
    <link rel="icon" type="image/x-icon" href="{{ $faviconUrl ?: '/favicon.ico' }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ $faviconUrl ?: '/favicon.ico' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;600;700;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        'tajawal': ['Tajawal', 'sans-serif'],
                        'arabic': ['Tajawal', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.0/dist/axios.min.js"></script>
    <script>
        // Setup Axios defaults
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        let token = document.head.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }
    </script>
    
    <style>
        .font-tajawal {
            font-family: 'Tajawal', sans-serif;
        }
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.3);
            border-radius: 2px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.6);
        }
        
        /* Original Dark Theme Sidebar */
        .sidebar-bg {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            border-color: #334155;
        }
        
        .sidebar-nav-bg {
            background: transparent;
        }
        
        .sidebar-user-bg {
            background: #1e293b;
            border-color: #334155;
            color: white;
        }
        
        .sidebar-user-bg * {
            color: white !important;
        }
        
        .sidebar-user-bg .text-slate-300 {
            color: #cbd5e1 !important;
        }
        
        .sidebar-bg {
            color: white;
        }
        
        .sidebar-bg h1,
        .sidebar-bg h2,
        .sidebar-bg h3,
        .sidebar-bg p,
        .sidebar-bg div,
        .sidebar-bg span {
            color: inherit;
        }
        
        .sidebar-link {
            background: transparent;
            color: #94a3b8;
            border-radius: 8px;
            margin: 2px 0;
            transition: all 0.2s ease;
        }
        
        .sidebar-link:hover {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }
        
        .sidebar-link.active {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .sidebar-section-title {
            color: #64748b;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 16px 0 8px 0;
        }
        
        /* Mobile Responsive Sidebar */
        @media (max-width: 768px) {
            .sidebar-bg {
                position: fixed;
                top: 0;
                right: 0;
                height: 100vh;
                z-index: 50;
                transform: translateX(100%);
                transition: transform 0.3s ease-in-out;
            }
            
            .sidebar-bg.mobile-open {
                transform: translateX(0);
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 40;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease-in-out;
            }
            
            .sidebar-overlay.active {
                opacity: 1;
                visibility: visible;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .main-content-mobile {
                margin-right: 0;
            }
        }
        
        @media (min-width: 769px) {
            .mobile-menu-btn {
                display: none;
            }
            
            .sidebar-overlay {
                display: none;
            }
        }
        
        /* Dark Theme Body */
        .dark-body {
            background: #0f172a;
        }
        
        .dark-main {
            background: #0f172a;
        }
        
        .dark-header {
            background: #ffffff;
            border-color: #e5e7eb;
            backdrop-filter: blur(10px);
        }
        
        /* Time updater for dashboard */
        #dashboard-time {
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }
        
        .dark-content {
            background: #0f172a;
        }
    </style>
    
    <!-- WhatsApp Automation Script -->
    <script src="{{ asset('js/whatsapp-automation.js') }}"></script>
</head>
<body class="bg-gray-50 font-tajawal">
    @php
        $webUser = Auth::guard('web')->user();
        $clientAccount = Auth::guard('client')->user();
        $isClientGuard = (bool) $clientAccount && !$webUser;
        $displayName = $webUser?->name ?? $clientAccount?->name ?? 'مستخدم';
        $displayEmail = $webUser?->email ?? $clientAccount?->email ?? null;
        $displayRole = $webUser?->roles?->first()?->name ?? ($isClientGuard ? 'عميل' : 'مستخدم');
    @endphp
    <div class="flex h-screen">
        <!-- Mobile Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        
        <!-- Sidebar -->
        <div class="w-72 sidebar-bg border-l border-slate-700 shadow-xl flex flex-col" id="sidebar" style="background-color: {{ \App\Helpers\SettingsHelper::getSidebarColor() }}">
            <!-- Logo -->
            <div class="p-6 border-b border-slate-700 bg-slate-800 shadow-sm">
                <div class="flex items-center">
                    @php
                        $logoUrl = \App\Helpers\SettingsHelper::getLogoUrl();
                        $logoSize = \App\Helpers\SettingsHelper::getLogoSizePixels();
                    @endphp
                    
                    @if($logoUrl)
                        <!-- Custom Logo -->
                        <div class="{{ $logoSize }} rounded-xl overflow-hidden ml-4 shadow-xl">
                            <img src="{{ $logoUrl }}" alt="Logo" class="w-full h-full object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        </div>
                    @else
                        <!-- Default Logo with Enhanced Design -->
                        <div class="{{ $logoSize }} rounded-xl flex items-center justify-center ml-4 shadow-xl overflow-hidden relative" style="background: linear-gradient(135deg, {{ \App\Helpers\SettingsHelper::getThemeColor() }} 0%, {{ \App\Helpers\SettingsHelper::getThemeColor() }}cc 100%);">
                            <div class="relative">
                                <!-- Gear Icon -->
                                <svg class="h-6 w-6 text-white absolute top-0 right-0 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                </svg>
                                <!-- Search Icon -->
                                <svg class="h-5 w-5 text-white absolute bottom-0 left-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <!-- Subtle glow effect -->
                            <div class="absolute inset-0 rounded-xl opacity-20" style="background: radial-gradient(circle at center, white 0%, transparent 70%);"></div>
                        </div>
                    @endif
                    
                    <div class="flex-1">
                        @if($isClientGuard)
                            <h1 class="text-xl font-bold text-white drop-shadow-sm tracking-wide">بوابة العميل</h1>
                            <p class="text-sm text-slate-300 leading-relaxed">{{ \App\Helpers\SettingsHelper::getCompanyName() }}</p>
                        @else
                            <h1 class="text-xl font-bold text-white drop-shadow-sm tracking-wide">{{ \App\Helpers\SettingsHelper::getSystemName() }}</h1>
                            <p class="text-sm text-slate-300 leading-relaxed">{{ \App\Helpers\SettingsHelper::getSystemDescription() }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Navigation - Scrollable -->
            <nav class="flex-1 overflow-y-auto sidebar-scroll sidebar-nav-bg">
                <div class="p-3">
                    <div class="space-y-1">
                        <!-- Dashboard (web users only) -->
                        @if(!$isClientGuard)
                        <a href="{{ route('dashboard') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                            </svg>
                            لوحة التحكم
                        </a>
                        @endif

                        {{-- Client Portal (for client role) --}}
                        @if(Auth::guard('client')->check())
                        <div class="mt-6">
                            <h3 class="sidebar-section-title px-4">بوابة العميل</h3>
                            <a href="{{ route('client.dashboard') }}"
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 1.656-1.791 3-4 3s-4-1.344-4-3 1.791-3 4-3 4 1.344 4 3zm8 0c0 1.656-1.791 3-4 3s-4-1.344-4-3 1.791-3 4-3 4 1.344 4 3z" />
                                </svg>
                                لوحة العميل
                            </a>
                            <a href="{{ route('client.projects') }}"
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('client.projects') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                                </svg>
                                مشاريعي
                            </a>
                            <a href="{{ route('client.invoices') }}"
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('client.invoices') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                فواتيري
                            </a>
                            <a href="{{ route('client.support.tickets') }}"
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('client.support.*') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-1.414-1.414a2 2 0 00-2.828 0L7 11.343V15h3.657l7.707-7.707a2 2 0 000-2.828z" />
                                </svg>
                                ما بعد البيع
                            </a>
                        </div>
                        @endif
                        
                        <!-- الرسائل - web users only -->
                        @if(!$isClientGuard)
                        <a href="{{ route('messages.index') }}" 
                           class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                            <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            الرسائل
                            <span id="unread-messages-count" class="mr-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 hidden">0</span>
                        </a>
                        @endif
                        
                        <!-- الإشعارات - web users only -->
                        @if(!$isClientGuard)
                        <a href="{{ route('notifications.index') }}" 
                           class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                            <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            الإشعارات
                            <span id="unread-notifications-count" class="mr-auto bg-red-500 text-white text-xs rounded-full px-2 py-1 hidden">0</span>
                        </a>
                        @endif
                        
                        <!-- Administration Section -->
                        @if($webUser && ($webUser->can('view-users') || $webUser->can('view-reports') || $webUser->can('view-departments') || $webUser->can('view-settings') || $webUser->can('manage-roles')))
                        <div class="mt-6">
                            <h3 class="sidebar-section-title px-4">الإدارة العليا</h3>
                            
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
                            @endif
                            
                            @if($webUser && ($webUser->can('view-users') || $webUser->can('manage-roles')))
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

                        <!-- Advanced HR Management -->
                        @if($webUser && ($webUser->can('view-employees') || $webUser->can('view-attendance') || $webUser->can('view-leaves') || $webUser->can('view-salaries')))
                        <div class="mt-6">
                            <h3 class="sidebar-section-title px-4">إدارة الموارد البشرية المتقدمة</h3>
                            
                            @can('view-employees')
                            <a href="{{ route('employees.index') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                الموظفين
                            </a>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 2z" />
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
                        </div>
                        @endif

                        <!-- Project Management Section -->
                        @if($webUser && ($webUser->can('view-own-projects') || $webUser->can('view-all-projects') || $webUser->can('view-own-tasks') || $webUser->can('view-all-tasks')))
                        <div class="mt-6">
                            <h3 class="sidebar-section-title px-4">إدارة المشاريع</h3>
                            
                            @if($webUser && ($webUser->can('view-own-projects') || $webUser->can('view-all-projects')))
                            <a href="{{ route('projects.index') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                المشاريع
                            </a>
                            @endif
                            
                            @if($webUser && ($webUser->can('view-own-tasks') || $webUser->can('view-all-tasks')))
                            <a href="{{ route('tasks.index') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                المهام
                            </a>
                            @endif
                        </div>
                        @endif

                        <!-- Training & Development Section -->
                        @can('view-training')
                        <div class="mt-6">
                            <h3 class="sidebar-section-title px-4">التدريب والتطوير</h3>
                            
                            <a href="{{ route('training.index') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('training.*') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                إدارة التدريب
                            </a>
                        </div>
                        @endcan

                        <!-- Meetings & Conferences Section -->
                        @can('view-meetings')
                        <div class="mt-6">
                            <h3 class="sidebar-section-title px-4">الاجتماعات والمؤتمرات</h3>
                            
                            <a href="{{ route('meetings.index') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('meetings.*') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                إدارة الاجتماعات
                            </a>
                        </div>
                        @endcan

                        <!-- Assets & Properties Section -->
                        @if($webUser && $webUser->can('view-assets'))
                        <div class="mt-6">
                            <h3 class="sidebar-section-title px-4">الأصول والممتلكات</h3>
                            
                            <a href="{{ route('assets.index') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('assets.*') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                إدارة الأصول
                            </a>
                        </div>
                        @endif

                        <!-- CRM System -->
                        @if($webUser && ($webUser->can('view-clients') || $webUser->can('view-sales') || $webUser->can('view-contracts') || $webUser->can('view-invoices')))
                        <div class="mt-6">
                            <h3 class="sidebar-section-title px-4">نظام إدارة العملاء (CRM)</h3>
                            
                            @can('view-clients')
                            <a href="{{ route('clients.index') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                العملاء
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
                            <a href="{{ route('sales.index') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                المبيعات
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
                            
                            @can('view-invoices')
                            <a href="{{ route('invoices.index') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                الفواتير
                            </a>
                            @endcan
                        </div>
                        @endif

                        <!-- Development Section -->
                        @if($webUser && ($webUser->can('view-bugs') || $webUser->can('view-qa')))
                        <div class="mt-6">
                            <h3 class="sidebar-section-title px-4">التطوير والبرمجة</h3>
                            
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
                        </div>
                        @endif

                        <!-- Design Section -->
                        @can('view-design')
                        <div class="mt-6">
                            <h3 class="sidebar-section-title px-4">التصميم</h3>
                            
                            <a href="{{ route('design.index') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('design.*') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z" />
                                </svg>
                                إدارة التصميم
                            </a>
                        </div>
                        @endif

                        <!-- Marketing Section -->
                        @can('view-marketing')
                        <div class="mt-6">
                            <h3 class="sidebar-section-title px-4">التسويق</h3>
                            
                            <a href="{{ route('marketing.index') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('marketing.*') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                                إدارة التسويق
                            </a>
                        </div>
                        @endif

                        <!-- Technical Support Section -->
                        @can('view-tickets')
                        <div class="mt-6">
                            <h3 class="sidebar-section-title px-4">الدعم الفني</h3>
                            
                            <a href="{{ route('tickets.index') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                                تذاكر الدعم
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

                        <!-- Finance & Accounting Section -->
                        @can('view-finance')
                        <div class="mt-6">
                            <h3 class="sidebar-section-title px-4">المالية والمحاسبة</h3>
                            
                            <!-- لوحة التحكم المالية -->
                            <a href="{{ route('accounting.index') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('accounting.index') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                لوحة التحكم المالية
                            </a>

                            <!-- شجرة الحسابات -->
                            <a href="{{ route('accounting.accounts') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('accounting.accounts') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                شجرة الحسابات
                            </a>

                            <!-- القيود المحاسبية -->
                            <a href="{{ route('accounting.journal-entries') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('accounting.journal-entries') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                القيود المحاسبية
                            </a>

                            <!-- الفواتير المالية -->
                            <a href="{{ route('financial-invoices.index') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('financial-invoices.*') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                الفواتير المالية
                            </a>

                            <!-- المدفوعات -->
                            <a href="{{ route('payments.index') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('payments.*') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                المدفوعات
                            </a>

                            <!-- المصروفات -->
                            <a href="{{ route('expenses.index') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                المصروفات
                            </a>

                            <!-- التقارير المالية -->
                            <a href="{{ route('accounting.reports.index') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('accounting.reports.*') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                التقارير المالية
                            </a>

                            <!-- الفواتير القديمة -->
                            <a href="{{ route('invoices.index') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('invoices.*') && !request()->routeIs('financial-invoices.*') ? 'active' : '' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                فواتير المشاريع
                            </a>
                        </div>
                        @endif

                        <!-- Legal Section -->
                        @can('view-contracts')
                        <div class="mt-6">
                            <h3 class="sidebar-section-title px-4">الشؤون القانونية</h3>
                            
                            <a href="{{ route('contracts.index') }}" 
                               class="sidebar-link flex items-center px-4 py-3 text-sm font-medium {{ request()->routeIs('contracts.*') ? 'bg-indigo-100 text-indigo-800 shadow-md' : 'sidebar-link' }}">
                                <svg class="ml-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                العقود
                            </a>
                        </div>
                        @endif

                    </div>
                </div>
            </nav>

            <!-- User Info & Logout -->
            <div class="p-6 border-t border-slate-700 sidebar-user-bg shadow-sm">
                @if($webUser || $clientAccount)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3 space-x-reverse">
                            <div class="h-10 w-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg relative overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-blue-500 opacity-50"></div>
                                <span class="text-lg font-bold text-white relative z-10">{{ substr($displayName, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-white">{{ $displayName }}</div>
                                <div class="text-xs text-slate-300">{{ $displayRole }}</div>
                            </div>
                        </div>
                        <form method="POST" action="{{ $isClientGuard ? route('client.logout') : route('logout') }}">
                            @csrf
                            <button type="submit" class="text-slate-400 hover:text-white transition duration-200 p-2 hover:bg-slate-700 rounded-lg">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden main-content-mobile">
            <!-- Top Header -->
            <header class="bg-white shadow-md border-b border-gray-200 overflow-visible sticky top-0 z-30">
                <div class="px-3 sm:px-5 lg:px-6 py-3 sm:py-4 header-container">
                    <div class="flex items-center justify-between gap-3 sm:gap-4">
                        <!-- Left Side - Menu Button & Title -->
                        <div class="flex items-center gap-3 sm:gap-4 flex-1 min-w-0">
                            <!-- Mobile Menu Button -->
                            <button class="mobile-menu-btn p-2 rounded-lg transition-all duration-200 flex-shrink-0 hover:bg-gray-100" 
                                    style="color: {{ \App\Helpers\SettingsHelper::getThemeColor() }}"
                                    id="mobileMenuBtn"
                                    onmouseover="this.style.backgroundColor='{{ \App\Helpers\SettingsHelper::getThemeColor() }}10'"
                                    onmouseout="this.style.backgroundColor='transparent'">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-3 sm:gap-4">
                                    <h2 class="text-lg sm:text-xl font-bold text-gray-900 truncate font-tajawal">@yield('page-title', 'لوحة التحكم')</h2>
                                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-50 text-xs text-gray-600">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span id="current-date">{{ now()->format('Y/m/d') }}</span>
                                    </div>
                                </div>
                                @if($isClientGuard)
                                    <p class="text-xs text-gray-500 mt-1 hidden sm:block font-tajawal">
                                        بوابة العميل — {{ \App\Helpers\SettingsHelper::getCompanyName() }}
                                    </p>
                                @else
                                    <p class="text-xs text-gray-500 mt-1 hidden sm:block font-tajawal">مرحباً بك في نظام {{ \App\Helpers\SettingsHelper::getSystemName() }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Right Side - Actions -->
                        <div class="flex items-center gap-2 sm:gap-3">
                            @if($isClientGuard)
                                <div class="hidden sm:flex items-center gap-2">
                                    <a href="{{ route('client.projects') }}"
                                       class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-sm font-bold text-gray-800 transition">
                                        المشاريع
                                    </a>
                                    <a href="{{ route('client.invoices') }}"
                                       class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-sm font-bold text-gray-800 transition">
                                        الفواتير
                                    </a>
                                </div>
                                <a href="{{ route('client.support.tickets.create') }}"
                                   class="inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 rounded-xl text-white text-sm font-extrabold shadow-md hover:shadow-lg transition flex-shrink-0"
                                   style="background: linear-gradient(135deg, {{ \App\Helpers\SettingsHelper::getThemeColor() }} 0%, {{ \App\Helpers\SettingsHelper::getThemeColor() }}dd 100%);">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                    افتح تذكرة
                                </a>
                            @else
                            <!-- Start Day Button -->
                            <button id="startDayBtn" 
                                    class="hidden sm:flex items-center gap-2.5 px-4 sm:px-5 py-2.5 text-white rounded-xl transition-all duration-300 text-sm font-medium shadow-md hover:shadow-lg transform hover:scale-105 active:scale-95 font-tajawal"
                                    style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);"
                                    onclick="toggleWorkTimer()">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div class="flex flex-col items-start">
                                    <span id="startDayText" class="text-xs font-bold leading-tight">بدء اليوم</span>
                                    <span id="workTimer" class="text-xs opacity-90 font-mono leading-tight" style="display: none;">00:00:00</span>
                                </div>
                            </button>

                            <!-- Notifications -->
                            <a href="{{ route('notifications.index') }}" 
                               class="relative p-2.5 sm:p-3 rounded-xl transition-all duration-200 hover:shadow-md flex items-center justify-center flex-shrink-0"
                               style="background: {{ \App\Helpers\SettingsHelper::getThemeColor() }}15; color: {{ \App\Helpers\SettingsHelper::getThemeColor() }}"
                               onmouseover="this.style.background='{{ \App\Helpers\SettingsHelper::getThemeColor() }}25'"
                               onmouseout="this.style.background='{{ \App\Helpers\SettingsHelper::getThemeColor() }}15'">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <span id="top-bar-notifications-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden font-bold border-2 border-white shadow-lg"></span>
                            </a>

                            <!-- Messages -->
                            <a href="{{ route('messages.index') }}" 
                               class="hidden sm:flex relative p-2.5 sm:p-3 rounded-xl transition-all duration-200 hover:shadow-md items-center justify-center flex-shrink-0"
                               style="background: {{ \App\Helpers\SettingsHelper::getThemeColor() }}15; color: {{ \App\Helpers\SettingsHelper::getThemeColor() }}"
                               onmouseover="this.style.background='{{ \App\Helpers\SettingsHelper::getThemeColor() }}25'"
                               onmouseout="this.style.background='{{ \App\Helpers\SettingsHelper::getThemeColor() }}15'">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <span id="top-bar-messages-count" class="absolute -top-1 -right-1 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden font-bold border-2 border-white shadow-lg"></span>
                            </a>

                            <!-- Settings -->
                            <a href="{{ route('system-settings.index') }}" 
                               class="hidden sm:flex p-2.5 sm:p-3 rounded-xl transition-all duration-200 hover:shadow-md items-center justify-center flex-shrink-0"
                               style="background: {{ \App\Helpers\SettingsHelper::getThemeColor() }}15; color: {{ \App\Helpers\SettingsHelper::getThemeColor() }}"
                               onmouseover="this.style.background='{{ \App\Helpers\SettingsHelper::getThemeColor() }}25'"
                               onmouseout="this.style.background='{{ \App\Helpers\SettingsHelper::getThemeColor() }}15'">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>
                            @endif

                            <!-- User Profile Dropdown -->
                            <div class="relative overflow-visible user-dropdown-container">
                                <button onclick="toggleUserDropdown()" 
                                        class="flex items-center gap-2.5 sm:gap-3 rounded-xl p-2 sm:p-2.5 transition-all duration-200 hover:shadow-md group"
                                        style="background: {{ \App\Helpers\SettingsHelper::getThemeColor() }}10"
                                        onmouseover="this.style.background='{{ \App\Helpers\SettingsHelper::getThemeColor() }}20'"
                                        onmouseout="this.style.background='{{ \App\Helpers\SettingsHelper::getThemeColor() }}10'">
                                    <!-- User info - hidden on small screens -->
                                    <div class="text-right hidden sm:block ml-2">
                                        <div class="text-sm font-bold text-gray-900 truncate max-w-28 font-tajawal">{{ $displayName }}</div>
                                        <div class="text-xs text-gray-600 truncate max-w-28 font-tajawal">{{ $displayRole }}</div>
                                    </div>
                                    <!-- Profile picture - always visible -->
                                    @if($webUser && $webUser->profile_picture)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" 
                                             alt="Profile Picture" 
                                             class="h-10 w-10 sm:h-11 sm:w-11 rounded-xl object-cover shadow-lg hover:shadow-xl transition-all duration-200 border-2 border-white ring-2 ring-transparent group-hover:ring-opacity-50 flex-shrink-0"
                                             style="border-color: {{ \App\Helpers\SettingsHelper::getThemeColor() }}30;">
                                    @else
                                        <div class="h-10 w-10 sm:h-11 sm:w-11 rounded-xl flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-200 border-2 border-white ring-2 ring-transparent group-hover:ring-opacity-50 flex-shrink-0"
                                             style="background: linear-gradient(135deg, {{ \App\Helpers\SettingsHelper::getThemeColor() }} 0%, {{ \App\Helpers\SettingsHelper::getThemeColor() }}dd 100%); border-color: {{ \App\Helpers\SettingsHelper::getThemeColor() }}30;">
                                            <span class="text-sm sm:text-base font-bold text-white">{{ substr($displayName, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <!-- Dropdown arrow - hidden on small screens -->
                                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 hidden sm:block transition-transform duration-200 group-hover:rotate-180 flex-shrink-0" 
                                         style="color: {{ \App\Helpers\SettingsHelper::getThemeColor() }}" 
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                            
                                <!-- Dropdown Menu -->
                                <div id="userDropdown" class="absolute top-full left-0 mt-2 w-56 sm:w-64 bg-white rounded-lg shadow-xl border border-gray-200 z-[9999] hidden user-dropdown">
                                                <div class="py-2">
                                                    <!-- Profile Header -->
                                                    <div class="px-4 py-3 border-b border-gray-100">
                            <div class="flex items-center space-x-3 space-x-reverse">
                                                            @if($webUser && $webUser->profile_picture)
                                                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture" class="h-12 w-12 rounded-full object-cover shadow-sm">
                                                            @else
                                                                <div class="h-12 w-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-sm">
                                                                    <span class="text-lg font-bold text-white">{{ substr($displayName, 0, 1) }}</span>
                                                                </div>
                                                            @endif
                                                            <div class="flex-1">
                                                                <div class="text-sm font-semibold text-gray-900">{{ $displayName }}</div>
                                                                <div class="text-xs text-gray-500">{{ $displayEmail }}</div>
                                                                <div class="text-xs text-blue-600">{{ $displayRole }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Menu Items -->
                                                    <div class="py-1">
                                                        @if(!$isClientGuard)
                                                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition duration-150">
                                                            <svg class="ml-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                            الملف الشخصي
                                                        </a>
                                                        @endif
                                                        
                                                        @if(!$isClientGuard)
                                                        <a href="{{ route('system-settings.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition duration-150">
                                                            <svg class="ml-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                            </svg>
                                                            الإعدادات
                                                        </a>
                                                        @endif
                                                        
                                                        <!-- Mobile-only items -->
                                                        <div class="sm:hidden">
                                                            <div class="border-t border-gray-100 my-1"></div>
                                                            
                                                            <button onclick="toggleWorkTimer()" class="w-full flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition duration-150">
                                                                <svg class="ml-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                <div class="flex flex-col items-start">
                                                                    <span id="startDayTextMobile">بدء اليوم</span>
                                                                    <span id="workTimerMobile" class="text-xs text-gray-500" style="display: none;">00:00:00</span>
                                                                </div>
                                                            </button>
                                                            
                                                            <a href="{{ route('notifications.index') }}" class="w-full flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition duration-150">
                                                                <svg class="ml-3 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                                </svg>
                                                                الإشعارات
                                                            </a>
                                                        </div>
                                                        
                                                        <div class="border-t border-gray-100 my-1"></div>
                                                        
                                                        <form method="POST" action="{{ $isClientGuard ? route('client.logout') : route('logout') }}">
                                                            @csrf
                                                            <button type="submit" class="w-full flex items-center px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition duration-150">
                                                                <svg class="ml-3 h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                                </svg>
                                                                تسجيل الخروج
                                                            </button>
                                                        </form>
                                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                <div class="{{ request()->routeIs('messages.*') || request()->routeIs('notifications.*') || request()->routeIs('users.create', 'users.edit') || request()->routeIs('system-monitoring.*') || request()->routeIs('system-settings.*') ? 'w-full max-w-full px-3 sm:px-4 lg:px-6 xl:px-8 py-4 sm:py-6 min-h-0' : 'container mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6' }}">
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-800 px-4 py-3 rounded-r-lg shadow-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-800 px-4 py-3 rounded-r-lg shadow-sm">
                            {{ session('error') }}
                        </div>
                    @endif
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

                <script>
                    // Add CSS for dropdown positioning
                    const style = document.createElement('style');
                    style.textContent = `
                        .user-dropdown-container {
                            position: relative;
                            overflow: visible !important;
                        }
                        .user-dropdown {
                            position: absolute;
                            z-index: 9999 !important;
                            transform: translateZ(0);
                            will-change: transform;
                        }
                        .header-container {
                            overflow: visible !important;
                            position: relative;
                            z-index: 40;
                        }
                    `;
                    document.head.appendChild(style);
                    
                    // Mobile Sidebar Toggle
                    document.addEventListener('DOMContentLoaded', function() {
                        // Load saved timer state
                        loadTimerState();
                        
                        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
                        const sidebar = document.getElementById('sidebar');
                        const sidebarOverlay = document.getElementById('sidebarOverlay');
                        
                        function openSidebar() {
                            sidebar.classList.add('mobile-open');
                            sidebarOverlay.classList.add('active');
                            document.body.style.overflow = 'hidden';
                        }
                        
                        function closeSidebar() {
                            sidebar.classList.remove('mobile-open');
                            sidebarOverlay.classList.remove('active');
                            document.body.style.overflow = '';
                        }
                        
                        // Toggle sidebar when menu button is clicked
                        mobileMenuBtn.addEventListener('click', function() {
                            if (sidebar.classList.contains('mobile-open')) {
                                closeSidebar();
                            } else {
                                openSidebar();
                            }
                        });
                        
                        // Close sidebar when overlay is clicked
                        sidebarOverlay.addEventListener('click', function() {
                            closeSidebar();
                        });
                        
                        // Close sidebar when clicking on sidebar links (mobile)
                        const sidebarLinks = sidebar.querySelectorAll('.sidebar-link');
                        sidebarLinks.forEach(link => {
                            link.addEventListener('click', function() {
                                if (window.innerWidth <= 768) {
                                    closeSidebar();
                                }
                            });
                        });
                        
                        // Handle window resize
                        window.addEventListener('resize', function() {
                            if (window.innerWidth > 768) {
                                closeSidebar();
                            }
                        });
                    });

                    // Navigation Bar Functions
                    let isWorkTimerRunning = false;
                    let startTime = null;
                    let timerInterval = null;
                    let totalWorkTime = 0; // in seconds

                    let checkInDateTime = null;
                    let currentDate = new Date().toDateString();
                    
                    // Load saved timer state from localStorage and sync with attendance
                    function loadTimerState() {
                        // Always check attendance status from server first
                        fetch('/attendances/current-work-time')
                        .then(response => response.json())
                        .then(data => {
                            // Check if date has changed - reset timer if new day
                            const today = new Date().toDateString();
                            if (today !== currentDate) {
                                currentDate = today;
                                checkInDateTime = null;
                                isWorkTimerRunning = false;
                                startTime = null;
                                totalWorkTime = 0;
                                if (timerInterval) {
                                    clearInterval(timerInterval);
                                    timerInterval = null;
                                }
                            }
                            
                            // Check if working - use is_working OR check if has check_in and no check_out
                            const isWorking = data.is_working || (data.check_in_time && !data.check_out_time && data.current_status !== 'completed');
                            
                            if (isWorking && (data.check_in_datetime || data.check_in_time)) {
                                // Employee is currently working according to attendance system
                                isWorkTimerRunning = true;
                                
                                // Get check_in datetime - try multiple formats
                                if (data.check_in_datetime) {
                                    checkInDateTime = data.check_in_datetime;
                                    startTime = new Date(data.check_in_datetime);
                                } else if (data.check_in_time) {
                                    // Build datetime from check_in_time and today's date
                                    const todayDate = new Date();
                                    const [hours, minutes, seconds] = data.check_in_time.split(':');
                                    startTime = new Date(todayDate.getFullYear(), todayDate.getMonth(), todayDate.getDate(), parseInt(hours), parseInt(minutes), parseInt(seconds || 0));
                                    checkInDateTime = startTime.toISOString();
                                }
                                
                                // Update UI to show timer is running
                                const startDayText = document.getElementById('startDayText');
                                const workTimer = document.getElementById('workTimer');
                                const startDayBtn = document.getElementById('startDayBtn');
                                
                                if (startDayText) {
                                    startDayText.textContent = 'إيقاف التايمر';
                                }
                                
                                if (workTimer) {
                                    // Remove hidden class and force display
                                    workTimer.classList.remove('hidden');
                                    workTimer.style.display = 'block';
                                    workTimer.style.visibility = 'visible';
                                    workTimer.style.opacity = '0.9';
                                    workTimer.textContent = data.work_time || '00:00:00';
                                }
                                
                                if (startDayBtn) {
                                    // Update button style to red (stop timer)
                                    startDayBtn.style.background = 'linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)';
                                    startDayBtn.classList.remove('from-green-600', 'to-green-700', 'hover:from-green-700', 'hover:to-green-800');
                                    startDayBtn.classList.add('from-red-600', 'to-red-700', 'hover:from-red-700', 'hover:to-red-800');
                                }
                                
                                // Update mobile button
                                const mobileStartDayText = document.getElementById('startDayTextMobile');
                                const mobileTimer = document.getElementById('workTimerMobile');
                                if (mobileStartDayText) {
                                    mobileStartDayText.textContent = 'إيقاف التايمر';
                                }
                                if (mobileTimer) {
                                    mobileTimer.classList.remove('hidden');
                                    mobileTimer.style.display = 'block';
                                    mobileTimer.style.visibility = 'visible';
                                    mobileTimer.textContent = data.work_time || '00:00:00';
                                }
                                
                                // Start the timer display immediately
                                if (timerInterval) {
                                    clearInterval(timerInterval);
                                }
                                // Update immediately first
                                updateTimerDisplay();
                                // Then start interval
                                timerInterval = setInterval(updateTimerDisplay, 1000);
                            } else if (data.current_status === 'completed') {
                                // Employee has checked out today
                                isWorkTimerRunning = false;
                                startTime = null;
                                checkInDateTime = null;
                                
                                if (timerInterval) {
                                    clearInterval(timerInterval);
                                    timerInterval = null;
                                }
                                
                                // Update UI
                                const startDayText = document.getElementById('startDayText');
                                const workTimer = document.getElementById('workTimer');
                                const startDayBtn = document.getElementById('startDayBtn');
                                
                                if (startDayText) startDayText.textContent = 'بدء اليوم';
                                if (workTimer) workTimer.classList.add('hidden');
                                if (startDayBtn) {
                                    startDayBtn.classList.remove('from-red-600', 'to-red-700', 'hover:from-red-700', 'hover:to-red-800');
                                    startDayBtn.classList.add('from-green-600', 'to-green-700', 'hover:from-green-700', 'hover:to-green-800');
                                }
                                
                                // Update mobile button
                                const mobileStartDayText = document.getElementById('startDayTextMobile');
                                const mobileTimer = document.getElementById('workTimerMobile');
                                if (mobileStartDayText) {
                                    mobileStartDayText.textContent = 'بدء اليوم';
                                }
                                if (mobileTimer) {
                                    mobileTimer.classList.add('hidden');
                                }
                            } else {
                                // Employee is not working
                                isWorkTimerRunning = false;
                                startTime = null;
                                checkInDateTime = null;
                                
                                if (timerInterval) {
                                    clearInterval(timerInterval);
                                    timerInterval = null;
                                }
                                
                                // Update UI
                                const startDayText = document.getElementById('startDayText');
                                const workTimer = document.getElementById('workTimer');
                                const startDayBtn = document.getElementById('startDayBtn');
                                
                                if (startDayText) startDayText.textContent = 'بدء اليوم';
                                if (workTimer) workTimer.classList.add('hidden');
                                if (startDayBtn) {
                                    startDayBtn.classList.remove('from-red-600', 'to-red-700', 'hover:from-red-700', 'hover:to-red-800');
                                    startDayBtn.classList.add('from-green-600', 'to-green-700', 'hover:from-green-700', 'hover:to-green-800');
                                }
                                
                                // Update mobile button
                                const mobileStartDayText = document.getElementById('startDayTextMobile');
                                const mobileTimer = document.getElementById('workTimerMobile');
                                if (mobileStartDayText) {
                                    mobileStartDayText.textContent = 'بدء اليوم';
                                }
                                if (mobileTimer) {
                                    mobileTimer.classList.add('hidden');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error checking attendance status:', error);
                            // If error, don't show timer
                            isWorkTimerRunning = false;
                            startTime = null;
                            checkInDateTime = null;
                        });
                    }

                    // Save timer state to localStorage
                    function saveTimerState() {
                        const state = {
                            isRunning: isWorkTimerRunning,
                            startTime: startTime ? startTime.toISOString() : null,
                            totalWorkTime: totalWorkTime
                        };
                        localStorage.setItem('workTimerState', JSON.stringify(state));
                    }

                    function toggleWorkTimer() {
                        if (!isWorkTimerRunning) {
                            // Start timer - Check in or load existing
                            checkIn();
                        } else {
                            // Stop timer - Check out
                            checkOut();
                        }
                    }
                    
                    // Check in function
                    function checkIn() {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (!csrfToken) {
                            showNotification('خطأ في التوكن، يرجى إعادة تحميل الصفحة', 'error');
                            return;
                        }

                        fetch('/attendances/check-in', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (response.status === 419) {
                                showNotification('انتهت صلاحية الجلسة، يرجى إعادة تحميل الصفحة', 'error');
                                setTimeout(() => window.location.reload(), 2000);
                                return;
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (!data) return;
                            if (data.success) {
                                // Reload timer state from server to get accurate check_in time
                                loadTimerState();
                                
                                // Show notification
                                showNotification(data.message, 'success');
                            } else {
                                // If already checked in, check current status and show timer
                                if (data.error && data.error.includes('تم تسجيل الحضور مسبقاً')) {
                                    // Load timer state to show the timer
                                    loadTimerState();
                                    // Don't show error, just show that timer is running
                                    showNotification('تم تسجيل الحضور مسبقاً - التايمر يعمل', 'info');
                                } else {
                                    showNotification(data.error || 'حدث خطأ في تسجيل الحضور', 'error');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error checking in:', error);
                            // On error, try to load timer state anyway in case attendance exists
                            loadTimerState();
                            showNotification('حدث خطأ في تسجيل الحضور. يرجى المحاولة مرة أخرى أو إعادة تحميل الصفحة', 'error');
                        });
                    }
                    
                    // Check out function
                    function checkOut() {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (!csrfToken) {
                            showNotification('خطأ في التوكن، يرجى إعادة تحميل الصفحة', 'error');
                            return;
                        }

                        fetch('/attendances/check-out', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (response.status === 419) {
                                showNotification('انتهت صلاحية الجلسة، يرجى إعادة تحميل الصفحة', 'error');
                                setTimeout(() => window.location.reload(), 2000);
                                return;
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (!data) return;
                            if (data.success) {
                                // Stop timer
                                isWorkTimerRunning = false;
                                startTime = null;
                                checkInDateTime = null;
                                
                                if (timerInterval) {
                                    clearInterval(timerInterval);
                                    timerInterval = null;
                                }
                                
                                // Update UI
                                const startDayText = document.getElementById('startDayText');
                                const workTimer = document.getElementById('workTimer');
                                const startDayBtn = document.getElementById('startDayBtn');
                                
                                if (startDayText) startDayText.textContent = 'بدء اليوم';
                                if (workTimer) workTimer.classList.add('hidden');
                                if (startDayBtn) {
                                    startDayBtn.classList.remove('from-red-600', 'to-red-700', 'hover:from-red-700', 'hover:to-red-800');
                                    startDayBtn.classList.add('from-green-600', 'to-green-700', 'hover:from-green-700', 'hover:to-green-800');
                                }
                                
                                // Update mobile button
                                const mobileStartDayText = document.getElementById('startDayTextMobile');
                                const mobileTimer = document.getElementById('workTimerMobile');
                                if (mobileStartDayText) {
                                    mobileStartDayText.textContent = 'بدء اليوم';
                                }
                                if (mobileTimer) {
                                    mobileTimer.classList.add('hidden');
                                }
                                
                                // Show notification
                                showNotification(data.message, 'success');
                                
                                // Reload page after 2 seconds to show updated data
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            } else {
                                showNotification(data.error || 'حدث خطأ في تسجيل الانصراف', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error checking out:', error);
                            showNotification('حدث خطأ في تسجيل الانصراف. يرجى المحاولة مرة أخرى أو إعادة تحميل الصفحة', 'error');
                        });
                    }

                    function startTimer() {
                        startTime = new Date();
                        isWorkTimerRunning = true;
                        
                        // Update button text and style
                        document.getElementById('startDayText').textContent = 'إيقاف التايمر';
                        document.getElementById('workTimer').classList.remove('hidden');
                        document.getElementById('startDayBtn').classList.remove('from-green-600', 'to-green-700', 'hover:from-green-700', 'hover:to-green-800');
                        document.getElementById('startDayBtn').classList.add('from-red-600', 'to-red-700', 'hover:from-red-700', 'hover:to-red-800');
                        
                        // Update mobile button
                        const mobileStartDayText = document.getElementById('startDayTextMobile');
                        const mobileTimer = document.getElementById('workTimerMobile');
                        if (mobileStartDayText) {
                            mobileStartDayText.textContent = 'إيقاف التايمر';
                        }
                        if (mobileTimer) {
                            mobileTimer.classList.remove('hidden');
                        }
                        
                        // Start the timer display
                        timerInterval = setInterval(updateTimerDisplay, 1000);
                        
                        // Save state
                        saveTimerState();
                        
                        showNotification('تم بدء التايمر بنجاح', 'success');
                    }

                    function stopTimer() {
                        if (timerInterval) {
                            clearInterval(timerInterval);
                            timerInterval = null;
                        }
                        
                        // Calculate work time
                        const endTime = new Date();
                        const sessionTime = Math.floor((endTime - startTime) / 1000);
                        totalWorkTime += sessionTime;
                        
                        isWorkTimerRunning = false;
                        
                        // Update button text and style
                        document.getElementById('startDayText').textContent = 'بدء اليوم';
                        document.getElementById('workTimer').classList.add('hidden');
                        document.getElementById('startDayBtn').classList.remove('from-red-600', 'to-red-700', 'hover:from-red-700', 'hover:to-red-800');
                        document.getElementById('startDayBtn').classList.add('from-green-600', 'to-green-700', 'hover:from-green-700', 'hover:to-green-800');
                        
                        // Update mobile button
                        const mobileStartDayText = document.getElementById('startDayTextMobile');
                        const mobileTimer = document.getElementById('workTimerMobile');
                        if (mobileStartDayText) {
                            mobileStartDayText.textContent = 'بدء اليوم';
                        }
                        if (mobileTimer) {
                            mobileTimer.classList.add('hidden');
                        }
                        
                        // Save state
                        saveTimerState();
                        
                        // Show work session summary
                        const sessionHours = Math.floor(sessionTime / 3600);
                        const sessionMinutes = Math.floor((sessionTime % 3600) / 60);
                        const sessionSeconds = sessionTime % 60;
                        
                        const totalHours = Math.floor(totalWorkTime / 3600);
                        const totalMinutes = Math.floor((totalWorkTime % 3600) / 60);
                        
                        showNotification(`جلسة العمل: ${sessionHours.toString().padStart(2, '0')}:${sessionMinutes.toString().padStart(2, '0')}:${sessionSeconds.toString().padStart(2, '0')} | إجمالي: ${totalHours}:${totalMinutes.toString().padStart(2, '0')}`, 'info');
                    }

                    function updateTimerDisplay() {
                        // Always get time from attendance API to ensure accuracy
                        fetch('/attendances/current-work-time')
                        .then(response => response.json())
                        .then(data => {
                            // Check if date has changed
                            const today = new Date().toDateString();
                            if (today !== currentDate) {
                                currentDate = today;
                                checkInDateTime = null;
                                isWorkTimerRunning = false;
                                startTime = null;
                                
                                if (timerInterval) {
                                    clearInterval(timerInterval);
                                    timerInterval = null;
                                }
                                
                                const workTimer = document.getElementById('workTimer');
                                const mobileTimer = document.getElementById('workTimerMobile');
                                if (workTimer) {
                                    workTimer.textContent = '00:00:00';
                                    workTimer.classList.add('hidden');
                                }
                                if (mobileTimer) {
                                    mobileTimer.textContent = '00:00:00';
                                    mobileTimer.classList.add('hidden');
                                }
                                return;
                            }
                            
                            if (data.is_working && data.work_time) {
                                // Update timer from server data
                                const workTimer = document.getElementById('workTimer');
                                const mobileTimer = document.getElementById('workTimerMobile');
                                
                                if (workTimer) {
                                    workTimer.textContent = data.work_time;
                                    workTimer.classList.remove('hidden');
                                    workTimer.style.display = 'block';
                                    workTimer.style.visibility = 'visible';
                                    workTimer.style.opacity = '0.9';
                                }
                                if (mobileTimer) {
                                    mobileTimer.textContent = data.work_time;
                                    mobileTimer.classList.remove('hidden');
                                    mobileTimer.style.display = 'block';
                                    mobileTimer.style.visibility = 'visible';
                                }
                                
                                // Update check-in datetime if changed
                                if (data.check_in_datetime && data.check_in_datetime !== checkInDateTime) {
                                    checkInDateTime = data.check_in_datetime;
                                    startTime = new Date(data.check_in_datetime);
                                }
                                
                                // Ensure button text shows "إيقاف التايمر"
                                const startDayText = document.getElementById('startDayText');
                                if (startDayText) {
                                    startDayText.textContent = 'إيقاف التايمر';
                                }
                                
                                // Ensure button style is red
                                const startDayBtn = document.getElementById('startDayBtn');
                                if (startDayBtn) {
                                    startDayBtn.style.background = 'linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)';
                                }
                            } else if (data.current_status === 'completed' || !data.is_working) {
                                // Timer stopped or completed
                                isWorkTimerRunning = false;
                                startTime = null;
                                checkInDateTime = null;
                                
                                if (timerInterval) {
                                    clearInterval(timerInterval);
                                    timerInterval = null;
                                }
                                
                                const workTimer = document.getElementById('workTimer');
                                const mobileTimer = document.getElementById('workTimerMobile');
                                if (workTimer) {
                                    workTimer.classList.add('hidden');
                                }
                                if (mobileTimer) {
                                    mobileTimer.classList.add('hidden');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error updating timer:', error);
                        });
                    }

                    // Keep the old function for backward compatibility
                    function startDay() {
                        toggleWorkTimer();
                    }

                    function toggleUserDropdown() {
                        const dropdown = document.getElementById('userDropdown');
                        const isHidden = dropdown.classList.contains('hidden');
                        
                        dropdown.classList.toggle('hidden');
                        
                        if (!isHidden) {
                            // Position dropdown to stay within viewport
                            positionDropdown(dropdown);
                        }
                        
                        // Close dropdown when clicking outside
                        document.addEventListener('click', function(event) {
                            if (!event.target.closest('.relative')) {
                                dropdown.classList.add('hidden');
                            }
                        });
                    }

                    function positionDropdown(dropdown) {
                        const rect = dropdown.getBoundingClientRect();
                        const viewportWidth = window.innerWidth;
                        const viewportHeight = window.innerHeight;
                        
                        // Check if dropdown goes beyond right edge
                        if (rect.right > viewportWidth) {
                            dropdown.classList.remove('left-0');
                            dropdown.classList.add('right-0');
                        } else {
                            dropdown.classList.remove('right-0');
                            dropdown.classList.add('left-0');
                        }
                        
                        // Check if dropdown goes beyond bottom edge
                        if (rect.bottom > viewportHeight) {
                            dropdown.classList.remove('top-full');
                            dropdown.classList.add('bottom-full');
                        } else {
                            dropdown.classList.remove('bottom-full');
                            dropdown.classList.add('top-full');
                        }
                    }

                    function openProfile() {
                        // Redirect to profile page
                        window.location.href = '/profile';
                    }

                    function toggleNotifications() {
                        // Fetch real notifications from API
                        fetch('{{ route("notifications.index") }}' + '?json=1', {
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json; charset=utf-8'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Create notifications dropdown
                            const dropdown = document.createElement('div');
                            dropdown.className = 'absolute top-full right-0 mt-2 w-96 bg-white rounded-2xl shadow-2xl border border-gray-200 z-50 overflow-hidden';
                            
                            let notificationsHTML = `
                                <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-4 text-white">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-bold">الإشعارات</h3>
                                        <span class="bg-white/20 backdrop-blur-sm px-2 py-1 rounded-full text-xs font-bold">${data.notifications.length}</span>
                                    </div>
                                </div>
                                <div class="max-h-96 overflow-y-auto">`;

                            if (data.notifications && data.notifications.length > 0) {
                                data.notifications.forEach(notification => {
                                    const iconColor = notification.type === 'task' ? 'from-green-500 to-emerald-600' : 
                                                     notification.type === 'project' ? 'from-blue-500 to-indigo-600' : 
                                                     notification.type === 'message' ? 'from-purple-500 to-pink-600' : 
                                                     'from-gray-500 to-gray-600';
                                    
                                    const isUnread = !notification.is_read;
                                    notificationsHTML += `
                                        <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors ${isUnread ? 'bg-blue-50 border-r-4 border-r-blue-500' : ''}">
                                            <div class="flex items-start gap-3">
                                                <div class="flex-shrink-0 relative">
                                                    <div class="h-10 w-10 bg-gradient-to-br ${iconColor} rounded-xl flex items-center justify-center shadow-lg">
                                                        ${notification.type === 'task' ? 
                                                            '<svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>' :
                                                            notification.type === 'project' ?
                                                            '<svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>' :
                                                            notification.type === 'message' ?
                                                            '<svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>' :
                                                            '<svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>'
                                                        }
                                                    </div>
                                                    ${isUnread ? '<div class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full border-2 border-white animate-pulse"></div>' : ''}
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="text-sm font-bold text-gray-900 mb-1">${notification.title}</h4>
                                                    <p class="text-xs text-gray-600 leading-relaxed">${notification.message}</p>
                                                    <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                        ${formatTime(new Date(notification.created_at))}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>`;
                                });
                            } else {
                                notificationsHTML += `
                                    <div class="text-center py-12 px-4">
                                        <div class="h-16 w-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                        </div>
                                        <p class="text-gray-600 font-medium">لا توجد إشعارات جديدة</p>
                                    </div>`;
                            }
                            
                            notificationsHTML += `</div>
                                <div class="bg-gray-50 p-4 border-t border-gray-200">
                                    <a href="{{ route('notifications.index') }}" class="block text-center text-sm text-blue-600 hover:text-blue-700 font-semibold">
                                        عرض جميع الإشعارات
                                    </a>
                                </div>`;
                            
                            dropdown.innerHTML = notificationsHTML;
                            
                            // Position dropdown
                            dropdown.style.position = 'absolute';
                            dropdown.style.top = '100%';
                            dropdown.style.right = '0';
                            dropdown.style.zIndex = '50';
                            
                            // Remove existing dropdown if any
                            const existingDropdown = document.querySelector('.notifications-dropdown');
                            if (existingDropdown) {
                                existingDropdown.remove();
                            }
                            
                            dropdown.classList.add('notifications-dropdown');
                            document.querySelector('button[onclick="toggleNotifications()"]').parentNode.appendChild(dropdown);
                            
                            // Close dropdown when clicking outside
                            setTimeout(() => {
                                document.addEventListener('click', function closeDropdown(e) {
                                    if (!dropdown.contains(e.target) && !e.target.closest('button[onclick="toggleNotifications()"]')) {
                                        dropdown.remove();
                                        document.removeEventListener('click', closeDropdown);
                                    }
                                });
                            }, 100);
                        })
                        .catch(error => {
                            console.error('Error fetching notifications:', error);
                            // Fallback to simple dropdown
                            window.location.href = '{{ route("notifications.index") }}';
                        });
                    }
                    
                    function formatTime(date) {
                        const now = new Date();
                        const diff = now - date;
                        const seconds = Math.floor(diff / 1000);
                        const minutes = Math.floor(seconds / 60);
                        const hours = Math.floor(minutes / 60);
                        const days = Math.floor(hours / 24);
                        
                        if (days > 0) return `منذ ${days} يوم`;
                        if (hours > 0) return `منذ ${hours} ساعة`;
                        if (minutes > 0) return `منذ ${minutes} دقيقة`;
                        return 'الآن';
                    }

                    function openSettings() {
                        // Create settings modal
                        const modal = document.createElement('div');
                        modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                        modal.innerHTML = `
                            <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-semibold text-gray-900">الإعدادات</h3>
                                    <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-700">الوضع الليلي</span>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-700">الإشعارات</span>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" checked class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-3 mt-6">
                                    <button onclick="this.closest('.fixed').remove()" class="px-4 py-2 text-gray-600 hover:text-gray-800">إلغاء</button>
                                    <button onclick="this.closest('.fixed').remove()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">حفظ</button>
                                </div>
                            </div>
                        `;
                        
                        document.body.appendChild(modal);
                        
                        // Close modal when clicking outside
                        modal.addEventListener('click', function(e) {
                            if (e.target === modal) {
                                modal.remove();
                            }
                        });
                    }

                    function showNotification(message, type = 'info') {
                        const notification = document.createElement('div');
                        const colors = {
                            success: 'bg-green-500',
                            error: 'bg-red-500',
                            info: 'bg-blue-500',
                            warning: 'bg-yellow-500'
                        };
                        
                        notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
                        notification.textContent = message;
                        
                        document.body.appendChild(notification);
                        
                        // Animate in
                        setTimeout(() => {
                            notification.classList.remove('translate-x-full');
                        }, 100);
                        
                        // Remove after 3 seconds
                        setTimeout(() => {
                            notification.classList.add('translate-x-full');
                            setTimeout(() => {
                                notification.remove();
                            }, 300);
                        }, 3000);
                    }
                </script>

<script>
// Update unread messages count in sidebar
function updateUnreadMessagesCount() {
    fetch('{{ route("messages.unread-count") }}', {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json; charset=utf-8'
        }
    })
        .then(response => response.json())
        .then(data => {
            const countElement = document.getElementById('unread-messages-count');
            if (data.count > 0) {
                countElement.textContent = data.count;
                countElement.classList.remove('hidden');
            } else {
                countElement.classList.add('hidden');
            }
        })
        .catch(error => console.error('Error updating unread messages count:', error));
}

// Global notification system
let lastNotificationCheck = 0;

// Update unread notifications count in sidebar and top bar, and show popup if new
function updateUnreadNotificationsCount() {
    fetch('{{ route("notifications.unread-count") }}', {
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json; charset=utf-8'
        }
    })
        .then(response => response.json())
        .then(data => {
            // تحديث عداد الإشعارات في السايدبار
            const sidebarCountElement = document.getElementById('unread-notifications-count');
            if (sidebarCountElement) {
                if (data.count > 0) {
                    sidebarCountElement.textContent = data.count;
                    sidebarCountElement.classList.remove('hidden');
                } else {
                    sidebarCountElement.classList.add('hidden');
                }
            }
            
            // تحديث عداد الإشعارات في الشريط العلوي
            const topBarCountElement = document.getElementById('top-bar-notifications-count');
            if (topBarCountElement) {
                if (data.count > 0) {
                    topBarCountElement.textContent = data.count;
                    topBarCountElement.classList.remove('hidden');
                } else {
                    topBarCountElement.classList.add('hidden');
                }
            }
            
            // إظهار إشعار منبثق للإشعارات الجديدة فقط إذا كان هناك إشعار جديد
            if (data.count > lastNotificationCheck) {
                showGlobalNotification('إشعار جديد!', 'لديك إشعار جديد في النظام');
                lastNotificationCheck = data.count;
            }
        })
        .catch(error => console.error('Error updating unread notifications count:', error));
}

function showGlobalNotification(title, message) {
    // إنشاء إشعار منبثق عام
    const popup = document.createElement('div');
    popup.className = 'fixed top-4 right-4 bg-gradient-to-r from-blue-500 to-indigo-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 max-w-sm transform transition-all duration-300 translate-x-full';
    popup.innerHTML = `
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <div class="h-8 w-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V7a7 7 0 00-14 0v5l-5 5h5m10 0v1a3 3 0 01-6 0v-1m6 0H9" />
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold">${title}</p>
                <p class="text-sm opacity-90 mt-1">${message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove();" 
                    class="text-white hover:text-gray-200 transition-colors">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(popup);
    
    // إظهار الإشعار
    setTimeout(() => {
        popup.classList.remove('translate-x-full');
    }, 100);
    
    // إزالة الإشعار بعد 5 ثوان
    setTimeout(() => {
        popup.classList.add('translate-x-full');
        setTimeout(() => {
            if (popup.parentNode) {
                popup.remove();
            }
        }, 300);
    }, 5000);
}

// Update counts on page load and every 10 seconds
updateUnreadMessagesCount();
updateUnreadNotificationsCount();
setInterval(updateUnreadMessagesCount, 10000);
setInterval(updateUnreadNotificationsCount, 10000);
</script>

@stack('scripts')
</body>
</html>