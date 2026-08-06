@extends('layouts.app')

@section('title', 'PS: Posting Payroll General Ledger — Tanos ERP')

@section('content')
<div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm w-full"
     x-data="{ 
         showJournalModal: false,
         showUploadModal: false,
         activePeriodId: '',
         periodName: '',
         projectCode: '',
         totalCost: 0,
         results: [],
         openJournalModal(id, name, project, total, resList) {
             this.activePeriodId = id;
             this.periodName = name;
             this.projectCode = project;
             this.totalCost = total;
             this.results = resList;
             this.showJournalModal = true;
         },
         openUploadModal(id, name) {
             this.activePeriodId = id;
             this.periodName = name;
             this.showUploadModal = true;
         }
     }">
     
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Posting Payroll (General Ledger)</h1>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Tempat penampungan Jurnal dokumen Payroll Result Pelindo Group (On-Cycle & Off-Cycle).</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-5 p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table General Ledger -->
    <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                    <th class="p-4">Bulan</th>
                    <th class="p-4">Nama Periode</th>
                    <th class="p-4">Project</th>
                    <th class="p-4">Tipe</th>
                    <th class="p-4">Total Gaji Pokok</th>
                    <th class="p-4">Total Lembur & Transport</th>
                    <th class="p-4">Total Pembayaran</th>
                    <th class="p-4">Status SAP</th>
                    <th class="p-4 text-center">Aksi Jurnal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-600 dark:text-slate-350">
                @forelse($periods as $item)
                @php
                    $basicSum = $item->results->sum('basic_salary');
                    $transLemburSum = $item->results->sum('transport_allowance') + $item->results->sum('overtime_pay') - $item->results->sum('deductions');
                    $totalPay = $item->results->sum('net_salary');
                @endphp
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                    <td class="p-4 font-semibold text-slate-500">{{ $item->month }}</td>
                    <td class="p-4 font-bold text-slate-800 dark:text-slate-200">{{ $item->name }}</td>
                    <td class="p-4">{{ $item->project->segment }} - {{ $item->project->regional }}</td>
                    <td class="p-4">{{ $item->type }}</td>
                    <td class="p-4 font-semibold">Rp {{ number_format($basicSum, 0, ',', '.') }}</td>
                    <td class="p-4 text-emerald-600 font-semibold">+Rp {{ number_format($transLemburSum, 0, ',', '.') }}</td>
                    <td class="p-4 font-black text-slate-900 dark:text-slate-100">Rp {{ number_format($totalPay, 0, ',', '.') }}</td>
                    <td class="p-4">
                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider
                            {{ $item->status === 'Completed' ? 'bg-amber-50 text-amber-700' : '' }}
                            {{ $item->status === 'Posted' ? 'bg-emerald-50 text-emerald-700' : '' }}
                            {{ $item->status === 'Voided' ? 'bg-rose-50 text-rose-700' : '' }}
                        ">
                            {{ $item->status === 'Posted' ? 'SAP Posted' : $item->status }}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <!-- Button View Jurnal (Halaman 9) -->
                            <button @click="openJournalModal('{{ $item->id }}', '{{ $item->name }}', '{{ $item->project->segment }} - {{ $item->project->regional }}', {{ $totalPay }}, {{ json_encode($item->results) }})" 
                                    class="px-2 py-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-750 text-[10px] font-bold rounded-lg transition cursor-pointer">
                                View
                            </button>

                            <!-- Button Upload Document / P-Files (Halaman 9) -->
                            <button @click="openUploadModal('{{ $item->id }}', '{{ $item->name }}')" 
                                    class="px-2 py-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-750 text-[10px] font-bold text-indigo-600 dark:text-indigo-400 rounded-lg transition cursor-pointer">
                                Upload P-Files
                            </button>

                            <!-- Button Void (Halaman 9) -->
                            @if($item->status !== 'Voided')
                                <form action="{{ route('posting_payrolls.void', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan/void jurnal payroll ini?')" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2 py-1 bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 text-[10px] font-bold rounded-lg hover:bg-rose-100 dark:hover:bg-rose-950/40 transition cursor-pointer">
                                        Void
                                    </button>
                                </form>
                            @endif

                            <!-- Button Send to SAP (Halaman 9) -->
                            @if($item->status === 'Completed')
                                <form action="{{ route('payrolls.post-sap', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-white text-[10px] font-black rounded-lg transition cursor-pointer">
                                        Send to SAP
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="p-12 text-center text-slate-400">Belum ada jurnal penggajian hasil generate. Silakan selesaikan (Generate) periode payroll di menu Payroll terlebih dahulu.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODAL: VIEW JURNAL PAYROLL --}}
    <div x-show="showJournalModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-md" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl w-full max-w-xl p-6 shadow-2xl relative" @click.away="showJournalModal = false">
            <h3 class="text-base font-bold text-slate-850 dark:text-slate-100 mb-1">Rincian Jurnal Umum Ledger</h3>
            <p class="text-[10px] text-slate-400 font-semibold mb-4" x-text="periodName + ' (' + projectCode + ')'"></p>

            <div class="border border-slate-150 dark:border-slate-800 rounded-xl overflow-hidden mb-4">
                <table class="w-full text-left text-[11px] border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/40 text-slate-500 dark:text-slate-400 font-bold border-b border-slate-150 dark:border-slate-800">
                            <th class="p-3">Pos Akun/COA</th>
                            <th class="p-3">Keterangan Jurnal</th>
                            <th class="p-3 text-right">Debet</th>
                            <th class="p-3 text-right">Kredit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        <!-- Debet: Gaji Pokok -->
                        <tr>
                            <td class="p-3 font-semibold text-slate-700 dark:text-slate-300">50101 - Beban Gaji TAD</td>
                            <td class="p-3 text-slate-400">Beban Gaji Pokok Pelindo Group</td>
                            <td class="p-3 text-right font-bold">Rp <span x-text="new Intl.NumberFormat('id-ID').format(results.reduce((sum, item) => sum + parseFloat(item.basic_salary), 0))"></span></td>
                            <td class="p-3 text-right text-slate-300">-</td>
                        </tr>
                        <!-- Debet: Tunjangan & Lembur -->
                        <tr>
                            <td class="p-3 font-semibold text-slate-700 dark:text-slate-300">50102 - Beban Transport & Lembur</td>
                            <td class="p-3 text-slate-400">Tunjangan Hadir & Jam Lembur Kerja</td>
                            <td class="p-3 text-right font-bold">Rp <span x-text="new Intl.NumberFormat('id-ID').format(results.reduce((sum, item) => sum + parseFloat(item.transport_allowance) + parseFloat(item.overtime_pay) - parseFloat(item.deductions), 0))"></span></td>
                            <td class="p-3 text-right text-slate-300">-</td>
                        </tr>
                        <!-- Kredit: Kas/Utang Gaji -->
                        <tr class="bg-slate-50/30 dark:bg-slate-950/10">
                            <td class="p-3 font-semibold text-slate-700 dark:text-slate-300 pl-6">20101 - Utang Payroll TAD</td>
                            <td class="p-3 text-slate-400">Alokasi Pembayaran Honorarium Karyawan</td>
                            <td class="p-3 text-right text-slate-300">-</td>
                            <td class="p-3 text-right font-bold text-indigo-600 dark:text-indigo-450">Rp <span x-text="new Intl.NumberFormat('id-ID').format(totalCost)"></span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-end pt-3 border-t border-slate-100 dark:border-slate-800/80">
                <button type="button" @click="showJournalModal = false" class="px-5 py-2 bg-slate-850 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow cursor-pointer">Tutup</button>
            </div>
        </div>
    </div>

    {{-- MODAL: UPLOAD FILE P-FILES TO SAP --}}
    <div x-show="showUploadModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-md" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showUploadModal = false">
            <h3 class="text-base font-bold text-slate-850 dark:text-slate-100 mb-1">Unggah Lampiran P-Files SAP</h3>
            <p class="text-[10px] text-slate-400 font-semibold mb-4" x-text="periodName"></p>

            <form :action="'/dashboard/posting-payrolls/' + activePeriodId + '/upload'" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">File Jurnal / Berkas PDF Lampiran</label>
                    <input type="file" name="attachment" required 
                           class="w-full text-xs file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 text-slate-450">
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800/80">
                    <button type="button" @click="showUploadModal = false" class="px-4 py-2 text-slate-500 text-xs font-bold rounded-xl hover:bg-slate-50 transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow transition cursor-pointer">Upload ke SAP</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
