@extends('layouts.app')

@section('title', 'Employee - Edit — Tanos ERP')

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
                <span>Human Capital</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <a href="{{ route('employees.index') }}" class="hover:text-primary dark:hover:text-sky-400 transition">Employee</a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-primary dark:text-sky-400 font-black">Edit</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight flex items-center space-x-3">
                <span>Employee - Edit</span>
                <span class="px-2.5 py-0.5 rounded-lg bg-primary-light text-primary font-mono text-xs font-bold">
                    {{ $employee->nipp ?? ('NIP-' . $employee->id) }}
                </span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 font-medium">
                Perbarui data pokok pegawai, status PTKP, rekening payroll, dan penempatan regional.
            </p>
        </div>

        <div class="flex items-center space-x-2 self-start sm:self-auto">
            <a href="{{ route('employees.show', $employee->id) }}"
               class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 shadow-xs cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Lihat Detail</span>
            </a>
        </div>
    </div>

    <!-- Alert Notification -->
    @if($errors->any())
    <div class="p-4 bg-rose-50 dark:bg-rose-950/30 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 rounded-xl text-xs font-bold space-y-1">
        @foreach($errors->all() as $err)
            <div class="flex items-center space-x-1.5">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span>{{ $err }}</span>
            </div>
        @endforeach
    </div>
    @endif

    <!-- Main Card -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 sm:p-8">
        <form action="{{ route('employees.update', $employee->id) }}" method="POST" class="space-y-6 text-xs">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-4">
                <!-- LEFT COLUMN: Identitas & Struktur -->
                <div class="space-y-4">
                    <h3 class="text-sm font-black text-slate-800 dark:text-slate-100 pb-2 border-b border-slate-100 dark:border-slate-800">
                        1. Data Pokok & Penempatan
                    </h3>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $employee->name) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            NIPP / Nomor Induk Pegawai <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nipp" value="{{ old('nipp', $employee->nipp) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono font-bold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Jabatan / Role <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="role" value="{{ old('role', $employee->role) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Regional <span class="text-rose-500">*</span>
                            </label>
                            <select name="regional" required
                                    class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100 focus:outline-none">
                                @foreach($regionals as $r)
                                    <option value="{{ $r->name }}" {{ $employee->regional === $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Sub Regional</label>
                            <select name="sub_regional"
                                    class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100 focus:outline-none">
                                <option value="">Pilih Sub Regional</option>
                                @foreach($subRegionals as $sr)
                                    <option value="{{ $sr->name }}" {{ $employee->sub_regional === $sr->name ? 'selected' : '' }}>{{ $sr->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Segment <span class="text-rose-500">*</span>
                            </label>
                            <select name="segment" required
                                    class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100 focus:outline-none">
                                @foreach($segments as $s)
                                    <option value="{{ $s->name }}" {{ $employee->segment === $s->name ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Bulan Masuk / Periode <span class="text-rose-500">*</span>
                            </label>
                            <select name="month" required
                                    class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100 focus:outline-none">
                                @foreach($months as $m)
                                    <option value="{{ $m }}" {{ $employee->month === $m ? 'selected' : '' }}>{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Agama</label>
                        <select name="religion"
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100 focus:outline-none">
                            @foreach($religions as $rel)
                                <option value="{{ $rel }}" {{ $employee->religion === $rel ? 'selected' : '' }}>{{ $rel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Pajak, Rekening & BPJS -->
                <div class="space-y-4">
                    <h3 class="text-sm font-black text-slate-800 dark:text-slate-100 pb-2 border-b border-slate-100 dark:border-slate-800">
                        2. Pajak, Payroll & Jaminan Sosial
                    </h3>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Status PTKP Pajak <span class="text-rose-500">*</span>
                        </label>
                        <select name="ptkp_status" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-slate-800 dark:text-slate-100 focus:outline-none">
                            <option value="TK/0" {{ $employee->ptkp_status === 'TK/0' ? 'selected' : '' }}>TK/0 (Tidak Kawin, 0 Tanggungan)</option>
                            <option value="TK/1" {{ $employee->ptkp_status === 'TK/1' ? 'selected' : '' }}>TK/1 (Tidak Kawin, 1 Tanggungan)</option>
                            <option value="TK/2" {{ $employee->ptkp_status === 'TK/2' ? 'selected' : '' }}>TK/2 (Tidak Kawin, 2 Tanggungan)</option>
                            <option value="TK/3" {{ $employee->ptkp_status === 'TK/3' ? 'selected' : '' }}>TK/3 (Tidak Kawin, 3 Tanggungan)</option>
                            <option value="K/0" {{ $employee->ptkp_status === 'K/0' ? 'selected' : '' }}>K/0 (Kawin, 0 Tanggungan)</option>
                            <option value="K/1" {{ $employee->ptkp_status === 'K/1' ? 'selected' : '' }}>K/1 (Kawin, 1 Tanggungan)</option>
                            <option value="K/2" {{ $employee->ptkp_status === 'K/2' ? 'selected' : '' }}>K/2 (Kawin, 2 Tanggungan)</option>
                            <option value="K/3" {{ $employee->ptkp_status === 'K/3' ? 'selected' : '' }}>K/3 (Kawin, 3 Tanggungan)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="col-span-1">
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Bank</label>
                            <input type="text" name="bank_name" value="{{ old('bank_name', $employee->bank_name ?? 'Bank Mandiri') }}"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-slate-800 dark:text-slate-100 focus:outline-none">
                        </div>
                        <div class="col-span-2">
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor Rekening Payroll</label>
                            <input type="text" name="bank_account_number" value="{{ old('bank_account_number', $employee->bank_account_number) }}" placeholder="Contoh: 1420018293849"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Atas Nama Rekening</label>
                        <input type="text" name="bank_account_name" value="{{ old('bank_account_name', $employee->bank_account_name) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-100 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">TMT Tanggal Mulai Kerja</label>
                        <input type="date" name="tmt_date" value="{{ old('tmt_date', $employee->tmt_date ? date('Y-m-d', strtotime($employee->tmt_date)) : '') }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">No. BPJS Kesehatan</label>
                            <input type="text" name="bpjs_kesehatan_number" value="{{ old('bpjs_kesehatan_number', $employee->bpjs_kesehatan_number) }}"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">No. BPJS Ketenagakerjaan</label>
                            <input type="text" name="bpjs_ketenagakerjaan_number" value="{{ old('bpjs_ketenagakerjaan_number', $employee->bpjs_ketenagakerjaan_number) }}"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:outline-none">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('employees.show', $employee->id) }}" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-200 font-bold rounded-xl transition">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-primary-hover text-white font-bold rounded-xl shadow-lg shadow-primary transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>

</div>
@endsection
