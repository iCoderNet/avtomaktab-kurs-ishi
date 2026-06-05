@extends('layouts.app')
@section('title', 'Davomat')
@section('breadcrumbs')
    <a href="{{ route('instructor.dashboard') }}" class="hover:text-slate-700">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Davomat</span>
@endsection
@section('content')
<x-page-header title="Davomat Belgilash" description="O'tgan darslar uchun davomat" />
<div class="space-y-4">
    @forelse($lessons as $lesson)
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="font-semibold text-slate-900 dark:text-white">
                    {{ $lesson->date->format('d.m.Y') }} — {{ \Carbon\Carbon::parse($lesson->start_time)->format('H:i') }}
                </p>
                <p class="text-sm text-slate-500">{{ $lesson->type === 'theory' ? 'Nazariy dars' : 'Amaliy dars' }}</p>
            </div>
            <x-badge :type="$lesson->status">{{ $lesson->status === 'scheduled' ? 'Rejalashtirilgan' : 'Tugadi' }}</x-badge>
        </div>
        @if($lesson->status === 'scheduled')
        <form method="POST" action="{{ route('instructor.attendance.store') }}">
            @csrf
            <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
            <div class="space-y-2 mb-4">
                <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-900 rounded-xl">
                    <img src="{{ $lesson->student->user->avatar_url }}" class="w-8 h-8 rounded-full" alt="">
                    <span class="flex-1 text-sm font-medium text-slate-900 dark:text-white">{{ $lesson->student->user->name }}</span>
                    <select name="attendances[{{ $lesson->student_id }}]"
                        class="px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="present">Keldi ✓</option>
                        <option value="absent">Kelmadi ✗</option>
                        <option value="late">Kech keldi</option>
                        <option value="excused">Sabab bilan</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white text-sm font-medium rounded-xl transition">
                Davomatni saqlash
            </button>
        </form>
        @else
        <p class="text-sm text-slate-500">Davomat allaqachon belgilangan.</p>
        @endif
    </div>
    @empty
    <x-empty-state title="Davomatni belgilash kerak bo'lgan darslar yo'q" />
    @endforelse
</div>
@if($lessons->hasPages())
<div class="mt-6">{{ $lessons->links() }}</div>
@endif
@endsection
