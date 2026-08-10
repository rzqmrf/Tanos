@extends('layouts.app')

@section('title', 'STO Chart (Organization/Unit) — Tanos ERP')

@section('content')
<div class="space-y-6 w-full" x-data="{ showCreateModal: false }">
    <!-- Header Block -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Organization / Unit (STO Chart)</h1>
                <p class="text-xs text-slate-400 dark:text-slate-550 font-semibold">Definisikan struktur departemen, wilayah, dan cabang terintegrasi SAP.</p>
            </div>
        </div>
        
        @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
        <div class="flex items-center gap-2">
            <button onclick="alert('Unit/Department data berhasil dikirim dan disinkronkan dengan SAP')" class="inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-950/40 hover:bg-blue-100 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 px-3 py-2 rounded-xl text-xs font-bold transition cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                Send to SAP
            </button>
            <button onclick="alert('Unit/Department data berhasil dikirim ke Master Data Management (MDM)')" class="inline-flex items-center gap-1.5 bg-purple-50 dark:bg-purple-950/40 hover:bg-purple-100 dark:hover:bg-purple-900/50 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800 px-3 py-2 rounded-xl text-xs font-bold transition cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                Send to MDM
            </button>
            <button @click="showCreateModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition cursor-pointer">
                + Create New Unit
            </button>
        </div>
        @endif
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Data Table Section -->
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
        <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <table class="w-full text-left border-collapse min-w-[1200px]">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <th class="p-4 align-middle">Kode Unit</th>
                        <th class="p-4 align-middle">Nama Departemen / Unit</th>
                        <th class="p-4 align-middle">Parent Unit</th>
                        <th class="p-4 align-middle">Regional</th>
                        <th class="p-4 align-middle">Cost Center</th>
                        <th class="p-4 align-middle">Unit Type</th>
                        <th class="p-4 align-middle">Masa Berlaku</th>
                        <th class="p-4 align-middle text-center">Status</th>
                        <th class="p-4 align-middle text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-350">
                    @forelse($divisions as $div)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition {{ !$div->active ? 'opacity-60 bg-slate-50/20' : '' }}">
                        <td class="p-4 align-middle font-mono font-bold text-slate-800 dark:text-slate-300 text-[13px]">
                            {{ $div->code ?? 'N/A' }}
                        </td>
                        <td class="p-4 align-middle">
                            <span class="block font-bold text-slate-850 dark:text-slate-150">{{ $div->name }}</span>
                            @if($div->description)
                                <span class="block text-[11px] text-slate-400 dark:text-slate-550 mt-0.5">{{ $div->description }}</span>
                            @endif
                        </td>
                        <td class="p-4 align-middle font-semibold text-slate-700 dark:text-slate-400">
                            {{ $div->parent ? $div->parent->name : '-' }}
                        </td>
                        <td class="p-4 align-middle font-semibold text-slate-750 dark:text-slate-400">
                            {{ $div->regional ?? '-' }}
                        </td>
                        <td class="p-4 align-middle font-mono text-slate-600 dark:text-slate-450">
                            {{ $div->cost_center ?? '-' }}
                        </td>
                        <td class="p-4 align-middle">
                            <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-400">
                                {{ $div->unit_type }}
                            </span>
                        </td>
                        <td class="p-4 align-middle text-xs whitespace-nowrap text-slate-500 dark:text-slate-450">
                            {{ $div->valid_from ? $div->valid_from->format('d M Y') : '01 Jan 2024' }} - {{ $div->valid_to ? $div->valid_to->format('d M Y') : 'Selamanya' }}
                        </td>
                        <td class="p-4 align-middle text-center whitespace-nowrap">
                            <span class="inline-block px-2.5 py-1 {{ $div->active ? 'bg-emerald-55/10 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400' : 'bg-rose-55/10 text-rose-600 dark:bg-rose-950/30 dark:text-rose-400' }} rounded-md text-xs font-bold">
                                {{ $div->active ? 'Active' : 'Delimited' }}
                            </span>
                        </td>
                        <td class="p-4 align-middle text-center whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-1.5">
                                @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
                                    @if($div->active)
                                        <form action="{{ route('org.sto.delimit', $div->id) }}" method="POST" onsubmit="return confirm('Delimit unit ini? Unit akan dinonaktifkan mulai hari ini sebagai catatan sejarah.')" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/30 dark:hover:bg-amber-900/40 text-amber-700 dark:text-amber-400 text-xs font-bold rounded-lg transition cursor-pointer">
                                                Delimit
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if(!$div->sent_to_sap)
                                        <form action="{{ route('org.sto.send-sap', $div->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/30 dark:hover:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded-lg transition cursor-pointer">
                                                Send to SAP
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-slate-400 text-xs font-bold rounded-lg flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 mr-1">
                                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                                            </svg>
                                            SAP Sent
                                        </span>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400 italic">No Action</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-12 text-center">
                            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/10 border border-slate-200 dark:border-slate-800 p-6 max-w-md mx-auto">
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Data unit kerja kosong.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL: CREATE UNIT --}}
    <div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-sm" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showCreateModal = false">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-4 uppercase tracking-wider">Tambah Unit Baru</h3>
            
            <form action="{{ route('org.sto.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Kode Unit SAP</label>
                        <input type="text" name="code" required placeholder="Contoh: 10100" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Unit</label>
                        <input type="text" name="name" required placeholder="Contoh: Regional 1" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Parent Unit (Pelaporan)</label>
                    <select name="parent_id" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                        <option value="">-- No Parent (Root Unit) --</option>
                        @foreach($rootDivs as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }} ({{ $parent->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Tipe Unit</label>
                        <select name="unit_type" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            <option value="Perusahaan User">Perusahaan User</option>
                            <option value="Cabang">Cabang</option>
                            <option value="Wilayah">Wilayah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Regional</label>
                        <input type="text" name="regional" placeholder="Jawa Timur" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Cost Center</label>
                        <input type="text" name="cost_center" placeholder="CC-9901" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Mulai Berlaku</label>
                        <input type="date" name="valid_from" value="{{ date('Y-m-d') }}" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Deskripsi / Catatan</label>
                    <textarea name="description" rows="2" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200" placeholder="Keterangan singkat unit kerja..."></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                        Simpan Unit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
