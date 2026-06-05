@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('breadcrumbs')
    <span class="text-sm font-medium text-slate-900 dark:text-white">Bosh sahifa</span>
@endsection

@section('content')
<x-page-header title="Admin Dashboard" description="Umumiy ko'rinish va statistika" />

{{-- Stats Grid --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <x-stat-card title="Jami o'quvchilar" :value="$stats['total_students']" color="indigo"
        :change="'Faol: '.$stats['active_students']" trend="up">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
    </x-stat-card>

    <x-stat-card title="Instruktorlar" :value="$stats['total_instructors']" color="green">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                d="M12 14l9-5-9-5-9 5 9 5z"/>
        </svg>
    </x-stat-card>

    <x-stat-card title="Oylik daromad"
        :value="number_format($stats['monthly_revenue'], 0, '.', ' ').' so\'m'"
        color="amber">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </x-stat-card>

    <x-stat-card title="Umumiy qarz"
        :value="number_format($stats['total_debt'], 0, '.', ' ').' so\'m'"
        color="red"
        :change="'Avtomobillar: '.$stats['available_vehicles'].'/'.$stats['total_vehicles']">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
    </x-stat-card>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

    {{-- Daromad grafigi --}}
    <div class="bg-white dark:bg-slate-800/60 rounded-2xl p-5
        border border-slate-100 dark:border-white/[0.06] shadow-card">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Daromad grafigi</h3>
                <p class="text-xs text-slate-500 mt-0.5">So'nggi 6 oy</p>
            </div>
            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-500/10">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                <span class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">So'm</span>
            </div>
        </div>
        <div style="position:relative; height:220px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- O'quvchilar grafigi --}}
    <div class="bg-white dark:bg-slate-800/60 rounded-2xl p-5
        border border-slate-100 dark:border-white/[0.06] shadow-card">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">O'quvchilar dinamikasi</h3>
                <p class="text-xs text-slate-500 mt-0.5">Oylar kesimida</p>
            </div>
            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-500/10">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">O'quvchi</span>
            </div>
        </div>
        <div style="position:relative; height:220px;">
            <canvas id="studentChart"></canvas>
        </div>
    </div>
</div>

{{-- Tables Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Yangi o'quvchilar --}}
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
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">So'nggi o'quvchilar</h3>
            </div>
            <a href="{{ route('admin.students.index') }}"
                class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 transition flex items-center gap-1">
                Barchasi
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        @forelse ($recentStudents as $student)
        <div class="table-row-hover flex items-center gap-3.5 px-5 py-3.5 border-b border-slate-50 dark:border-white/[0.03] last:border-0">
            <img src="{{ $student->user->avatar_url }}"
                class="w-8 h-8 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-white/10 flex-shrink-0" alt="">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ $student->user->name }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $student->student_number }} · {{ $student->license_category }} toifa</p>
            </div>
            <x-badge :type="$student->status">
                {{ match($student->status) { 'active' => 'Faol', 'completed' => 'Tugatdi', 'inactive' => 'Nofaol', default => $student->status } }}
            </x-badge>
        </div>
        @empty
        <x-empty-state title="O'quvchilar yo'q" />
        @endforelse
    </div>

    {{-- Qarz bo'lgan o'quvchilar --}}
    <div class="bg-white dark:bg-slate-800/60 rounded-2xl
        border border-slate-100 dark:border-white/[0.06] shadow-card overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-white/[0.06]">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-lg bg-red-500/10 flex items-center justify-center">
                    <svg class="w-3 h-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Qarzli o'quvchilar</h3>
            </div>
            <a href="{{ route('admin.payments.index') }}"
                class="text-xs font-medium text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 transition flex items-center gap-1">
                To'lovlar
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
        @forelse ($debtStudents as $student)
        <div class="table-row-hover flex items-center gap-3.5 px-5 py-3.5 border-b border-slate-50 dark:border-white/[0.03] last:border-0">
            <img src="{{ $student->user->avatar_url }}"
                class="w-8 h-8 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-white/10 flex-shrink-0" alt="">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ $student->user->name }}</p>
                <div class="mt-1.5 h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden w-24">
                    <div class="h-full bg-gradient-to-r from-indigo-400 to-indigo-500 rounded-full"
                        style="width: {{ $student->payment_percentage }}%"></div>
                </div>
            </div>
            <div class="text-right flex-shrink-0">
                <p class="text-sm font-bold text-red-500">{{ number_format($student->debt, 0, '.', ' ') }}</p>
                <p class="text-[10px] text-slate-500 mt-0.5">so'm</p>
            </div>
        </div>
        @empty
        <div class="flex items-center gap-2.5 px-5 py-4 text-sm text-emerald-600 dark:text-emerald-400">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Barcha to'lovlar to'liq
        </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark     = document.documentElement.classList.contains('dark');
    const gridColor  = isDark ? 'rgba(255,255,255,0.04)' : 'rgba(0,0,0,0.05)';
    const textColor  = isDark ? '#6b7f96' : '#94a3b8';
    const borderColor = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.07)';
    const tooltipBg  = isDark ? '#0f1929' : '#ffffff';

    Chart.defaults.font.family = 'Inter, sans-serif';

    // ── Daromad grafigi ──────────────────────────────────────────
    new Chart(document.getElementById('revenueChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: @json($revenueLabels),
            datasets: [{
                label: "Daromad",
                data: @json($revenueData),
                backgroundColor: isDark ? 'rgba(99,102,241,0.7)' : 'rgba(99,102,241,0.8)',
                hoverBackgroundColor: '#6366f1',
                borderRadius: 7,
                borderSkipped: false,
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: tooltipBg,
                    titleColor: isDark ? '#e2e8f0' : '#0f172a',
                    bodyColor: textColor,
                    borderColor: borderColor,
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: ctx => '  ' + new Intl.NumberFormat('uz-UZ').format(ctx.raw) + " so'm"
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: gridColor, drawBorder: false },
                    border: { dash: [4, 4], display: false },
                    ticks: {
                        color: textColor, font: { size: 11 },
                        callback: v => v >= 1_000_000 ? (v/1_000_000).toFixed(1)+'M' : v >= 1_000 ? (v/1_000).toFixed(0)+'K' : v
                    }
                },
                x: { grid: { display: false }, border: { display: false }, ticks: { color: textColor, font: { size: 11 } } }
            }
        }
    });

    // ── O'quvchilar grafigi ──────────────────────────────────────
    new Chart(document.getElementById('studentChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: @json($studentLabels),
            datasets: [{
                label: "O'quvchilar",
                data: @json($studentData),
                borderColor: '#10b981',
                borderWidth: 2.5,
                backgroundColor: isDark ? 'rgba(16,185,129,0.08)' : 'rgba(16,185,129,0.10)',
                fill: true,
                tension: 0.42,
                pointBackgroundColor: '#10b981',
                pointBorderColor: isDark ? '#1e293b' : '#ffffff',
                pointBorderWidth: 2.5,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: tooltipBg,
                    titleColor: isDark ? '#e2e8f0' : '#0f172a',
                    bodyColor: textColor,
                    borderColor: borderColor,
                    borderWidth: 1,
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: ctx => "  Jami: " + ctx.raw + " ta o'quvchi"
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: Math.max(...@json($studentData)) + 3,
                    grid: { color: gridColor, drawBorder: false },
                    border: { dash: [4, 4], display: false },
                    ticks: {
                        color: textColor, font: { size: 11 },
                        stepSize: Math.max(1, Math.ceil(Math.max(...@json($studentData)) / 5)),
                        precision: 0
                    }
                },
                x: { grid: { display: false }, border: { display: false }, ticks: { color: textColor, font: { size: 11 } } }
            }
        }
    });
});
</script>
@endpush
@endsection
