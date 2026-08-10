@extends('layouts.app')

@section('title', 'Master Data: Projects — Tanos ERP')

@section('content')
<div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800 shadow-sm w-full">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18M2.25 13.5a2.25 2.25 0 0 0-2.25 2.25v3.75A2.25 2.25 0 0 0 2.25 21.75h19.5a2.25 2.25 0 0 0 2.25-2.25v-3.75a2.25 2.25 0 0 0-2.25-2.25M2.25 13.5V4.5A2.25 2.25 0 0 1 4.5 2.25h15a2.25 2.25 0 0 1 2.25 2.25v9m-18 0J" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Master Data: Projects</h1>
                <p class="text-sm text-slate-400 dark:text-slate-500">Manajemen kontrak, alokasi anggaran, integrasi SAP Cost/Fund Center, dan struktur WBS proyek.</p>
            </div>
        </div>
        @if(in_array(session('user.role'), ['Admin', 'Project Manager', 'Finance Manager']))
        <button onclick="document.getElementById('modal-create-project').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition shrink-0 cursor-pointer">
            + Tambah Project
        </button>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 p-4 bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30 rounded-xl text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Data Table Section -->
    <div class="mt-6 border-t border-slate-100 dark:border-slate-800 pt-6">
        <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <table class="w-full text-left border-collapse min-w-[1350px]">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <th class="p-4 align-middle w-[180px] min-w-[180px]">Kode Proyek (SAP)</th>
                        <th class="p-4 align-middle w-[320px] min-w-[320px]">Nama Proyek / Pelanggan</th>
                        <th class="p-4 align-middle w-[160px] min-w-[160px]">No. Kontrak (OA)</th>
                        <th class="p-4 align-middle w-[220px] min-w-[220px]">Masa Berlaku</th>
                        <th class="p-4 align-middle w-[150px] min-w-[150px]">Segmen & Region</th>
                        <th class="p-4 align-middle w-[140px] min-w-[140px]">SAP Center</th>
                        <th class="p-4 align-middle text-right w-[140px] min-w-[140px]">Cost Budget</th>
                        <th class="p-4 align-middle text-center w-[100px] min-w-[100px]">Status</th>
                        <th class="p-4 align-middle text-center w-[120px] min-w-[120px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-350">
                    @forelse($projects as $item)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                        <td class="p-4 align-middle font-mono font-bold text-blue-650 dark:text-blue-400 text-[13px] whitespace-nowrap">{{ $item->project_code ?? '-' }}</td>
                        <td class="p-4 align-middle w-[320px] min-w-[320px]">
                            <span class="block font-bold text-slate-800 dark:text-slate-100">{{ $item->project_name ?? 'N/A' }}</span>
                            <span class="block text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $item->customer_name ?? '-' }}</span>
                        </td>
                        <td class="p-4 align-middle text-[13px] text-slate-550 dark:text-slate-400 font-semibold whitespace-nowrap">{{ $item->contract_number ?? '-' }}</td>
                        <td class="p-4 align-middle text-xs whitespace-nowrap">
                            <div class="flex items-center space-x-1.5 text-slate-500 dark:text-slate-450">
                                <span class="font-semibold">{{ $item->start_date ? $item->start_date->format('d M Y') : 'N/A' }}</span>
                                <span class="text-slate-300 dark:text-slate-700">-</span>
                                <span class="font-semibold">{{ $item->end_date ? $item->end_date->format('d M Y') : 'N/A' }}</span>
                            </div>
                            <span class="block text-[10px] text-slate-400 dark:text-slate-500 mt-0.5 font-medium">Bulan: {{ $item->month }}</span>
                        </td>
                        <td class="p-4 align-middle text-xs font-semibold whitespace-nowrap">
                            <span class="block text-slate-700 dark:text-slate-300">{{ $item->segment }}</span>
                            <span class="block text-slate-400 dark:text-slate-500 font-normal mt-0.5">{{ $item->regional }}</span>
                        </td>
                        <td class="p-4 align-middle text-[11px] font-mono whitespace-nowrap">
                            <span class="block text-slate-500 dark:text-slate-450"><span class="font-bold text-slate-400/80">CC:</span> {{ $item->cost_center ?? '-' }}</span>
                            <span class="block text-slate-500 dark:text-slate-450 mt-0.5"><span class="font-bold text-slate-400/80">FC:</span> {{ $item->fund_center ?? '-' }}</span>
                        </td>
                        <td class="p-4 align-middle text-right font-bold text-slate-900 dark:text-slate-100 whitespace-nowrap">
                            Rp {{ number_format($item->cost, 0, ',', '.') }}
                        </td>
                        <td class="p-4 align-middle text-center whitespace-nowrap">
                            <span class="inline-block px-2.5 py-1 {{ $item->active == 1 ? 'bg-emerald-55/10 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400' : 'bg-rose-55/10 text-rose-600 dark:bg-rose-950/30 dark:text-rose-400' }} rounded-md text-xs font-bold">
                                {{ $item->active == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="p-4 align-middle text-center whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-1.5">
                                <a href="/dashboard/projects/{{ $item->id }}/wbs" class="p-1.5 text-indigo-650 dark:text-indigo-400 hover:text-indigo-855 dark:hover:text-indigo-300 bg-indigo-50 dark:bg-indigo-950/30 rounded-lg transition" title="Kelola WBS">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                        <rect x="3" y="3" width="6" height="6" rx="1.5" />
                                        <rect x="3" y="15" width="6" height="6" rx="1.5" />
                                        <rect x="15" y="9" width="6" height="6" rx="1.5" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 6h3v12H9M12 12h3" />
                                    </svg>
                                </a>
                                @if(in_array(session('user.role'), ['Admin', 'Project Manager', 'Finance Manager']))
                                <button onclick="openEditModal({{ json_encode($item) }})" class="p-1.5 text-blue-650 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 bg-blue-50 dark:bg-blue-950/30 rounded-lg transition cursor-pointer" title="Ubah Proyek">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                                </button>
                                <form action="/dashboard/projects/{{ $item->id }}" method="POST" onsubmit="return confirm('Yakin hapus project ini?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-650 dark:text-red-400 hover:text-red-855 dark:hover:text-red-300 bg-red-50 dark:bg-red-950/30 rounded-lg transition cursor-pointer" title="Hapus Proyek">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m12 6c0 1.66-1.34 3-3 3H6c-1.34 0-3-1.34-3-3V7h18v8Z" /></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-12 text-center">
                            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/10 border border-slate-200 dark:border-slate-800 p-6 max-w-md mx-auto">
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Data project belum ditambahkan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div id="modal-create-project" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full p-6 shadow-xl border border-slate-100 dark:border-slate-800">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-150 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Tambah Data Project</h3>
            <button onclick="document.getElementById('modal-create-project').classList.add('hidden')" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form action="/dashboard/projects" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kolom Kiri: Detail Proyek & Pelanggan -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Detail Kontrak & Proyek</h4>
                    
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Kode Proyek / SAP</label>
                        <input type="text" name="project_code" required placeholder="Contoh: S/PS-2026-01-0001" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Nama Proyek</label>
                        <input type="text" name="project_name" required placeholder="Contoh: Jasa TAD Terminal Belawan" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Nama Pelanggan (Customer)</label>
                        <input type="text" name="customer_name" required placeholder="Contoh: PT Pelindo Multi Terminal" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Nomor Kontrak (OA)</label>
                        <input type="text" name="contract_number" required placeholder="Contoh: OA-2026-0819" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Tanggal Selesai</label>
                            <input type="date" name="end_date" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Finansial & SAP Integration -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Integrasi SAP & Anggaran</h4>
                    
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Bulan Pengikatan</label>
                        <select name="month" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                            <option value="">-- Pilih Bulan --</option>
                            @foreach($months as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Regional</label>
                            <select name="regional" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                                <option value="">-- Pilih Regional --</option>
                                @foreach($regionals as $reg)
                                    <option value="{{ $reg->name }}">{{ $reg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Segment</label>
                            <select name="segment" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                                <option value="">-- Pilih Segment --</option>
                                @foreach($segments as $seg)
                                    <option value="{{ $seg->name }}">{{ $seg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Cost Center SAP</label>
                            <input type="text" name="cost_center" required placeholder="Contoh: CC1001" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Fund Center SAP</label>
                            <input type="text" name="fund_center" required placeholder="Contoh: FC1001" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Cost Budget (IDR)</label>
                        <input type="number" name="cost" required placeholder="Nominal Anggaran" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Status Proyek</label>
                        <select name="active" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-2 pt-4 border-t border-slate-150 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modal-create-project').classList.add('hidden')" class="px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm cursor-pointer">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT -->
<div id="modal-edit-project" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full p-6 shadow-xl border border-slate-100 dark:border-slate-800">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-150 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Edit Data Project</h3>
            <button onclick="document.getElementById('modal-edit-project').classList.add('hidden')" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form id="form-edit-project" action="" method="POST" class="space-y-4">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kolom Kiri: Detail Proyek & Pelanggan -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Detail Kontrak & Proyek</h4>
                    
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Kode Proyek / SAP</label>
                        <input type="text" id="edit-project-code" name="project_code" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Nama Proyek</label>
                        <input type="text" id="edit-project-name" name="project_name" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Nama Pelanggan (Customer)</label>
                        <input type="text" id="edit-customer-name" name="customer_name" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Nomor Kontrak (OA)</label>
                        <input type="text" id="edit-contract-number" name="contract_number" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Tanggal Mulai</label>
                            <input type="date" id="edit-start-date" name="start_date" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Tanggal Selesai</label>
                            <input type="date" id="edit-end-date" name="end_date" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Finansial & SAP Integration -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Integrasi SAP & Anggaran</h4>
                    
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Bulan Pengikatan</label>
                        <select id="edit-month" name="month" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                            @foreach($months as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Regional</label>
                            <select id="edit-regional" name="regional" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                                @foreach($regionals as $reg)
                                    <option value="{{ $reg->name }}">{{ $reg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Segment</label>
                            <select id="edit-segment" name="segment" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                                @foreach($segments as $seg)
                                    <option value="{{ $seg->name }}">{{ $seg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Cost Center SAP</label>
                            <input type="text" id="edit-cost-center" name="cost_center" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Fund Center SAP</label>
                            <input type="text" id="edit-fund-center" name="fund_center" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Cost Budget (IDR)</label>
                        <input type="number" id="edit-cost" name="cost" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Status Proyek</label>
                        <select id="edit-active" name="active" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-2 pt-4 border-t border-slate-150 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modal-edit-project').classList.add('hidden')" class="px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm cursor-pointer">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(project) {
        const modal = document.getElementById('modal-edit-project');
        const form = document.getElementById('form-edit-project');
        form.action = `/dashboard/projects/${project.id}`;
        
        document.getElementById('edit-project-code').value = project.project_code || '';
        document.getElementById('edit-project-name').value = project.project_name || '';
        document.getElementById('edit-customer-name').value = project.customer_name || '';
        document.getElementById('edit-contract-number').value = project.contract_number || '';
        
        // Handle dates formatting (YYYY-MM-DD)
        let startDateStr = '';
        if (project.start_date) {
            startDateStr = project.start_date.substring(0, 10);
        }
        let endDateStr = '';
        if (project.end_date) {
            endDateStr = project.end_date.substring(0, 10);
        }
        
        document.getElementById('edit-start-date').value = startDateStr;
        document.getElementById('edit-end-date').value = endDateStr;
        
        document.getElementById('edit-cost-center').value = project.cost_center || '';
        document.getElementById('edit-fund-center').value = project.fund_center || '';
        document.getElementById('edit-month').value = project.month || '';
        document.getElementById('edit-regional').value = project.regional || '';
        document.getElementById('edit-segment').value = project.segment || '';
        document.getElementById('edit-cost').value = project.cost || '';
        document.getElementById('edit-active').value = project.active !== undefined ? (project.active ? '1' : '0') : '1';
        
        modal.classList.remove('hidden');
    }
</script>
@endsection
