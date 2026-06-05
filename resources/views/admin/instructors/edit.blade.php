@extends('layouts.app')
@section('title', 'Instruktor Tahrirlash')
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.instructors.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Instruktorlar</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">{{ $instructor->user->name }}</span>
@endsection

@section('content')
<x-page-header title="Instruktorni Tahrirlash" :description="$instructor->user->name" />

<form method="POST" action="{{ route('admin.instructors.update', $instructor) }}" class="space-y-6">
    @csrf @method('PUT')

    <!-- Shaxsiy ma'lumotlar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/50">
            Shaxsiy ma'lumotlar
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">To'liq ism *</label>
                <input type="text" name="name" value="{{ old('name', $instructor->user->name) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white @error('name') border-red-500 @enderror">
                @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email *</label>
                <input type="email" name="email" value="{{ old('email', $instructor->user->email) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white @error('email') border-red-500 @enderror">
                @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Telefon *</label>
                <input type="text" name="phone" value="{{ old('phone', $instructor->user->phone) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                    Yangi parol
                    <span class="text-xs text-slate-400 font-normal">(o'zgartirmasangiz bo'sh qoldiring)</span>
                </label>
                <input type="password" name="password"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white @error('password') border-red-500 @enderror">
                @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Jinsi</label>
                <select name="gender"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
                    <option value="">Tanlanmagan</option>
                    <option value="male" {{ old('gender', $instructor->user->gender) === 'male' ? 'selected' : '' }}>Erkak</option>
                    <option value="female" {{ old('gender', $instructor->user->gender) === 'female' ? 'selected' : '' }}>Ayol</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Ishga kirgan sana</label>
                <input type="date" name="hire_date" value="{{ old('hire_date', $instructor->hire_date?->format('Y-m-d')) }}"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
            </div>
        </div>
    </div>

    <!-- Kasbiy ma'lumotlar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/50">
            Kasbiy ma'lumotlar
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Tajriba (yil)</label>
                <input type="number" name="experience_years" value="{{ old('experience_years', $instructor->experience_years) }}" min="0"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Maosh (so'm)</label>
                <input type="number" name="salary" value="{{ old('salary', $instructor->salary) }}" min="0"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Maksimal o'quvchilar soni</label>
                <input type="number" name="max_students" value="{{ old('max_students', $instructor->max_students ?? 15) }}" min="1"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Haydovchilik guvohnomasi raqami</label>
                <input type="text" name="driving_license_number" value="{{ old('driving_license_number', $instructor->driving_license_number) }}"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Guvohnoma amal qilish muddati</label>
                <input type="date" name="driving_license_expiry" value="{{ old('driving_license_expiry', $instructor->driving_license_expiry?->format('Y-m-d')) }}"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Toifalar</label>
                <div class="flex gap-3 mt-1">
                    @foreach(['A', 'B', 'C', 'D'] as $cat)
                    @php
                        $selected = old('license_categories', $instructor->license_categories ?? []);
                    @endphp
                    <label class="flex items-center gap-1.5 cursor-pointer">
                        <input type="checkbox" name="license_categories[]" value="{{ $cat }}"
                            {{ in_array($cat, $selected) ? 'checked' : '' }}
                            class="w-4 h-4 text-indigo-600 bg-slate-50 dark:bg-slate-900 border-slate-300 dark:border-slate-600 rounded focus:ring-indigo-500">
                        <span class="text-sm text-slate-700 dark:text-slate-300">{{ $cat }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Izoh</label>
                <textarea name="notes" rows="3"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white resize-none">{{ old('notes', $instructor->notes) }}</textarea>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition shadow-lg shadow-indigo-500/25">
            O'zgarishlarni saqlash
        </button>
        <a href="{{ route('admin.instructors.show', $instructor) }}" class="px-6 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition">
            Bekor qilish
        </a>
    </div>
</form>
@endsection
