@extends('layouts.app')

@section('title', 'Master Data: Projects — Tanos ERP')

@section('content')
<div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
    <div class="flex items-center space-x-3 mb-4">
        <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18m-18 0V6.75A2.25 2.25 0 0 1 4.5 4.5h15a2.25 2.25 0 0 1 2.25 2.25V13.5m-18 0v4.5A2.25 2.25 0 0 0 4.5 20.25h15a2.25 2.25 0 0 0 2.25-2.25V13.5" />
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Master Data: Projects</h1>
            <p class="text-sm text-slate-400">Daftar tracking seluruh project aktif.</p>
        </div>
    </div>
    
    <div class="mt-6 border-t border-slate-100 pt-6">
        <div class="rounded-xl bg-slate-50 border border-slate-200 p-6 text-center">
            <p class="text-sm font-medium text-slate-600">Tabel master data project kosong.</p>
        </div>
    </div>
</div>
@endsection