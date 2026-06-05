@props(['title' => "Ma'lumot topilmadi", 'description' => null])
<div class="flex flex-col items-center justify-center py-14 text-center px-4">
    <div class="w-14 h-14 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-4">
        <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
    </div>
    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $title }}</p>
    @if($description)
    <p class="text-xs text-slate-400 mt-1 max-w-xs">{{ $description }}</p>
    @endif
    @if($slot->isNotEmpty())
    <div class="mt-4">{{ $slot }}</div>
    @endif
</div>
