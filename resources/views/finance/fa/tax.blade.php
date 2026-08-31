@extends('layouts.app')

@section('title', 'Tax Master — Finance & Accounting TANOS ERP')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: false, 
    editMode: false, 
    form: { 
        id: null, 
        code: '', 
        name: '', 
        rate_percent: 11.00, 
        tax_type: 'ppn', 
        description: '', 
        active: true 
    },
    openCreate() {
        this.editMode = false;
        this.form = { 
            id: null, 
            code: '', 
            name: '', 
            rate_percent: 11.00, 
            tax_type: 'ppn', 
            description: '', 
            active: true 
        };
        this.showModal = true;
    },
    openEdit(item) {
        this.editMode = true;
        this.form = { 
            id: item.id, 
            code: item.code, 
            name: item.name, 
            rate_percent: item.rate_percent, 
            tax_type: item.tax_type, 
            description: item.description || '', 
            active: !!item.active 
        };
        this.showModal = true;
    }
}">

    <!-- Page Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-[#100b60] dark:text-blue-400 uppercase tracking-wider mb-1">
                <span>Finance & Accounting</span>
                <span>•</span>
                <span>Perpajakan & Billing</span>
            </div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight flex items-center">
                <span>Tax Master (Tarif Pajak)</span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Pengaturan tarif PPN, PPh 21 Tenaga Kerja, PPh 23 Jasa, dan PPh Final 4(2) untuk transaksi invoice & RAB proyek.</p>
        </div>

        <button @click="openCreate()"
                class="px-5 py-2.5 bg-[#100b60] hover:bg-[#0c084d] text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-950/20 transition flex items-center space-x-2 self-start sm:self-auto cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tambah Tarif Pajak</span>
        </button>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-[#100b60] dark:text-blue-400 flex items-center justify-center font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6H2.25m0 0v8.25m0-8.25a60.073 60.073 0 0 1 15.797-2.101C18.774 3.95 19.5 4.49 19.5 5.244V6m0 0v8.25m0-8.25h.75a.75.75 0 0 1 .75.75v.75m0 0v8.25m0-8.25a60.07 60.07 0 0 1-15.797 2.101C3.726 17.05 3 16.51 3 15.756V15" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Total Master Pajak</span>
                <span class="text-xl font-black text-slate-800 dark:text-slate-100">{{ $totalAll }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Status Aktif</span>
                <span class="text-xl font-black text-emerald-600 dark:text-emerald-400">{{ $totalActive }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Integrasi Billing</span>
                <span class="text-sm font-bold text-rose-600 dark:text-rose-400">Pranota & Faktur Pajak</span>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('fa.tax') }}" class="flex flex-wrap items-center gap-2 flex-1 max-w-lg">
            <div class="relative min-w-[220px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama pajak..."
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-medium">
                <div class="absolute left-3 top-2.5 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </div>

            <select name="tax_type" onchange="this.form.submit()"
                    class="py-2 px-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-blue-500">
                <option value="">Semua Tipe Pajak</option>
                <option value="ppn" {{ request('tax_type') == 'ppn' ? 'selected' : '' }}>PPN (Pertambahan Nilai)</option>
                <option value="pph21" {{ request('tax_type') == 'pph21' ? 'selected' : '' }}>PPh 21 (Tenaga Kerja / Gaji)</option>
                <option value="pph23" {{ request('tax_type') == 'pph23' ? 'selected' : '' }}>PPh 23 (Jasa & Sewa)</option>
                <option value="pph4_2" {{ request('tax_type') == 'pph4_2' ? 'selected' : '' }}>PPh Final 4 Ayat (2)</option>
                <option value="other" {{ request('tax_type') == 'other' ? 'selected' : '' }}>Lainnya</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-[#100b60] text-white rounded-xl text-xs font-bold hover:bg-[#0c084d] transition cursor-pointer">Filter</button>
            @if(request('search') || request('tax_type'))
                <a href="{{ route('fa.tax') }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-200 transition">Reset</a>
            @endif
        </form>

        <div class="flex items-center space-x-2">
            <a href="{{ route('billing.index') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition flex items-center space-x-1.5">
                <span>Modul Billing & Pranota</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4">No</th>
                        <th class="py-3.5 px-4">Kode Pajak</th>
                        <th class="py-3.5 px-4">Nama Pajak</th>
                        <th class="py-3.5 px-4 text-center">Tarif (%)</th>
                        <th class="py-3.5 px-4">Tipe Pemotongan</th>
                        <th class="py-3.5 px-4">Keterangan</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($taxes as $index => $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                            <td class="py-3.5 px-4 text-slate-400 font-semibold">{{ $taxes->firstItem() + $index }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-950/50 text-[#100b60] dark:text-blue-300 font-mono font-bold rounded-md text-[11px] border border-blue-100 dark:border-blue-900">
                                    {{ $item->code }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-slate-100">{{ $item->name }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 font-black rounded-lg text-xs border border-rose-200 dark:border-rose-800">
                                    {{ number_format($item->rate_percent, 2) }} %
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-bold uppercase text-[11px] text-slate-700 dark:text-slate-300">
                                {{ strtoupper($item->tax_type) }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400 max-w-xs">{{ $item->description ?? '—' }}</td>
                            <td class="py-3.5 px-4">
                                @if($item->active)
                                    <span class="px-2.5 py-1 text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 rounded-lg inline-flex items-center">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Aktif
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-lg inline-flex items-center">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>Non-Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end space-x-1.5">
                                    <button @click="openEdit({{ json_encode($item) }})"
                                            class="p-1.5 text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/40 rounded-lg transition cursor-pointer" title="Edit Pajak">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>

                                    <form method="POST" action="{{ route('fa.tax.destroy', $item->id) }}" onsubmit="return confirm('Hapus tarif pajak ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition cursor-pointer" title="Hapus Pajak">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                Belum ada data Tarif Pajak yang tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($taxes->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                {{ $taxes->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form Tambah / Edit Pajak -->
    <div x-show="showModal" x-transition class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
        <div @click.away="showModal = false" class="bg-white dark:bg-slate-900 w-full max-w-md rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-base font-black text-slate-800 dark:text-slate-100" x-text="editMode ? 'Edit Tarif Pajak' : 'Tambah Tarif Pajak Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form :action="editMode ? '{{ url('dashboard/fa/tax') }}/' + form.id : '{{ route('fa.tax.store') }}'" method="POST" class="p-6 space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Kode Pajak <span class="text-rose-500">*</span></label>
                    <input type="text" name="code" x-model="form.code" required placeholder="Contoh: PPN-11, PPH23-JASA"
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-mono font-bold uppercase">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Pajak <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" x-model="form.name" required placeholder="Contoh: PPN Keluaran 11%"
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-semibold">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Tarif Pajak (%) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" min="0" max="100" name="rate_percent" x-model="form.rate_percent" required placeholder="11.00"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Tipe Pemotongan <span class="text-rose-500">*</span></label>
                        <select name="tax_type" x-model="form.tax_type" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-bold">
                            <option value="ppn">PPN</option>
                            <option value="pph21">PPh 21</option>
                            <option value="pph23">PPh 23</option>
                            <option value="pph4_2">PPh Final 4(2)</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Deskripsi / Peruntukan</label>
                    <textarea name="description" x-model="form.description" rows="2" placeholder="Keterangan dasar pengenaan pajak..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500"></textarea>
                </div>

                <div class="flex items-center space-x-2 pt-1">
                    <input type="checkbox" id="taxActiveToggle" name="active" value="1" x-model="form.active"
                           class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 cursor-pointer">
                    <label for="taxActiveToggle" class="text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer">Status Tarif Pajak Aktif</label>
                </div>

                <div class="pt-4 flex items-center justify-end space-x-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-bold text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#100b60] hover:bg-[#0c084d] text-white text-xs font-bold rounded-xl shadow-md transition cursor-pointer" x-text="editMode ? 'Simpan Perubahan' : 'Tambah Tarif'"></button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
