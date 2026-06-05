@extends('layouts.app')
@section('title', 'Yangi Sertifikat')
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.certificates.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Sertifikatlar</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Yangi berish</span>
@endsection

@section('content')
<x-page-header title="Sertifikat Berish" />

<div class="max-w-xl">
    <form method="POST" action="{{ route('admin.certificates.store') }}" class="space-y-6">
        @csrf

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/50">
                Sertifikat ma'lumotlari
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">O'quvchi *</label>
                    <select name="student_id" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white @error('student_id') border-red-500 @enderror">
                        <option value="">O'quvchi tanlang</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->user->name }} ({{ $student->student_number }})
                        </option>
                        @endforeach
                    </select>
                    @error('student_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Litsenziya toifasi *</label>
                    <select name="license_category" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
                        @foreach(['A', 'B', 'C', 'D'] as $cat)
                        <option value="{{ $cat }}" {{ old('license_category', 'B') === $cat ? 'selected' : '' }}>{{ $cat }} toifa</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Berilgan sana *</label>
                    <input type="date" name="issued_date" value="{{ old('issued_date', now()->format('Y-m-d')) }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white @error('issued_date') border-red-500 @enderror">
                    @error('issued_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Amal qilish muddati</label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date') }}"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Izoh</label>
                    <textarea name="notes" rows="3"
                        class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white resize-none">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition shadow-lg shadow-indigo-500/25">
                Sertifikat berish
            </button>
            <a href="{{ route('admin.certificates.index') }}" class="px-6 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition">
                Bekor qilish
            </a>
        </div>
    </form>
</div>
@endsection
