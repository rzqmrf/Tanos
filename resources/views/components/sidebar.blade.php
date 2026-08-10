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
        <!-- Logo TANOS -->
        <div class="flex items-center px-2 py-3 mb-3 min-h-[48px]">
            <img src="{{ asset('images/logo.png') }}"
                 alt="Tanos Logo"
                 class="h-8 object-contain max-w-[150px] dark:brightness-0 dark:invert"
                 onerror="this.style.display='none'; document.getElementById('sidebar-logo-fallback').classList.remove('hidden');">

            <!-- Fallback Logo matching BUMN Tanos Hexagon style from screenshot -->
            <div id="sidebar-logo-fallback" class="hidden flex items-center space-x-2.5">
                <div class="w-8 h-8 flex items-center justify-center text-white bg-[#100b60] rounded-lg shadow-sm select-none" style="clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%);">
                    <span class="font-extrabold text-sm leading-none">T</span>
                </div>
                <span class="text-lg font-black text-[#100b60] dark:text-slate-100 tracking-tight">Tanos</span>
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

            // Extract initials
            $words = explode(' ', $name);
            $initials = '';
            foreach ($words as $w) {
                if (!empty($w)) {
                    $initials .= strtoupper(substr($w, 0, 1));
                }
            }
            $initials = substr($initials, 0, 2);
        @endphp

        <!-- User Profile Card -->
        <div class="px-3 py-3 mb-5 bg-slate-50/60 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800/60 rounded-2xl flex items-center space-x-3">
            <div class="relative flex-shrink-0">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-600 to-[#100b60] text-white flex items-center justify-center font-bold text-xs select-none shadow-sm">
                    {{ $initials }}
                </div>
                <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full ring-2 ring-slate-50 dark:ring-slate-900 bg-emerald-500" title="Online"></span>
            </div>

            <div class="min-w-0 flex-1">
                <span class="block text-xs font-bold text-slate-800 dark:text-slate-200 truncate leading-none">{{ $name }}</span>
                <span class="block text-xs font-semibold text-slate-400 dark:text-slate-550 truncate mt-1">{{ $role }}</span>
            </div>
        </div>

        @php
            $isDashboard = request()->routeIs('dashboard.index');
            $isCopilot = request()->routeIs('copilot.index');
            $isReports = request()->routeIs('reports.index');

            // --- GENERAL ACTIVE STATE ---
            $isProjectConfig = request()->routeIs('project.config');
            $isAccessControls = request()->routeIs('access.controls');
            $isUsers = request()->routeIs('users.index') || request()->routeIs('users.show') || request()->routeIs('users.create') || request()->routeIs('users.edit');
            $isSettingsActive = $isProjectConfig || $isAccessControls || $isUsers;
            $isGeneralActive = $isSettingsActive;

            // --- MATERIAL ACTIVE STATE ---
            $isMaterialActive = false;

            // --- HUMAN CAPITAL ACTIVE STATE ---
            $isEmployees = request()->routeIs('employees.index') || request()->routeIs('employees.show') || request()->routeIs('employees.create') || request()->routeIs('employees.edit');
            $isAbsentTypes = request()->routeIs('org.absent-types.*');
            $isSchedules = request()->routeIs('schedules.index') || request()->routeIs('org.schedules.*');
            $isTimeEvaluations = request()->routeIs('org.evaluations.*');
            $isTimePeriods = request()->routeIs('org.periods.*');
            $isAttendance = request()->routeIs('attendances.index') || $isTimePeriods || $isAbsentTypes || $isTimeEvaluations;
            $isEssAdmin = request()->routeIs('ess.admin.index');
            $isESS = request()->routeIs('ess.index');
            $isPayroll = request()->routeIs('payrolls.index') || request()->routeIs('payrolls.show');
            $isSto = request()->routeIs('org.sto.*');
            $isJob = request()->routeIs('org.job.*');
            $isEcn = request()->routeIs('org.ecn.*');
            $isOrg = $isSto || $isJob || $isEcn;
            $isHCActive = $isEmployees || $isSchedules || $isAttendance || $isEssAdmin || $isPayroll || $isOrg;

            // --- PROJECT SYSTEM ACTIVE STATE ---
            $isProjects = request()->routeIs('projects.index') || request()->routeIs('projects.show') || request()->routeIs('projects.create') || request()->routeIs('projects.edit');
            $isWbs = request()->routeIs('projects.wbs.index');
            $isRab = request()->routeIs('rab.*');
            $isBilling = request()->routeIs('billing.index');
            $isPostingPayroll = request()->routeIs('posting_payrolls.index');
            $isPSActive = $isProjects || $isWbs || $isRab || $isBilling || $isPostingPayroll;

            // Determine active group for single accordion
            $activeGroup = '';
            if ($isGeneralActive) { $activeGroup = 'general'; }
            elseif ($isMaterialActive) { $activeGroup = 'material'; }
            elseif ($isHCActive) { $activeGroup = 'hc'; }
            elseif ($isPSActive) { $activeGroup = 'ps'; }

            // Determine active sub-group for General
            $generalActiveSub = '';
            if ($isSettingsActive) { $generalActiveSub = 'settings'; }

            // Determine active sub-group for HC
            $hcActiveSub = '';
            if ($isEmployees) { $hcActiveSub = 'personal_data'; }
            elseif ($isOrg) { $hcActiveSub = 'org_structure'; }
            elseif ($isSchedules) { $hcActiveSub = 'schedule_group'; }
            elseif ($isAbsentTypes || $isTimeEvaluations || $isTimePeriods) { $hcActiveSub = 'time_management'; }
            elseif (request()->routeIs('attendances.index') || $isEssAdmin) { $hcActiveSub = 'attendance_rekap'; }
            elseif ($isPayroll) { $hcActiveSub = 'proses_payroll'; }

            // Determine active sub-group for PS
            $psActiveSub = '';
            if ($isProjects || $isWbs || $isRab) { $psActiveSub = 'project'; }
            elseif ($isPostingPayroll) { $psActiveSub = 'budgeting'; }
            elseif ($isBilling) { $psActiveSub = 'billing'; }
            elseif ($isReports) { $psActiveSub = 'reports'; }
        @endphp

        <!-- Single Accordion Menu wrapper using Alpine x-data -->
        <nav x-data="{ activeMenu: '{{ $activeGroup }}' }" class="space-y-3">
            @if($role === 'Employee')
                <!-- ==================== EMPLOYEE VIEW ==================== -->
                <div class="space-y-1.5">
                    @if(\App\Models\RolePermission::hasPermission($role, 'dashboard'))
                        <a href="{{ route('dashboard.index') }}" 
                           style="{{ $isDashboard ? 'background-color: #100b60 !important; color: white !important;' : '' }}"
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 group {{ $isDashboard ? 'font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 font-semibold' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                 class="w-4.5 h-4.5 transition-colors {{ $isDashboard ? 'text-white' : 'text-slate-400 dark:text-slate-550 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            <span class="text-sm">Dashboard</span>
                        </a>
                    @endif

                    @if(\App\Models\RolePermission::hasPermission($role, 'attendance'))
                        <a href="{{ route('attendances.index') }}" 
                           style="{{ $isAttendance ? 'background-color: #100b60 !important; color: white !important;' : '' }}"
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 group {{ $isAttendance ? 'font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 font-semibold' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                 class="w-4.5 h-4.5 transition-colors {{ $isAttendance ? 'text-white' : 'text-slate-400 dark:text-slate-550 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            <span class="text-sm">Absensi Saya</span>
                        </a>
                    @endif

                    @if(\App\Models\RolePermission::hasPermission($role, 'pengajuan_cuti'))
                        <a href="{{ route('ess.index') }}" 
                           style="{{ $isESS ? 'background-color: #100b60 !important; color: white !important;' : '' }}"
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 group {{ $isESS ? 'font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-50/80 dark:hover:bg-slate-800/40 font-semibold' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5 {{ $isESS ? 'text-white' : 'text-slate-400 dark:text-slate-550 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}">
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
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5 text-slate-400 dark:text-slate-550 group-hover:text-slate-600 dark:group-hover:text-slate-300">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25" />
                            </svg>
                            <span class="text-sm">Laporan</span>
                        </a>
                    @endif
                </div>
            @else
                <!-- ==================== ADMIN / HR VIEW ==================== -->
                <!-- Home & AI Copilot (Closes other accordions) -->
                <div class="space-y-1.5 mb-4">
                    @if(\App\Models\RolePermission::hasPermission($role, 'dashboard'))
                        <a href="{{ route('dashboard.index') }}" 
                           @click="activeMenu = ''"
                           style="{{ $isDashboard ? 'background-color: #100b60 !important; color: white !important;' : '' }}"
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 group {{ $isDashboard ? 'font-bold shadow-md shadow-blue-900/10' : 'text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-100/70 dark:hover:bg-slate-800/40 font-semibold' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                 class="w-5 h-5 transition-colors {{ $isDashboard ? 'text-white' : 'text-slate-400 dark:text-slate-550 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            <span class="text-sm">Home</span>
                        </a>
                        <a href="{{ route('copilot.index') }}" 
                           @click="activeMenu = ''"
                           style="{{ $isCopilot ? 'background-color: #100b60 !important; color: white !important;' : '' }}"
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-150 group {{ $isCopilot ? 'font-bold shadow-md shadow-blue-900/10' : 'text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-100/70 dark:hover:bg-slate-800/40 font-semibold' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                 class="w-5 h-5 transition-colors {{ $isCopilot ? 'text-white' : 'text-slate-400 dark:text-slate-550 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 21l-.813-5.096L3 15l5.096-.813L9 9l.813 5.096L15 15l-5.187.904zM18.097 5.196L17.5 8l-.597-2.804L14 5l2.903-.597L17.5 2l.597 2.403L21 5l-2.903.196zM11.666 4.086l-.416 1.914-.416-1.914L9 3.5l1.834-.416.416-1.914.416 1.914L13.5 3.5l-1.834.586z" />
                            </svg>
                            <span class="text-sm">Tanos AI Copilot</span>
                        </a>
                    @endif
                </div>

                <!-- Group: General -->
                @if(\App\Models\RolePermission::hasPermission($role, 'settings'))
                <div class="relative mb-2">
                    <button @click="activeMenu = (activeMenu === 'general' ? '' : 'general')"
                            :style="activeMenu === 'general' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                            :class="activeMenu === 'general' ? 'font-bold shadow-md shadow-blue-900/10' : 'text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-100/70 dark:hover:bg-slate-800/40 font-semibold'"
                            class="w-full text-left flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-150 group cursor-pointer">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                 class="w-5 h-5 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.936 6.936 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <span class="text-sm">General</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                             :class="activeMenu === 'general' ? 'rotate-180 text-white' : ''"
                             class="w-3 h-3 transition-transform duration-150">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    
                    <div x-show="activeMenu === 'general'" 
                         x-data="{ activeSubMenu: '{{ $generalActiveSub }}' }"
                         x-transition class="mt-1 pl-4 space-y-1.5 border-l border-slate-100 dark:border-slate-800 ml-4.5" style="display: none;">
                        <!-- Nested: Settings -->
                        <div class="space-y-1">
                            <button @click="activeSubMenu = (activeSubMenu === 'settings' ? '' : 'settings')" 
                                    :style="activeSubMenu === 'settings' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                                    class="w-full text-left flex items-center justify-between py-1.5 px-3 text-[13px] font-semibold rounded-lg transition-colors cursor-pointer text-[#100b60] hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-100 hover:bg-slate-100/70 dark:hover:bg-slate-800/40">
                                <span class="flex items-start text-left"><span class="mr-1.5 text-xs text-current font-extrabold mt-0.5">•</span>Settings</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="activeSubMenu === 'settings' ? 'rotate-180 text-white' : 'text-slate-400'" class="w-2.5 h-2.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeSubMenu === 'settings'" x-transition class="pl-4 space-y-1" style="display: none;">
                                <a href="{{ route('users.index') }}" 
                                   class="block py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isUsers ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200' }}">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>User
                                </a>
                                <a href="{{ route('access.controls') }}" 
                                   class="block py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isAccessControls ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200' }}">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>Access Controls
                                </a>
                                <a href="{{ route('project.config') }}" 
                                   class="block py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isProjectConfig ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200' }}">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>PEO Setting
                                </a>
                            </div>
                        </div>

                        <!-- Nested: Master Data -->
                        <div class="space-y-1">
                            <button @click="activeSubMenu = (activeSubMenu === 'master_data' ? '' : 'master_data')" 
                                    :style="activeSubMenu === 'master_data' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                                    class="w-full text-left flex items-center justify-between py-1.5 px-3 text-[13px] font-semibold rounded-lg transition-colors cursor-pointer text-[#100b60] hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-100 hover:bg-slate-100/70 dark:hover:bg-slate-800/40">
                                <span class="flex items-start text-left"><span class="mr-1.5 text-xs text-current font-extrabold mt-0.5">•</span>Master Data</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="activeSubMenu === 'master_data' ? 'rotate-180 text-white' : 'text-slate-400'" class="w-2.5 h-2.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeSubMenu === 'master_data'" x-transition class="pl-4 space-y-1" style="display: none;">
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>Partner Type
                                </a>
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>Partner
                                </a>
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>Bank ACS Customer
                                </a>
                            </div>
                        </div>

                        <!-- Nested: Master FA -->
                        <div class="space-y-1">
                            <button @click="activeSubMenu = (activeSubMenu === 'master_fa' ? '' : 'master_fa')" 
                                    :style="activeSubMenu === 'master_fa' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                                    class="w-full text-left flex items-center justify-between py-1.5 px-3 text-[13px] font-semibold rounded-lg transition-colors cursor-pointer text-[#100b60] hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-100 hover:bg-slate-100/70 dark:hover:bg-slate-800/40">
                                <span class="flex items-start text-left"><span class="mr-1.5 text-xs text-current font-extrabold mt-0.5">•</span>Master FA</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="activeSubMenu === 'master_fa' ? 'rotate-180 text-white' : 'text-slate-400'" class="w-2.5 h-2.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeSubMenu === 'master_fa'" x-transition class="pl-4 space-y-1" style="display: none;">
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>Tax
                                </a>
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>Profit Center
                                </a>
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>Cost Center
                                </a>
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>Fund Center
                                </a>
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>Currency
                                </a>
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>Currency Rate
                                </a>
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>Bank Account
                                </a>
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>Period
                                </a>
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>Account Group
                                </a>
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>CoA
                                </a>
                            </div>
                        </div>

                        <!-- Nested: Setting FA -->
                        <div class="space-y-1">
                            <button @click="activeSubMenu = (activeSubMenu === 'setting_fa' ? '' : 'setting_fa')" 
                                    :style="activeSubMenu === 'setting_fa' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                                    class="w-full text-left flex items-center justify-between py-1.5 px-3 text-[13px] font-semibold rounded-lg transition-colors cursor-pointer text-[#100b60] hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-100 hover:bg-slate-100/70 dark:hover:bg-slate-800/40">
                                <span class="flex items-start text-left"><span class="mr-1.5 text-xs text-current font-extrabold mt-0.5">•</span>Setting FA</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="activeSubMenu === 'setting_fa' ? 'rotate-180 text-white' : 'text-slate-400'" class="w-2.5 h-2.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeSubMenu === 'setting_fa'" x-transition class="pl-4 space-y-1" style="display: none;">
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>FI Settings
                                </a>
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>Budget Management
                                </a>
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>Set Budget tolerance Profile
                                </a>
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>Cash Flow Setting
                                </a>
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>Coa Mapping Persediaan
                                </a>
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>Open Item Check
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Group: Material -->
                @if(\App\Models\RolePermission::hasPermission($role, 'projects') || \App\Models\RolePermission::hasPermission($role, 'settings'))
                <div class="relative mb-2">
                    <button @click="activeMenu = (activeMenu === 'material' ? '' : 'material')"
                            :style="activeMenu === 'material' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                            :class="activeMenu === 'material' ? 'font-bold shadow-md shadow-blue-900/10' : 'text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-100/70 dark:hover:bg-slate-800/40 font-semibold'"
                            class="w-full text-left flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-150 group cursor-pointer">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                 class="w-5 h-5 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.75A1.125 1.125 0 0 1 10.125 16.125h3.75A1.125 1.125 0 0 1 15 17.25V21" />
                            </svg>
                            <span class="text-sm">Material</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                             :class="activeMenu === 'material' ? 'rotate-180 text-white' : ''"
                             class="w-3 h-3 transition-transform duration-150">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    
                    <div x-show="activeMenu === 'material'" 
                         x-data="{ activeSubMenu: '' }"
                         x-transition class="mt-1 pl-4 space-y-1.5 border-l border-slate-100 dark:border-slate-800 ml-4.5" style="display: none;">
                        <!-- Nested: Equipment Master -->
                        <div class="space-y-1">
                            <button @click="activeSubMenu = (activeSubMenu === 'equipment_master' ? '' : 'equipment_master')" 
                                    :style="activeSubMenu === 'equipment_master' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                                    class="w-full text-left flex items-center justify-between py-1.5 px-3 text-[13px] font-semibold rounded-lg transition-colors cursor-pointer text-[#100b60] hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-100 hover:bg-slate-100/70 dark:hover:bg-slate-800/40">
                                <span class="flex items-start text-left"><span class="mr-1.5 text-xs text-current font-extrabold mt-0.5">•</span>Equipment Master</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="activeSubMenu === 'equipment_master' ? 'rotate-180 text-white' : 'text-slate-400'" class="w-2.5 h-2.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeSubMenu === 'equipment_master'" x-transition class="pl-4 space-y-1" style="display: none;">
                                <a href="#" class="block py-1.5 px-2 text-xs font-semibold rounded-lg text-[#100b60]/85 hover:text-[#100b60] dark:text-slate-400 dark:hover:text-slate-200">
                                    <span class="mr-1.5 text-xs font-extrabold text-current">•</span>Equipment
                                </a>
                            </div>
                        </div>

                        <!-- Nested: Outline Agreement -->
                        <a href="#" class="block py-1.5 px-3 text-[13px] font-semibold rounded-lg text-[#100b60] hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-100 hover:bg-slate-100/70 dark:hover:bg-slate-800/40">
                            <span class="flex items-start text-left"><span class="mr-1.5 text-xs text-current font-extrabold mt-0.5">•</span>Outline Agreement</span>
                        </a>
                    </div>
                </div>
                @endif

                <!-- Group: Human Capital -->
                @if(\App\Models\RolePermission::hasPermission($role, 'employees') || \App\Models\RolePermission::hasPermission($role, 'attendance') || \App\Models\RolePermission::hasPermission($role, 'schedules') || \App\Models\RolePermission::hasPermission($role, 'payroll') || \App\Models\RolePermission::hasPermission($role, 'reports'))
                <div class="relative mb-2">
                    <button @click="activeMenu = (activeMenu === 'hc' ? '' : 'hc')"
                            :style="activeMenu === 'hc' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                            :class="activeMenu === 'hc' ? 'font-bold shadow-md shadow-blue-900/10' : 'text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-100/70 dark:hover:bg-slate-800/40 font-semibold'"
                            class="w-full text-left flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-150 group cursor-pointer">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
                                 class="w-5 h-5 transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                            <span class="text-sm">Human Capital</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                             :class="activeMenu === 'hc' ? 'rotate-180 text-white' : ''"
                             class="w-3 h-3 transition-transform duration-150">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    
                    <div x-show="activeMenu === 'hc'" 
                         x-data="{ activeSubMenu: '{{ $hcActiveSub }}' }"
                         x-transition class="mt-1 pl-4 space-y-1.5 border-l border-slate-100 dark:border-slate-800 ml-4.5" style="display: none;">
                        
                        <!-- Nested: Master Data -->
                        @if(\App\Models\RolePermission::hasPermission($role, 'employees'))
                        <div class="space-y-1">
                            <button @click="activeSubMenu = (activeSubMenu === 'personal_data' ? '' : 'personal_data')" 
                                    :style="activeSubMenu === 'personal_data' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                                    class="w-full text-left flex items-center justify-between py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors cursor-pointer text-[#100b60] hover:text-slate-800 dark:text-slate-350 dark:hover:text-slate-200">
                                <span class="flex items-center text-left"><span class="mr-1.5 text-xs text-blue-600 dark:text-blue-400 font-extrabold">•</span>Master Data</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="activeSubMenu === 'personal_data' ? 'rotate-180 text-white' : ''" class="w-2.5 h-2.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeSubMenu === 'personal_data'" x-transition class="pl-4 space-y-1" style="display: none;">
                                <a href="{{ route('employees.index') }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isEmployees ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}"><span class="mr-1.5 font-extrabold text-current">•</span>Employee Data</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Employee Insurance</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Employee Education</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Employee Skill Training</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Custom Date</a>
                            </div>
                        </div>
                        @endif

                        <!-- Nested: Organizational Structure -->
                        @if(\App\Models\RolePermission::hasPermission($role, 'employees'))
                        <div class="space-y-1">
                            <button @click="activeSubMenu = (activeSubMenu === 'org_structure' ? '' : 'org_structure')" 
                                    :style="activeSubMenu === 'org_structure' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                                    class="w-full text-left flex items-center justify-between py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors cursor-pointer text-[#100b60] hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-200">
                                <span class="flex items-center text-left"><span class="mr-1.5 text-xs text-blue-600 dark:text-blue-400 font-extrabold">•</span>Organizational Structure</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="activeSubMenu === 'org_structure' ? 'rotate-180 text-white' : ''" class="w-2.5 h-2.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeSubMenu === 'org_structure'" x-transition class="pl-4 space-y-1" style="display: none;">
                                <a href="{{ route('org.sto.index') }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isSto ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}"><span class="mr-1.5 font-extrabold text-current">•</span>STO Chart</a>
                                <a href="{{ route('org.job.index') }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isJob ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}"><span class="mr-1.5 font-extrabold text-current">•</span>Job Position</a>
                                <a href="{{ route('org.ecn.index') }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isEcn ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}"><span class="mr-1.5 font-extrabold text-current">•</span>Employee Change Notice</a>
                            </div>
                        </div>
                        @endif

                        <!-- Nested: Employee -->
                        @if(\App\Models\RolePermission::hasPermission($role, 'employees'))
                        <div class="space-y-1">
                            <button @click="activeSubMenu = (activeSubMenu === 'employee' ? '' : 'employee')" 
                                    :style="activeSubMenu === 'employee' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                                    class="w-full text-left flex items-center justify-between py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors cursor-pointer text-[#100b60] hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200">
                                <span class="flex items-center text-left"><span class="mr-1.5 text-xs text-blue-600 dark:text-blue-400 font-extrabold">•</span>Employee</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="activeSubMenu === 'employee' ? 'rotate-180 text-white' : ''" class="w-2.5 h-2.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeSubMenu === 'employee'" x-transition class="pl-4 space-y-1" style="display: none;">
                                <a href="{{ route('schedules.index') }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isSchedules ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}"><span class="mr-1.5 font-extrabold text-current">•</span>Schedule Assignment</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Schedule Config</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Employee Actions</a>
                            </div>
                        </div>
                        @endif

                        <!-- Nested: Career -->
                        @if(\App\Models\RolePermission::hasPermission($role, 'employees'))
                        <div class="space-y-1">
                            <button @click="activeSubMenu = (activeSubMenu === 'career' ? '' : 'career')" 
                                    :style="activeSubMenu === 'career' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                                    class="w-full text-left flex items-center justify-between py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors cursor-pointer text-[#100b60] hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200">
                                <span class="flex items-center text-left"><span class="mr-1.5 text-xs text-blue-600 dark:text-blue-400 font-extrabold">•</span>Career</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="activeSubMenu === 'career' ? 'rotate-180 text-white' : ''" class="w-2.5 h-2.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeSubMenu === 'career'" x-transition class="pl-4 space-y-1" style="display: none;">
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Career Development</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Succession Planning</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Training & Certification</a>
                            </div>
                        </div>
                        @endif

                        <!-- Nested: Time Management -->
                        @if(\App\Models\RolePermission::hasPermission($role, 'attendance'))
                        <div class="space-y-1">
                            <button @click="activeSubMenu = (activeSubMenu === 'time_management' ? '' : 'time_management')" 
                                    :style="activeSubMenu === 'time_management' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                                    class="w-full text-left flex items-center justify-between py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors cursor-pointer text-[#100b60] hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200">
                                <span class="flex items-center text-left"><span class="mr-1.5 text-xs text-primary dark:text-blue-400 font-extrabold">•</span>Time Management</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="activeSubMenu === 'time_management' ? 'rotate-180 text-white' : ''" class="w-2.5 h-2.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeSubMenu === 'time_management'" x-transition class="pl-4 space-y-1" style="display: none;">
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Time Valuation Master</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Attendance Type</a>
                                <a href="{{ route('org.absent-types.index') }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isAbsentTypes ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}"><span class="mr-1.5 font-extrabold text-current">•</span>Absent Type</a>
                                <a href="{{ route('org.evaluations.index') }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isTimeEvaluations ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}"><span class="mr-1.5 font-extrabold text-current">•</span>Time Evaluation</a>
                                <a href="{{ route('org.periods.index') }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isTimePeriods ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}"><span class="mr-1.5 font-extrabold text-current">•</span>Time Period</a>
                            </div>
                        </div>
                        @endif

                        <!-- Nested: Employee Self Service -->
                        @if(\App\Models\RolePermission::hasPermission($role, 'attendance'))
                        <div class="space-y-1">
                            <button @click="activeSubMenu = (activeSubMenu === 'attendance_rekap' ? '' : 'attendance_rekap')" 
                                    :style="activeSubMenu === 'attendance_rekap' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                                    class="w-full text-left flex items-center justify-between py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors cursor-pointer text-[#100b60] hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200">
                                <span class="flex items-center text-left"><span class="mr-1.5 text-xs text-primary dark:text-blue-400 font-extrabold">•</span>Employee Self Service</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="activeSubMenu === 'attendance_rekap' ? 'rotate-180 text-white' : ''" class="w-2.5 h-2.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeSubMenu === 'attendance_rekap'" x-transition class="pl-4 space-y-1" style="display: none;">
                                <a href="{{ route('attendances.index') }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg {{ request()->routeIs('attendances.index') ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}"><span class="mr-1.5 font-extrabold text-current">•</span>Upload Attendance</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Get My Intan (MyPelindo)</a>
                                <a href="{{ route('ess.admin.index') }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isEssAdmin ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}"><span class="mr-1.5 font-extrabold text-current">•</span>Cico Correction</a>
                                <a href="{{ route('attendances.index') }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Employee Absent</a>
                            </div>
                        </div>
                        @endif

                        <!-- Nested: Payroll -->
                        @if(\App\Models\RolePermission::hasPermission($role, 'payroll'))
                        <div class="space-y-1">
                            <button @click="activeSubMenu = (activeSubMenu === 'proses_payroll' ? '' : 'proses_payroll')" 
                                    :style="activeSubMenu === 'proses_payroll' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                                    class="w-full text-left flex items-center justify-between py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors cursor-pointer text-[#100b60] hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200">
                                <span class="flex items-center text-left"><span class="mr-1.5 text-xs text-primary dark:text-blue-400 font-extrabold">•</span>Payroll</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="activeSubMenu === 'proses_payroll' ? 'rotate-180 text-white' : ''" class="w-2.5 h-2.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeSubMenu === 'proses_payroll'" x-transition class="pl-4 space-y-1" style="display: none;">
                                <a href="{{ route('payrolls.index') }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isPayroll ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}"><span class="mr-1.5 font-extrabold text-current">•</span>Period Payroll</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Assignment Employee Payroll</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Payroll Formulation</a>
                            </div>
                        </div>
                        @endif

                        <!-- Nested: Report -->
                        @if(\App\Models\RolePermission::hasPermission($role, 'reports') || \App\Models\RolePermission::hasPermission($role, 'employees'))
                        <div class="space-y-1">
                            <button @click="activeSubMenu = (activeSubMenu === 'hc_report' ? '' : 'hc_report')" 
                                    :style="activeSubMenu === 'hc_report' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                                    class="w-full text-left flex items-center justify-between py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors cursor-pointer text-[#100b60] hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200">
                                <span class="flex items-center text-left"><span class="mr-1.5 text-xs text-primary dark:text-blue-400 font-extrabold">•</span>Report</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="activeSubMenu === 'hc_report' ? 'rotate-180 text-white' : ''" class="w-2.5 h-2.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeSubMenu === 'hc_report'" x-transition class="pl-4 space-y-1" style="display: none;">
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Attendance Report</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Payroll Report</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Employee Report</a>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
                @endif

                <!-- Group: Project System -->
                @if(\App\Models\RolePermission::hasPermission($role, 'projects') || \App\Models\RolePermission::hasPermission($role, 'payroll') || \App\Models\RolePermission::hasPermission($role, 'invoices') || \App\Models\RolePermission::hasPermission($role, 'reports'))
                <div class="relative mb-2">
                    <button @click="activeMenu = (activeMenu === 'ps' ? '' : 'ps')"
                            :style="activeMenu === 'ps' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                            :class="activeMenu === 'ps' ? 'font-bold shadow-md shadow-blue-900/10' : 'text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-100 hover:bg-slate-100/70 dark:hover:bg-slate-800/40 font-semibold'"
                            class="w-full text-left flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-150 group cursor-pointer">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" 
                                 class="w-5 h-5 transition-colors">
                                <rect x="3" y="3" width="6" height="6" rx="1.5" />
                                <rect x="3" y="15" width="6" height="6" rx="1.5" />
                                <rect x="15" y="9" width="6" height="6" rx="1.5" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 6h3v12H9M12 12h3" />
                            </svg>
                            <span class="text-sm">Project System</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                             :class="activeMenu === 'ps' ? 'rotate-180 text-white' : ''"
                             class="w-3 h-3 transition-transform duration-150">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    
                    <div x-show="activeMenu === 'ps'" 
                         x-data="{ activeSubMenu: '{{ $psActiveSub }}' }"
                         x-transition class="mt-1 pl-4 space-y-1.5 border-l border-slate-100 dark:border-slate-800 ml-4.5" style="display: none;">
                        
                        <!-- Nested: Master -->
                        @if(\App\Models\RolePermission::hasPermission($role, 'projects'))
                        <div class="space-y-1">
                            <button @click="activeSubMenu = (activeSubMenu === 'master_data' ? '' : 'master_data')" 
                                    :style="activeSubMenu === 'master_data' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                                    class="w-full text-left flex items-center justify-between py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors cursor-pointer text-[#100b60] hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-200">
                                <span class="flex items-center text-left"><span class="mr-1.5 text-xs text-blue-600 dark:text-blue-400 font-extrabold">•</span>Master</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="activeSubMenu === 'master_data' ? 'rotate-180 text-white' : ''" class="w-2.5 h-2.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeSubMenu === 'master_data'" x-transition class="pl-4 space-y-1" style="display: none;">
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Project Category</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Contract Type</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Project Role</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Project Type</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Status</a>
                            </div>
                        </div>
                        @endif

                        <!-- Nested: Project -->
                        @if(\App\Models\RolePermission::hasPermission($role, 'projects'))
                        <div class="space-y-1">
                            <button @click="activeSubMenu = (activeSubMenu === 'project' ? '' : 'project')" 
                                    :style="activeSubMenu === 'project' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                                    class="w-full text-left flex items-center justify-between py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors cursor-pointer text-[#100b60] hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-200">
                                <span class="flex items-center text-left"><span class="mr-1.5 text-xs text-primary dark:text-blue-400 font-extrabold">•</span>Project</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="activeSubMenu === 'project' ? 'rotate-180 text-white' : ''" class="w-2.5 h-2.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeSubMenu === 'project'" x-transition class="pl-4 space-y-1" style="display: none;">
                                <a href="{{ route('projects.index') }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isProjects ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}"><span class="mr-1.5 font-extrabold text-current">•</span>Project Definition</a>
                                <a href="{{ route('rab.index') }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isRab ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}"><span class="mr-1.5 font-extrabold text-current">•</span>RAB Budget</a>
                                <a href="{{ route('projects.wbs.index', ['project' => 1]) }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isWbs ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}"><span class="mr-1.5 font-extrabold text-current">•</span>WBS</a>
                            </div>
                        </div>
                        @endif

                        <!-- Nested: Budgeting -->
                        @if(\App\Models\RolePermission::hasPermission($role, 'projects') || \App\Models\RolePermission::hasPermission($role, 'payroll'))
                        <div class="space-y-1">
                            <button @click="activeSubMenu = (activeSubMenu === 'budgeting' ? '' : 'budgeting')" 
                                    :style="activeSubMenu === 'budgeting' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                                    class="w-full text-left flex items-center justify-between py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors cursor-pointer text-[#100b60] hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-200">
                                <span class="flex items-center text-left"><span class="mr-1.5 text-xs text-primary dark:text-blue-400 font-extrabold">•</span>Budgeting</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="activeSubMenu === 'budgeting' ? 'rotate-180 text-white' : ''" class="w-2.5 h-2.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeSubMenu === 'budgeting'" x-transition class="pl-4 space-y-1" style="display: none;">
                                @if(\App\Models\RolePermission::hasPermission($role, 'projects'))
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Budget Expense SAP</a>
                                @endif
                                @if(\App\Models\RolePermission::hasPermission($role, 'payroll'))
                                <a href="{{ route('posting_payrolls.index') }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isPostingPayroll ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}"><span class="mr-1.5 font-extrabold text-current">•</span>Posting Payroll</a>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Nested: Nota -->
                        @if(\App\Models\RolePermission::hasPermission($role, 'invoices'))
                        <div class="space-y-1">
                            <button @click="activeSubMenu = (activeSubMenu === 'billing' ? '' : 'billing')" 
                                    :style="activeSubMenu === 'billing' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                                    class="w-full text-left flex items-center justify-between py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors cursor-pointer text-[#100b60] hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-200">
                                <span class="flex items-center text-left"><span class="mr-1.5 text-xs text-primary dark:text-blue-400 font-extrabold">•</span>Nota</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="activeSubMenu === 'billing' ? 'rotate-180 text-white' : ''" class="w-2.5 h-2.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeSubMenu === 'billing'" x-transition class="pl-4 space-y-1" style="display: none;">
                                <a href="{{ route('billing.index', ['tab' => 'pranota']) }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isBilling && request()->query('tab', 'pranota') === 'pranota' ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}"><span class="mr-1.5 font-extrabold text-current">•</span>Pranota</a>
                                <a href="{{ route('billing.index', ['tab' => 'nota']) }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isBilling && request()->query('tab', 'pranota') === 'nota' ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}"><span class="mr-1.5 font-extrabold text-current">•</span>Nota Billing</a>
                                <a href="{{ route('invoices.index') }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg {{ request()->routeIs('invoices.index') ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}"><span class="mr-1.5 font-extrabold text-current">•</span>Invoice</a>
                            </div>
                        </div>
                        @endif

                        <!-- Nested: Reports -->
                        @if(\App\Models\RolePermission::hasPermission($role, 'reports'))
                        <div class="space-y-1">
                            <button @click="activeSubMenu = (activeSubMenu === 'reports' ? '' : 'reports')" 
                                    :style="activeSubMenu === 'reports' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                                    class="w-full text-left flex items-center justify-between py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors cursor-pointer text-[#100b60] hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-200">
                                <span class="flex items-center text-left"><span class="mr-1.5 text-xs text-primary dark:text-blue-400 font-extrabold">•</span>Reports</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="activeSubMenu === 'reports' ? 'rotate-180 text-white' : ''" class="w-2.5 h-2.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeSubMenu === 'reports'" x-transition class="pl-4 space-y-1" style="display: none;">
                                <a href="{{ route('reports.index') }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg {{ $isReports ? 'text-[#100b60] dark:text-white font-extrabold bg-blue-50/40 dark:bg-slate-800' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200' }}"><span class="mr-1.5 font-extrabold text-current">•</span>WBS Report</a>
                                <a href="{{ route('reports.index') }}" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Profit Loss per Segment</a>
                            </div>
                        </div>
                        @endif

                        <!-- Nested: Monitoring API -->
                        @if(\App\Models\RolePermission::hasPermission($role, 'settings'))
                        <div class="space-y-1">
                            <button @click="activeSubMenu = (activeSubMenu === 'monitoring_api' ? '' : 'monitoring_api')" 
                                    :style="activeSubMenu === 'monitoring_api' ? 'background-color: #100b60 !important; color: white !important;' : ''"
                                    class="w-full text-left flex items-center justify-between py-1.5 px-2 text-[13px] font-semibold rounded-lg transition-colors cursor-pointer text-[#100b60] hover:text-slate-800 dark:text-slate-300 dark:hover:text-slate-200">
                                <span class="flex items-center text-left"><span class="mr-1.5 text-xs text-primary dark:text-blue-400 font-extrabold">•</span>Monitoring API</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" :class="activeSubMenu === 'monitoring_api' ? 'rotate-180 text-white' : ''" class="w-2.5 h-2.5 transition-transform duration-150">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                            </button>
                            <div x-show="activeSubMenu === 'monitoring_api'" x-transition class="pl-4 space-y-1" style="display: none;">
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>API Status</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>API Log</a>
                                <a href="#" class="flex items-center py-1.5 px-2 text-xs font-semibold rounded-lg text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200"><span class="mr-1.5 font-extrabold text-current">•</span>Integration Config</a>
                            </div>
                        </div>
                        @endif


                </div>

                @endif
            @endif
        </nav>
    </div>

</aside>
