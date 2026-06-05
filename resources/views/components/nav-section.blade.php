@props(['title'])
<div class="mb-3 mt-2 first:mt-0">
    <p class="px-3 mb-1 text-[10px] font-semibold uppercase tracking-[0.12em] text-white/25 select-none">
        {{ $title }}
    </p>
    <div class="space-y-0.5">
        {{ $slot }}
    </div>
</div>
