@extends('layouts.app')

@section('title', 'RAB Budget Detail — Tanos ERP')

@section('content')
<div class="space-y-6 w-full" 
     x-data="{ 
         showAddModal: false, 
         showEditModal: false,
         itemId: '',
         coaCode: '',
         fundCenter: '',
         costCenter: '',
         profitCenter: '',
         jan: 0, feb: 0, mar: 0, apr: 0, may: 0, jun: 0, jul: 0, aug: 0, sep: 0, oct: 0, nov: 0, dec: 0,
         openEditModal(id, coa, fund, cost, profit, j, f, m, a, my, jn, jl, ag, s, o, n, d) {
             this.itemId = id;
             this.coaCode = coa;
             this.fundCenter = fund || '';
             this.costCenter = cost || '';
             this.profitCenter = profit || '';
             this.jan = j; this.feb = f; this.mar = m; this.apr = a; this.may = my; this.jun = jn;
             this.jul = jl; this.aug = ag; this.sep = s; this.oct = o; this.nov = n; this.dec = d;
             this.showEditModal = true;
         }
     }">
     
    <!-- Header Block -->
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-xl shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('rab.index') }}" class="p-2 border border-slate-200 dark:border-slate-700 text-slate-500 hover:text-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center space-x-2">
                        <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ $rab->name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400">
                            RAB Locking
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 dark:text-slate-550 mt-0.5 font-semibold">
                        Nomor Dokumen: {{ $rab->document_number }} | Tahun Anggaran: {{ $rab->year }}
                    </p>
                </div>
            </div>
            
            @if(in_array(session('user.role'), ['Admin', 'Project Manager', 'Finance Manager']))
            <div class="flex items-center gap-2">
                @if($rab->sap_status != 'Sent')
                    <button @click="showAddModal = true" class="bg-blue-650 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition cursor-pointer">
                        + Tambah Anggaran
                    </button>
                    <form action="{{ route('rab.send-sap', $rab->id) }}" method="POST" class="inline" onsubmit="return confirm('Posting ke SAP akan mengunci budget bulanan secara permanen. Lanjutkan?')">
                        @csrf
                        <button type="submit" class="bg-emerald-650 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition cursor-pointer">
                            Send & Lock SAP
                        </button>
                    </form>
                @else
                    <span class="px-4 py-2 border border-emerald-200 bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 dark:border-emerald-900/30 dark:text-emerald-400 text-xs font-bold rounded-xl flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1.5">
                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                        </svg>
                        SAP Budget Locked
                    </span>
                @endif
            </div>
            @else
                @if($rab->sap_status == 'Sent')
                    <span class="px-4 py-2 border border-emerald-200 bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 dark:border-emerald-900/30 dark:text-emerald-400 text-xs font-bold rounded-xl flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1.5">
                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd" />
                        </svg>
                        SAP Budget Locked
                    </span>
                @else
                    <span class="text-xs text-slate-450 italic bg-slate-50 dark:bg-slate-850 px-3 py-1.5 rounded-lg border border-slate-200/50 dark:border-slate-800">Read-Only Mode</span>
                @endif
            @endif
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 pt-6 border-t border-slate-100 dark:border-slate-800">
            <div class="p-3 bg-slate-50 dark:bg-slate-850/60 rounded-xl">
                <span class="block text-[10px] uppercase font-bold text-slate-400">Proyek (SAP Code)</span>
                <span class="block text-xs font-bold text-slate-800 dark:text-slate-150 mt-1 font-mono">{{ $project->project_code }}</span>
            </div>
            <div class="p-3 bg-slate-50 dark:bg-slate-850/60 rounded-xl">
                <span class="block text-[10px] uppercase font-bold text-slate-400">Pelanggan</span>
                <span class="block text-xs font-bold text-slate-800 dark:text-slate-150 mt-1 truncate">{{ $project->customer_name }}</span>
            </div>
            <div class="p-3 bg-slate-50 dark:bg-slate-850/60 rounded-xl">
                <span class="block text-[10px] uppercase font-bold text-slate-400 font-medium">Total Pendapatan</span>
                <span class="block text-xs font-bold text-emerald-600 dark:text-emerald-400 mt-1">Rp {{ number_format($rab->total_revenue, 0, ',', '.') }}</span>
            </div>
            <div class="p-3 bg-slate-50 dark:bg-slate-850/60 rounded-xl">
                <span class="block text-[10px] uppercase font-bold text-slate-400 font-medium">Total Biaya (Cost)</span>
                <span class="block text-xs font-bold text-slate-900 dark:text-slate-100 mt-1">Rp {{ number_format($rab->total_cost, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Monthly Matrix Section -->
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider mb-4">Budgeting Line Items (Matriks Bulanan)</h3>

        <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <table class="w-full text-[11px] text-left border-collapse min-w-[1600px] font-medium text-slate-600 dark:text-slate-400">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 text-[10px] font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <th class="p-3 align-middle w-[50px]">No</th>
                        <th class="p-3 align-middle w-[150px]">Account (COA)</th>
                        <th class="p-3 align-middle w-[100px]">Cost Center</th>
                        <th class="p-3 align-middle w-[100px]">Fund Center</th>
                        <th class="p-3 align-middle text-right w-[90px]">Jan</th>
                        <th class="p-3 align-middle text-right w-[90px]">Feb</th>
                        <th class="p-3 align-middle text-right w-[90px]">Mar</th>
                        <th class="p-3 align-middle text-right w-[90px]">Apr</th>
                        <th class="p-3 align-middle text-right w-[90px]">Mei</th>
                        <th class="p-3 align-middle text-right w-[90px]">Jun</th>
                        <th class="p-3 align-middle text-right w-[90px]">Jul</th>
                        <th class="p-3 align-middle text-right w-[90px]">Agu</th>
                        <th class="p-3 align-middle text-right w-[90px]">Sep</th>
                        <th class="p-3 align-middle text-right w-[90px]">Okt</th>
                        <th class="p-3 align-middle text-right w-[90px]">Nov</th>
                        <th class="p-3 align-middle text-right w-[90px]">Des</th>
                        <th class="p-3 align-middle text-right w-[110px] font-bold">Total</th>
                        @if($rab->sap_status != 'Sent')
                            <th class="p-3 align-middle text-center w-[100px]">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @php
                        $totJan = 0; $totFeb = 0; $totMar = 0; $totApr = 0; $totMay = 0; $totJun = 0;
                        $totJul = 0; $totAug = 0; $totSep = 0; $totOct = 0; $totNov = 0; $totDec = 0; $totGrand = 0;
                    @endphp
                    @forelse($rab->items as $idx => $item)
                        @php
                            $totJan += $item->jan; $totFeb += $item->feb; $totMar += $item->mar; $totApr += $item->apr;
                            $totMay += $item->may; $totJun += $item->jun; $totJul += $item->jul; $totAug += $item->aug;
                            $totSep += $item->sep; $totOct += $item->oct; $totNov += $item->nov; $totDec += $item->dec;
                            $totGrand += $item->total_amount;
                        @endphp
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                            <td class="p-3 align-middle font-semibold">{{ $idx + 1 }}</td>
                            <td class="p-3 align-middle font-bold text-slate-800 dark:text-slate-200">
                                {{ $item->coa_code }} - {{ $coaAccounts[$item->coa_code] ?? 'Other Expense' }}
                            </td>
                            <td class="p-3 align-middle font-mono">{{ $item->cost_center ?? '-' }}</td>
                            <td class="p-3 align-middle font-mono">{{ $item->fund_center ?? '-' }}</td>
                            
                            <!-- Monthly values -->
                            <td class="p-3 align-middle text-right">Rp {{ number_format($item->jan, 0, ',', '.') }}</td>
                            <td class="p-3 align-middle text-right">Rp {{ number_format($item->feb, 0, ',', '.') }}</td>
                            <td class="p-3 align-middle text-right">Rp {{ number_format($item->mar, 0, ',', '.') }}</td>
                            <td class="p-3 align-middle text-right">Rp {{ number_format($item->apr, 0, ',', '.') }}</td>
                            <td class="p-3 align-middle text-right">Rp {{ number_format($item->may, 0, ',', '.') }}</td>
                            <td class="p-3 align-middle text-right">Rp {{ number_format($item->jun, 0, ',', '.') }}</td>
                            <td class="p-3 align-middle text-right">Rp {{ number_format($item->jul, 0, ',', '.') }}</td>
                            <td class="p-3 align-middle text-right">Rp {{ number_format($item->aug, 0, ',', '.') }}</td>
                            <td class="p-3 align-middle text-right">Rp {{ number_format($item->sep, 0, ',', '.') }}</td>
                            <td class="p-3 align-middle text-right">Rp {{ number_format($item->oct, 0, ',', '.') }}</td>
                            <td class="p-3 align-middle text-right">Rp {{ number_format($item->nov, 0, ',', '.') }}</td>
                            <td class="p-3 align-middle text-right">Rp {{ number_format($item->dec, 0, ',', '.') }}</td>
                            
                            <td class="p-3 align-middle text-right font-bold text-slate-800 dark:text-slate-200">
                                Rp {{ number_format($item->total_amount, 0, ',', '.') }}
                            </td>
                            @if($rab->sap_status != 'Sent')
                                <td class="p-3 align-middle text-center whitespace-nowrap">
                                    @if(in_array(session('user.role'), ['Admin', 'Project Manager', 'Finance Manager']))
                                    <div class="flex items-center justify-center space-x-1.5">
                                        <button @click="openEditModal(
                                            '{{ $item->id }}', '{{ $item->coa_code }}', '{{ $item->fund_center }}', '{{ $item->cost_center }}', '{{ $item->profit_center }}',
                                            {{ $item->jan }}, {{ $item->feb }}, {{ $item->mar }}, {{ $item->apr }}, {{ $item->may }}, {{ $item->jun }},
                                            {{ $item->jul }}, {{ $item->aug }}, {{ $item->sep }}, {{ $item->oct }}, {{ $item->nov }}, {{ $item->dec }}
                                        )" class="p-1 text-blue-650 dark:text-blue-400 hover:text-blue-800 bg-blue-50 dark:bg-blue-950/20 rounded-md transition cursor-pointer" title="Edit Baris">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                                        </button>
                                        <form action="{{ route('rab.items.destroy', [$rab->id, $item->id]) }}" method="POST" onsubmit="return confirm('Hapus baris anggaran ini?')" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1 text-red-650 dark:text-red-400 hover:text-red-800 bg-red-50 dark:bg-red-950/20 rounded-md transition cursor-pointer" title="Hapus Baris">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m12 6c0 1.66-1.34 3-3 3H6c-1.34 0-3-1.34-3-3V7h18v8Z" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">No Action</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $rab->sap_status != 'Sent' ? 18 : 17 }}" class="p-8 text-center text-slate-400 text-xs">
                                Anggaran bulanan belum didefinisikan. Klik "+ Tambah Anggaran" untuk membuat budgeting line baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-slate-50/70 dark:bg-slate-800/40 font-bold text-slate-800 dark:text-slate-200 border-t border-slate-200 dark:border-slate-800">
                        <td colspan="4" class="p-3 text-center uppercase tracking-wider text-[10px]">Total Anggaran (RAB)</td>
                        <td class="p-3 text-right">Rp {{ number_format($totJan, 0, ',', '.') }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($totFeb, 0, ',', '.') }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($totMar, 0, ',', '.') }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($totApr, 0, ',', '.') }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($totMay, 0, ',', '.') }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($totJun, 0, ',', '.') }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($totJul, 0, ',', '.') }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($totAug, 0, ',', '.') }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($totSep, 0, ',', '.') }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($totOct, 0, ',', '.') }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($totNov, 0, ',', '.') }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($totDec, 0, ',', '.') }}</td>
                        <td class="p-3 text-right font-black text-blue-650 dark:text-blue-400">
                            Rp {{ number_format($totGrand, 0, ',', '.') }}
                        </td>
                        @if($rab->sap_status != 'Sent')
                            <td></td>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- MODAL: TAMBAH BUDGET LINE --}}
    <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-sm" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl w-full max-w-lg p-6 shadow-2xl relative" @click.away="showAddModal = false">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-4 uppercase tracking-wider">Tambah Budgeting Line (RAB)</h3>
            
            <form action="{{ route('rab.items.store', $rab->id) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">GL Account (COA)</label>
                        <select name="coa_code" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            @foreach($coaAccounts as $code => $lbl)
                                <option value="{{ $code }}">{{ $code }} - {{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Cost Center</label>
                        <input type="text" name="cost_center" placeholder="CC-9901" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Fund Center</label>
                        <input type="text" name="fund_center" placeholder="FC-9901" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Profit Center</label>
                        <input type="text" name="profit_center" placeholder="PC-9901" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-slate-800 pt-3">
                    <span class="block text-[10px] uppercase font-bold text-slate-400 mb-3">Distribusi Anggaran Bulanan (Rp)</span>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['jan' => 'Januari', 'feb' => 'Februari', 'mar' => 'Maret', 'apr' => 'April', 'may' => 'Mei', 'jun' => 'Juni', 'jul' => 'Juli', 'aug' => 'Agustus', 'sep' => 'September', 'oct' => 'Oktober', 'nov' => 'November', 'dec' => 'Desember'] as $key => $mName)
                            <div>
                                <label class="block text-[10px] font-bold text-slate-455 mb-1">{{ $mName }}</label>
                                <input type="number" name="{{ $key }}" value="0" min="0" required class="w-full text-xs px-2.5 py-2 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-200">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-650 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: EDIT BUDGET LINE --}}
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-sm" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl w-full max-w-lg p-6 shadow-2xl relative" @click.away="showEditModal = false">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-4 uppercase tracking-wider">Ubah Rincian Anggaran (RAB)</h3>
            
            <form :action="'/dashboard/rab-budgets/' + {{ $rab->id }} + '/items/' + itemId" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">GL Account (COA)</label>
                        <select name="coa_code" required x-model="coaCode" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            @foreach($coaAccounts as $code => $lbl)
                                <option value="{{ $code }}">{{ $code }} - {{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Cost Center</label>
                        <input type="text" name="cost_center" x-model="costCenter" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Fund Center</label>
                        <input type="text" name="fund_center" x-model="fundCenter" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Profit Center</label>
                        <input type="text" name="profit_center" x-model="profitCenter" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-slate-800 pt-3">
                    <span class="block text-[10px] uppercase font-bold text-slate-400 mb-3">Distribusi Anggaran Bulanan (Rp)</span>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['jan' => 'Januari', 'feb' => 'Februari', 'mar' => 'Maret', 'apr' => 'April', 'may' => 'Mei', 'jun' => 'Juni', 'jul' => 'Juli', 'aug' => 'Agustus', 'sep' => 'September', 'oct' => 'Oktober', 'nov' => 'November', 'dec' => 'Desember'] as $key => $mName)
                            <div>
                                <label class="block text-[10px] font-bold text-slate-455 mb-1">{{ $mName }}</label>
                                <input type="number" name="{{ $key }}" x-model="{{ $key }}" min="0" required class="w-full text-xs px-2.5 py-2 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-200">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-650 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                        Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

