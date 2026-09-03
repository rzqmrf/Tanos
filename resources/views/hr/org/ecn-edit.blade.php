@extends('layouts.app')

@section('title', 'Edit SK ECN #' . $movement->reference_number . ' — Tanos ERP')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Page Header -->
    <x-page-header 
        :title="'Edit SK ECN #' . $movement->reference_number" 
        subtitle="Ubah detail usulan mutasi pegawai, tanggal efektif, atau penempatan jabatan/proyek baru."
        :breadcrumbs="[
            'General' => '#',
            'Human Resource' => '#',
            'ECN Management' => route('org.ecn.index'),
            'Edit SK' => ''
        ]"
    >
        <x-slot:action>
            <a href="{{ route('org.ecn.show', $movement->id) }}" 
               class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Kembali</span>
            </a>
        </x-slot:action>
    </x-page-header>

    <!-- Error Notification -->
    @if($errors->any())
    <div class="p-4 bg-rose-50 dark:bg-rose-950/30 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 rounded-xl text-xs font-bold space-y-1">
        @foreach($errors->all() as $err)
            <p>• {{ $err }}</p>
        @endforeach
    </div>
    @endif

    <!-- Form Container Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden p-6 sm:p-8">
        <form action="{{ route('org.ecn.update', $movement->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- No. Surat SK ECN -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        No. Referensi / SK ECN <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="reference_number" value="{{ old('reference_number', $movement->reference_number) }}" required 
                           class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 outline-none text-slate-800 dark:text-slate-100 transition font-mono font-bold">
                </div>

                <!-- Perihal / Judul Pengajuan -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Judul ECN / Perihal <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="ecn_name" value="{{ old('ecn_name', $movement->ecn_name) }}" required 
                           class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 outline-none text-slate-800 dark:text-slate-100 transition">
                </div>

                <!-- Pegawai yang Diusulkan -->
                <div class="space-y-1.5 md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Pilih Pegawai <span class="text-rose-500">*</span>
                    </label>
                    <select name="employee_id" required 
                            class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-800 dark:text-slate-100 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 transition">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id', $movement->employee_id) == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }} (NIP: {{ $emp->nip }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Jenis Mutasi / Perubahan -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Jenis Perubahan / Movement <span class="text-rose-500">*</span>
                    </label>
                    <select name="movement_type" required 
                            class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-800 dark:text-slate-100 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 transition">
                        <option value="Promotion" {{ old('movement_type', $movement->movement_type) == 'Promotion' ? 'selected' : '' }}>Promotion (Promosi Jabatan)</option>
                        <option value="Demotion" {{ old('movement_type', $movement->movement_type) == 'Demotion' ? 'selected' : '' }}>Demotion (Demosi)</option>
                        <option value="Mutation" {{ old('movement_type', $movement->movement_type) == 'Mutation' ? 'selected' : '' }}>Mutation (Mutasi Antar Unit/Proyek)</option>
                        <option value="Rotation" {{ old('movement_type', $movement->movement_type) == 'Rotation' ? 'selected' : '' }}>Rotation (Rotasi Tugas)</option>
                    </select>
                </div>

                <!-- Tanggal Efektif -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Tanggal Efektif SK <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="effective_date" value="{{ old('effective_date', $movement->effective_date ? $movement->effective_date->format('Y-m-d') : '') }}" required 
                           class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 outline-none text-slate-800 dark:text-slate-100 transition">
                </div>

                <!-- Jabatan Tujuan (To Position) -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Formasi Jabatan Tujuan
                    </label>
                    <select name="to_position_id" 
                            class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-800 dark:text-slate-100 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 transition">
                        <option value="">-- Tetap / Tidak Berubah --</option>
                        @foreach($jobPositions as $pos)
                            <option value="{{ $pos->id }}" {{ old('to_position_id', $movement->to_position_id) == $pos->id ? 'selected' : '' }}>
                                {{ $pos->name }} ({{ $pos->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Proyek Tujuan (To Project) -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Project / Segment Tujuan
                    </label>
                    <select name="to_project_id" 
                            class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-800 dark:text-slate-100 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 transition">
                        <option value="">-- Tetap / Tidak Berubah --</option>
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}" {{ old('to_project_id', $movement->to_project_id) == $proj->id ? 'selected' : '' }}>
                                {{ $proj->segment }} - {{ $proj->regional }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('org.ecn.show', $movement->id) }}" 
                   class="px-5 py-2.5 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer border-0 flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
