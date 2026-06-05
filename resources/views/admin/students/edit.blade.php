@extends('layouts.app')
@section('title', "O'quvchini tahrirlash")
@section('breadcrumbs')
    <a href="{{ route('admin.students.index') }}" class="hover:text-slate-700">O'quvchilar</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Tahrirlash</span>
@endsection
@section('content')
<x-page-header title="{{ $student->user->name }} ni tahrirlash" />
<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.students.update', $student) }}" class="space-y-6">
        @csrf @method('PUT')
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Shaxsiy ma'lumotlar</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">To'liq ism *</label>
                    <input type="text" name="name" value="{{ old('name', $student->user->name) }}" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $student->user->email) }}" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Telefon</label>
                    <input type="text" name="phone" value="{{ old('phone', $student->user->phone) }}" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Holat</label>
                    <select name="status" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        @foreach(['active' => 'Faol', 'inactive' => 'Nofaol', 'completed' => 'Tugatdi', 'expelled' => 'Chiqarildi'] as $val => $label)
                        <option value="{{ $val }}" {{ $student->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Toifa</label>
                    <select name="license_category" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        @foreach(['A','B','C','D'] as $cat)
                        <option value="{{ $cat }}" {{ $student->license_category === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Instruktor</label>
                    <select name="instructor_id" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        <option value="">—</option>
                        @foreach($instructors as $i)
                        <option value="{{ $i->id }}" {{ $student->instructor_id == $i->id ? 'selected' : '' }}>{{ $i->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Jami to'lov</label>
                    <input type="number" name="total_fee" value="{{ $student->total_fee }}" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                </div>
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition">Saqlash</button>
            <a href="{{ route('admin.students.show', $student) }}" class="px-6 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition">Bekor</a>
        </div>
    </form>
</div>
@endsection
