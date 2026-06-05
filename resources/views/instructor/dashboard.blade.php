@extends('layouts.app')
@section('title', 'Instruktor Dashboard')
@section('breadcrumbs')
    <span class="text-sm font-medium text-slate-900 dark:text-white">Bosh sahifa</span>
@endsection

@section('content')
<x-page-header
    title="Xush kelibsiz, {{ auth()->user()->name }}!"
    description="Bugun — {{ now()->locale('uz')->translatedFormat('d-MMMM, Y') }}" />

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <x-stat-card title="Mening o'quvchilarim" :value="$stats['my_students']" color="indigo">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
        </svg>
    </x-stat-card>

    <x-stat-card title="Bugungi darslar" :value="$stats['today_lessons']" color="green">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
    </x-stat-card>

    <x-stat-card title="Haftalik darslar" :value="$stats['week_lessons']" color="blue">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
    </x-stat-card>

    <x-stat-card title="Jami soatlar" :value="round($stats['total_hours'], 1).' soat'" color="purple">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </x-stat-card>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Bugungi darslar --}}
    <div class="bg-white dark:bg-slate-800/60 rounded-2xl
        border border-slate-100 dark:border-white/[0.06] shadow-card overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-white/[0.06]">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Bugungi darslar</h3>
            </div>
            <a href="{{ route('instructor.schedule.index') }}"
                class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 transition flex items-center gap-1">
                Jadval
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        @forelse($todayLessons as $lesson)
        <div class="table-row-hover flex items-center gap-4 px-5 py-3.5 border-b border-slate-50 dark:border-white/[0.03] last:border-0">
            <div class="flex-shrink-0 text-center w-14 bg-slate-50 dark:bg-white/[0.04] rounded-xl py-1.5">
                <p class="text-xs font-bold text-indigo-600 dark:text-indigo-400 leading-none">
                    {{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }}
                </p>
                <p class="text-[10px] text-slate-400 mt-0.5">
                    {{ \Carbon\Carbon::parse($lesson->end_time)->format('H:i') }}
                </p>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $lesson->student->user->name }}</p>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ $lesson->type === 'theory' ? 'Nazariy dars' : 'Amaliy mashg\'ulot' }}
                    @if($lesson->vehicle) · <span class="font-medium">{{ $lesson->vehicle->plate_number }}</span> @endif
                </p>
            </div>
            <x-badge :type="$lesson->status">
                {{ match($lesson->status) {
                    'scheduled'  => 'Rejalashtirilgan',
                    'completed'  => 'Tugadi',
                    'missed'     => 'Kelmagan',
                    default      => $lesson->status
                } }}
            </x-badge>
        </div>
        @empty
        <x-empty-state title="Bugun dars yo'q" description="Siz bugungi kunda bo'sh kunsiz." />
        @endforelse
    </div>

    {{-- Mening o'quvchilarim --}}
    <div class="bg-white dark:bg-slate-800/60 rounded-2xl
        border border-slate-100 dark:border-white/[0.06] shadow-card overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-white/[0.06]">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-lg bg-indigo-500/10 flex items-center justify-center">
                    <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Mening o'quvchilarim</h3>
            </div>
            <a href="{{ route('instructor.students.index') }}"
                class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 transition flex items-center gap-1">
                Barchasi
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        @forelse($myStudents as $student)
        <div class="table-row-hover flex items-center gap-3.5 px-5 py-3.5 border-b border-slate-50 dark:border-white/[0.03] last:border-0">
            <img src="{{ $student->user->avatar_url }}"
                class="w-8 h-8 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-white/10 flex-shrink-0" alt="">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ $student->user->name }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $student->student_number }}</p>
            </div>
            <div class="text-right flex-shrink-0">
                <p class="text-sm font-bold {{ $student->attendance_rate >= 80 ? 'text-emerald-500' : ($student->attendance_rate >= 60 ? 'text-amber-500' : 'text-red-500') }}">
                    {{ $student->attendance_rate }}%
                </p>
                <p class="text-[10px] text-slate-500 mt-0.5">davomat</p>
            </div>
        </div>
        @empty
        <x-empty-state title="O'quvchilar yo'q" />
        @endforelse
    </div>

</div>
@endsection
