@extends('layouts.app')

@section('title', 'Master Data: Employees — Tanos ERP')

@section('content')
<div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm w-full">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Master Data: Employees</h1>
                <p class="text-sm text-slate-400 dark:text-slate-505">Manajemen kepegawaian internal perusahaan.</p>
            </div>
        </div>
        <!-- Tombol Tambah Data -->
        <button onclick="document.getElementById('modal-create-employee').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium shadow-sm transition shrink-0 cursor-pointer">
            + Tambah Data
        </button>
    </div>

    <!-- Alert Sukses Bawaan Laravel -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-sm animate-pulse">
            {{ session('success') }}
        </div>
    @endif

    <!-- Data Table Section -->
    <div class="mt-6 border-t border-slate-100 dark:border-slate-800 pt-6">
        <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <th class="p-4">Nama Pegawai & NIPP</th>
                        <th class="p-4">Jabatan / Role</th>
                        <th class="p-4">Bulan</th>
                        <th class="p-4">Regional & Sub-Area</th>
                        <th class="p-4">Segment</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-300">
                    @forelse($employees as $item)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                        <td class="p-4">
                            <div class="font-bold text-slate-800 dark:text-slate-100">{{ $item->name ?? 'No Name' }}</div>
                            <div class="text-xs text-slate-400 dark:text-slate-500 font-semibold mt-0.5 tracking-wider">{{ $item->nipp ?? 'N/A' }}</div>
                        </td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 rounded-md text-xs font-medium text-slate-700 dark:text-slate-300">
                                {{ $item->role ?? 'Staff Member' }}
                            </span>
                        </td>
                        <td class="p-4 text-slate-500 dark:text-slate-400 font-medium">{{ $item->month }}</td>
                        <td class="p-4 text-slate-500 dark:text-slate-400">
                            <span class="font-medium">{{ $item->regional }}</span>
                            @if(!empty($item->sub_regional))
                                <span class="ml-1 px-2 py-0.5 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 rounded-md text-xs font-medium">
                                    {{ $item->sub_regional }}
                                </span>
                            @endif
                        </td>
                        <td class="p-4 text-slate-500 dark:text-slate-400 font-medium">{{ $item->segment }}</td>
                        <td class="p-4 flex items-center justify-center space-x-2">
                            <!-- Tombol Detail -->
                            <button onclick="openDetailModal({{ json_encode($item) }})" class="p-1.5 text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/30 rounded-lg transition cursor-pointer" title="Detail Karyawan">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>
                            <!-- Tombol Edit -->
                            <button onclick="openEditModal({{ json_encode($item) }})" class="p-1.5 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 bg-blue-50 dark:bg-blue-950/30 rounded-lg transition cursor-pointer" title="Edit Karyawan">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                            </button>
                            <!-- Tombol Hapus -->
                            <form action="{{ route('employees.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus data ini?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 bg-red-50 dark:bg-red-950/30 rounded-lg transition cursor-pointer" title="Hapus Karyawan">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m12 6c0 1.66-1.34 3-3 3H6c-1.34 0-3-1.34-3-3V7h18v8Z" /></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center">
                            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/10 border border-slate-200 dark:border-slate-800 p-6 max-w-md mx-auto">
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Data manajemen karyawan belum ditambahkan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $employees->links() }}
        </div>
    </div>
</div>

