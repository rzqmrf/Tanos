@extends('layouts.app')

@section('title', 'Equipment Master — Tanos ERP')

@section('content')
<div class="space-y-6" x-data="{
    showModal: false,
    editMode: false,
    form: { id: null, equipment_code: '', name: '', category: 'Heavy Equipment', brand_model: '', serial_number: '', project_id: '', condition: 'Operational', purchase_date: '', purchase_cost: 0, last_service_date: '', next_service_date: '', certification_expiry: '', notes: '', active: true },
    openCreate() {
        this.editMode = false;
        this.form = { id: null, equipment_code: '', name: '', category: 'Heavy Equipment', brand_model: '', serial_number: '', project_id: '', condition: 'Operational', purchase_date: '', purchase_cost: 0, last_service_date: '', next_service_date: '', certification_expiry: '', notes: '', active: true };
        this.showModal = true;
    },
    openEdit(item) {
        this.editMode = true;
        this.form = {
            id: item.id,
            equipment_code: item.equipment_code,
            name: item.name,
            category: item.category,
            brand_model: item.brand_model || '',
            serial_number: item.serial_number || '',
            project_id: item.project_id || '',
            condition: item.condition,
            purchase_date: item.purchase_date || '',
            purchase_cost: item.purchase_cost || 0,
            last_service_date: item.last_service_date || '',
            next_service_date: item.next_service_date || '',
            certification_expiry: item.certification_expiry || '',
            notes: item.notes || '',
            active: !!item.active
        };
        this.showModal = true;
    }
}">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-primary uppercase tracking-wider mb-1">
                <span>Material Management</span>
                <span>•</span>
                <span>Logistik & Inventaris</span>
            </div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight flex items-center">
                <span>Equipment Master (Alat Kerja & Alat Berat)</span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Pencatatan aset mesin, alat berat, kendaraan operasional, masa berlaku sertifikasi & jadwal pemeliharaan berkala.</p>
        </div>

        <button @click="openCreate()"
                class="px-5 py-2.5 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-lg shadow-primary transition flex items-center space-x-2 self-start sm:self-auto cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tambah Peralatan</span>
        </button>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-2xl text-xs font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-primary-light text-primary flex items-center justify-center font-bold text-lg shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Unit Alat</p>
                <p class="text-xl font-black text-slate-800 dark:text-slate-100 mt-0.5">{{ $totalAll }} <span class="text-xs font-normal text-slate-400">Unit</span></p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-lg shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Siap Operasional</p>
                <p class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-0.5">{{ $totalOperational }} <span class="text-xs font-normal text-slate-400">Unit</span></p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-lg shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Dalam Servis</p>
                <p class="text-xl font-black text-amber-600 dark:text-amber-400 mt-0.5">{{ $totalMaintenance }} <span class="text-xs font-normal text-slate-400">Unit</span></p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-lg shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Valuasi Aset Alat</p>
                <p class="text-lg font-black text-blue-600 dark:text-blue-400 mt-0.5">Rp {{ number_format($totalValuation, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
        <form method="GET" action="{{ route('material.equipment') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari kode alat, nama alat, brand model, serial number..."
                       class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/20">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="category" class="px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:outline-none">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>

            <select name="condition" class="px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:outline-none">
                <option value="">Semua Kondisi</option>
                <option value="Operational" {{ request('condition') === 'Operational' ? 'selected' : '' }}>Operational</option>
                <option value="Maintenance" {{ request('condition') === 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                <option value="Standby" {{ request('condition') === 'Standby' ? 'selected' : '' }}>Standby</option>
                <option value="Broken" {{ request('condition') === 'Broken' ? 'selected' : '' }}>Broken</option>
            </select>

            <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl transition cursor-pointer">
                Filter
            </button>

            @if(request()->hasAny(['search', 'category', 'condition']))
            <a href="{{ route('material.equipment') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-xl transition flex items-center justify-center">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200/80 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-4 px-5">Kode Alat</th>
                        <th class="py-4 px-5">Nama Alat & Spesifikasi</th>
                        <th class="py-4 px-5">Kategori</th>
                        <th class="py-4 px-5">Lokasi Proyek</th>
                        <th class="py-4 px-5">Kondisi</th>
                        <th class="py-4 px-5">Jadwal Servis Berikutnya</th>
                        <th class="py-4 px-5">Sertifikasi SIO/SILO</th>
                        <th class="py-4 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($equipments as $item)
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                        <td class="py-3.5 px-5 font-mono font-bold text-primary">{{ $item->equipment_code }}</td>
                        <td class="py-3.5 px-5">
                            <span class="font-bold text-slate-800 dark:text-slate-100">{{ $item->name }}</span>
                            <span class="text-[11px] text-slate-400 block mt-0.5">
                                {{ $item->brand_model ?? '-' }} • SN: {{ $item->serial_number ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                {{ $item->category }}
                            </span>
                        </td>
                        <td class="py-3.5 px-5">
                            @if($item->project)
                            <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $item->project->project_name }}</span>
                            <span class="text-[10px] text-slate-400 block">{{ $item->project->regional }}</span>
                            @else
                            <span class="text-slate-400 italic">Pool / Workshop Pusat</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5">
                            @if($item->condition === 'Operational')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400">
                                OPERATIONAL
                            </span>
                            @elseif($item->condition === 'Maintenance')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400">
                                MAINTENANCE
                            </span>
                            @elseif($item->condition === 'Standby')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400">
                                STANDBY
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400">
                                BROKEN
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 font-medium text-slate-600 dark:text-slate-300">
                            @if($item->next_service_date)
                                {{ \Carbon\Carbon::parse($item->next_service_date)->format('d M Y') }}
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 font-medium text-slate-600 dark:text-slate-300">
                            @if($item->certification_expiry)
                                <span class="{{ \Carbon\Carbon::parse($item->certification_expiry)->isPast() ? 'text-rose-600 font-bold' : '' }}">
                                    Exp: {{ \Carbon\Carbon::parse($item->certification_expiry)->format('d M Y') }}
                                </span>
                            @else
                                <span class="text-slate-400">Tidak ada sertifikasi</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 text-right space-x-1.5 whitespace-nowrap">
                            <button @click="openEdit({{ $item }})"
                                    class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 hover:text-primary rounded-lg transition cursor-pointer" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>

                            <form action="{{ route('material.equipment.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus Peralatan {{ $item->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 hover:bg-rose-50 dark:hover:bg-rose-950/40 text-slate-500 hover:text-rose-600 rounded-lg transition cursor-pointer" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            Tidak ada data peralatan kerja.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($equipments->hasPages())
        <div class="p-4 border-t border-slate-100 dark:border-slate-800">
            {{ $equipments->links() }}
        </div>
        @endif
    </div>

    <!-- Modal Create / Edit -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="showModal = false"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-xl bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl p-6 overflow-hidden">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-black text-slate-800 dark:text-slate-100" x-text="editMode ? 'Edit Data Peralatan' : 'Tambah Peralatan Baru'"></h3>
                    <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="editMode ? '{{ url('material/equipment') }}/' + form.id : '{{ route('material.equipment.store') }}'" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kode Peralatan *</label>
                            <input type="text" name="equipment_code" x-model="form.equipment_code" required placeholder="Contoh: EQ-EXC-001"
                                   class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-mono font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kategori Alat *</label>
                            <select name="category" x-model="form.category" required
                                    class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                <option value="Heavy Equipment">Heavy Equipment (Alat Berat)</option>
                                <option value="Vehicle">Vehicle (Armada Kendaraan)</option>
                                <option value="Power Equipment">Power Equipment (Genset / Listrik)</option>
                                <option value="Survey Instrument">Survey Instrument (Total Station/GPS)</option>
                                <option value="Workshop Equipment">Workshop Equipment (Mesin Las/Tools)</option>
                                <option value="Safety Equipment">Safety Equipment (Scaffolding/K3)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Alat / Mesin *</label>
                        <input type="text" name="name" x-model="form.name" required placeholder="Contoh: Hydraulic Excavator CAT 320D"
                               class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Merek & Model Tipe</label>
                            <input type="text" name="brand_model" x-model="form.brand_model" placeholder="Contoh: Caterpillar 320D GC"
                                   class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor Seri / Chassis (SN)</label>
                            <input type="text" name="serial_number" x-model="form.serial_number" placeholder="Nomor Seri Pabrik"
                                   class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-mono text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Lokasi Proyek Penugasan</label>
                            <select name="project_id" x-model="form.project_id"
                                    class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                <option value="">Pool / Workshop Pusat</option>
                                @foreach($projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->project_name }} ({{ $p->regional }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kondisi Alat *</label>
                            <select name="condition" x-model="form.condition" required
                                    class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                <option value="Operational">Operational (Siap Operasi)</option>
                                <option value="Maintenance">Maintenance (Dalam Servis)</option>
                                <option value="Standby">Standby (Siaga)</option>
                                <option value="Broken">Broken (Rusak Berat)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nilai Perolehan (Rp)</label>
                            <input type="number" step="0.01" name="purchase_cost" x-model="form.purchase_cost" placeholder="0.00"
                                   class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Jadwal Servis Berikutnya</label>
                            <input type="date" name="next_service_date" x-model="form.next_service_date"
                                   class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kedaluwarsa Sertifikasi</label>
                            <input type="date" name="certification_expiry" x-model="form.certification_expiry"
                                   class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Catatan Tambahan</label>
                        <textarea name="notes" x-model="form.notes" rows="2" placeholder="Catatan kelengkapan alat..."
                                  class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="showModal = false"
                                class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-md shadow-primary transition cursor-pointer">
                            Simpan Peralatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
