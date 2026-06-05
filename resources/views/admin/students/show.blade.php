@extends('layouts.app')
@section('title', $student->user->name)
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.students.index') }}" class="hover:text-slate-700">O'quvchilar</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">{{ $student->user->name }}</span>
@endsection

@section('content')
<!-- Header -->
<div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm mb-6">
    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
        <img src="{{ $student->user->avatar_url }}" class="w-16 h-16 rounded-2xl object-cover" alt="">
        <div class="flex-1">
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ $student->user->name }}</h1>
                <x-badge :type="$student->status">
                    {{ match($student->status) { 'active'=>'Faol', 'inactive'=>'Nofaol', 'completed'=>'Tugatdi', 'expelled'=>'Chiqarildi', default=>$student->status } }}
                </x-badge>
            </div>
            <p class="text-sm text-slate-500 mt-1">{{ $student->student_number }} · {{ $student->license_category }} toifa · {{ $student->user->phone }}</p>
            @if($student->instructor)
            <p class="text-sm text-slate-500">Instruktor: <span class="text-slate-700 dark:text-slate-300">{{ $student->instructor->user->name }}</span></p>
            @endif
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.students.edit', $student) }}"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition">
                Tahrirlash
            </a>
            <a href="{{ route('admin.payments.create', ['student_id' => $student->id]) }}"
                class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white text-sm font-medium rounded-xl transition">
                To'lov qo'shish
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-6 border-t border-slate-100 dark:border-slate-700/50">
        <div class="text-center">
            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $student->lessons->count() }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Jami dars</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $student->attendance_rate }}%</p>
            <p class="text-xs text-slate-500 mt-0.5">Davomat</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $student->payment_percentage }}%</p>
            <p class="text-xs text-slate-500 mt-0.5">To'lov</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold {{ $student->debt > 0 ? 'text-red-500' : 'text-green-500' }}">
                {{ number_format($student->debt, 0, '.', ' ') }}
            </p>
            <p class="text-xs text-slate-500 mt-0.5">Qarz (so'm)</p>
        </div>
    </div>
</div>

<!-- Tabs content -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- To'lovlar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">To'lovlar</h3>
            <span class="text-xs text-slate-500">{{ $student->payments->count() }} ta</span>
        </div>
        @forelse($student->payments as $payment)
        <div class="flex items-center gap-3 px-6 py-3 border-b border-slate-50 dark:border-slate-700/30 last:border-0">
            <div class="flex-1">
                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $payment->type_name }}</p>
                <p class="text-xs text-slate-500">{{ $payment->payment_date->format('d.m.Y') }} · {{ $payment->receipt_number }}</p>
            </div>
            <p class="text-sm font-bold text-green-600">{{ number_format($payment->amount, 0, '.', ' ') }} so'm</p>
        </div>
        @empty
        <x-empty-state title="To'lovlar yo'q" />
        @endforelse
    </div>

    <!-- Imtihon natijalari -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Imtihon natijalari</h3>
        </div>
        @forelse($student->examResults as $result)
        <div class="flex items-center gap-3 px-6 py-3 border-b border-slate-50 dark:border-slate-700/30 last:border-0">
            <div class="flex-1">
                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $result->exam->name }}</p>
                <p class="text-xs text-slate-500">{{ $result->exam->exam_date->format('d.m.Y') }} · {{ $result->score }}%</p>
            </div>
            <x-badge :type="$result->result">
                {{ $result->result === 'passed' ? 'O\'tdi' : ($result->result === 'failed' ? 'O\'tmadi' : 'Kelmadi') }}
            </x-badge>
        </div>
        @empty
        <x-empty-state title="Imtihon natijalari yo'q" />
        @endforelse
    </div>

    <!-- Sertifikatlar -->
    @if($student->certificates->count())
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden lg:col-span-2">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Sertifikatlar</h3>
        </div>
        @foreach($student->certificates as $cert)
        <div class="flex items-center gap-4 px-6 py-4">
            <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $cert->certificate_number }}</p>
                <p class="text-xs text-slate-500">{{ $cert->license_category }} toifa · Berildi: {{ $cert->issued_date->format('d.m.Y') }}</p>
            </div>
            <a href="{{ route('admin.certificates.download', $cert) }}" target="_blank"
                class="px-3 py-1.5 bg-red-50 dark:bg-red-900/30 text-red-600 text-xs font-medium rounded-lg hover:bg-red-100 transition">
                PDF
            </a>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
