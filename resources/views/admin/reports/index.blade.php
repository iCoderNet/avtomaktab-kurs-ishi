@extends('layouts.app')
@section('title', 'Hisobotlar')
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Hisobotlar</span>
@endsection
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <x-page-header title="Hisobotlar va Tahlil" />
    <div class="flex gap-2">
        <a href="{{ route('admin.reports.export.excel', request()->query()) }}"
            class="flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-500 text-white text-sm font-medium rounded-xl transition">
            Excel
        </a>
        <a href="{{ route('admin.reports.export.pdf', request()->query()) }}"
            class="flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-500 text-white text-sm font-medium rounded-xl transition">
            PDF
        </a>
    </div>
</div>

<!-- Filter -->
<div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-100 dark:border-slate-700/50 shadow-sm mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs text-slate-500 mb-1">Boshlanish sanasi</label>
            <input type="date" name="start_date" value="{{ $startDate->format('Y-m-d') }}"
                class="px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>
        <div>
            <label class="block text-xs text-slate-500 mb-1">Tugash sanasi</label>
            <input type="date" name="end_date" value="{{ $endDate->format('Y-m-d') }}"
                class="px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>
        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm rounded-xl transition h-fit">Qo'llash</button>
    </form>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    <x-stat-card title="Yangi o'quvchilar" :value="$stats['new_students']" color="indigo">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"/></svg>
    </x-stat-card>
    <x-stat-card title="Daromad" :value="number_format($stats['total_revenue'], 0, '.', ' ')" color="green">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </x-stat-card>
    <x-stat-card title="Umumiy qarz" :value="number_format($stats['total_debt'], 0, '.', ' ')" color="red">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    </x-stat-card>
    <x-stat-card title="Imtihon o'tdi" :value="$stats['exams_passed']" color="blue">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </x-stat-card>
    <x-stat-card title="Davomat" :value="$stats['attendance_rate'].'%'" color="purple">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
    </x-stat-card>
</div>

<!-- Oylik daromad grafigi -->
<div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm">
    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">So'nggi 6 oy daromadi</h3>
    <canvas id="revenueChart" height="100"></canvas>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: @json(array_column($monthlyRevenue, 'label')),
        datasets: [{
            label: "Daromad (so'm)",
            data: @json(array_column($monthlyRevenue, 'amount')),
            backgroundColor: 'rgba(79, 70, 229, 0.8)',
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { ticks: { callback: v => (v/1000000).toFixed(1) + 'M so\'m' } }
        }
    }
});
</script>
@endpush
@endsection
