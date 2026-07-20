@extends('layouts.app')

@section('title', 'User Management — Tanos ERP')

@section('content')
<div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800/80 shadow-sm">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-violet-50 dark:bg-violet-950/30 text-violet-600 dark:text-violet-400 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0 1 10.089 18H9.91A11.386 11.386 0 0 1 5 19.237v-.11c0-1.113.285-2.16.786-3.07M15 7.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5-3a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 9a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">User Management</h1>
                <p class="text-sm text-slate-400 dark:text-slate-500">Pengelolaan pengguna, hak akses, dan pengaturan role.</p>
            </div>
        </div>
        <button class="bg-violet-600 hover:bg-violet-700 text-white px-4 py-2 rounded-xl text-xs font-bold transition-all duration-150 shadow-sm flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tambah User</span>
        </button>
    </div>
    
    <div class="mt-6 border-t border-slate-100 dark:border-slate-800/80 pt-6">
        <div class="rounded-xl bg-slate-50 dark:bg-slate-800/10 border border-slate-200 dark:border-slate-800/80 p-6 text-center">
            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Daftar user administrator dan staf masih kosong.</p>
        </div>
    </div>
</div>
@endsection
