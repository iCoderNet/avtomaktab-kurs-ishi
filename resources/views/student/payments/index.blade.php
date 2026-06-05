@extends('layouts.app')
@section('title', "To'lovlar")
@section('breadcrumbs')
    <a href="{{ route('student.dashboard') }}" class="hover:text-slate-700">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">To'lovlar</span>
@endsection
@section('content')
<x-page-header title="To'lovlar Tarixi" />

<!-- Summary -->
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm text-center">
        <p class="text-xs text-slate-500 mb-1">Jami to'lov</p>
        <p class="text-lg font-bold text-slate-900 dark:text-white">{{ number_format($student->total_fee, 0, '.', ' ') }} so'm</p>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm text-center">
        <p class="text-xs text-slate-500 mb-1">To'langan</p>
        <p class="text-lg font-bold text-green-600">{{ number_format($student->paid_amount, 0, '.', ' ') }} so'm</p>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm text-center">
        <p class="text-xs text-slate-500 mb-1">Qarz</p>
        <p class="text-lg font-bold {{ $student->debt > 0 ? 'text-red-500' : 'text-green-500' }}">{{ number_format($student->debt, 0, '.', ' ') }} so'm</p>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
    @if($payments->count())
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-100 dark:border-slate-700/50">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Kvitansiya</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase hidden sm:table-cell">Turi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Miqdor</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Sana</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-slate-700/30">
                @foreach($payments as $payment)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                    <td class="px-6 py-4">
                        <span class="text-sm font-mono text-slate-600 dark:text-slate-400">{{ $payment->receipt_number }}</span>
                    </td>
                    <td class="px-6 py-4 hidden sm:table-cell">
                        <span class="text-sm text-slate-600 dark:text-slate-400">{{ $payment->type_name }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-bold text-green-600">{{ number_format($payment->amount, 0, '.', ' ') }} so'm</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-slate-500">{{ $payment->payment_date->format('d.m.Y') }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700/50">{{ $payments->links() }}</div>
    @else
    <x-empty-state title="To'lovlar yo'q" />
    @endif
</div>
@endsection
