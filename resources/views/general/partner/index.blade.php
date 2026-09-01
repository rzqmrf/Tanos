@extends('layouts.app')

@section('title', 'Business Partner — Tanos ERP')

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
                <span>General</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span>Master Data</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span>Partner</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-primary dark:text-sky-400 font-black">Index</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                Business Partner
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 font-medium">
                Master Data Business Partner (Vendor, Customer, Mitraniaga BUMN & Swasta).
            </p>
        </div>

        <a href="{{ route('general.partner.create') }}"
           class="px-4 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center space-x-1.5 self-start sm:self-auto cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Create New</span>
        </a>
    </div>

    <!-- Alert Notification -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-2xl text-xs font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Data Table Container -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden p-6 space-y-5">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
            <h2 class="text-base font-black text-slate-800 dark:text-slate-100">
                Business Partner - List
            </h2>

            <!-- Export Buttons (Copy, PDF, Excel) -->
            <div class="flex items-center space-x-2">
                <button type="button" onclick="window.print()" title="Print / Copy Data"
                        class="p-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </button>
                <button type="button" onclick="alert('Exporting PDF...')" title="Export PDF"
                        class="p-2 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 text-rose-600 dark:text-rose-400 rounded-xl transition border border-rose-200 dark:border-rose-900/50 cursor-pointer flex items-center space-x-1 text-xs font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>PDF</span>
                </button>
                <button type="button" onclick="alert('Exporting Excel...')" title="Export Excel"
                        class="p-2 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 text-emerald-600 dark:text-emerald-400 rounded-xl transition border border-emerald-200 dark:border-emerald-900/50 cursor-pointer flex items-center space-x-1 text-xs font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>XLS</span>
                </button>
            </div>
        </div>

        <!-- Filter Controls (Show entries + Search) -->
        <form method="GET" action="{{ route('general.partner') }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-2 text-xs text-slate-600 dark:text-slate-400 font-bold">
                <span>Tampilkan</span>
                <select name="per_page" onchange="this.form.submit()"
                        class="px-2.5 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none cursor-pointer">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
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
                <a href="{{ route('general.partner') }}" class="p-2 text-xs font-bold text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">Reset</a>
                @endif
            </div>
        </form>

        <!-- Main Table matching Tanos Screenshot -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-800/60 border-y border-slate-200 dark:border-slate-800 text-[11px] font-extrabold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                        <th class="py-3.5 px-3 whitespace-nowrap">Partner Code</th>
                        <th class="py-3.5 px-3 min-w-[200px]">Name</th>
                        <th class="py-3.5 px-3 min-w-[220px]">Address</th>
                        <th class="py-3.5 px-3 text-center">Vendor</th>
                        <th class="py-3.5 px-3 text-center">Customer</th>
                        <th class="py-3.5 px-3 text-center">Partner Type</th>
                        <th class="py-3.5 px-3 min-w-[180px]">Description</th>
                        <th class="py-3.5 px-3">Email</th>
                        <th class="py-3.5 px-3">Phone No.1</th>
                        <th class="py-3.5 px-3">City</th>
                        <th class="py-3.5 px-3">Identity Card</th>
                        <th class="py-3.5 px-3 font-mono">NPWP</th>
                        <th class="py-3.5 px-3 text-center whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($partners as $item)
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                        <!-- Partner Code -->
                        <td class="py-3.5 px-3 font-mono font-bold text-primary">
                            <a href="{{ route('general.partner.show', $item->id) }}" class="hover:underline">
                                {{ $item->code }}
                            </a>
                        </td>

                        <!-- Name -->
                        <td class="py-3.5 px-3 font-bold text-slate-900 dark:text-slate-100">
                            <a href="{{ route('general.partner.show', $item->id) }}" class="hover:text-primary dark:hover:text-sky-400 transition">
                                {{ $item->name }}
                            </a>
                        </td>

                        <!-- Address -->
                        <td class="py-3.5 px-3 text-slate-600 dark:text-slate-400">
                            {{ $item->address ?? '-' }}
                        </td>

                        <!-- Vendor -->
                        <td class="py-3.5 px-3 text-center font-bold">
                            @if($item->is_vendor)
                                <span class="text-emerald-600 dark:text-emerald-400">Yes</span>
                            @else
                                <span class="text-slate-400">No</span>
                            @endif
                        </td>

                        <!-- Customer -->
                        <td class="py-3.5 px-3 text-center font-bold">
                            @if($item->is_customer)
                                <span class="text-emerald-600 dark:text-emerald-400">Yes</span>
                            @else
                                <span class="text-slate-400">No</span>
                            @endif
                        </td>

                        <!-- Partner Type -->
                        <td class="py-3.5 px-3 text-center font-mono font-semibold">
                            <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-[11px]">
                                {{ $item->partnerType?->code ?? '-' }}
                            </span>
                        </td>

                        <!-- Description -->
                        <td class="py-3.5 px-3 text-slate-500 dark:text-slate-400 truncate max-w-xs">
                            {{ $item->description ?? $item->name }}
                        </td>

                        <!-- Email -->
                        <td class="py-3.5 px-3 text-slate-600 dark:text-slate-400 font-mono">
                            {{ $item->email ?? '-' }}
                        </td>

                        <!-- Phone No.1 -->
                        <td class="py-3.5 px-3 font-mono">
                            {{ $item->phone_1 ?? $item->phone ?? '0' }}
                        </td>

                        <!-- City -->
                        <td class="py-3.5 px-3 font-bold text-slate-800 dark:text-slate-200">
                            {{ $item->city ?? '-' }}
                        </td>

                        <!-- Identity Card -->
                        <td class="py-3.5 px-3 font-mono text-slate-500 dark:text-slate-400">
                            {{ $item->identity_card ?? '-' }}
                        </td>

                        <!-- NPWP -->
                        <td class="py-3.5 px-3 font-mono font-semibold text-slate-800 dark:text-slate-200">
                            {{ $item->npwp ?? '-' }}
                        </td>

                        <!-- Action Buttons matching Screenshot style -->
                        <td class="py-3.5 px-3 text-center whitespace-nowrap">
                            <div class="inline-flex items-center space-x-1.5">
                                <!-- View Button (Green with magnifying glass / eye) -->
                                <a href="{{ route('general.partner.show', $item->id) }}"
                                   class="p-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition shadow-xs" title="View Partner">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </a>

                                <!-- Edit Button (Purple with pencil) -->
                                <a href="{{ route('general.partner.edit', $item->id) }}"
                                   class="p-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition shadow-xs" title="Edit Partner">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </a>

                                <!-- Delete Button (Red with trash) -->
                                <form action="{{ route('general.partner.destroy', $item->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data Partner {{ $item->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg transition shadow-xs cursor-pointer" title="Delete Partner">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="13" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            Tidak ada data Business Partner ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($partners->hasPages())
        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
            {{ $partners->links() }}
        </div>
        @endif

    </div>

</div>
@endsection
