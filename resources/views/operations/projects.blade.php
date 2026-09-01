@extends('layouts.app')

@section('title', 'Master Data: Projects — Tanos ERP')

@section('content')
<div class="space-y-6 w-full">
    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 dark:text-slate-400 mb-4.5">
                <a href="{{ route('dashboard.index') }}" class="hover:text-primary dark:hover:text-sky-400 transition flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Home
                </a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span>Project System</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-primary dark:text-sky-400 font-black">Master Projects</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                Master Data: Projects
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 font-medium">Manajemen proyek, alokasi Cost Center & Fund Center untuk pencatatan Expense, Revenue, & Budgeting.</p>
        </div>

        @if(in_array(session('user.role'), ['Admin', 'Project Manager', 'Finance Manager']))
        <button onclick="document.getElementById('modal-create-project').classList.remove('hidden')" 
            class="px-4 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center space-x-1.5 self-start sm:self-auto cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tambah Project</span>
        </button>
        @endif
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30 rounded-xl text-sm">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Data Table Card Section -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1350px]">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <th class="p-4 align-middle w-[180px] min-w-[180px]">Kode Proyek (SAP)</th>
                        <th class="p-4 align-middle w-[320px] min-w-[320px]">Nama Proyek / Pelanggan</th>
                        <th class="p-4 align-middle w-[160px] min-w-[160px]">No. Kontrak (OA)</th>
                        <th class="p-4 align-middle w-[220px] min-w-[220px]">Masa Berlaku</th>
                        <th class="p-4 align-middle w-[150px] min-w-[150px]">Segmen & Region</th>
                        <th class="p-4 align-middle w-[180px] min-w-[180px]">Cost & Fund Center SAP</th>
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
                            <span class="block text-slate-700 dark:text-slate-200"><span class="font-bold text-blue-600 dark:text-blue-400">CC:</span> {{ $item->cost_center ?? ('CC-' . strtoupper(str_replace([' ', '-'], '', $item->project_code))) }}</span>
                            <span class="block text-slate-700 dark:text-slate-200 mt-0.5"><span class="font-bold text-purple-600 dark:text-purple-400">FC:</span> {{ $item->fund_center ?? ('FC-' . strtoupper(str_replace([' ', '-'], '', $item->project_code))) }}</span>
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
                                <button onclick="openEditModal({{ json_encode($item) }})" class="p-1.5 text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 bg-emerald-50 dark:bg-emerald-950/30 rounded-lg transition" title="Edit Proyek">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                                </button>
                                <form action="/dashboard/projects/{{ $item->id }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus proyek ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-rose-600 dark:text-rose-400 hover:text-rose-800 bg-rose-50 dark:bg-rose-950/30 rounded-lg transition" title="Hapus Proyek">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-8 text-center text-slate-400">Belum ada data proyek.</td>
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

