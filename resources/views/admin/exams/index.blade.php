@extends('layouts.app')
@section('title', 'Imtihonlar')
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Imtihonlar</span>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <x-page-header title="Imtihonlar ro'yxati" :description="$exams->total() . ' ta imtihon'" />
    <a href="{{ route('admin.exams.create') }}"
        class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition shadow-lg shadow-indigo-500/25">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Yangi imtihon
    </a>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
    @if($exams->count())
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 dark:border-slate-700/50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nomi</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell">Turi</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden md:table-cell">Sanasi</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden lg:table-cell">Davomiyligi</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden lg:table-cell">O'tish bali</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden md:table-cell">Natijalar</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Holat</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-slate-700/30">
                @foreach($exams as $exam)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                    <td class="px-4 py-3">
                        <p class="font-medium text-slate-900 dark:text-white">{{ $exam->name }}</p>
                        @if($exam->total_questions)
                        <p class="text-xs text-slate-500">{{ $exam->total_questions }} ta savol</p>
                        @endif
                    </td>
                    <td class="px-4 py-3 hidden sm:table-cell">
                        <x-badge :type="match($exam->type) { 'theory' => 'primary', 'practical' => 'success', 'stop' => 'warning', default => 'default' }">
                            {{ match($exam->type) { 'theory' => 'Nazariy', 'practical' => 'Amaliy', 'stop' => 'To\'xtatish', default => $exam->type } }}
                        </x-badge>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        <span class="text-slate-600 dark:text-slate-400">{{ $exam->exam_date?->format('d.m.Y') ?? '—' }}</span>
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell">
                        <span class="text-slate-600 dark:text-slate-400">{{ $exam->duration_minutes ?? '—' }} daqiqa</span>
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell">
                        <span class="text-slate-600 dark:text-slate-400">{{ $exam->passing_score ?? '—' }}%</span>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        <span class="text-slate-600 dark:text-slate-400">{{ $exam->results_count ?? 0 }} ta</span>
                    </td>
                    <td class="px-4 py-3">
                        <x-badge :type="($exam->status ?? 'active') === 'active' ? 'success' : 'default'">
                            {{ ($exam->status ?? 'active') === 'active' ? 'Faol' : 'Nofaol' }}
                        </x-badge>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.exams.results', $exam) }}"
                                class="p-1.5 text-slate-400 hover:text-green-600 hover:bg-green-50 dark:hover:bg-green-900/30 rounded-lg transition" title="Natijalar">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.exams.show', $exam) }}"
                                class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition" title="Ko'rish">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.exams.edit', $exam) }}"
                                class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg transition" title="Tahrirlash">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.exams.destroy', $exam) }}"
                                onsubmit="return confirm('Imtihonni o\'chirishni tasdiqlaysizmi?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition" title="O'chirish">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700/50">
        {{ $exams->links() }}
    </div>
    @else
    <x-empty-state title="Imtihonlar topilmadi" description="Hali hech bir imtihon qo'shilmagan.">
        <a href="{{ route('admin.exams.create') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-xl hover:bg-indigo-500 transition">
            Birinchi imtihonni qo'shish
        </a>
    </x-empty-state>
    @endif
</div>
@endsection
