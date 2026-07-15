@props([
    'title'
])

<div class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-100 dark:border-slate-800/80 shadow-[0_4px_20px_-3px_rgba(0,0,0,0.02)] hover:shadow-[0_10px_25px_-5px_rgba(0,0,0,0.04)] transition-shadow duration-300 flex flex-col justify-between h-full">
    
    <!-- Card Header -->
    <div class="flex items-center justify-between mb-5">
        <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200 tracking-tight leading-none">{{ $title }}</h3>
        <button class="text-[11px] font-extrabold text-[#1b3bb6] dark:text-blue-400 bg-blue-50/50 dark:bg-blue-950/30 hover:bg-[#f0f3ff] dark:hover:bg-blue-900/30 border border-blue-100/50 dark:border-blue-900/40 px-3 py-1.5 rounded-xl transition-all duration-200 focus:outline-none">
            Lihat Detail
        </button>
    </div>

    <!-- Chart Canvas Container -->
    <div class="relative flex-1 w-full min-h-0 flex items-center justify-center">
        {{ $slot }}
    </div>
</div>
