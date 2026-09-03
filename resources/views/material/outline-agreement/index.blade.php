@extends('layouts.app')

@section('title', 'Outline Agreement — Tanos ERP')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action -->
    <x-page-header 
        title="Outline Agreement Master" 
        subtitle="Kontrak Payung Pengadaan Material, Suku Cadang, dan Jasa Vendor Rekanan."
        :breadcrumbs="[
            'General' => '#',
            'Material' => '#',
            'Outline Agreement' => ''
        ]"
        create-label="Tambah Outline Agreement"
        create-url="{{ route('material.outline-agreement.create') }}"
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
        title="Outline Agreement - List" 
        :total="$agreements->total()"
        :search-route="route('material.outline-agreement')"
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-3">No</th>
                        <th class="py-3.5 px-3">No Perjanjian</th>
                        <th class="py-3.5 px-3">Judul Kontrak / Deskripsi</th>
                        <th class="py-3.5 px-3">Vendor / Rekanan</th>
                        <th class="py-3.5 px-3">Masa Perjanjian</th>
                        <th class="py-3.5 px-3 text-right">Target Nilai Kontrak</th>
                        <th class="py-3.5 px-3 text-center">Status</th>
                        <th class="py-3.5 px-3 text-center w-28">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($agreements as $index => $item)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                        <td class="py-3.5 px-3 text-slate-400 font-semibold">{{ $agreements->firstItem() + $index }}</td>
                        
                        <!-- Agreement No -->
                        <td class="py-3.5 px-3">
                            <a href="{{ route('material.outline-agreement.show', $item->id) }}" class="px-2.5 py-1 bg-primary-light text-primary font-mono font-bold rounded-md text-[11px] border border-primary-subtle hover:opacity-80 transition inline-block">
                                {{ $item->agreement_number }}
                            </a>
                        </td>

                        <!-- Title -->
                        <td class="py-3.5 px-3 font-bold text-slate-800 dark:text-slate-100">
                            <a href="{{ route('material.outline-agreement.show', $item->id) }}" class="hover:text-primary transition">
                                {{ $item->title }}
                            </a>
                        </td>

                        <!-- Vendor -->
                        <td class="py-3.5 px-3 text-slate-700 dark:text-slate-300 font-semibold">
                            {{ $item->partner ? $item->partner->name : '-' }}
                        </td>

                        <!-- Validity Period -->
                        <td class="py-3.5 px-3 font-mono text-slate-600 dark:text-slate-400 text-[11px]">
                            {{ $item->start_date }} - {{ $item->end_date }}
                        </td>

                        <!-- Target Value -->
                        <td class="py-3.5 px-3 text-right font-mono font-bold text-emerald-600 dark:text-emerald-400">
                            {{ $item->currency }} {{ number_format($item->target_value, 0, ',', '.') }}
                        </td>

                        <!-- Status -->
                        <td class="py-3.5 px-3 text-center">
                            @if($item->status === 'Active')
                                <span class="px-2.5 py-1 text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 rounded-lg inline-flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>ACTIVE
                                </span>
                            @elseif($item->status === 'Draft')
                                <span class="px-2.5 py-1 text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-lg inline-flex items-center">
                                    DRAFT
                                </span>
                            @else
                                <span class="px-2.5 py-1 text-[10px] font-bold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800 rounded-lg inline-flex items-center">
                                    {{ strtoupper($item->status) }}
                                </span>
                            @endif
                        </td>

                        <!-- Action -->
                        <td class="py-3.5 px-3 text-center whitespace-nowrap">
                            <div class="inline-flex items-center space-x-1.5">
                                <x-action-button type="view" :href="route('material.outline-agreement.show', $item->id)" title="View Agreement" />
                                <x-action-button type="edit" :href="route('material.outline-agreement.edit', $item->id)" title="Edit Agreement" />
                                <form action="{{ route('material.outline-agreement.destroy', $item->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus data kontrak payung ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-action-button type="delete" title="Delete Agreement" />
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            Tidak ada data Outline Agreement ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($agreements->hasPages())
        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
            {{ $agreements->links() }}
        </div>
        @endif
    </x-data-card>

</div>
@endsection
