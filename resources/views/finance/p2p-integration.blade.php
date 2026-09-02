@extends('layouts.app')

@section('title', 'P2P Integration Monitor — Tanos ERP')

@section('content')
<div class="space-y-6 w-full" x-data="p2pMonitoring()">
    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 dark:text-slate-400 mb-4.5">
                <a href="{{ route('dashboard.index') }}" class="hover:text-primary dark:hover:text-sky-400 transition flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Home
                </a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span>Monitoring API</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-primary dark:text-sky-400 font-black">P2P Integration</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                P2P Integration & SAP Gateway
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 font-medium">Monitoring sinkronisasi transaksi Procure-to-Pay (P2P), integrasi SAP Project Budget, dan SAP AR Journal.</p>
        </div>

        <form action="{{ route('p2p.sync') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2.5 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center space-x-2 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                <span>Trigger Sync Manual</span>
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-sm font-semibold flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Requests -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Payload Calls</span>
                <span class="p-2 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-800 dark:text-slate-100 mt-2">{{ number_format($totalCalls, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Antrean API P2P terkirim</p>
        </div>

        <!-- Success Rate -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Success Rate</span>
                <span class="p-2 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-2">{{ $successRate }}%</p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ $totalSuccess }} transaksi berhasil</p>
        </div>

        <!-- Failed Calls -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider">Failed / Error</span>
                <span class="p-2 bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </span>
            </div>
            <p class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-2">{{ $totalFailed }}</p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Perlu rekonsiliasi gateway</p>
        </div>

        <!-- Average Latency -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Gateway Latency</span>
                <span class="p-2 bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </span>
            </div>
            <p class="text-2xl font-black text-slate-800 dark:text-slate-100 mt-2">245 ms</p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">SAP RFC API Latency</p>
        </div>
    </div>

    <!-- Filter & Table Container -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
        <!-- Search & Quick Filters -->
        <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex flex-col md:flex-row md:items-center md:justify-between gap-3 bg-slate-50/50 dark:bg-slate-800/30">
            <form method="GET" action="{{ route('p2p.index') }}" class="flex items-center gap-2 flex-1 max-w-md">
                <div class="relative w-full">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari ID Sinkronisasi, Proyek, Layanan SAP..."
                           class="w-full pl-9 pr-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:outline-none focus:border-primary">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                @if($statusFilter)
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                @endif
                <button type="submit" class="px-3 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-600 transition">Cari</button>
            </form>

            <div class="flex items-center gap-1.5 overflow-x-auto text-xs">
                <a href="{{ route('p2p.index') }}" class="px-3 py-1.5 rounded-lg font-bold transition {{ !$statusFilter ? 'bg-primary text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200' }}">Semua ({{ $totalCalls }})</a>
                <a href="{{ route('p2p.index', ['status' => 'SUCCESS', 'search' => $search]) }}" class="px-3 py-1.5 rounded-lg font-bold transition {{ $statusFilter === 'SUCCESS' ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200' }}">Success ({{ $totalSuccess }})</a>
                <a href="{{ route('p2p.index', ['status' => 'FAILED', 'search' => $search]) }}" class="px-3 py-1.5 rounded-lg font-bold transition {{ $statusFilter === 'FAILED' ? 'bg-rose-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200' }}">Failed ({{ $totalFailed }})</a>
            </div>
        </div>

        <!-- Table Data -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/75 dark:bg-slate-800/50 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-4">Sync ID</th>
                        <th class="py-3 px-4">Layanan Target (SAP)</th>
                        <th class="py-3 px-4">Referensi Proyek / Transaksi</th>
                        <th class="py-3 px-4">HTTP Status</th>
                        <th class="py-3 px-4">Waktu Respon</th>
                        <th class="py-3 px-4">Waktu Sinkronisasi</th>
                        <th class="py-3 px-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-200">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                        <td class="py-3.5 px-4 font-mono font-bold text-primary dark:text-sky-400">
                            {{ $log->sync_id }}
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="font-bold text-slate-800 dark:text-slate-100 block">{{ $log->service }}</span>
                            <span class="text-[10px] font-mono text-slate-400 dark:text-slate-500">{{ $log->endpoint }}</span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="font-semibold block truncate max-w-xs">{{ $log->project_name }}</span>
                            <span class="text-[10px] font-mono text-slate-400">{{ $log->project_code }}</span>
                        </td>
                        <td class="py-3.5 px-4">
                            @if($log->status === 'SUCCESS')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                                    {{ $log->http_code }} SUCCESS
                                </span>
                            @elseif($log->status === 'FAILED')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-100 text-rose-800 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-300 dark:border-rose-800">
                                    {{ $log->http_code }} FAILED
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-300 dark:border-amber-800">
                                    PENDING
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 font-mono text-slate-500 dark:text-slate-400">
                            {{ $log->response_time }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400">
                            {{ $log->synced_at }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center">
                                <button @click="showDetail({{ json_encode($log) }})"
                                        style="background-color: #0091ea; color: #ffffff;"
                                        class="p-2 rounded-lg hover:opacity-90 transition shadow-2xs flex items-center justify-center cursor-pointer" title="Detail Log">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">
                            Tidak ada log sinkronisasi P2P yang sesuai kriteria pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- DETAIL LOG MODAL -->
    <div x-show="isDetailOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 rounded-xl max-w-lg w-full p-6 shadow-xl border border-slate-100 dark:border-slate-800" @click.outside="isDetailOpen = false">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Detail Sinkronisasi P2P Gateway</h3>
                <button @click="isDetailOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <template x-if="selectedLog">
                <div class="space-y-3 text-xs">
                    <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400">Sync Identifier:</span>
                        <span class="font-mono font-bold text-primary dark:text-sky-400" x-text="selectedLog.sync_id"></span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400">Target Service:</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200" x-text="selectedLog.service"></span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400">API Endpoint:</span>
                        <span class="font-mono text-[11px] text-slate-600 dark:text-slate-300" x-text="selectedLog.endpoint"></span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400">Referensi Data:</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200" x-text="selectedLog.project_name"></span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-100 dark:border-slate-800">
                        <span class="text-slate-400">Status Response:</span>
                        <span class="font-bold" :class="selectedLog.status === 'SUCCESS' ? 'text-emerald-600' : 'text-rose-600'" x-text="selectedLog.http_code + ' (' + selectedLog.status + ')'"></span>
                    </div>
                    
                    <template x-if="selectedLog.error_message">
                        <div class="p-3 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 rounded-xl border border-rose-200 dark:border-rose-900 mt-2">
                            <span class="font-bold block mb-0.5">Pesan Error Gateway:</span>
                            <span x-text="selectedLog.error_message"></span>
                        </div>
                    </template>
                </div>
            </template>

            <div class="mt-5 pt-3 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                <button @click="isDetailOpen = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-200 font-bold rounded-xl text-xs transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function p2pMonitoring() {
    return {
        isDetailOpen: false,
        selectedLog: null,
        showDetail(log) {
            this.selectedLog = log;
            this.isDetailOpen = true;
        }
    }
}
</script>
@endsection
