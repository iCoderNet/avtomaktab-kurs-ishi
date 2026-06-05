@extends('layouts.app')
@section('title', 'Baholar')
@section('breadcrumbs')
    <a href="{{ route('instructor.dashboard') }}" class="hover:text-slate-700">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Baholar</span>
@endsection
@section('content')
<x-page-header title="Baholar Berish" />

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Baho berish formasi -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm lg:col-span-1">
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">➕ Yangi baho</h3>
        <form method="POST" action="{{ route('instructor.grades.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">O'quvchi</label>
                <select name="student_id" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="">Tanlang...</option>
                    @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Baho turi</label>
                <select name="type" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                    <option value="theory">Nazariy</option>
                    <option value="practical">Amaliy</option>
                    <option value="behavior">Intizom</option>
                    <option value="final">Yakuniy</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Ball (0-100)</label>
                <input type="number" name="score" required min="0" max="100"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-medium text-slate-500 mb-1">Izoh</label>
                <textarea name="comment" rows="2" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none"></textarea>
            </div>
            <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition">
                Baho qo'yish
            </button>
        </form>
    </div>

    <!-- O'quvchilar baholari -->
    <div class="lg:col-span-2 space-y-3">
        @foreach($students as $student)
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <img src="{{ $student->user->avatar_url }}" class="w-9 h-9 rounded-full" alt="">
                <div>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $student->user->name }}</p>
                    <p class="text-xs text-slate-500">O'rtacha: {{ round($student->grades->avg('score'), 0) ?? 0 }}%</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach($student->grades->take(5) as $grade)
                <div class="text-center px-3 py-1 rounded-lg bg-slate-50 dark:bg-slate-900">
                    <span class="text-base font-bold {{ $grade->score >= 70 ? 'text-green-600' : 'text-red-500' }}">{{ $grade->grade_letter }}</span>
                    <p class="text-xs text-slate-500">{{ match($grade->type) { 'theory'=>'Naz', 'practical'=>'Aml', 'behavior'=>'Int', 'final'=>'Yak', default=>$grade->type } }}</p>
                </div>
                @endforeach
                @if($student->grades->isEmpty())
                <p class="text-xs text-slate-400 italic">Hali baho qo'yilmagan</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
