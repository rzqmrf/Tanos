@extends('layouts.app')

@section('title', 'Payroll Tagihan TAD — Tanos ERP')

@section('content')
<div class="space-y-6 w-full" x-data="payrollBilling()">
    <!-- Header & Action -->
    <x-page-header 
        title="Payroll Tagihan Tenaga Alih Daya (TAD)" 
        subtitle="Pengelolaan penagihan payroll TAD ke pelanggan/prinsipal, penerbitan Pranota, dan posting Billing ke SAP AR."
        :breadcrumbs="[
            'General' => '#',
            'Finance & Billing' => '#',
            'Payroll Tagihan' => ''
        ]"
    >
        <x-slot:action>
            <button onclick="window.print()" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 cursor-pointer border-0 print:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.656h10.5Z"/></svg>
                <span>Cetak Rekap</span>
            </button>
        </x-slot:action>
    </x-page-header>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-sm font-semibold flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Tagihan -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Tagihan TAD</span>
                <span class="p-2 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <p class="text-xl font-black text-slate-800 dark:text-slate-100 mt-2">Rp {{ number_format($totalNominal, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">{{ $totalAll }} berkas tagihan proyek</p>
        </div>

        <!-- Total TAD Count -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Total Tenaga Kerja TAD</span>
                <span class="p-2 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </span>
            </div>
            <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-2">{{ number_format($totalTad, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Personel TAD aktif tertagih</p>
        </div>

        <!-- Status Billed -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-purple-600 dark:text-purple-400 uppercase tracking-wider">Billed to SAP AR</span>
                <span class="p-2 bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </span>
            </div>
            <p class="text-2xl font-black text-purple-600 dark:text-purple-400 mt-2">{{ $totalBilled }}</p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">Invoice SAP telah terbit</p>
        </div>

        <!-- Alur Proses -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Integrasi Alur LPJ</span>
                <span class="p-2 bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 rounded-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </span>
            </div>
            <p class="text-xs font-bold text-slate-700 dark:text-slate-300 mt-2.5">Payroll ➔ Pranota ➔ Billing AR</p>
            <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1">End-to-End ISMA Pelindo Flow</p>
        </div>
    </div>

    <!-- Filter & Table Container -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs overflow-hidden">
        <!-- Search & Quick Filters -->
        <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex flex-col md:flex-row md:items-center md:justify-between gap-3 bg-slate-50/50 dark:bg-slate-800/30">
            <form method="GET" action="{{ route('payroll.billing.index') }}" class="flex items-center gap-2 flex-1 max-w-md">
                <div class="relative w-full">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. Tagihan, Pranota, Proyek, Pelanggan..."
                           class="w-full pl-9 pr-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:outline-none focus:border-primary">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                @if($statusFilter)
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                @endif
                <button type="submit" class="px-3 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-600 transition">Cari</button>
            </form>

            <div class="flex items-center gap-1.5 overflow-x-auto text-xs">
                <a href="{{ route('payroll.billing.index') }}" class="px-3 py-1.5 rounded-lg font-bold transition {{ !$statusFilter ? 'bg-primary text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200' }}">Semua ({{ $totalAll }})</a>
                <a href="{{ route('payroll.billing.index', ['status' => 'Billed', 'search' => $search]) }}" class="px-3 py-1.5 rounded-lg font-bold transition {{ $statusFilter === 'Billed' ? 'bg-purple-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200' }}">Billed</a>
                <a href="{{ route('payroll.billing.index', ['status' => 'Pranota Ready', 'search' => $search]) }}" class="px-3 py-1.5 rounded-lg font-bold transition {{ $statusFilter === 'Pranota Ready' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200' }}">Pranota Ready</a>
                <a href="{{ route('payroll.billing.index', ['status' => 'Paid', 'search' => $search]) }}" class="px-3 py-1.5 rounded-lg font-bold transition {{ $statusFilter === 'Paid' ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200' }}">Paid</a>
            </div>
        </div>

        <!-- Table Data -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/75 dark:bg-slate-800/50 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-4">No. Tagihan / Pranota</th>
                        <th class="py-3 px-4">Proyek TAD & Pelanggan</th>
                        <th class="py-3 px-4">Periode</th>
                        <th class="py-3 px-4 text-center">Jml TAD</th>
                        <th class="py-3 px-4 text-right">Total Tagihan</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-200">
                    @forelse($billings as $item)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                        <td class="py-3.5 px-4 font-mono">
                            <span class="font-bold text-primary dark:text-sky-400 block">{{ $item->billing_no }}</span>
                            <span class="text-[10px] text-slate-400 block">{{ $item->pranota_no }}</span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="font-bold text-slate-800 dark:text-slate-100 block">{{ $item->project_name }}</span>
                            <span class="text-[11px] text-slate-500 dark:text-slate-400">{{ $item->customer_name }}</span>
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-slate-600 dark:text-slate-300">
                            {{ $item->period }}
                        </td>
                        <td class="py-3.5 px-4 text-center font-bold">
                            <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 rounded-md">{{ $item->tad_count }} Orang</span>
                        </td>
                        <td class="py-3.5 px-4 text-right font-mono font-bold text-slate-900 dark:text-slate-100">
                            Rp {{ number_format($item->total_billing, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if($item->status === 'Paid')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                                    PAID
                                </span>
                            @elseif($item->status === 'Billed')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-purple-100 text-purple-800 dark:bg-purple-950/60 dark:text-purple-300 border border-purple-300 dark:border-purple-800">
                                    BILLED
                                </span>
                            @elseif($item->status === 'Pranota Ready')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-300 dark:border-blue-800">
                                    PRANOTA READY
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-300 dark:border-amber-800">
                                    DRAFT
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <button @click="showDetail(@js($item))"
                                        class="p-2 rounded-lg bg-sky-500 hover:bg-sky-600 text-white transition shadow-2xs flex items-center justify-center cursor-pointer"
                                        title="Lihat Detail Tagihan">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </button>
                                @if($item->status === 'Draft')
                                <form action="{{ route('payroll.billing.pranota', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg text-xs font-bold transition cursor-pointer" title="Generate Pranota">
                                        Pranota
                                    </button>
                                </form>
                                @elseif($item->status === 'Pranota Ready')
                                <form action="{{ route('payroll.billing.post', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 bg-purple-50 text-purple-700 hover:bg-purple-100 rounded-lg text-xs font-bold transition cursor-pointer" title="Post ke Billing AR SAP">
                                        Post Billing
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">
                            Tidak ada data tagihan payroll yang sesuai kriteria pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- DETAIL MODAL -->
    <div x-show="isDetailOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 rounded-xl max-w-xl w-full p-6 shadow-xl border border-slate-100 dark:border-slate-800" @click.outside="isDetailOpen = false">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Rincian Pranota & Tagihan Payroll TAD</h3>
                <button @click="isDetailOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <template x-if="selectedItem">
                <div class="space-y-3 text-xs">
                    <div class="grid grid-cols-2 gap-2 p-3 bg-slate-50 dark:bg-slate-800/40 rounded-xl">
                        <div>
                            <span class="text-slate-400 block">Nomor Tagihan:</span>
                            <span class="font-mono font-bold text-primary dark:text-sky-400" x-text="selectedItem.billing_no"></span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Nomor Pranota:</span>
                            <span class="font-mono font-bold text-slate-700 dark:text-slate-300" x-text="selectedItem.pranota_no"></span>
                        </div>
                    </div>

                    <div class="space-y-1.5 pt-2">
                        <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500">Proyek TAD:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200" x-text="selectedItem.project_name"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500">Customer / Prinsipal:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200" x-text="selectedItem.customer_name"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500">Periode Gaji:</span>
                            <span class="font-semibold text-slate-800 dark:text-slate-200" x-text="selectedItem.period"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500">Total Tenaga Kerja TAD:</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200" x-text="selectedItem.tad_count + ' Orang'"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500">Gaji Pokok TAD:</span>
                            <span class="font-mono font-semibold" x-text="'Rp ' + (selectedItem.base_salary || 0).toLocaleString('id-ID')"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500">Tunjangan & Lembur:</span>
                            <span class="font-mono font-semibold" x-text="'Rp ' + (((Number(selectedItem.allowances) || 0) + (Number(selectedItem.overtime) || 0))).toLocaleString('id-ID')"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500">Management Fee (10%):</span>
                            <span class="font-mono font-semibold text-blue-600 dark:text-blue-400" x-text="'Rp ' + (selectedItem.management_fee || 0).toLocaleString('id-ID')"></span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-slate-500">PPN (11%):</span>
                            <span class="font-mono font-semibold text-slate-600 dark:text-slate-400" x-text="'Rp ' + (selectedItem.ppn || 0).toLocaleString('id-ID')"></span>
                        </div>
                        <div class="flex justify-between py-2 bg-blue-50/50 dark:bg-blue-950/20 px-2 rounded-lg text-sm font-bold text-blue-900 dark:text-blue-100">
                            <span>TOTAL TAGIHAN BILLING:</span>
                            <span class="font-mono text-base" x-text="'Rp ' + (selectedItem.total_billing || 0).toLocaleString('id-ID')"></span>
                        </div>
                    </div>
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
function payrollBilling() {
    return {
        isDetailOpen: false,
        selectedItem: null,
        showDetail(item) {
            this.selectedItem = item;
            this.isDetailOpen = true;
        }
    }
}
</script>
@endsection
