@extends('layouts.app')

@section('title', 'Guruhlar')

@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Guruhlar</span>
@endsection

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <x-page-header title="O'quv guruhlari" :description="$groups->total() . ' ta guruh'" />
    <a href="{{ route('admin.groups.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition shadow-lg shadow-indigo-500/20 flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Yangi guruh
    </a>
</div>

{{-- Flash xabarlar --}}
@if(session('success'))
    <div class="flex items-center gap-3 p-4 mb-6 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-400 rounded-xl text-sm">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="flex items-center gap-3 p-4 mb-6 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-400 rounded-xl text-sm">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('error') }}
    </div>
@endif

{{-- Jadval --}}
<div class="bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-white/[0.06] shadow-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-100 dark:border-white/[0.06] bg-slate-50/50 dark:bg-slate-800/30">
                    <th class="px-5 py-3.5 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Guruh</th>
                    <th class="px-5 py-3.5 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Instruktor</th>
                    <th class="px-5 py-3.5 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Muddat</th>
                    <th class="px-5 py-3.5 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">O'quvchilar</th>
                    <th class="px-5 py-3.5 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">Darslar</th>
                    <th class="px-5 py-3.5 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Holat</th>
                    <th class="px-5 py-3.5 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/[0.04]">
                @forelse($groups as $group)
                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition-colors group/row">
                    {{-- Guruh nomi --}}
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-sm flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $group->name }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">#{{ $group->id }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Instruktor --}}
                    <td class="px-5 py-4">
                        <p class="text-sm text-slate-700 dark:text-slate-300">
                            {{ $group->instructor->user->name ?? '—' }}
                        </p>
                    </td>

                    {{-- Muddat --}}
                    <td class="px-5 py-4">
                        <p class="text-sm text-slate-700 dark:text-slate-300">
                            {{ $group->start_date ? \Carbon\Carbon::parse($group->start_date)->format('d.m.Y') : '—' }}
                        </p>
                        <p class="text-xs text-slate-400 mt-0.5">
                            → {{ $group->end_date ? \Carbon\Carbon::parse($group->end_date)->format('d.m.Y') : '—' }}
                        </p>
                    </td>

                    {{-- O'quvchilar --}}
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 text-sm font-semibold">
                            {{ $group->students_count }}
                        </span>
                    </td>

                    {{-- Darslar --}}
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 text-sm font-semibold">
                            {{ $group->schedules_count }}
                        </span>
                    </td>

                    {{-- Holat --}}
                    <td class="px-5 py-4">
                        @php
                            $statusConfig = [
                                'active'    => ['bg' => 'bg-emerald-50 dark:bg-emerald-500/10', 'text' => 'text-emerald-700 dark:text-emerald-400', 'dot' => 'bg-emerald-500', 'label' => 'Faol'],
                                'completed' => ['bg' => 'bg-blue-50 dark:bg-blue-500/10',   'text' => 'text-blue-700 dark:text-blue-400',   'dot' => 'bg-blue-500',   'label' => 'Tugatildi'],
                                'cancelled' => ['bg' => 'bg-slate-100 dark:bg-slate-700/30', 'text' => 'text-slate-600 dark:text-slate-400', 'dot' => 'bg-slate-400',  'label' => 'Bekor qilindi'],
                            ];
                            $sc = $statusConfig[$group->status] ?? $statusConfig['cancelled'];
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium {{ $sc['bg'] }} {{ $sc['text'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $sc['dot'] }}"></span>
                            {{ $sc['label'] }}
                        </span>
                    </td>

                    {{-- Amallar --}}
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-end gap-2">
                            {{-- Ko'rish --}}
                            <a href="{{ route('admin.groups.show', $group->id) }}"
                               title="Ko'rish / Jadval"
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>

                            {{-- Tahrirlash --}}
                            <a href="{{ route('admin.groups.edit', $group->id) }}"
                               title="Tahrirlash"
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 hover:bg-amber-100 dark:hover:bg-amber-500/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>

                            {{-- O'chirish --}}
                            <button
                               onclick="confirmDelete({{ $group->id }}, '{{ addslashes($group->name) }}')"
                               title="O'chirish"
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>

                            {{-- Yashirin o'chirish formasi --}}
                            <form id="delete-form-{{ $group->id }}"
                                  action="{{ route('admin.groups.destroy', $group->id) }}"
                                  method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-700/40 flex items-center justify-center">
                                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-slate-900 dark:text-white">Guruhlar topilmadi</p>
                            <p class="text-sm text-slate-500">Birinchi guruhni yarating</p>
                            <a href="{{ route('admin.groups.create') }}"
                               class="mt-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition">
                                Yangi guruh
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($groups->hasPages())
    <div class="px-5 py-4 border-t border-slate-100 dark:border-white/[0.06]">
        {{ $groups->links() }}
    </div>
    @endif
</div>

{{-- O'chirish tasdiqlash modali --}}
<div id="delete-modal"
     class="fixed inset-0 z-50 hidden items-center justify-center p-4"
     style="display:none;">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-xl max-w-sm w-full p-6 border border-slate-200 dark:border-white/[0.08]">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-red-100 dark:bg-red-500/15 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-semibold text-slate-900 dark:text-white">Guruhni o'chirish</h3>
                <p class="text-sm text-slate-500 mt-0.5" id="delete-modal-text">Bu amalni qaytarib bo'lmaydi.</p>
            </div>
        </div>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-5">
            Guruh bilan birga barcha dars jadvallari va o'quvchi dars yozuvlari ham o'chiriladi. O'quvchilarning hisobi saqlanib qoladi.
        </p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-xl transition">
                Bekor qilish
            </button>
            <button id="delete-confirm-btn"
                    onclick="submitDelete()"
                    class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-red-600 hover:bg-red-500 rounded-xl transition shadow-sm shadow-red-500/20">
                Ha, o'chirish
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let deleteGroupId = null;

    function confirmDelete(id, name) {
        deleteGroupId = id;
        document.getElementById('delete-modal-text').textContent = '"' + name + '" guruhini o\'chirishni tasdiqlaysizmi?';
        document.getElementById('delete-modal').style.display = 'flex';
    }

    function closeDeleteModal() {
        deleteGroupId = null;
        document.getElementById('delete-modal').style.display = 'none';
    }

    function submitDelete() {
        if (deleteGroupId) {
            document.getElementById('delete-form-' + deleteGroupId).submit();
        }
    }
</script>
@endpush