<!-- ================= MODAL DETAIL FILE KARYAWAN ================= -->
<div id="modal-detail-employee" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full p-6 shadow-xl border border-slate-100 dark:border-slate-800">
        <div class="flex items-center justify-between mb-5 border-b border-slate-100 dark:border-slate-800 pb-3">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">
                    <span id="detail-initials">TU</span>
                </div>
                <div>
                    <h3 id="detail-name" class="text-lg font-bold text-slate-800 dark:text-slate-100">Nama Lengkap</h3>
                    <p id="detail-nipp" class="text-xs text-slate-400 dark:text-slate-500 font-semibold tracking-wider">NIPP-123456</p>
                </div>
            </div>
            <button onclick="document.getElementById('modal-detail-employee').classList.add('hidden')" class="text-slate-400 dark:text-slate-505 hover:text-slate-600 dark:hover:text-slate-300 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-slate-600 dark:text-slate-300">
            <!-- Informasi Kepegawaian -->
            <div class="space-y-3.5">
                <h4 class="font-bold text-slate-700 dark:text-slate-200 border-l-2 border-blue-500 pl-2">Informasi Kepegawaian</h4>
                <div class="bg-slate-50 dark:bg-slate-800/40 rounded-xl p-3.5 space-y-2.5">
                    <div class="flex justify-between"><span class="text-slate-400">Jabatan:</span><span id="detail-role" class="font-semibold text-slate-800 dark:text-slate-200">Staff</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Bulan Proyek:</span><span id="detail-month" class="font-semibold">Juni 2025</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Regional:</span><span id="detail-regional" class="font-semibold">Regional 1</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Sub-Regional:</span><span id="detail-sub-regional" class="font-semibold">Area A</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Segment:</span><span id="detail-segment" class="font-semibold">Enterprise</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">TMT:</span><span id="detail-tmt" class="font-semibold text-blue-600 dark:text-blue-400">01-01-2023</span></div>
                </div>
            </div>

            <!-- Detail Finansial & Jaminan Sosial -->
            <div class="space-y-3.5">
                <h4 class="font-bold text-slate-700 dark:text-slate-200 border-l-2 border-emerald-500 pl-2">Detail Payroll & Pajak</h4>
                <div class="bg-slate-50 dark:bg-slate-800/40 rounded-xl p-3.5 space-y-2.5">
                    <div class="flex justify-between"><span class="text-slate-400">PTKP Pajak:</span><span id="detail-ptkp" class="font-semibold text-slate-800 dark:text-slate-200 bg-emerald-100 dark:bg-emerald-950/40 px-2 py-0.5 rounded text-xs">TK/0</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Bank:</span><span id="detail-bank-name" class="font-semibold">Bank Mandiri</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">No. Rekening:</span><span id="detail-bank-number" class="font-semibold tracking-wider">1234567890</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Atas Nama:</span><span id="detail-bank-holder" class="font-semibold truncate max-w-[150px]">Atas Nama</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">BPJS Kes:</span><span id="detail-bpjs-kes" class="font-semibold">1234567</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">BPJS TK:</span><span id="detail-bpjs-tk" class="font-semibold">1234567</span></div>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-5 mt-5 border-t border-slate-100 dark:border-slate-800">
            <button onclick="document.getElementById('modal-detail-employee').classList.add('hidden')" class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-sm font-semibold transition cursor-pointer">Tutup Profile</button>
        </div>
    </div>
</div>

