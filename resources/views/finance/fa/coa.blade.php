@extends('layouts.app')

@section('title', 'Chart of Accounts (CoA) — Finance & Accounting TANOS ERP')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: false, 
    showDetailModal: false,
    editMode: false, 
    detailItem: {},
    form: { 
        id: null, 
        account_group_id: '{{ $accountGroups->first()->id ?? '' }}', 
        parent_id: '', 
        code: '', 
        name: '', 
        level: 2, 
        normal_balance: 'debit', 
        is_header: false, 
        description: '', 
        active: true 
    },
    openCreate() {
        this.editMode = false;
        this.form = { 
            id: null, 
            account_group_id: '{{ $accountGroups->first()->id ?? '' }}', 
            parent_id: '', 
            code: '', 
            name: '', 
            level: 2, 
            normal_balance: 'debit', 
            is_header: false, 
            description: '', 
            active: true 
        };
        this.showModal = true;
    },
    openEdit(item) {
        this.editMode = true;
        this.form = { 
            id: item.id, 
            account_group_id: item.account_group_id, 
            parent_id: item.parent_id || '', 
            code: item.code, 
            name: item.name, 
            level: item.level, 
            normal_balance: item.normal_balance, 
            is_header: !!item.is_header, 
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
        title="Chart of Accounts (CoA) Master" 
        subtitle="Struktur bagan akun hierarkis terpadu untuk integrasi jurnal voucher, posting payroll, dan sinkronisasi SAP General Ledger."
        :breadcrumbs="[
            'General' => '#',
            'Master FA' => '#',
            'Chart of Accounts' => ''
        ]"
        create-label="Tambah Akun CoA"
        create-click="openCreate()"
    />

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-primary-light text-primary flex items-center justify-center font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5-3h7.5M8.25 9.75h.008v.008H8.25V9.75Z" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Total Nomor Akun</span>
                <span class="text-xl font-black text-slate-800 dark:text-slate-100">{{ $totalAll }} Akun</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Akun Induk / Header</span>
                <span class="text-xl font-black text-amber-600 dark:text-amber-400">{{ $totalHeaders }} Header</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Akun Posting Transaksi</span>
                <span class="text-xl font-black text-emerald-600 dark:text-emerald-400">{{ $totalPosting }} Akun</span>
            </div>
        </div>
    </div>

    <!-- Data Table Container matching Golden Benchmark -->
    <x-data-card 
        title="Chart of Accounts - List" 
        :total="$accounts->total()"
        :search-route="route('fa.coa')"
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4">No Akun</th>
                        <th class="py-3.5 px-4">Nama Akun CoA</th>
                        <th class="py-3.5 px-4">Group Akun</th>
                        <th class="py-3.5 px-4 text-center">Tipe / Level</th>
                        <th class="py-3.5 px-4 text-center">Posisi Saldo</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center w-28">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($accounts as $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                            <td class="py-3.5 px-4">
                                <button @click="openDetail({{ json_encode($item) }})" class="px-2.5 py-1 bg-primary-light text-primary font-mono font-bold rounded-md text-[11px] border border-primary-subtle hover:opacity-80 transition cursor-pointer">
                                    {{ $item->code }}
                                </button>
                            </td>
                            <td class="py-3.5 px-4">
                                <button @click="openDetail({{ json_encode($item) }})" class="font-bold text-slate-800 dark:text-slate-100 hover:text-primary transition cursor-pointer text-left flex items-center">
                                    @if($item->level > 1)
                                        <span class="text-slate-300 mr-1 font-mono">└─</span>
                                    @endif
                                    <span class="{{ $item->is_header ? 'font-black text-slate-900 dark:text-white' : '' }}">{{ $item->name }}</span>
                                </button>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-medium text-[11px]">
                                    {{ $item->accountGroup?->name ?? '—' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($item->is_header)
                                    <span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 font-bold rounded-lg text-[11px] border border-amber-200 dark:border-amber-800/50 inline-flex items-center">
                                        Header (L{{ $item->level }})
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-400 font-bold rounded-lg text-[11px] border border-sky-200 dark:border-sky-800/50 inline-flex items-center">
                                        Detail (L{{ $item->level }})
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($item->normal_balance === 'debit')
                                    <span class="px-2 py-0.5 rounded font-mono font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 text-[11px]">DEBIT</span>
                                @else
                                    <span class="px-2 py-0.5 rounded font-mono font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400 text-[11px]">KREDIT</span>
                                @endif
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
                                    <x-action-button type="edit" :click="'openEdit(' . json_encode($item) . ')'" title="Edit Akun" />
                                    <form method="POST" action="{{ route('fa.coa.destroy', $item->id) }}" onsubmit="return confirm('Hapus akun CoA {{ $item->code }} - {{ $item->name }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <x-action-button type="delete" title="Hapus Akun" />
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                Belum ada data Akun Chart of Accounts yang tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($accounts->hasPages())
        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
            {{ $accounts->links() }}
        </div>
        @endif
    </x-data-card>

    <!-- Modal Form Tambah / Edit CoA -->
    <div x-show="showModal" x-transition class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
        <div @click.away="showModal = false" class="bg-white dark:bg-slate-900 w-full max-w-xl rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-base font-black text-slate-800 dark:text-slate-100" x-text="editMode ? 'Edit Akun CoA' : 'Tambah Akun CoA Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form :action="editMode ? '{{ url('dashboard/fa/coa') }}/' + form.id : '{{ route('fa.coa.store') }}'" method="POST" class="p-6 space-y-4 overflow-y-auto">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Account Group <span class="text-rose-500">*</span></label>
                        <select name="account_group_id" x-model="form.account_group_id" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-bold">
                            @foreach($accountGroups as $grp)
                                <option value="{{ $grp->id }}">{{ $grp->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Akun Induk (Parent)</label>
                        <select name="parent_id" x-model="form.parent_id"
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-semibold">
                            <option value="">— Tidak Ada (Header Utama Level 1) —</option>
                            @foreach($headerAccounts as $hdr)
                                <option value="{{ $hdr->id }}">{{ $hdr->code }} - {{ $hdr->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Kode Akun <span class="text-rose-500">*</span></label>
                        <input type="text" name="code" x-model="form.code" required placeholder="Contoh: 11201"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-mono font-bold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Level Hierarki <span class="text-rose-500">*</span></label>
                        <select name="level" x-model="form.level" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-bold">
                            <option value="1">Level 1 (Main Header)</option>
                            <option value="2">Level 2 (Sub Header / Akun)</option>
                            <option value="3">Level 3 (Detail Transaksi)</option>
                            <option value="4">Level 4 (Sub Detail)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Saldo Normal <span class="text-rose-500">*</span></label>
                        <select name="normal_balance" x-model="form.normal_balance" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-bold">
                            <option value="debit">Debit (D)</option>
                            <option value="credit">Kredit (K)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Akun Buku Besar <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" x-model="form.name" required placeholder="Contoh: Bank Mandiri Rekening Operasional"
                           class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-bold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Deskripsi / Peruntukan Akun</label>
                    <textarea name="description" x-model="form.description" rows="2" placeholder="Keterangan transaksi akun ini..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500"></textarea>
                </div>

                <div class="p-4 bg-slate-50 dark:bg-slate-950/60 rounded-xl border border-slate-200/80 dark:border-slate-800/80 space-y-2">
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="headerToggle" name="is_header" value="1" x-model="form.is_header"
                               class="w-4 h-4 text-amber-600 rounded border-slate-300 focus:ring-amber-500 cursor-pointer">
                        <label for="headerToggle" class="text-xs font-bold text-slate-800 dark:text-slate-200 cursor-pointer">Jadikan Akun Induk (Header / Tidak untuk Posting Jurnal Langsung)</label>
                    </div>

                    <div class="flex items-center space-x-2 pt-1">
                        <input type="checkbox" id="coaActiveToggle" name="active" value="1" x-model="form.active"
                               class="w-4 h-4 text-primary rounded border-slate-300 focus:ring-primary cursor-pointer">
                        <label for="coaActiveToggle" class="text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer">Status Akun Aktif</label>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end space-x-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-bold text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-md shadow-primary transition cursor-pointer" x-text="editMode ? 'Simpan Perubahan' : 'Tambah Akun CoA'"></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal View Detail CoA -->
    <div x-show="showDetailModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="showDetailModal = false"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xl p-6 overflow-hidden">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-1 rounded-lg bg-primary-light text-primary font-mono font-black text-xs" x-text="detailItem.code"></span>
                        <h3 class="text-base font-black text-slate-800 dark:text-slate-100">Detail Chart of Account</h3>
                    </div>
                    <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4 text-xs">
                    <div>
                        <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Nama Akun</span>
                        <p class="text-base font-black text-slate-800 dark:text-slate-100 mt-0.5" x-text="detailItem.name"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-slate-800/50 p-3.5 rounded-xl border border-slate-100 dark:border-slate-800">
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Account Group</span>
                            <p class="font-bold text-slate-800 dark:text-slate-200 mt-0.5" x-text="detailItem.account_group ? detailItem.account_group.name : '-'"></p>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Saldo Normal</span>
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase inline-block mt-1"
                                  :class="detailItem.normal_balance === 'debit' ? 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800' : 'bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 border border-purple-200 dark:border-purple-800'"
                                  x-text="detailItem.normal_balance === 'debit' ? 'DEBIT (D)' : 'KREDIT (K)'"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Tipe Akun / Level</span>
                            <template x-if="detailItem.is_header">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800 inline-block mt-1"
                                      x-text="'HEADER (Level ' + detailItem.level + ')'"></span>
                            </template>
                            <template x-if="!detailItem.is_header">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 inline-block mt-1"
                                      x-text="'POSTING (Level ' + detailItem.level + ')'"></span>
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
                        <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Deskripsi / Keterangan Transaksi</span>
                        <p class="text-slate-700 dark:text-slate-300 mt-0.5 leading-relaxed bg-slate-50 dark:bg-slate-800/30 p-3 rounded-xl border border-slate-100 dark:border-slate-800" x-text="detailItem.description || 'Tidak ada keterangan transaksi.'"></p>
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

