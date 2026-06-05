@extends('layouts.app')
@section('title', 'Jadval')
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2 text-slate-300 dark:text-slate-600">/</span>
    <a href="{{ route('admin.schedules.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Dars Jadvali</a>
    <span class="mx-2 text-slate-300 dark:text-slate-600">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Jadval #{{ $schedule->id }}</span>
@endsection

@section('content')

{{-- Header --}}
<div class="bg-white dark:bg-slate-800/60 rounded-2xl p-5
    border border-slate-100 dark:border-white/[0.06] shadow-card mb-5">
    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
        <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 flex items-center justify-center shrink-0">
            <svg class="w-7 h-7 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div class="flex-1">
            <div class="flex items-center gap-2.5 flex-wrap">
                <h1 class="text-lg font-bold text-slate-900 dark:text-white">
                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                    — {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                </h1>
                <x-badge :type="$schedule->lesson_type === 'theory' ? 'info' : 'success'">
                    {{ $schedule->lesson_type === 'theory' ? 'Nazariy' : 'Amaliy' }}
                </x-badge>
            </div>
            <p class="text-sm text-slate-500 mt-1">
                {{ ($schedule->date instanceof \Carbon\Carbon ? $schedule->date : \Carbon\Carbon::parse($schedule->date))->format('d.m.Y') }}
                @if($schedule->instructor) · <span class="font-medium text-slate-700 dark:text-slate-300">{{ $schedule->instructor->user->name }}</span> @endif
                @if($schedule->location) · {{ $schedule->location }} @endif
            </p>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('admin.schedules.edit', $schedule) }}"
                class="px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300
                    bg-slate-100 dark:bg-white/[0.06] hover:bg-slate-200 dark:hover:bg-white/[0.10]
                    rounded-xl transition">
                Tahrirlash
            </a>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-5 pt-5 border-t border-slate-100 dark:border-white/[0.06]">
        <div class="text-center p-3 rounded-xl bg-slate-50 dark:bg-white/[0.03]">
            <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $schedule->lessons->count() }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Tayinlangan</p>
        </div>
        <div class="text-center p-3 rounded-xl bg-slate-50 dark:bg-white/[0.03]">
            <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $schedule->max_students ?? '∞' }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Maksimal</p>
        </div>
        <div class="text-center p-3 rounded-xl bg-slate-50 dark:bg-white/[0.03]">
            <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $schedule->vehicle?->plate_number ?? '—' }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Avtomobil</p>
        </div>
        <div class="text-center p-3 rounded-xl bg-slate-50 dark:bg-white/[0.03]">
            <p class="text-xl font-bold text-slate-900 dark:text-white">{{ $schedule->location ?? '—' }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Manzil</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- ══ Tayinlangan o'quvchilar ══ --}}
    <div class="bg-white dark:bg-slate-800/60 rounded-2xl
        border border-slate-100 dark:border-white/[0.06] shadow-card overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-white/[0.06]">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                    <svg class="w-3 h-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Tayinlangan o'quvchilar</h3>
            </div>
            <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-slate-100 dark:bg-white/[0.06] text-slate-600 dark:text-slate-400">
                {{ $schedule->lessons->count() }} ta
            </span>
        </div>

        @forelse($schedule->lessons as $lesson)
        <div class="flex items-center gap-3.5 px-5 py-3.5 border-b border-slate-50 dark:border-white/[0.03] last:border-0">
            <img src="{{ $lesson->student?->user->avatar_url ?? '/images/default-avatar.png' }}"
                class="w-8 h-8 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-white/10 flex-shrink-0" alt="">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-slate-900 dark:text-white truncate">
                    {{ $lesson->student?->user->name ?? '—' }}
                </p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $lesson->student?->student_number ?? '' }}</p>
            </div>
            <x-badge :type="match($lesson->status) {
                'completed' => 'success',
                'missed'    => 'danger',
                'cancelled' => 'inactive',
                default     => 'info'
            }">
                {{ match($lesson->status) {
                    'completed' => 'Bajarildi',
                    'missed'    => 'Kelmadi',
                    'cancelled' => 'Bekor',
                    default     => 'Rejalashtirilgan'
                } }}
            </x-badge>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center py-10 text-center px-4">
            <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-white/[0.04] flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Hali o'quvchi tayinlanmagan</p>
            <p class="text-xs text-slate-400 mt-1">O'ng tomondagi ro'yxatdan tanlang</p>
        </div>
        @endforelse
    </div>

    {{-- ══ O'quvchi tayinlash ══ --}}
    <div x-data="{ selected: [] }" class="bg-white dark:bg-slate-800/60 rounded-2xl
        border border-slate-100 dark:border-white/[0.06] shadow-card overflow-hidden">

        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-white/[0.06]">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-lg bg-indigo-500/10 flex items-center justify-center">
                    <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold text-slate-900 dark:text-white">O'quvchi tayinlash</h3>
            </div>
            {{-- Tanlangan soni --}}
            <span x-show="selected.length > 0"
                class="text-xs font-semibold px-2 py-0.5 rounded-full bg-indigo-500 text-white">
                <span x-text="selected.length"></span> tanlandi
            </span>
        </div>

        @if($students->isEmpty())
        <div class="flex flex-col items-center justify-center py-10 px-4 text-center">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">
                Bu instruktorda faol o'quvchi yo'q
            </p>
            <p class="text-xs text-slate-400 mt-1">
                Avval o'quvchiga instruktor tayinlang
            </p>
        </div>
        @else

        {{-- O'quvchilar ro'yxati --}}
        <div class="divide-y divide-slate-50 dark:divide-white/[0.03] max-h-72 overflow-y-auto scrollbar-thin">
            @foreach($students as $student)
            @php $alreadyAssigned = $schedule->lessons->where('student_id', $student->id)->isNotEmpty(); @endphp
            <label class="flex items-center gap-3.5 px-5 py-3.5 cursor-pointer
                hover:bg-slate-50 dark:hover:bg-white/[0.03] transition
                {{ $alreadyAssigned ? 'opacity-50 cursor-not-allowed' : '' }}">
                <input type="checkbox"
                    value="{{ $student->id }}"
                    @if($alreadyAssigned) disabled @else
                        x-model="selected"
                    @endif
                    class="w-4 h-4 rounded border-slate-300 dark:border-white/20
                        text-indigo-500 bg-white dark:bg-slate-800
                        focus:ring-indigo-500 focus:ring-offset-0 flex-shrink-0">
                <img src="{{ $student->user->avatar_url }}"
                    class="w-8 h-8 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-white/10 flex-shrink-0" alt="">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-900 dark:text-white truncate">
                        {{ $student->user->name }}
                    </p>
                    <p class="text-xs text-slate-500 mt-0.5">
                        {{ $student->license_category }} toifa · {{ $student->student_number }}
                    </p>
                </div>
                @if($alreadyAssigned)
                <span class="flex-shrink-0 text-[11px] font-semibold text-emerald-600 dark:text-emerald-400
                    bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 rounded-full">
                    Tayinlangan
                </span>
                @endif
            </label>
            @endforeach
        </div>

        {{-- Tayinlash tugmasi --}}
        <div class="px-5 py-4 border-t border-slate-100 dark:border-white/[0.06] bg-slate-50 dark:bg-white/[0.02]">
            <form method="POST"
                action="{{ route('admin.schedules.assign', $schedule) }}"
                x-on:submit.prevent="
                    if(selected.length === 0) { alert('Avval o\'quvchi tanlang!'); return; }
                    $el.submit();
                ">
                @csrf
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="student_ids[]" :value="id">
                </template>
                <button type="submit"
                    class="w-full h-10 flex items-center justify-center gap-2
                        bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed
                        text-white text-sm font-semibold rounded-xl
                        transition-all duration-150
                        shadow-sm shadow-indigo-500/20"
                    :disabled="selected.length === 0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v16m8-8H4"/>
                    </svg>
                    <span x-show="selected.length === 0">O'quvchi tanlang</span>
                    <span x-show="selected.length > 0" x-cloak>
                        <span x-text="selected.length"></span> o'quvchi qo'shish
                    </span>
                </button>
            </form>
        </div>
        @endif
    </div>

</div>
@endsection