<!-- ================= MODAL TAMBAH DATA (2-Column Premium Grid) ================= -->
<div id="modal-create-employee" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full p-6 shadow-xl border border-slate-100 dark:border-slate-800">
        <div class="flex items-center justify-between mb-4 border-b border-slate-100 dark:border-slate-800 pb-3">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Tambah Data Pegawai</h3>
            <button onclick="document.getElementById('modal-create-employee').classList.add('hidden')" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form action="{{ route('employees.store') }}" method="POST" class="space-y-5">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kolom Kiri: Info Utama -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-2">1. Data Kepegawaian</h4>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Nama Pegawai</label>
                        <input type="text" name="name" required placeholder="Nama lengkap pegawai" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">NIPP / Perner ID</label>
                        <input type="text" name="nipp" required placeholder="Contoh: NIPP-123456" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Jabatan / Role</label>
                        <input type="text" name="role" required placeholder="Contoh: Manager, Developer, Staff" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Tanggal TMT</label>
                        <input type="date" name="tmt_date" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Bulan</label>
                            <select name="month" required class="w-full px-2 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                                @foreach($months as $m)
                                    <option value="{{ $m }}">{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Segment</label>
                            <select name="segment" required class="w-full px-2 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                                @foreach($segments as $seg)
                                    <option value="{{ $seg->name }}">{{ $seg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Regional</label>
                            <select name="regional" required class="w-full px-2 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                                @foreach($regionals as $reg)
                                    <option value="{{ $reg->name }}">{{ $reg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Sub-Area</label>
                            <select name="sub_regional" class="w-full px-2 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                                <option value="">Opsional</option>
                                @foreach($subRegionals as $sub)
                                    <option value="{{ $sub->name }}">{{ $sub->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Finansial & BPJS -->
                <div class="space-y-4 border-l border-slate-100 dark:border-slate-800 pl-4">
                    <h4 class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mb-2">2. Payroll, Pajak, & BPJS</h4>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Kategori PTKP Pajak</label>
                        <select name="ptkp_status" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                            <option value="TK/0">TK/0 (Belum Menikah, 0 Tanggungan)</option>
                            <option value="TK/1">TK/1 (Belum Menikah, 1 Tanggungan)</option>
                            <option value="K/0">K/0 (Menikah, 0 Tanggungan)</option>
                            <option value="K/1">K/1 (Menikah, 1 Tanggungan)</option>
                            <option value="K/2">K/2 (Menikah, 2 Tanggungan)</option>
                            <option value="K/3">K/3 (Menikah, 3 Tanggungan)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Nama Bank Transfer</label>
                        <input type="text" name="bank_name" placeholder="Contoh: Bank Mandiri" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Nomor Rekening</label>
                        <input type="text" name="bank_account_number" placeholder="Nomor rekening bank" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Atas Nama Rekening</label>
                        <input type="text" name="bank_account_name" placeholder="Nama pemilik rekening" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">No. BPJS Kesehatan</label>
                            <input type="text" name="bpjs_kesehatan_number" placeholder="Nomor BPJS Kes" class="w-full px-2 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">No. BPJS TK</label>
                            <input type="text" name="bpjs_ketenagakerjaan_number" placeholder="Nomor BPJS TK" class="w-full px-2 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modal-create-employee').classList.add('hidden')" class="px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium shadow-sm cursor-pointer">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL EDIT DATA (2-Column Premium Grid) ================= -->
<div id="modal-edit-employee" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full p-6 shadow-xl border border-slate-100 dark:border-slate-800">
        <div class="flex items-center justify-between mb-4 border-b border-slate-100 dark:border-slate-800 pb-3">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Edit Data Pegawai</h3>
            <button onclick="document.getElementById('modal-edit-employee').classList.add('hidden')" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form id="form-edit-employee" method="POST" class="space-y-5">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kolom Kiri: Info Utama -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-2">1. Data Kepegawaian</h4>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Nama Pegawai</label>
                        <input type="text" id="edit-name" name="name" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">NIPP / Perner ID</label>
                        <input type="text" id="edit-nipp" name="nipp" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Jabatan / Role</label>
                        <input type="text" id="edit-role" name="role" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Tanggal TMT</label>
                        <input type="date" id="edit-tmt-date" name="tmt_date" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Bulan</label>
                            <select id="edit-month" name="month" required class="w-full px-2 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                                @foreach($months as $m)
                                    <option value="{{ $m }}">{{ $m }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Segment</label>
                            <select id="edit-segment" name="segment" required class="w-full px-2 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                                @foreach($segments as $seg)
                                    <option value="{{ $seg->name }}">{{ $seg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Regional</label>
                            <select id="edit-regional" name="regional" required class="w-full px-2 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                                @foreach($regionals as $reg)
                                    <option value="{{ $reg->name }}">{{ $reg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Sub-Area</label>
                            <select id="edit-sub-regional" name="sub_regional" class="w-full px-2 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                                <option value="">Opsional</option>
                                @foreach($subRegionals as $sub)
                                    <option value="{{ $sub->name }}">{{ $sub->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Finansial & BPJS -->
                <div class="space-y-4 border-l border-slate-100 dark:border-slate-800 pl-4">
                    <h4 class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mb-2">2. Payroll, Pajak, & BPJS</h4>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Kategori PTKP Pajak</label>
                        <select id="edit-ptkp-status" name="ptkp_status" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                            <option value="TK/0">TK/0 (Belum Menikah, 0 Tanggungan)</option>
                            <option value="TK/1">TK/1 (Belum Menikah, 1 Tanggungan)</option>
                            <option value="K/0">K/0 (Menikah, 0 Tanggungan)</option>
                            <option value="K/1">K/1 (Menikah, 1 Tanggungan)</option>
                            <option value="K/2">K/2 (Menikah, 2 Tanggungan)</option>
                            <option value="K/3">K/3 (Menikah, 3 Tanggungan)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Nama Bank Transfer</label>
                        <input type="text" id="edit-bank-name" name="bank_name" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Nomor Rekening</label>
                        <input type="text" id="edit-bank-account-number" name="bank_account_number" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Atas Nama Rekening</label>
                        <input type="text" id="edit-bank-account-name" name="bank_account_name" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">No. BPJS Kesehatan</label>
                            <input type="text" id="edit-bpjs-kesehatan-number" name="bpjs_kesehatan_number" class="w-full px-2 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">No. BPJS TK</label>
                            <input type="text" id="edit-bpjs-ketenagakerjaan-number" name="bpjs_ketenagakerjaan_number" class="w-full px-2 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modal-edit-employee').classList.add('hidden')" class="px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium shadow-sm cursor-pointer">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Buka Modal Detail Profile
    function openDetailModal(employee) {
        // Tentukan inisial
        let initials = 'TU';
        if (employee.name) {
            let parts = employee.name.split(' ');
            if (parts.length >= 2) {
                initials = (parts[0][0] + parts[1][0]).toUpperCase();
            } else {
                initials = parts[0].substring(0, 2).toUpperCase();
            }
        }
        document.getElementById('detail-initials').innerText = initials;
        document.getElementById('detail-name').innerText = employee.name || 'No Name';
        document.getElementById('detail-nipp').innerText = employee.nipp || 'Belum diatur';
        document.getElementById('detail-role').innerText = employee.role || 'Staff';
        document.getElementById('detail-month').innerText = employee.month || '-';
        document.getElementById('detail-regional').innerText = employee.regional || '-';
        document.getElementById('detail-sub-regional').innerText = employee.sub_regional || 'Tidak ada';
        document.getElementById('detail-segment').innerText = employee.segment || '-';
        
        // Format Tanggal TMT
        if (employee.tmt_date) {
            let tmt = new Date(employee.tmt_date);
            document.getElementById('detail-tmt').innerText = tmt.toLocaleDateString('id-ID', {
                day: '2-digit', month: 'long', year: 'numeric'
            });
        } else {
            document.getElementById('detail-tmt').innerText = 'Belum diatur';
        }

        document.getElementById('detail-ptkp').innerText = employee.ptkp_status || 'TK/0';
        document.getElementById('detail-bank-name').innerText = employee.bank_name || 'Belum diatur';
        document.getElementById('detail-bank-number').innerText = employee.bank_account_number || 'Belum diatur';
        document.getElementById('detail-bank-holder').innerText = employee.bank_account_name || 'Belum diatur';
        document.getElementById('detail-bank-holder').title = employee.bank_account_name || '';
        document.getElementById('detail-bpjs-kes').innerText = employee.bpjs_kesehatan_number || 'Belum terdaftar';
        document.getElementById('detail-bpjs-tk').innerText = employee.bpjs_ketenagakerjaan_number || 'Belum terdaftar';

        document.getElementById('modal-detail-employee').classList.remove('hidden');
    }

    // Buka Modal Edit Karyawan
    function openEditModal(employee) {
        const modal = document.getElementById('modal-edit-employee');
        const form = document.getElementById('form-edit-employee');
        form.action = `/dashboard/employees/${employee.id}`;
        
        document.getElementById('edit-name').value = employee.name || '';
        document.getElementById('edit-nipp').value = employee.nipp || '';
        document.getElementById('edit-role').value = employee.role || '';
        
        // Format TMT date ke input type="date" (YYYY-MM-DD)
        if (employee.tmt_date) {
            let rawDate = employee.tmt_date.split('T')[0]; // jika timestamp ISO
            document.getElementById('edit-tmt-date').value = rawDate;
        } else {
            document.getElementById('edit-tmt-date').value = '';
        }
        
        document.getElementById('edit-month').value = employee.month || '';
        document.getElementById('edit-regional').value = employee.regional || '';
        document.getElementById('edit-sub-regional').value = employee.sub_regional || '';
        document.getElementById('edit-segment').value = employee.segment || '';
        document.getElementById('edit-ptkp-status').value = employee.ptkp_status || 'TK/0';
        document.getElementById('edit-bank-name').value = employee.bank_name || '';
        document.getElementById('edit-bank-account-number').value = employee.bank_account_number || '';
        document.getElementById('edit-bank-account-name').value = employee.bank_account_name || '';
        document.getElementById('edit-bpjs-kesehatan-number').value = employee.bpjs_kesehatan_number || '';
        document.getElementById('edit-bpjs-ketenagakerjaan-number').value = employee.bpjs_ketenagakerjaan_number || '';
        
        modal.classList.remove('hidden');
    }
</script>
@endsection
