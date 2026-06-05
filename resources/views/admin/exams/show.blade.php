@extends('layouts.app')
@section('title', $exam->name)
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.exams.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Imtihonlar</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">{{ $exam->name }}</span>
@endsection

@section('content')
<!-- Header -->
<div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm mb-6">
    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
        <div class="flex-1">
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ $exam->name }}</h1>
                <x-badge :type="match($exam->type) { 'theory' => 'primary', 'practical' => 'success', 'stop' => 'warning', default => 'default' }">
                    {{ match($exam->type) { 'theory' => 'Nazariy', 'practical' => 'Amaliy', 'stop' => "To'xtatish", default => $exam->type } }}
                </x-badge>
                <x-badge :type="($exam->status ?? 'active') === 'active' ? 'success' : 'default'">
                    {{ ($exam->status ?? 'active') === 'active' ? 'Faol' : 'Nofaol' }}
                </x-badge>
            </div>
            @if($exam->description)
            <p class="text-sm text-slate-500 mt-2">{{ $exam->description }}</p>
            @endif
            <div class="flex flex-wrap gap-4 mt-3 text-sm text-slate-600 dark:text-slate-400">
                @if($exam->exam_date)
                <span>Sana: {{ $exam->exam_date->format('d.m.Y') }}</span>
                @endif
                <span>Davomiyligi: {{ $exam->duration_minutes ?? 40 }} daqiqa</span>
                <span>O'tish bali: {{ $exam->passing_score ?? 70 }}%</span>
                @if($exam->total_questions)
                <span>Savollar: {{ $exam->total_questions }} ta</span>
                @endif
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.exams.results', $exam) }}"
                class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white text-sm font-medium rounded-xl transition">
                Natijalar
            </a>
            <a href="{{ route('admin.exams.edit', $exam) }}"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition">
                Tahrirlash
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Savollar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Savollar</h3>
            <span class="text-xs text-slate-500">{{ $exam->questions->count() }} ta</span>
        </div>
        <div class="divide-y divide-slate-50 dark:divide-slate-700/30 max-h-96 overflow-y-auto">
            @forelse($exam->questions as $i => $question)
            <div class="px-6 py-4">
                <p class="text-sm font-medium text-slate-900 dark:text-white mb-2">
                    {{ $i + 1 }}. {{ $question->question }}
                </p>
                @php
                    $opts = is_array($question->options) ? $question->options : json_decode($question->options, true);
                @endphp
                @if($opts)
                <div class="grid grid-cols-2 gap-1">
                    @foreach($opts as $key => $val)
                    <p class="text-xs {{ $key === $question->correct_answer ? 'text-green-600 dark:text-green-400 font-semibold' : 'text-slate-500' }}">
                        {{ $key }}. {{ $val }}
                        @if($key === $question->correct_answer)
                            <span class="ml-1">✓</span>
                        @endif
                    </p>
                    @endforeach
                </div>
                @endif
            </div>
            @empty
            <div class="px-6 py-8 text-center">
                <p class="text-sm text-slate-500">Savollar qo'shilmagan</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- So'nggi natijalar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">So'nggi natijalar</h3>
            <a href="{{ route('admin.exams.results', $exam) }}" class="text-xs text-indigo-600 hover:text-indigo-500">Barchasini ko'rish</a>
        </div>
        @forelse($exam->results->take(10) as $result)
        <div class="flex items-center gap-3 px-6 py-3 border-b border-slate-50 dark:border-slate-700/30 last:border-0">
            <img src="{{ $result->student?->user->avatar_url ?? '/images/default-avatar.png' }}" class="w-8 h-8 rounded-full" alt="">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ $result->student?->user->name ?? '—' }}</p>
                <p class="text-xs text-slate-500">{{ $result->completed_at?->format('d.m.Y') ?? '—' }} · {{ $result->score ?? 0 }}%</p>
            </div>
            <x-badge :type="$result->result === 'passed' ? 'success' : 'danger'">
                {{ $result->result === 'passed' ? "O'tdi" : "O'tmadi" }}
            </x-badge>
        </div>
        @empty
        <div class="px-6 py-8 text-center">
            <p class="text-sm text-slate-500">Hali natijalar yo'q</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
