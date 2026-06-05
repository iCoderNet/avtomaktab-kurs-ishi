@extends('layouts.app')
@section('title', 'Sertifikatlar')
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Sertifikatlar</span>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <x-page-header title="Sertifikatlar ro'yxati" :description="$certificates->total() . ' ta sertifikat'" />
    <a href="{{ route('admin.certificates.create') }}"
        class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition shadow-lg shadow-indigo-500/25">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Yangi sertifikat
    </a>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
    @if($certificates->count())
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 dark:border-slate-700/50">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Sertifikat raqami</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">O'quvchi</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell">Toifa</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden md:table-cell">Berilgan sana</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden lg:table-cell">Amal qilish muddati</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Holat</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-slate-700/30">
                @foreach($certificates as $cert)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.certificates.show', $cert) }}"
                            class="font-mono font-semibold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                            {{ $cert->certificate_number }}
                        </a>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <img src="{{ $cert->student->user->avatar_url }}" class="w-7 h-7 rounded-full" alt="">
                            <p class="font-medium text-slate-900 dark:text-white">{{ $cert->student->user->name }}</p>
                        </div>
                    </td>
                    <td class="px-4 py-3 hidden sm:table-cell">
                        <span class="px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs font-medium rounded-full">
                            {{ $cert->license_category }} toifa
                        </span>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        <span class="text-slate-600 dark:text-slate-400">{{ $cert->issued_date?->format('d.m.Y') ?? '—' }}</span>
                    </td>
                    <td class="px-4 py-3 hidden lg:table-cell">
                        <span class="{{ $cert->expiry_date?->isPast() ? 'text-red-500' : 'text-slate-600 dark:text-slate-400' }}">
                            {{ $cert->expiry_date?->format('d.m.Y') ?? '—' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <x-badge :type="match($cert->status ?? 'active') { 'active' => 'success', 'expired' => 'danger', 'revoked' => 'danger', default => 'default' }">
                            {{ match($cert->status ?? 'active') { 'active' => 'Faol', 'expired' => 'Muddati o\'tgan', 'revoked' => 'Bekor qilingan', default => $cert->status ?? '—' } }}
                        </x-badge>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.certificates.show', $cert) }}"
                                class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition" title="Ko'rish">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.certificates.download', $cert) }}"
                                class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition" title="PDF yuklab olish">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.certificates.destroy', $cert) }}"
                                onsubmit="return confirm('Sertifikatni o\'chirishni tasdiqlaysizmi?')">
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
        {{ $certificates->links() }}
    </div>
    @else
    <x-empty-state title="Sertifikatlar topilmadi" description="Hali hech bir sertifikat berilmagan.">
        <a href="{{ route('admin.certificates.create') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-xl hover:bg-indigo-500 transition">
            Sertifikat berish
        </a>
    </x-empty-state>
    @endif
</div>
@endsection
