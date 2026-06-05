@extends('layouts.app')
@section('title', $student->user->name)
@section('breadcrumbs')
    <a href="{{ route('instructor.students.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200">O'quvchilarim</a>
    <span class="mx-2">/</span>
    <span class="text-slate-900 dark:text-white font-medium">{{ $student->user->name }}</span>
@endsection

@section('content')
<!-- Profile header -->
<div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm mb-6">
    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
        <img src="{{ $student->user->avatar_url }}" class="w-16 h-16 rounded-2xl object-cover" alt="">
        <div class="flex-1">
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ $student->user->name }}</h1>
                <x-badge :type="$student->status">
                    {{ match($student->status) { 'active' => 'Faol', 'inactive' => 'Nofaol', 'completed' => 'Tugatdi', 'expelled' => 'Chiqarildi', default => $student->status } }}
                </x-badge>
            </div>
            <p class="text-sm text-slate-500 mt-1">
                {{ $student->student_number }} · {{ $student->license_category }} toifa · {{ $student->user->phone }}
            </p>
            @if($student->user->email)
            <p class="text-sm text-slate-500">{{ $student->user->email }}</p>
            @endif
        </div>
    </div>

    <!-- Quick stats -->
    @php
        $presentCount = $student->attendances->where('status', 'present')->count();
        $absentCount = $student->attendances->where('status', 'absent')->count();
        $lateCount = $student->attendances->where('status', 'late')->count();
        $totalAttend = $student->attendances->count();
        $attendRate = $totalAttend > 0 ? round(($presentCount / $totalAttend) * 100) : 0;
        $avgGrade = $student->grades->count() > 0 ? round($student->grades->avg('score'), 1) : null;
        $paidAmount = $student->payments->sum('amount');
        $debtAmount = max(0, ($student->total_fee ?? 0) - $paidAmount);
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-6 border-t border-slate-100 dark:border-slate-700/50">
        <div class="text-center">
            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $attendRate }}%</p>
            <p class="text-xs text-slate-500 mt-0.5">Davomat</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold {{ $absentCount > 0 ? 'text-red-500' : 'text-slate-900 dark:text-white' }}">{{ $absentCount }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Sababsiz</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $avgGrade !== null ? $avgGrade : '—' }}</p>
            <p class="text-xs text-slate-500 mt-0.5">O'rtacha ball</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold {{ $debtAmount > 0 ? 'text-red-500' : 'text-green-500' }}">
                {{ $debtAmount > 0 ? number_format($debtAmount, 0, '.', ' ') : '0' }}
            </p>
            <p class="text-xs text-slate-500 mt-0.5">Qarz (so'm)</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Davomat statistikasi -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm">
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/50">
            Davomat statistikasi
        </h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-slate-600 dark:text-slate-400">Keldi</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-32 h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-green-500 rounded-full" style="width: {{ $totalAttend > 0 ? round(($presentCount/$totalAttend)*100) : 0 }}%"></div>
                    </div>
                    <span class="text-sm font-semibold text-slate-900 dark:text-white w-6 text-right">{{ $presentCount }}</span>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    <span class="text-sm text-slate-600 dark:text-slate-400">Kelmadi</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-32 h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-red-500 rounded-full" style="width: {{ $totalAttend > 0 ? round(($absentCount/$totalAttend)*100) : 0 }}%"></div>
                    </div>
                    <span class="text-sm font-semibold text-slate-900 dark:text-white w-6 text-right">{{ $absentCount }}</span>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-amber-500 rounded-full"></div>
                    <span class="text-sm text-slate-600 dark:text-slate-400">Kech keldi</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-32 h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full" style="width: {{ $totalAttend > 0 ? round(($lateCount/$totalAttend)*100) : 0 }}%"></div>
                    </div>
                    <span class="text-sm font-semibold text-slate-900 dark:text-white w-6 text-right">{{ $lateCount }}</span>
                </div>
            </div>
            <div class="pt-2 border-t border-slate-100 dark:border-slate-700/50 flex justify-between">
                <span class="text-sm text-slate-500">Jami darslar</span>
                <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $totalAttend }}</span>
            </div>
        </div>
    </div>

    <!-- Baholar ro'yxati -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/50 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Baholar</h3>
            <span class="text-xs text-slate-500">{{ $student->grades->count() }} ta</span>
        </div>
        @forelse($student->grades->sortByDesc('created_at')->take(10) as $grade)
        <div class="flex items-center gap-3 px-6 py-3 border-b border-slate-50 dark:border-slate-700/30 last:border-0">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm shrink-0
                {{ ($grade->score ?? 0) >= 90 ? 'bg-green-100 dark:bg-green-900/30 text-green-600' :
                   (($grade->score ?? 0) >= 70 ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600' :
                   (($grade->score ?? 0) >= 50 ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600' : 'bg-red-100 dark:bg-red-900/30 text-red-600')) }}">
                {{ $grade->score ?? '—' }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ $grade->subject ?? $grade->title ?? 'Baho' }}</p>
                <p class="text-xs text-slate-500">
                    {{ $grade->created_at?->format('d.m.Y') ?? '—' }}
                    @if($grade->grade_type) · {{ $grade->grade_type }} @endif
                </p>
            </div>
            @if(isset($grade->max_score) && $grade->max_score)
            <span class="text-xs text-slate-500">/ {{ $grade->max_score }}</span>
            @endif
        </div>
        @empty
        <div class="px-6 py-8 text-center">
            <p class="text-sm text-slate-500">Hali baholar yo'q</p>
        </div>
        @endforelse
    </div>

    <!-- To'lovlar xulosasi -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-100 dark:border-slate-700/50 shadow-sm lg:col-span-2">
        <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/50">
            To'lovlar xulosasi
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-xl p-4">
                <p class="text-xs text-slate-500 font-medium">Umumiy to'lov</p>
                <p class="text-lg font-bold text-slate-900 dark:text-white mt-1">{{ number_format($student->total_fee ?? 0, 0, '.', ' ') }} so'm</p>
            </div>
            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4">
                <p class="text-xs text-slate-500 font-medium">To'langan</p>
                <p class="text-lg font-bold text-green-600 mt-1">{{ number_format($paidAmount, 0, '.', ' ') }} so'm</p>
            </div>
            <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-4">
                <p class="text-xs text-slate-500 font-medium">Qarz</p>
                <p class="text-lg font-bold {{ $debtAmount > 0 ? 'text-red-500' : 'text-green-600' }} mt-1">{{ number_format($debtAmount, 0, '.', ' ') }} so'm</p>
            </div>
        </div>

        @if($student->payments->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-700/50">
                        <th class="px-3 py-2 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Kvitansiya</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Turi</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase">Miqdori</th>
                        <th class="px-3 py-2 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase hidden sm:table-cell">Sana</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-700/30">
                    @foreach($student->payments->sortByDesc('payment_date') as $payment)
                    <tr>
                        <td class="px-3 py-2.5 font-mono text-xs text-slate-500">{{ $payment->receipt_number }}</td>
                        <td class="px-3 py-2.5 text-slate-600 dark:text-slate-400">{{ $payment->type_name }}</td>
                        <td class="px-3 py-2.5 font-semibold text-green-600">{{ number_format($payment->amount, 0, '.', ' ') }} so'm</td>
                        <td class="px-3 py-2.5 text-slate-500 hidden sm:table-cell">{{ $payment->payment_date?->format('d.m.Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-sm text-slate-500 text-center py-4">Hali to'lovlar yo'q</p>
        @endif
    </div>
</div>
@endsection
