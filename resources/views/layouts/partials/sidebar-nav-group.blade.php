@props([
    'title',
    'icon' => 'M4 6h16M4 12h16M4 18h16',
    'routes' => [],
    'content' => null,
])
@php
    $groupActive = ! empty($routes) && collect($routes)->contains(fn ($r) => request()->routeIs($r));
@endphp
<div class="sidebar-nav-group" x-data="{ open: {{ $groupActive ? 'true' : 'false' }} }">
    <button type="button"
            @click="open = !open"
            class="sidebar-group-toggle flex items-center w-full px-4 py-3.5 rounded-xl transition-all duration-200 {{ $groupActive ? 'sidebar-group-toggle-active' : '' }}">
        <svg class="ml-3 h-5 w-5 flex-shrink-0 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}" />
        </svg>
        <span class="flex-1 text-right text-[15px] font-semibold leading-snug">{{ $title }}</span>
        <svg class="h-5 w-5 flex-shrink-0 transition-transform duration-200 opacity-70"
             :class="{ 'rotate-180': open }"
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="sidebar-group-body mt-1.5 space-y-1"
         @unless($groupActive) style="display: none;" @endunless>
        {!! $content ?? $slot ?? '' !!}
    </div>
</div>
