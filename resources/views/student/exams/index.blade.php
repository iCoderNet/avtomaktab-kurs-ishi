@extends('layouts.app')
@section('title', 'Imtihonlar')
@section('breadcrumbs')
    <a href="{{ route('student.dashboard') }}" class="hover:text-slate-700">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Imtihonlar</span>
@endsection
@section('content')
<x-page-header title="Imtihonlar" />

@if($upcomingExams->count())
<div class="mb-6">
    <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">📋 Mavjud imtihonlar</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach($upcomingExams as $exam)
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-indigo-200 dark:border-indigo-800/50 shadow-sm p-5">
            <p class="font-semibold text-slate-900 dark:text-white">{{ $exam->name }}</p>
            <p class="text-sm text-slate-500 mt-1">{{ $exam->exam_date->format('d.m.Y H:i') }} · {{ $exam->duration_minutes }} daqiqa</p>
            <p class="text-xs text-slate-500 mt-1">O'tish bali: {{ $exam->passing_score }}%</p>
            @if($exam->type === 'theory')
            <a href="{{ route('student.exams.take', $exam) }}"
                class="mt-3 inline-block px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition">
                Imtihonga kirish →
            </a>
            @else
            <p class="mt-3 text-xs text-amber-600 bg-amber-50 dark:bg-amber-900/30 px-3 py-1.5 rounded-lg inline-block">Amaliy imtihon — instruktor bilan bog'laning</p>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

<h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">📊 Imtihon natijalari</h3>
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
    @if($myResults->count())
    @foreach($myResults as $result)
    <div class="flex items-center gap-4 px-6 py-4 border-b border-slate-50 dark:border-slate-700/30 last:border-0">
        <div class="w-12 h-12 rounded-2xl {{ $result->result === 'passed' ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }} flex items-center justify-center flex-shrink-0">
            <span class="text-xl">{{ $result->result === 'passed' ? '✅' : '❌' }}</span>
        </div>
        <div class="flex-1">
            <p class="font-medium text-slate-900 dark:text-white">{{ $result->exam->name }}</p>
            <p class="text-xs text-slate-500">{{ $result->exam->exam_date->format('d.m.Y') }} · {{ $result->correct_answers ?? '—' }}/{{ $result->total_questions ?? '—' }} to'g'ri</p>
        </div>
        <div class="text-right">
            <p class="text-xl font-bold {{ $result->score >= $result->exam->passing_score ? 'text-green-600' : 'text-red-500' }}">
                {{ $result->score }}%
            </p>
            <x-badge :type="$result->result">{{ $result->result === 'passed' ? "O'tdi" : ($result->result === 'failed' ? "O'tmadi" : 'Kelmadi') }}</x-badge>
        </div>
    </div>
    @endforeach
    @else
    <x-empty-state title="Imtihon natijalari yo'q" />
    @endif
</div>
@endsection
