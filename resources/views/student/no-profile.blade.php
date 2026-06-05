@extends('layouts.app')
@section('title', 'Profil topilmadi')
@section('breadcrumbs')
  <a href="{{ route('home') }}" class="hover:text-slate-700">Orqaga</a>
  <span class="mx-2">/</span>
  <span class="text-slate-900 dark:text-white font-medium">Profil topilmadi</span>
@endsection
@section('content')
<x-page-header title="Profil topilmadi" />
<div class="bg-white dark:bg-slate-800 rounded-2xl p-8 border border-slate-100 dark:border-slate-700/50 shadow-sm">
  <p class="text-slate-500">Bu sahifa to'liq qurilmoqda...</p>
</div>
@endsection
