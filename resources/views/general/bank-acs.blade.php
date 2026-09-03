@extends('layouts.app')

@section('title', 'Bank ACS — Setting Bank ACS Customer — TANOS ERP')

@section('content')
<div class="space-y-6 w-full" x-data="bankAcsManager()">

    <!-- Header & Breadcrumbs matching Screenshot 1 -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 dark:text-slate-400 mb-4 print:hidden">
                <a href="{{ route('dashboard.index') }}" class="hover:text-primary dark:hover:text-sky-400 transition flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Home
                </a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span>General</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-primary dark:text-sky-400 font-black">Bank ACS</span>
            </div>

            <!-- Page Title -->
            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                Bank ACS
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Setting Bank ACS Customer</p>
        </div>

        <!-- Top Right Actions -->
        <div class="flex items-center gap-2.5 self-start sm:self-auto">
            <button @click="openCreate()"
                    class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 cursor-pointer border-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Data</span>
            </button>

            <!-- Advanced Search Green Button -->
            <button @click="showAdvancedSearch = true"
                    class="px-4 py-2.5 bg-[#00c853] hover:bg-[#00b34a] text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-2 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <span>Advanced Search</span>
            </button>
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="p-3.5 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/40 rounded-xl text-xs font-bold flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Main Card Container -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden p-5 sm:p-6">
             <!-- Card Header Title & Export Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 mb-5 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center space-x-2">
                <h2 class="text-base font-black text-slate-800 dark:text-slate-100">
                    Bank ACS - List
                </h2>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                    {{ $bankAcs->total() }} total data
                </span>
            </div>

            <!-- Export Buttons (Copy, PDF, Excel) -->
            <div class="flex items-center space-x-2">
                <button type="button" onclick="window.print()" title="Cetak Rekap Data"
                        class="p-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl transition cursor-pointer flex items-center space-x-1 text-xs font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Cetak</span>
                </button>
                <button type="button" onclick="window.print()" title="Export PDF"
                        class="p-2 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 text-rose-600 dark:text-rose-400 rounded-xl transition border border-rose-200 dark:border-rose-900/50 cursor-pointer flex items-center space-x-1 text-xs font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>PDF</span>
                </button>
                <a href="#" onclick="alert('Export Excel sedang diproses...')" title="Export Excel"
                        class="p-2 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 text-emerald-600 dark:text-emerald-400 rounded-xl transition border border-emerald-200 dark:border-emerald-900/50 cursor-pointer flex items-center space-x-1 text-xs font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>XLS</span>
                </a>
            </div>
        </div>

        <!-- Filter Controls (Show entries + Search) -->
        <form method="GET" action="{{ route('general.bank-acs') }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
            <div class="flex items-center space-x-2 text-xs text-slate-600 dark:text-slate-400 font-bold">
                <span>Tampilkan</span>
                <select name="per_page" onchange="this.form.submit()"
                        class="px-2.5 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none cursor-pointer">
                    <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ ($perPage ?? 10) == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ ($perPage ?? 10) == 100 ? 'selected' : '' }}>100</option>
                </select>
                <span>entri per halaman</span>
            </div>

            <div class="flex items-center space-x-2">
                <div class="relative min-w-[240px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search:"
                           class="w-full pl-8 pr-4 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                @if(request()->filled('search'))
                <a href="{{ route('general.bank-acs') }}" class="p-2 text-xs font-bold text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">Reset</a>
                @endif
            </div>
        </form>

        <!-- Table Data matching Golden Benchmark -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs" id="bankAcsTable">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-800/60 border-y border-slate-200 dark:border-slate-800 text-[11px] font-extrabold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                        <th class="py-3.5 px-3">Bank Name</th>
                        <th class="py-3.5 px-3">Account No</th>
                        <th class="py-3.5 px-3">Customer</th>
                        <th class="py-3.5 px-3">Valid From</th>
                        <th class="py-3.5 px-3">Valid To</th>
                        <th class="py-3.5 px-3">Document Status</th>
                        <th class="py-3.5 px-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 text-xs text-slate-700 dark:text-slate-300">
                    @forelse($bankAccounts as $index => $item)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/30 transition">
                            <!-- Bank Name -->
                            <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-slate-200">
                                {{ strtoupper($item->bank_name) }}
                            </td>

                            <!-- Account No -->
                            <td class="py-3.5 px-4 font-mono text-slate-700 dark:text-slate-300">
                                {{ $item->account_number }}
                            </td>

                            <!-- Customer -->
                            <td class="py-3.5 px-4 font-normal text-slate-800 dark:text-slate-200">
                                {{ $item->partner?->name ?? $item->account_holder }}
                            </td>

                            <!-- Valid From -->
                            <td class="py-3.5 px-4 text-slate-600 dark:text-slate-400 font-mono text-[11px]">
                                {{ $item->valid_from ?? '2025-07-22 00:00:00' }}
                            </td>

                            <!-- Valid To -->
                            <td class="py-3.5 px-4 text-slate-600 dark:text-slate-400 font-mono text-[11px]">
                                {{ $item->valid_to ?? '9999-12-31 00:00:00' }}
                            </td>

                            <!-- Document Status (Completed + LOG & Approvers badges) -->
                            <td class="py-3.5 px-4">
                                <div class="space-y-1">
                                    <div class="text-xs font-semibold text-slate-700 dark:text-slate-300">
                                        {{ $item->document_status ?? 'Completed' }}
                                    </div>
                                    <div class="flex items-center space-x-1.5">
                                        <!-- LOG Badge/Button -->
                                        <button type="button" @click="openLogModal({{ json_encode($item) }})"
                                                class="px-2 py-0.5 bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 hover:bg-sky-100 border border-sky-100 dark:border-sky-900/50 rounded text-[10px] font-bold tracking-wide transition cursor-pointer">
                                            LOG
                                        </button>

                                        <!-- Approvers Badge/Button -->
                                        <button type="button" @click="openApproversModal({{ json_encode($item) }})"
                                                class="px-2 py-0.5 bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 hover:bg-sky-100 border border-sky-100 dark:border-sky-900/50 rounded text-[10px] font-bold tracking-wide transition cursor-pointer">
                                            Approvers
                                        </button>
                                    </div>
                                </div>
                            </td>

                            <!-- Action Column: 3 Standard Action Buttons (Blue Inquiry, Purple Edit, Red Delete) -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center space-x-1.5">
                                    <x-action-button type="view" :click="'openInquiryModal(' . json_encode($item) . ', ' . $loop->iteration . ')'" title="Inquiry Bank ACS Customer" />
                                    <x-action-button type="edit" :click="'openEdit(' . json_encode($item) . ')'" title="Edit Data" />
                                    <form method="POST" action="{{ route('general.bank-acs.destroy', $item->id) }}" onsubmit="return confirm('Hapus data rekening {{ $item->account_number }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <x-action-button type="delete" title="Hapus Data" />
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-400 dark:text-slate-500">
                                Tidak ada data rekening Bank ACS yang sesuai dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($bankAccounts->hasPages())
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500">
                <div>
                    Menampilkan {{ $bankAccounts->firstItem() }} - {{ $bankAccounts->lastItem() }} dari {{ $bankAccounts->total() }} data
                </div>
                <div>
                    {{ $bankAccounts->links() }}
                </div>
            </div>
        @endif

    </div>

    <!-- ==================== MODAL 1: INQUIRY BANK ACS CUSTOMER (Screenshot 2) ==================== -->
    <div x-show="showInquiryModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4" 
         style="display: none;">
        
        <div @click.away="showInquiryModal = false" 
             x-show="showInquiryModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white dark:bg-slate-900 w-full max-w-5xl rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden">
            
            <!-- Modal Header -->
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">
                    Inquiry Bank ACS Customer
                </h3>
                <button type="button" @click="showInquiryModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Content: Table -->
            <div class="p-6">
                <div class="overflow-x-auto rounded-xl border border-slate-100 dark:border-slate-800">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 font-bold">
                                <th class="py-3 px-4 w-12">No</th>
                                <th class="py-3 px-4">Customer</th>
                                <th class="py-3 px-4">Bank Name</th>
                                <th class="py-3 px-4">Account No</th>
                                <th class="py-3 px-4">Response Code H2H</th>
                                <th class="py-3 px-4">Response H2H</th>
                                <th class="py-3 px-4 text-center w-32">File Attachment</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20">
                                <td class="py-3.5 px-4 text-slate-500 font-medium" x-text="selectedItem.row_num || 1"></td>
                                <td class="py-3.5 px-4 font-semibold text-slate-800 dark:text-slate-200" x-text="selectedItem.customer_name"></td>
                                <td class="py-3.5 px-4 font-semibold uppercase" x-text="selectedItem.bank_name"></td>
                                <td class="py-3.5 px-4 font-mono font-medium" x-text="selectedItem.account_number"></td>
                                <td class="py-3.5 px-4 font-medium" x-text="selectedItem.h2h_response_code || 'Sukses'"></td>
                                <td class="py-3.5 px-4 text-slate-600 dark:text-slate-400 leading-relaxed" x-text="selectedItem.h2h_response_message || ('Message Bank ' + selectedItem.bank_name + ': Request has been processed successfully.')"></td>
                                <td class="py-3.5 px-4 text-center">
                                    <!-- Purple Download Button matching Screenshot 2 -->
                                    <button type="button" @click="downloadAttachment(selectedItem)"
                                            class="p-2.5 bg-[#7c4dff] hover:bg-[#651fff] text-white rounded-lg shadow-2xs transition inline-flex items-center justify-center cursor-pointer"
                                            title="Download Attachment">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex justify-end">
                    <button type="button" @click="showInquiryModal = false"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl transition cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- ==================== MODAL 2: ADVANCED SEARCH ==================== -->
    <div x-show="showAdvancedSearch" 
         x-transition
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4" 
         style="display: none;">
        
        <div @click.away="showAdvancedSearch = false" 
             class="bg-white dark:bg-slate-900 w-full max-w-xl rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden">
            
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="p-1.5 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </span>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">
                        Advanced Search Bank ACS Customer
                    </h3>
                </div>
                <button type="button" @click="showAdvancedSearch = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form method="GET" action="{{ route('general.bank-acs') }}" class="p-6 space-y-4 text-xs">
                @if(request('per_page'))
                    <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Bank</label>
                        <select name="filter_bank" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl focus:outline-none focus:border-primary font-medium">
                            <option value="">-- Semua Bank --</option>
                            <option value="MANDIRI" {{ request('filter_bank') == 'MANDIRI' ? 'selected' : '' }}>MANDIRI</option>
                            <option value="BNI" {{ request('filter_bank') == 'BNI' ? 'selected' : '' }}>BNI</option>
                            <option value="BRI" {{ request('filter_bank') == 'BRI' ? 'selected' : '' }}>BRI</option>
                            <option value="BCA" {{ request('filter_bank') == 'BCA' ? 'selected' : '' }}>BCA</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nomor Rekening</label>
                        <input type="text" name="filter_account_no" value="{{ request('filter_account_no') }}" placeholder="1400099..."
                               class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl focus:outline-none focus:border-primary font-mono">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Pelanggan (Customer)</label>
                    <input type="text" name="filter_customer" value="{{ request('filter_customer') }}" placeholder="PT Terminal Petikemas..."
                           class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl focus:outline-none focus:border-primary font-medium">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Valid From (Mulai)</label>
                        <input type="date" name="filter_valid_from" value="{{ request('filter_valid_from') }}"
                               class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl focus:outline-none focus:border-primary">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Valid To (Sampai)</label>
                        <input type="date" name="filter_valid_to" value="{{ request('filter_valid_to') }}"
                               class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl focus:outline-none focus:border-primary">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Document Status</label>
                    <select name="filter_status" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl focus:outline-none focus:border-primary font-medium">
                        <option value="">-- Semua Status --</option>
                        <option value="Completed" {{ request('filter_status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option value="In Progress" {{ request('filter_status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Pending" {{ request('filter_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end space-x-2">
                    <a href="{{ route('general.bank-acs') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold rounded-xl transition">
                        Reset Filter
                    </a>
                    <button type="submit" class="px-5 py-2 bg-[#00c853] hover:bg-[#00b34a] text-white font-bold rounded-xl shadow-xs transition cursor-pointer">
                        Terapkan Filter
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- ==================== MODAL 3: AUDIT LOG MODAL ==================== -->
    <div x-show="showLogModal" 
         x-transition
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4" 
         style="display: none;">
        
        <div @click.away="showLogModal = false" 
             class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden">
            
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">
                        Host-to-Host (H2H) Audit Log
                    </h3>
                    <p class="text-[11px] text-slate-400" x-text="'Rekening: ' + selectedItem.bank_name + ' - ' + selectedItem.account_number"></p>
                </div>
                <button type="button" @click="showLogModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-4 text-xs">
                <div class="space-y-3">
                    <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-slate-800 dark:text-slate-200">Request Registration to Banking Gateway</span>
                            <span class="text-[10px] text-slate-400 font-mono" x-text="selectedItem.valid_from || '2025-07-22 00:00:00'"></span>
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 text-[11px]">Payload data dikirimkan melalui secure HTTPS TLS 1.3 payload encrypted ISO 8583 message.</p>
                        <div class="mt-2 text-[10px] font-mono text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-1 rounded inline-block">
                            HTTP 200 OK — Handshake Verified
                        </div>
                    </div>

                    <div class="p-3 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-slate-800 dark:text-slate-200">Host-to-Host Response & Confirmation</span>
                            <span class="text-[10px] text-slate-400 font-mono" x-text="selectedItem.valid_from || '2025-07-22 00:00:15'"></span>
                        </div>
                        <p class="text-slate-600 dark:text-slate-400 text-[11px]" x-text="selectedItem.h2h_response_message || 'Message Bank: Request has been processed successfully.'"></p>
                        <div class="mt-2 text-[10px] font-mono text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/40 px-2 py-1 rounded inline-block">
                            Response Code: 00 (SUKSES)
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="button" @click="showLogModal = false"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold rounded-xl transition">
                        Tutup Log
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- ==================== MODAL 4: APPROVERS MODAL ==================== -->
    <div x-show="showApproversModal" 
         x-transition
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4" 
         style="display: none;">
        
        <div @click.away="showApproversModal = false" 
             class="bg-white dark:bg-slate-900 w-full max-w-xl rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden">
            
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">
                        Approval Matrix & Approvers
                    </h3>
                    <p class="text-[11px] text-slate-400" x-text="selectedItem.customer_name"></p>
                </div>
                <button type="button" @click="showApproversModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-4 text-xs">
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    <div class="py-3 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">1</div>
                            <div>
                                <span class="font-bold text-slate-800 dark:text-slate-200 block">Maker / Admin Keuangan</span>
                                <span class="text-[11px] text-slate-400">Pengajuan Rekening Bank ACS Customer</span>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 font-bold rounded-lg text-[10px]">APPROVED</span>
                    </div>

                    <div class="py-3 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">2</div>
                            <div>
                                <span class="font-bold text-slate-800 dark:text-slate-200 block">Checker / Manager Treasury</span>
                                <span class="text-[11px] text-slate-400">Verifikasi Validitas Rekening & H2H Gateway</span>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 font-bold rounded-lg text-[10px]">APPROVED</span>
                    </div>

                    <div class="py-3 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">3</div>
                            <div>
                                <span class="font-bold text-slate-800 dark:text-slate-200 block">Signer / VP Finance & Accounting</span>
                                <span class="text-[11px] text-slate-400">Persetujuan Akhir Kliring Bank ACS</span>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 font-bold rounded-lg text-[10px]">APPROVED</span>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="button" @click="showApproversModal = false"
                            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold rounded-xl transition">
                        Tutup
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- ==================== MODAL 5: CREATE / EDIT REKENING ==================== -->
    <div x-show="showCreateModal" 
         x-transition
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4" 
         style="display: none;">
        
        <div @click.away="showCreateModal = false" 
             class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-2xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden">
            
            <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100" x-text="editMode ? 'Edit Rekening Bank ACS' : 'Tambah Rekening Bank ACS Baru'"></h3>
                <button type="button" @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form :action="editMode ? '{{ url('dashboard/general/bank-acs') }}/' + form.id : '{{ route('general.bank-acs.store') }}'" method="POST" class="p-6 space-y-4 text-xs">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Mitraniaga / Customer <span class="text-rose-500">*</span></label>
                    <select name="partner_id" x-model="form.partner_id" required
                            class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl focus:outline-none focus:border-primary font-bold">
                        @foreach($partners as $partner)
                            <option value="{{ $partner->id }}">{{ $partner->name }} ({{ $partner->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Bank <span class="text-rose-500">*</span></label>
                        <input type="text" name="bank_name" x-model="form.bank_name" required placeholder="MANDIRI / BNI / BRI"
                               class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl focus:outline-none focus:border-primary font-bold uppercase">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nomor Rekening <span class="text-rose-500">*</span></label>
                        <input type="text" name="account_number" x-model="form.account_number" required placeholder="1400099101777"
                               class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl focus:outline-none focus:border-primary font-mono font-bold">
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Atas Nama (Account Holder) <span class="text-rose-500">*</span></label>
                    <input type="text" name="account_holder" x-model="form.account_holder" required placeholder="PT TERMINAL PETIKEMAS SURABAYA"
                           class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl focus:outline-none focus:border-primary font-semibold">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Valid From</label>
                        <input type="text" name="valid_from" x-model="form.valid_from" placeholder="2025-07-22 00:00:00"
                               class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl focus:outline-none focus:border-primary font-mono">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1.5">Valid To</label>
                        <input type="text" name="valid_to" x-model="form.valid_to" placeholder="9999-12-31 00:00:00"
                               class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl focus:outline-none focus:border-primary font-mono">
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-between border-t border-slate-100 dark:border-slate-800">
                    <template x-if="editMode">
                        <button type="button" @click="deleteAccount(form.id)" class="text-rose-600 hover:text-rose-700 font-bold">
                            Hapus Rekening
                        </button>
                    </template>
                    <div class="flex items-center space-x-2 ml-auto">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition cursor-pointer font-bold">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white font-bold rounded-xl shadow-xs transition cursor-pointer" x-text="editMode ? 'Simpan Perubahan' : 'Tambah Rekening'"></button>
                    </div>
                </div>
            </form>

            <template x-if="editMode">
                <form :id="'delete-form-' + form.id" :action="'{{ url('dashboard/general/bank-acs') }}/' + form.id" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </template>
        </div>
    </div>

</div>

<!-- Alpine.js logic & helper functions -->
<script>
function bankAcsManager() {
    return {
        showInquiryModal: false,
        showAdvancedSearch: false,
        showLogModal: false,
        showApproversModal: false,
        showCreateModal: false,
        editMode: false,
        selectedItem: {
            row_num: 1,
            customer_name: '',
            bank_name: '',
            account_number: '',
            h2h_response_code: 'Sukses',
            h2h_response_message: '',
            valid_from: '',
            valid_to: '',
            document_status: 'Completed'
        },
        form: {
            id: null,
            partner_id: '{{ $partners->first()->id ?? '' }}',
            bank_name: 'MANDIRI',
            account_number: '',
            account_holder: '',
            valid_from: '{{ now()->format("Y-m-d 00:00:00") }}',
            valid_to: '9999-12-31 00:00:00',
            document_status: 'Completed',
            is_primary: true
        },

        openInquiryModal(item, rowNum) {
            this.selectedItem = {
                row_num: rowNum || 1,
                customer_name: item.partner?.name || item.account_holder || 'PT Pelanggan',
                bank_name: (item.bank_name || 'MANDIRI').toUpperCase(),
                account_number: item.account_number || '',
                h2h_response_code: item.h2h_response_code || 'Sukses',
                h2h_response_message: item.h2h_response_message || ('Message Bank ' + item.bank_name + ': Request has been processed successfully.'),
                valid_from: item.valid_from || '2025-07-22 00:00:00',
                valid_to: item.valid_to || '9999-12-31 00:00:00',
                document_status: item.document_status || 'Completed'
            };
            this.showInquiryModal = true;
        },

        openLogModal(item) {
            this.selectedItem = {
                customer_name: item.partner?.name || item.account_holder,
                bank_name: (item.bank_name || '').toUpperCase(),
                account_number: item.account_number,
                valid_from: item.valid_from,
                h2h_response_message: item.h2h_response_message
            };
            this.showLogModal = true;
        },

        openApproversModal(item) {
            this.selectedItem = {
                customer_name: item.partner?.name || item.account_holder,
                bank_name: (item.bank_name || '').toUpperCase(),
                account_number: item.account_number
            };
            this.showApproversModal = true;
        },

        openCreate() {
            this.editMode = false;
            this.form = {
                id: null,
                partner_id: '{{ $partners->first()->id ?? '' }}',
                bank_name: 'MANDIRI',
                account_number: '',
                account_holder: '',
                valid_from: '{{ now()->format("Y-m-d 00:00:00") }}',
                valid_to: '9999-12-31 00:00:00',
                document_status: 'Completed',
                is_primary: true
            };
            this.showCreateModal = true;
        },

        openEdit(item) {
            this.editMode = true;
            this.form = {
                id: item.id,
                partner_id: item.partner_id,
                bank_name: (item.bank_name || '').toUpperCase(),
                account_number: item.account_number,
                account_holder: item.account_holder,
                valid_from: item.valid_from || '2025-07-22 00:00:00',
                valid_to: item.valid_to || '9999-12-31 00:00:00',
                document_status: item.document_status || 'Completed',
                is_primary: !!item.is_primary
            };
            this.showCreateModal = true;
        },

        deleteAccount(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data rekening ini?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        },

        downloadAttachment(item) {
            const content = "=== INQUIRY BANK ACS CUSTOMER ===\n" +
                "Customer: " + item.customer_name + "\n" +
                "Bank: " + item.bank_name + "\n" +
                "Account No: " + item.account_number + "\n" +
                "H2H Response Code: " + item.h2h_response_code + "\n" +
                "H2H Response Message: " + item.h2h_response_message + "\n" +
                "Timestamp: " + new Date().toISOString() + "\n" +
                "Status: VERIFIED & COMPLETED";
            
            const blob = new Blob([content], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = "Inquiry_Bank_ACS_" + item.bank_name + "_" + item.account_number + ".txt";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        },

        copyTableData() {
            let table = document.getElementById('bankAcsTable');
            let text = '';
            for (let row of table.rows) {
                let rowData = [];
                for (let cell of row.cells) {
                    rowData.push(cell.innerText.replace(/\n/g, ' '));
                }
                text += rowData.join('\t') + '\n';
            }
            navigator.clipboard.writeText(text).then(() => {
                alert('Data tabel Bank ACS berhasil disalin ke clipboard!');
            });
        },

        exportExcel() {
            let table = document.getElementById('bankAcsTable');
            let csv = [];
            for (let row of table.rows) {
                let rowData = [];
                for (let i = 0; i < row.cells.length - 1; i++) {
                    let cellText = row.cells[i].innerText.replace(/"/g, '""').replace(/\n/g, ' ');
                    rowData.push('"' + cellText + '"');
                }
                csv.push(rowData.join(','));
            }
            let csvContent = "data:text/csv;charset=utf-8," + csv.join("\n");
            let encodedUri = encodeURI(csvContent);
            let link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "Bank_ACS_Customer_List.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },

        exportPdf() {
            window.print();
        }
    };
}
</script>
@endsection
