@extends('layouts.app')

@section('title', 'Shift Scheduling — Tanos ERP')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[500px] bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-8 shadow-sm text-center">
    <div class="relative mb-6">
        <!-- Glowing background decoration -->
        <div class="absolute inset-0 bg-blue-500/10 dark:bg-blue-500/5 rounded-full blur-2xl w-24 h-24 mx-auto"></div>
        
        <!-- Animated visual icon -->
        <div class="relative p-5 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-3xl inline-block animate-pulse">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>
    </div>
    
    <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 mb-2">Halaman Sedang Dalam Pengembangan</h1>
    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-md mb-8 leading-relaxed">
        Kami sedang mempersiapkan modul Penjadwalan Shift. Fitur ini akan memfasilitasi pembuatan jadwal kerja bergilir, pembagian regu tugas (shift), serta pemantauan ketersediaan kru di pelabuhan secara waktu nyata.
    </p>
    
    <a href="{{ route('dashboard.index') }}" class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-5 py-3 rounded-xl shadow-md shadow-blue-500/10 transition-all duration-150 cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
        </svg>
        <span>Kembali ke Dashboard</span>
    </a>
</div>
@endsection
