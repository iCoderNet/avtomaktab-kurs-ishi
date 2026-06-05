@extends('layouts.app')
@section('title', 'Yangi Imtihon')
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.exams.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Imtihonlar</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Yangi qo'shish</span>
@endsection

@section('content')
<x-page-header title="Yangi Imtihon Yaratish" />

<form method="POST" action="{{ route('admin.exams.store') }}" class="space-y-6">
    @csrf

    <!-- Asosiy ma'lumotlar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/50">
            Imtihon ma'lumotlari
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Imtihon nomi *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    placeholder="Misol: Nazariy imtihon #1"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white @error('name') border-red-500 @enderror">
                @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Imtihon turi *</label>
                <select name="type" required
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
                    <option value="theory" {{ old('type', 'theory') === 'theory' ? 'selected' : '' }}>Nazariy</option>
                    <option value="practical" {{ old('type') === 'practical' ? 'selected' : '' }}>Amaliy</option>
                    <option value="stop" {{ old('type') === 'stop' ? 'selected' : '' }}>To'xtatish</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Imtihon sanasi</label>
                <input type="date" name="exam_date" value="{{ old('exam_date') }}"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Davomiyligi (daqiqa)</label>
                <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 40) }}" min="1"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">O'tish bali (%)</label>
                <input type="number" name="passing_score" value="{{ old('passing_score', 70) }}" min="0" max="100"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Jami savollar soni</label>
                <input type="number" name="total_questions" value="{{ old('total_questions') }}" min="1"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Maksimal urinishlar soni</label>
                <input type="number" name="max_attempts" value="{{ old('max_attempts', 3) }}" min="1"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Holat</label>
                <select name="status"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Faol</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nofaol</option>
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Tavsif</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white resize-none">{{ old('description') }}</textarea>
            </div>
        </div>
    </div>

    <!-- Savollar bo'limi -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm"
        x-data="{
            questions: {{ old('questions') ? json_encode(old('questions')) : '[]' }},
            addQuestion() {
                this.questions.push({ question: '', options: { A: '', B: '', C: '', D: '' }, correct_answer: 'A' });
            },
            removeQuestion(index) {
                this.questions.splice(index, 1);
            }
        }">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/50">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Test savollari</h3>
            <button type="button" @click="addQuestion()"
                class="flex items-center gap-2 px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-sm font-medium rounded-xl hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Savol qo'shish
            </button>
        </div>

        <div x-show="questions.length === 0" class="text-center py-8">
            <p class="text-sm text-slate-500">Hali savollar qo'shilmagan. "Savol qo'shish" tugmasini bosing.</p>
        </div>

        <div class="space-y-4">
            <template x-for="(q, index) in questions" :key="index">
                <div class="border border-slate-200 dark:border-slate-600 rounded-xl p-4 relative">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300" x-text="'Savol ' + (index + 1)"></span>
                        <button type="button" @click="removeQuestion(index)"
                            class="p-1 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">Savol matni *</label>
                        <textarea
                            :name="'questions[' + index + '][question]'"
                            x-model="q.question"
                            rows="2"
                            required
                            placeholder="Savolni kiriting..."
                            class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                        <template x-for="opt in ['A', 'B', 'C', 'D']" :key="opt">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-500 w-5 shrink-0" x-text="opt + '.'"></span>
                                <input type="text"
                                    :name="'questions[' + index + '][options][' + opt + ']'"
                                    x-model="q.options[opt]"
                                    :placeholder="'Variant ' + opt"
                                    required
                                    class="flex-1 px-3 py-1.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
                            </div>
                        </template>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-1">To'g'ri javob</label>
                        <select
                            :name="'questions[' + index + '][correct_answer]'"
                            x-model="q.correct_answer"
                            class="px-3 py-1.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition shadow-lg shadow-indigo-500/25">
            Imtihon yaratish
        </button>
        <a href="{{ route('admin.exams.index') }}" class="px-6 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition">
            Bekor qilish
        </a>
    </div>
</form>
@endsection
