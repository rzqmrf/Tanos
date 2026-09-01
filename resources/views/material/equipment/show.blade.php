@extends('layouts.app')

@section('title', 'Equipment - View — Tanos ERP')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 dark:text-slate-400 mb-4.5">
                <a href="{{ route('dashboard.index') }}" class="hover:text-primary dark:hover:text-sky-400 transition flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Home
                </a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span>Material</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <a href="{{ route('material.equipment') }}" class="hover:text-primary dark:hover:text-sky-400 transition">Equipment Master</a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-primary dark:text-sky-400 font-black">View</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                Equipment - View
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 font-medium">
                Detail spesifikasi teknis dan riwayat operasional peralatan.
            </p>
        </div>

        <div class="flex items-center space-x-2 self-start sm:self-auto">
            <a href="{{ route('material.equipment') }}"
               class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 shadow-xs cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Back</span>
            </a>

            <a href="{{ route('material.equipment.edit', $equipment->id) }}"
               class="px-4 py-2 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span>Edit</span>
            </a>

            <form action="{{ route('material.equipment.destroy', $equipment->id) }}" method="POST" class="inline"
                  onsubmit="return confirm('Hapus data peralatan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 bg-rose-50 dark:bg-rose-950/50 hover:bg-rose-100 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 shadow-xs cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Delete</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 rounded-xl bg-primary-light text-primary font-mono text-sm font-black">
                    {{ $equipment->equipment_code }}
                </span>
                <h2 class="text-base font-black text-slate-800 dark:text-slate-100">
                    {{ $equipment->name }}
                </h2>
            </div>

            <div>
                @if($equipment->condition === 'Operational')
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400">OPERATIONAL</span>
                @elseif($equipment->condition === 'Maintenance')
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400">MAINTENANCE</span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400">{{ strtoupper($equipment->condition) }}</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 text-xs">
            <!-- Left Info -->
            <div class="space-y-4">
                <div>
                    <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Kategori Peralatan</label>
                    <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl font-bold text-slate-800 dark:text-slate-200">{{ $equipment->category }}</div>
                </div>

                <div>
                    <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Merk & Tipe Model</label>
                    <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl text-slate-800 dark:text-slate-200">{{ $equipment->brand_model ?? '-' }}</div>
                </div>

                <div>
                    <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Nomor Seri (Serial Number)</label>
                    <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl font-mono font-bold text-slate-800 dark:text-slate-200">{{ $equipment->serial_number ?? '-' }}</div>
                </div>

                <div>
                    <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Alokasi Penempatan Project</label>
                    <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl font-semibold text-slate-800 dark:text-slate-200">
                        {{ $equipment->project ? $equipment->project->project_name : 'Pool Standby (Belum Dialokasikan)' }}
                    </div>
                </div>
            </div>

            <!-- Right Info -->
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Tanggal Perolehan</label>
                        <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl font-mono text-slate-800 dark:text-slate-200">{{ $equipment->purchase_date ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Nilai Perolehan</label>
                        <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl font-mono font-bold text-slate-900 dark:text-slate-100">
                            Rp {{ number_format($equipment->purchase_cost, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Servis Terakhir</label>
                        <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl font-mono text-slate-800 dark:text-slate-200">{{ $equipment->last_service_date ?? '-' }}</div>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Jadwal Servis Berikutnya</label>
                        <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl font-mono text-slate-800 dark:text-slate-200">{{ $equipment->next_service_date ?? '-' }}</div>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Kadaluarsa Sertifikasi / SILO</label>
                    <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl font-mono text-slate-800 dark:text-slate-200">{{ $equipment->certification_expiry ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
            <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1 text-xs">Catatan & Keterangan Tambahan</label>
            <div class="p-4 bg-slate-50 dark:bg-slate-800/60 rounded-2xl text-xs text-slate-800 dark:text-slate-200 leading-relaxed min-h-[60px]">
                {{ $equipment->notes ?: 'Tidak ada catatan tambahan untuk peralatan ini.' }}
            </div>
        </div>

    </div>

</div>
@endsection
