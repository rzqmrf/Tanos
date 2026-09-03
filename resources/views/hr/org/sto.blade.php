@extends('layouts.app')

@section('title', 'Organizational Structure — Tanos ERP')

@section('content')
<div class="space-y-6" x-data="{ showCreateModal: false, searchKeyword: '' }">
    {{-- ALERT MESSAGES --}}
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm font-semibold flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 font-bold">&times;</button>
    </div>
    @endif

    {{-- PAGE HEADER --}}
    <x-page-header 
        title="Organizational Structure" 
        subtitle="Definisikan hierarki unit kerja, formasi jabatan, dan pegawai terintegrasi SAP & MDM."
        :breadcrumbs="[
            'General' => '#',
            'Human Resource' => '#',
            'Organizational Structure' => ''
        ]"
    >
        <x-slot:action>
            @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
            <a href="{{ route('org.sto.create') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center space-x-1.5 cursor-pointer border-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Create New Unit</span>
            </a>
            @endif
        </x-slot:action>
    </x-page-header>

    {{-- MAIN CONTENT CARD --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden p-6 space-y-6">
        {{-- Card Title --}}
        <div class="border-b border-slate-100 dark:border-slate-800 pb-3 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Organizational Structure - List</h2>
        </div>

        {{-- FILTER CONTROLS BAR --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center bg-slate-50/70 dark:bg-slate-800/40 p-4 rounded-xl border border-slate-100 dark:border-slate-800">
            {{-- Regional Filter --}}
            <div class="md:col-span-4 flex items-center gap-2">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 shrink-0">Regional</label>
                <select class="w-full px-3 py-2 text-xs font-semibold border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl focus:outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer">
                    <option selected>Regional PDS Jawa - Regional PDS Jawa</option>
                    <option>Regional PDS Jakarta</option>
                    <option>Regional PDS Sumatra</option>
                    <option>Regional PDS Kalimantan</option>
                </select>
            </div>

            {{-- Valid Date Picker --}}
            <div class="md:col-span-4 flex items-center gap-2">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400 shrink-0">Valid Date*</label>
                <div class="relative w-full flex items-center">
                    <input type="text" value="2024-08-19" class="w-full pl-9 pr-3 py-2 text-xs font-semibold border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl focus:outline-none focus:ring-1 focus:ring-blue-500">
                    <svg class="w-4 h-4 absolute left-3 text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                </div>
            </div>

            {{-- Action Buttons (Search & Send to MDM) --}}
            <div class="md:col-span-4 flex items-center gap-2.5 justify-end">
                {{-- Search Button --}}
                <button class="px-5 py-2 bg-sky-500 hover:bg-sky-600 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5 cursor-pointer border-0">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                    <span>Search</span>
                </button>

                {{-- Send to MDM Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                        class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 text-blue-600 dark:text-blue-400 font-bold text-xs rounded-xl transition flex items-center gap-1.5 cursor-pointer shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" /></svg>
                        <span>Send to MDM</span>
                        <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" style="display: none;" 
                        class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 py-1.5 z-20 text-xs font-semibold">
                        <a href="javascript:void(0)" onclick="alert('Data Struktur Organisasi berhasil disinkronkan ke Master Data Management (MDM)')" class="block px-4 py-2 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700">Sync All Units to MDM</a>
                        <a href="javascript:void(0)" onclick="alert('Data Job Formation berhasil disinkronkan ke MDM')" class="block px-4 py-2 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700">Sync Job Formations</a>
                        <a href="javascript:void(0)" onclick="alert('Data Employee Movement berhasil disinkronkan ke MDM')" class="block px-4 py-2 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700">Sync Employees</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- GRID LAYOUT: TREE VIEW (LEFT 8 COLS) & LEGEND SIDEBAR (RIGHT 4 COLS) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            {{-- HIERARCHICAL TREE VIEW CONTAINER --}}
            <div class="lg:col-span-8 border border-slate-200 dark:border-slate-800 rounded-xl p-5 bg-white dark:bg-slate-900 shadow-sm space-y-2 font-mono text-xs text-slate-800 dark:text-slate-200">
                
                {{-- Root 1: 6481 - Regional PDS Jawa --}}
                <div x-data="{ open: true }">
                    <div class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/60 transition group cursor-pointer" @click="open = !open">
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-slate-500 transition-transform duration-200" :class="open ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                            {{-- Unit Icon (2 People) --}}
                            <div class="p-1 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /></svg>
                            </div>
                            <span class="font-bold text-slate-900 dark:text-slate-100">6481 - Regional PDS Jawa</span>
                        </div>
                        <span class="text-[11px] text-slate-400 dark:text-slate-500 font-sans">01-01-2024 - 31-12-9999</span>
                    </div>

                    {{-- Level 2 Children --}}
                    <div x-show="open" class="pl-6 space-y-1 mt-1 border-l-2 border-slate-100 dark:border-slate-800 ml-3">
                        
                        {{-- Child 1: 0710 - PT Pelabuhan Indonesia --}}
                        <div class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/60 transition cursor-pointer">
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                                <div class="p-1 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /></svg>
                                </div>
                                <span class="font-bold text-slate-800 dark:text-slate-200">0710 - PT Pelabuhan Indonesia</span>
                            </div>
                            <span class="text-[11px] text-slate-400 dark:text-slate-500 font-sans">01-01-2024 - 31-12-9999</span>
                        </div>

                        {{-- Child 2: 6824 - PT Berlian Jasa Terminal Indonesia --}}
                        <div class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/60 transition cursor-pointer">
                            <div class="flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                                <div class="p-1 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /></svg>
                                </div>
                                <span class="font-bold text-slate-800 dark:text-slate-200">6824 - PT Berlian Jasa Terminal Indonesia</span>
                            </div>
                            <span class="text-[11px] text-slate-400 dark:text-slate-500 font-sans">01-01-2024 - 31-12-9999</span>
                        </div>

                        {{-- Child 3: 6828 - PT Pelindo Energi Logistik --}}
                        <div x-data="{ subOpen: true }">
                            <div class="flex items-center justify-between py-1.5 px-2 rounded-lg bg-slate-100/80 dark:bg-slate-800/80 border-l-4 border-blue-600 transition cursor-pointer" @click="subOpen = !subOpen">
                                <div class="flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5 text-slate-600 dark:text-slate-300 transition-transform duration-200" :class="subOpen ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                                    <div class="p-1 rounded bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /></svg>
                                    </div>
                                    <span class="font-extrabold text-slate-900 dark:text-white">6828 - PT Pelindo Energi Logistik</span>
                                </div>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 font-sans">01-01-2024 - 31-12-9999</span>
                            </div>

                            {{-- Level 3 Children --}}
                            <div x-show="subOpen" class="pl-6 space-y-1 mt-1 border-l-2 border-slate-100 dark:border-slate-800 ml-3">
                                
                                {{-- Sub-unit: 6831 - Surabaya --}}
                                <div x-data="{ branchOpen: true }">
                                    <div class="flex items-center justify-between py-1.5 px-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/60 transition cursor-pointer" @click="branchOpen = !branchOpen">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="branchOpen ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                                            <div class="p-1 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /></svg>
                                            </div>
                                            <span class="font-bold text-slate-800 dark:text-slate-200">6831 - Surabaya</span>
                                        </div>
                                        <span class="text-[11px] text-slate-400 dark:text-slate-500 font-sans">01-01-2024 - 31-12-9999</span>
                                    </div>

                                    {{-- Level 4 Items: Job Formations & Employees --}}
                                    <div x-show="branchOpen" class="pl-6 space-y-1.5 mt-1 border-l-2 border-slate-100 dark:border-slate-800 ml-3">
                                        
                                        {{-- Job Formation 1: 43314 - Tenaga Pengamanan --}}
                                        <div x-data="{ jobOpen: true }">
                                            <div class="flex items-center justify-between py-1 px-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/60 transition cursor-pointer" @click="jobOpen = !jobOpen">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-3 h-3 text-slate-400 transition-transform duration-200" :class="jobOpen ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                                                    {{-- Job Formation Icon (Person in Circle) --}}
                                                    <div class="p-1 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                                    </div>
                                                    <span class="font-semibold text-slate-700 dark:text-slate-300">43314 - Tenaga Pengamanan</span>
                                                </div>
                                                <span class="text-[11px] text-slate-400 dark:text-slate-500 font-sans">01-06-2024 - 31-12-9999</span>
                                            </div>

                                            {{-- Employee under Job Formation 1 --}}
                                            <div x-show="jobOpen" class="pl-7 mt-1">
                                                <div class="flex items-center justify-between py-1 px-2 rounded-lg bg-blue-50/50 dark:bg-blue-950/30 text-blue-900 dark:text-blue-200 border-l-2 border-emerald-500">
                                                    <div class="flex items-center gap-2">
                                                        {{-- Employee Icon (Single Person) --}}
                                                        <div class="p-1 rounded bg-slate-800 text-white dark:bg-slate-100 dark:text-slate-900">
                                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                                        </div>
                                                        <span class="font-bold">5192052205 - AHMAD NANANG SUCIPTO</span>
                                                    </div>
                                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 font-sans">03-07-2018 - 31-12-9999</span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Job Formation 2: 43315 - Pengemudi --}}
                                        <div class="flex items-center justify-between py-1 px-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/60 transition cursor-pointer">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                                                <div class="p-1 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                                </div>
                                                <span class="font-semibold text-slate-700 dark:text-slate-300">43315 - Pengemudi</span>
                                            </div>
                                            <span class="text-[11px] text-slate-400 dark:text-slate-500 font-sans">01-06-2024 - 31-12-9999</span>
                                        </div>

                                        {{-- Job Formation 3: 43316 - Operator Jembatan Timbang --}}
                                        <div class="flex items-center justify-between py-1 px-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/60 transition cursor-pointer">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                                                <div class="p-1 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                                </div>
                                                <span class="font-semibold text-slate-700 dark:text-slate-300">43316 - Operator Jembatan Timbang</span>
                                            </div>
                                            <span class="text-[11px] text-slate-400 dark:text-slate-500 font-sans">01-06-2024 - 31-12-9999</span>
                                        </div>

                                        {{-- Job Formation 4: 43317 - Operator Jembatan Timbang --}}
                                        <div class="flex items-center justify-between py-1 px-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800/60 transition cursor-pointer">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-3 h-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                                                <div class="p-1 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                                </div>
                                                <span class="font-semibold text-slate-700 dark:text-slate-300">43317 - Operator Jembatan Timbang</span>
                                            </div>
                                            <span class="text-[11px] text-slate-400 dark:text-slate-500 font-sans">01-06-2024 - 31-12-9999</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- LEGEND SIDEBAR CONTAINER (RIGHT 4 COLS) --}}
            <div class="lg:col-span-4 border border-slate-200 dark:border-slate-800 rounded-xl p-5 bg-white dark:bg-slate-900 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider border-b border-slate-100 dark:border-slate-800 pb-2">Keterangan Ikon Structure</h3>
                
                <div class="space-y-3.5 text-xs font-semibold text-slate-700 dark:text-slate-300">
                    {{-- Unit / Departement --}}
                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <div class="p-2 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-100 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /></svg>
                        </div>
                        <div>
                            <span class="block font-bold text-slate-900 dark:text-white">Menandakan Unit/Departement</span>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">Struktur entitas bisnis / cabang / divisi</span>
                        </div>
                    </div>

                    {{-- Job Formation --}}
                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <div class="p-2 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-100 shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        </div>
                        <div>
                            <span class="block font-bold text-slate-900 dark:text-white">Menandakan Job Formation</span>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">Posisi formasi jabatan pekerjaan</span>
                        </div>
                    </div>

                    {{-- Employee --}}
                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800">
                        <div class="p-2 rounded-lg bg-slate-800 text-white dark:bg-slate-100 dark:text-slate-900 shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        </div>
                        <div>
                            <span class="block font-bold text-slate-900 dark:text-white">Menandakan Employee</span>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">Pegawai pelaksana aktif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BOTTOM NOTICE CARD MATCHING SLIDE NOTE --}}
        <div class="p-4 rounded-xl bg-blue-50/70 dark:bg-blue-950/40 border border-blue-200/80 dark:border-blue-800/70 text-blue-900 dark:text-blue-200 text-xs font-semibold flex items-start gap-3">
            <svg class="w-5 h-5 shrink-0 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
            <div class="leading-relaxed">
                Tampilan <strong class="font-black text-blue-950 dark:text-white">View Organizational Structure</strong> dibuat lebih <strong class="font-black text-blue-600 dark:text-blue-300">Ultimate</strong> dengan load data lebih singkat dan dapat mengakomodir banyak Unit dengan Type User Perusahaan, Cabang, dan Wilayah.
            </div>
        </div>
    </div>
</div>

{{-- MODAL: CREATE UNIT --}}
<div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-sm" style="display: none;">
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showCreateModal = false">
        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-4 uppercase tracking-wider">Tambah Unit Baru</h3>
        
        <form action="{{ route('org.sto.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Kode Unit SAP</label>
                    <input type="text" name="code" required placeholder="Contoh: 6831" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Unit</label>
                    <input type="text" name="name" required placeholder="Contoh: Surabaya" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1.5">Parent Unit (Pelaporan)</label>
                <select name="parent_id" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    <option value="">-- No Parent (Root Unit) --</option>
                    @foreach($rootDivs as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }} ({{ $parent->code }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Tipe Unit</label>
                    <select name="unit_type" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                        <option value="Perusahaan User">Perusahaan User</option>
                        <option value="Cabang">Cabang</option>
                        <option value="Wilayah">Wilayah</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Regional</label>
                    <input type="text" name="regional" value="Regional PDS Jawa" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Cost Center</label>
                    <input type="text" name="cost_center" placeholder="CC-6831" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200 font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Mulai Berlaku</label>
                    <input type="date" name="valid_from" value="{{ date('Y-m-d') }}" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1.5">Deskripsi / Catatan</label>
                <textarea name="description" rows="2" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200" placeholder="Keterangan singkat unit kerja..."></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="button" @click="showCreateModal = false" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-50 transition cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer border-0">
                    Simpan Unit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
