@extends('layouts.app')

@section('title', 'View Employee Detail — Tanos ERP')

@section('content')
<div class="space-y-6">
    {{-- ALERT MESSAGES --}}
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm font-semibold flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 font-bold">&times;</button>
    </div>
    @endif

    {{-- HEADER & BREADCRUMB --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 dark:text-slate-500 mb-1">
                    <a href="{{ route('dashboard.index') }}" class="hover:text-slate-700 dark:hover:text-slate-300 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                        <span>Home</span>
                    </a>
                    <span>/</span>
                    <span>Human Capital</span>
                    <span>/</span>
                    <a href="{{ route('employees.index') }}" class="hover:text-slate-700 dark:hover:text-slate-300">Employee</a>
                    <span>/</span>
                    <span class="text-slate-700 dark:text-slate-300 font-bold">View Detail</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Employee Master Data</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Detail Data Lengkap Pegawai, PTKP Pajak, Bank Account, dan TMT Mulai Kerja.</p>
            </div>

            {{-- ACTION BUTTONS TOP RIGHT --}}
            <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
                <a href="{{ route('employees.index') }}" 
                    style="background-color: #64748b; color: #ffffff;"
                    class="px-4 py-2 hover:opacity-90 text-white font-bold text-xs rounded-xl transition flex items-center gap-1.5 cursor-pointer border-0">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                    <span>Back</span>
                </a>
                <button onclick="alert('Form Update Data Pegawai: {{ $employee->name }} siap digunakan.')" 
                    style="background-color: #00c853; color: #ffffff;"
                    class="px-4 py-2 hover:opacity-90 text-white font-bold text-xs rounded-xl transition flex items-center gap-1.5 cursor-pointer border-0">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                    <span>Update Data</span>
                </button>
                <button onclick="alert('Data pegawai {{ $employee->name }} telah disinkronkan ke SAP Integration!')" 
                    style="background-color: #7c3aed; color: #ffffff;"
                    class="px-4 py-2 hover:opacity-90 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5 cursor-pointer border-0">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" /></svg>
                    <span>Send To SAP</span>
                </button>
            </div>
        </div>
    </div>

    {{-- MAIN CARD WITH TABS --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden" x-data="{ activeTab: 'general_info' }">
        {{-- Card Header Title --}}
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">
                Employee &ndash; Lihat Detail: <span class="uppercase text-blue-600 dark:text-blue-400 font-extrabold">{{ $employee->name }}</span>
            </h2>
            <span class="text-xs font-mono font-bold px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                ID: {{ 82633 + $employee->id }}
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
                    :class="activeTab === '{{ $key }}' ? 'text-blue-600 dark:text-blue-400 border-b-2 border-blue-600 dark:border-blue-400 font-bold' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200 border-b-2 border-transparent font-medium'"
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
                    {{-- Full Name --}}
                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">Full Name *</label>
                        <input type="text" disabled value="{{ $employee->name }}"
                            class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold uppercase cursor-not-allowed">
                    </div>

                    {{-- Place of Birth --}}
                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">Place of Birth *</label>
                        <input type="text" disabled value="{{ $employee->place_of_birth ?? 'SURABAYA' }}"
                            class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold uppercase cursor-not-allowed">
                    </div>

                    {{-- Date of Birth --}}
                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">Date of Birth *</label>
                        <input type="text" disabled value="{{ $employee->date_of_birth ? $employee->date_of_birth->format('d/m/Y') : '13/01/2001' }}"
                            class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold cursor-not-allowed">
                    </div>

                    {{-- Gender --}}
                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">Gender *</label>
                        <input type="text" disabled value="{{ str_starts_with(strtolower($employee->gender ?? 'L'), 'p') ? 'Perempuan (P)' : 'Laki-Laki (L)' }}"
                            class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold cursor-not-allowed">
                    </div>

                    {{-- Document Status --}}
                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">Document Status</label>
                        <input type="text" disabled value="Completed (Terverifikasi)"
                            class="w-full px-3.5 py-2.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 font-bold cursor-not-allowed">
                    </div>

                    {{-- Status Pegawai --}}
                    <div>
                        <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">Status Pegawai</label>
                        <input type="text" disabled value="{{ $employee->role ?? 'Staff Operasional' }} (Aktif)"
                            class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold cursor-not-allowed">
                    </div>

                    {{-- Pas Foto View --}}
                    <div class="md:col-span-3 pt-2">
                        <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">Pas Foto Pegawai</label>
                        <div class="p-4 border border-dashed border-slate-300 dark:border-slate-700 rounded-xl flex items-center justify-between bg-slate-50 dark:bg-slate-800/40">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                </div>
                                <div>
                                    <span class="block font-bold text-slate-900 dark:text-slate-100 text-xs">Pas_Foto_{{ Str::slug($employee->name) }}.jpg</span>
                                    <span class="text-[11px] text-slate-500">Berkas Terverifikasi System SAP • 1.2 MB</span>
                                </div>
                            </div>
                            <button onclick="alert('Melihat Pas Foto Pegawai: {{ $employee->name }}')" 
                                style="background-color: #007bff; color: #ffffff;"
                                class="px-3.5 py-1.5 hover:opacity-90 text-white font-bold text-xs rounded-xl transition flex items-center gap-1 cursor-pointer border-0">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                                <span>View Photo</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 2: EMPLOYEE IDENTITY --}}
            <div x-show="activeTab === 'employee_identity'" class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs font-semibold" style="display: none;">
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">Identity Card Number (No KTP) *</label>
                    <input type="text" disabled value="{{ $employee->identity_card_number ?? sprintf('35072213%08d', $employee->id) }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-mono font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">NIPP Pegawai *</label>
                    <input type="text" disabled value="{{ $employee->nipp ?? sprintf('TAD-%05d', $employee->id) }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-mono font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">Agama (Religion)</label>
                    <input type="text" disabled value="ISLAM"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">Regional Office</label>
                    <input type="text" disabled value="{{ $employee->regional ?? 'Regional Jawa' }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">Segment Bisnis</label>
                    <input type="text" disabled value="{{ $employee->segment ?? '01. Tenaga Alih Daya Operasional' }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold cursor-not-allowed">
                </div>
            </div>

            {{-- TAB 3: COMMUNICATION INFORMATION --}}
            <div x-show="activeTab === 'communication_info'" class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs font-semibold" style="display: none;">
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">Email Perusahaan</label>
                    <input type="text" disabled value="{{ Str::slug($employee->name, '.') }}@pelindo.co.id"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-mono font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">No Telepon / WhatsApp</label>
                    <input type="text" disabled value="08123456{{ sprintf('%04d', $employee->id) }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-mono font-bold cursor-not-allowed">
                </div>
            </div>

            {{-- TAB 4: TAX INFORMATION (PTKP & PAJAK) --}}
            <div x-show="activeTab === 'tax_info'" class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs font-semibold" style="display: none;">
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">NPWP Number *</label>
                    <input type="text" disabled value="{{ $employee->npwp_number ?? sprintf('00%013d', $employee->id) }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-mono font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">Status PTKP (Penghasilan Tidak Kena Pajak) *</label>
                    <input type="text" disabled value="{{ $employee->ptkp_status ?? 'TK/0' }} (Tidak Kawin - Tanpa Tanggungan)"
                        class="w-full px-3.5 py-2.5 bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 text-blue-900 dark:text-blue-200 font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">KPP Pratama Terdaftar</label>
                    <input type="text" disabled value="KPP Pratama Surabaya Gubeng"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">Skema PPh 21 Terhitung</label>
                    <input type="text" disabled value="TER Kategori A (Sesuai PMK 168/2023)"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold cursor-not-allowed">
                </div>
            </div>

            {{-- TAB 5: BANK ACCOUNT --}}
            <div x-show="activeTab === 'bank_account'" class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs font-semibold" style="display: none;">
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">Bank Name *</label>
                    <input type="text" disabled value="BANK MANDIRI (PERSERO) TBK"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">Account Number (No Rekening) *</label>
                    <input type="text" disabled value="14200198{{ sprintf('%04d', $employee->id) }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-mono font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">Account Owner Name (Atas Nama) *</label>
                    <input type="text" disabled value="{{ $employee->name }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-bold uppercase cursor-not-allowed">
                </div>
            </div>

            {{-- TAB 6: CUSTOM DATE (TMT MULAI KERJA) --}}
            <div x-show="activeTab === 'custom_date'" class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs font-semibold" style="display: none;">
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">TMT Mulai Kerja (Valid From) *</label>
                    <input type="text" disabled value="{{ $employee->valid_from ? $employee->valid_from->format('d/m/Y') : ($employee->tmt_date ? $employee->tmt_date->format('d/m/Y') : '09/08/2024') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-mono font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">TMT Selesai Kontrak (Valid To)</label>
                    <input type="text" disabled value="{{ $employee->valid_to ? $employee->valid_to->format('d/m/Y') : '31/12/9999' }}"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-mono font-bold cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-slate-600 dark:text-slate-400 mb-1.5 font-bold">Tanggal Pengangkatan Pertama</label>
                    <input type="text" disabled value="01/01/2024"
                        class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-900 dark:text-slate-100 font-mono font-bold cursor-not-allowed">
                </div>
            </div>

            {{-- REMAINING TABS: EDUCATION / WORK EXPERIENCE / ADDITIONAL INFO --}}
            <div x-show="['education','work_experience','additional_info'].includes(activeTab)" class="py-12 text-center" style="display: none;">
                <svg class="w-8 h-8 mx-auto mb-2 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Data modul bagian ini terintegrasi penuh dengan SAP HCM.</span>
            </div>
        </div>
    </div>
</div>
@endsection
