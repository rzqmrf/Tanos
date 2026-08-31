@extends('layouts.app')

@section('title', 'Chart of Accounts (CoA) — Finance & Accounting TANOS ERP')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: false, 
    editMode: false, 
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
        this.showModal = true;
    }
}">

    <!-- Page Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-[#100b60] dark:text-blue-400 uppercase tracking-wider mb-1">
                <span>Finance & Accounting</span>
                <span>•</span>
                <span>Bagan Akun Standar</span>
            </div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight flex items-center">
                <span>Chart of Accounts (CoA) Master</span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Struktur bagan akun hierarkis terpadu untuk integrasi jurnal voucher, posting payroll, dan sinkronisasi SAP General Ledger.</p>
        </div>

        <button @click="openCreate()"
                class="px-5 py-2.5 bg-[#100b60] hover:bg-[#0c084d] text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-950/20 transition flex items-center space-x-2 self-start sm:self-auto cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tambah Akun CoA</span>
        </button>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-[#100b60] dark:text-blue-400 flex items-center justify-center font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5-3h7.5M8.25 9.75h.008v.008H8.25V9.75Z" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Total Nomor Akun</span>
                <span class="text-xl font-black text-slate-800 dark:text-slate-100">{{ $totalAll }} Akun</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
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

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
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

    <!-- Search & Filter Bar -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('fa.coa') }}" class="flex flex-wrap items-center gap-2 flex-1">
            <div class="relative min-w-[220px] flex-1 max-w-sm">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode akun atau nama CoA..."
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-medium">
                <div class="absolute left-3 top-2.5 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </div>

            <select name="account_group_id" onchange="this.form.submit()"
                    class="py-2 px-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-blue-500">
                <option value="">Semua Account Group</option>
                @foreach($accountGroups as $grp)
                    <option value="{{ $grp->id }}" {{ request('account_group_id') == $grp->id ? 'selected' : '' }}>{{ $grp->name }}</option>
                @endforeach
            </select>

            <select name="normal_balance" onchange="this.form.submit()"
                    class="py-2 px-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-blue-500">
                <option value="">Semua Pos Saldo</option>
                <option value="debit" {{ request('normal_balance') == 'debit' ? 'selected' : '' }}>Saldo Normal: Debit</option>
                <option value="credit" {{ request('normal_balance') == 'credit' ? 'selected' : '' }}>Saldo Normal: Kredit</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-[#100b60] text-white rounded-xl text-xs font-bold hover:bg-[#0c084d] transition cursor-pointer">Filter</button>
            @if(request('search') || request('account_group_id') || request('normal_balance'))
                <a href="{{ route('fa.coa') }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-200 transition">Reset</a>
            @endif
        </form>

        <div class="flex items-center space-x-2">
            <a href="{{ route('fa.account-group') }}" class="px-3.5 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition flex items-center space-x-1.5">
                <span>Account Groups</span>
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
                        <th class="py-3.5 px-4">Kode Akun</th>
                        <th class="py-3.5 px-4">Nama Akun Buku Besar (CoA)</th>
                        <th class="py-3.5 px-4">Account Group</th>
                        <th class="py-3.5 px-4 text-center">Tipe / Level</th>
                        <th class="py-3.5 px-4 text-center">Saldo Normal</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($accounts as $index => $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition {{ $item->is_header ? 'bg-slate-50/40 dark:bg-slate-800/30 font-bold' : '' }}">
                            <td class="py-3.5 px-4 text-slate-400 font-semibold">{{ $accounts->firstItem() + $index }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 font-mono font-bold rounded-md text-[11px] border
                                    {{ $item->is_header ? 'bg-slate-200/80 dark:bg-slate-800 text-slate-800 dark:text-slate-100 border-slate-300 dark:border-slate-700' : 'bg-blue-50 dark:bg-blue-950/50 text-[#100b60] dark:text-blue-300 border-blue-100 dark:border-blue-900' }}">
                                    {{ $item->code }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-2" style="padding-left: {{ ($item->level - 1) * 1.25 }}rem;">
                                    @if($item->level > 1)
                                        <span class="text-slate-400 font-mono text-[10px]">└─</span>
                                    @endif
                                    <span class="{{ $item->is_header ? 'text-slate-900 dark:text-white font-extrabold text-sm' : 'text-slate-800 dark:text-slate-200 font-semibold' }}">
                                        {{ $item->name }}
                                    </span>
                                </div>
                                @if($item->description)
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 block mt-0.5" style="padding-left: {{ ($item->level - 1) * 1.25 }}rem;">
                                        {{ $item->description }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 dark:text-slate-400">
                                <span class="text-[11px] font-semibold">{{ $item->accountGroup?->name ?? '—' }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($item->is_header)
                                    <span class="px-2 py-0.5 text-[10px] font-extrabold bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800 rounded-md">
                                        HEADER (Lvl {{ $item->level }})
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-md">
                                        POSTING (Lvl {{ $item->level }})
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($item->normal_balance === 'debit')
                                    <span class="px-2.5 py-0.5 text-[10px] font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800 rounded-lg">
                                        DEBIT (D)
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 text-[10px] font-bold bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-400 border border-purple-200 dark:border-purple-800 rounded-lg">
                                        KREDIT (K)
                                    </span>
                                @endif
                            </td>
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
                                            class="p-1.5 text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/40 rounded-lg transition cursor-pointer" title="Edit Akun">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>

                                    <form method="POST" action="{{ route('fa.coa.destroy', $item->id) }}" onsubmit="return confirm('Hapus Akun CoA {{ $item->code }} - {{ $item->name }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition cursor-pointer" title="Hapus Akun">
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
                                Belum ada data Akun Chart of Accounts yang tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($accounts->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                {{ $accounts->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form Tambah / Edit CoA -->
    <div x-show="showModal" x-transition class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
        <div @click.away="showModal = false" class="bg-white dark:bg-slate-900 w-full max-w-xl rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
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

                <div class="p-4 bg-slate-50 dark:bg-slate-950/60 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 space-y-2">
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" id="headerToggle" name="is_header" value="1" x-model="form.is_header"
                               class="w-4 h-4 text-amber-600 rounded border-slate-300 focus:ring-amber-500 cursor-pointer">
                        <label for="headerToggle" class="text-xs font-bold text-slate-800 dark:text-slate-200 cursor-pointer">Jadikan Akun Induk (Header / Tidak untuk Posting Jurnal Langsung)</label>
                    </div>

                    <div class="flex items-center space-x-2 pt-1">
                        <input type="checkbox" id="coaActiveToggle" name="active" value="1" x-model="form.active"
                               class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 cursor-pointer">
                        <label for="coaActiveToggle" class="text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer">Status Akun Aktif</label>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-end space-x-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-bold text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#100b60] hover:bg-[#0c084d] text-white text-xs font-bold rounded-xl shadow-md transition cursor-pointer" x-text="editMode ? 'Simpan Perubahan' : 'Tambah Akun CoA'"></button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
