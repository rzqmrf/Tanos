@extends('layouts.app')

@section('title', 'Company Bank Accounts — Tanos ERP')

@section('content')
<div class="space-y-6" x-data="{
    showModal: false,
    showDetailModal: false,
    editMode: false,
    detailItem: {},
    form: { id: null, bank_name: '', account_number: '', account_holder: '', branch: '', currency: 'IDR', chart_of_account_id: '', is_primary: false, active: true },
    openCreate() {
        this.editMode = false;
        this.form = { id: null, bank_name: '', account_number: '', account_holder: '', branch: '', currency: 'IDR', chart_of_account_id: '', is_primary: false, active: true };
        this.showModal = true;
    },
    openEdit(item) {
        this.editMode = true;
        this.form = {
            id: item.id,
            bank_name: item.bank_name,
            account_number: item.account_number,
            account_holder: item.account_holder,
            branch: item.branch || '',
            currency: item.currency || 'IDR',
            chart_of_account_id: item.chart_of_account_id || '',
            is_primary: !!item.is_primary,
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

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
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
                <span>Master FA</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-primary dark:text-sky-400 font-black">Bank Account</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                Company Bank Accounts
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 font-medium">Master rekening bank kas operasional, penerimaan invoice billing, payroll, dan pemetaan akun buku besar (GL CoA).</p>
        </div>

        <button @click="openCreate()"
                class="px-4 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center space-x-1.5 self-start sm:self-auto cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tambah Rekening Bank</span>
        </button>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-rose-50 dark:bg-rose-950/30 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <!-- Data Table -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-sm">
        <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <form method="GET" action="{{ route('fa.bank-account') }}" class="flex gap-2 w-full max-w-md">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari bank, nomor rekening, pemilik..."
                       class="w-full px-3.5 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:outline-none">
                <button type="submit" class="px-4 py-1.5 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl transition cursor-pointer">
                    Cari
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200/80 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-4 px-5">Nama Bank</th>
                        <th class="py-4 px-5">Nomor Rekening</th>
                        <th class="py-4 px-5">Nama Pemilik Rekening</th>
                        <th class="py-4 px-5">Cabang</th>
                        <th class="py-4 px-5">Pemetaan GL CoA</th>
                        <th class="py-4 px-5 text-center">Tipe Rekening</th>
                        <th class="py-4 px-5 text-center">Status</th>
                        <th class="py-4 px-5 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($bankAccounts as $item)
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                        <td class="py-3.5 px-5 font-bold text-slate-800 dark:text-slate-100 flex items-center space-x-2">
                            <span class="w-7 h-7 rounded-lg bg-primary-light text-primary flex items-center justify-center font-black text-xs">
                                {{ substr($item->bank_name, 0, 1) }}
                            </span>
                            <button @click="openDetail({{ $item }})" class="text-left hover:text-primary transition cursor-pointer">
                                {{ $item->bank_name }}
                            </button>
                        </td>
                        <td class="py-3.5 px-5 font-mono font-bold text-primary">
                            <button @click="openDetail({{ $item }})" class="hover:underline cursor-pointer">
                                {{ $item->account_number }}
                            </button>
                            <span class="text-[10px] text-slate-400 font-sans block font-medium">{{ $item->currency }}</span>
                        </td>
                        <td class="py-3.5 px-5 font-medium text-slate-700 dark:text-slate-300">
                            {{ $item->account_holder }}
                        </td>
                        <td class="py-3.5 px-5 text-slate-500 dark:text-slate-400">
                            {{ $item->branch ?? '-' }}
                        </td>
                        <td class="py-3.5 px-5">
                            @if($item->chartOfAccount)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-mono font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                {{ $item->chartOfAccount->code }}
                            </span>
                            <span class="text-[10px] text-slate-400 block line-clamp-1 mt-0.5">{{ $item->chartOfAccount->name }}</span>
                            @else
                            <span class="text-slate-400 italic">Belum di-mapping</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 text-center">
                            @if($item->is_primary)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400">
                                REKENING UTAMA
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                Rekening Operasional
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 text-center">
                            @if($item->active)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400">
                                AKTIF
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                NON-AKTIF
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-1.5">
                                <!-- View Detail Button -->
                                <button @click="openDetail({{ $item }})"
                                        style="background-color: #0091ea; color: #ffffff;"
                                        class="p-2 rounded-lg hover:opacity-90 transition shadow-2xs flex items-center justify-center cursor-pointer" title="Lihat Detail">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </button>

                                <!-- Edit Button -->
                                <button @click="openEdit({{ $item }})"
                                        style="background-color: #7c4dff; color: #ffffff;"
                                        class="p-2 rounded-lg hover:opacity-90 transition shadow-2xs flex items-center justify-center cursor-pointer" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>

                                <!-- Delete Button -->
                                <form action="{{ route('fa.bank-account.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus Rekening {{ $item->bank_name }} - {{ $item->account_number }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            style="background-color: #ff1744; color: #ffffff;"
                                            class="p-2 rounded-lg hover:opacity-90 transition shadow-2xs flex items-center justify-center cursor-pointer" title="Hapus">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            Tidak ada data rekening bank perusahaan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bankAccounts->hasPages())
        <div class="p-4 border-t border-slate-100 dark:border-slate-800">
            {{ $bankAccounts->links() }}
        </div>
        @endif
    </div>

    <!-- Modal View Detail -->
    <div x-show="showDetailModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="showDetailModal = false"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xl p-6 overflow-hidden">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-1 rounded-lg bg-primary-light text-primary font-bold text-xs" x-text="detailItem.bank_name"></span>
                        <h3 class="text-base font-black text-slate-800 dark:text-slate-100">Detail Rekening Bank</h3>
                    </div>
                    <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4 text-xs">
                    <div>
                        <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Nomor Rekening Bank</span>
                        <p class="text-lg font-black text-primary font-mono mt-0.5" x-text="detailItem.account_number"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-slate-800/50 p-3.5 rounded-xl border border-slate-100 dark:border-slate-800">
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Nama Pemilik Rekening</span>
                            <p class="font-bold text-slate-800 dark:text-slate-200 mt-0.5" x-text="detailItem.account_holder"></p>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Kantor Cabang</span>
                            <p class="font-bold text-slate-800 dark:text-slate-200 mt-0.5" x-text="detailItem.branch || '-'"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Mata Uang</span>
                            <p class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 font-mono" x-text="detailItem.currency || 'IDR'"></p>
                        </div>

                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Tipe Rekening</span>
                            <template x-if="detailItem.is_primary">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 mt-1">
                                    REKENING UTAMA
                                </span>
                            </template>
                            <template x-if="!detailItem.is_primary">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 mt-1">
                                    Rekening Operasional
                                </span>
                            </template>
                        </div>
                    </div>

                    <div>
                        <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Mapping GL Akun (CoA)</span>
                        <p class="text-slate-700 dark:text-slate-300 font-mono font-semibold mt-0.5 bg-slate-50 dark:bg-slate-800/30 p-3 rounded-xl border border-slate-100 dark:border-slate-800"
                           x-text="detailItem.chart_of_account ? (detailItem.chart_of_account.code + ' - ' + detailItem.chart_of_account.name) : 'Belum dimapping ke Chart of Accounts'"></p>
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

    <!-- Modal Create / Edit -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="showModal = false"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xl p-6 overflow-hidden">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-black text-slate-800 dark:text-slate-100" x-text="editMode ? 'Edit Rekening Bank' : 'Tambah Rekening Bank Baru'"></h3>
                    <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="editMode ? '{{ url('fa/bank-account') }}/' + form.id : '{{ route('fa.bank-account.store') }}'" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Bank *</label>
                        <input type="text" name="bank_name" x-model="form.bank_name" required placeholder="Contoh: Bank Mandiri (Persero) Tbk"
                               class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor Rekening *</label>
                            <input type="text" name="account_number" x-model="form.account_number" required placeholder="Contoh: 142-00-9821882-1"
                                   class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-mono font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Mata Uang *</label>
                            <input type="text" name="currency" x-model="form.currency" required placeholder="IDR"
                                   class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-mono font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Pemilik Rekening *</label>
                        <input type="text" name="account_holder" x-model="form.account_holder" required placeholder="Contoh: PT TANOS TEKNOLOGI INTEGRASI"
                               class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kantor Cabang</label>
                            <input type="text" name="branch" x-model="form.branch" placeholder="Contoh: KCP Surabaya Basuki Rahmat"
                                   class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Mapping GL Akun (CoA)</label>
                            <select name="chart_of_account_id" x-model="form.chart_of_account_id"
                                    class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                <option value="">Pilih Akun Kas & Bank</option>
                                @foreach($coas as $coa)
                                    <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2 pt-1">
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" name="is_primary" value="1" id="bank_primary" x-model="form.is_primary"
                                   class="rounded border-slate-300 dark:border-slate-700 text-primary focus:ring-primary h-4 w-4">
                            <label for="bank_primary" class="text-xs font-bold text-slate-700 dark:text-slate-300">Set sebagai Rekening Utama Perusahaan</label>
                        </div>

                        <div class="flex items-center space-x-2">
                            <input type="checkbox" name="active" value="1" id="bank_active" x-model="form.active"
                                   class="rounded border-slate-300 dark:border-slate-700 text-primary focus:ring-primary h-4 w-4">
                            <label for="bank_active" class="text-xs font-bold text-slate-700 dark:text-slate-300">Status Aktif</label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="showModal = false"
                                class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-md shadow-primary transition cursor-pointer">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

