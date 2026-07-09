@props([
    'months' => [],
    'regionals' => [],
    'segments' => []
])

<header class="sticky top-0 bg-[#f8fafc]/80 backdrop-blur-md border-b border-slate-100 z-30 px-6 py-4 flex items-center justify-between">
    
    <!-- Left Area: Hamburger for mobile, Search bar for desktop -->
    <div class="flex items-center space-x-4 flex-1">
        <!-- Mobile Sidebar Toggle -->
        <button @click="sidebarOpen = !sidebarOpen" class="text-slate-500 hover:text-slate-700 focus:outline-none md:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        <!-- Search Bar -->
        <div class="relative max-w-xs w-full hidden sm:block">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <!-- Search Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.602 10.602Z" />
                </svg>
            </div>
            <input type="text" 
                   placeholder="Search..." 
                   class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-2 text-xs font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#1b3bb6] focus:ring-1 focus:ring-[#1b3bb6] transition-all duration-200">
        </div>
    </div>

    <!-- Right Area: Filters + Notification + Profile -->
    <div class="flex items-center space-x-3 lg:space-x-4">
        
        <!-- Month Filter Dropdown -->
        <div class="relative flex items-center bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 shadow-sm focus-within:border-[#1b3bb6] focus-within:ring-1 focus-within:ring-[#1b3bb6] transition-all duration-200">
            <!-- Calendar Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-400 mr-2 pointer-events-none">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
            </svg>
            <select x-model="selectedMonth" @change="fetchData()" class="bg-transparent border-none p-0 pr-6 text-xs font-semibold focus:ring-0 focus:outline-none cursor-pointer appearance-none">
                @foreach($months as $month)
                    <option value="{{ $month }}">{{ $month }}</option>
                @endforeach
            </select>
            <div class="absolute right-3 pointer-events-none text-slate-400">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </div>
        </div>

        <!-- Regional Filter Dropdown -->
        <div class="relative flex items-center bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 shadow-sm focus-within:border-[#1b3bb6] focus-within:ring-1 focus-within:ring-[#1b3bb6] transition-all duration-200">
            <select x-model="selectedRegional" @change="fetchData()" class="bg-transparent border-none p-0 pr-6 text-xs font-semibold focus:ring-0 focus:outline-none cursor-pointer appearance-none">
                <option value="All">All Regional</option>
                @foreach($regionals as $reg)
                    <option value="{{ $reg }}">{{ $reg }}</option>
                @endforeach
            </select>
            <div class="absolute right-3 pointer-events-none text-slate-400">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </div>
        </div>

        <!-- Segment Filter Dropdown -->
        <div class="relative flex items-center bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-medium text-slate-700 shadow-sm focus-within:border-[#1b3bb6] focus-within:ring-1 focus-within:ring-[#1b3bb6] transition-all duration-200">
            <select x-model="selectedSegment" @change="fetchData()" class="bg-transparent border-none p-0 pr-6 text-xs font-semibold focus:ring-0 focus:outline-none cursor-pointer appearance-none">
                <option value="All">All Segment</option>
                @foreach($segments as $seg)
                    <option value="{{ $seg }}">{{ $seg }}</option>
                @endforeach
            </select>
            <div class="absolute right-3 pointer-events-none text-slate-400">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </div>
        </div>

        <!-- Notification Button -->
        <button class="relative bg-white border border-slate-200 p-2 rounded-xl text-slate-500 hover:text-slate-700 hover:border-slate-300 shadow-sm transition-all duration-200">
            <!-- Bell Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>
            <!-- Red Badge Dot -->
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
        </button>

        <!-- User Profile Button -->
        <button class="flex items-center space-x-2 border border-slate-200 p-0.5 rounded-full hover:border-slate-300 transition-all duration-200">
            <!-- User Icon (or Profile Picture) -->
            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-semibold text-sm">
                <!-- User Circle Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7 text-slate-400">
                    <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12c0 2.754 1.14 5.244 2.977 7.03.024.024.047.05.072.073C6.392 17.72 8.831 17 12 17c3.169 0 5.608.72 7.001 2.097a9.725 9.725 0 0 0 .684-.684.072.072 0 0 0-.001-.016ZM12 4.5a7.5 7.5 0 1 0 0 15 7.5 7.5 0 0 0 0-15Zm0 3.75a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z" clip-rule="evenodd" />
                </svg>
            </div>
        </button>

    </div>
</header>
