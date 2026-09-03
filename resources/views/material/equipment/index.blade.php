@extends('layouts.app')

@section('title', 'Equipment Master — Tanos ERP')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action -->
    <x-page-header 
        title="Equipment Master" 
        subtitle="Katalog Alat Berat, Armada Logistik, dan Mesin Operasional Pelabuhan."
        :breadcrumbs="[
            'General' => '#',
            'Material' => '#',
            'Equipment Master' => ''
        ]"
        create-label="Tambah Equipment"
        create-url="{{ route('material.equipment.create') }}"
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
        title="Equipment - List" 
        :total="$equipments->total()"
        :search-route="route('material.equipment')"
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-3">No</th>
                        <th class="py-3.5 px-3">Kode Equipment</th>
                        <th class="py-3.5 px-3">Nama Equipment</th>
                        <th class="py-3.5 px-3">Merek / Model</th>
                        <th class="py-3.5 px-3">Serial / Chassis</th>
                        <th class="py-3.5 px-3 text-center">Tahun Beli</th>
                        <th class="py-3.5 px-3 text-center">Kondisi Status</th>
                        <th class="py-3.5 px-3 text-right">Nilai Perolehan</th>
                        <th class="py-3.5 px-3 text-center w-28">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($equipments as $index => $item)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                        <td class="py-3.5 px-3 text-slate-400 font-semibold">{{ $equipments->firstItem() + $index }}</td>
                        
                        <!-- Code -->
                        <td class="py-3.5 px-3">
                            <a href="{{ route('material.equipment.show', $item->id) }}" class="px-2.5 py-1 bg-primary-light text-primary font-mono font-bold rounded-md text-[11px] border border-primary-subtle hover:opacity-80 transition inline-block">
                                {{ $item->equipment_code }}
                            </a>
                        </td>

                        <!-- Name -->
                        <td class="py-3.5 px-3 font-bold text-slate-800 dark:text-slate-100">
                            <a href="{{ route('material.equipment.show', $item->id) }}" class="hover:text-primary transition">
                                {{ $item->name }}
                            </a>
                        </td>

                        <!-- Brand / Model -->
                        <td class="py-3.5 px-3 text-slate-700 dark:text-slate-300">
                            {{ $item->brand_model ?? '—' }}
                        </td>

                        <!-- Serial -->
                        <td class="py-3.5 px-3 font-mono text-slate-600 dark:text-slate-400">
                            {{ $item->serial_number ?? '—' }}
                        </td>

                        <!-- Year -->
                        <td class="py-3.5 px-3 text-center font-mono text-slate-700 dark:text-slate-300 font-bold">
                            {{ $item->year_manufactured ?? '—' }}
                        </td>

                        <!-- Condition Status -->
                        <td class="py-3.5 px-3 text-center">
                            @if($item->condition === 'Operational')
                                <span class="px-2.5 py-1 text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 rounded-lg inline-flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>OPERATIONAL
                                </span>
                            @elseif($item->condition === 'Maintenance')
                                <span class="px-2.5 py-1 text-[10px] font-bold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800 rounded-lg inline-flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>MAINTENANCE
                                </span>
                            @else
                                <span class="px-2.5 py-1 text-[10px] font-bold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800 rounded-lg inline-flex items-center">
                                    {{ strtoupper($item->condition) }}
                                </span>
                            @endif
                        </td>

                        <!-- Cost -->
                        <td class="py-3.5 px-3 text-right font-mono font-bold text-emerald-600 dark:text-emerald-400">
                            Rp {{ number_format($item->purchase_cost, 0, ',', '.') }}
                        </td>

                        <!-- Action -->
                        <td class="py-3.5 px-3 text-center whitespace-nowrap">
                            <div class="inline-flex items-center space-x-1.5">
                                <x-action-button type="view" :href="route('material.equipment.show', $item->id)" title="View Equipment" />
                                <x-action-button type="edit" :href="route('material.equipment.edit', $item->id)" title="Edit Equipment" />
                                <form action="{{ route('material.equipment.destroy', $item->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus data peralatan {{ $item->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-action-button type="delete" title="Delete Equipment" />
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            Tidak ada data Equipment ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($equipments->hasPages())
        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
            {{ $equipments->links() }}
        </div>
        @endif
    </x-data-card>

</div>
@endsection
