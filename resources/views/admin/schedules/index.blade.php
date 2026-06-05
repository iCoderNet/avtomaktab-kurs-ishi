@extends('layouts.app')
@section('title', 'Dars Jadvali')
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Dars Jadvali</span>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <x-page-header title="Dars Jadvali" :description="$date->isoFormat('D MMMM YYYY, dddd')" />
    <a href="{{ route('admin.schedules.create') }}"
        class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition shadow-lg shadow-indigo-500/25">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Yangi jadval
    </a>
</div>

<!-- Date navigator -->
<div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-100 dark:border-slate-700/50 shadow-sm mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.schedules.index', ['date' => $date->copy()->subDay()->format('Y-m-d')]) }}"
            class="p-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <form method="GET" class="flex-1 flex items-center gap-2">
            <input type="date" name="date" value="{{ $date->format('Y-m-d') }}"
                onchange="this.form.submit()"
                class="flex-1 px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
        </form>
        <a href="{{ route('admin.schedules.index', ['date' => now()->format('Y-m-d')]) }}"
            class="px-3 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-xl transition">
            Bugun
        </a>
        <a href="{{ route('admin.schedules.index', ['date' => $date->copy()->addDay()->format('Y-m-d')]) }}"
            class="p-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>
</div>

<!-- Table -->
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
    @if($schedules->count())
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 dark:border-slate-700/50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Vaqt</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Dars turi</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell">Instruktor</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden md:table-cell">Avtomobil</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden lg:table-cell">Manzil</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden md:table-cell">O'quvchilar</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-slate-700/30">
                @foreach($schedules as $schedule)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                    <td class="px-4 py-3">
                        <p class="font-semibold text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</p>
                        <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <x-badge :type="$schedule->lesson_type === 'theory' ? 'primary' : 'success'">
                            {{ $schedule->lesson_type === 'theory' ? 'Nazariy' : 'Amaliy' }}
                        </x-badge>
                    </td>
                    <td class="px-4 py-3 hidden sm:table-cell">
                        <span class="text-slate-700 dark:text-slate-300">{{ $schedule->instructor?->user->name ?? '—' }}</span>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        <span class="font-mono text-slate-600 dark:text-slate-400">{{ $schedule->vehicle?->plate_number ?? '—' }}</span>
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell">
                        <span class="text-slate-600 dark:text-slate-400">{{ $schedule->location ?? '—' }}</span>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        <span class="text-slate-600 dark:text-slate-400">
                            {{ $schedule->lessons_count ?? $schedule->lessons->count() }} / {{ $schedule->max_students ?? '—' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.schedules.show', $schedule) }}"
                                class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition" title="Ko'rish">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.schedules.edit', $schedule) }}"
                                class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg transition" title="Tahrirlash">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.schedules.destroy', $schedule) }}"
                                onsubmit="return confirm('Jadval yozuvini o\'chirishni tasdiqlaysizmi?')">
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
    @else
    <x-empty-state title="Bu kunda jadval yo'q" description="Tanlangan sana uchun hech qanday dars jadvali topilmadi.">
        <a href="{{ route('admin.schedules.create') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-xl hover:bg-indigo-500 transition">
            Jadval qo'shish
        </a>
    </x-empty-state>
    @endif
</div>
@endsection
