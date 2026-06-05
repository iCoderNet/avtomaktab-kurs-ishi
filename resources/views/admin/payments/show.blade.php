@extends('layouts.app')
@section('title', "To'lov #" . $payment->receipt_number)
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.payments.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200">To'lovlar</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">{{ $payment->receipt_number }}</span>
@endsection

@section('content')
<div class="max-w-2xl">
    <!-- Header -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm mb-6">
        <div class="flex flex-col sm:flex-row sm:items-start gap-4">
            <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-2xl flex items-center justify-center shrink-0">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="flex-1">
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ number_format($payment->amount, 0, '.', ' ') }} so'm</h1>
                <p class="text-sm text-slate-500 mt-0.5 font-mono">{{ $payment->receipt_number }}</p>
                <p class="text-sm text-slate-500">{{ $payment->payment_date?->format('d.m.Y') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.payments.receipt', $payment) }}" target="_blank"
                    class="flex items-center gap-2 px-4 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-sm font-medium rounded-xl hover:bg-red-100 dark:hover:bg-red-900/40 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Kvitansiya yuklab olish
                </a>
                <a href="{{ route('admin.payments.edit', $payment) }}"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition">
                    Tahrirlash
                </a>
            </div>
        </div>
    </div>

    <!-- Details -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/50">
            To'lov tafsilotlari
        </h3>
        <dl class="space-y-4">
            <div class="flex justify-between items-start">
                <dt class="text-sm text-slate-500">O'quvchi</dt>
                <dd class="text-sm font-medium text-slate-900 dark:text-white text-right">
                    <a href="{{ route('admin.students.show', $payment->student) }}" class="hover:text-indigo-600 transition">
                        {{ $payment->student->user->name }}
                    </a>
                    <p class="text-xs text-slate-500 font-normal">{{ $payment->student->student_number }}</p>
                </dd>
            </div>
            <div class="flex justify-between items-start">
                <dt class="text-sm text-slate-500">To'lov turi</dt>
                <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $payment->type_name }}</dd>
            </div>
            <div class="flex justify-between items-start">
                <dt class="text-sm text-slate-500">To'lov usuli</dt>
                <dd class="text-sm font-medium text-slate-900 dark:text-white">
                    {{ match($payment->payment_method ?? 'cash') {
                        'cash' => 'Naqd',
                        'card' => 'Karta',
                        'transfer' => "O'tkazma",
                        default => $payment->payment_method ?? '—'
                    } }}
                </dd>
            </div>
            <div class="flex justify-between items-start">
                <dt class="text-sm text-slate-500">Miqdori</dt>
                <dd class="text-sm font-bold text-green-600">{{ number_format($payment->amount, 0, '.', ' ') }} so'm</dd>
            </div>
            <div class="flex justify-between items-start">
                <dt class="text-sm text-slate-500">To'lov sanasi</dt>
                <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $payment->payment_date?->format('d.m.Y') ?? '—' }}</dd>
            </div>
            <div class="flex justify-between items-start">
                <dt class="text-sm text-slate-500">Holat</dt>
                <dd>
                    <x-badge :type="match($payment->status ?? 'completed') { 'completed' => 'success', 'pending' => 'warning', 'cancelled' => 'danger', default => 'default' }">
                        {{ match($payment->status ?? 'completed') { 'completed' => 'Tasdiqlangan', 'pending' => 'Kutilmoqda', 'cancelled' => 'Bekor qilingan', default => $payment->status ?? '—' } }}
                    </x-badge>
                </dd>
            </div>
            @if($payment->receivedBy)
            <div class="flex justify-between items-start">
                <dt class="text-sm text-slate-500">Qabul qildi</dt>
                <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $payment->receivedBy->name }}</dd>
            </div>
            @endif
            @if($payment->notes)
            <div class="pt-3 border-t border-slate-100 dark:border-slate-700/50">
                <dt class="text-sm text-slate-500 mb-1">Izoh</dt>
                <dd class="text-sm text-slate-700 dark:text-slate-300">{{ $payment->notes }}</dd>
            </div>
            @endif
        </dl>
    </div>
</div>
@endsection
