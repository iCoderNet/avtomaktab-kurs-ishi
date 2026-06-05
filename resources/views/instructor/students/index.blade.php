@extends('layouts.app')
@section('title', "Mening O'quvchilarim")
@section('breadcrumbs')
    <a href="{{ route('instructor.dashboard') }}" class="hover:text-slate-700">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Mening o'quvchilarim</span>
@endsection
@section('content')
<x-page-header title="Mening O'quvchilarim" :description="$students->total().' ta faol o\'quvchi'" />
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($students as $student)
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm p-5 hover:shadow-md transition">
        <div class="flex items-center gap-3 mb-4">
            <img src="{{ $student->user->avatar_url }}" class="w-12 h-12 rounded-2xl" alt="">
            <div>
                <p class="font-semibold text-slate-900 dark:text-white">{{ $student->user->name }}</p>
                <p class="text-xs text-slate-500">{{ $student->student_number }} · {{ $student->license_category }} toifa</p>
            </div>
        </div>
        <div class="space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">Davomat:</span>
                <span class="font-medium text-slate-700 dark:text-slate-300">{{ $student->attendance_rate }}%</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">To'lov:</span>
                <span class="{{ $student->debt > 0 ? 'text-red-500' : 'text-green-500' }} font-medium">{{ $student->payment_percentage }}%</span>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-700/50">
            <a href="{{ route('instructor.students.show', $student) }}"
                class="w-full block text-center px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-sm font-medium rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition">
                Batafsil ko'rish
            </a>
        </div>
    </div>
    @empty
    <div class="col-span-3">
        <x-empty-state title="O'quvchilar yo'q" description="Hozircha sizga biron o'quvchi biriktirilmagan." />
    </div>
    @endforelse
</div>
@endsection
