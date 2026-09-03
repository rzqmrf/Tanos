@extends('layouts.app')

@section('title', 'Executive & Finance Reports — Tanos ERP')

@section('content')
<div class="space-y-6" x-data="{ activeTab: '{{ $activeTab }}' }">
    
    <!-- Page Header & Action -->
    <x-page-header 
        title="Laporan & Analisis Eksekutif" 
        subtitle="Monitoring komprehensif RAB Proyek, Billing SAP, dan Remunerasi HCM."
        :breadcrumbs="[
            'General' => '#',
            'Finance & Accounting' => '#',
            'Executive Reports' => ''
        ]"
    >
        <x-slot:action>
            <a :href="'{{ route('reports.export') }}?tab=' + activeTab + '&year={{ $selectedYear }}&regional={{ $selectedRegional }}&segment={{ $selectedSegment }}'" 
               class="inline-flex items-center space-x-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-xs transition-all duration-150 cursor-pointer border-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <span>Export Excel / CSV</span>
            </a>
            <button onclick="window.print()" class="inline-flex items-center space-x-2 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 font-bold text-xs px-3.5 py-2 rounded-xl shadow-xs transition-all duration-150 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231a1.125 1.125 0 0 1-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m0 0a48.1 48.1 0 0 1 10.56 0m-10.56 0V3.75C6.75 2.921 7.421 2.25 8.25 2.25h7.5c.829 0 1.5.671 1.5 1.5v3.456" />
                </svg>
                <span>Cetak</span>
            </button>
        </div>
    </div>

    <!-- Top KPI Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: Total RAB Budget -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-xl p-4 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total RAB Budget</span>
            <div class="text-xl font-black text-slate-800 dark:text-slate-100 mt-1">
                Rp {{ number_format($kpiSummary['total_rab_budget'], 0, ',', '.') }}
            </div>
            <div class="text-[11px] font-semibold text-blue-600 dark:text-blue-400 mt-2 flex items-center space-x-1">
                <span>{{ $kpiSummary['active_projects_count'] }} Proyek Aktif</span>
            </div>
        </div>

        <!-- Card 2: Realisasi Cost -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-xl p-4 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Realisasi Cost Aktual</span>
            <div class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-1">
                Rp {{ number_format($kpiSummary['total_actual_cost'], 0, ',', '.') }}
            </div>
            <div class="text-[11px] font-semibold text-slate-500 mt-2">
                Serapan {{ $kpiSummary['total_rab_budget'] > 0 ? round(($kpiSummary['total_actual_cost'] / $kpiSummary['total_rab_budget']) * 100, 1) : 0 }}% dari RAB
            </div>
        </div>

        <!-- Card 3: Total Billing Nota -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-xl p-4 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Billing (Nota SAP)</span>
            <div class="text-xl font-black text-purple-600 dark:text-purple-400 mt-1">
                Rp {{ number_format($kpiSummary['total_billing_bruto'], 0, ',', '.') }}
            </div>
            <div class="text-[11px] font-semibold text-slate-500 mt-2">
                Tagihan Bruto Terposting
            </div>
        </div>

        <!-- Card 4: Total Beban Payroll -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-xl p-4 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Beban Payroll</span>
            <div class="text-xl font-black text-amber-600 dark:text-amber-400 mt-1">
                Rp {{ number_format($kpiSummary['total_payroll_thp'], 0, ',', '.') }}
            </div>
            <div class="text-[11px] font-semibold text-slate-500 mt-2">
                {{ $kpiSummary['total_employees_count'] }} Pegawai TAD Terdaftar
            </div>
        </div>
    </div>

    <!-- Filter Bar & Tabs Header -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-5 rounded-xl shadow-sm space-y-4">
        
        <!-- Filter Form -->
        <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <input type="hidden" name="tab" :value="activeTab">

            <!-- Regional Filter -->
            <div>
                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Regional</label>
                <select name="regional" class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-700 dark:text-slate-200">
                    <option value="All" {{ $selectedRegional === 'All' ? 'selected' : '' }}>Semua Regional</option>
                    @foreach($regionals as $r)
                        <option value="{{ $r }}" {{ $selectedRegional === $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Segment Filter -->
            <div>
                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Segment</label>
                <select name="segment" class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-700 dark:text-slate-200">
                    <option value="All" {{ $selectedSegment === 'All' ? 'selected' : '' }}>Semua Segment</option>
                    @foreach($segments as $s)
                        <option value="{{ $s }}" {{ $selectedSegment === $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Month Filter -->
            <div>
                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Bulan</label>
                <select name="month" class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-700 dark:text-slate-200">
                    <option value="All" {{ $selectedMonth === 'All' ? 'selected' : '' }}>Semua Bulan</option>
                    @foreach($months as $m)
                        <option value="{{ $m }}" {{ $selectedMonth === $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Year Filter -->
            <div>
                <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Tahun</label>
                <select name="year" class="w-full text-xs font-semibold px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-700 dark:text-slate-200">
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Submit Button -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white text-xs font-bold py-2 px-4 rounded-xl shadow-md shadow-primary transition cursor-pointer">
                    Terapkan
                </button>
                <a href="{{ route('reports.index') }}" class="bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-800 dark:hover:text-slate-200 text-xs font-bold py-2 px-3 rounded-xl transition">
                    Reset
                </a>
            </div>
        </form>

        <!-- Navigation Tabs -->
        <div class="flex border-b border-slate-200 dark:border-slate-800 pt-2 space-x-2">
            <button @click="activeTab = 'projects'" 
                    :class="activeTab === 'projects' ? 'border-blue-600 text-blue-600 dark:text-blue-400 font-bold' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 font-semibold'"
                    class="py-2.5 px-4 text-xs border-b-2 transition duration-150 cursor-pointer flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5m-16.5 3h16.5m-16.5 3h16.5m-16.5 3h16.5m-16.5 3h16.5M3.75 4.5v12m16.5-12v12m-16.5 0a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5" />
                </svg>
                <span>Proyek & RAB Budget</span>
            </button>

            <button @click="activeTab = 'billing'" 
                    :class="activeTab === 'billing' ? 'border-blue-600 text-blue-600 dark:text-blue-400 font-bold' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 font-semibold'"
                    class="py-2.5 px-4 text-xs border-b-2 transition duration-150 cursor-pointer flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5h16.5m-16.5 3h16.5m-16.5 3h16.5m-16.5 3h16.5m-16.5 3h16.5M3.75 4.5v12m16.5-12v12m-16.5 0a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5" />
                </svg>
                <span>Tagihan & Billing SAP</span>
            </button>

            <button @click="activeTab = 'payroll'" 
                    :class="activeTab === 'payroll' ? 'border-blue-600 text-blue-600 dark:text-blue-400 font-bold' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 font-semibold'"
                    class="py-2.5 px-4 text-xs border-b-2 transition duration-150 cursor-pointer flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <span>HCM & Payroll</span>
            </button>
        </div>
    </div>

    <!-- TAB 1: PROYEK & RAB BUDGET REPORT TABLE -->
    <div x-show="activeTab === 'projects'" class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-200">Laporan Realisasi Anggaran RAB per Proyek</h2>
            <span class="text-xs text-slate-400 font-semibold">Total {{ count($projectReports) }} Data Proyek</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/70 dark:bg-slate-800/50 text-slate-400 dark:text-slate-400 uppercase text-[10px] font-extrabold tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <th class="py-3.5 px-4">Proyek & Customer</th>
                        <th class="py-3.5 px-4">Regional / Segment</th>
                        <th class="py-3.5 px-4 text-right">Anggaran RAB (Rp)</th>
                        <th class="py-3.5 px-4 text-right">Realisasi Cost (Rp)</th>
                        <th class="py-3.5 px-4 text-right">Sisa Budget (Rp)</th>
                        <th class="py-3.5 px-4 text-center">Progress Serapan</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-semibold text-slate-700 dark:text-slate-200">
                    @forelse($projectReports as $proj)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition duration-150">
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-800 dark:text-slate-100">{{ $proj['name'] }}</div>
                                <div class="text-[11px] text-slate-400 font-medium">{{ $proj['code'] }} • {{ $proj['customer'] }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="block font-bold">{{ $proj['regional'] }}</span>
                                <span class="text-[11px] text-slate-400 font-medium">{{ $proj['segment'] }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-bold text-slate-800 dark:text-slate-200">
                                {{ number_format($proj['rab_budget'], 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                {{ number_format($proj['actual_cost'], 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-medium text-slate-500">
                                {{ number_format($proj['remaining_budget'], 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center w-36">
                                <div class="flex items-center space-x-2">
                                    <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                                        <div class="bg-primary h-2 rounded-full" :style="'width: ' + {{ $proj['realization_pct'] }} + '%'"></div>
                                    </div>
                                    <span class="text-[11px] font-bold shrink-0">{{ $proj['realization_pct'] }}%</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase {{ $proj['status'] === 'Active' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/50' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $proj['status'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 italic">Tidak ada data proyek sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 2: BILLING & INVOICE REPORT TABLE -->
    <div x-show="activeTab === 'billing'" class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-xl shadow-sm overflow-hidden" style="display: none;">
        <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-200">Laporan Rekapitulasi Billing & Nota SAP</h2>
            <span class="text-xs text-slate-400 font-semibold">Total {{ count($billingReports) }} Nota Billing</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/70 dark:bg-slate-800/50 text-slate-400 dark:text-slate-400 uppercase text-[10px] font-extrabold tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <th class="py-3.5 px-4">No Nota SAP</th>
                        <th class="py-3.5 px-4">Proyek & Regional</th>
                        <th class="py-3.5 px-4 text-right">Tagihan Bruto (Rp)</th>
                        <th class="py-3.5 px-4 text-right">PPN 11% (Rp)</th>
                        <th class="py-3.5 px-4 text-right">PPh 2% (Rp)</th>
                        <th class="py-3.5 px-4 text-right">Tagihan Netto (Rp)</th>
                        <th class="py-3.5 px-4 text-center">Status SAP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-semibold text-slate-700 dark:text-slate-200">
                    @forelse($billingReports as $nota)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition duration-150">
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-800 dark:text-slate-100">{{ $nota['nota_number'] }}</div>
                                <div class="text-[11px] text-slate-400 font-medium">Tanggal: {{ $nota['posted_at'] }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="block font-bold">{{ $nota['project_name'] }}</span>
                                <span class="text-[11px] text-slate-400 font-medium">{{ $nota['regional'] }} • {{ $nota['segment'] }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-bold text-slate-800 dark:text-slate-200">
                                {{ number_format($nota['amount_bruto'], 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-medium text-slate-500">
                                {{ number_format($nota['ppn'], 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-medium text-slate-500">
                                {{ number_format($nota['pph'], 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-bold text-purple-600 dark:text-purple-400">
                                {{ number_format($nota['amount_netto'], 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase {{ strtolower($nota['status']) === 'posted' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/50' : 'bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-200/50' }}">
                                    {{ $nota['status'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 italic">Tidak ada data billing nota sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 3: HCM & PAYROLL REPORT TABLE -->
    <div x-show="activeTab === 'payroll'" class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-xl shadow-sm overflow-hidden" style="display: none;">
        <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-200">Laporan Beban Payroll & Remunerasi Karyawan</h2>
            <span class="text-xs text-slate-400 font-semibold">Total {{ count($payrollReports) }} Periode Payroll</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/70 dark:bg-slate-800/50 text-slate-400 dark:text-slate-400 uppercase text-[10px] font-extrabold tracking-wider border-b border-slate-100 dark:border-slate-800">
                        <th class="py-3.5 px-4">Periode & Proyek</th>
                        <th class="py-3.5 px-4">Regional</th>
                        <th class="py-3.5 px-4 text-center">Jumlah Pegawai (TAD)</th>
                        <th class="py-3.5 px-4 text-right">Gaji Pokok (Rp)</th>
                        <th class="py-3.5 px-4 text-right">Tunjangan (Rp)</th>
                        <th class="py-3.5 px-4 text-right">Total THP (Rp)</th>
                        <th class="py-3.5 px-4 text-center">Status Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 font-semibold text-slate-700 dark:text-slate-200">
                    @forelse($payrollReports as $pay)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition duration-150">
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-800 dark:text-slate-100">{{ $pay['period_name'] }}</div>
                                <div class="text-[11px] text-slate-400 font-medium">{{ $pay['project_name'] }}</div>
                            </td>
                            <td class="py-3.5 px-4 font-bold">
                                {{ $pay['regional'] }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg text-xs font-bold">
                                    {{ $pay['employee_count'] }} Orang
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-medium text-slate-700 dark:text-slate-300">
                                {{ number_format($pay['total_basic'], 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-medium text-slate-500">
                                {{ number_format($pay['total_allowance'], 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-bold text-amber-600 dark:text-amber-400">
                                {{ number_format($pay['total_thp'], 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase {{ strtolower($pay['status']) === 'completed' || strtolower($pay['status']) === 'posted' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/50' : 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200/50' }}">
                                    {{ $pay['status'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 italic">Tidak ada data payroll sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
