@props([
    'title',
    'subtitle' => null,
    'breadcrumbs' => [],
    'createLabel' => null,
    'createClick' => null,
    'createHref' => null,
])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <!-- Breadcrumbs -->
        @if(!empty($breadcrumbs))
        <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 dark:text-slate-400 mb-4 print:hidden">
            <a href="{{ route('dashboard.index') }}" class="hover:text-primary dark:hover:text-sky-400 transition flex items-center">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                Home
            </a>
            @foreach($breadcrumbs as $label => $url)
                <span class="text-slate-300 dark:text-slate-600">/</span>
                @if($url && $url !== '#')
                    <a href="{{ $url }}" class="hover:text-primary dark:hover:text-sky-400 transition">{{ $label }}</a>
                @elseif($loop->last)
                    <span class="text-primary dark:text-sky-400 font-black">{{ $label }}</span>
                @else
                    <span>{{ $label }}</span>
                @endif
            @endforeach
        </div>
        @endif

        <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
            {{ $title }}
        </h1>
        @if($subtitle)
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 font-medium">{{ $subtitle }}</p>
        @endif
    </div>

    @if($createLabel)
        @if($createHref)
            <a href="{{ $createHref }}"
               class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 self-start sm:self-auto cursor-pointer border-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>{{ $createLabel }}</span>
            </a>
        @elseif($createClick)
            <button @click="{{ $createClick }}"
                    class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 self-start sm:self-auto cursor-pointer border-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>{{ $createLabel }}</span>
            </button>
        @endif
    @elseif(isset($action))
        {{ $action }}
    @endif
</div>
