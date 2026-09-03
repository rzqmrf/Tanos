@extends('layouts.app')

@section('title', 'Tax Master — Finance & Accounting TANOS ERP')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: false, 
    showDetailModal: false,
    editMode: false, 
    detailItem: {},
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
        this.showDetailModal = false;
        this.showModal = true;
    },
    openDetail(item) {
        this.detailItem = item;
        this.showDetailModal = true;
    }
}">    <!-- Page Header & Action -->
    <x-page-header 
        title="Tax Master (Tarif Pajak)" 
        subtitle="Pengaturan tarif PPN, PPh 21 Tenaga Kerja, PPh 23 Jasa, dan PPh Final 4(2) untuk transaksi invoice & RAB proyek."
        :breadcrumbs="[
            'General' => '#',
            'Master FA' => '#',
            'Tax Master' => ''
        ]"
        create-label="Tambah Tarif Pajak"
        create-click="openCreate()"
    />

    <!-- Alert Notification -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Data Table Container matching Golden Benchmark -->
    <x-data-card 
        title="Tax Master - List" 
        :total="$taxes->total()"
        :search-route="route('fa.tax')"
    >
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
                        <th class="py-3.5 px-4 text-center w-28">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($taxes as $index => $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                            <td class="py-3.5 px-4 text-slate-400 font-semibold">{{ $taxes->firstItem() + $index }}</td>
                            <td class="py-3.5 px-4">
                                <button @click="openDetail({{ json_encode($item) }})" class="px-2.5 py-1 bg-primary-light text-primary font-mono font-bold rounded-md text-[11px] border border-primary-subtle hover:opacity-80 transition cursor-pointer">
                                    {{ $item->code }}
                                </button>
                            </td>
                            <td class="py-3.5 px-4">
                                <button @click="openDetail({{ json_encode($item) }})" class="font-bold text-slate-800 dark:text-slate-100 hover:text-primary transition cursor-pointer text-left">
                                    {{ $item->name }}
                                </button>
                            </td>
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
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1.5">
                                    <x-action-button type="view" :click="'openDetail(' . json_encode($item) . ')'" title="Lihat Detail" />
                                    <x-action-button type="edit" :click="'openEdit(' . json_encode($item) . ')'" title="Edit Pajak" />
                                    <form method="POST" action="{{ route('fa.tax.destroy', $item->id) }}" onsubmit="return confirm('Hapus tarif pajak ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <x-action-button type="delete" title="Hapus Pajak" />
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
        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
            {{ $taxes->links() }}
        </div>
        @endif
    </x-data-card>

    <!-- Modal Form Tambah / Edit Pajak -->
    <div x-show="showModal" x-transition class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
        <div @click.away="showModal = false" class="bg-white dark:bg-slate-900 w-full max-w-md rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden">
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
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-primary font-mono font-bold uppercase">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Pajak <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" x-model="form.name" required placeholder="Contoh: PPN Keluaran 11%"
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-primary font-semibold">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Tarif Pajak (%) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.01" min="0" max="100" name="rate_percent" x-model="form.rate_percent" required placeholder="11.00"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-primary font-black">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Tipe Pemotongan <span class="text-rose-500">*</span></label>
                        <select name="tax_type" x-model="form.tax_type" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-primary font-bold">
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
                              class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-primary"></textarea>
                </div>

                <div class="flex items-center space-x-2 pt-1">
                    <input type="checkbox" id="taxActiveToggle" name="active" value="1" x-model="form.active"
                           class="w-4 h-4 text-primary rounded border-slate-300 focus:ring-primary cursor-pointer">
                    <label for="taxActiveToggle" class="text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer">Status Tarif Pajak Aktif</label>
                </div>

                <div class="pt-4 flex items-center justify-end space-x-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-bold text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-md shadow-primary transition cursor-pointer" x-text="editMode ? 'Simpan Perubahan' : 'Tambah Tarif'"></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal View Detail Pajak -->
    <div x-show="showDetailModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="showDetailModal = false"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xl p-6 overflow-hidden">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-1 rounded-lg bg-primary-light text-primary font-mono font-black text-xs" x-text="detailItem.code"></span>
                        <h3 class="text-base font-black text-slate-800 dark:text-slate-100">Detail Tarif Pajak</h3>
                    </div>
                    <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4 text-xs">
                    <div>
                        <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Nama Pajak</span>
                        <p class="text-base font-black text-slate-800 dark:text-slate-100 mt-0.5" x-text="detailItem.name"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-slate-800/50 p-3.5 rounded-xl border border-slate-100 dark:border-slate-800">
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Besaran Tarif</span>
                            <p class="text-lg font-black text-rose-600 dark:text-rose-400 mt-0.5" x-text="(detailItem.rate_percent || 0) + ' %'"></p>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Tipe Pemotongan</span>
                            <p class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 uppercase" x-text="detailItem.tax_type"></p>
                        </div>
                    </div>

                    <div>
                        <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Status</span>
                        <template x-if="detailItem.active">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 mt-1">
                                AKTIF
                            </span>
                        </template>
                        <template x-if="!detailItem.active">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400 mt-1">
                                NON-AKTIF
                            </span>
                        </template>
                    </div>

                    <div>
                        <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Deskripsi / Peruntukan</span>
                        <p class="text-slate-700 dark:text-slate-300 mt-0.5 leading-relaxed bg-slate-50 dark:bg-slate-800/30 p-3 rounded-xl border border-slate-100 dark:border-slate-800" x-text="detailItem.description || 'Tidak ada catatan tambahan.'"></p>
                    </div>

                    <div class="pt-2 border-t border-slate-100 dark:border-slate-800 text-[11px] text-slate-400 flex justify-between">
                        <span>Dibuat: <span class="font-mono" x-text="detailItem.created_at ? new Date(detailItem.created_at).toLocaleString('id-ID') : '-'"></span></span>
                        <span>Diupdate: <span class="font-mono" x-text="detailItem.updated_at ? new Date(detailItem.updated_at).toLocaleString('id-ID') : '-'"></span></span>
                    </div>
                </div>

                <div class="flex justify-end space-x-2 pt-4 mt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showDetailModal = false"
                            class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition cursor-pointer">
                        Tutup
                    </button>
                    <button type="button" @click="openEdit(detailItem)"
                            class="px-5 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-md shadow-primary transition flex items-center space-x-1.5 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        <span>Edit Data Ini</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
