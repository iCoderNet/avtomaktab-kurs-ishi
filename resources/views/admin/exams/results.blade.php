@extends('layouts.app')
@section('title', $exam->name . ' — Natijalar')
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.exams.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Imtihonlar</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.exams.show', $exam) }}" class="hover:text-slate-700 dark:hover:text-slate-200">{{ $exam->name }}</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Natijalar</span>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <x-page-header title="{{ $exam->name }} — Natijalar" :description="$exam->results->count() . ' ta natija'" />
    <a href="{{ route('admin.exams.show', $exam) }}"
        class="px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-xl transition">
        Imtihonga qaytish
    </a>
</div>

<!-- Stats -->
@php
    $passed = $exam->results->where('result', 'passed')->count();
    $failed = $exam->results->where('result', 'failed')->count();
    $total = $exam->results->count();
    $avgScore = $total > 0 ? round($exam->results->avg('score')) : 0;
@endphp
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <p class="text-xs text-slate-500 font-medium mb-1">Jami</p>
        <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $total }}</p>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <p class="text-xs text-slate-500 font-medium mb-1">O'tdi</p>
        <p class="text-2xl font-bold text-green-600">{{ $passed }}</p>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <p class="text-xs text-slate-500 font-medium mb-1">O'tmadi</p>
        <p class="text-2xl font-bold text-red-500">{{ $failed }}</p>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <p class="text-xs text-slate-500 font-medium mb-1">O'rtacha ball</p>
        <p class="text-2xl font-bold text-indigo-600">{{ $avgScore }}%</p>
    </div>
</div>

<!-- Results table -->
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
    @if($exam->results->count())
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 dark:border-slate-700/50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">O'quvchi</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Ball</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Natija</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell">Urinish</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden md:table-cell">Boshlandi</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden md:table-cell">Tugadi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-slate-700/30">
                @foreach($exam->results as $result)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $result->student?->user->avatar_url ?? '/images/default-avatar.png' }}" class="w-8 h-8 rounded-full" alt="">
                            <div>
                                <p class="font-medium text-slate-900 dark:text-white">{{ $result->student?->user->name ?? '—' }}</p>
                                <p class="text-xs text-slate-500">{{ $result->student?->student_number ?? '' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-semibold {{ ($result->score ?? 0) >= ($exam->passing_score ?? 70) ? 'text-green-600' : 'text-red-500' }}">
                            {{ $result->score ?? 0 }}%
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <x-badge :type="$result->result === 'passed' ? 'success' : 'danger'">
                            {{ $result->result === 'passed' ? "O'tdi" : "O'tmadi" }}
                        </x-badge>
                    </td>
                    <td class="px-4 py-3 hidden sm:table-cell">
                        <span class="text-slate-600 dark:text-slate-400">{{ $result->attempt_number ?? 1 }}-urinish</span>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        <span class="text-slate-500">{{ $result->started_at?->format('d.m.Y H:i') ?? '—' }}</span>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        <span class="text-slate-500">{{ $result->completed_at?->format('d.m.Y H:i') ?? '—' }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <x-empty-state title="Natijalar topilmadi" description="Bu imtihon uchun hali hech qanday natija yo'q." />
    @endif
</div>
@endsection
