@props([
    'active' => 'dashboard'
])

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            class="fixed top-0 left-0 bottom-0 w-[250px] bg-white border-r border-slate-200/60 flex flex-col justify-between p-4 z-40 transition-transform duration-300 md:translate-x-0 rounded-none">

    <div>
        <div class="flex items-center px-2 py-3 mb-3 min-h-[48px]">
            <img src="{{ asset('images/logo.png') }}"
                    alt="Tanos Logo"
                    class="h-8 object-contain max-w-[150px]"
                    onerror="this.style.display='none'; document.getElementById('sidebar-logo-fallback').classList.remove('hidden');">

            <div id="sidebar-logo-fallback" class="hidden flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center text-white font-black text-lg shadow-sm shadow-blue-200 select-none">
                    T
                </div>
                <span class="text-base font-bold text-slate-800 tracking-tight">Tanos ERP</span>
            </div>
        </div>

        @php
            $name = session('user.name', 'Guest User');
            $role = session('user.role', 'Staff Member');

            // Extract initials (first letters of the first two words)
            $words = explode(' ', $name);
            $initials = '';
            foreach ($words as $w) {
                if (!empty($w)) {
                    $initials .= strtoupper(substr($w, 0, 1));
                }
            }
            $initials = substr($initials, 0, 2);
        @endphp
        <div class="px-3 py-3 mb-5 bg-slate-50/60 border border-slate-100 rounded-2xl flex items-center space-x-3">
            <div class="relative flex-shrink-0">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xs select-none shadow-sm shadow-blue-200">
                    {{ $initials }}
                </div>
                <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-slate-50 bg-emerald-500" title="Online"></span>
            </div>

            <div class="min-w-0 flex-1">
                <span class="block text-xs font-bold text-slate-800 truncate leading-none">{{ $name }}</span>
                <span class="block text-[10px] font-semibold text-slate-400 truncate mt-1">{{ $role }}</span>
            </div>
        </div>

        <nav class="space-y-6">

            <div class="space-y-1.5">
                <span class="text-[10px] font-bold text-slate-400/80 uppercase tracking-widest px-2.5 block mb-2">Main Menu</span>

                <a href="{{ route('dashboard.index') }}" class="flex items-center space-x-3 bg-blue-50/80 text-blue-600 px-3 py-2.5 rounded-xl font-bold transition-all duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span class="text-xs">Dashboard</span>
                </a>

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="w-full flex items-center justify-between text-slate-500 hover:text-slate-800 hover:bg-slate-50/80 px-3 py-2.5 rounded-xl font-semibold transition-all duration-150 group cursor-pointer">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5 text-slate-400 group-hover:text-slate-600 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.936 6.936 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                             </svg>
                            <span class="text-xs">General</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                             :class="open ? 'rotate-180 text-slate-600' : 'text-slate-400 group-hover:text-slate-600'"
                             class="w-3 h-3 transition-transform duration-150">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 -translate-y-1"
                         class="mt-1 pl-9 space-y-1"
                         style="display: none;">
                        <a href="{{ route('project.config') }}" class="block py-1.5 px-2 text-[11px] font-semibold text-slate-500 hover:text-blue-600 hover:bg-slate-50 rounded-lg transition-colors">
                            Project Config
                        </a>
                        <a href="{{ route('access.controls') }}" class="block py-1.5 px-2 text-[11px] font-semibold text-slate-500 hover:text-blue-600 hover:bg-slate-50 rounded-lg transition-colors">
                            Access Controls
                        </a>
                    </div>
                </div>
            </div>

            <div class="space-y-1.5">
                <span class="text-[10px] font-bold text-slate-400/80 uppercase tracking-widest px-2.5 block mb-2">Master Data</span>

                <a href="{{ route('projects.index') }}" class="flex items-center space-x-3 text-slate-500 hover:text-slate-800 hover:bg-slate-50/80 px-3 py-2.5 rounded-xl font-semibold transition-all duration-150 group">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5 text-slate-400 group-hover:text-slate-600 transition-colors">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75c.621 0 1.125.504 1.125 1.125v1.875c0 .621-.504 1.125-1.125 1.125H5.625A1.125 1.125 0 0 1 4.5 7.5V5.625C4.5 5.004 5.004 4.5 5.625 4.5Z" />
                    </svg>
                    <span class="text-xs">Projects</span>
                </a>

                <a href="{{ route('employees.index') }}" class="flex items-center space-x-3 text-slate-500 hover:text-slate-800 hover:bg-slate-50/80 px-3 py-2.5 rounded-xl font-semibold transition-all duration-150 group">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5 text-slate-400 group-hover:text-slate-600 transition-colors">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span class="text-xs">Employees</span>
                </a>

                <a href="{{ route('invoices.index') }}" class="flex items-center space-x-3 text-slate-500 hover:text-slate-800 hover:bg-slate-50/80 px-3 py-2.5 rounded-xl font-semibold transition-all duration-150 group">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5 text-slate-400 group-hover:text-slate-600 transition-colors">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-19.5 5.25h19.5m-19.5 0h19.5M2.25 12h19.5m-19.5 0h19.5m-19.5 5.25h19.5m-19.5 0h19.5M3 19.5h18a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 21 4.5H3a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 3 19.5Z" />
                    </svg>
                    <span class="text-xs">Invoices</span>
                </a>
            </div>
        </nav>
    </div>

    <div class="border-t border-slate-100 pt-3">
        <a href="{{ route('logout') }}" class="w-full flex items-center space-x-3 text-slate-500 hover:text-rose-600 hover:bg-rose-50/50 px-3 py-2.5 rounded-xl font-semibold transition-all duration-150 group">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5 text-slate-400 group-hover:text-rose-500 transition-colors">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
            </svg>
            <span class="text-xs">Logout</span>
        </a>
    </div>
</aside>