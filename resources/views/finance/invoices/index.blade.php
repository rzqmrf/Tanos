@extends('layouts.app')

@section('title', 'Invoice Management — Tanos ERP')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 dark:text-slate-400 mb-4.5">
                <a href="{{ route('dashboard.index') }}" class="hover:text-primary dark:hover:text-sky-400 transition flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Home
                </a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span>Finance & Accounting</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span>Invoice</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-primary dark:text-sky-400 font-black">Index</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                Invoice Management
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 font-medium">
                Pencatatan Faktur Tagihan dan Pembayaran (P2P & Non P2P).
            </p>
        </div>

        <a href="{{ route('invoices.create') }}"
           class="px-4 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center space-x-1.5 self-start sm:self-auto cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Create New</span>
        </a>
    </div>

    <!-- Alert Notification -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Data Table Container -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden p-6 space-y-5">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
            <h2 class="text-base font-black text-slate-800 dark:text-slate-100">
                Invoice - List
            </h2>

            <div class="flex items-center space-x-2">
                <button type="button" onclick="window.print()" title="Print"
                        class="p-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </button>
            </div>
        </div>

        <!-- Filter Controls -->
        <form method="GET" action="{{ route('invoices.index') }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-2 text-xs text-slate-600 dark:text-slate-400 font-bold">
                <span>Tampilkan</span>
                <select name="per_page" onchange="this.form.submit()"
                        class="px-2.5 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none cursor-pointer">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                </select>
                <span>entri</span>
            </div>

            <div class="flex items-center space-x-2">
                <div class="relative min-w-[240px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tipe, regional, segment..."
                           class="w-full pl-8 pr-4 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                @if(request()->filled('search'))
                <a href="{{ route('invoices.index') }}" class="p-2 text-xs font-bold text-slate-400 hover:text-slate-600">Reset</a>
                @endif
            </div>
        </form>

        <!-- Main Invoices Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-800/60 border-y border-slate-200 dark:border-slate-800 text-[11px] font-extrabold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                        <th class="py-3.5 px-3">Invoice ID</th>
                        <th class="py-3.5 px-3">Tipe Tagihan</th>
                        <th class="py-3.5 px-3">Periode Bulan</th>
                        <th class="py-3.5 px-3">Regional</th>
                        <th class="py-3.5 px-3">Segment</th>
                        <th class="py-3.5 px-3 text-right">Nominal Tagihan</th>
                        <th class="py-3.5 px-3 text-center whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($invoices as $item)
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                        <!-- Code -->
                        <td class="py-3.5 px-3 font-mono font-bold text-primary">
                            <a href="{{ route('invoices.show', $item->id) }}" class="hover:underline">
                                INV-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                            </a>
                        </td>

                        <!-- Type -->
                        <td class="py-3.5 px-3 font-bold">
                            <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-extrabold {{ $item->type === 'P2P' ? 'bg-indigo-100 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300' : 'bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300' }}">
                                {{ $item->type }}
                            </span>
                        </td>

                        <!-- Month -->
                        <td class="py-3.5 px-3 font-semibold text-slate-800 dark:text-slate-200">
                            {{ $item->month }}
                        </td>

                        <!-- Regional -->
                        <td class="py-3.5 px-3 text-slate-600 dark:text-slate-400">
                            {{ $item->regional }}
                        </td>

                        <!-- Segment -->
                        <td class="py-3.5 px-3 text-slate-600 dark:text-slate-400">
                            {{ $item->segment }}
                        </td>

                        <!-- Amount -->
                        <td class="py-3.5 px-3 text-right font-mono font-bold text-slate-900 dark:text-slate-100">
                            Rp {{ number_format($item->amount, 0, ',', '.') }}
                        </td>

                        <!-- Action -->
                        <td class="py-3.5 px-3 text-center whitespace-nowrap">
                            <div class="inline-flex items-center space-x-1.5">
                                <!-- View (Blue) -->
                                <a href="{{ route('invoices.show', $item->id) }}"
                                   style="background-color: #0091ea; color: #ffffff;"
                                   class="p-2 rounded-lg hover:opacity-90 transition shadow-2xs flex items-center justify-center" title="View Detail">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </a>

                                <!-- Edit (Purple) -->
                                <a href="{{ route('invoices.edit', $item->id) }}"
                                   style="background-color: #7c4dff; color: #ffffff;"
                                   class="p-2 rounded-lg hover:opacity-90 transition shadow-2xs flex items-center justify-center" title="Edit Invoice">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>

                                <!-- Delete (Red) -->
                                <form action="{{ route('invoices.destroy', $item->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm(&quot;Hapus data invoice ID INV-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}?&quot;)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            style="background-color: #ff1744; color: #ffffff;"
                                            class="p-2 rounded-lg hover:opacity-90 transition shadow-2xs flex items-center justify-center cursor-pointer" title="Delete Invoice">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">
                            Tidak ada data Invoice ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($invoices->hasPages())
        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
            {{ $invoices->links() }}
        </div>
        @endif

    </div>

</div>
@endsection
