@extends('layouts.app')
@section('title', 'Sertifikatlar')
@section('breadcrumbs')
    <a href="{{ route('student.dashboard') }}" class="hover:text-slate-700">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Sertifikatlar</span>
@endsection
@section('content')
<x-page-header title="Mening Sertifikatlarim" />
@if($certificates->count())
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($certificates as $cert)
    <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl p-6 text-white shadow-lg shadow-indigo-500/25">
        <div class="text-3xl mb-3">🏆</div>
        <p class="text-sm font-medium opacity-80 mb-1">Sertifikat</p>
        <p class="text-lg font-bold">{{ $cert->license_category }} toifa haydovchi</p>
        <p class="text-sm opacity-80 mt-2">{{ $cert->certificate_number }}</p>
        <p class="text-xs opacity-60 mt-1">Berildi: {{ $cert->issued_date->format('d.m.Y') }}</p>
        <div class="mt-4 pt-4 border-t border-white/20">
            <a href="{{ route('student.certificates.download', $cert) }}"
                class="flex items-center gap-2 text-sm font-medium bg-white/20 hover:bg-white/30 px-4 py-2 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                PDF yuklash
            </a>
        </div>
    </div>
    @endforeach
</div>
@else
<x-empty-state title="Sertifikatlar yo'q" description="Kursni muvaffaqiyatli tamomlagan va imtihonlardan o'tganingizdan so'ng sertifikat beriladi." />
@endif
@endsection
