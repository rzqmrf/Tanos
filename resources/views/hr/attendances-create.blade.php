@extends('layouts.app')

@section('title', 'Catat Presensi / Cuti Manual — Tanos ERP')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Page Header -->
    <x-page-header 
        title="Catat Presensi / Cuti Manual" 
        subtitle="Input data kehadiran, jam kerja, dinas luar, atau cuti pegawai secara manual."
        :breadcrumbs="[
            'General' => '#',
            'Human Resource' => '#',
            'Absensi & Cuti' => route('attendances.index'),
            'Catat Manual' => ''
        ]"
    >
        <x-slot:action>
            <a href="{{ route('attendances.index') }}" 
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
        <form action="{{ route('attendances.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pegawai -->
                <div class="space-y-1.5 md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Pilih Pegawai <span class="text-rose-500">*</span>
                    </label>
                    <select name="employee_id" required 
                            class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-800 dark:text-slate-100 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition">
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->name }} (NIP: {{ $emp->nip }}) — {{ $emp->regional ?? 'Jawa' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tanggal -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Tanggal Presensi <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required 
                           class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 outline-none text-slate-800 dark:text-slate-100 transition">
                </div>

                <!-- Status Kehadiran -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Status Presensi <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" required 
                            class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-800 dark:text-slate-100 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition">
                        <option value="Hadir" {{ old('status') == 'Hadir' ? 'selected' : '' }}>Hadir (Normal)</option>
                        <option value="Izin" {{ old('status') == 'Izin' ? 'selected' : '' }}>Izin / Cuti</option>
                        <option value="Sakit" {{ old('status') == 'Sakit' ? 'selected' : '' }}>Sakit (Surat Dokter)</option>
                        <option value="Dinas Luar" {{ old('status') == 'Dinas Luar' ? 'selected' : '' }}>Dinas Luar (ST)</option>
                        <option value="Alfa" {{ old('status') == 'Alfa' ? 'selected' : '' }}>Alfa (Tanpa Keterangan)</option>
                    </select>
                </div>

                <!-- Jam Masuk (Clock In) -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Jam Masuk (Clock In)
                    </label>
                    <input type="time" name="clock_in" value="{{ old('clock_in', '08:00') }}" 
                           class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 outline-none text-slate-800 dark:text-slate-100 transition">
                </div>

                <!-- Jam Keluar (Clock Out) -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Jam Keluar (Clock Out)
                    </label>
                    <input type="time" name="clock_out" value="{{ old('clock_out', '17:00') }}" 
                           class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 outline-none text-slate-800 dark:text-slate-100 transition">
                </div>

                <!-- Jam Lembur (Overtime) -->
                <div class="space-y-1.5 md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Jam Lembur / Overtime (Jam)
                    </label>
                    <input type="number" step="0.5" name="overtime_hours" value="{{ old('overtime_hours', 0) }}" min="0" 
                           class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 outline-none text-slate-800 dark:text-slate-100 transition">
                </div>

                <!-- Catatan / Keterangan -->
                <div class="space-y-1.5 md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Catatan / Keterangan Tambahan
                    </label>
                    <textarea name="notes" rows="3" placeholder="Tuliskan catatan khusus atau nomor surat tugas jika ada..." 
                              class="w-full text-xs p-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 outline-none text-slate-800 dark:text-slate-100 transition">{{ old('notes') }}</textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('attendances.index') }}" 
                   class="px-5 py-2.5 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer border-0 flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Simpan Presensi Manual</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
