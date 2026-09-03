@extends('layouts.app')

@section('title', 'User — Tanos ERP')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action -->
    <x-page-header 
        title="User Management" 
        subtitle="Kelola akun pengguna, peran sistem (roles), dan otorisasi hak akses modul ERP."
        :breadcrumbs="[
            'General' => '#',
            'Settings' => '#',
            'User' => ''
        ]"
        create-label="Tambah User"
        create-url="{{ route('users.create') }}"
    />

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
    <x-data-card 
        title="User - List" 
        :total="$users->total()"
        :search-route="route('users.index')"
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-3">No</th>
                        <th class="py-3.5 px-3">Full Name</th>
                        <th class="py-3.5 px-3">Username</th>
                        <th class="py-3.5 px-3">Email Address</th>
                        <th class="py-3.5 px-3">Role Group</th>
                        <th class="py-3.5 px-3">PEO / Company</th>
                        <th class="py-3.5 px-3 text-center">Status</th>
                        <th class="py-3.5 px-3 text-center w-28">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($users as $index => $item)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                        <td class="py-3.5 px-3 text-slate-400 font-semibold">{{ $users->firstItem() + $index }}</td>
                        
                        <!-- Name with Avatar -->
                        <td class="py-3.5 px-3 font-bold text-slate-800 dark:text-slate-100">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-7 h-7 rounded-full bg-primary/10 text-primary font-black text-xs flex items-center justify-center border border-primary/20 shrink-0">
                                    {{ strtoupper(substr($item->name, 0, 1)) }}
                                </div>
                                <a href="{{ route('users.show', $item->id) }}" class="hover:text-primary transition">
                                    {{ $item->name }}
                                </a>
                            </div>
                        </td>

                        <!-- Username -->
                        <td class="py-3.5 px-3 font-mono text-slate-600 dark:text-slate-400">
                            {{ $item->username ?? '—' }}
                        </td>

                        <!-- Email -->
                        <td class="py-3.5 px-3 text-slate-600 dark:text-slate-400">
                            {{ $item->email }}
                        </td>

                        <!-- Role Group -->
                        <td class="py-3.5 px-3">
                            @if($item->roleGroup)
                                <span class="px-2.5 py-1 bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-400 font-bold rounded-lg text-[11px] border border-sky-200 dark:border-sky-800/50 inline-flex items-center">
                                    {{ $item->roleGroup->name }}
                                </span>
                            @else
                                <span class="text-slate-400 font-normal">No Role</span>
                            @endif
                        </td>

                        <!-- PEO -->
                        <td class="py-3.5 px-3 text-slate-600 dark:text-slate-400 font-semibold">
                            {{ $item->peoSetting?->name ?? 'System Global' }}
                        </td>

                        <!-- Active Status -->
                        <td class="py-3.5 px-3 text-center">
                            @if($item->active)
                            <span class="px-2.5 py-1 text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 rounded-lg inline-flex items-center">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>ACTIVE
                            </span>
                            @else
                            <span class="px-2.5 py-1 text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-lg inline-flex items-center">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>INACTIVE
                            </span>
                            @endif
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
                        <td colspan="8" class="py-8 text-center text-slate-400 dark:text-slate-500">
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
    </x-data-card>

</div>
@endsection
