@props([
    'active' => 'dashboard'
])

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
            class="fixed top-0 left-0 bottom-0 w-[250px] bg-white dark:bg-slate-900 border-r border-slate-200/60 dark:border-slate-800/80 flex flex-col justify-between p-4 z-40 transition-transform duration-300 md:translate-x-0 rounded-none">

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
                <span class="block text-[10px] font-semibold text-slate-400 dark:text-slate-500 truncate mt-1">{{ $role }}</span>
            </div>
        </div>

        @php
            $isDashboard = request()->routeIs('dashboard.index');
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

            $isOperationalActive = $isProjects || $isClients || $isSchedules;
            $isHRActive = $isEmployees || $isAttendance || $isRecruitment || $isEvaluations || $isCertifications;
            $isFinanceActive = $isInvoices || $isPayroll || $isExpenses;
        @endphp
        <nav x-data="{ activeGroup: '{{ $isOperationalActive ? 'operations' : ($isHRActive ? 'hr' : ($isFinanceActive ? 'finance' : ($isSettingsActive ? 'settings' : ''))) }}' }" class="space-y-6">

            <!-- Category: Dashboard & Analytics -->
            <div class="space-y-1.5">
                <span class="text-[10px] font-bold text-slate-400/80 uppercase tracking-widest px-2.5 block mb-2">Dashboard & Analytics</span>

                <a href="{{ route('dashboard.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 group {{ $isDashboard ? 'bg-blue-50/80 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 font-semibold' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                         class="w-4.5 h-4.5 transition-colors {{ $isDashboard ? 'text-blue-500 dark:text-blue-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span class="text-xs">Dashboard</span>
                </a>

                <a href="{{ route('reports.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 group {{ $isReports ? 'bg-blue-50/80 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 font-semibold' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                         class="w-4.5 h-4.5 transition-colors {{ $isReports ? 'text-blue-500 dark:text-blue-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v5.25c0 .621-.504 1.125-1.125 1.125h-2.25A1.125 1.125 0 0 1 3 18.375v-5.25ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125v-9.75ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v14.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                    <span class="text-xs">Laporan & Analitik</span>
                </a>
            </div>

            <!-- Category: Operasional -->
            <div class="space-y-3">
                <span class="text-[10px] font-bold text-slate-400/80 uppercase tracking-widest px-2.5 block mb-2">Operasional</span>

                <!-- Group: Operations -->
                <div class="relative">
                    <button @click="activeGroup = activeGroup === 'operations' ? '' : 'operations'"
                            :class="(activeGroup === 'operations' || {{ $isOperationalActive ? 'true' : 'false' }}) ? 'text-blue-600 dark:text-blue-400 bg-slate-50/50 dark:bg-slate-800/20' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40'"
                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold transition-all duration-150 group cursor-pointer">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                 :class="(activeGroup === 'operations' || {{ $isOperationalActive ? 'true' : 'false' }}) ? 'text-blue-500 dark:text-blue-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-slate-600 dark:group-hover:text-slate-300'"
                                 class="w-4.5 h-4.5 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                            </svg>
                            <span class="text-xs">Operations</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                             :class="activeGroup === 'operations' ? 'rotate-180 text-slate-600 dark:text-slate-300' : 'text-slate-400 dark:text-slate-500 group-hover:text-slate-600 dark:group-hover:text-slate-300'"
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
                        <a href="{{ route('projects.index') }}" class="block py-1.5 px-2 text-[11px] font-semibold rounded-lg transition-colors {{ $isProjects ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            Projects
                        </a>
                        <a href="{{ route('clients.index') }}" class="block py-1.5 px-2 text-[11px] font-semibold rounded-lg transition-colors {{ $isClients ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            Clients
                        </a>
                        <a href="{{ route('schedules.index') }}" class="block py-1.5 px-2 text-[11px] font-semibold rounded-lg transition-colors {{ $isSchedules ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            Shift Scheduling
                        </a>
                    </div>
                </div>

                <!-- Group: Human Resources -->
                <div class="relative">
                    <button @click="activeGroup = activeGroup === 'hr' ? '' : 'hr'"
                            :class="(activeGroup === 'hr' || {{ $isHRActive ? 'true' : 'false' }}) ? 'text-blue-600 dark:text-blue-400 bg-slate-50/50 dark:bg-slate-800/20' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40'"
                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold transition-all duration-150 group cursor-pointer">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                 :class="(activeGroup === 'hr' || {{ $isHRActive ? 'true' : 'false' }}) ? 'text-blue-500 dark:text-blue-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-slate-600 dark:group-hover:text-slate-300'"
                                 class="w-4.5 h-4.5 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766v-.109A12.318 12.318 0 0 1 9.374 18c2.331 0 4.512.645 6.374 1.766Zm-3-12.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM18 10.5a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                            <span class="text-xs">Human Resources</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                             :class="activeGroup === 'hr' ? 'rotate-180 text-slate-600 dark:text-slate-300' : 'text-slate-400 dark:text-slate-500 group-hover:text-slate-600 dark:group-hover:text-slate-300'"
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
                        <a href="{{ route('employees.index') }}" class="block py-1.5 px-2 text-[11px] font-semibold rounded-lg transition-colors {{ $isEmployees ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            Employees
                        </a>
                        <a href="{{ route('attendances.index') }}" class="block py-1.5 px-2 text-[11px] font-semibold rounded-lg transition-colors {{ $isAttendance ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            Attendance
                        </a>
                        <a href="{{ route('recruitment.index') }}" class="block py-1.5 px-2 text-[11px] font-semibold rounded-lg transition-colors {{ $isRecruitment ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            Recruitment
                        </a>
                        <a href="{{ route('evaluations.index') }}" class="block py-1.5 px-2 text-[11px] font-semibold rounded-lg transition-colors {{ $isEvaluations ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            Performance Appraisal
                        </a>
                        <a href="{{ route('certifications.index') }}" class="block py-1.5 px-2 text-[11px] font-semibold rounded-lg transition-colors {{ $isCertifications ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            Training & Certs
                        </a>
                    </div>
                </div>
            </div>

            <!-- Category: Keuangan -->
            <div class="space-y-1.5">
                <span class="text-[10px] font-bold text-slate-400/80 uppercase tracking-widest px-2.5 block mb-2">Keuangan</span>

                <div class="relative">
                    <button @click="activeGroup = activeGroup === 'finance' ? '' : 'finance'"
                            :class="(activeGroup === 'finance' || {{ $isFinanceActive ? 'true' : 'false' }}) ? 'text-blue-600 dark:text-blue-400 bg-slate-50/50 dark:bg-slate-800/20' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40'"
                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold transition-all duration-150 group cursor-pointer">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                 :class="(activeGroup === 'finance' || {{ $isFinanceActive ? 'true' : 'false' }}) ? 'text-blue-500 dark:text-blue-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-slate-600 dark:group-hover:text-slate-300'"
                                 class="w-4.5 h-4.5 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-19.5 5.25h19.5m-19.5 0h19.5M2.25 12h19.5m-19.5 0h19.5m-19.5 5.25h19.5m-19.5 0h19.5M3 19.5h18a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 21 4.5H3a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 3 19.5Z" />
                            </svg>
                            <span class="text-xs">Finance</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                             :class="activeGroup === 'finance' ? 'rotate-180 text-slate-600 dark:text-slate-300' : 'text-slate-400 dark:text-slate-500 group-hover:text-slate-600 dark:group-hover:text-slate-300'"
                             class="w-3 h-3 transition-transform duration-150">
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
                        <a href="{{ route('invoices.index') }}" class="block py-1.5 px-2 text-[11px] font-semibold rounded-lg transition-colors {{ $isInvoices ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            Invoices
                        </a>
                        <a href="{{ route('payrolls.index') }}" class="block py-1.5 px-2 text-[11px] font-semibold rounded-lg transition-colors {{ $isPayroll ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            Payroll
                        </a>
                        <a href="{{ route('expenses.index') }}" class="block py-1.5 px-2 text-[11px] font-semibold rounded-lg transition-colors {{ $isExpenses ? 'text-blue-600 dark:text-blue-400 bg-blue-50/30 dark:bg-blue-950/10' : 'text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                            Expenses
                        </a>
                    </div>
                </div>
            </div>

            <!-- Category: Pengaturan -->
            <div class="space-y-1.5">
                <span class="text-[10px] font-bold text-slate-400/80 uppercase tracking-widest px-2.5 block mb-2">Pengaturan</span>

                <div class="relative">
                    <button @click="activeGroup = activeGroup === 'settings' ? '' : 'settings'"
                            :class="(activeGroup === 'settings' || {{ $isSettingsActive ? 'true' : 'false' }}) ? 'text-blue-600 dark:text-blue-400 bg-slate-50/50 dark:bg-slate-800/20' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40'"
                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl font-semibold transition-all duration-150 group cursor-pointer">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                 :class="(activeGroup === 'settings' || {{ $isSettingsActive ? 'true' : 'false' }}) ? 'text-blue-500 dark:text-blue-400' : 'text-slate-400 dark:text-slate-500 group-hover:text-slate-600 dark:group-hover:text-slate-300'"
                                 class="w-4.5 h-4.5 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.936 6.936 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <span class="text-xs">General Settings</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                             :class="activeGroup === 'settings' ? 'rotate-180 text-slate-600 dark:text-slate-300' : 'text-slate-400 dark:text-slate-500 group-hover:text-slate-600 dark:group-hover:text-slate-300'"
                             class="w-3 h-3 transition-transform duration-150">
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
        </nav>
    </div>

</aside>
