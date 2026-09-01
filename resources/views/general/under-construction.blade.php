@extends('layouts.app')

@section('title', 'Fitur Dalam Pengembangan — Tanos ERP')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[500px] bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-xl p-8 shadow-sm text-center">
    <div class="relative mb-6">
        <!-- Glowing background decoration -->
        <div class="absolute inset-0 bg-blue-500/10 dark:bg-blue-500/5 rounded-full blur-2xl w-24 h-24 mx-auto"></div>
        
        <!-- Animated visual icon -->
        <div class="relative p-5 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-xl inline-block animate-pulse">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.83-5.83M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766l3.963-1.188a.75.75 0 0 0 .5-.5l1.188-3.963c.14-.468-.102-.89-.486-1.208l-3.03-2.496A6.745 6.745 0 0 0 11.42 1.5c-3.728 0-6.75 3.022-6.75 6.75 0 1.62.57 3.107 1.527 4.275l-4.44 4.44a1.875 1.875 0 1 0 2.652 2.652l4.44-4.44a6.71 6.71 0 0 0 2.57.953Z" />
            </svg>
        </div>
    </div>
    
    <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-2">Halaman Sedang Dalam Pengembangan</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-md mb-8 leading-relaxed">
        Modul ini sedang dalam tahap pengembangan dan penyempurnaan sistem oleh tim pengembang. Fitur ini akan segera tersedia.
    </p>
    
    <a href="{{ route('dashboard.index') }}" class="inline-flex items-center space-x-2 bg-primary hover:bg-primary-hover text-white font-semibold text-xs px-5 py-3 rounded-xl shadow-md shadow-primary transition-all duration-150 cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        <span>Kembali ke Dashboard</span>
    </a>
</div>
@endsection
