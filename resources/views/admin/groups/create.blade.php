@extends('layouts.app')

@section('title', 'Yangi guruh yaratish')

@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.groups.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Guruhlar</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">Yangi guruh</span>
@endsection

@section('content')

<div class="mb-6">
    <x-page-header title="Yangi guruh shakllantirish" description="Guruh nomi, instruktor, o'quvchilar va o'quv muddatini belgilang." />
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Asosiy forma --}}
    <div class="xl:col-span-2">
        <div class="bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-white/[0.06] shadow-card">

            @if($errors->any())
            <div class="px-6 pt-6">
                <div class="flex gap-3 p-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-xl">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <ul class="text-sm text-red-700 dark:text-red-400 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <form action="{{ route('admin.groups.store') }}" method="POST" id="createGroupForm">
                @csrf

                {{-- Bo'lim 1: Asosiy ma'lumotlar --}}
                <div class="p-6 space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-white/[0.06]">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Asosiy ma'lumotlar</h3>
                    </div>

                    {{-- Guruh nomi --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                            Guruh nomi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               placeholder="Masalan: B-toifa 2026-1"
                               class="w-full px-4 py-2.5 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-900 border @error('name') border-red-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all placeholder:text-slate-400">
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Instruktor --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                            Instruktor <span class="text-red-500">*</span>
                        </label>
                        <select name="instructor_id"
                                class="w-full px-4 py-2.5 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-900 border @error('instructor_id') border-red-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            <option value="">— Tanlang —</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                    {{ $instructor->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('instructor_id')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Bo'lim 2: O'quv muddati --}}
                <div class="px-6 pb-5 space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-white/[0.06]">
                        <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white">O'quv muddati</h3>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Boshlanish --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                Boshlanish sanasi <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="start_date" id="start_date"
                                   value="{{ old('start_date') }}"
                                   class="w-full px-4 py-2.5 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-900 border @error('start_date') border-red-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            @error('start_date')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tugash --}}
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">
                                Tugash sanasi <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="end_date" id="end_date"
                                   value="{{ old('end_date') }}"
                                   class="w-full px-4 py-2.5 text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-900 border @error('end_date') border-red-400 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            @error('end_date')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Tezkor muddat tugmalari --}}
                    <div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Tezkor tanlov (boshlanish sanasi kiritilgandan so'ng ishlaydi):</p>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" onclick="setDuration(80)"
                                    class="px-3 py-1.5 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-800 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400 transition-all">
                                2 oy 20 kun (80 kun)
                            </button>
                            <button type="button" onclick="setDuration(90)"
                                    class="px-3 py-1.5 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-800 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400 transition-all">
                                3 oy (90 kun)
                            </button>
                            <button type="button" onclick="setDuration(120)"
                                    class="px-3 py-1.5 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-800 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400 transition-all">
                                4 oy (120 kun)
                            </button>
                            <button type="button" onclick="setDuration(180)"
                                    class="px-3 py-1.5 text-xs font-medium rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-800 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400 transition-all">
                                6 oy (180 kun)
                            </button>
                        </div>
                    </div>

                    {{-- Muddat ko'rsatkichi --}}
                    <div id="duration-badge" class="hidden">
                        <div class="flex items-center gap-2 px-4 py-2.5 bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 rounded-xl">
                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-indigo-700 dark:text-indigo-400 font-medium" id="duration-text"></p>
                        </div>
                    </div>
                </div>

                {{-- Bo'lim 3: O'quvchilar --}}
                <div class="px-6 pb-6 space-y-4">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-white/[0.06]">
                        <div class="w-8 h-8 rounded-lg bg-violet-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">O'quvchilar</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Guruhsiz, faol o'quvchilar ro'yxati</p>
                        </div>
                        <span class="ml-auto text-xs font-semibold text-indigo-700 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 px-2 py-1 rounded-lg" id="selected-count">0 ta tanlandi</span>
                    </div>

                    @error('students')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror

                    @if($students->isEmpty())
                        <div class="flex flex-col items-center gap-2 py-8 text-center">
                            <svg class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-slate-500">Mavjud guruhsiz o'quvchilar yo'q</p>
                            <a href="{{ route('admin.students.create') }}" class="text-sm text-indigo-600 hover:underline">Yangi o'quvchi qo'shish →</a>
                        </div>
                    @else
                        {{-- Qidiruv --}}
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" id="studentSearch"
                                   placeholder="Ism yoki pasport raqami bo'yicha qidirish..."
                                   class="w-full pl-9 pr-4 py-2.5 text-sm bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl outline-none focus:ring-2 focus:ring-indigo-500 transition-all placeholder:text-slate-400">
                        </div>

                        {{-- O'quvchilar ro'yxati --}}
                        <div class="border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                            <div class="flex items-center justify-between px-4 py-2.5 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                                <span class="text-xs text-slate-500">Jami {{ $students->count() }} ta o'quvchi</span>
                                <button type="button" onclick="toggleAll()"
                                        class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                                    Hammasini tanlash/bekor qilish
                                </button>
                            </div>
                            <div class="max-h-64 overflow-y-auto divide-y divide-slate-100 dark:divide-slate-700/50" id="studentList">
                                @foreach($students as $student)
                                <label class="student-item flex items-center gap-3 px-4 py-3 hover:bg-indigo-50/50 dark:hover:bg-indigo-500/5 cursor-pointer transition-colors"
                                       data-name="{{ strtolower($student->user->name) }}"
                                       data-passport="{{ strtolower($student->passport_series . $student->passport_number) }}">
                                    <input type="checkbox" name="students[]" value="{{ $student->id }}"
                                           class="student-checkbox w-4 h-4 rounded text-indigo-600 border-slate-300 dark:border-slate-600 focus:ring-indigo-500"
                                           {{ in_array($student->id, old('students', [])) ? 'checked' : '' }}>
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-indigo-700 dark:text-indigo-400 font-semibold text-xs flex-shrink-0">
                                        {{ substr($student->user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ $student->user->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $student->passport_series }}{{ $student->passport_number }}</p>
                                    </div>
                                    <span class="text-xs px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400">
                                        {{ $student->license_category }}-toifa
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Footer tugmalar --}}
                <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/30 border-t border-slate-100 dark:border-white/[0.06] flex items-center gap-3 rounded-b-2xl">
                    <button type="submit"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl shadow-sm shadow-indigo-500/20 transition focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Guruhni saqlash
                    </button>
                    <a href="{{ route('admin.groups.index') }}"
                       class="px-5 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-xl transition">
                        Bekor qilish
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Yon panel: Ko'rsatmalar --}}
    <div class="space-y-4">

        {{-- Qo'llanma --}}
        <div class="bg-white dark:bg-slate-800/60 rounded-2xl border border-slate-100 dark:border-white/[0.06] shadow-card p-5">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h4 class="text-sm font-semibold text-slate-900 dark:text-white">Qo'llanma</h4>
            </div>
            <ol class="space-y-3">
                <li class="flex gap-3">
                    <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">1</span>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Guruh nomini kiriting (masalan: <em>B-2026-1</em>)</p>
                </li>
                <li class="flex gap-3">
                    <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">2</span>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Instruktor va o'quv muddatini tanlang</p>
                </li>
                <li class="flex gap-3">
                    <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">3</span>
                    <p class="text-sm text-slate-600 dark:text-slate-400">O'quvchilarni belgilang</p>
                </li>
                <li class="flex gap-3">
                    <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-xs font-bold flex items-center justify-center flex-shrink-0 mt-0.5">4</span>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Guruh yaratilgandan so'ng dars jadvalini generatsiya qiling</p>
                </li>
            </ol>
        </div>

        {{-- Muhim eslatma --}}
        <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-2xl p-5">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-400 mb-1">Eslatma</p>
                    <p class="text-xs text-amber-700 dark:text-amber-500 leading-relaxed">
                        Ro'yxatda faqat <strong>guruhsiz</strong> va <strong>faol</strong> o'quvchilar ko'rinadi. Bir o'quvchi bir vaqtda faqat bitta guruhda bo'lishi mumkin.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
    // Sana o'zgarishida muddat hisoblash
    function calcDuration() {
        const start = document.getElementById('start_date').value;
        const end   = document.getElementById('end_date').value;
        const badge = document.getElementById('duration-badge');
        const text  = document.getElementById('duration-text');

        if (start && end) {
            const s = new Date(start);
            const e = new Date(end);
            const diff = Math.round((e - s) / (1000 * 60 * 60 * 24));
            if (diff > 0) {
                const months = Math.floor(diff / 30);
                const days   = diff % 30;
                let label    = diff + ' kun';
                if (months > 0) label = months + ' oy ' + (days > 0 ? days + ' kun ' : '') + '(' + diff + ' kun)';
                text.textContent  = 'O\'quv muddati: ' + label;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        } else {
            badge.classList.add('hidden');
        }
    }

    // Tezkor muddat belgilash
    function setDuration(days) {
        const start = document.getElementById('start_date').value;
        if (!start) {
            alert('Avval boshlanish sanasini kiriting.');
            return;
        }
        const s = new Date(start);
        s.setDate(s.getDate() + days);
        const yyyy = s.getFullYear();
        const mm   = String(s.getMonth() + 1).padStart(2, '0');
        const dd   = String(s.getDate()).padStart(2, '0');
        document.getElementById('end_date').value = yyyy + '-' + mm + '-' + dd;
        calcDuration();
    }

    document.getElementById('start_date').addEventListener('change', calcDuration);
    document.getElementById('end_date').addEventListener('change', calcDuration);

    // O'quvchi qidirish
    document.getElementById('studentSearch').addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('.student-item').forEach(function (item) {
            const name     = item.dataset.name || '';
            const passport = item.dataset.passport || '';
            item.style.display = (!q || name.includes(q) || passport.includes(q)) ? '' : 'none';
        });
    });

    // Hammasini tanlash
    function toggleAll() {
        const boxes   = document.querySelectorAll('.student-checkbox');
        const anyUnchecked = Array.from(boxes).some(b => !b.checked);
        boxes.forEach(b => { b.checked = anyUnchecked; });
        updateCount();
    }

    // Tanlangan sonini yangilash
    function updateCount() {
        const count = document.querySelectorAll('.student-checkbox:checked').length;
        document.getElementById('selected-count').textContent = count + ' ta tanlandi';
    }

    document.querySelectorAll('.student-checkbox').forEach(cb => {
        cb.addEventListener('change', updateCount);
    });

    // Sahifa yuklanganda sonni yangilash (old input uchun)
    updateCount();

    // Sahifa yuklanganda sanalar bo'lsa muddat badge'ni ko'rsatish
    if ('{{ old("start_date") }}' && '{{ old("end_date") }}') {
        calcDuration();
    }
</script>
@endpush
