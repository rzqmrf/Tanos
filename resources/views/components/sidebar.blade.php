@props([
    'active' => 'dashboard'
])

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            class="fixed top-0 left-0 bottom-0 w-[250px] bg-white dark:bg-slate-900 border-r border-slate-200/60 dark:border-slate-800/80 flex flex-col justify-between p-4 z-40 transition-transform duration-300 md:translate-x-0 rounded-none overflow-y-auto no-scrollbar">

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
                <span class="text-base font-bold text-slate-800 dark:text-slate-100 tracking-tight">Tanos ERP</span>
            </div>
        </div>

        @php
            $name = session('user.name', 'Guest User');
            $role = session('user.role', 'Staff Member');
            
            if (session()->has('user')) {
                $realUser = \App\Models\User::find(session('user.id'));
                if ($realUser) {
                    $name = $realUser->name;
                    $role = $realUser->role ?? 'Employee';
                }
            }

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
        <div class="px-3 py-3 mb-5 bg-slate-50/60 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800/60 rounded-2xl flex items-center space-x-3">
            <div class="relative flex-shrink-0">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold text-xs select-none shadow-sm shadow-blue-200">
                    {{ $initials }}
                </div>
                <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-slate-50 dark:ring-slate-900 bg-emerald-500" title="Online"></span>
            </div>

            <div class="min-w-0 flex-1">
                <span class="block text-xs font-bold text-slate-800 dark:text-slate-200 truncate leading-none">{{ $name }}</span>
                <span class="block text-xs font-semibold text-slate-400 dark:text-slate-500 truncate mt-1">{{ $role }}</span>
            </div>
        </div>

        @php
            $isDashboard = request()->routeIs('dashboard.index');
            $isCopilot = request()->routeIs('copilot.index');
            $isReports = request()->routeIs('reports.index');
            $isProjects = request()->routeIs('projects.index');
            $isEmployees = request()->routeIs('employees.index');
            $isInvoices = request()->routeIs('invoices.index');
            $isClients = request()->routeIs('clients.index');
            $isAttendance = request()->routeIs('attendances.index');
            $isPayroll = request()->routeIs('payrolls.index');
            $isExpenses = request()->routeIs('expenses.index');
            $isRecruitment = request()->routeIs('recruitment.index');
            $isEvaluations = request()->routeIs('evaluations.index');
            $isCertifications = request()->routeIs('certifications.index');
            $isSchedules = request()->routeIs('schedules.index');
            $isProjectConfig = request()->routeIs('project.config');
            $isAccessControls = request()->routeIs('access.controls');
            $isUsers = request()->routeIs('users.index');
            $isSettingsActive = $isProjectConfig || $isAccessControls || $isUsers;

            $isESS = request()->routeIs('ess.index');
            $isESSAdmin = request()->routeIs('ess.admin.index');

            $isOperationalActive = $isProjects || $isClients || $isSchedules;
            $isHRActive = $isEmployees || $isAttendance || $isRecruitment || $isEvaluations || $isCertifications || $isESS || $isESSAdmin;
            $isFinanceActive = $isInvoices || $isPayroll || $isExpenses;
        @endphp
        <nav x-data="{ activeGroup: '{{ $isOperationalActive ? 'operations' : ($isHRActive ? 'hr' : ($isFinanceActive ? 'finance' : ($isSettingsActive ? 'settings' : ''))) }}' }" class="space-y-6">

            @if($role === 'Employee')
                <!-- Category: Menu Utama (Employee View) -->
                <div class="space-y-1.5">
                    <!-- Menu Utama -->

                    @if(\App\Models\RolePermission::hasPermission($role, 'dashboard'))
                        <a href="{{ route('dashboard.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 group {{ $isDashboard ? 'bg-blue-50/80 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 font-semibold' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                 class="w-4.5 h-4.5 transition-colors {{ $isDashboard ? 'text-blue-500 dark:text-blue-400' : 'text-slate-400 dark:text-slate-505 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            <span class="text-sm">Dashboard</span>
                        </a>
                    @endif

                    @if(\App\Models\RolePermission::hasPermission($role, 'attendance'))
                        <a href="{{ route('attendances.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 group {{ $isAttendance ? 'bg-blue-50/80 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 font-semibold' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                 class="w-4.5 h-4.5 transition-colors {{ $isAttendance ? 'text-blue-500 dark:text-blue-400' : 'text-slate-400 dark:text-slate-505 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            <span class="text-sm">Absensi Saya</span>
                        </a>
                    @endif

                    @if(\App\Models\RolePermission::hasPermission($role, 'rekap_absensi'))
                        <a href="#" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 group text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5 text-slate-400 dark:text-slate-505 group-hover:text-slate-600 dark:group-hover:text-slate-300">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                            </svg>
                            <span class="text-sm">Rekap Absensi</span>
                        </a>
                    @endif

                    @if(\App\Models\RolePermission::hasPermission($role, 'pengajuan_cuti'))
                        <a href="{{ route('ess.index') }}" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 group {{ $isESS ? 'bg-blue-50/80 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 font-semibold' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5 {{ $isESS ? 'text-blue-500 dark:text-blue-400' : 'text-slate-400 dark:text-slate-550 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                            <span class="text-sm">Pengajuan Cuti / ESS</span>
                        </a>
                    @endif

                    @if(\App\Models\RolePermission::hasPermission($role, 'kalender'))
                        <a href="#" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 group text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5 text-slate-400 dark:text-slate-550 group-hover:text-slate-600 dark:group-hover:text-slate-300">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            <span class="text-sm">Kalender</span>
                        </a>
                    @endif

                    @if(\App\Models\RolePermission::hasPermission($role, 'laporan'))
                        <a href="#" 
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 group text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5 text-slate-400 dark:text-slate-505 group-hover:text-slate-600 dark:group-hover:text-slate-300">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25" />
                            </svg>
                            <span class="text-sm">Laporan</span>
                        </a>
                    @endif
                </div>
            @else
                <!-- Category: Dashboard & Analytics -->
                @if(\App\Models\RolePermission::hasPermission($role, 'dashboard') || \App\Models\RolePermission::hasPermission($role, 'reports'))
                    <div class="space-y-1.5">
                        <!-- Dashboard & Analytics -->

                        @if(\App\Models\RolePermission::hasPermission($role, 'dashboard'))
                            <a href="{{ route('dashboard.index') }}" 
                               class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 group {{ $isDashboard ? 'bg-blue-50/80 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 font-semibold' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                     class="w-4.5 h-4.5 transition-colors {{ $isDashboard ? 'text-blue-500 dark:text-blue-400' : 'text-slate-400 dark:text-slate-505 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                </svg>
                                <span class="text-sm">Dashboard</span>
                            </a>
                            <a href="{{ route('copilot.index') }}" 
                               class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 group {{ $isCopilot ? 'bg-blue-50/80 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 font-semibold' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                     class="w-4.5 h-4.5 transition-colors {{ $isCopilot ? 'text-blue-500 dark:text-blue-400' : 'text-slate-400 dark:text-slate-505 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l-.813-5.096L3 15l5.096-.813L9 9l.813 5.096L15 15l-5.187.904zM18.097 5.196L17.5 8l-.597-2.804L14 5l2.903-.597L17.5 2l.597 2.403L21 5l-2.903.196zM11.666 4.086l-.416 1.914-.416-1.914L9 3.5l1.834-.416.416-1.914.416 1.914L13.5 3.5l-1.834.586z" />
                                </svg>
                                <span class="text-sm">Tanos AI Copilot</span>
                            </a>
                        @endif

                        @if(\App\Models\RolePermission::hasPermission($role, 'reports'))
                            <a href="{{ route('reports.index') }}" 
                               class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 group {{ $isReports ? 'bg-blue-50/80 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 font-semibold' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                     class="w-4.5 h-4.5 transition-colors {{ $isReports ? 'text-blue-500 dark:text-blue-400' : 'text-slate-400 dark:text-slate-505 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v5.25c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 3 18.375v-5.25ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125v-9.75ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v14.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                                </svg>
                                <span class="text-sm">Laporan & Analitik</span>
                            </a>
                        @endif
                    </div>
                @endif

                <!-- Category: Operasional -->
                @if(\App\Models\RolePermission::hasPermission($role, 'projects') || \App\Models\RolePermission::hasPermission($role, 'clients') || \App\Models\RolePermission::hasPermission($role, 'schedules'))
                    <div class="space-y-3">
                        <!-- Operasional -->

                        <!-- Group: Operations -->
                        <div class="relative">
                            <button @click="activeGroup = activeGroup === 'operations' ? '' : 'operations'"
                                    :class="(activeGroup === 'operations' || {{ $isOperationalActive ? 'true' : 'false' }}) ? 'text-blue-600 dark:text-blue-400 bg-slate-50/50 dark:bg-slate-800/20' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40'"
                                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold transition-all duration-150 group cursor-pointer">
                                <div class="flex items-center space-x-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                         :class="(activeGroup === 'operations' || {{ $isOperationalActive ? 'true' : 'false' }}) ? 'text-blue-500 dark:text-blue-400' : 'text-slate-400 dark:text-slate-505 group-hover:text-slate-600 dark:group-hover:text-slate-300'"
                                         class="w-4.5 h-4.5 transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                    </svg>
                                    <span class="text-sm">Operations</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                     :class="activeGroup === 'operations' ? 'rotate-180 text-slate-600 dark:text-slate-300' : 'text-slate-400 dark:text-slate-505 group-hover:text-slate-600 dark:group-hover:text-slate-300'"
                                     class="w-3 h-3 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeGroup === 'operations'"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-1"
                                 class="mt-1 pl-9 space-y-1"
                                 style="display: none;">
                                @if(\App\Models\RolePermission::hasPermission($role, 'projects'))
                                    <a href="{{ route('projects.index') }}" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors {{ $isProjects ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                        Projects
                                    </a>
                                    <a href="#" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                                        Master Project
                                    </a>
                                    <a href="#" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                                        Budgeting
                                    </a>
                                    <a href="#" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                                        Nota
                                    </a>
                                @endif
                                @if(\App\Models\RolePermission::hasPermission($role, 'clients'))
                                    <a href="{{ route('clients.index') }}" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors {{ $isClients ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                        Clients
                                    </a>
                                @endif
                                @if(\App\Models\RolePermission::hasPermission($role, 'schedules'))
                                    <a href="{{ route('schedules.index') }}" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors {{ $isSchedules ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                        Shift Scheduling
                                    </a>
                                @endif
                                <div x-data="{ subOpen: false }">
                                    <button @click="subOpen = !subOpen" class="w-full flex items-center justify-between py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                                        <span>Material</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="subOpen ? 'rotate-180' : ''" class="w-2.5 h-2.5 transition-transform duration-150">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                    <div x-show="subOpen" x-transition class="pl-3 mt-1 space-y-1" style="display: none;">
                                        <a href="#" class="block py-1 px-2 text-xs font-medium rounded-lg transition-colors text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800/40">Equipment Master</a>
                                        <a href="#" class="block py-1 px-2 text-xs font-medium rounded-lg transition-colors text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800/40">Outline Agreement</a>
                                    </div>
                                </div>
                                <a href="#" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                                    Monitoring API
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Category: Human Resources -->
                @if(\App\Models\RolePermission::hasPermission($role, 'employees') || \App\Models\RolePermission::hasPermission($role, 'attendance') || \App\Models\RolePermission::hasPermission($role, 'recruitment') || \App\Models\RolePermission::hasPermission($role, 'evaluations') || \App\Models\RolePermission::hasPermission($role, 'certifications') || \App\Models\RolePermission::hasPermission($role, 'rekap_absensi') || \App\Models\RolePermission::hasPermission($role, 'pengajuan_cuti') || \App\Models\RolePermission::hasPermission($role, 'kalender') || \App\Models\RolePermission::hasPermission($role, 'laporan'))
                    <div class="space-y-3">
                        <!-- Human Resources -->

                        <!-- Group: HR -->
                        <div class="relative">
                            <button @click="activeGroup = activeGroup === 'hr' ? '' : 'hr'"
                                    :class="(activeGroup === 'hr' || {{ $isHRActive ? 'true' : 'false' }}) ? 'text-blue-600 dark:text-blue-400 bg-slate-50/50 dark:bg-slate-800/20' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40'"
                                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold transition-all duration-150 group cursor-pointer">
                                <div class="flex items-center space-x-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                         :class="(activeGroup === 'hr' || {{ $isHRActive ? 'true' : 'false' }}) ? 'text-blue-500 dark:text-blue-400' : 'text-slate-400 dark:text-slate-505 group-hover:text-slate-600 dark:group-hover:text-slate-300'"
                                         class="w-4.5 h-4.5 transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766v-.109A12.318 12.318 0 0 1 9.374 18c2.331 0 4.512.645 6.374 1.766Zm-3-12.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM18 10.5a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                    </svg>
                                    <span class="text-sm">Human Resources</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                     :class="activeGroup === 'hr' ? 'rotate-180 text-slate-600 dark:text-slate-300' : 'text-slate-400 dark:text-slate-505 group-hover:text-slate-600 dark:group-hover:text-slate-300'"
                                     class="w-3 h-3 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeGroup === 'hr'"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-1"
                                 class="mt-1 pl-9 space-y-1"
                                 style="display: none;">
                                @if(\App\Models\RolePermission::hasPermission($role, 'employees'))
                                    <a href="{{ route('employees.index') }}" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors {{ $isEmployees ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                        Employees
                                    </a>
                                    <a href="#" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                                        Organizational Structure
                                    </a>
                                    <a href="#" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                                        Career
                                    </a>
                                @endif
                                @if(\App\Models\RolePermission::hasPermission($role, 'attendance'))
                                    <a href="{{ route('attendances.index') }}" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors {{ $isAttendance ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                        Attendance / Time Mgt
                                    </a>
                                    <a href="{{ route('ess.index') }}" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors {{ $isESS ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                        Employee Self Service
                                    </a>
                                    @if(in_array($role, ['Admin', 'HR']))
                                        <a href="{{ route('ess.admin.index') }}" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors {{ $isESSAdmin ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                            ESS Approvals
                                        </a>
                                    @endif
                                @endif
                                @if(\App\Models\RolePermission::hasPermission($role, 'recruitment'))
                                    <a href="{{ route('recruitment.index') }}" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors {{ $isRecruitment ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                        Recruitment
                                    </a>
                                @endif
                                @if(\App\Models\RolePermission::hasPermission($role, 'evaluations'))
                                    <a href="{{ route('evaluations.index') }}" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors {{ $isEvaluations ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                        Performance Appraisal
                                    </a>
                                @endif
                                @if(\App\Models\RolePermission::hasPermission($role, 'certifications'))
                                    <a href="{{ route('certifications.index') }}" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors {{ $isCertifications ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                        Training & Certs
                                    </a>
                                @endif
                                @if(\App\Models\RolePermission::hasPermission($role, 'rekap_absensi'))
                                    <a href="#" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                                        Rekap Absensi
                                    </a>
                                @endif
                                @if(\App\Models\RolePermission::hasPermission($role, 'pengajuan_cuti'))
                                    <a href="#" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                                        Pengajuan Cuti
                                    </a>
                                @endif
                                @if(\App\Models\RolePermission::hasPermission($role, 'kalender'))
                                    <a href="#" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                                        Kalender
                                    </a>
                                @endif
                                @if(\App\Models\RolePermission::hasPermission($role, 'laporan'))
                                    <a href="#" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                                        Laporan
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Category: Keuangan -->
                @if(\App\Models\RolePermission::hasPermission($role, 'invoices') || \App\Models\RolePermission::hasPermission($role, 'payroll') || \App\Models\RolePermission::hasPermission($role, 'expenses'))
                    <div class="space-y-1.5">
                        <!-- Keuangan -->

                        <!-- Group: Finance -->
                        <div class="relative">
                            <button @click="activeGroup = activeGroup === 'finance' ? '' : 'finance'"
                                    :class="(activeGroup === 'finance' || {{ $isFinanceActive ? 'true' : 'false' }}) ? 'text-blue-600 dark:text-blue-400 bg-slate-50/50 dark:bg-slate-800/20' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40'"
                                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold transition-all duration-150 group cursor-pointer">
                                <div class="flex items-center space-x-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                         :class="(activeGroup === 'finance' || {{ $isFinanceActive ? 'true' : 'false' }}) ? 'text-blue-500 dark:text-blue-400' : 'text-slate-400 dark:text-slate-505 group-hover:text-slate-600 dark:group-hover:text-slate-300'"
                                         class="w-4.5 h-4.5 transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-19.5 5.25h19.5m-19.5 0h19.5M2.25 12h19.5m-19.5 0h19.5m-19.5 5.25h19.5m-19.5 0h19.5M3 19.5h18a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 21 4.5H3a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 3 19.5Z" />
                                    </svg>
                                    <span class="text-sm">Finance</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                     :class="activeGroup === 'finance' ? 'rotate-180 text-slate-600 dark:text-slate-300' : 'text-slate-400 dark:text-slate-550 group-hover:text-slate-600 dark:group-hover:text-slate-300'"
                                     class="w-3.5 h-3.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeGroup === 'finance'"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-1"
                                 class="mt-1 pl-9 space-y-1"
                                 style="display: none;">
                                @if(\App\Models\RolePermission::hasPermission($role, 'invoices'))
                                    <a href="{{ route('invoices.index') }}" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors {{ $isInvoices ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                        Invoices
                                    </a>
                                @endif
                                @if(\App\Models\RolePermission::hasPermission($role, 'payroll'))
                                    <a href="{{ route('payrolls.index') }}" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors {{ $isPayroll ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                        Payroll
                                    </a>
                                @endif
                                @if(\App\Models\RolePermission::hasPermission($role, 'expenses'))
                                    <a href="{{ route('expenses.index') }}" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors {{ $isExpenses ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                        Expenses
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Category: Pengaturan -->
                @if(\App\Models\RolePermission::hasPermission($role, 'settings'))
                    <div class="space-y-1.5">
                        <!-- Pengaturan -->

                        <!-- Group: Settings -->
                        <div class="relative">
                            <button @click="activeGroup = activeGroup === 'settings' ? '' : 'settings'"
                                    :class="(activeGroup === 'settings' || {{ $isSettingsActive ? 'true' : 'false' }}) ? 'text-blue-600 dark:text-blue-400 bg-slate-50/50 dark:bg-slate-800/20' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40'"
                                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold transition-all duration-150 group cursor-pointer">
                                <div class="flex items-center space-x-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                         :class="(activeGroup === 'settings' || {{ $isSettingsActive ? 'true' : 'false' }}) ? 'text-blue-500 dark:text-blue-400' : 'text-slate-400 dark:text-slate-550 group-hover:text-slate-600 dark:group-hover:text-slate-300'"
                                         class="w-4.5 h-4.5 transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.936 6.936 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                    <span class="text-sm">General Settings</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                     :class="activeGroup === 'settings' ? 'rotate-180 text-slate-600 dark:text-slate-300' : 'text-slate-400 dark:text-slate-550 group-hover:text-slate-600 dark:group-hover:text-slate-300'"
                                     class="w-3.5 h-3.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeGroup === 'settings'"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 -translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-1"
                                 class="mt-1 pl-9 space-y-1"
                                 style="display: none;">
                                <a href="{{ route('project.config') }}" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors {{ $isProjectConfig ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                    Project Config
                                </a>
                                <a href="{{ route('access.controls') }}" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors {{ $isAccessControls ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                    Access Controls
                                </a>
                                <a href="{{ route('users.index') }}" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors {{ $isUsers ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                    User Management
                                </a>
                                <div x-data="{ subOpen: false }">
                                    <button @click="subOpen = !subOpen" class="w-full flex items-center justify-between py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                                        <span>Master Data</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="subOpen ? 'rotate-180' : ''" class="w-2.5 h-2.5 transition-transform duration-150">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                    <div x-show="subOpen" x-transition class="pl-3 mt-1 space-y-1" style="display: none;">
                                        <a href="#" class="block py-1 px-2 text-xs font-medium rounded-lg transition-colors text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800/40">Data Divisi</a>
                                        <a href="#" class="block py-1 px-2 text-xs font-medium rounded-lg transition-colors text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800/40">Data Jabatan</a>
                                        <a href="#" class="block py-1 px-2 text-xs font-medium rounded-lg transition-colors text-slate-400 dark:text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800/40">Data Lokasi</a>
                                    </div>
                                </div>
                                <a href="#" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                                    Master FA
                                </a>
                                <a href="#" class="block py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800">
                                    Setting FA
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </nav>
    </div>

</aside>
