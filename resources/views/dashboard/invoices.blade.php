@extends('layouts.app')

@section('title', 'Master Data: Invoices — Tanos ERP')

@section('content')
<div x-data="{ 
    showCreateModal: false,
    showEditModal: false,
    editInvoice: {
        id: '',
        type: '',
        month: '',
        regional: '',
        segment: '',
        amount: ''
    },
    openEditModal(invoice) {
        this.editInvoice = { ...invoice };
        this.showEditModal = true;
    }
}" class="space-y-6">

    <!-- Top Alert Notification -->
    @if(session('success'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="flex items-center justify-between p-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200/50 dark:border-emerald-800/30 rounded-2xl text-emerald-800 dark:text-emerald-300 text-sm font-semibold shadow-sm">
            <div class="flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 text-emerald-600 dark:text-emerald-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-200 focus:outline-none">
                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 18 18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-6 shadow-sm">
        <div class="flex items-center space-x-4">
            <div class="p-3 bg-violet-50 dark:bg-violet-950/40 text-violet-600 dark:text-violet-400 rounded-2xl">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-19.5 5.25h19.5m-19.5 0h19.5M2.25 12h19.5m-19.5 0h19.5m-19.5 5.25h19.5m-19.5 0h19.5M3 19.5h18a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 21 4.5H3a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 3 19.5Z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Master Data: Invoices</h1>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Kelola data tagihan, tipe pembayaran P2P & Non P2P, serta nominal invoice Pelindo.</p>
            </div>
        </div>
        <button @click="showCreateModal = true" class="flex items-center justify-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-4 py-3 rounded-xl shadow-md shadow-blue-500/10 transition-all duration-150 cursor-pointer self-start sm:self-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4.5 h-4.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tambah Invoice Baru</span>
        </button>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-5 shadow-sm">
        <form method="GET" action="{{ route('invoices.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3.5 items-end">
            <!-- Filter Month -->
            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Bulan</label>
                <select name="month" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                    <option value="All">Semua Bulan</option>
                    @foreach($months as $m)
                        <option value="{{ $m }}" {{ $currentMonth == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Regional -->
            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Regional</label>
                <select name="regional" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                    <option value="All">Semua Regional</option>
                    @foreach($regionals as $r)
                        <option value="{{ $r }}" {{ $currentRegional == $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Segment -->
            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Segment</label>
                <select name="segment" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                    <option value="All">Semua Segment</option>
                    @foreach($segments as $s)
                        <option value="{{ $s }}" {{ $currentSegment == $s ? 'selected' : '' }}>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Type -->
            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Tipe Tagihan</label>
                <select name="type" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                    <option value="All" {{ $currentType == 'All' ? 'selected' : '' }}>Semua Tipe</option>
                    <option value="P2P" {{ $currentType == 'P2P' ? 'selected' : '' }}>P2P (Peer-to-Peer)</option>
                    <option value="Non P2P" {{ $currentType == 'Non P2P' ? 'selected' : '' }}>Non P2P</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 justify-center flex items-center space-x-1.5 bg-slate-850 hover:bg-slate-950 dark:bg-slate-800 dark:hover:bg-slate-700 text-white font-semibold text-xs px-4 py-2.5 rounded-xl cursor-pointer transition-colors h-[38px]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                    </svg>
                    <span>Cari</span>
                </button>
                <a href="{{ route('invoices.index') }}" class="justify-center flex items-center bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs px-3 py-2.5 rounded-xl cursor-pointer transition-colors h-[38px]" title="Reset Filters">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                </a>
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-950/30 border-b border-slate-100 dark:border-slate-800/60">
                        <th class="px-6 py-4.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest w-24">Nomor</th>
                        <th class="px-6 py-4.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Detail Tagihan / Tipe</th>
                        <th class="px-6 py-4.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest w-32">Periode</th>
                        <th class="px-6 py-4.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest w-40">Regional</th>
                        <th class="px-6 py-4.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest w-36">Segment</th>
                        <th class="px-6 py-4.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-right w-44">Jumlah Nominal</th>
                        <th class="px-6 py-4.5 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($invoices as $invoice)
                        @php
                            $invNum = "INV/2025/" . str_pad($invoice->id, 6, "0", STR_PAD_LEFT);
                        @endphp
                        <tr class="hover:bg-slate-50/40 dark:hover:bg-slate-800/20 transition-colors">
                            <td class="px-6 py-4 text-xs font-bold text-slate-400 dark:text-slate-500">
                                {{ $invNum }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 dark:text-slate-200 text-xs">
                                    Tagihan Port Modernization
                                </div>
                                <div class="text-[10px] mt-1">
                                    @if($invoice->type === 'P2P')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400">
                                            P2P (Peer-to-Peer)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-bold bg-violet-50 dark:bg-violet-950/40 text-violet-600 dark:text-violet-400">
                                            Non P2P
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-600 dark:text-slate-300">
                                {{ $invoice->month }}
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-600 dark:text-slate-300">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                    {{ $invoice->regional }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-600 dark:text-slate-300">
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                    {{ $invoice->segment }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-slate-800 dark:text-slate-100 text-right">
                                Rp {{ number_format($invoice->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <!-- Edit Button -->
                                    <button @click="openEditModal({
                                                id: '{{ $invoice->id }}',
                                                type: '{{ $invoice->type }}',
                                                month: '{{ $invoice->month }}',
                                                regional: '{{ $invoice->regional }}',
                                                segment: '{{ $invoice->segment }}',
                                                amount: '{{ $invoice->amount }}'
                                            })" 
                                            class="p-1.5 text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer" 
                                            title="Edit Invoice">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.83 20.013a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>

                                    <!-- Delete Form -->
                                    <form method="POST" action="{{ route('invoices.destroy', $invoice->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data invoice ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 dark:text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors cursor-pointer" title="Hapus Invoice">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-slate-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                    </svg>
                                    <span class="text-xs font-semibold">Tabel data tagihan invoice kosong atau filter tidak cocok.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Area -->
        @if($invoices->hasPages())
            <div class="px-6 py-4.5 bg-slate-50/50 dark:bg-slate-950/20 border-t border-slate-100 dark:border-slate-800/60">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>

    <!-- CREATE INVOICE MODAL -->
    <div x-show="showCreateModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/50 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-3xl border border-slate-100 dark:border-slate-800 shadow-2xl overflow-hidden"
             @click.away="showCreateModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="scale-95"
             x-transition:enter-end="scale-100">
            
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                <span class="text-sm font-bold text-slate-800 dark:text-slate-100">Tambah Invoice Baru</span>
                <button @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('invoices.store') }}" class="p-6 space-y-4">
                @csrf
                <!-- Type -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Tipe Tagihan</label>
                    <select name="type" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="P2P">P2P (Peer-to-Peer)</option>
                        <option value="Non P2P">Non P2P</option>
                    </select>
                </div>

                <!-- Bulan -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Periode Bulan</label>
                    <select name="month" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @foreach($months as $m)
                            <option value="{{ $m }}">{{ $m }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Regional -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Regional</label>
                    <select name="regional" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @foreach($regionals as $r)
                            <option value="{{ $r }}">{{ $r }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Segment -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Segment</label>
                    <select name="segment" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @foreach($segments as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Nominal Tagihan (Rp)</label>
                    <input type="number" name="amount" required min="0" placeholder="Contoh: 500000000" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showCreateModal = false" class="flex-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-850 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs px-4 py-3 rounded-xl cursor-pointer transition-colors text-center">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-4 py-3 rounded-xl cursor-pointer shadow-md shadow-blue-500/10 transition-colors text-center">
                        Simpan Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT INVOICE MODAL -->
    <div x-show="showEditModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/50 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-3xl border border-slate-100 dark:border-slate-800 shadow-2xl overflow-hidden"
             @click.away="showEditModal = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="scale-95"
             x-transition:enter-end="scale-100">
            
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
                <span class="text-sm font-bold text-slate-800 dark:text-slate-100">Edit Invoice #<span x-text="editInvoice.id"></span></span>
                <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 18 18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form method="POST" :action="'/invoices/' + editInvoice.id" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <!-- Type -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Tipe Tagihan</label>
                    <select name="type" x-model="editInvoice.type" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        <option value="P2P">P2P (Peer-to-Peer)</option>
                        <option value="Non P2P">Non P2P</option>
                    </select>
                </div>

                <!-- Bulan -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Periode Bulan</label>
                    <select name="month" x-model="editInvoice.month" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @foreach($months as $m)
                            <option value="{{ $m }}">{{ $m }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Regional -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Regional</label>
                    <select name="regional" x-model="editInvoice.regional" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @foreach($regionals as $r)
                            <option value="{{ $r }}">{{ $r }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Segment -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Segment</label>
                    <select name="segment" x-model="editInvoice.segment" required class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                        @foreach($segments as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-1.5 pl-1">Nominal Tagihan (Rp)</label>
                    <input type="number" name="amount" x-model="editInvoice.amount" required min="0" placeholder="Contoh: 500000000" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2.5 text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showEditModal = false" class="flex-1 bg-slate-100 hover:bg-slate-200 dark:bg-slate-850 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold text-xs px-4 py-3 rounded-xl cursor-pointer transition-colors text-center">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs px-4 py-3 rounded-xl cursor-pointer shadow-md shadow-blue-500/10 transition-colors text-center">
                        Perbarui Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection