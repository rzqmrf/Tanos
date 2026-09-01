@extends('layouts.app')

@section('title', 'User - View — Tanos ERP')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 dark:text-slate-400 mb-3">
                <a href="{{ route('dashboard.index') }}" class="hover:text-primary dark:hover:text-sky-400 transition flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Home
                </a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span>General</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span>Setting</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <a href="{{ route('users.index') }}" class="hover:text-primary dark:hover:text-sky-400 transition">User</a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-primary dark:text-sky-400 font-black">View</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                User
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 font-medium">
                Detail profil akun dan konfigurasi Role Group.
            </p>
        </div>

        <div class="flex items-center space-x-2 self-start sm:self-auto">
            <a href="{{ route('users.index') }}"
               class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 shadow-xs cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Back</span>
            </a>

            <a href="{{ route('users.edit', $user->id) }}"
               class="px-4 py-2 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span>Edit</span>
            </a>
        </div>
    </div>

    <!-- Alert Notification -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-2xl text-xs font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Main View Card: User - View -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
            <h2 class="text-base font-black text-slate-800 dark:text-slate-100 flex items-center space-x-3">
                <span>User - View</span>
                <span class="px-2.5 py-0.5 rounded-lg bg-primary-light text-primary font-mono text-xs font-bold">
                    {{ $user->username }}
                </span>
            </h2>

            <span class="px-2.5 py-1 rounded-full text-xs font-extrabold {{ $user->active ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400' : 'bg-slate-150 text-slate-600' }}">
                {{ $user->active ? 'ACTIVE' : 'INACTIVE' }}
            </span>
        </div>

        <!-- User Information Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs max-w-4xl">
            <div class="space-y-3">
                <div>
                    <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5">Nama Lengkap</label>
                    <div class="font-bold text-sm text-slate-900 dark:text-slate-100">{{ $user->name }}</div>
                </div>
                <div>
                    <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5">Username</label>
                    <div class="font-mono font-bold text-primary">{{ $user->username }}</div>
                </div>
                <div>
                    <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5">Jabatan</label>
                    <div class="font-semibold text-slate-800 dark:text-slate-200">{{ $user->jabatan ?? 'Staff' }}</div>
                </div>
            </div>

            <div class="space-y-3">
                <div>
                    <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5">Email</label>
                    <div class="font-mono text-slate-800 dark:text-slate-200">{{ $user->email }}</div>
                </div>
                <div>
                    <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-0.5">Mapping Pegawai (Employee)</label>
                    <div class="font-bold text-slate-800 dark:text-slate-100">
                        @if($user->employee)
                            <span>{{ $user->employee->name }}</span>
                            <span class="text-slate-400 font-mono text-[11px] block">NIPP: {{ $user->employee->nipp ?? '-' }}</span>
                        @else
                            <span class="text-slate-400 italic">Tidak terhubung ke profil pegawai</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Role Group & Scope Data Table -->
        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 space-y-3">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Role Group & Hak Akses Scope Data</h3>
            
            <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-800 text-[11px] font-black text-slate-700 dark:text-slate-200">
                            <th rowspan="2" class="py-3 px-3 w-12 text-center border-r border-slate-200 dark:border-slate-700">No</th>
                            <th rowspan="2" class="py-3 px-4 min-w-[180px] border-r border-slate-200 dark:border-slate-700">Role Group</th>
                            <th colspan="5" class="py-2 px-4 text-center border-b border-slate-200 dark:border-slate-700 bg-slate-100/70 dark:bg-slate-800">Scope Data</th>
                        </tr>
                        <tr class="bg-slate-50/90 dark:bg-slate-800/60 text-[11px] font-bold text-slate-600 dark:text-slate-400">
                            <th class="py-2 px-3">Module</th>
                            <th class="py-2 px-3">Action</th>
                            <th class="py-2 px-3">Table</th>
                            <th class="py-2 px-3">Column</th>
                            <th class="py-2 px-3">Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                        @php
                            $roleGroups = $user->role_groups ?? [
                                ['role_group' => $user->role ?? 'Admin', 'module' => 'All Modules', 'action' => 'All', 'table' => '-', 'column' => '-', 'value' => '-']
                            ];
                        @endphp
                        @foreach($roleGroups as $idx => $row)
                        <tr>
                            <td class="py-3 px-3 text-center font-mono font-bold text-slate-500 border-r border-slate-200 dark:border-slate-800">{{ $idx + 1 }}</td>
                            <td class="py-3 px-4 font-bold text-primary border-r border-slate-200 dark:border-slate-800">
                                <span class="px-2.5 py-1 rounded-lg bg-primary-light text-primary font-bold">
                                    {{ $row['role_group'] ?? 'Admin' }}
                                </span>
                            </td>
                            <td class="py-3 px-3 font-semibold text-slate-800 dark:text-slate-200">{{ $row['module'] ?? 'All' }}</td>
                            <td class="py-3 px-3">
                                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                    {{ $row['action'] ?? 'All' }}
                                </span>
                            </td>
                            <td class="py-3 px-3 font-mono text-slate-500">{{ $row['table'] ?: '-' }}</td>
                            <td class="py-3 px-3 font-mono text-slate-500">{{ $row['column'] ?: '-' }}</td>
                            <td class="py-3 px-3 font-mono text-slate-500">{{ $row['value'] ?: '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