<!-- MODAL CREATE PROJECT -->
<div id="modal-create-project" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full p-6 shadow-xl border border-slate-100 dark:border-slate-800">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-150 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">+ Tambah Data Project (Entry Project)</h3>
            <button onclick="document.getElementById('modal-create-project').classList.add('hidden')" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form action="{{ route('projects.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kolom Kiri: Detail Proyek & Pelanggan -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Detail Kontrak & Proyek</h4>
                    
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Kode Proyek / SAP *</label>
                        <input type="text" name="project_code" required placeholder="Contoh: S/PS-2024-04-00062" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 font-mono">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Nama Proyek *</label>
                        <input type="text" name="project_name" required placeholder="Contoh: PBR - BJTI - PENGAMANAN SURABAYA" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 uppercase">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Nama Pelanggan (Customer) *</label>
                        <input type="text" name="customer_name" required placeholder="Contoh: PT BERLIAN JASA TERMINAL INDONESIA" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 uppercase">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Nomor Kontrak (OA) *</label>
                        <input type="text" name="contract_number" required placeholder="Contoh: OA-2024-0098" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Tanggal Mulai *</label>
                            <input type="date" name="start_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Tanggal Selesai *</label>
                            <input type="date" name="end_date" value="{{ date('Y-12-31') }}" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Finansial & SAP Integration -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Integrasi SAP & Anggaran</h4>
                    
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Bulan Pengikatan *</label>
                        <select name="month" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                            @foreach($months as $m)
                                <option value="{{ $m }}">{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Regional *</label>
                            <select name="regional" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                                <option value="">-- Pilih Regional --</option>
                                @foreach($regionals as $reg)
                                    <option value="{{ $reg->name }}">{{ $reg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Segment *</label>
                            <select name="segment" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                                <option value="">-- Pilih Segment --</option>
                                @foreach($segments as $seg)
                                    <option value="{{ $seg->name }}">{{ $seg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Cost Center & Fund Center Auto-Generation Container --}}
                    <div class="p-3 bg-blue-50/70 dark:bg-blue-950/40 border border-blue-200/80 dark:border-blue-800/70 rounded-xl space-y-2">
                        <div class="flex items-center gap-1.5 text-[11px] font-bold text-blue-900 dark:text-blue-200">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 10.5 10.875v-1.5a3.375 3.375 0 0 0-3.375-3.375H4.5" /></svg>
                            <span>Otomatis Membuat Wadah SAP:</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-[11px]">
                            <div>
                                <label class="block font-bold text-slate-600 dark:text-slate-400 mb-1">Cost Center SAP</label>
                                <input type="text" name="cost_center" placeholder="Otomatis: CC-[KODE]" class="w-full px-3 py-1.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-lg text-xs font-mono focus:outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block font-bold text-slate-600 dark:text-slate-400 mb-1">Fund Center SAP</label>
                                <input type="text" name="fund_center" placeholder="Otomatis: FC-[KODE]" class="w-full px-3 py-1.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-lg text-xs font-mono focus:outline-none focus:border-blue-500">
                            </div>
                        </div>
                        <p class="text-[10px] text-blue-700 dark:text-blue-300 font-medium">Bila dikosongkan, sistem otomatis menerbitkan Cost Center & Fund Center sebagai wadah Expense, Revenue, & Budgeting proyek.</p>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Cost Budget (IDR) *</label>
                        <input type="number" name="cost" required placeholder="Contoh: 500000000" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Status Proyek *</label>
                        <select name="active" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-2 pt-4 border-t border-slate-150 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modal-create-project').classList.add('hidden')" class="px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 cursor-pointer">Batal</button>
                <button type="submit" style="background-color: #007bff; color: #ffffff;" class="px-4 py-2 hover:opacity-90 text-white rounded-xl text-sm font-semibold shadow-sm cursor-pointer border-0">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT PROJECT -->
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
                <!-- Kolom Kiri -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Detail Kontrak & Proyek</h4>
                    
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Kode Proyek / SAP</label>
                        <input type="text" id="edit-project-code" name="project_code" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 font-mono">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Nama Proyek</label>
                        <input type="text" id="edit-project-name" name="project_name" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 uppercase">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Nama Pelanggan (Customer)</label>
                        <input type="text" id="edit-customer-name" name="customer_name" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 uppercase">
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

                <!-- Kolom Kanan -->
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
                            <input type="text" id="edit-cost-center" name="cost_center" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 font-mono">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-1">Fund Center SAP</label>
                            <input type="text" id="edit-fund-center" name="fund_center" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 font-mono">
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
                <button type="submit" style="background-color: #007bff; color: #ffffff;" class="px-4 py-2 hover:opacity-90 text-white rounded-xl text-sm font-semibold shadow-sm cursor-pointer border-0">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(project) {
    document.getElementById('form-edit-project').action = '/dashboard/projects/' + project.id;
    document.getElementById('edit-project-code').value = project.project_code || '';
    document.getElementById('edit-project-name').value = project.project_name || '';
    document.getElementById('edit-customer-name').value = project.customer_name || '';
    document.getElementById('edit-contract-number').value = project.contract_number || '';
    
    if (project.start_date) {
        document.getElementById('edit-start-date').value = project.start_date.substring(0, 10);
    }
    if (project.end_date) {
        document.getElementById('edit-end-date').value = project.end_date.substring(0, 10);
    }

    document.getElementById('edit-month').value = project.month || '';
    document.getElementById('edit-regional').value = project.regional || '';
    document.getElementById('edit-segment').value = project.segment || '';
    document.getElementById('edit-cost-center').value = project.cost_center || '';
    document.getElementById('edit-fund-center').value = project.fund_center || '';
    document.getElementById('edit-cost').value = project.cost || 0;
    document.getElementById('edit-active').value = project.active ? 1 : 0;

    document.getElementById('modal-edit-project').classList.remove('hidden');
}
</script>
@endsection
