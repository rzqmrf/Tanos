@extends('layouts.app')

@section('title', 'Reports & Analytics — Tanos ERP')

@section('content')
<div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800/80 shadow-sm">
    <div class="flex items-center space-x-3 mb-4">
        <div class="p-2 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v5.25c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 3 18.375v-5.25ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125v-9.75ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v14.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Reports & Analytics</h1>
            <p class="text-sm text-slate-400 dark:text-slate-500">Analisis komprehensif data proyek, SDM, dan keuangan ERP.</p>
        </div>
    </div>
    
    <div class="mt-6 border-t border-slate-100 dark:border-slate-800/80 pt-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="p-5 border border-slate-100 dark:border-slate-800/80 rounded-xl bg-slate-50/50 dark:bg-slate-800/20">
                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-1">Total Project Cost</span>
                <span class="text-2xl font-bold text-slate-800 dark:text-slate-200">Rp --</span>
                <span class="text-xs text-emerald-600 font-medium block mt-2">↑ Terhitung dari seluruh proyek aktif</span>
            </div>
            <div class="p-5 border border-slate-100 dark:border-slate-800/80 rounded-xl bg-slate-50/50 dark:bg-slate-800/20">
                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-1">Total Employees Active</span>
                <span class="text-2xl font-bold text-slate-800 dark:text-slate-200">-- Orang</span>
                <span class="text-xs text-slate-505 font-medium block mt-2">Tersebar di seluruh regional & segmen</span>
            </div>
            <div class="p-5 border border-slate-100 dark:border-slate-800/80 rounded-xl bg-slate-50/50 dark:bg-slate-800/20">
                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-1">Invoice Realization</span>
                <span class="text-2xl font-bold text-slate-800 dark:text-slate-200">-- %</span>
                <span class="text-xs text-indigo-600 font-medium block mt-2">Menunggu sinkronisasi data tagihan</span>
            </div>
        </div>

        <div class="rounded-xl bg-slate-50 dark:bg-slate-800/10 border border-slate-200 dark:border-slate-800/80 p-8 text-center">
            <div class="w-12 h-12 rounded-full bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800 dark:text-slate-200">Data Report Belum Tersedia</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 max-w-md mx-auto mt-1">Gunakan filter pencarian di navbar atas untuk memperbarui data visualisasi laporan secara real-time.</p>
        </div>
    </div>
</div>
@endsection
