@props(['title', 'description' => null, 'actions' => null])
<div class="flex items-start justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $title }}</h1>
        @if($description)
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $description }}</p>
        @endif
    </div>
    @if($actions)
    <div class="flex items-center gap-2 flex-shrink-0">
        {{ $actions }}
    </div>
    @endif
</div>
