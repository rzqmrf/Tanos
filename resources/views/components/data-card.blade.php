@props([
    'title',
    'total' => null,
    'searchRoute' => null,
    'showExport' => true,
    'showPerPage' => true,
    'showSearch' => true,
])

<div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden p-6 space-y-5">
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
        <div class="flex items-center space-x-2">
            <h2 class="text-base font-black text-slate-800 dark:text-slate-100">
                {{ $title }}
            </h2>
            @if($total !== null)
            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                {{ $total }} total data
            </span>
            @endif
        </div>

        @if($showExport)
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
        @endif
    </div>

    @if($searchRoute || $showPerPage || $showSearch)
    <!-- Filter Controls (Show entries + Search) -->
    <form method="GET" action="{{ $searchRoute ?? url()->current() }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        @if($showPerPage)
        <div class="flex items-center space-x-2 text-xs text-slate-600 dark:text-slate-400 font-bold">
            <span>Tampilkan</span>
            <select name="per_page" onchange="this.form.submit()"
                    class="px-2.5 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none cursor-pointer">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
            <span>entri per halaman</span>
        </div>
        @endif

        @if($showSearch)
        <div class="flex items-center space-x-2">
            <div class="relative min-w-[240px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search:"
                       class="w-full pl-8 pr-4 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/20">
                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            @if(request()->filled('search'))
            <a href="{{ $searchRoute ?? url()->current() }}" class="p-2 text-xs font-bold text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">Reset</a>
            @endif
        </div>
        @endif
    </form>
    @endif

    {{ $slot }}

</div>
