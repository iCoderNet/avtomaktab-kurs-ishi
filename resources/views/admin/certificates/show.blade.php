@extends('layouts.app')
@section('title', 'Sertifikat #' . $certificate->certificate_number)
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.certificates.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Sertifikatlar</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">{{ $certificate->certificate_number }}</span>
@endsection

@section('content')
<div class="max-w-3xl">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm mb-6">
        <div class="flex flex-col sm:flex-row sm:items-start gap-4">
            <div class="w-14 h-14 bg-indigo-100 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center shrink-0">
                <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-3">
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white font-mono">{{ $certificate->certificate_number }}</h1>
                    <x-badge :type="match($certificate->status ?? 'active') { 'active' => 'success', 'expired' => 'danger', 'revoked' => 'danger', default => 'default' }">
                        {{ match($certificate->status ?? 'active') { 'active' => 'Faol', 'expired' => 'Muddati o\'tgan', 'revoked' => 'Bekor qilingan', default => $certificate->status ?? '—' } }}
                    </x-badge>
                </div>
                <p class="text-sm text-slate-500 mt-1">
                    {{ $certificate->license_category }} toifa · Berildi:
                    {{ $certificate->issued_date?->format('d.m.Y') ?? '—' }}
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.certificates.download', $certificate) }}" target="_blank"
                    class="flex items-center gap-2 px-4 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-sm font-medium rounded-xl hover:bg-red-100 dark:hover:bg-red-900/40 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    PDF yuklab olish
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/50">
            Sertifikat tafsilotlari
        </h3>
        <dl class="space-y-4">
            <div class="flex justify-between items-start gap-4">
                <dt class="text-sm text-slate-500">O'quvchi</dt>
                <dd class="text-sm font-medium text-slate-900 dark:text-white text-right">
                    <a href="{{ route('admin.students.show', $certificate->student) }}" class="hover:text-indigo-600 transition">
                        {{ $certificate->student->user->name }}
                    </a>
                    <p class="text-xs text-slate-500 font-normal">{{ $certificate->student->student_number }}</p>
                </dd>
            </div>
            <div class="flex justify-between items-start gap-4">
                <dt class="text-sm text-slate-500">Sertifikat raqami</dt>
                <dd class="text-sm font-mono font-medium text-slate-900 dark:text-white">{{ $certificate->certificate_number }}</dd>
            </div>
            <div class="flex justify-between items-start gap-4">
                <dt class="text-sm text-slate-500">Litsenziya toifasi</dt>
                <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $certificate->license_category }} toifa</dd>
            </div>
            <div class="flex justify-between items-start gap-4">
                <dt class="text-sm text-slate-500">Berilgan sana</dt>
                <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $certificate->issued_date?->format('d.m.Y') ?? '—' }}</dd>
            </div>
            <div class="flex justify-between items-start gap-4">
                <dt class="text-sm text-slate-500">Amal qilish muddati</dt>
                <dd class="text-sm font-medium {{ $certificate->expiry_date?->isPast() ? 'text-red-500' : 'text-slate-900 dark:text-white' }}">
                    {{ $certificate->expiry_date?->format('d.m.Y') ?? '—' }}
                </dd>
            </div>
            @if($certificate->issuedBy)
            <div class="flex justify-between items-start gap-4">
                <dt class="text-sm text-slate-500">Berdi</dt>
                <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $certificate->issuedBy->name }}</dd>
            </div>
            @endif
            @if($certificate->notes)
            <div class="pt-3 border-t border-slate-100 dark:border-slate-700/50">
                <dt class="text-sm text-slate-500 mb-1">Izoh</dt>
                <dd class="text-sm text-slate-700 dark:text-slate-300">{{ $certificate->notes }}</dd>
            </div>
            @endif
        </dl>
    </div>
</div>
@endsection
