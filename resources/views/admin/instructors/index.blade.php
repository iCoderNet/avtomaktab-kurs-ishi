@extends('layouts.app')
@section('title', 'Instruktorlar')
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Instruktorlar</span>
@endsection
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <x-page-header title="Instruktorlar ro'yxati" :description="$instructors->total().' ta instruktor'" />
    <a href="{{ route('admin.instructors.create') }}" class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Yangi instruktor
    </a>
</div>
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
    @if($instructors->count())
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-100 dark:border-slate-700/50">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Instruktor</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden sm:table-cell">Toifalar</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden md:table-cell">O'quvchilar</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider hidden lg:table-cell">Tajriba</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Holat</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Amallar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-slate-700/30">
                @foreach($instructors as $instructor)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $instructor->user->avatar_url }}" class="w-9 h-9 rounded-full" alt="">
                            <div>
                                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $instructor->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $instructor->employee_number }} · {{ $instructor->user->phone }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 hidden sm:table-cell">
                        <div class="flex gap-1">
                            @foreach($instructor->license_categories as $cat)
                            <span class="px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs rounded-full font-medium">{{ $cat }}</span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-4 hidden md:table-cell">
                        <span class="text-sm text-slate-600 dark:text-slate-400">
                            {{ $instructor->students->where('status', 'active')->count() }} / {{ $instructor->max_students }}
                        </span>
                    </td>
                    <td class="px-6 py-4 hidden lg:table-cell">
                        <span class="text-sm text-slate-600 dark:text-slate-400">{{ $instructor->experience_years }} yil</span>
                    </td>
                    <td class="px-6 py-4">
                        <x-badge :type="$instructor->status">
                            {{ match($instructor->status) { 'active'=>'Faol', 'inactive'=>'Nofaol', 'on_leave'=>'Tatilda', default=>$instructor->status } }}
                        </x-badge>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.instructors.show', $instructor) }}" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>
                            <a href="{{ route('admin.instructors.edit', $instructor) }}" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700/50">{{ $instructors->links() }}</div>
    @else
    <x-empty-state title="Instruktorlar yo'q">
        <a href="{{ route('admin.instructors.create') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-xl">Qo'shish</a>
    </x-empty-state>
    @endif
</div>
@endsection
