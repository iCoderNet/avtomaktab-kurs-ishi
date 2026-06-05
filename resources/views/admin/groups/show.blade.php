@extends('layouts.app')

@section('title', $group->name . ' — Guruh jadvali')

@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.groups.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Guruhlar</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">{{ $group->name }}</span>
@endsection

@section('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <style>
        /* ── Core variables ── */
        .fc {
            --fc-border-color: rgba(0, 0, 0, 0.06);
            --fc-button-bg-color: #4f46e5;
            --fc-button-border-color: #4f46e5;
            --fc-button-hover-bg-color: #4338ca;
            --fc-button-hover-border-color: #4338ca;
            --fc-button-active-bg-color: #3730a3;
            --fc-today-bg-color: rgba(99, 102, 241, 0.06);
            font-size: 0.8125rem;
        }
        .dark .fc {
            --fc-border-color: rgba(255, 255, 255, 0.05);
            --fc-page-bg-color: transparent;
            --fc-neutral-bg-color: rgba(255, 255, 255, 0.02);
            --fc-neutral-text-color: #94a3b8;
            --fc-today-bg-color: rgba(99, 102, 241, 0.12);
        }

        /* ── Toolbar ── */
        .fc-toolbar.fc-header-toolbar {
            margin-bottom: 1rem !important;
            padding: 0 !important;
            gap: 0.5rem;
        }
        .fc-toolbar-title {
            font-size: 0.9375rem !important;
            font-weight: 700 !important;
            letter-spacing: -0.01em;
        }
        .dark .fc-toolbar-title { color: #f1f5f9; }

        /* ── Nav buttons (prev/next/today) ── */
        .fc-button {
            border-radius: 10px !important;
            font-size: 0.72rem !important;
            font-weight: 600 !important;
            padding: 0.35rem 0.8rem !important;
            box-shadow: none !important;
            transition: background 0.15s, transform 0.1s !important;
        }
        .fc-button:active { transform: scale(0.96) !important; }

        .fc-button-group .fc-button { border-radius: 0 !important; }
        .fc-button-group .fc-button:first-child { border-radius: 10px 0 0 10px !important; }
        .fc-button-group .fc-button:last-child  { border-radius: 0 10px 10px 0 !important; }

        /* View-switcher pill */
        .fc-dayGridMonth-button,
        .fc-timeGridWeek-button,
        .fc-listMonth-button {
            background: transparent !important;
            border-color: rgba(99,102,241,0.35) !important;
            color: #6366f1 !important;
            font-size: 0.68rem !important;
            padding: 0.28rem 0.65rem !important;
        }
        .dark .fc-dayGridMonth-button,
        .dark .fc-timeGridWeek-button,
        .dark .fc-listMonth-button {
            border-color: rgba(99,102,241,0.4) !important;
            color: #a5b4fc !important;
        }
        .fc-dayGridMonth-button.fc-button-active,
        .fc-timeGridWeek-button.fc-button-active,
        .fc-listMonth-button.fc-button-active {
            background: #4f46e5 !important;
            border-color: #4f46e5 !important;
            color: #fff !important;
        }

        /* ── Column headers ── */
        .fc-col-header-cell {
            font-weight: 700 !important;
            font-size: 0.68rem !important;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 10px 0 !important;
            background: rgba(248, 250, 252, 0.6) !important;
        }
        .dark .fc-col-header-cell {
            background: rgba(255,255,255,0.03) !important;
        }
        .dark .fc-col-header-cell-cushion { color: #94a3b8 !important; }

        /* ── Day numbers ── */
        .fc-daygrid-day-number {
            font-size: 0.78rem !important;
            font-weight: 500 !important;
            padding: 6px 8px !important;
            color: #475569 !important;
            text-decoration: none !important;
        }
        .dark .fc-daygrid-day-number { color: #94a3b8 !important; }

        .fc-day-other .fc-daygrid-day-number { opacity: 0.38 !important; }

        /* Today highlight */
        .fc-day-today .fc-daygrid-day-number {
            background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
            color: #fff !important;
            border-radius: 50% !important;
            width: 28px !important;
            height: 28px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 2px 8px rgba(99,102,241,0.45) !important;
        }

        /* ── Events ── */
        .fc-event {
            border-radius: 7px !important;
            padding: 2px 6px !important;
            font-size: 0.7rem !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            border: none !important;
            box-shadow: 0 1px 4px rgba(0,0,0,0.10) !important;
            transition: opacity 0.15s, transform 0.15s, box-shadow 0.15s !important;
        }
        .fc-event:hover {
            opacity: 0.92 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15) !important;
        }

        /* Theory events → indigo gradient */
        .fc-event[style*="#6366f1"],
        .fc-event[style*="#4f46e5"],
        .fc-event[style*="indigo"] {
            background: linear-gradient(90deg,#6366f1,#818cf8) !important;
        }
        /* Practical events → emerald gradient */
        .fc-event[style*="#10b981"],
        .fc-event[style*="#059669"],
        .fc-event[style*="emerald"] {
            background: linear-gradient(90deg,#10b981,#34d399) !important;
        }

        .fc-daygrid-event-dot { border-width: 5px !important; }

        /* ── Week/time grid ── */
        .fc-timegrid-slot {
            height: 2.5rem !important;
        }
        .fc-timegrid-slot-label {
            font-size: 0.68rem !important;
            color: #94a3b8 !important;
        }

        /* ── List view ── */
        .fc-list-event-time {
            font-size: 0.7rem !important;
            color: #94a3b8 !important;
        }
        .fc-list-day-text,
        .fc-list-day-side-text {
            font-size: 0.78rem !important;
            font-weight: 700 !important;
        }
        .dark .fc-list-event-title a,
        .dark .fc-list-day-cushion { color: #e2e8f0 !important; }
        .dark .fc-list-event:hover td { background: rgba(255,255,255,0.03) !important; }
        .dark .fc-list-day-cushion { background: rgba(255,255,255,0.03) !important; }

        /* ── Grid borders ── */
        .fc-theme-standard td,
        .fc-theme-standard th { border-color: var(--fc-border-color) !important; }
        .fc-scrollgrid { border-radius: 14px !important; overflow: hidden; }

        /* ── Popover (more events) ── */
        .fc-popover {
            border-radius: 14px !important;
            border: none !important;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12) !important;
            overflow: hidden;
        }
        .fc-popover-header {
            font-size: 0.75rem !important;
            font-weight: 700 !important;
            padding: 10px 14px !important;
            background: rgba(248,250,252,0.95) !important;
        }
        .dark .fc-popover {
            background: #1e293b !important;
            box-shadow: 0 8px 30px rgba(0,0,0,0.4) !important;
        }
        .dark .fc-popover-header {
            background: rgba(30,41,59,0.95) !important;
            color: #e2e8f0 !important;
        }

        /* ── "more" link ── */
        .fc-daygrid-more-link {
            font-size: 0.65rem !important;
            font-weight: 700 !important;
            color: #6366f1 !important;
        }
        .dark .fc-daygrid-more-link { color: #a5b4fc !important; }

        /* ── Calendar detail card animation ── */
        @keyframes slideDownFade {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .calendar-event-card {
            animation: slideDownFade 0.18s ease-out;
        }
    </style>
@endsection

@section('content')

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <x-page-header title="{{ $group->name }}" description="Dars jadvali, o'quvchilar va guruh boshqaruvi" />
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('admin.groups.edit', $group->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 hover:bg-amber-100 dark:hover:bg-amber-500/20 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Tahrirlash
            </a>
            <a href="{{ route('admin.groups.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Orqaga
            </a>
        </div>
    </div>

    {{-- Flash xabarlar --}}
    @if(session('success'))
        <div
            class="flex items-center gap-3 p-4 mb-6 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 rounded-xl text-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div
            class="flex items-center gap-3 p-4 mb-6 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-400 rounded-xl text-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Statistika kartochkalar --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- O'quvchilar --}}
        <div
            class="bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-white/[0.06] shadow-card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-500/15 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-indigo-600 dark:text-indigo-400 w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $group->students->count() }}</p>
            <p class="text-xs text-slate-500 mt-0.5">O'quvchilar</p>
        </div>

        {{-- Darslar --}}
        <div
            class="bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-white/[0.06] shadow-card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-500/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $schedulesCount }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Jadval darslari</p>
        </div>

        {{-- Tugatilgan darslar --}}
        <div
            class="bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-white/[0.06] shadow-card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-500/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $completedLessons }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Yakunlangan darslar</p>
        </div>

        {{-- O'quv muddati --}}
        <div
            class="bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-white/[0.06] shadow-card p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-violet-100 dark:bg-violet-500/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            @php
                $daysLeft = $group->end_date ? now()->diffInDays($group->end_date, false) : null;
            @endphp
            <p class="text-2xl font-bold text-slate-900 dark:text-white">
                {{ $daysLeft !== null ? max(0, $daysLeft) : '—' }}
            </p>
            <p class="text-xs text-slate-500 mt-0.5">Kun qoldi</p>
        </div>
    </div>

    {{-- Asosiy kontent: 3 ustunli grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ═══ CHAP USTUN ═══ --}}
        <div class="space-y-5 lg:col-span-1">

            {{-- Guruh ma'lumotlari --}}
            <div
                class="bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-white/[0.06] shadow-card overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-white/[0.06] flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white">Guruh ma'lumotlari</h4>
                </div>
                <div class="p-5 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Instruktor</span>
                        <span
                            class="text-sm font-medium text-slate-900 dark:text-white">{{ $group->instructor->user->name ?? '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Boshlanish</span>
                        <span
                            class="text-sm font-medium text-slate-900 dark:text-white">{{ $group->start_date ? $group->start_date->format('d.m.Y') : '—' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Tugash</span>
                        <span
                            class="text-sm font-medium text-slate-900 dark:text-white">{{ $group->end_date ? $group->end_date->format('d.m.Y') : '—' }}</span>
                    </div>
                    @if($group->start_date && $group->end_date)
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500">Jami muddat</span>
                            <span class="text-sm font-medium text-slate-900 dark:text-white">
                                {{ $group->start_date->diffInDays($group->end_date) }} kun
                            </span>
                        </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-slate-500">Holat</span>
                        @php
                            $sc = ['active' => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400', 'completed' => 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400', 'cancelled' => 'bg-slate-100 dark:bg-slate-700/40 text-slate-600 dark:text-slate-400'];
                            $sl = ['active' => 'Faol', 'completed' => 'Tugatildi', 'cancelled' => 'Bekor qilindi'];
                        @endphp
                        <span
                            class="px-2 py-0.5 text-xs font-medium rounded-lg {{ $sc[$group->status] ?? $sc['cancelled'] }}">
                            {{ $sl[$group->status] ?? $group->status }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Jadval generatsiya --}}
            <div
                class="bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-white/[0.06] shadow-card overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-white/[0.06] flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <h4 class="text-sm font-semibold text-slate-900 dark:text-white">Jadval generatsiyasi</h4>
                    </div>
                    {{-- Mavjud jadvallar quick stats --}}
                    @if($schedulesCount > 0)
                        <div class="flex items-center gap-2">
                            @if($theoryCount > 0)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                    {{ $theoryCount }}
                                </span>
                            @endif
                            @if($practicalCount > 0)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    {{ $practicalCount }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>
                <div class="p-5">
                    <div x-data="{
                        lessonType: 'theory',
                        theoryFutureCount: {{ $theoryFutureCount }},
                        practicalFutureCount: {{ $practicalFutureCount }},
                        days: [],
                        startTime: '09:00',
                        endTime: '11:00',
                        toggleDay(d) {
                            const i = this.days.indexOf(d);
                            if (i === -1) this.days.push(d);
                            else this.days.splice(i, 1);
                        },
                        hasDay(d) { return this.days.includes(d); },
                        get currentFutureCount() {
                            return this.lessonType === 'theory' ? this.theoryFutureCount : this.practicalFutureCount;
                        },
                        submit() {
                            if (this.days.length === 0) { alert('Kamida bitta kun tanlang.'); return; }
                            if (!this.startTime || !this.endTime) { alert('Vaqtni kiriting.'); return; }
                            if (this.startTime >= this.endTime) { alert('Tugash vaqti boshlanishdan keyin bo\'lishi kerak.'); return; }
                            const typeLabel = this.lessonType === 'theory' ? 'Nazariy' : 'Amaliy';
                            const dayMap = {1:'Du',2:'Se',3:'Chor',4:'Pay',5:'Ju',6:'Shan'};
                            const dayNames = this.days.map(d => dayMap[d]).join(', ');
                            let warn = '';
                            if (this.currentFutureCount > 0) {
                                warn = '\n\n⚠️ ' + this.currentFutureCount + ' ta kelajakdagi ' + typeLabel.toLowerCase() + ' jadval yangilanadi.\nO\'tgan darslar saqlanib qoladi.';
                            }
                            if (confirm(typeLabel + ' dars jadvali:\nKunlar: ' + dayNames + '\nVaqt: ' + this.startTime + ' — ' + this.endTime + warn + '\n\nDavom etasizmi?')) {
                                this.$refs.form.submit();
                            }
                        }
                    }">

                        {{-- Tur bo'yicha ogohlantirish --}}
                        <template x-if="currentFutureCount > 0">
                            <div class="flex items-start gap-2 p-3 mb-4 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-xl">
                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <p class="text-xs text-amber-700 dark:text-amber-400 leading-relaxed">
                                    <span x-text="currentFutureCount + ' ta kelajakdagi ' + (lessonType === \'theory\' ? \'nazariy\' : \'amaliy\') + \' jadval yangilanadi.'"></span>
                                    <span class="block mt-0.5 text-amber-600/80 dark:text-amber-500/70">O'tib ketgan darslar o'chirilmaydi — ular isbot sifatida saqlanib qoladi.</span>
                                </p>
                            </div>
                        </template>

                        <form x-ref="form" action="{{ route('admin.groups.generate-schedule', $group->id) }}" method="POST"
                            class="space-y-4">
                            @csrf

                            {{-- Yashirin dars turi --}}
                            <input type="hidden" name="lesson_type" :value="lessonType">

                            {{-- Yashirin hafta kunlari --}}
                            <template x-for="d in days" :key="d">
                                <input type="hidden" name="days_of_week[]" :value="d">
                            </template>

                            {{-- Yashirin vaqtlar --}}
                            <input type="hidden" name="start_time" :value="startTime">
                            <input type="hidden" name="end_time" :value="endTime">

                            {{-- Dars turi --}}
                            <div>
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2">Dars turi</p>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" @click="lessonType = 'theory'"
                                        :class="lessonType === 'theory'
                                                ? 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300 dark:border-indigo-400'
                                                : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700'"
                                        class="flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl border-2 text-sm font-medium transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                        Nazariy
                                        <template x-if="theoryFutureCount > 0 && lessonType !== 'theory'">
                                            <span class="ml-0.5 text-xs text-slate-400" x-text="'(' + theoryFutureCount + ')'"></span>
                                        </template>
                                    </button>
                                    <button type="button" @click="lessonType = 'practical'"
                                        :class="lessonType === 'practical'
                                                ? 'border-emerald-500 bg-emerald-50 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300 dark:border-emerald-400'
                                                : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700'"
                                        class="flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl border-2 text-sm font-medium transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l-1 1h14l-1-1z" />
                                        </svg>
                                        Amaliy
                                        <template x-if="practicalFutureCount > 0 && lessonType !== 'practical'">
                                            <span class="ml-0.5 text-xs text-slate-400" x-text="'(' + practicalFutureCount + ')'"></span>
                                        </template>
                                    </button>
                                </div>
                            </div>

                            {{-- Hafta kunlari --}}
                            <div>
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2">
                                    Dars kunlari
                                    <span class="ml-1 font-semibold text-indigo-600 dark:text-indigo-400"
                                        x-text="days.length > 0 ? '(' + days.length + ' kun)' : ''"></span>
                                </p>
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach([1 => 'Du', 2 => 'Se', 3 => 'Chor', 4 => 'Pay', 5 => 'Ju', 6 => 'Shan'] as $val => $day)
                                        <button type="button" @click="toggleDay({{ $val }})"
                                            :class="hasDay({{ $val }})
                                                    ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm'
                                                    : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700'"
                                            class="py-2 text-xs font-bold rounded-xl border-2 transition-all">
                                            {{ $day }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Vaqt --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5">Boshlanish</label>
                                    <input type="time" x-model="startTime"
                                        class="w-full px-3 py-2 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 dark:text-slate-400 mb-1.5">Tugash</label>
                                    <input type="time" x-model="endTime"
                                        class="w-full px-3 py-2 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                </div>
                            </div>

                            {{-- Generatsiya tugmasi --}}
                            <button type="button" @click="submit()"
                                :class="lessonType === 'theory'
                                    ? 'bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700'
                                    : 'bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-700'"
                                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white rounded-xl transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <span x-text="(lessonType === 'theory' ? 'Nazariy' : 'Amaliy') + ' jadval generatsiya'"></span>
                            </button>
                            <p class="text-xs text-center text-slate-400">Barcha o'quvchilar uchun dars yozuvlari ham yaratiladi</p>
                        </form>
                    </div>
                </div>
            </div>

            {{-- O'quvchilar ro'yxati --}}
            <div
                class="bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-white/[0.06] shadow-card overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-white/[0.06] flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <h4 class="text-sm font-semibold text-slate-900 dark:text-white">O'quvchilar</h4>
                        <span
                            class="px-2 py-0.5 text-xs font-semibold rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400">{{ $group->students->count() }}</span>
                    </div>
                    {{-- O'quvchi qo'shish trigger --}}
                    <button onclick="document.getElementById('addStudentPanel').classList.toggle('hidden')"
                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-indigo-700 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 rounded-lg transition">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Qo'shish
                    </button>
                </div>

                {{-- O'quvchi qo'shish paneli --}}
                <div id="addStudentPanel" class="hidden px-5 pt-4 pb-0">
                    @if($availableStudents->isEmpty())
                        <p class="text-xs text-slate-400 text-center py-3">Guruhga qo'shish uchun mavjud o'quvchi yo'q.</p>
                    @else
                        <form action="{{ route('admin.groups.add-student', $group->id) }}" method="POST" class="flex gap-2">
                            @csrf
                            <select name="student_id"
                                class="flex-1 min-w-0 px-3 py-2 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                <option value="">— Tanlang —</option>
                                @foreach($availableStudents as $s)
                                    <option value="{{ $s->id }}">{{ $s->user->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit"
                                class="px-3 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl transition flex-shrink-0">
                                Qo'shish
                            </button>
                        </form>
                    @endif
                    <div class="my-4 border-t border-slate-100 dark:border-white/[0.06]"></div>
                </div>

                {{-- O'quvchilar ro'yxati --}}
                <div class="divide-y divide-slate-100 dark:divide-white/[0.04] max-h-80 overflow-y-auto">
                    @forelse($group->students as $student)
                        <div
                            class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors group">
                            <div
                                class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white font-semibold text-xs flex-shrink-0">
                                {{ substr($student->user->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 dark:text-white truncate">
                                    {{ $student->user->name }}</p>
                                <p class="text-xs text-slate-400">{{ $student->license_category }}-toifa</p>
                            </div>
                            {{-- Chiqarish --}}
                            <form action="{{ route('admin.groups.remove-student', [$group->id, $student->id]) }}" method="POST"
                                onsubmit="return confirm('{{ addslashes($student->user->name) }} ni guruhdan chiqarasizmi?')"
                                class="opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Guruhdan chiqarish"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center">
                            <p class="text-sm text-slate-400">Guruhda o'quvchi yo'q</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ═══ O'NG USTUN: KALENDAR ═══ --}}
        <div class="lg:col-span-2"
             x-data="{
                 selectedEvent: null,
                 showEvent(data) { this.selectedEvent = data; },
                 closeEvent() { this.selectedEvent = null; }
             }"
             x-on:show-calendar-event.window="showEvent($event.detail)">

            <div class="bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-white/[0.06] shadow-card overflow-hidden h-full flex flex-col">

                {{-- Kalendar header --}}
                <div class="px-5 py-4 border-b border-slate-100 dark:border-white/[0.06] flex items-center justify-between flex-shrink-0 gap-3 flex-wrap">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-sm flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white leading-tight">Dars jadvali</h4>
                            @if($schedulesCount > 0)
                                <p class="text-xs text-slate-400 leading-tight">{{ $schedulesCount }} ta dars rejalashtirilgan</p>
                            @else
                                <p class="text-xs text-slate-400 leading-tight">Jadval hali yaratilmagan</p>
                            @endif
                        </div>
                    </div>
                    {{-- Legenda --}}
                    <div class="flex items-center gap-3">
                        @if($theoryCount > 0 || $schedulesCount === 0)
                            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-500/10">
                                <span class="w-2 h-2 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 flex-shrink-0"></span>
                                <span class="text-xs font-medium text-indigo-700 dark:text-indigo-400">Nazariy</span>
                                @if($theoryCount > 0)
                                    <span class="text-xs font-bold text-indigo-600 dark:text-indigo-300">{{ $theoryCount }}</span>
                                @endif
                            </div>
                        @endif
                        @if($practicalCount > 0 || $schedulesCount === 0)
                            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-500/10">
                                <span class="w-2 h-2 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex-shrink-0"></span>
                                <span class="text-xs font-medium text-emerald-700 dark:text-emerald-400">Amaliy</span>
                                @if($practicalCount > 0)
                                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-300">{{ $practicalCount }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Tanlangan event detail panel --}}
                <div x-show="selectedEvent"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2 scale-99"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 -translate-y-2 scale-99"
                     class="border-b border-slate-100 dark:border-white/[0.06] flex-shrink-0">
                    <template x-if="selectedEvent">
                        <div class="calendar-event-card mx-4 my-3">
                            <div :class="selectedEvent.type === 'theory'
                                    ? 'bg-gradient-to-r from-indigo-50 to-violet-50 dark:from-indigo-500/10 dark:to-violet-500/10 border-indigo-200 dark:border-indigo-500/25'
                                    : 'bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-500/10 dark:to-teal-500/10 border-emerald-200 dark:border-emerald-500/25'"
                                 class="rounded-xl border px-4 py-3 flex items-center gap-4">
                                {{-- Icon badge --}}
                                <div :class="selectedEvent.type === 'theory'
                                        ? 'bg-gradient-to-br from-indigo-500 to-violet-600'
                                        : 'bg-gradient-to-br from-emerald-500 to-teal-600'"
                                     class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <template x-if="selectedEvent.type === 'theory'">
                                        <svg class="w-4.5 h-4.5 text-white w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                        </svg>
                                    </template>
                                    <template x-if="selectedEvent.type !== 'theory'">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l-1 1h14l-1-1z"/>
                                        </svg>
                                    </template>
                                </div>
                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <p :class="selectedEvent.type === 'theory' ? 'text-indigo-700 dark:text-indigo-300' : 'text-emerald-700 dark:text-emerald-300'"
                                       class="text-sm font-bold leading-tight" x-text="selectedEvent.typeLabel + ' dars'"></p>
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1.5">
                                        <span class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <span x-text="selectedEvent.date"></span>
                                        </span>
                                        <span class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span x-text="selectedEvent.startTime + ' — ' + selectedEvent.endTime"></span>
                                        </span>
                                        <span class="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                            </svg>
                                            {{ $group->students->count() }} o'quvchi
                                        </span>
                                    </div>
                                </div>
                                {{-- Close --}}
                                <button @click="closeEvent()"
                                        class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-white/60 dark:hover:bg-white/10 transition flex-shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Kalendar body --}}
                <div class="p-4 flex-1">
                    @if($schedulesCount === 0)
                        <div class="flex flex-col items-center justify-center gap-4 py-20 text-center">
                            <div class="relative">
                                <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-50 dark:from-slate-700/60 dark:to-slate-800/40 flex items-center justify-center shadow-inner">
                                    <svg class="w-9 h-9 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="absolute -top-1 -right-1 w-6 h-6 rounded-full bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">Dars jadvali yaratilmagan</p>
                                <p class="text-xs text-slate-400 mt-1.5 max-w-xs leading-relaxed">Chap paneldagi <span class="font-semibold text-indigo-600 dark:text-indigo-400">"Jadval generatsiyasi"</span> orqali nazariy va amaliy darslarni alohida yarating</p>
                            </div>
                        </div>
                    @else
                        <div id="calendar"></div>
                    @endif
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    @if($schedulesCount > 0)
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/uz.js'></script>
    @endif
    <script>
        @if($schedulesCount > 0)
            document.addEventListener('DOMContentLoaded', function () {
                const calendarEl = document.getElementById('calendar');
                if (!calendarEl) return;

                const isDark = document.documentElement.classList.contains('dark');

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'uz',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listMonth'
                    },
                    buttonText: {
                        today: 'Bugun',
                        month: 'Oy',
                        week:  'Hafta',
                        list:  'Ro\'yxat',
                    },
                    events: '{{ route("admin.groups.schedule-events", $group->id) }}',
                    height: 'auto',
                    aspectRatio: 1.65,
                    eventTimeFormat: { hour: '2-digit', minute: '2-digit', meridiem: false },
                    dayMaxEvents: 3,
                    moreLinkContent: function(args) {
                        return '+' + args.num + ' ta';
                    },
                    eventDidMount: function(info) {
                        const type = info.event.extendedProps.type;
                        // Apply gradient colors via inline style
                        if (type === 'theory') {
                            info.el.style.background = 'linear-gradient(90deg, #6366f1, #818cf8)';
                        } else {
                            info.el.style.background = 'linear-gradient(90deg, #10b981, #34d399)';
                        }
                        info.el.style.border = 'none';
                        info.el.style.borderRadius = '7px';
                    },
                    eventClick: function (info) {
                        const p = info.event.extendedProps;
                        const startStr = info.event.startStr || '';
                        const endStr   = info.event.endStr   || '';

                        // Format date to dd.mm.yyyy
                        const rawDate = startStr.substring(0, 10);
                        const parts   = rawDate.split('-');
                        const date    = parts.length === 3 ? parts[2] + '.' + parts[1] + '.' + parts[0] : rawDate;

                        window.dispatchEvent(new CustomEvent('show-calendar-event', {
                            detail: {
                                type:      p.type,
                                typeLabel: p.type === 'theory' ? 'Nazariy' : 'Amaliy',
                                date:      date,
                                startTime: startStr.substring(11, 16),
                                endTime:   endStr.substring(11, 16),
                            }
                        }));
                    },
                    initialDate: '{{ now()->format("Y-m-d") }}',
                    // Smooth scroll to today on render
                    viewDidMount: function() {
                        setTimeout(() => {
                            const todayEl = calendarEl.querySelector('.fc-day-today');
                            if (todayEl) todayEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }, 100);
                    }
                });

                calendar.render();
            });
        @endif
    </script>
@endpush