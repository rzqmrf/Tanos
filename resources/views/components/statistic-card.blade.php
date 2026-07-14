@props([
    'title',
    'field',  // The key inside Alpine's stats object (e.g., totalActiveProjects, totalCost)
    'theme'   // Colors: green, purple, orange, red, blue, cyan
])

@php
    $themes = [
        'green' => 'bg-emerald-50 text-emerald-600',
        'purple' => 'bg-purple-50 text-purple-600',
        'orange' => 'bg-orange-50 text-orange-600',
        'red' => 'bg-rose-50 text-rose-600',
        'blue' => 'bg-blue-50 text-blue-600',
        'cyan' => 'bg-cyan-50 text-cyan-600',
    ];
    $themeClass = $themes[$theme] ?? $themes['blue'];
@endphp

<div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-[0_4px_15px_-3px_rgba(0,0,0,0.02)] hover:-translate-y-1 hover:shadow-[0_12px_30px_-5px_rgba(0,0,0,0.06)] hover:border-slate-200/60 transition-all duration-300 flex items-center group">
    <div class="w-12 h-12 rounded-2xl flex-shrink-0 flex items-center justify-center mr-4 transition-all duration-300 group-hover:scale-110 {{ $themeClass }}">
        {{ $slot }}
    </div>

    <div class="min-w-0">
        <span class="text-xs font-semibold text-slate-400 block mb-1 truncate">{{ $title }}</span>
        
        <span x-text="stats.{{ $field }} ? stats.{{ $field }}.formatted : '...'" class="text-2xl font-extrabold text-slate-800 tracking-tight block leading-tight">
            ...
        </span>

        <div class="flex items-center mt-1 text-[11px] font-bold"
             x-show="stats.{{ $field }}"
             :class="(stats.{{ $field }} && stats.{{ $field }}.growth >= 0) ? 'text-emerald-600' : 'text-rose-500'">

            <template x-if="stats.{{ $field }} && stats.{{ $field }}.growth >= 0">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 mr-0.5">
                    <path fill-rule="evenodd" d="M10 17a.75.75 0 0 1-.75-.75V5.612L5.29 9.77a.75.75 0 0 1-1.08-1.04l5.25-5.5a.75.75 0 0 1 1.08 0l5.25 5.5a.75.75 0 1 1-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0 1 10 17Z" clip-rule="evenodd" />
                </svg>
            </template>

            <template x-if="stats.{{ $field }} && stats.{{ $field }}.growth < 0">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 mr-0.5">
                    <path fill-rule="evenodd" d="M10 3a.75.75 0 0 1 .75.75v10.638l3.96-4.158a.75.75 0 1 1 1.08 1.04l-5.25 5.5a.75.75 0 0 1-1.08 0l-5.25-5.5a.75.75 0 1 1 1.08-1.04l3.96 4.158V3.75A.75.75 0 0 1 10 3Z" clip-rule="evenodd" />
                </svg>
            </template>

            <span x-text="stats.{{ $field }} ? Math.abs(stats.{{ $field }}.growth).toFixed(1) + '%' : '0%'"></span>
            <span class="text-slate-400 font-normal ml-1">dari bulan lalu</span>
        </div>
    </div>
</div>