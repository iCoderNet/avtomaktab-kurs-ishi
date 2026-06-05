@extends('layouts.app')
@section('title', 'Dars Jadvali')
@section('breadcrumbs')
    <a href="{{ route('instructor.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2 text-slate-300 dark:text-slate-600">/</span>
    <span class="text-sm font-medium text-slate-900 dark:text-white">Dars jadvali</span>
@endsection

@section('content')
{{-- Header + navigation --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
    <x-page-header title="Haftalik Dars Jadvali"
        description="{{ $week->format('d') }} – {{ $week->copy()->endOfWeek()->translatedFormat('d MMMM, Y') }}" />

    <div class="flex items-center gap-2 flex-shrink-0">
        <a href="{{ route('instructor.schedule.index', ['week' => $week->copy()->subWeek()->format('Y-m-d')]) }}"
            class="w-9 h-9 flex items-center justify-center rounded-xl
                bg-white dark:bg-slate-800/60
                border border-slate-200 dark:border-white/[0.08]
                text-slate-600 dark:text-slate-400
                hover:bg-slate-50 dark:hover:bg-white/[0.08] transition shadow-card">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>

        <a href="{{ route('instructor.schedule.index') }}"
            class="px-3 h-9 flex items-center text-xs font-semibold rounded-xl
                bg-white dark:bg-slate-800/60
                border border-slate-200 dark:border-white/[0.08]
                text-slate-600 dark:text-slate-400
                hover:bg-slate-50 dark:hover:bg-white/[0.08] transition shadow-card">
            Bu hafta
        </a>

        <a href="{{ route('instructor.schedule.index', ['week' => $week->copy()->addWeek()->format('Y-m-d')]) }}"
            class="w-9 h-9 flex items-center justify-center rounded-xl
                bg-white dark:bg-slate-800/60
                border border-slate-200 dark:border-white/[0.08]
                text-slate-600 dark:text-slate-400
                hover:bg-slate-50 dark:hover:bg-white/[0.08] transition shadow-card">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</div>

{{-- Jami darslar badge --}}
@php
    $totalLessons = collect($lessons)->flatten()->count();
    $completedLessons = collect($lessons)->flatten()->where('status', 'completed')->count();
@endphp
@if($totalLessons > 0)
<div class="flex items-center gap-3 mb-4 px-1">
    <span class="text-xs text-slate-500">Bu hafta:</span>
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-xs font-semibold">
        {{ $totalLessons }} dars
    </span>
    @if($completedLessons > 0)
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-xs font-semibold">
        {{ $completedLessons }} bajarildi
    </span>
    @endif
</div>
@endif

{{-- Kunlar --}}
@php $days = ['Dushanba','Seshanba','Chorshanba','Payshanba','Juma','Shanba','Yakshanba']; @endphp

<div class="space-y-3">
    @for($i = 0; $i < 7; $i++)
    @php
        $day = $week->copy()->addDays($i);
        $dayKey = $day->format('Y-m-d');
        $dayLessons = $lessons[$dayKey] ?? collect();
        $isToday = $day->isToday();
        $isPast  = $day->isPast() && !$isToday;
    @endphp

    <div class="bg-white dark:bg-slate-800/60 rounded-2xl overflow-hidden
        border shadow-card
        {{ $isToday
            ? 'border-indigo-300 dark:border-indigo-500/40 ring-1 ring-indigo-200 dark:ring-indigo-500/20'
            : 'border-slate-100 dark:border-white/[0.06]' }}">

        {{-- Kun sarlavhasi --}}
        <div class="flex items-center justify-between px-5 py-3
            {{ $isToday ? 'bg-indigo-50 dark:bg-indigo-500/10' : '' }}">
            <div class="flex items-center gap-2.5">
                <div class="text-center w-9 h-9 rounded-xl flex flex-col items-center justify-center
                    {{ $isToday ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-white/[0.05] text-slate-600 dark:text-slate-400' }}">
                    <span class="text-sm font-bold leading-none">{{ $day->format('d') }}</span>
                    <span class="text-[9px] uppercase tracking-wide opacity-75 leading-none mt-0.5">{{ $day->format('M') }}</span>
                </div>
                <div>
                    <span class="text-sm font-semibold {{ $isToday ? 'text-indigo-700 dark:text-indigo-300' : 'text-slate-700 dark:text-slate-300' }}">
                        {{ $days[$i] }}
                    </span>
                    @if($isToday)
                    <span class="ml-2 text-[10px] font-bold bg-indigo-600 text-white px-2 py-0.5 rounded-full">BUGUN</span>
                    @endif
                </div>
            </div>
            <span class="text-xs font-medium {{ $dayLessons->count() > 0 ? 'text-slate-700 dark:text-slate-300' : 'text-slate-400' }}">
                @if($dayLessons->count() > 0)
                    {{ $dayLessons->count() }} dars
                @else
                    Bo'sh kun
                @endif
            </span>
        </div>

        {{-- Darslar --}}
        @if($dayLessons->count() > 0)
        <div class="divide-y divide-slate-50 dark:divide-white/[0.03]">
            @foreach($dayLessons as $lesson)
            <div class="table-row-hover flex items-center gap-4 px-5 py-3.5">
                {{-- Vaqt --}}
                <div class="flex-shrink-0 text-center w-14">
                    <p class="text-xs font-bold text-indigo-600 dark:text-indigo-400 leading-none">
                        {{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }}
                    </p>
                    <p class="text-[10px] text-slate-400 mt-0.5">
                        {{ \Carbon\Carbon::parse($lesson->end_time)->format('H:i') }}
                    </p>
                </div>

                {{-- O'quvchi --}}
                <img src="{{ $lesson->student->user->avatar_url }}"
                    class="w-8 h-8 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-white/10 flex-shrink-0" alt="">

                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-900 dark:text-white truncate">
                        {{ $lesson->student->user->name }}
                    </p>
                    <p class="text-xs text-slate-500 mt-0.5">
                        {{ $lesson->type === 'theory' ? 'Nazariy dars' : 'Amaliy mashg\'ulot' }}
                        @if($lesson->vehicle) · <span class="font-medium">{{ $lesson->vehicle->plate_number }}</span> @endif
                    </p>
                </div>

                <x-badge :type="match($lesson->status) {
                    'completed' => 'success',
                    'missed'    => 'danger',
                    'cancelled' => 'inactive',
                    default     => 'info'
                }">
                    {{ match($lesson->status) {
                        'completed' => 'Tugadi',
                        'missed'    => 'Kelmadi',
                        'cancelled' => 'Bekor',
                        default     => 'Kutmoqda'
                    } }}
                </x-badge>
            </div>
            @endforeach
        </div>
        @else
        <div class="px-5 py-3 flex items-center gap-2 text-sm text-slate-400 dark:text-slate-600 {{ $isPast ? 'opacity-60' : '' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            {{ $isPast ? 'Dars bo\'lmagan' : 'Dars rejalanmagan' }}
        </div>
        @endif
    </div>
    @endfor
</div>

@if($totalLessons === 0)
<div class="mt-4 flex flex-col items-center justify-center py-12 text-center
    bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-white/[0.06] shadow-card">
    <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-white/[0.04] flex items-center justify-center mb-4">
        <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
    </div>
    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">Bu haftada darslar yo'q</p>
    <p class="text-xs text-slate-400 mt-1 max-w-xs">
        Admin sizga o'quvchi tayinlasa, darslar bu yerda ko'rinadi
    </p>
</div>
@endif

@endsection
