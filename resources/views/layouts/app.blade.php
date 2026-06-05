<!DOCTYPE html>
<html lang="uz"
    x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }"
    :class="{ 'dark': darkMode }"
    x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Avtomaktab') — Boshqaruv Tizimi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 dark:bg-[#0a0e1a] text-slate-900 dark:text-slate-100 font-sans antialiased">

    <!-- Mobile overlay -->
    <div x-show="sidebarOpen" x-cloak
        @click="sidebarOpen = false"
        x-transition:enter="transition-opacity ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-in duration-150"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-20 bg-black/60 backdrop-blur-sm lg:hidden"></div>

    {{-- ═══════════════════════════  SIDEBAR  ═══════════════════════════ --}}
    <aside
        class="fixed top-0 left-0 h-full w-[240px] z-30 transform transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0"
        :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">

        <div class="flex flex-col h-full sidebar-bg border-r border-white/[0.06]">

            {{-- Logo ──────────────────────────────────────────── --}}
            <div class="flex items-center gap-3 px-5 h-16 border-b border-white/[0.06] flex-shrink-0">
                <div class="relative flex-shrink-0">
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center shadow-brand">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l-1 1h14l-1-1z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 6l2 3h5l-1 7H13"/>
                        </svg>
                    </div>
                    <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-emerald-400 rounded-full animate-pulse-dot border border-[#060b14]"></span>
                </div>
                <div class="min-w-0">
                    <p class="text-white font-bold text-sm tracking-tight leading-none">Avtomaktab</p>
                    <p class="text-[11px] text-white/35 mt-0.5 tracking-wide">Boshqaruv tizimi</p>
                </div>
                <button @click="sidebarOpen = false"
                    class="ml-auto lg:hidden w-7 h-7 flex items-center justify-center rounded-lg text-white/40 hover:text-white hover:bg-white/08 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Navigation ─────────────────────────────────────── --}}
            <nav class="flex-1 overflow-y-auto scrollbar-thin py-4 px-3 space-y-0.5">
                @include('layouts.partials.sidebar-nav')
            </nav>

            {{-- User Profile ────────────────────────────────────── --}}
            <div class="flex-shrink-0 px-3 py-3 border-t border-white/[0.06]">
                <div class="flex items-center gap-2.5 px-2.5 py-2 rounded-xl hover:bg-white/[0.05] transition group cursor-pointer">
                    <div class="relative flex-shrink-0">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                            class="w-8 h-8 rounded-lg object-cover ring-1 ring-white/10">
                        <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-[#0b1120]"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white/90 truncate leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-white/35 mt-0.5 truncate">
                            {{ auth()->user()->roles->first()?->name ?? 'Foydalanuvchi' }}
                        </p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Chiqish"
                            class="w-7 h-7 flex items-center justify-center rounded-lg text-white/25 hover:text-red-400 hover:bg-red-500/10 transition opacity-0 group-hover:opacity-100">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </aside>

    {{-- ═══════════════════════════  MAIN  ═══════════════════════════════ --}}
    <div class="lg:pl-[240px] min-h-screen flex flex-col">

        {{-- Topbar ──────────────────────────────────────────────────────── --}}
        <header class="sticky top-0 z-10 h-16 flex items-center
            bg-white/90 dark:bg-slate-900/90
            backdrop-blur-xl
            border-b border-slate-200/80 dark:border-white/[0.06]">
            <div class="flex items-center gap-3 px-5 w-full">

                {{-- Mobile hamburger --}}
                <button @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden w-9 h-9 flex items-center justify-center rounded-xl
                        text-slate-500 dark:text-slate-400
                        hover:bg-slate-100 dark:hover:bg-white/08 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Breadcrumbs --}}
                <nav class="flex-1 hidden sm:flex items-center gap-1.5 text-sm">
                    <span class="text-slate-400 dark:text-slate-500 text-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </span>
                    <span class="text-slate-300 dark:text-slate-600">/</span>
                    @yield('breadcrumbs')
                </nav>

                {{-- Right actions --}}
                <div class="flex items-center gap-1 ml-auto">

                    {{-- Notifications --}}
                    <a href="{{ route('notifications.index') }}"
                        class="relative w-9 h-9 flex items-center justify-center rounded-xl
                            text-slate-500 dark:text-slate-400
                            hover:bg-slate-100 dark:hover:bg-white/08
                            transition">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        @php $unread = auth()->user()->unreadNotifications()->count(); @endphp
                        @if($unread > 0)
                        <span class="absolute top-1.5 right-1.5 w-[18px] h-[18px] bg-emerald-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center leading-none">
                            {{ $unread > 9 ? '9+' : $unread }}
                        </span>
                        @endif
                    </a>

                    {{-- Dark mode toggle --}}
                    <button @click="darkMode = !darkMode"
                        class="w-9 h-9 flex items-center justify-center rounded-xl
                            text-slate-500 dark:text-slate-400
                            hover:bg-slate-100 dark:hover:bg-white/08
                            transition">
                        <svg x-show="!darkMode" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="darkMode" x-cloak class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>

                    {{-- Divider --}}
                    <div class="w-px h-5 bg-slate-200 dark:bg-white/08 mx-1"></div>

                    {{-- User avatar --}}
                    <div class="flex items-center gap-2 pl-1">
                        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}"
                            class="w-7 h-7 rounded-lg object-cover ring-1 ring-slate-200 dark:ring-white/10">
                        <span class="hidden sm:block text-sm font-medium text-slate-700 dark:text-slate-300 max-w-[120px] truncate">
                            {{ auth()->user()->name }}
                        </span>
                    </div>

                </div>
            </div>
        </header>

        {{-- Page Content ────────────────────────────────────────────────── --}}
        <main class="flex-1 p-5 sm:p-6 page-content">

            {{-- Toast: success --}}
            @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-end="opacity-0 scale-95"
                class="fixed top-5 right-5 z-50 toast-glass flex items-center gap-3 pl-4 pr-3 py-3
                    bg-emerald-500 text-white rounded-xl shadow-lg shadow-emerald-500/30 max-w-sm">
                <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <p class="text-sm font-medium flex-1">{{ session('success') }}</p>
                <button @click="show = false" class="w-6 h-6 flex items-center justify-center rounded-lg bg-white/10 hover:bg-white/20 text-white/80 transition text-lg leading-none">×</button>
            </div>
            @endif

            {{-- Toast: error --}}
            @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-end="opacity-0 scale-95"
                class="fixed top-5 right-5 z-50 toast-glass flex items-center gap-3 pl-4 pr-3 py-3
                    bg-red-500 text-white rounded-xl shadow-lg shadow-red-500/30 max-w-sm">
                <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <p class="text-sm font-medium flex-1">{{ session('error') }}</p>
                <button @click="show = false" class="w-6 h-6 flex items-center justify-center rounded-lg bg-white/10 hover:bg-white/20 text-white/80 transition text-lg leading-none">×</button>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
