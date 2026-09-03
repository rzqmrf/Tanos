@extends('layouts.app')

@section('title', 'Employee Master — Tanos ERP')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action -->
    <x-page-header 
        title="Employee Master" 
        subtitle="Katalog data induk seluruh pegawai, struktur organisasi, dan penempatan regional."
        :breadcrumbs="[
            'General' => '#',
            'Human Capital' => '#',
            'Employee' => ''
        ]"
        create-label="Tambah Pegawai"
        create-url="{{ route('employees.create') }}"
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
        title="Employee - List" 
        :total="$employees->total()"
        :search-route="route('employees.index')"
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-3">No</th>
                        <th class="py-3.5 px-3">Pegawai / NIPP</th>
                        <th class="py-3.5 px-3">NIK Identity</th>
                        <th class="py-3.5 px-3">Jabatan & Org Unit</th>
                        <th class="py-3.5 px-3 text-center">Status Kerja</th>
                        <th class="py-3.5 px-3 text-center">PTKP Tax</th>
                        <th class="py-3.5 px-3 text-center w-28">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($employees as $index => $item)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                        <td class="py-3.5 px-3 text-slate-400 font-semibold">{{ $employees->firstItem() + $index }}</td>
                        
                        <!-- Name & NIPP -->
                        <td class="py-3.5 px-3 font-bold text-slate-800 dark:text-slate-100">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-7 h-7 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 font-black text-xs flex items-center justify-center shrink-0 border border-emerald-200 dark:border-emerald-800">
                                    {{ strtoupper(substr($item->name, 0, 1)) }}
                                </div>
                                <div>
                                    <a href="{{ route('employees.show', $item->id) }}" class="hover:text-primary transition">
                                        {{ $item->name }}
                                    </a>
                                    <span class="text-[10px] font-mono text-slate-400 block">NIPP: {{ $item->nipp ?? '—' }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- NIK -->
                        <td class="py-3.5 px-3 font-mono text-slate-600 dark:text-slate-400">
                            {{ $item->nik ?? '—' }}
                        </td>

                        <!-- Position & Unit -->
                        <td class="py-3.5 px-3">
                            <span class="font-bold text-slate-800 dark:text-slate-200 block">{{ $item->position?->name ?? $item->jabatan ?? '—' }}</span>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400 block">{{ $item->organizationalUnit?->name ?? '—' }}</span>
                        </td>

                        <!-- Employment Status -->
                        <td class="py-3.5 px-3 text-center">
                            @if($item->employment_status === 'PERMANENT')
                            <span class="px-2.5 py-1 text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 rounded-lg inline-flex items-center">
                                Tetap (PKWTT)
                            </span>
                            @elseif($item->employment_status === 'CONTRACT')
                            <span class="px-2.5 py-1 text-[10px] font-bold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800 rounded-lg inline-flex items-center">
                                Kontrak (PKWT)
                            </span>
                            @else
                            <span class="px-2.5 py-1 text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-lg inline-flex items-center">
                                {{ $item->employment_status ?? '—' }}
                            </span>
                            @endif
                        </td>

                        <!-- PTKP -->
                        <td class="py-3.5 px-3 text-center">
                            <span class="px-2 py-0.5 rounded font-mono font-bold text-[11px] bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                {{ $item->ptkp_status ?? 'TK/0' }}
                            </span>
                        </td>

                        <!-- Action -->
                        <td class="py-3.5 px-3 text-center whitespace-nowrap">
                            <div class="inline-flex items-center space-x-1.5">
                                <x-action-button type="view" :href="route('employees.show', $item->id)" title="View Detail" />
                                <x-action-button type="edit" :href="route('employees.edit', $item->id)" title="Edit Employee" />
                                <form action="{{ route('employees.destroy', $item->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus data pegawai {{ $item->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-action-button type="delete" title="Delete Employee" />
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            Tidak ada data Employee ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($employees->hasPages())
        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
            {{ $employees->links() }}
        </div>
        @endif
    </x-data-card>

</div>
@endsection
