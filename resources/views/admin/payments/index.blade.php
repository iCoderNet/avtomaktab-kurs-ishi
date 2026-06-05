@extends('layouts.app')
@section('title', "To'lovlar")
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">To'lovlar</span>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <x-page-header title="To'lovlar" description="Barcha o'quvchilarning to'lovlari" />
    <a href="{{ route('admin.payments.create') }}" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        To'lov qabul qilish
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <p class="text-xs text-slate-500 font-medium mb-1">Oylik daromad</p>
        <p class="text-2xl font-bold text-green-600">{{ number_format($totalThisMonth, 0, '.', ' ') }} so'm</p>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <p class="text-xs text-slate-500 font-medium mb-1">Qarzli o'quvchilar</p>
        <p class="text-2xl font-bold text-red-500">{{ $debtStudents }} ta</p>
    </div>
</div>

<!-- Filter -->
<div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-100 dark:border-slate-700/50 shadow-sm mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ism yoki kvitansiya raqami..."
            class="flex-1 min-w-48 px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        <input type="month" name="month" value="{{ request('month') }}"
            class="px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm rounded-xl transition">Qidirish</button>
        @if(request()->anyFilled(['search','month']))
        <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition">Tozalash</a>
        @endif
    </form>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
    @if($payments->count())
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-100 dark:border-slate-700/50">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Kvitansiya</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">O'quvchi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase hidden sm:table-cell">Turi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Miqdor</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase hidden md:table-cell">Sana</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-slate-700/30">
                @foreach($payments as $payment)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                    <td class="px-6 py-4">
                        <span class="text-sm font-mono text-slate-600 dark:text-slate-400">{{ $payment->receipt_number }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $payment->student->user->name }}</p>
                    </td>
                    <td class="px-6 py-4 hidden sm:table-cell">
                        <span class="text-sm text-slate-600 dark:text-slate-400">{{ $payment->type_name }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-semibold text-green-600">{{ number_format($payment->amount, 0, '.', ' ') }} so'm</span>
                    </td>
                    <td class="px-6 py-4 hidden md:table-cell">
                        <span class="text-sm text-slate-500">{{ $payment->payment_date->format('d.m.Y') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.payments.receipt', $payment) }}"
                                class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition" title="PDF">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('admin.payments.destroy', $payment) }}"
                                onsubmit="return confirm('O\'chirishni tasdiqlaysizmi?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700/50">{{ $payments->links() }}</div>
    @else
    <x-empty-state title="To'lovlar topilmadi" />
    @endif
</div>
@endsection
