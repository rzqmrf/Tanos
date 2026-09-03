@extends('layouts.app')

@section('title', 'User — Tanos ERP')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 dark:text-slate-400 mb-4 print:hidden">
                <a href="{{ route('dashboard.index') }}" class="hover:text-primary dark:hover:text-sky-400 transition flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Home
                </a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span>General</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span>Setting</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-primary dark:text-sky-400 font-black">User</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                User Management
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 font-medium">
                Manage Users & Role Groups.
            </p>
        </div>

        <a href="{{ route('users.create') }}"
           class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 self-start sm:self-auto cursor-pointer border-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Create New</span>
        </a>
    </div>

    <!-- Alert Notification -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Data Table Container -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden p-6 space-y-5">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center space-x-2">
                <h2 class="text-base font-black text-slate-800 dark:text-slate-100">
                    User - List
                </h2>
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                    {{ $users->total() }} total data
                </span>
            </div>

            <!-- Export Buttons -->
            <div class="flex items-center space-x-2">
                <button type="button" onclick="window.print()" title="Cetak Rekap Data"
                        class="p-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl transition cursor-pointer flex items-center space-x-1 text-xs font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Cetak</span>
                </button>
                <button type="button" onclick="window.print()" title="Export PDF"
                        class="p-2 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 text-rose-600 dark:text-rose-400 rounded-xl transition border border-rose-200 dark:border-rose-900/50 cursor-pointer flex items-center space-x-1 text-xs font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>PDF</span>
                </button>
                <a href="#" onclick="alert('Export Excel sedang diproses...')" title="Export Excel"
                        class="p-2 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 text-emerald-600 dark:text-emerald-400 rounded-xl transition border border-emerald-200 dark:border-emerald-900/50 cursor-pointer flex items-center space-x-1 text-xs font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>XLS</span>
                </a>
            </div>
        </div>

        <!-- Filter Controls -->
        <form method="GET" action="{{ route('users.index') }}" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-2 text-xs text-slate-600 dark:text-slate-400 font-bold">
                <span>Tampilkan</span>
                <select name="per_page" onchange="this.form.submit()"
                        class="px-2.5 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-800 dark:text-slate-200 focus:outline-none cursor-pointer">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                </select>
                <span>entri per halaman</span>
            </div>

            <div class="flex items-center space-x-2">
                <div class="relative min-w-[240px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search user, email, jabatan..."
                           class="w-full pl-8 pr-4 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                @if(request()->filled('search'))
                <a href="{{ route('users.index') }}" class="p-2 text-xs font-bold text-slate-400 hover:text-slate-600">Reset</a>
                @endif
            </div>
        </form>

        <!-- Main Users Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-800/60 border-y border-slate-200 dark:border-slate-800 text-[11px] font-extrabold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                        <th class="py-3.5 px-3">Name</th>
                        <th class="py-3.5 px-3">Username</th>
                        <th class="py-3.5 px-3">Jabatan</th>
                        <th class="py-3.5 px-3">Email</th>
                        <th class="py-3.5 px-3">Employee Mapping</th>
                        <th class="py-3.5 px-3">Role Groups</th>
                        <th class="py-3.5 px-3 text-center">Status</th>
                        <th class="py-3.5 px-3 text-center whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($users as $item)
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                        <!-- Name -->
                        <td class="py-3.5 px-3 font-bold text-slate-900 dark:text-slate-100">
                            <a href="{{ route('users.show', $item->id) }}" class="hover:text-primary dark:hover:text-sky-400 transition">
                                {{ $item->name }}
                            </a>
                        </td>

                        <!-- Username -->
                        <td class="py-3.5 px-3 font-mono font-bold text-primary">
                            <a href="{{ route('users.show', $item->id) }}" class="hover:underline">
                                {{ $item->username }}
                            </a>
                        </td>

                        <!-- Jabatan -->
                        <td class="py-3.5 px-3 font-medium text-slate-800 dark:text-slate-200">
                            {{ $item->jabatan ?? 'Staff' }}
                        </td>

                        <!-- Email -->
                        <td class="py-3.5 px-3 font-mono text-slate-600 dark:text-slate-400">
                            {{ $item->email }}
                        </td>

                        <!-- Employee -->
                        <td class="py-3.5 px-3 text-slate-600 dark:text-slate-400">
                            @if($item->employee)
                                <span class="font-bold text-slate-800 dark:text-slate-200 block">{{ $item->employee->name }}</span>
                                <span class="text-[10px] font-mono text-slate-400">NIPP: {{ $item->employee->nipp ?? '-' }}</span>
                            @else
                                <span class="text-slate-400 italic">-</span>
                            @endif
                        </td>

                        <!-- Role Groups -->
                        <td class="py-3.5 px-3">
                            @php
                                $rgs = $item->role_groups ?? [['role_group' => $item->role]];
                            @endphp
                            <div class="flex flex-wrap gap-1">
                                @foreach($rgs as $rg)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-primary-light text-primary">
                                        {{ $rg['role_group'] ?? $item->role }}
                                    </span>
                                @endforeach
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="py-3.5 px-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold {{ $item->active ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400' : 'bg-slate-150 text-slate-500' }}">
                                {{ $item->active ? 'ACTIVE' : 'INACTIVE' }}
                            </span>
                        </td>

                        <!-- Action -->
                        <td class="py-3.5 px-3 text-center whitespace-nowrap">
                            <div class="inline-flex items-center space-x-1.5">
                                <x-action-button type="view" :href="route('users.show', $item->id)" title="View User" />
                                <x-action-button type="edit" :href="route('users.edit', $item->id)" title="Edit User" />
                                @if($item->id !== auth()->id())
                                <form action="{{ route('users.destroy', $item->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus user {{ $item->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-action-button type="delete" title="Delete User" />
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400">
                            Tidak ada data User ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
            {{ $users->links() }}
        </div>
        @endif

    </div>

</div>
@endsection
