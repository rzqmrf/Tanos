@extends('layouts.app')

@section('title', 'Project Configuration — Tanos ERP')

@section('content')
<div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
    <div class="flex items-center space-x-3 mb-4">
        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.43l-1.003.774a1.119 1.119 0 0 0-.363.863v.074c0 .273.13.531.363.864l1.003.774a1.125 1.125 0 0 1 .26 1.43l-1.296 2.247a1.125 1.125 0 0 1-1.37.49l-1.216-.456a1.125 1.125 0 0 0-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281a1.125 1.125 0 0 0-.644-.87a6.52 6.52 0 0 1-.22-.127a1.125 1.125 0 0 0-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.43l1.004-.774c.232-.333.362-.591.362-.864v-.074c0-.273-.13-.531-.362-.864l-1.004-.774a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.49l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128c.332-.183.582-.495.644-.869l.214-1.28Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Project Configuration</h1>
            <p class="text-sm text-slate-400">Pengaturan general project Tanos ERP.</p>
        </div>
    </div>
    
    <div class="mt-6 border-t border-slate-100 pt-6">
        <div class="rounded-xl bg-slate-50 border border-slate-200 p-6 text-center">
            <p class="text-sm font-medium text-slate-600">Form konfigurasi sedang dalam pengembangan database lokal.</p>
        </div>
    </div>
</div>
@endsection