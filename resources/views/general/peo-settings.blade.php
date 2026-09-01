@extends('layouts.app')

@section('title', 'Mapping PEO Setting — Tanos ERP')

@section('content')
<div class="space-y-6">
    {{-- ALERT MESSAGES --}}
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs font-semibold flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-200 font-bold text-base leading-none">&times;</button>
    </div>
    @endif

    {{-- HEADER & BREADCRUMB --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 dark:text-slate-400 mb-4.5">
                <a href="{{ route('dashboard.index') }}" class="hover:text-primary dark:hover:text-sky-400 transition flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Home
                </a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span>General</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span>Setting</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-primary dark:text-sky-400 font-black">PEO Setting</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                Mapping PEO Setting
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 font-medium">Pengelolaan Mapping Integrasi Dokumen PEO</p>
        </div>

        {{-- ACTION BUTTONS TOP RIGHT --}}
        <div class="flex items-center gap-2.5 shrink-0 flex-wrap self-start md:self-auto">
            <button onclick="document.getElementById('modal-create-peo').classList.remove('hidden')" 
                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center space-x-1.5 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Create New</span>
            </button>
            <button onclick="alert('Dokumen Header Setting telah dikonfigurasi.')" 
                class="px-4 py-2 bg-primary hover:bg-primary-hover text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center space-x-1.5 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                <span>Document Header Setting</span>
            </button>
        </div>
    </div>

    {{-- MAIN CONTENT CARD --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
        {{-- Card Header Title --}}
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Pengelolaan Mapping Integrasi Dokumen PEO - List</h2>
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ $peoSettings->total() }} total konfigurasi</span>
        </div>

        {{-- TABS NAV --}}
        <div class="px-6 pt-4 border-b border-slate-200/80 dark:border-slate-800 flex items-center gap-6 bg-white dark:bg-slate-900">
            <a href="{{ route('peo.index', ['tab' => 'Berita Acara', 'per_page' => $perPage, 'search' => $search]) }}" 
                class="pb-3 text-xs font-bold transition-all border-b-2 {{ $activeTab === 'Berita Acara' ? 'border-blue-600 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">
                Berita Acara
            </a>
            <a href="{{ route('peo.index', ['tab' => 'Surat Keluar', 'per_page' => $perPage, 'search' => $search]) }}" 
                class="pb-3 text-xs font-bold transition-all border-b-2 {{ $activeTab === 'Surat Keluar' ? 'border-blue-600 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">
                Surat Keluar
            </a>
        </div>

        {{-- TOOLBAR --}}
        <div class="p-6 flex flex-col md:flex-row items-center justify-between gap-4 bg-white dark:bg-slate-900">
            {{-- Export Toolbar Icons --}}
            <div class="flex items-center gap-2">
                <button onclick="exportToCSV()" title="Export CSV / Document" style="background-color: #e9ecef;" class="p-2.5 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-xl transition cursor-pointer">
                    <svg class="w-4 h-4 text-slate-700 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                </button>
                <button onclick="window.print()" title="Export PDF" style="background-color: #e9ecef;" class="p-2.5 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-rose-600 dark:text-rose-400 rounded-xl transition cursor-pointer">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.656" /></svg>
                </button>
                <button onclick="exportToCSV()" title="Export Excel" style="background-color: #e9ecef;" class="p-2.5 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-emerald-600 dark:text-emerald-400 rounded-xl transition cursor-pointer">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25-6h17.25m-17.25-6h17.25" /></svg>
                </button>
                <button onclick="window.location.reload()" title="Refresh Data" style="background-color: #e9ecef;" class="p-2.5 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 text-blue-600 dark:text-blue-400 rounded-xl transition cursor-pointer">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                </button>
            </div>

            {{-- Controls: Per Page & Search --}}
            <div class="flex items-center gap-4 w-full md:w-auto">
                {{-- Per Page --}}
                <form action="{{ route('peo.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="tab" value="{{ $activeTab }}">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <select name="per_page" onchange="this.form.submit()" 
                        class="px-3.5 py-2 text-xs font-semibold border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                        @foreach([10, 25, 50, 100] as $n)
                        <option value="{{ $n }}" {{ $perPage == $n ? 'selected' : '' }}>{{ $n }}</option>
                        @endforeach
                    </select>
                </form>

                {{-- Search Box with Centered Magnifier Icon --}}
                <form action="{{ route('peo.index') }}" method="GET" class="relative flex-1 md:w-64">
                    <input type="hidden" name="tab" value="{{ $activeTab }}">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <div class="relative flex items-center">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search:" 
                            class="w-full pl-9 pr-3 py-2 text-xs font-semibold border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-slate-100 rounded-xl focus:bg-white dark:focus:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-3.5 h-3.5 absolute left-3 text-slate-400 dark:text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                    </div>
                </form>
            </div>
        </div>

        {{-- TABLE DATA --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs" id="peo-table">
                <thead>
                    <tr class="bg-white dark:bg-slate-900 border-y border-slate-200 dark:border-slate-800 font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        <th class="px-5 py-3.5 w-12 text-center">NO</th>
                        <th class="px-5 py-3.5">NAMA CUSTOMER</th>
                        <th class="px-5 py-3.5">NAMA PROYEK</th>
                        <th class="px-5 py-3.5 text-center">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-800 dark:text-slate-200 font-semibold">
                    @forelse($peoSettings as $index => $item)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-5 py-4 text-center font-semibold text-slate-500 dark:text-slate-400">
                            {{ $peoSettings->firstItem() + $index }}
                        </td>
                        <td class="px-5 py-4 font-bold text-slate-900 dark:text-slate-100 uppercase">
                            {{ $item->customer }}
                        </td>
                        <td class="px-5 py-4 text-slate-800 dark:text-slate-200 uppercase">
                            {{ $item->project_name }}
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                {{-- Blue Detail Button --}}
                                <a href="{{ route('peo.show', $item->id) }}" 
                                    style="background-color: #007bff; color: #ffffff;"
                                    class="w-7 h-7 hover:opacity-90 text-white rounded flex items-center justify-center transition shadow-sm cursor-pointer border-0" title="View Detail">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                                </a>

                                {{-- Green Edit Button --}}
                                <button onclick='openEditModal(@json($item))' 
                                    style="background-color: #00c853; color: #ffffff;"
                                    class="w-7 h-7 hover:opacity-90 text-white rounded flex items-center justify-center transition shadow-sm cursor-pointer border-0" title="Edit Setting">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                                </button>

                                {{-- Red Delete Button --}}
                                <form action="{{ route('peo.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus mapping PEO ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                        style="background-color: #dc3545; color: #ffffff;"
                                        class="w-7 h-7 hover:opacity-90 text-white rounded flex items-center justify-center transition shadow-sm cursor-pointer border-0" title="Hapus">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400 text-xs">
                            Tidak ada data PEO Setting untuk kategori <strong>{{ $activeTab }}</strong>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION FOOTER --}}
        @if($peoSettings->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
            {{ $peoSettings->links() }}
        </div>
        @endif
    </div>
</div>

{{-- MODAL CREATE PEO SETTING --}}
<div id="modal-create-peo" class="hidden fixed inset-0 z-50 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-xl max-w-lg w-full shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="px-6 py-4 bg-primary text-white flex items-center justify-between">
            <h3 class="text-sm font-bold text-white">+ Tambah Mapping PEO Setting</h3>
            <button onclick="document.getElementById('modal-create-peo').classList.add('hidden')" class="text-white hover:text-slate-200 font-bold">&times;</button>
        </div>
        <form action="{{ route('peo.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tipe Dokumen PEO *</label>
                <select name="document_type" required class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
                    <option value="Berita Acara">Berita Acara</option>
                    <option value="Surat Keluar">Surat Keluar</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Customer *</label>
                <input type="text" name="customer" required placeholder="Contoh: PT PELINDO MARINE SERVICE" class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 uppercase">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kode Proyek *</label>
                <input type="text" name="project_code" required placeholder="P002" class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 uppercase">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Proyek *</label>
                <input type="text" name="project_name" required placeholder="JASA TUNTUN & TUNDA PELINDO" class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 uppercase">
            </div>
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modal-create-peo').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl">Batal</button>
                <button type="submit" style="background-color: #00c853; color: #ffffff;" class="px-4 py-2 text-xs font-bold text-white rounded-xl shadow-md border-0">Simpan Mapping</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT PEO SETTING --}}
<div id="modal-edit-peo" class="hidden fixed inset-0 z-50 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-xl max-w-lg w-full shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="px-6 py-4 bg-primary text-white flex items-center justify-between">
            <h3 class="text-sm font-bold text-white">Edit Mapping PEO Setting</h3>
            <button onclick="document.getElementById('modal-edit-peo').classList.add('hidden')" class="text-white hover:text-slate-200 font-bold">&times;</button>
        </div>
        <form id="form-edit-peo" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tipe Dokumen PEO *</label>
                <select id="edit-document_type" name="document_type" required class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
                    <option value="Berita Acara">Berita Acara</option>
                    <option value="Surat Keluar">Surat Keluar</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Customer *</label>
                <input type="text" id="edit-customer" name="customer" required class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 uppercase">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kode Proyek *</label>
                <input type="text" id="edit-project_code" name="project_code" required class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 uppercase">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Proyek *</label>
                <input type="text" id="edit-project_name" name="project_name" required class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 uppercase">
            </div>
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modal-edit-peo').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl">Batal</button>
                <button type="submit" style="background-color: #007bff; color: #ffffff;" class="px-4 py-2 text-xs font-bold text-white rounded-xl shadow-md border-0">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(item) {
    document.getElementById('form-edit-peo').action = '/dashboard/peo-settings/' + item.id;
    document.getElementById('edit-document_type').value = item.document_type || '';
    document.getElementById('edit-customer').value = item.customer || '';
    document.getElementById('edit-project_code').value = item.project_code || '';
    document.getElementById('edit-project_name').value = item.project_name || '';
    document.getElementById('modal-edit-peo').classList.remove('hidden');
}

function exportToCSV() {
    const table = document.getElementById('peo-table');
    if (!table) return;
    let csv = [];
    table.querySelectorAll('tr').forEach(row => {
        const cols = row.querySelectorAll('th, td');
        const rowData = [];
        cols.forEach((col, index) => {
            if (index < 3) {
                let text = col.innerText.replace(/"/g, '""').trim();
                rowData.push('"' + text + '"');
            }
        });
        if (rowData.length) csv.push(rowData.join(','));
    });
    const blob = new Blob(["\uFEFF" + csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'PEO_Setting_List_' + new Date().toISOString().slice(0,10) + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection
