@extends('layouts.app')
@section('title', 'Avtomobil Tahrirlash')
@section('breadcrumbs')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Bosh sahifa</a>
    <span class="mx-2">/</span>
    <a href="{{ route('admin.vehicles.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Avtomobillar</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">{{ $vehicle->plate_number }}</span>
@endsection

@section('content')
<x-page-header title="Avtomobilni Tahrirlash" :description="$vehicle->brand . ' ' . $vehicle->model . ' · ' . $vehicle->plate_number" />

<form method="POST" action="{{ route('admin.vehicles.update', $vehicle) }}" class="space-y-6">
    @csrf @method('PUT')

    <!-- Asosiy ma'lumotlar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/50">
            Asosiy ma'lumotlar
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Davlat raqami *</label>
                <input type="text" name="plate_number" value="{{ old('plate_number', $vehicle->plate_number) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white @error('plate_number') border-red-500 @enderror">
                @error('plate_number')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Brend *</label>
                <input type="text" name="brand" value="{{ old('brand', $vehicle->brand) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white @error('brand') border-red-500 @enderror">
                @error('brand')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Model *</label>
                <input type="text" name="model" value="{{ old('model', $vehicle->model) }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white @error('model') border-red-500 @enderror">
                @error('model')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Yili *</label>
                <select name="year" required
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
                    @for($y = 2026; $y >= 1990; $y--)
                    <option value="{{ $y }}" {{ old('year', $vehicle->year) == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Rang</label>
                <input type="text" name="color" value="{{ old('color', $vehicle->color) }}"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Holat</label>
                <select name="status"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
                    <option value="available" {{ old('status', $vehicle->status) === 'available' ? 'selected' : '' }}>Bo'sh</option>
                    <option value="in_use" {{ old('status', $vehicle->status) === 'in_use' ? 'selected' : '' }}>Ishlatilmoqda</option>
                    <option value="maintenance" {{ old('status', $vehicle->status) === 'maintenance' ? 'selected' : '' }}>Ta'mirda</option>
                    <option value="retired" {{ old('status', $vehicle->status) === 'retired' ? 'selected' : '' }}>Hisobdan chiqarilgan</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Yoqilg'i turi</label>
                <select name="fuel_type"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
                    <option value="petrol" {{ old('fuel_type', $vehicle->fuel_type) === 'petrol' ? 'selected' : '' }}>Benzin</option>
                    <option value="diesel" {{ old('fuel_type', $vehicle->fuel_type) === 'diesel' ? 'selected' : '' }}>Dizel</option>
                    <option value="gas" {{ old('fuel_type', $vehicle->fuel_type) === 'gas' ? 'selected' : '' }}>Gaz</option>
                    <option value="electric" {{ old('fuel_type', $vehicle->fuel_type) === 'electric' ? 'selected' : '' }}>Elektr</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Yurgan masofasi (km)</label>
                <input type="number" name="mileage" value="{{ old('mileage', $vehicle->mileage) }}" min="0"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
            </div>
        </div>
    </div>

    <!-- Texnik ma'lumotlar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/50">
            Texnik ma'lumotlar
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Dvigatel raqami</label>
                <input type="text" name="engine_number" value="{{ old('engine_number', $vehicle->engine_number) }}"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Kuzov raqami (VIN)</label>
                <input type="text" name="chassis_number" value="{{ old('chassis_number', $vehicle->chassis_number) }}"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Sug'urta raqami</label>
                <input type="text" name="insurance_number" value="{{ old('insurance_number', $vehicle->insurance_number) }}"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Sug'urta muddati tugashi</label>
                <input type="date" name="insurance_expiry" value="{{ old('insurance_expiry', $vehicle->insurance_expiry?->format('Y-m-d')) }}"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Texosmotr muddati tugashi</label>
                <input type="date" name="inspection_expiry" value="{{ old('inspection_expiry', $vehicle->inspection_expiry?->format('Y-m-d')) }}"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Tayinlangan instruktor</label>
                <select name="assigned_instructor_id"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white">
                    <option value="">Instruktor tanlang</option>
                    @foreach($instructors as $instructor)
                    <option value="{{ $instructor->id }}" {{ old('assigned_instructor_id', $vehicle->assigned_instructor_id) == $instructor->id ? 'selected' : '' }}>
                        {{ $instructor->user->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Izoh</label>
                <textarea name="notes" rows="3"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none dark:text-white resize-none">{{ old('notes', $vehicle->notes) }}</textarea>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium rounded-xl transition shadow-lg shadow-indigo-500/25">
            O'zgarishlarni saqlash
        </button>
        <a href="{{ route('admin.vehicles.show', $vehicle) }}" class="px-6 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition">
            Bekor qilish
        </a>
    </div>
</form>
@endsection
