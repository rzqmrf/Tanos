@props([
    'months' => [],
    'regionals' => [],
    'segments' => []
])

<header class="sticky top-0 bg-[#f8fafc]/80 dark:bg-slate-950/80 backdrop-blur-md border-b border-slate-100 dark:border-slate-900 z-30 px-6 py-4 flex items-center justify-between">
    
    <!-- Left Area: Hamburger for mobile + Dynamic Page Title -->
    <div class="flex items-center space-x-4 flex-1">
        <!-- Mobile Sidebar Toggle -->
        <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 focus:outline-none md:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        <h2 class="text-base font-bold text-slate-800 dark:text-slate-100 hidden md:block">
            @if(request()->routeIs('dashboard.index'))
                Dashboard
            @elseif(request()->routeIs('projects.index'))
                Projects
            @elseif(request()->routeIs('employees.index'))
                Employees
            @elseif(request()->routeIs('invoices.index'))
                Invoices
            @elseif(request()->routeIs('reports.index'))
                Reports & Analytics
            @elseif(request()->routeIs('project.config'))
                Project Configuration
            @elseif(request()->routeIs('access.controls'))
                Access Controls
            @elseif(request()->routeIs('users.index'))
                User Management
            @elseif(request()->routeIs('profile.edit'))
                Edit Profile
            @elseif(request()->routeIs('clients.index'))
                Client Management
            @elseif(request()->routeIs('attendances.index'))
                Attendance & Leaves
            @elseif(request()->routeIs('payrolls.index'))
                Payroll
            @elseif(request()->routeIs('expenses.index'))
                Expenses & Procurement
            @else
                Tanos ERP
            @endif
        </h2>
    </div>

    <!-- Right Area: Filters + Notification + Profile -->
    <div class="flex items-center space-x-3 lg:space-x-4">
        
        @if(request()->routeIs('dashboard.index'))
        <!-- Month Filter Dropdown -->
        <div class="relative flex items-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-200 shadow-sm transition-all duration-200 cursor-pointer"
             x-data="{ 
                 open: false,
                 options: [
                     { value: 'All', label: 'All Bulan' },
                     @foreach($months as $month)
                         { value: '{{ $month }}', label: '{{ $month }}' },
                     @endforeach
                 ],
                 get activeLabel() {
                     return (this.options.find(o => o.value === selectedMonth) || { label: 'All Bulan' }).label;
                 }
             }" 
             :class="open ? 'border-[#1b3bb6] ring-1 ring-[#1b3bb6] dark:border-blue-500 dark:ring-blue-500' : ''"
             @click="open = !open"
             @click.away="open = false">
            <!-- Calendar Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-400 mr-2 pointer-events-none">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
            </svg>
            <select x-model="selectedMonth" class="bg-transparent border-none p-0 pr-6 text-xs font-semibold focus:ring-0 focus:outline-none cursor-pointer appearance-none text-slate-700 dark:text-slate-200 pointer-events-none select-none">
                <option value="All" class="bg-white dark:bg-slate-900">All Bulan</option>
                @foreach($months as $month)
                    <option value="{{ $month }}" class="bg-white dark:bg-slate-900">{{ $month }}</option>
                @endforeach
            </select>
            <div class="absolute right-3 pointer-events-none text-slate-400">
                <svg class="w-3 h-3 transition-transform duration-150" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </div>

            <!-- Custom Options List -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute left-0 w-48 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xl z-40 overflow-hidden origin-top-left"
                 style="top: 100%; display: none;">
                <div class="p-1 space-y-0.5 max-h-60 overflow-y-auto">
                    <template x-for="option in options" :key="option.value">
                        <button @click.stop="selectedMonth = option.value; fetchData(); open = false" 
                                class="w-full text-left px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center justify-between cursor-pointer"
                                :class="selectedMonth === option.value ? 'bg-blue-50/80 dark:bg-blue-950/40 text-[#1b3bb6] dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/40 hover:text-slate-800 dark:hover:text-slate-100'">
                            <span x-text="option.label"></span>
                            <svg x-show="selectedMonth === option.value" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Regional Filter Dropdown -->
        <div class="relative flex items-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-200 shadow-sm transition-all duration-200 cursor-pointer"
             x-data="{ 
                 open: false,
                 options: [
                     { value: 'All', label: 'All Regional' },
                     @foreach($regionals as $reg)
                         { value: '{{ $reg }}', label: '{{ $reg }}' },
                     @endforeach
                 ],
                 get activeLabel() {
                     return (this.options.find(o => o.value === selectedRegional) || { label: 'All Regional' }).label;
                 }
             }" 
             :class="open ? 'border-[#1b3bb6] ring-1 ring-[#1b3bb6] dark:border-blue-500 dark:ring-blue-500' : ''"
             @click="open = !open"
             @click.away="open = false">
            <select x-model="selectedRegional" class="bg-transparent border-none p-0 pr-6 text-xs font-semibold focus:ring-0 focus:outline-none cursor-pointer appearance-none text-slate-700 dark:text-slate-200 pointer-events-none select-none">
                <option value="All" class="bg-white dark:bg-slate-900">All Regional</option>
                @foreach($regionals as $reg)
                    <option value="{{ $reg }}" class="bg-white dark:bg-slate-900">{{ $reg }}</option>
                @endforeach
            </select>
            <div class="absolute right-3 pointer-events-none text-slate-400">
                <svg class="w-3 h-3 transition-transform duration-150" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </div>

            <!-- Custom Options List -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute left-0 w-48 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xl z-40 overflow-hidden origin-top-left"
                 style="top: 100%; display: none;">
                <div class="p-1 space-y-0.5 max-h-60 overflow-y-auto">
                    <template x-for="option in options" :key="option.value">
                        <button @click.stop="selectedRegional = option.value; fetchData(); open = false" 
                                class="w-full text-left px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center justify-between cursor-pointer"
                                :class="selectedRegional === option.value ? 'bg-blue-50/80 dark:bg-blue-950/40 text-[#1b3bb6] dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/40 hover:text-slate-800 dark:hover:text-slate-100'">
                            <span x-text="option.label"></span>
                            <svg x-show="selectedRegional === option.value" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Segment Filter Dropdown -->
        <div class="relative flex items-center bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 dark:text-slate-200 shadow-sm transition-all duration-200 cursor-pointer"
             x-data="{ 
                 open: false,
                 options: [
                     { value: 'All', label: 'All Segment' },
                     @foreach($segments as $seg)
                         { value: '{{ $seg }}', label: '{{ $seg }}' },
                     @endforeach
                 ],
                 get activeLabel() {
                     return (this.options.find(o => o.value === selectedSegment) || { label: 'All Segment' }).label;
                 }
             }" 
             :class="open ? 'border-[#1b3bb6] ring-1 ring-[#1b3bb6] dark:border-blue-500 dark:ring-blue-500' : ''"
             @click="open = !open"
             @click.away="open = false">
            <select x-model="selectedSegment" class="bg-transparent border-none p-0 pr-6 text-xs font-semibold focus:ring-0 focus:outline-none cursor-pointer appearance-none text-slate-700 dark:text-slate-200 pointer-events-none select-none">
                <option value="All" class="bg-white dark:bg-slate-900">All Segment</option>
                @foreach($segments as $seg)
                    <option value="{{ $seg }}" class="bg-white dark:bg-slate-900">{{ $seg }}</option>
                @endforeach
            </select>
            <div class="absolute right-3 pointer-events-none text-slate-400">
                <svg class="w-3 h-3 transition-transform duration-150" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </div>

            <!-- Custom Options List -->
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute left-0 w-48 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xl z-40 overflow-hidden origin-top-left"
                 style="top: 100%; display: none;">
                <div class="p-1 space-y-0.5 max-h-60 overflow-y-auto">
                    <template x-for="option in options" :key="option.value">
                        <button @click.stop="selectedSegment = option.value; fetchData(); open = false" 
                                class="w-full text-left px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center justify-between cursor-pointer"
                                :class="selectedSegment === option.value ? 'bg-blue-50/80 dark:bg-blue-950/40 text-[#1b3bb6] dark:text-blue-400' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/40 hover:text-slate-800 dark:hover:text-slate-100'">
                            <span x-text="option.label"></span>
                            <svg x-show="selectedSegment === option.value" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </button>
                    </template>
                </div>
            </div>
        </div>
        @endif

        <!-- Dark Mode Toggle Button -->
        <button @click="toggleDarkMode()" 
                class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-700 shadow-sm transition-all duration-200"
                title="Toggle Dark Mode">
            <!-- Sun Icon (visible in Dark Mode) -->
            <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21M4.93 4.93l1.59 1.59m10.96 10.96l1.59 1.59m-11.85-.707L5.4 18.33m12.02-12.02l1.18-1.18M12 8.25a3.75 3.75 0 1 0 0 7.5 3.75 3.75 0 0 0 0-7.5Z" />
            </svg>
            <!-- Moon Icon (visible in Light Mode) -->
            <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
            </svg>
        </button>

        <!-- Notification Button & Dropdown -->
        <div class="relative" x-data="{ 
            open: false,
            notifications: [],
            unreadCount: 0,
            async fetchNotifications() {
                try {
                    const res = await fetch('/dashboard/api/notifications');
                    const data = await res.json();
                    this.notifications = data.notifications;
                    this.unreadCount = data.unreadCount;
                } catch (e) {
                    console.error(e);
                }
            },
            async markAllAsRead() {
                try {
                    await fetch('/dashboard/api/notifications/read-all', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    });
                    this.unreadCount = 0;
                    this.notifications.forEach(n => n.read_at = new Date().toISOString());
                } catch (e) {
                    console.error(e);
                }
            },
            async markAsRead(id) {
                try {
                    await fetch(`/dashboard/api/notifications/${id}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    });
                    const notif = this.notifications.find(n => n.id === id);
                    if (notif) notif.read_at = new Date().toISOString();
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                } catch (e) {
                    console.error(e);
                }
            }
        }" x-init="fetchNotifications()" @click.away="open = false">
            <button @click="open = !open"
                    class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:border-slate-300 dark:hover:border-slate-700 shadow-sm transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
                <!-- Red Badge -->
                <span x-show="unreadCount > 0" class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border border-white dark:border-slate-950"></span>
            </button>

            <!-- Notification Dropdown Panel -->
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                 style="display: none;"
                 class="absolute right-0 mt-3 w-80 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/60 dark:shadow-slate-950/60 z-50 overflow-hidden origin-top-right">

                <!-- Header -->
                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800">
                    <span class="text-sm font-bold text-slate-800 dark:text-slate-100">Notifikasi</span>
                    <span x-show="unreadCount > 0" class="text-[10px] font-bold text-white bg-red-500 rounded-full px-2 py-0.5" x-text="unreadCount + ' Baru'"></span>
                    <span x-show="unreadCount === 0" class="text-[10px] font-bold text-slate-400 bg-slate-100 dark:bg-slate-800 dark:text-slate-500 rounded-full px-2 py-0.5">Semua Dibaca</span>
                </div>

                <!-- Notification Items -->
                <div class="divide-y divide-slate-100 dark:divide-slate-800 max-h-72 overflow-y-auto">
                    <!-- Empty State -->
                    <div x-show="notifications.length === 0" class="p-6 text-center text-xs text-slate-400 dark:text-slate-500">
                        Tidak ada notifikasi baru.
                    </div>

                    <!-- Items loop -->
                    <template x-for="item in notifications" :key="item.id">
                        <div @click="markAsRead(item.id)" 
                             class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors cursor-pointer"
                             :class="item.read_at ? 'opacity-65' : ''">
                            
                            <!-- Icon depending on type -->
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                                 :class="[
                                     item.type === 'invoice' ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400' : '',
                                     item.type === 'project' ? 'bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400' : '',
                                     item.type === 'employee' ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400' : '',
                                     item.type === 'system' ? 'bg-slate-100 dark:bg-slate-800 text-slate-400' : ''
                                 ]">
                                
                                <!-- Invoice Icon -->
                                <template x-if="item.type === 'invoice'">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5-3h7.5M8.25 9.75h.008v.008H8.25V9.75Z" />
                                    </svg>
                                </template>
                                
                                <!-- Project Icon -->
                                <template x-if="item.type === 'project'">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                    </svg>
                                </template>
                                
                                <!-- Employee Icon -->
                                <template x-if="item.type === 'employee'">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                </template>
                                
                                <!-- System Icon -->
                                <template x-if="item.type === 'system'">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </template>
                            </div>
                            
                            <!-- Title & message -->
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-slate-800 dark:text-slate-200 truncate"
                                   :class="item.read_at ? 'font-semibold' : 'font-bold'"
                                   x-text="item.title"></p>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2"
                                   x-text="item.message"></p>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium mt-1"
                                   x-text="item.time_ago"></p>
                            </div>
                            
                            <!-- Unread blue dot -->
                            <span x-show="!item.read_at" class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-1.5"></span>
                        </div>
                    </template>
                </div>

                <!-- Footer -->
                <div class="px-4 py-2.5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <button @click="markAllAsRead()" class="text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 transition-colors">Tandai semua dibaca</button>
                    <a href="{{ route('notifications.page') }}" class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition-colors">Lihat semua →</a>
                </div>
            </div>
        </div>

        <!-- User Profile Button & Dropdown -->
        @php
            $dbUser = null;
            if (session()->has('user')) {
                $dbUser = \App\Models\User::where('email', session('user.username'))->first();
            }
            $navName = $dbUser?->name ?? session('user.name', 'Guest User');
            $navRole = session('user.role', 'Staff Member');
            $navWords = explode(' ', $navName);
            $navInitials = '';
            foreach ($navWords as $w) { if (!empty($w)) $navInitials .= strtoupper(substr($w, 0, 1)); }
            $navInitials = substr($navInitials, 0, 2);
        @endphp
        <div class="relative" x-data="{ open: false }" @click.away="open = false">
            <button @click="open = !open"
                    class="flex items-center space-x-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 pl-1.5 pr-3 py-1 rounded-full hover:border-slate-300 dark:hover:border-slate-700 shadow-sm transition-all duration-200">
                <!-- Avatar -->
                @if($dbUser && $dbUser->photo)
                    <img src="{{ asset('storage/' . $dbUser->photo) }}" alt="Avatar" class="w-7 h-7 rounded-full object-cover nav-avatar-img">
                @else
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-[11px] select-none nav-avatar-placeholder">
                        {{ $navInitials }}
                    </div>
                @endif
                <span class="text-xs font-bold text-slate-700 dark:text-slate-200 hidden md:block max-w-[80px] truncate">{{ $navName }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                     :class="open ? 'rotate-180' : ''"
                     class="w-3 h-3 text-slate-400 transition-transform duration-150 hidden md:block">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <!-- Profile Dropdown Panel -->
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                 style="display: none;"
                 class="absolute right-0 mt-3 w-60 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-xl shadow-slate-200/60 dark:shadow-slate-950/60 z-50 overflow-hidden origin-top-right">

                <!-- User Info Header -->
                <div class="px-4 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center space-x-3">
                    @if($dbUser && $dbUser->photo)
                        <img src="{{ asset('storage/' . $dbUser->photo) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover flex-shrink-0 nav-avatar-img">
                    @else
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-sm select-none flex-shrink-0 nav-avatar-placeholder">
                            {{ $navInitials }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate">{{ $navName }}</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium truncate">{{ $navRole }}</p>
                    </div>
                </div>

                <!-- Menu Items -->
                <div class="p-2">
                    <a href="#" @click.prevent="open = false; $dispatch('open-profile-modal')" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-800 dark:hover:text-slate-100 transition-colors group">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-blue-950/40 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                        <span>Edit Profil</span>
                    </a>

                    <a href="#" @click.prevent="open = false; $dispatch('open-password-modal')" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-800 dark:hover:text-slate-100 transition-colors group">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-blue-950/40 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </div>
                        <span>Ubah Password</span>
                    </a>

                    <a href="#" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:text-slate-800 dark:hover:text-slate-100 transition-colors group">
                        <div class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center group-hover:bg-blue-50 dark:group-hover:bg-blue-950/40 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.936 6.936 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </div>
                        <span>Pengaturan</span>
                    </a>

                    <div class="my-1.5 border-t border-slate-100 dark:border-slate-800"></div>

                    <a href="{{ route('logout') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-rose-500 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/20 transition-colors group">
                        <div class="w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-950/30 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-rose-500 dark:text-rose-400">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                            </svg>
                        </div>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</header>
