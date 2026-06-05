@extends('layouts.app')
@section('title', $exam->name)
@section('breadcrumbs')
    <a href="{{ route('student.exams.index') }}" class="hover:text-slate-700">Imtihonlar</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">{{ $exam->name }}</span>
@endsection
@section('content')
<div class="max-w-3xl mx-auto" x-data="{ timeLeft: {{ $exam->duration_minutes * 60 }}, timerInterval: null }"
    x-init="timerInterval = setInterval(() => { if(timeLeft > 0) timeLeft--; else { clearInterval(timerInterval); document.getElementById('examForm').submit(); } }, 1000)">

    <!-- Header -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ $exam->name }}</h1>
                <p class="text-sm text-slate-500 mt-1">{{ $questions->count() }} ta savol · O'tish bali: {{ $exam->passing_score }}%</p>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold font-mono text-indigo-600"
                    x-text="Math.floor(timeLeft/60).toString().padStart(2,'0') + ':' + (timeLeft%60).toString().padStart(2,'0')"></div>
                <p class="text-xs text-slate-500 mt-0.5">qoldi</p>
            </div>
        </div>
    </div>

    <form id="examForm" method="POST" action="{{ route('student.exams.submit', $exam) }}">
        @csrf
        <div class="space-y-4">
            @foreach($questions as $i => $question)
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm">
                <p class="text-sm font-semibold text-slate-900 dark:text-white mb-4">
                    <span class="inline-flex items-center justify-center w-6 h-6 bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 rounded-full text-xs mr-2">{{ $i + 1 }}</span>
                    {{ $question->question }}
                </p>
                @if($question->image)
                <img src="{{ asset('storage/'.$question->image) }}" class="mb-4 rounded-xl max-h-40 object-contain" alt="">
                @endif
                <div class="space-y-2">
                    @foreach($question->options as $key => $option)
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-600 cursor-pointer hover:border-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 dark:has-[:checked]:bg-indigo-900/30">
                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $key }}" class="text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-slate-700 dark:text-slate-300">
                            <strong class="text-indigo-600 mr-2">{{ $key }})</strong>{{ $option }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6 flex gap-3">
            <button type="submit" onclick="return confirm('Imtihonni yakunlashni tasdiqlaysizmi?')"
                class="flex-1 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl transition shadow-lg shadow-indigo-500/25">
                ✅ Imtihonni yakunlash
            </button>
        </div>
    </form>
</div>
@endsection
