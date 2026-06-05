@props(['title', 'value', 'change' => null, 'color' => 'emerald', 'trend' => null])
@php
    $palette = [
        'emerald' => [
            'icon'    => 'text-emerald-500',
            'iconBg'  => 'bg-emerald-500/10',
            'accent'  => 'card-accent-emerald',
            'badge'   => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
        ],
        'indigo' => [
            'icon'    => 'text-indigo-500',
            'iconBg'  => 'bg-indigo-500/10',
            'accent'  => 'card-accent-indigo',
            'badge'   => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-400',
        ],
        'blue' => [
            'icon'    => 'text-blue-500',
            'iconBg'  => 'bg-blue-500/10',
            'accent'  => 'card-accent-blue',
            'badge'   => 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400',
        ],
        'green' => [
            'icon'    => 'text-emerald-500',
            'iconBg'  => 'bg-emerald-500/10',
            'accent'  => 'card-accent-emerald',
            'badge'   => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400',
        ],
        'amber' => [
            'icon'    => 'text-amber-500',
            'iconBg'  => 'bg-amber-500/10',
            'accent'  => 'card-accent-amber',
            'badge'   => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400',
        ],
        'red' => [
            'icon'    => 'text-red-500',
            'iconBg'  => 'bg-red-500/10',
            'accent'  => 'card-accent-red',
            'badge'   => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
        ],
        'purple' => [
            'icon'    => 'text-violet-500',
            'iconBg'  => 'bg-violet-500/10',
            'accent'  => 'card-accent-purple',
            'badge'   => 'bg-violet-50 text-violet-600 dark:bg-violet-500/10 dark:text-violet-400',
        ],
    ];
    $p = $palette[$color] ?? $palette['emerald'];
@endphp

<div class="stat-card bg-white dark:bg-slate-800/60 rounded-2xl p-5
    border border-slate-100 dark:border-white/[0.06]
    {{ $p['accent'] }}
    shadow-card hover:shadow-card-md
    transition-all duration-200 group">

    <div class="flex items-start justify-between gap-3">
        <div class="flex-1 min-w-0">
            <p class="text-[12px] font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wide truncate">
                {{ $title }}
            </p>
            <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1.5 leading-none truncate">
                {{ $value }}
            </p>
        </div>

        <div class="w-10 h-10 rounded-xl {{ $p['iconBg'] }} {{ $p['icon'] }}
            flex items-center justify-center flex-shrink-0
            group-hover:scale-110 transition-transform duration-200">
            {{ $slot }}
        </div>
    </div>

    @if($change !== null)
    <div class="mt-3 pt-3 border-t border-slate-100 dark:border-white/[0.04]">
        <span class="inline-flex items-center gap-1 text-[11px] font-medium px-2 py-0.5 rounded-full {{ $p['badge'] }}">
            @if($trend === 'up')
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
            </svg>
            @elseif($trend === 'down')
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
            @endif
            {{ $change }}
        </span>
    </div>
    @endif
</div>
