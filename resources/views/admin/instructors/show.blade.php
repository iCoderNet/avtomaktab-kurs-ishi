@extends('layouts.app')
@section('title', $instructor->user->name)
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.instructors.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Instruktorlar</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">{{ $instructor->user->name }}</span>
@endsection

@section('content')
<!-- Profile header -->
<div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm mb-6">
    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
        <img src="{{ $instructor->user->avatar_url }}" class="w-16 h-16 rounded-2xl object-cover" alt="">
        <div class="flex-1">
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ $instructor->user->name }}</h1>
                <x-badge :type="match($instructor->status ?? 'active') { 'active' => 'success', 'inactive' => 'default', 'on_leave' => 'warning', default => 'default' }">
                    {{ match($instructor->status ?? 'active') { 'active' => 'Faol', 'inactive' => 'Nofaol', 'on_leave' => 'Tatilda', default => $instructor->status ?? '—' } }}
                </x-badge>
            </div>
            <p class="text-sm text-slate-500 mt-1">
                {{ $instructor->employee_number ?? '' }}
                @if($instructor->user->phone) · {{ $instructor->user->phone }} @endif
                @if($instructor->user->email) · {{ $instructor->user->email }} @endif
            </p>
            @if($instructor->license_categories)
            <div class="flex gap-1 mt-2">
                @foreach($instructor->license_categories as $cat)
                <span class="px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-xs rounded-full font-medium">{{ $cat }}</span>
                @endforeach
            </div>
            @endif
        </div>
        <a href="{{ route('admin.instructors.edit', $instructor) }}"
            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition">
            Tahrirlash
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-6 border-t border-slate-100 dark:border-slate-700/50">
        <div class="text-center">
            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $instructor->experience_years ?? 0 }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Yil tajriba</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold text-slate-900 dark:text-white">
                {{ $instructor->students->where('status', 'active')->count() }}
            </p>
            <p class="text-xs text-slate-500 mt-0.5">Faol o'quvchi</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $instructor->max_students ?? 15 }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Maksimal sig'im</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $instructor->vehicle?->plate_number ?? '—' }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Avtomobil</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Ma'lumotlar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/50">
            Kasbiy ma'lumotlar
        </h3>
        <dl class="space-y-3">
            <div class="flex justify-between">
                <dt class="text-sm text-slate-500">Ishga kirgan sana</dt>
                <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $instructor->hire_date?->format('d.m.Y') ?? '—' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-sm text-slate-500">Haydovchilik guvohnomasi</dt>
                <dd class="text-sm font-medium text-slate-900 dark:text-white font-mono">{{ $instructor->driving_license_number ?? '—' }}</dd>
            </div>
            <div class="flex justify-between">
                <dt class="text-sm text-slate-500">Guvohnoma muddati</dt>
                <dd class="text-sm font-medium {{ $instructor->driving_license_expiry?->isPast() ? 'text-red-500' : 'text-slate-900 dark:text-white' }}">
                    {{ $instructor->driving_license_expiry?->format('d.m.Y') ?? '—' }}
                </dd>
            </div>
            @if($instructor->vehicle)
            <div class="flex justify-between">
                <dt class="text-sm text-slate-500">Avtomobil</dt>
                <dd class="text-sm font-medium text-slate-900 dark:text-white">
                    <a href="{{ route('admin.vehicles.show', $instructor->vehicle) }}" class="hover:text-indigo-600 transition">
                        {{ $instructor->vehicle->plate_number }} — {{ $instructor->vehicle->brand }} {{ $instructor->vehicle->model }}
                    </a>
                </dd>
            </div>
            @endif
            @if($instructor->notes)
            <div class="pt-2 border-t border-slate-100 dark:border-slate-700/50">
                <dt class="text-sm text-slate-500 mb-1">Izoh</dt>
                <dd class="text-sm text-slate-700 dark:text-slate-300">{{ $instructor->notes }}</dd>
            </div>
            @endif
        </dl>
    </div>

    <!-- O'quvchilar ro'yxati -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">O'quvchilar</h3>
            <span class="text-xs text-slate-500">{{ $instructor->students->count() }} ta</span>
        </div>
        @if($instructor->students->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-700/50">
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">O'quvchi</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden sm:table-cell">Raqam</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Holat</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider hidden md:table-cell">Kirdi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700/30">
                    @foreach($instructor->students as $student)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <img src="{{ $student->user->avatar_url }}" class="w-7 h-7 rounded-full" alt="">
                                <a href="{{ route('admin.students.show', $student) }}" class="font-medium text-slate-900 dark:text-white hover:text-indigo-600 transition truncate">
                                    {{ $student->user->name }}
                                </a>
                            </div>
                        </td>
                        <td class="px-4 py-3 hidden sm:table-cell">
                            <span class="font-mono text-xs text-slate-500">{{ $student->student_number }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <x-badge :type="$student->status">
                                {{ match($student->status) { 'active' => 'Faol', 'inactive' => 'Nofaol', 'completed' => 'Tugatdi', 'expelled' => 'Chiqarildi', default => $student->status } }}
                            </x-badge>
                        </td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            <span class="text-xs text-slate-500">{{ $student->enrollment_date?->format('d.m.Y') ?? '—' }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="px-6 py-8 text-center">
            <p class="text-sm text-slate-500">Hali o'quvchilar yo'q</p>
        </div>
        @endif
    </div>
</div>
@endsection
