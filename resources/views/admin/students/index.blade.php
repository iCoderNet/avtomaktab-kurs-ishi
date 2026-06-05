@extends('layouts.app')
@section('title', "O'quvchilar")
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">O'quvchilar</span>
@endsection

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <x-page-header title="O'quvchilar ro'yxati" :description="$students->total() . ' ta o\'quvchi'" />
    <a href="{{ route('admin.students.create') }}"
        class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition shadow-lg shadow-indigo-500/25">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Yangi o'quvchi
    </a>
</div>

<!-- Filter -->
<div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-100 dark:border-slate-700/50 shadow-sm mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Ism, telefon yoki raqam..."
            class="flex-1 min-w-48 px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        <select name="status" class="px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            <option value="">Barcha holat</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Faol</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nofaol</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Tugatdi</option>
            <option value="expelled" {{ request('status') === 'expelled' ? 'selected' : '' }}>Chiqarildi</option>
        </select>
        <select name="category" class="px-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            <option value="">Barcha toifa</option>
            @foreach(['A','B','C','D'] as $cat)
            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }} toifa</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm rounded-xl transition">Qidirish</button>
        @if(request()->anyFilled(['search','status','category']))
        <a href="{{ route('admin.students.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition">Tozalash</a>
        @endif
    </form>
</div>

<!-- Table -->
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
    @if($students->count())
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-100 dark:border-slate-700/50">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">O'quvchi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell">Raqam</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden md:table-cell">Instruktor</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden lg:table-cell">To'lov</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Holat</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-slate-700/30">
                @foreach($students as $student)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $student->user->avatar_url }}" class="w-9 h-9 rounded-full" alt="">
                            <div>
                                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $student->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $student->user->phone }} · {{ $student->license_category }} toifa</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 hidden sm:table-cell">
                        <span class="text-sm text-slate-600 dark:text-slate-400 font-mono">{{ $student->student_number }}</span>
                    </td>
                    <td class="px-6 py-4 hidden md:table-cell">
                        <span class="text-sm text-slate-600 dark:text-slate-400">
                            {{ $student->instructor?->user->name ?? '—' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 hidden lg:table-cell">
                        <div>
                            <div class="flex items-center gap-2">
                                <div class="w-20 h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $student->payment_percentage }}%"></div>
                                </div>
                                <span class="text-xs text-slate-500">{{ $student->payment_percentage }}%</span>
                            </div>
                            @if($student->debt > 0)
                                <p class="text-xs text-red-500 mt-0.5">{{ number_format($student->debt, 0, '.', ' ') }} so'm qarz</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <x-badge :type="$student->status">
                            {{ match($student->status) { 'active'=>'Faol', 'inactive'=>'Nofaol', 'completed'=>'Tugatdi', 'expelled'=>'Chiqarildi', default=>$student->status } }}
                        </x-badge>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.students.show', $student) }}"
                                class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition" title="Ko'rish">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.students.edit', $student) }}"
                                class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg transition" title="Tahrirlash">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.students.destroy', $student) }}"
                                onsubmit="return confirm('O\'quvchini o\'chirishni tasdiqlaysizmi?')">
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
        {{ $students->links() }}
    </div>
    @else
    <x-empty-state title="O'quvchilar topilmadi" description="Hali hech bir o'quvchi qo'shilmagan yoki qidiruv shartlari bo'yicha topilmadi.">
        <a href="{{ route('admin.students.create') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-xl hover:bg-indigo-500 transition">
            Birinchi o'quvchini qo'shish
        </a>
    </x-empty-state>
    @endif
</div>
@endsection
