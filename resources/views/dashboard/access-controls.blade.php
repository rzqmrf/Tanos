@extends('layouts.app')

@section('title', 'Access Controls — Tanos ERP')

@section('content')
<div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
    <div class="flex items-center space-x-3 mb-4">
        <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Access Controls</h1>
            <p class="text-sm text-slate-400">Manajemen hak akses dan peran pengguna Tanos ERP.</p>
        </div>
    </div>
    
    <div class="mt-6 border-t border-slate-100 pt-6">
        <div class="rounded-xl bg-slate-50 border border-slate-200 p-6 text-center">
            <p class="text-sm font-medium text-slate-600">Fitur Role & Permission sedang disiapkan!</p>
        </div>
    </div>
</div>
@endsection