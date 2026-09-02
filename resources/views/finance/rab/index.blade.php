@extends('layouts.app')

@section('title', 'RAB Budget List — Tanos ERP')

@section('content')
<div class="p-6 bg-white dark:bg-slate-900 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm w-full">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-blue-50 dark:bg-blue-950/30 text-blue-650 dark:text-blue-400 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Rencana Anggaran Biaya (RAB)</h1>
                <p class="text-xs text-slate-400 dark:text-slate-550 font-semibold">Kelola locking budget proyek Pelindo dan sinkronisasi ke SAP.</p>
            </div>
        </div>
    </div>

    <!-- Data Table Section -->
    <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
        <table class="w-full text-left border-collapse min-w-[1200px]">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                    <th class="p-4 align-middle">No. Dokumen RAB</th>
                    <th class="p-4 align-middle">Kode Proyek (SAP)</th>
                    <th class="p-4 align-middle">Nama Proyek / Pelanggan</th>
                    <th class="p-4 align-middle text-center">Tahun</th>
                    <th class="p-4 align-middle text-right">Total Pendapatan</th>
                    <th class="p-4 align-middle text-right">Total Biaya (Cost)</th>
                    <th class="p-4 align-middle text-center">SAP Status</th>
                    <th class="p-4 align-middle text-center">Doc Status</th>
                    <th class="p-4 align-middle text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-350">
                @forelse($projects as $proj)
                @php $rab = $proj->rabBudget; @endphp
                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                    <td class="p-4 align-middle font-semibold text-slate-800 dark:text-slate-200">
                        {{ $rab->document_number ?? 'Draft (Belum Dibuat)' }}
                    </td>
                    <td class="p-4 align-middle font-mono font-bold text-blue-650 dark:text-blue-400 text-[13px] whitespace-nowrap">
                        {{ $proj->project_code ?? '-' }}
                    </td>
                    <td class="p-4 align-middle">
                        <span class="block font-bold text-slate-800 dark:text-slate-100">{{ $proj->project_name ?? 'N/A' }}</span>
                        <span class="block text-xs text-slate-400 dark:text-slate-550 mt-0.5">{{ $proj->customer_name ?? '-' }}</span>
                    </td>
                    <td class="p-4 align-middle text-center font-semibold">
                        {{ $rab->year ?? date('Y') }}
                    </td>
                    <td class="p-4 align-middle text-right font-bold text-emerald-600 dark:text-emerald-400">
                        Rp {{ number_format($rab->total_revenue ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="p-4 align-middle text-right font-bold text-slate-900 dark:text-slate-100">
                        Rp {{ number_format($rab->total_cost ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="p-4 align-middle text-center whitespace-nowrap">
                        @if($rab && $rab->sap_status == 'Sent')
                            <span class="inline-block px-2.5 py-1 bg-emerald-55/10 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400 rounded-md text-xs font-bold">
                                Sent to SAP
                            </span>
                        @else
                            <span class="inline-block px-2.5 py-1 bg-amber-55/10 text-amber-600 dark:bg-amber-950/30 dark:text-amber-400 rounded-md text-xs font-bold">
                                Draft
                            </span>
                        @endif
                    </td>
                    <td class="p-4 align-middle text-center whitespace-nowrap">
                        @if($rab && $rab->doc_status == 'Approved')
                            <span class="inline-block px-2.5 py-1 bg-blue-55/10 text-blue-600 dark:bg-blue-950/30 dark:text-blue-400 rounded-md text-xs font-bold">
                                Approved
                            </span>
                        @elseif($rab && $rab->doc_status == 'Voided')
                            <span class="inline-block px-2.5 py-1 bg-rose-55/10 text-rose-600 dark:bg-rose-950/30 dark:text-rose-400 rounded-md text-xs font-bold">
                                Voided
                            </span>
                        @else
                            <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 rounded-md text-xs font-bold">
                                Draft
                            </span>
                        @endif
                    </td>
                    <td class="p-4 align-middle text-center whitespace-nowrap">
                        <div class="flex items-center justify-center">
                            <a href="{{ route('rab.show', $proj->id) }}" 
                               style="background-color: #0091ea; color: #ffffff;"
                               class="p-2 rounded-lg hover:opacity-90 transition shadow-2xs flex items-center justify-center cursor-pointer" title="Detail Anggaran">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="p-12 text-center">
                        <div class="rounded-xl bg-slate-50 dark:bg-slate-800/10 border border-slate-200 dark:border-slate-800 p-6 max-w-md mx-auto">
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Belum ada proyek terdaftar.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

