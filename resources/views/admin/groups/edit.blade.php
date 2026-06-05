@extends('layouts.app')

@section('title', 'Guruhni tahrirlash: ' . $group->name)

@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.groups.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Guruhlar</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.groups.show', $group->id) }}" class="hover:text-slate-700 dark:hover:text-slate-200">{{ $group->name }}</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Tahrirlash</span>
@endsection

@section('content')

<div class="mb-6 flex items-start justify-between gap-4">
    <x-page-header title="Guruhni tahrirlash" description="{{ $group->name }} — asosiy ma'lumotlarni o'zgartiring." />
    <a href="{{ route('admin.groups.show', $group->id) }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-xl transition flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Orqaga
    </a>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Asosiy forma --}}
    <div class="xl:col-span-2">
        <div class="bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-white/[0.06] shadow-card">

            @if($errors->any())
            <div class="px-6 pt-6">
                <div class="flex gap-3 p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-xl">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <ul class="text-sm text-red-700 dark:text-red-400 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <form action="{{ route('admin.groups.update', $group->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Asosiy ma'lumotlar --}}
                <div class="p-6 space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-white/[0.06]">
                        <div class="w-8 h-8 rounded-lg bg-amber-500 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Asosiy ma'lumotlar</h3>
                    </div>

                    {{-- Guruh nomi --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                            Guruh nomi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name"
                               value="{{ old('name', $group->name) }}"
                               class="w-full px-4 py-2.5 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-900 border @error('name') border-red-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                        @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    {{-- Instruktor --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                            Instruktor <span class="text-red-500">*</span>
                        </label>
                        <select name="instructor_id"
                                class="w-full px-4 py-2.5 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-900 border @error('instructor_id') border-red-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            <option value="">— Tanlang —</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}"
                                    {{ old('instructor_id', $group->instructor_id) == $instructor->id ? 'selected' : '' }}>
                                    {{ $instructor->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('instructor_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        <p class="text-xs text-slate-400 mt-1.5">Instruktor o'zgartirilsa, guruh o'quvchilari va darslarining instruktori ham yangilanadi.</p>
                    </div>

                    {{-- Holat --}}
                    <div x-data="{ status: '{{ old('status', $group->status) }}' }">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                            Holat <span class="text-red-500">*</span>
                        </label>
                        <input type="hidden" name="status" :value="status">
                        <div class="grid grid-cols-3 gap-3">
                            <button type="button" @click="status = 'active'"
                                    :class="status === 'active'
                                        ? 'border-emerald-500 bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-400'
                                        : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700'"
                                    class="p-3 text-center rounded-xl border-2 text-sm font-medium transition-all">
                                Faol
                            </button>
                            <button type="button" @click="status = 'completed'"
                                    :class="status === 'completed'
                                        ? 'border-blue-500 bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-400'
                                        : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700'"
                                    class="p-3 text-center rounded-xl border-2 text-sm font-medium transition-all">
                                Tugatildi
                            </button>
                            <button type="button" @click="status = 'cancelled'"
                                    :class="status === 'cancelled'
                                        ? 'border-slate-500 bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-500'
                                        : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700'"
                                    class="p-3 text-center rounded-xl border-2 text-sm font-medium transition-all">
                                Bekor qilindi
                            </button>
                        </div>
                        @error('status')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Muddat --}}
                <div class="px-6 pb-6 space-y-4">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-white/[0.06]">
                        <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">O'quv muddati</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                Boshlanish sanasi <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="start_date" id="start_date"
                                   value="{{ old('start_date', $group->start_date?->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2.5 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-900 border @error('start_date') border-red-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            @error('start_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                Tugash sanasi <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="end_date" id="end_date"
                                   value="{{ old('end_date', $group->end_date?->format('Y-m-d')) }}"
                                   class="w-full px-4 py-2.5 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-900 border @error('end_date') border-red-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            @error('end_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Muddat ko'rsatkichi --}}
                    <div id="duration-badge" class="@if(!$group->start_date || !$group->end_date) hidden @endif">
                        <div class="flex items-center gap-2 px-4 py-2.5 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 rounded-xl">
                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-indigo-700 dark:text-indigo-400 font-medium" id="duration-text">
                                @if($group->start_date && $group->end_date)
                                    @php
                                        $diff = $group->start_date->diffInDays($group->end_date);
                                        $months = floor($diff / 30);
                                        $days = $diff % 30;
                                    @endphp
                                    O'quv muddati: {{ $months > 0 ? $months.' oy '.($days > 0 ? $days.' kun ' : '') : '' }}({{ $diff }} kun)
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Tezkor muddat --}}
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Tezkor sanalar (boshlanish sanasidan hisoblaydi):</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach([80 => '2 oy 20 kun', 90 => '3 oy', 120 => '4 oy', 180 => '6 oy'] as $days => $label)
                            <button type="button" onclick="setDuration({{ $days }})"
                                    class="px-3 py-1.5 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-800 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400 transition-all">
                                {{ $label }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/30 border-t border-slate-100 dark:border-white/[0.06] flex items-center gap-3 rounded-b-2xl">
                    <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-amber-500 hover:bg-amber-400 rounded-xl shadow-sm shadow-amber-500/20 transition">
                        O'zgarishlarni saqlash
                    </button>
                    <a href="{{ route('admin.groups.show', $group->id) }}"
                       class="px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-xl transition">
                        Bekor qilish
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Yon panel: Guruh haqida --}}
    <div class="space-y-4">
        {{-- Joriy holat --}}
        <div class="bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-white/[0.06] shadow-card p-5">
            <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Joriy ma'lumotlar</h4>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-white/[0.04]">
                    <span class="text-xs text-slate-500">O'quvchilar</span>
                    <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $group->students()->count() }} ta</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-100 dark:border-white/[0.04]">
                    <span class="text-xs text-slate-500">Jadvaldagi darslar</span>
                    <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $group->schedules()->count() }} ta</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-xs text-slate-500">Yaratilgan</span>
                    <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $group->created_at->format('d.m.Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Ogohlantiruv --}}
        <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-2xl p-5">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-400 mb-1">Muhim</p>
                    <p class="text-xs text-amber-700 dark:text-amber-500 leading-relaxed">
                        Muddatni o'zgartirgandan keyin, dars jadvalini qayta generatsiya qilish tavsiya etiladi — eski jadval yangi muddat bilan mos kelmasligi mumkin.
                    </p>
                </div>
            </div>
        </div>

        {{-- Tezkor havolalar --}}
        <div class="bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-white/[0.06] shadow-card p-5">
            <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">Tezkor havolalar</h4>
            <div class="space-y-2">
                <a href="{{ route('admin.groups.show', $group->id) }}"
                   class="flex items-center gap-2 px-3 py-2.5 text-sm text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-900/50 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:text-indigo-700 dark:hover:text-indigo-400 rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Dars jadvalini ko'rish
                </a>
                <a href="{{ route('admin.groups.index') }}"
                   class="flex items-center gap-2 px-3 py-2.5 text-sm text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-900/50 hover:bg-indigo-50 dark:hover:bg-indigo-500/10 hover:text-indigo-700 dark:hover:text-indigo-400 rounded-xl transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Barcha guruhlar
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function calcDuration() {
        const start = document.getElementById('start_date').value;
        const end   = document.getElementById('end_date').value;
        const badge = document.getElementById('duration-badge');
        const text  = document.getElementById('duration-text');

        if (start && end) {
            const diff = Math.round((new Date(end) - new Date(start)) / (1000 * 60 * 60 * 24));
            if (diff > 0) {
                const months = Math.floor(diff / 30);
                const days   = diff % 30;
                let label    = diff + ' kun';
                if (months > 0) label = months + ' oy ' + (days > 0 ? days + ' kun ' : '') + '(' + diff + ' kun)';
                text.textContent = 'O\'quv muddati: ' + label;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
    }

    function setDuration(days) {
        const start = document.getElementById('start_date').value;
        if (!start) { alert('Avval boshlanish sanasini kiriting.'); return; }
        const s  = new Date(start);
        s.setDate(s.getDate() + days);
        document.getElementById('end_date').value = s.toISOString().split('T')[0];
        calcDuration();
    }

    document.getElementById('start_date').addEventListener('change', calcDuration);
    document.getElementById('end_date').addEventListener('change', calcDuration);

    // Sahifa yuklanganda hisoblash
    calcDuration();
</script>
@endpush
