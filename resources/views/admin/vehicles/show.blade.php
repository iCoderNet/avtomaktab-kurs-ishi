@extends('layouts.app')
@section('title', $vehicle->plate_number)
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.vehicles.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Avtomobillar</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">{{ $vehicle->plate_number }}</span>
@endsection

@section('content')
<!-- Header card -->
<div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm mb-6">
    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
        <div class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center shrink-0">
            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM3 10l1.5-5A2 2 0 016.4 3h11.2a2 2 0 011.9 1.37L21 9.5V17h-2M3 10h18M3 10v7h2"/>
            </svg>
        </div>
        <div class="flex-1">
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-xl font-bold text-slate-900 dark:text-white font-mono">{{ $vehicle->plate_number }}</h1>
                <x-badge :type="match($vehicle->status) { 'available' => 'success', 'in_use' => 'primary', 'maintenance' => 'warning', 'retired' => 'danger', default => 'default' }">
                    {{ match($vehicle->status) { 'available' => "Bo'sh", 'in_use' => 'Ishlatilmoqda', 'maintenance' => "Ta'mirda", 'retired' => 'Hisobdan chiqarilgan', default => $vehicle->status } }}
                </x-badge>
            </div>
            <p class="text-sm text-slate-500 mt-1">{{ $vehicle->brand }} {{ $vehicle->model }} · {{ $vehicle->year }} yil · {{ $vehicle->color }}</p>
            @if($vehicle->assignedInstructor)
            <p class="text-sm text-slate-500 mt-0.5">Instruktor: <span class="text-slate-700 dark:text-slate-300">{{ $vehicle->assignedInstructor->user->name }}</span></p>
            @endif
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.vehicles.edit', $vehicle) }}"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition">
                Tahrirlash
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Ma'lumotlar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/50">
            Avtomobil ma'lumotlari
        </h3>
        <dl class="space-y-3">
            <div class="flex justify-between items-start">
                <dt class="text-sm text-slate-500">Dvigatel raqami</dt>
                <dd class="text-sm font-medium text-slate-900 dark:text-white font-mono">{{ $vehicle->engine_number ?? '—' }}</dd>
            </div>
            <div class="flex justify-between items-start">
                <dt class="text-sm text-slate-500">Kuzov raqami (VIN)</dt>
                <dd class="text-sm font-medium text-slate-900 dark:text-white font-mono">{{ $vehicle->chassis_number ?? '—' }}</dd>
            </div>
            <div class="flex justify-between items-start">
                <dt class="text-sm text-slate-500">Yoqilg'i turi</dt>
                <dd class="text-sm font-medium text-slate-900 dark:text-white">
                    {{ match($vehicle->fuel_type) { 'petrol' => 'Benzin', 'diesel' => 'Dizel', 'gas' => 'Gaz', 'electric' => 'Elektr', default => $vehicle->fuel_type ?? '—' } }}
                </dd>
            </div>
            <div class="flex justify-between items-start">
                <dt class="text-sm text-slate-500">Yurgan masofasi</dt>
                <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $vehicle->mileage ? number_format($vehicle->mileage, 0, '.', ' ') . ' km' : '—' }}</dd>
            </div>
            <div class="flex justify-between items-start">
                <dt class="text-sm text-slate-500">Sug'urta raqami</dt>
                <dd class="text-sm font-medium text-slate-900 dark:text-white">{{ $vehicle->insurance_number ?? '—' }}</dd>
            </div>
            <div class="flex justify-between items-start">
                <dt class="text-sm text-slate-500">Sug'urta muddati</dt>
                <dd class="text-sm font-medium {{ $vehicle->insurance_expiry?->isPast() ? 'text-red-500' : 'text-slate-900 dark:text-white' }}">
                    {{ $vehicle->insurance_expiry ? $vehicle->insurance_expiry->format('d.m.Y') : '—' }}
                    @if($vehicle->insurance_expiry?->isPast())
                        <span class="ml-1 text-xs">(muddati o'tgan)</span>
                    @endif
                </dd>
            </div>
            <div class="flex justify-between items-start">
                <dt class="text-sm text-slate-500">Texosmotr muddati</dt>
                <dd class="text-sm font-medium {{ $vehicle->inspection_expiry?->isPast() ? 'text-red-500' : 'text-slate-900 dark:text-white' }}">
                    {{ $vehicle->inspection_expiry ? $vehicle->inspection_expiry->format('d.m.Y') : '—' }}
                    @if($vehicle->inspection_expiry?->isPast())
                        <span class="ml-1 text-xs">(muddati o'tgan)</span>
                    @endif
                </dd>
            </div>
            @if($vehicle->notes)
            <div class="pt-2 border-t border-slate-100 dark:border-slate-700/50">
                <dt class="text-sm text-slate-500 mb-1">Izoh</dt>
                <dd class="text-sm text-slate-700 dark:text-slate-300">{{ $vehicle->notes }}</dd>
            </div>
            @endif
        </dl>
    </div>

    <!-- So'nggi darslar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">So'nggi darslar</h3>
            <span class="text-xs text-slate-500">{{ $vehicle->lessons->count() }} ta</span>
        </div>
        @forelse($vehicle->lessons->take(5) as $lesson)
        <div class="flex items-center gap-3 px-6 py-3 border-b border-slate-50 dark:border-slate-700/30 last:border-0">
            <div class="w-8 h-8 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-slate-900 dark:text-white truncate">
                    {{ $lesson->student?->user->name ?? 'O\'quvchi' }}
                </p>
                <p class="text-xs text-slate-500">
                    {{ $lesson->lesson_date?->format('d.m.Y') ?? '—' }}
                    @if($lesson->lesson_type)
                        · {{ $lesson->lesson_type === 'theory' ? 'Nazariy' : 'Amaliy' }}
                    @endif
                </p>
            </div>
            @if($lesson->status)
            <x-badge :type="match($lesson->status) { 'completed' => 'success', 'cancelled' => 'danger', 'scheduled' => 'primary', default => 'default' }">
                {{ match($lesson->status) { 'completed' => 'Bajarildi', 'cancelled' => 'Bekor', 'scheduled' => "Rejalashtirilgan", default => $lesson->status } }}
            </x-badge>
            @endif
        </div>
        @empty
        <div class="px-6 py-8 text-center">
            <p class="text-sm text-slate-500">Hali darslar yo'q</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
