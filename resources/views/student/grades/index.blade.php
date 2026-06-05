@extends('layouts.app')
@section('title', 'Baholarim')
@section('breadcrumbs')
    <a href="{{ route('student.dashboard') }}" class="hover:text-slate-700">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Baholarim</span>
@endsection
@section('content')
<div class="flex items-center justify-between mb-6">
    <x-page-header title="Baholarim" />
    <div class="bg-indigo-50 dark:bg-indigo-900/30 rounded-xl px-4 py-2 text-center">
        <p class="text-xs text-slate-500">O'rtacha baho</p>
        <p class="text-xl font-bold text-indigo-600">{{ round($avgScore, 1) }}%</p>
    </div>
</div>
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
    @if($grades->count())
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-100 dark:border-slate-700/50">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Sana</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Turi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase hidden sm:table-cell">Izoh</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Ball</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-slate-700/30">
                @foreach($grades as $grade)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                    <td class="px-6 py-4">
                        <p class="text-sm text-slate-600 dark:text-slate-400">{{ $grade->graded_at->format('d.m.Y') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-slate-600 dark:text-slate-400">{{ match($grade->type) { 'theory'=>'Nazariy', 'practical'=>'Amaliy', 'behavior'=>'Intizom', 'final'=>'Yakuniy', default=>$grade->type } }}</span>
                    </td>
                    <td class="px-6 py-4 hidden sm:table-cell">
                        <span class="text-sm text-slate-500">{{ $grade->comment ?? '—' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <span class="text-xl font-bold {{ $grade->score >= 70 ? 'text-green-600' : 'text-red-500' }}">{{ $grade->grade_letter }}</span>
                            <span class="text-sm text-slate-500">{{ $grade->score }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700/50">{{ $grades->links() }}</div>
    @else
    <x-empty-state title="Baholar yo'q" />
    @endif
</div>
@endsection
