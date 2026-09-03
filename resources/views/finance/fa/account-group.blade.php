@extends('layouts.app')

@section('title', 'Account Group Master — Finance & Accounting TANOS ERP')

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
        category: 'balance_sheet', 
        description: '', 
        active: true 
    },
    openCreate() {
        this.editMode = false;
        this.form = { 
            id: null, 
            code: '', 
            name: '', 
            category: 'balance_sheet', 
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
            category: item.category, 
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
}">

    <x-page-header 
        title="Account Group Master" 
        subtitle="Pengelompokan akun buku besar (General Ledger) untuk laporan Neraca (Balance Sheet) & Laba Rugi (Income Statement)."
        :breadcrumbs="[
            'General' => '#',
            'Master FA' => '#',
            'Account Group' => ''
        ]"
        create-label="Tambah Account Group"
        create-click="openCreate()"
    />

    <!-- Data Table Container matching Golden Benchmark -->
    <x-data-card 
        title="Account Group - List" 
        :total="$accountGroups->total()"
        :search-route="route('fa.account-group')"
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4">No</th>
                        <th class="py-3.5 px-4">Kode Group</th>
                        <th class="py-3.5 px-4">Nama Account Group</th>
                        <th class="py-3.5 px-4">Kategori Laporan</th>
                        <th class="py-3.5 px-4">Keterangan</th>
                        <th class="py-3.5 px-4 text-center">Jumlah Akun</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center w-28">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($accountGroups as $index => $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                            <td class="py-3.5 px-4 text-slate-400 font-semibold">{{ $accountGroups->firstItem() + $index }}</td>
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
                            <td class="py-3.5 px-4">
                                @if($item->category === 'balance_sheet')
                                    <span class="px-2.5 py-1 bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-400 font-bold rounded-lg text-[11px] border border-sky-200 dark:border-sky-800/50 inline-flex items-center">
                                        Balance Sheet (Neraca)
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 font-bold rounded-lg text-[11px] border border-purple-200 dark:border-purple-800/50 inline-flex items-center">
                                        Income Statement (Laba Rugi)
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400 max-w-xs">{{ $item->description ?? '—' }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-[11px]">
                                    {{ $item->accounts_count }} Akun
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
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
                                    <x-action-button type="edit" :click="'openEdit(' . json_encode($item) . ')'" title="Edit Group" />
                                    @if($item->accounts_count == 0)
                                        <form method="POST" action="{{ route('fa.account-group.destroy', $item->id) }}" onsubmit="return confirm('Hapus Account Group ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <x-action-button type="delete" title="Hapus Group" />
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                Belum ada data Account Group yang tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($accountGroups->hasPages())
        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
            {{ $accountGroups->links() }}
        </div>
        @endif
    </x-data-card>

    <!-- Modal Form Tambah / Edit Account Group -->
    <div x-show="showModal" x-transition class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
        <div @click.away="showModal = false" class="bg-white dark:bg-slate-900 w-full max-w-md rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-base font-black text-slate-800 dark:text-slate-100" x-text="editMode ? 'Edit Account Group' : 'Tambah Account Group Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form :action="editMode ? '{{ url('dashboard/fa/account-group') }}/' + form.id : '{{ route('fa.account-group.store') }}'" method="POST" class="p-6 space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Kode Group <span class="text-rose-500">*</span></label>
                    <input type="text" name="code" x-model="form.code" required placeholder="Contoh: 100, 200, 400"
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-primary font-mono font-bold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Account Group <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" x-model="form.name" required placeholder="Contoh: Aset Lancar / Liabilitas Jangka Pendek"
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-primary font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Kategori Pelaporan <span class="text-rose-500">*</span></label>
                    <select name="category" x-model="form.category" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-primary font-bold">
                        <option value="balance_sheet">Pos Neraca (Balance Sheet)</option>
                        <option value="income_statement">Laba Rugi (Income Statement)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Deskripsi / Keterangan</label>
                    <textarea name="description" x-model="form.description" rows="3" placeholder="Keterangan pengelompokan akun..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-primary"></textarea>
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <input type="checkbox" id="groupActiveToggle" name="active" value="1" x-model="form.active"
                           class="w-4 h-4 text-primary rounded border-slate-300 focus:ring-primary cursor-pointer">
                    <label for="groupActiveToggle" class="text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer">Status Group Aktif</label>
                </div>

                <div class="pt-4 flex items-center justify-end space-x-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-bold text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-md shadow-primary transition cursor-pointer" x-text="editMode ? 'Simpan Perubahan' : 'Tambah Group'"></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal View Detail Account Group -->
    <div x-show="showDetailModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="showDetailModal = false"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xl p-6 overflow-hidden">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-1 rounded-lg bg-primary-light text-primary font-mono font-black text-xs" x-text="detailItem.code"></span>
                        <h3 class="text-base font-black text-slate-800 dark:text-slate-100">Detail Account Group</h3>
                    </div>
                    <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4 text-xs">
                    <div>
                        <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Nama Account Group</span>
                        <p class="text-base font-black text-slate-800 dark:text-slate-100 mt-0.5" x-text="detailItem.name"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-slate-800/50 p-3.5 rounded-xl border border-slate-100 dark:border-slate-800">
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Kategori Laporan</span>
                            <template x-if="detailItem.category === 'balance_sheet'">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 inline-block mt-1">
                                    Neraca (Balance Sheet)
                                </span>
                            </template>
                            <template x-if="detailItem.category !== 'balance_sheet'">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 inline-block mt-1">
                                    Laba Rugi (Income Statement)
                                </span>
                            </template>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Status Akun</span>
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
                    </div>

                    <div>
                        <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Deskripsi / Keterangan</span>
                        <p class="text-slate-700 dark:text-slate-300 mt-0.5 leading-relaxed bg-slate-50 dark:bg-slate-800/30 p-3 rounded-xl border border-slate-100 dark:border-slate-800" x-text="detailItem.description || 'Tidak ada deskripsi tambahan.'"></p>
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

