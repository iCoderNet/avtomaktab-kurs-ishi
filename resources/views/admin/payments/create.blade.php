@extends('layouts.app')
@section('title', "To'lov qabul qilish")
@section('breadcrumbs')
    <a href="{{ route('admin.payments.index') }}" class="hover:text-slate-700">To'lovlar</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Yangi to'lov</span>
@endsection
@section('content')
<x-page-header title="To'lov Qabul Qilish" />
<div class="max-w-lg">
    <form method="POST" action="{{ route('admin.payments.store') }}" class="space-y-5">
        @csrf
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">O'quvchi *</label>
                <select name="student_id" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="">O'quvchini tanlang...</option>
                    @foreach($students as $s)
                    <option value="{{ $s->id }}" {{ (request('student_id') == $s->id || (isset($selectedStudent) && $selectedStudent->id == $s->id)) ? 'selected' : '' }}>
                        {{ $s->user->name }} (Qarz: {{ number_format($s->debt, 0, '.', ' ') }} so'm)
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Miqdor (so'm) *</label>
                    <input type="number" name="amount" value="{{ old('amount') }}" required min="1"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Sana *</label>
                    <input type="date" name="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Turi</label>
                    <select name="type" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="tuition">O'quv to'lovi</option>
                        <option value="exam_fee">Imtihon to'lovi</option>
                        <option value="certificate">Sertifikat</option>
                        <option value="penalty">Jarima</option>
                        <option value="other">Boshqa</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Usul</label>
                    <select name="payment_method" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="cash">Naqd pul</option>
                        <option value="card">Karta</option>
                        <option value="transfer">O'tkazma</option>
                        <option value="online">Online</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Izoh</label>
                <textarea name="notes" rows="2" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none"></textarea>
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-green-600 hover:bg-green-500 text-white text-sm font-medium rounded-xl transition shadow-lg shadow-green-500/25">
                💰 To'lovni qabul qilish
            </button>
            <a href="{{ route('admin.payments.index') }}" class="px-6 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition">Bekor</a>
        </div>
    </form>
</div>
@endsection
