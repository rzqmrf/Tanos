@extends('layouts.app')

@section('title', 'View Detail Mapping PEO Setting — Tanos ERP')

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
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 dark:text-slate-400 mb-1">
                    <a href="{{ route('dashboard.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                        <span>Home</span>
                    </a>
                    <span class="text-slate-300 dark:text-slate-600">/</span>
                    <a href="{{ route('peo.index') }}" class="hover:text-slate-700 dark:hover:text-slate-200">Mapping PEO Setting</a>
                    <span class="text-slate-300 dark:text-slate-600">/</span>
                    <span class="text-slate-600 dark:text-slate-300 font-bold">View Detail</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Mapping PEO Setting</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Pengelolaan Mapping Integrasi Dokumen PEO</p>
            </div>

            {{-- ACTION BUTTONS TOP RIGHT --}}
            <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
                <a href="{{ route('peo.index') }}" 
                    style="background-color: #64748b; color: #ffffff;"
                    class="px-4 py-2 hover:opacity-90 text-white font-bold text-xs rounded-xl transition flex items-center gap-1.5 cursor-pointer border-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                    <span>Back</span>
                </a>
                <a href="{{ route('peo.index') }}" 
                    style="background-color: #7c3aed; color: #ffffff;"
                    class="px-4 py-2 hover:opacity-90 text-white font-bold text-xs rounded-xl transition flex items-center gap-1.5 cursor-pointer border-0">
                    <span class="text-sm font-bold">+</span>
                    <span>Create New</span>
                </a>
                <button onclick="alert('Setting berhasil di-copy.')" 
                    style="background-color: #00c853; color: #ffffff;"
                    class="px-4 py-2 hover:opacity-90 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center gap-1.5 cursor-pointer border-0">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25c0-.621.504-1.125 1.125-1.125h6.75c.621 0 1.125.504 1.125 1.125v9.25c0 .621-.504 1.125-1.125 1.125Z" /></svg>
                    <span>Copy Setting</span>
                </button>
            </div>
        </div>
    </div>

    {{-- MAIN CARD CONTENT --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden p-6 space-y-8">
        {{-- Card Header & Title --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">
                Mapping PEO Setting - View PEO Setting Dokumen {{ strtolower($peoSetting->document_type) }} For {{ $peoSetting->customer }}
            </h2>
            <div class="flex items-center gap-2">
                <button onclick="document.getElementById('modal-edit-peo-detail').classList.remove('hidden')" 
                    style="color: #00c853; border-color: #00c853;"
                    class="px-3 py-1.5 text-xs font-bold border hover:bg-emerald-50 dark:hover:bg-emerald-950/40 rounded-xl transition flex items-center gap-1 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                    <span>Edit</span>
                </button>
                <form action="{{ route('peo.destroy', $peoSetting->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" 
                        style="color: #dc3545; border-color: #dc3545;"
                        class="px-3 py-1.5 text-xs font-bold border hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition flex items-center gap-1 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                        <span>Delete</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- FORM FIELDS (DISABLED VIEW STATE) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs font-semibold">
            {{-- Document Type --}}
            <div>
                <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-3 font-bold">Document Type</label>
                <div class="relative">
                    <select disabled class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-100 font-bold appearance-none cursor-not-allowed">
                        <option selected>{{ $peoSetting->document_type }}</option>
                    </select>
                    <svg class="w-3.5 h-3.5 absolute right-3 top-3 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                </div>
            </div>

            {{-- Customer --}}
            <div>
                <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-3 font-bold">Customer</label>
                <input type="text" disabled value="1000000004 - {{ $peoSetting->customer }}" 
                    class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-100 font-bold cursor-not-allowed">
            </div>

            {{-- Project --}}
            <div class="md:col-span-1">
                <label class="block text-slate-600 dark:text-slate-400 dark:text-slate-400 mb-3 font-bold">Project</label>
                <input type="text" disabled value="{{ $peoSetting->project_code }} - {{ $peoSetting->project_name }}" 
                    class="w-full px-3.5 py-2.5 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-100 font-bold cursor-not-allowed">
            </div>
        </div>

        {{-- SECTION 1: PENANDATANGAN --}}
        <div class="space-y-3 pt-4">
            <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100">Penandatangan</h3>
            <div class="overflow-x-auto border border-slate-200 dark:border-slate-800 rounded-xl">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 font-bold text-slate-700 dark:text-slate-200 uppercase">
                            <th class="px-4 py-3 w-12 text-center">No</th>
                            <th class="px-4 py-3">Jenis Pihak</th>
                            <th class="px-4 py-3">Kode Jabatan</th>
                            <th class="px-4 py-3">Nama Jabatan</th>
                            <th class="px-4 py-3">User ID</th>
                            <th class="px-4 py-3">User Description</th>
                            <th class="px-4 py-3">Jabatan Cetak</th>
                            <th class="px-4 py-3">Unit Kerja</th>
                            <th class="px-4 py-3 text-center">Urutan TTD</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-semibold text-slate-800 dark:text-slate-200">
                        @forelse($peoSetting->signers as $idx => $s)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <td class="px-4 py-3 text-center text-slate-500 dark:text-slate-400">{{ $idx + 1 }}</td>
                            <td class="px-4 py-3 uppercase font-bold text-slate-900 dark:text-slate-100">{{ $s->party_type }}</td>
                            <td class="px-4 py-3 font-mono text-slate-700 dark:text-slate-300">{{ $s->position_code }}</td>
                            <td class="px-4 py-3 uppercase">{{ $s->position_name }}</td>
                            <td class="px-4 py-3 font-mono text-slate-600 dark:text-slate-400">{{ $s->user_id }}</td>
                            <td class="px-4 py-3 font-bold text-slate-900 dark:text-slate-100 uppercase">{{ $s->user_description }}</td>
                            <td class="px-4 py-3 uppercase">{{ $s->print_position }}</td>
                            <td class="px-4 py-3 uppercase">{{ $s->work_unit }}</td>
                            <td class="px-4 py-3 text-center font-bold font-mono text-slate-900 dark:text-slate-100">{{ $s->signer_order }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">Tidak ada data penandatangan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- SECTION 2: PEMARAF --}}
        <div class="space-y-3 pt-4">
            <h3 class="text-xs font-bold text-slate-800 dark:text-slate-100">Pemaraf</h3>
            <div class="overflow-x-auto border border-slate-200 dark:border-slate-800 rounded-xl">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 font-bold text-slate-700 dark:text-slate-200 uppercase">
                            <th class="px-4 py-3 w-12 text-center">No</th>
                            <th class="px-4 py-3">Jenis Pihak</th>
                            <th class="px-4 py-3">Kode Jabatan</th>
                            <th class="px-4 py-3">Nama Jabatan</th>
                            <th class="px-4 py-3">User ID</th>
                            <th class="px-4 py-3">User Description</th>
                            <th class="px-4 py-3">Jabatan Cetak</th>
                            <th class="px-4 py-3">Unit Kerja</th>
                            <th class="px-4 py-3 text-center">Urutan TTD</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 font-semibold text-slate-800 dark:text-slate-200">
                        @forelse($peoSetting->initials as $idx => $i)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition">
                            <td class="px-4 py-3 text-center text-slate-500 dark:text-slate-400">{{ $idx + 1 }}</td>
                            <td class="px-4 py-3 uppercase font-bold text-slate-900 dark:text-slate-100">{{ $i->party_type }}</td>
                            <td class="px-4 py-3 font-mono text-slate-700 dark:text-slate-300">{{ $i->position_code }}</td>
                            <td class="px-4 py-3 uppercase">{{ $i->position_name }}</td>
                            <td class="px-4 py-3 font-mono text-slate-600 dark:text-slate-400">{{ $i->user_id }}</td>
                            <td class="px-4 py-3 font-bold text-slate-900 dark:text-slate-100 uppercase">{{ $i->user_description }}</td>
                            <td class="px-4 py-3 uppercase">{{ $i->print_position }}</td>
                            <td class="px-4 py-3 uppercase">{{ $i->work_unit }}</td>
                            <td class="px-4 py-3 text-center font-bold font-mono text-slate-900 dark:text-slate-100">{{ $i->signer_order }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">Tidak ada data pemaraf.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT DETAIL PEO SETTING --}}
<div id="modal-edit-peo-detail" class="hidden fixed inset-0 z-50 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="px-6 py-4 bg-primary text-white flex items-center justify-between">
            <h3 class="text-sm font-bold text-white">Edit Mapping PEO Setting</h3>
            <button onclick="document.getElementById('modal-edit-peo-detail').classList.add('hidden')" class="text-white hover:text-slate-200 font-bold">&times;</button>
        </div>
        <form action="{{ route('peo.update', $peoSetting->id) }}" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tipe Dokumen PEO *</label>
                <select name="document_type" required class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
                    <option value="Berita Acara" {{ $peoSetting->document_type === 'Berita Acara' ? 'selected' : '' }}>Berita Acara</option>
                    <option value="Surat Keluar" {{ $peoSetting->document_type === 'Surat Keluar' ? 'selected' : '' }}>Surat Keluar</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Customer *</label>
                <input type="text" name="customer" value="{{ $peoSetting->customer }}" required class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 uppercase">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kode Proyek *</label>
                <input type="text" name="project_code" value="{{ $peoSetting->project_code }}" required class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 uppercase">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Proyek *</label>
                <input type="text" name="project_name" value="{{ $peoSetting->project_name }}" required class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 uppercase">
            </div>
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modal-edit-peo-detail').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl">Batal</button>
                <button type="submit" style="background-color: #007bff; color: #ffffff;" class="px-4 py-2 text-xs font-bold text-white rounded-xl shadow-md border-0">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
