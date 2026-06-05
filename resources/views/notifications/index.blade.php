@extends('layouts.app')
@section('title', 'Bildirishnomalar')
@section('breadcrumbs')
    <span class="text-slate-900 dark:text-white font-medium">Bildirishnomalar</span>
@endsection
@section('content')
<div class="flex items-center justify-between mb-6">
    <x-page-header title="Bildirishnomalar" />
    @if(auth()->user()->unreadNotifications->count())
    <form method="POST" action="{{ route('notifications.read-all') }}">
        @csrf
        <button type="submit" class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-sm rounded-xl hover:bg-slate-200 dark:hover:bg-slate-600 transition">
            Barchasini o'qildi deb belgilash
        </button>
    </form>
    @endif
</div>

<div class="space-y-2">
    @forelse($notifications as $notification)
    <div class="bg-white dark:bg-slate-800 rounded-2xl border {{ $notification->read_at ? 'border-slate-100 dark:border-slate-700/50' : 'border-indigo-200 dark:border-indigo-800/50' }} shadow-sm p-5 flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl {{ $notification->read_at ? 'bg-slate-100 dark:bg-slate-700' : 'bg-indigo-100 dark:bg-indigo-900/40' }} flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 {{ $notification->read_at ? 'text-slate-400' : 'text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-slate-900 dark:text-white">
                {{ $notification->data['title'] ?? 'Bildirishnoma' }}
            </p>
            <p class="text-sm text-slate-500 mt-0.5">{{ $notification->data['message'] ?? '' }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
        </div>
        @if(!$notification->read_at)
        <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
            @csrf
            <button type="submit" class="text-xs text-indigo-600 hover:underline">O'qildi</button>
        </form>
        @endif
    </div>
    @empty
    <x-empty-state title="Bildirishnomalar yo'q" description="Hech qanday yangi bildirishnoma yo'q." />
    @endforelse
</div>
@if($notifications->hasPages())
<div class="mt-6">{{ $notifications->links() }}</div>
@endif
@endsection
