@extends('layouts.app')

@section('title', 'RAB Budget List — Tanos ERP')

@section('content')
<div class="space-y-6 w-full">
    <!-- Header Section -->
    <x-page-header 
        title="Rencana Anggaran Biaya (RAB)" 
        subtitle="Kelola locking budget proyek Pelindo dan sinkronisasi ke SAP."
        :breadcrumbs="[
            'General' => '#',
            'Finance & Accounting' => '#',
            'RAB Budget' => ''
        ]"
    />

    <!-- Data Table Container -->
    <x-data-card 
        title="RAB Budget - List" 
        :total="count($projects)"
        :show-per-page="false"
        :show-search="false"
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4">No. Dokumen RAB</th>
                        <th class="py-3.5 px-4">Kode Proyek (SAP)</th>
                        <th class="py-3.5 px-4">Nama Proyek / Pelanggan</th>
                        <th class="py-3.5 px-4 text-center">Tahun</th>
                        <th class="py-3.5 px-4 text-right">Total Pendapatan</th>
                        <th class="py-3.5 px-4 text-right">Total Biaya (Cost)</th>
                        <th class="py-3.5 px-4 text-center">SAP Status</th>
                        <th class="py-3.5 px-4 text-center">Doc Status</th>
                        <th class="py-3.5 px-4 text-center w-24">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($projects as $proj)
                    @php $rab = $proj->rabBudget; @endphp
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                        <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-slate-100">
                            {{ $rab->document_number ?? 'Draft (Belum Dibuat)' }}
                        </td>
                        <td class="py-3.5 px-4 font-mono font-bold text-primary text-[11px] whitespace-nowrap">
                            <span class="px-2 py-0.5 bg-primary-light text-primary rounded border border-primary-subtle">
                                {{ $proj->project_code ?? '—' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="block font-bold text-slate-800 dark:text-slate-100">{{ $proj->project_name ?? 'N/A' }}</span>
                            <span class="block text-[10px] text-slate-400 dark:text-slate-400 mt-0.5">{{ $proj->customer_name ?? '—' }}</span>
                        </td>
                        <td class="py-3.5 px-4 text-center font-bold text-slate-700 dark:text-slate-300">
                            {{ $rab->year ?? date('Y') }}
                        </td>
                        <td class="py-3.5 px-4 text-right font-mono font-bold text-emerald-600 dark:text-emerald-400">
                            Rp {{ number_format($rab->total_revenue ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-4 text-right font-mono font-bold text-slate-800 dark:text-slate-100">
                            Rp {{ number_format($rab->total_cost ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            @if($rab && $rab->sap_status == 'Sent')
                                <span class="px-2.5 py-1 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 rounded-lg text-[10px] font-bold inline-flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Sent to SAP
                                </span>
                            @else
                                <span class="px-2.5 py-1 bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800 rounded-lg text-[10px] font-bold inline-flex items-center">
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            @if($rab && $rab->doc_status == 'Approved')
                                <span class="px-2.5 py-1 bg-sky-100 dark:bg-sky-950/60 text-sky-700 dark:text-sky-400 border border-sky-200 dark:border-sky-800 rounded-lg text-[10px] font-bold inline-flex items-center">
                                    Approved
                                </span>
                            @elseif($rab && $rab->doc_status == 'Voided')
                                <span class="px-2.5 py-1 bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800 rounded-lg text-[10px] font-bold inline-flex items-center">
                                    Voided
                                </span>
                            @else
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-lg text-[10px] font-bold inline-flex items-center">
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            <x-action-button type="view" :href="route('rab.show', $proj->id)" title="Detail Anggaran RAB" />
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            Belum ada dokumen RAB yang terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-data-card>
</div>
@endsection
