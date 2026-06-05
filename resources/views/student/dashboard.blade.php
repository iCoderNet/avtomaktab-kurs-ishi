@extends('layouts.app')
@section('title', "O'quvchi Dashboard")
@section('breadcrumbs')
    <span class="text-sm font-medium text-slate-900 dark:text-white">Bosh sahifa</span>
@endsection

@section('content')
<x-page-header
    title="Xush kelibsiz, {{ auth()->user()->name }}!"
    description="{{ $student->student_number }} · {{ $student->license_category }} toifa · Haydovchilik maktabi" />

{{-- Qarz banneri --}}
@if($student->debt > 0)
<div class="mb-6 flex items-center gap-4 p-4 rounded-2xl
    bg-amber-50 dark:bg-amber-500/10
    border border-amber-200 dark:border-amber-500/20">
    <div class="w-9 h-9 rounded-xl bg-amber-100 dark:bg-amber-500/15 flex items-center justify-center flex-shrink-0">
        <svg class="w-4.5 h-4.5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
    </div>
    <div class="flex-1 min-w-0">
        <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">To'lov qarzi mavjud</p>
        <p class="text-xs text-amber-600 dark:text-amber-400 mt-0.5">{{ number_format($student->debt, 0, '.', ' ') }} so'm to'lanmagan</p>
    </div>
    <a href="{{ route('student.payments.index') }}"
        class="flex-shrink-0 px-4 py-2 text-sm font-semibold text-white
            bg-amber-500 hover:bg-amber-600 rounded-xl transition shadow-sm shadow-amber-500/25">
        To'lash
    </a>
</div>
@endif

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <x-stat-card title="Jami darslar" :value="$stats['total_lessons']" color="indigo">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
    </x-stat-card>

    <x-stat-card title="Davomat" :value="$stats['attendance_rate'].'%'" color="green">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </x-stat-card>

    <x-stat-card title="Imtihonlar"
        :value="$stats['exams_passed'].' / '.App\Models\ExamResult::where('student_id', $student->id)->count()"
        color="purple">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
        </svg>
    </x-stat-card>
</div>

{{-- To'lov progress --}}
<div class="mb-6 bg-white dark:bg-slate-800/60 rounded-2xl p-5
    border border-slate-100 dark:border-white/[0.06] shadow-card">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">To'lov holati</span>
        </div>
        <div class="text-right">
            <span class="text-xl font-bold {{ $stats['payment_percent'] >= 100 ? 'text-emerald-500' : ($stats['payment_percent'] >= 50 ? 'text-indigo-500' : 'text-amber-500') }}">
                {{ $stats['payment_percent'] }}%
            </span>
        </div>
    </div>
    <div class="h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
        <div class="h-full rounded-full transition-all duration-700 ease-out
            {{ $stats['payment_percent'] >= 100 ? 'bg-gradient-to-r from-emerald-400 to-emerald-500' : ($stats['payment_percent'] >= 50 ? 'bg-gradient-to-r from-indigo-400 to-indigo-500' : 'bg-gradient-to-r from-amber-400 to-amber-500') }}"
            style="width: {{ $stats['payment_percent'] }}%"></div>
    </div>
    <div class="flex justify-between mt-2.5 text-xs text-slate-500">
        <span>To'langan: <span class="font-medium text-slate-700 dark:text-slate-300">{{ number_format($student->paid_amount, 0, '.', ' ') }} so'm</span></span>
        <span>Jami: <span class="font-medium text-slate-700 dark:text-slate-300">{{ number_format($student->total_fee, 0, '.', ' ') }} so'm</span></span>
    </div>
</div>

{{-- Tables --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Kelgusi darslar --}}
    <div class="bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-white/[0.06] shadow-card overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-white/[0.06]">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-lg bg-indigo-500/10 flex items-center justify-center">
                    <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Kelgusi darslar</h3>
            </div>
            <a href="{{ route('student.lessons.index') }}"
                class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 transition flex items-center gap-1">
                Barchasi
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        @forelse($upcomingLessons as $lesson)
        <div class="table-row-hover flex items-center gap-4 px-5 py-3.5 border-b border-slate-50 dark:border-white/[0.03] last:border-0">
            <div class="text-center w-11 flex-shrink-0 bg-slate-50 dark:bg-white/[0.04] rounded-xl py-1.5">
                <p class="text-base font-bold text-slate-900 dark:text-white leading-none">{{ $lesson->date->format('d') }}</p>
                <p class="text-[10px] text-slate-500 mt-0.5 uppercase tracking-wide">{{ $lesson->date->locale('uz')->translatedFormat('MMM') }}</p>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-slate-900 dark:text-white">
                    {{ $lesson->type === 'theory' ? 'Nazariy dars' : 'Amaliy mashg\'ulot' }}
                </p>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }} · {{ $lesson->instructor->user->name }}
                </p>
            </div>
            <span class="flex-shrink-0 px-2 py-0.5 rounded-full text-[10px] font-semibold
                {{ $lesson->type === 'theory' ? 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400' : 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400' }}">
                {{ $lesson->type === 'theory' ? 'Nazariy' : 'Amaliy' }}
            </span>
        </div>
        @empty
        <x-empty-state title="Kelgusi dars yo'q" />
        @endforelse
    </div>

    {{-- So'nggi to'lovlar --}}
    <div class="bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-white/[0.06] shadow-card overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-white/[0.06]">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">To'lovlar tarixi</h3>
            </div>
            <a href="{{ route('student.payments.index') }}"
                class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 transition flex items-center gap-1">
                Barchasi
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        @forelse($recentPayments as $payment)
        <div class="table-row-hover flex items-center gap-3.5 px-5 py-3.5 border-b border-slate-50 dark:border-white/[0.03] last:border-0">
            <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $payment->type_name }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $payment->payment_date->format('d.m.Y') }} · {{ $payment->receipt_number }}</p>
            </div>
            <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400 flex-shrink-0">
                +{{ number_format($payment->amount, 0, '.', ' ') }}
            </p>
        </div>
        @empty
        <x-empty-state title="To'lovlar yo'q" />
        @endforelse
    </div>

</div>
@endsection
