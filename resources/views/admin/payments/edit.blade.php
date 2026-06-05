@extends('layouts.app')
@section('title', "To'lov Tahrirlash")
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.payments.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200">To'lovlar</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.payments.show', $payment) }}" class="hover:text-slate-700 dark:hover:text-slate-200">{{ $payment->receipt_number }}</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Tahrirlash</span>
@endsection

@section('content')
<x-page-header title="To'lovni Tahrirlash" :description="$payment->receipt_number" />

<div class="max-w-xl">
    <form method="POST" action="{{ route('admin.payments.update', $payment) }}" class="space-y-6">
        @csrf @method('PUT')

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/50">
                To'lov ma'lumotlari
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Miqdori (so'm) *</label>
                    <input type="number" name="amount" value="{{ old('amount', $payment->amount) }}" required min="0"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white @error('amount') border-red-500 @enderror">
                    @error('amount')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">To'lov turi</label>
                    <select name="type"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
                        <option value="tuition" {{ old('type', $payment->type) === 'tuition' ? 'selected' : '' }}>O'quv to'lovi</option>
                        <option value="registration" {{ old('type', $payment->type) === 'registration' ? 'selected' : '' }}>Ro'yxatdan o'tish</option>
                        <option value="exam" {{ old('type', $payment->type) === 'exam' ? 'selected' : '' }}>Imtihon to'lovi</option>
                        <option value="certificate" {{ old('type', $payment->type) === 'certificate' ? 'selected' : '' }}>Sertifikat</option>
                        <option value="other" {{ old('type', $payment->type) === 'other' ? 'selected' : '' }}>Boshqa</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">To'lov usuli</label>
                    <select name="payment_method"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
                        <option value="cash" {{ old('payment_method', $payment->payment_method) === 'cash' ? 'selected' : '' }}>Naqd</option>
                        <option value="card" {{ old('payment_method', $payment->payment_method) === 'card' ? 'selected' : '' }}>Karta</option>
                        <option value="transfer" {{ old('payment_method', $payment->payment_method) === 'transfer' ? 'selected' : '' }}>O'tkazma</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">To'lov sanasi</label>
                    <input type="date" name="payment_date" value="{{ old('payment_date', $payment->payment_date?->format('Y-m-d')) }}"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Holat</label>
                    <select name="status"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
                        <option value="completed" {{ old('status', $payment->status) === 'completed' ? 'selected' : '' }}>Tasdiqlangan</option>
                        <option value="pending" {{ old('status', $payment->status) === 'pending' ? 'selected' : '' }}>Kutilmoqda</option>
                        <option value="cancelled" {{ old('status', $payment->status) === 'cancelled' ? 'selected' : '' }}>Bekor qilingan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Izoh</label>
                    <textarea name="notes" rows="3"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white resize-none">{{ old('notes', $payment->notes) }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition shadow-lg shadow-indigo-500/25">
                O'zgarishlarni saqlash
            </button>
            <a href="{{ route('admin.payments.show', $payment) }}" class="px-6 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                Bekor qilish
            </a>
        </div>
    </form>
</div>
@endsection
