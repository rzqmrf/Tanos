@extends('layouts.app')

@section('title', 'Payroll Detail — Tanos ERP')

@section('content')
<div class="space-y-6" x-data="{ showCopyModal: false }">
    
    <!-- Top Bar Navigation -->
    <div class="flex items-center justify-between bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-4 rounded-2xl shadow-sm">
        <div class="flex items-center space-x-3">
            <a href="{{ route('payrolls.index') }}" class="p-2 bg-slate-50 dark:bg-slate-800 text-slate-550 dark:text-slate-350 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-750 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </a>
            <div>
                <h1 class="text-sm font-bold text-slate-850 dark:text-slate-150">{{ $period->name }}</h1>
                <p class="text-[10px] text-slate-400 font-semibold">{{ $period->project->segment }} - {{ $period->project->regional }} | {{ $period->month }}</p>
            </div>
        </div>
        
        @if(in_array(session('user.role'), ['Admin', 'Finance Manager']))
        <div class="flex items-center gap-2">
            <!-- Action: Copy Formula (Halaman 29) -->
            <button @click="showCopyModal = true" class="px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl text-xs font-bold hover:bg-slate-100 dark:hover:bg-slate-750 transition cursor-pointer">
                Copy Formula
            </button>

            <!-- Action: Simulation (Green Button - Halaman 28) -->
            <form action="{{ route('payrolls.calculate', $period->id) }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="action" value="Simulation">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition cursor-pointer">
                    Simulation Period
                </button>
            </form>

            <!-- Action: Generate (Yellow Button - Halaman 28) -->
            <form action="{{ route('payrolls.calculate', $period->id) }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="action" value="Payroll">
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition cursor-pointer">
                    Generate
                </button>
            </form>

            <!-- Action: Send to SAP / Posting (Petir Kuning Button - Halaman 30) -->
            @if($period->status === 'Completed')
                <form action="{{ route('payrolls.post-sap', $period->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-yellow-450 hover:bg-yellow-500 text-slate-900 px-4 py-2 rounded-xl text-xs font-black shadow-sm transition flex items-center gap-1.5 cursor-pointer" title="Posting Payroll ke SAP">
                        <span>⚡</span> Posting Payroll
                    </button>
                </form>
            @endif
        </div>
        @else
            <span class="text-xs text-slate-450 italic bg-slate-50 dark:bg-slate-850 px-3 py-1.5 rounded-lg border border-slate-200/50 dark:border-slate-800">Read-Only Mode</span>
        @endif
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Panel: Period & Formulation Components (Halaman 29) -->
        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
                <h3 class="text-xs font-bold text-slate-400 uppercase mb-4">Informasi Periode</h3>
                
                <div class="space-y-3.5 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Status Dokumen:</span>
                        <span class="font-black uppercase tracking-wider
                            {{ $period->status === 'Draft' ? 'text-slate-600 dark:text-slate-400' : '' }}
                            {{ $period->status === 'Simulated' ? 'text-emerald-600 dark:text-emerald-400' : '' }}
                            {{ $period->status === 'Completed' ? 'text-amber-500 dark:text-amber-400' : '' }}
                            {{ $period->status === 'Posted' ? 'text-blue-600 dark:text-blue-400' : '' }}
                            {{ $period->status === 'Voided' ? 'text-rose-600' : '' }}
                        ">{{ $period->status }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Tipe:</span>
                        <span class="font-bold text-slate-850 dark:text-slate-150">{{ $period->type }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Bulan:</span>
                        <span class="font-bold text-slate-850 dark:text-slate-150">{{ $period->month }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-medium">Rentang Tanggal:</span>
                        <span class="font-bold text-slate-850 dark:text-slate-150">{{ $period->start_date->format('d M Y') }} - {{ $period->end_date->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-bold text-slate-400 uppercase">Komponen & WBS Mapping</h3>
                    <span class="px-2 py-0.5 rounded bg-indigo-50 text-indigo-700 dark:bg-indigo-950/20 dark:text-indigo-400 text-[10px] font-bold">WBS Configured</span>
                </div>

                <div class="space-y-4">
                    @foreach($period->components as $comp)
                        <div class="border border-slate-100 dark:border-slate-800/80 p-3 rounded-xl bg-slate-50/50 dark:bg-slate-950/10">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="text-[10px] font-bold text-slate-400">{{ $comp->code }}</span>
                                    <h4 class="text-xs font-bold text-slate-850 dark:text-slate-200">{{ $comp->name }}</h4>
                                </div>
                                <span class="text-[9px] px-1.5 py-0.5 rounded font-black {{ $comp->type === 'Valuation' ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/20' : 'bg-teal-50 text-teal-700 dark:bg-teal-950/20' }}">
                                    {{ $comp->type }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between mt-2.5 pt-2 border-t border-slate-100 dark:border-slate-800/40 text-[10px]">
                                <span class="text-slate-400 font-medium">WBS: <strong class="text-slate-650 dark:text-slate-350">{{ $comp->wbsElement ? $comp->wbsElement->wbs_code : '-' }}</strong></span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">
                                    {{ $comp->amount < 0 ? '-' : '' }}Rp {{ number_format(abs($comp->amount), 0, ',', '.') }}
                                    @if($comp->type === 'Formula')
                                        <span class="text-[9px] text-slate-400">/hr</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Right Panel: Payroll Result Sheet (Halaman 30) -->
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5 pb-4 border-b border-slate-150 dark:border-slate-800/80">
                    <div>
                        <h3 class="text-base font-bold text-slate-850 dark:text-slate-100">Slip Payroll Result Karyawan</h3>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">Daftar rincian gaji tenaga alih daya yang terdeteksi pada cost center proyek.</p>
                    </div>
                    <div class="text-xs font-bold text-slate-500 bg-slate-50 dark:bg-slate-950/20 px-3 py-1.5 rounded-xl border border-slate-150 dark:border-slate-850">
                        Total Anggaran: <span class="text-indigo-600 dark:text-indigo-400">Rp {{ number_format($period->results->sum('net_salary'), 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase border-b border-slate-200 dark:border-slate-800">
                                <th class="p-3">Karyawan</th>
                                <th class="p-3 text-center">Hadir</th>
                                <th class="p-3 text-center">Lembur (jam)</th>
                                <th class="p-3">Gaji Pokok</th>
                                <th class="p-3">Transport</th>
                                <th class="p-3">Tunjangan</th>
                                <th class="p-3">Uang Lembur</th>
                                <th class="p-3">Potongan</th>
                                <th class="p-3">Net Gaji</th>
                                <th class="p-3">Doc SAP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-[11px] text-slate-600 dark:text-slate-350">
                            @forelse($period->results as $res)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                                    <td class="p-3 font-bold text-slate-850 dark:text-slate-200">{{ $res->employee->name }}</td>
                                    <td class="p-3 text-center font-semibold">{{ $res->days_present }} hari</td>
                                    <td class="p-3 text-center">{{ floatval($res->overtime_hours) }}</td>
                                    <td class="p-3">Rp {{ number_format($res->basic_salary, 0, ',', '.') }}</td>
                                    <td class="p-3 text-emerald-600 dark:text-emerald-400">+Rp {{ number_format($res->transport_allowance, 0, ',', '.') }}</td>
                                    <td class="p-3 text-emerald-600 dark:text-emerald-400">+Rp {{ number_format($res->allowances, 0, ',', '.') }}</td>
                                    <td class="p-3 text-emerald-600 dark:text-emerald-400">+Rp {{ number_format($res->overtime_pay, 0, ',', '.') }}</td>
                                    <td class="p-3 text-rose-600 dark:text-rose-400">-Rp {{ number_format($res->deductions, 0, ',', '.') }}</td>
                                    <td class="p-3 font-black text-slate-900 dark:text-slate-100">Rp {{ number_format($res->net_salary, 0, ',', '.') }}</td>
                                    <td class="p-3 text-[10px] font-medium text-slate-400 truncate max-w-[100px]" title="{{ $res->sap_doc_number }}">
                                        {{ $res->sap_doc_number ?: '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="p-8 text-center text-slate-400">Belum ada hasil generate. Tekan "Generate" atau "Simulation Period" untuk memulai hitungan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>

    {{-- MODAL: COPY FORMULA --}}
    <div x-show="showCopyModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-md" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showCopyModal = false">
            <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 mb-2">Salin Rumus Penggajian (Copy Formula)</h3>
            <p class="text-xs text-slate-400 mb-4 font-medium">Pilih periode asal komponen gaji yang akan disalin formulasinya ke periode aktif ini.</p>
            
            <form action="{{ route('payrolls.copy-formula', $period->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Pilih Periode Asal</label>
                    <select name="source_period_id" required 
                            class="w-full text-xs px-3 py-2.5 bg-slate-50 dark:bg-slate-855 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-800 dark:text-slate-200">
                        @foreach($allPeriods as $src)
                            <option value="{{ $src->id }}">{{ $src->name }} ({{ $src->project->segment }} - {{ $src->project->regional }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800/80">
                    <button type="button" @click="showCopyModal = false" class="px-4 py-2 text-slate-500 text-xs font-bold rounded-xl hover:bg-slate-50 transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow transition cursor-pointer">Salin Sekarang</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
