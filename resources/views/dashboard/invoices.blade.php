@extends('layouts.app')

@section('title', 'Master Data: Invoices — Tanos ERP')

@section('content')
<div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
    <div class="flex items-center space-x-3 mb-4">
        <div class="p-2 bg-rose-50 text-rose-600 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Master Data: Invoices</h1>
            <p class="text-sm text-slate-400">Rekapitulasi tagihan P2P dan Non-P2P.</p>
        </div>
    </div>
    
    <div class="mt-6 border-t border-slate-100 pt-6">
        <div class="rounded-xl bg-slate-50 border border-slate-200 p-6 text-center">
            <p class="text-sm font-medium text-slate-600">Rekaman Invoice financial masih kosong.</p>
        </div>
    </div>
</div>
@endsection