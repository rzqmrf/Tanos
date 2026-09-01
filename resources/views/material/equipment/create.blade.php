@extends('layouts.app')

@section('title', 'Equipment - Create — Tanos ERP')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 dark:text-slate-500 mb-1.5">
                <a href="{{ route('dashboard.index') }}" class="hover:text-primary transition flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Home
                </a>
                <span>/</span>
                <span>Material</span>
                <span>/</span>
                <a href="{{ route('material.equipment') }}" class="hover:text-primary transition">Equipment Master</a>
                <span>/</span>
                <span class="text-primary font-black">Create</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                Equipment - Create
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">
                Pendaftaran data armada atau peralatan baru ke dalam inventaris operasional.
            </p>
        </div>

        <a href="{{ route('material.equipment') }}"
           class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 self-start sm:self-auto shadow-xs cursor-pointer">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <span>Back</span>
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 sm:p-8">
        <form action="{{ route('material.equipment.store') }}" method="POST" class="space-y-6 text-xs">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-4">
                <!-- LEFT COLUMN -->
                <div class="space-y-4">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Equipment Code <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="equipment_code" value="{{ old('equipment_code', 'EQP-' . rand(1000, 9999)) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono font-bold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Equipment Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Rubber Tyred Gantry Crane 01"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Category <span class="text-rose-500">*</span>
                        </label>
                        <select name="category" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100 focus:outline-none">
                            <option value="Heavy Equipment">Heavy Equipment (RTG, CC, Reach Stacker)</option>
                            <option value="Vehicles">Vehicles (Head Truck, Chassis, Forklift)</option>
                            <option value="Tools">Tools & Ancillary (Spreader, Genset)</option>
                            <option value="Machinery">Machinery & Vessel Equipment</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Brand & Model
                        </label>
                        <input type="text" name="brand_model" value="{{ old('brand_model') }}" placeholder="Contoh: Kalmar RTG-40E / Konecranes"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-100 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Serial Number
                        </label>
                        <input type="text" name="serial_number" value="{{ old('serial_number') }}" placeholder="Nomor Seri Mesin / Rangka"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:outline-none">
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div class="space-y-4">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Alokasi Project Pelindo
                        </label>
                        <select name="project_id"
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100 focus:outline-none">
                            <option value="">-- Pool Standby (Belum Dialokasikan) --</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->project_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Kondisi / Status Alat <span class="text-rose-500">*</span>
                        </label>
                        <select name="condition" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-slate-800 dark:text-slate-100 focus:outline-none">
                            <option value="Operational">Operational (Siap Operasi)</option>
                            <option value="Maintenance">Maintenance (Dalam Perbaikan/Servis)</option>
                            <option value="Standby">Standby (Cadangan)</option>
                            <option value="Broken">Broken (Rusak Berat)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Tanggal Perolehan</label>
                            <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nilai Perolehan (Rp)</label>
                            <input type="number" name="purchase_cost" value="0" min="0"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono font-bold text-slate-800 dark:text-slate-100 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Servis Terakhir</label>
                            <input type="date" name="last_service_date"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Jadwal Servis Berikutnya</label>
                            <input type="date" name="next_service_date"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Kadaluarsa Sertifikasi / SILO</label>
                        <input type="date" name="certification_expiry"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Catatan Tambahan & Spesifikasi</label>
                <textarea name="notes" rows="3" placeholder="Keterangan spesifikasi teknis alat..."
                          class="w-full p-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-slate-100 focus:outline-none leading-relaxed"></textarea>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('material.equipment') }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-200 font-bold rounded-xl transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-primary-hover text-white font-bold rounded-xl shadow-lg shadow-primary transition">Simpan Equipment</button>
            </div>
        </form>
    </div>

</div>
@endsection
