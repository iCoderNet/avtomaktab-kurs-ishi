@extends('layouts.app')
@section('title', 'Darslarim')
@section('breadcrumbs')
    <a href="{{ route('student.dashboard') }}" class="hover:text-slate-700">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Darslarim</span>
@endsection
@section('content')
<x-page-header title="Dars Jadvalim" />
<div class="mb-4">
    <form method="GET" class="flex gap-3">
        <select name="status" onchange="this.form.submit()" class="px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            <option value="">Barcha darslar</option>
            <option value="scheduled" {{ request('status')==='scheduled'?'selected':'' }}>Rejalashtirilgan</option>
            <option value="completed" {{ request('status')==='completed'?'selected':'' }}>Tugatilgan</option>
            <option value="missed" {{ request('status')==='missed'?'selected':'' }}>O'tkazib yuborilgan</option>
        </select>
    </form>
</div>
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
    @if($lessons->count())
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-100 dark:border-slate-700/50">
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Sana</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Turi</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase hidden sm:table-cell">Instruktor</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase hidden md:table-cell">Davomat</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Holat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-slate-700/30">
                @foreach($lessons as $lesson)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $lesson->date->format('d.m.Y') }}</p>
                        <p class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($lesson->end_time)->format('H:i') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-slate-600 dark:text-slate-400">{{ $lesson->type === 'theory' ? 'Nazariy' : 'Amaliy' }}</span>
                    </td>
                    <td class="px-6 py-4 hidden sm:table-cell">
                        <span class="text-sm text-slate-600 dark:text-slate-400">{{ $lesson->instructor->user->name }}</span>
                    </td>
                    <td class="px-6 py-4 hidden md:table-cell">
                        @if($lesson->attendance)
                        <x-badge :type="$lesson->attendance->status">
                            {{ match($lesson->attendance->status) { 'present'=>'Keldi', 'absent'=>'Kelmadi', 'late'=>'Kech', 'excused'=>'Sababli', default=>$lesson->attendance->status } }}
                        </x-badge>
                        @else
                        <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <x-badge :type="$lesson->status">
                            {{ match($lesson->status) { 'scheduled'=>'Rejalashtirilgan', 'completed'=>'Tugadi', 'missed'=>"O'tkazib", 'cancelled'=>'Bekor', default=>$lesson->status } }}
                        </x-badge>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700/50">{{ $lessons->links() }}</div>
    @else
    <x-empty-state title="Darslar topilmadi" />
    @endif
</div>
@endsection
