@props(['type' => 'default'])
@php
    $cls = match($type) {
        'success', 'active', 'completed', 'passed'
            => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10 dark:bg-emerald-500/10 dark:text-emerald-400 dark:ring-emerald-400/20',
        'warning', 'pending', 'upcoming'
            => 'bg-amber-50 text-amber-700 ring-amber-600/10 dark:bg-amber-500/10 dark:text-amber-400 dark:ring-amber-400/20',
        'danger', 'failed', 'blocked', 'expelled'
            => 'bg-red-50 text-red-700 ring-red-600/10 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-400/20',
        'info', 'ongoing'
            => 'bg-blue-50 text-blue-700 ring-blue-600/10 dark:bg-blue-500/10 dark:text-blue-400 dark:ring-blue-400/20',
        'inactive', 'cancelled'
            => 'bg-slate-100 text-slate-600 ring-slate-500/10 dark:bg-slate-700 dark:text-slate-400 dark:ring-slate-500/20',
        default
            => 'bg-violet-50 text-violet-700 ring-violet-600/10 dark:bg-violet-500/10 dark:text-violet-400 dark:ring-violet-400/20',
    };

    $dot = match($type) {
        'success', 'active', 'completed', 'passed' => 'bg-emerald-500',
        'warning', 'pending', 'upcoming'            => 'bg-amber-500',
        'danger', 'failed', 'blocked', 'expelled'   => 'bg-red-500',
        'info', 'ongoing'                           => 'bg-blue-500',
        'inactive', 'cancelled'                     => 'bg-slate-400',
        default                                     => 'bg-violet-500',
    };
@endphp
<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold ring-1 ring-inset {{ $cls }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $dot }} flex-shrink-0"></span>
    {{ $slot }}
</span>
