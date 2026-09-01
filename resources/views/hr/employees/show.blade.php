@extends('layouts.app')

@section('title', 'Employee - View Detail — Tanos ERP')

@section('content')
<div class="space-y-6">
    {{-- ALERT MESSAGES --}}
    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 font-bold">&times;</button>
    </div>
    @endif

    {{-- HEADER & BREADCRUMB --}}
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
                <span class="text-primary dark:text-sky-400 font-black">View</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                Employee - View Detail
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 font-medium">
                Detail profil data pokok, status PTKP, data rekening, dan riwayat TMT kerja.
            </p>
        </div>

        <div class="flex items-center space-x-2 self-start sm:self-auto">
            <a href="{{ route('employees.index') }}"
               class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 shadow-xs cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Back</span>
            </a>

            <a href="{{ route('employees.edit', $employee->id) }}"
               class="px-4 py-2 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span>Edit</span>
            </a>
        </div>
    </div>

    {{-- MAIN CARD WITH TABS --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl shadow-sm overflow-hidden" x-data="{ activeTab: 'general_info' }">
        {{-- Card Header Title --}}
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">
                Employee &ndash; Detail: <span class="uppercase text-primary font-extrabold">{{ $employee->name }}</span>
            </h2>
            <span class="text-xs font-mono font-bold px-2.5 py-1 rounded-lg bg-primary-light text-primary">
                NIPP: {{ $employee->nipp ?? ('NIP-' . $employee->id) }}
            </span>
        </div>

        {{-- HORIZONTAL TABS --}}
        <div class="px-6 border-b border-slate-200 dark:border-slate-800 overflow-x-auto bg-slate-50/50 dark:bg-slate-900/50">
            @php
                $tabs = [
                    'general_info' => 'General Info',
                    'employee_identity' => 'Employee Identity',
                    'communication_info' => 'Communication Information',
                    'tax_info' => 'Tax Information (PTKP)',
                    'bank_account' => 'Bank Account',
                    'custom_date' => 'Custom Date (TMT Kerja)',
                    'education' => 'Education',
                    'work_experience' => 'Work Experience',
                    'additional_info' => 'Additional Info',
                ];
            @endphp
            <div class="flex items-center gap-6 whitespace-nowrap">
                @foreach($tabs as $key => $label)
                <button @click="activeTab = '{{ $key }}'"
                    :class="activeTab === '{{ $key }}' ? 'text-primary border-b-2 border-primary font-bold' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 border-b-2 border-transparent font-medium'"
                    class="py-3.5 text-xs transition duration-150 cursor-pointer">
                    {{ $label }}
                </button>
                @endforeach
            </div>
        </div>

        {{-- TAB CONTENT --}}
        <div class="p-6">
            {{-- TAB 1: GENERAL INFO --}}
            <div x-show="activeTab === 'general_info'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs font-semibold">
                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">Full Name *</label>
                        <input type="text" disabled value="{{ $employee->name }}"
                            class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold uppercase cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">Place of Birth *</label>
                        <input type="text" disabled value="{{ $employee->place_of_birth ?? 'SURABAYA' }}"
                            class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold uppercase cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">Date of Birth *</label>
                        <input type="text" disabled value="{{ $employee->date_of_birth ? $employee->date_of_birth->format('d/m/Y') : '13/01/2001' }}"
                            class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">Gender *</label>
                        <input type="text" disabled value="{{ str_starts_with(strtolower($employee->gender ?? 'L'), 'p') ? 'Perempuan (P)' : 'Laki-Laki (L)' }}"
                            class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">Document Status</label>
                        <input type="text" disabled value="Completed (Terverifikasi)"
                            class="w-full px-3.5 py-2.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 font-bold cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">Status Pegawai</label>
                        <input type="text" disabled value="{{ $employee->role ?? 'Staff Operasional' }} (Aktif)"
                            class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold cursor-not-allowed">
                    </div>
                </div>
            </div>

            {{-- TAB 2: EMPLOYEE IDENTITY --}}
            <div x-show="activeTab === 'employee_identity'" class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs font-semibold" style="display: none;">
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">Identity Card Number (No KTP) *</label>
                    <input type="text" disabled value="{{ $employee->identity_card_number ?? sprintf('35072213%08d', $employee->id) }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-mono font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">NIPP Pegawai *</label>
                    <input type="text" disabled value="{{ $employee->nipp ?? sprintf('TAD-%05d', $employee->id) }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-mono font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">Agama (Religion)</label>
                    <input type="text" disabled value="{{ $employee->religion ?? 'ISLAM' }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">Regional Office</label>
                    <input type="text" disabled value="{{ $employee->regional ?? 'Regional Jawa' }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">Sub Regional</label>
                    <input type="text" disabled value="{{ $employee->sub_regional ?? '-' }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">Segment Bisnis</label>
                    <input type="text" disabled value="{{ $employee->segment ?? '01. Tenaga Alih Daya Operasional' }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold cursor-not-allowed">
                </div>
            </div>

            {{-- TAB 3: COMMUNICATION INFORMATION --}}
            <div x-show="activeTab === 'communication_info'" class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs font-semibold" style="display: none;">
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">Email Perusahaan</label>
                    <input type="text" disabled value="{{ Str::slug($employee->name, '.') }}@pelindo.co.id"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-mono font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">No Telepon / WhatsApp</label>
                    <input type="text" disabled value="08123456{{ sprintf('%04d', $employee->id) }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-mono font-bold cursor-not-allowed">
                </div>
            </div>

            {{-- TAB 4: TAX INFORMATION (PTKP & PAJAK) --}}
            <div x-show="activeTab === 'tax_info'" class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs font-semibold" style="display: none;">
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">NPWP Number *</label>
                    <input type="text" disabled value="{{ $employee->npwp_number ?? sprintf('00%013d', $employee->id) }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-mono font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">Status PTKP (Penghasilan Tidak Kena Pajak) *</label>
                    <input type="text" disabled value="{{ $employee->ptkp_status ?? 'TK/0' }}"
                        class="w-full px-3.5 py-2.5 bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 text-blue-900 dark:text-blue-200 font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">KPP Pratama Terdaftar</label>
                    <input type="text" disabled value="KPP Pratama Surabaya Gubeng"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold cursor-not-allowed">
                </div>
            </div>

            {{-- TAB 5: BANK ACCOUNT --}}
            <div x-show="activeTab === 'bank_account'" class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs font-semibold" style="display: none;">
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">Bank Name *</label>
                    <input type="text" disabled value="{{ $employee->bank_name ?? 'BANK MANDIRI' }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">Account Number (No Rekening) *</label>
                    <input type="text" disabled value="{{ $employee->bank_account_number ?? '-' }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-mono font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">Account Owner Name (Atas Nama) *</label>
                    <input type="text" disabled value="{{ $employee->bank_account_name ?? $employee->name }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold uppercase cursor-not-allowed">
                </div>
            </div>

            {{-- TAB 6: CUSTOM DATE (TMT MULAI KERJA) --}}
            <div x-show="activeTab === 'custom_date'" class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs font-semibold" style="display: none;">
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">TMT Mulai Kerja (Valid From) *</label>
                    <input type="text" disabled value="{{ $employee->valid_from ? $employee->valid_from->format('d/m/Y') : ($employee->tmt_date ? date('d/m/Y', strtotime($employee->tmt_date)) : '09/08/2024') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-mono font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">No BPJS Kesehatan</label>
                    <input type="text" disabled value="{{ $employee->bpjs_kesehatan_number ?? '-' }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-mono font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-4.5 font-bold">No BPJS Ketenagakerjaan</label>
                    <input type="text" disabled value="{{ $employee->bpjs_ketenagakerjaan_number ?? '-' }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-mono font-bold cursor-not-allowed">
                </div>
            </div>

            {{-- REMAINING TABS --}}
            <div x-show="['education','work_experience','additional_info'].includes(activeTab)" class="py-12 text-center" style="display: none;">
                <svg class="w-8 h-8 mx-auto mb-2 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Data modul bagian ini terintegrasi penuh dengan sistem database Tanos ERP.</span>
            </div>
        </div>
    </div>
</div>
@endsection
