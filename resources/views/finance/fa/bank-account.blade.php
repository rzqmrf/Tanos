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
    <x-page-header 
        title="Company Bank Accounts" 
        subtitle="Master rekening bank kas operasional, penerimaan invoice billing, payroll, dan pemetaan akun buku besar (GL CoA)."
        :breadcrumbs="[
            'General' => '#',
            'Master FA' => '#',
            'Bank Account' => ''
        ]"
        create-label="Tambah Rekening Bank"
        create-click="openCreate()"
    />

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

    <!-- Data Table Container matching Golden Benchmark -->
    <x-data-card 
        title="Bank Accounts - List" 
        :total="$bankAccounts->total()"
        :search-route="route('fa.bank-account')"
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-5">Nama Bank</th>
                        <th class="py-3.5 px-5">Nomor Rekening</th>
                        <th class="py-3.5 px-5">Atas Nama (Holder)</th>
                        <th class="py-3.5 px-5">Cabang & Valas</th>
                        <th class="py-3.5 px-5">Akun GL CoA Induk</th>
                        <th class="py-3.5 px-5 text-center">Rekening Utama</th>
                        <th class="py-3.5 px-5 text-center">Status</th>
                        <th class="py-3.5 px-5 text-center w-28">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($bankAccounts as $item)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                        <td class="py-3.5 px-5 font-bold text-slate-800 dark:text-slate-100">
                            <button @click="openDetail({{ json_encode($item) }})" class="hover:text-primary transition cursor-pointer text-left">
                                {{ $item->bank_name }}
                            </button>
                        </td>
                        <td class="py-3.5 px-5 font-mono font-bold text-primary">
                            <button @click="openDetail({{ json_encode($item) }})" class="px-2.5 py-1 bg-primary-light text-primary font-mono font-bold rounded-md text-[11px] border border-primary-subtle hover:opacity-80 transition cursor-pointer">
                                {{ $item->account_number }}
                            </button>
                        </td>
                        <td class="py-3.5 px-5 font-bold text-slate-700 dark:text-slate-300">{{ $item->account_holder }}</td>
                        <td class="py-3.5 px-5">
                            <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $item->branch ?? 'Pusat' }}</span>
                            <span class="px-2 py-0.5 ml-1 bg-slate-100 dark:bg-slate-800 font-mono text-[10px] font-bold text-slate-600 dark:text-slate-400 rounded">{{ $item->currency }}</span>
                        </td>
                        <td class="py-3.5 px-5">
                            @if($item->chartOfAccount)
                            <span class="px-2.5 py-1 bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-400 font-bold rounded-lg text-[11px] border border-sky-200 dark:border-sky-800/50 inline-flex items-center">
                                {{ $item->chartOfAccount->code }} - {{ $item->chartOfAccount->name }}
                            </span>
                            @else
                            <span class="text-slate-400 font-normal">—</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 text-center">
                            @if($item->is_primary)
                            <span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 font-bold rounded-lg text-[11px] border border-amber-200 dark:border-amber-800/50 inline-flex items-center">
                                Utama (Default)
                            </span>
                            @else
                            <span class="text-slate-400 font-normal">—</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 text-center">
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
                        <td class="py-3.5 px-5 text-center whitespace-nowrap">
                            <div class="inline-flex items-center space-x-1.5">
                                <x-action-button type="view" :click="'openDetail(' . json_encode($item) . ')'" title="Lihat Detail" />
                                <x-action-button type="edit" :click="'openEdit(' . json_encode($item) . ')'" title="Edit Rekening" />
                                <form action="{{ route('fa.bank-account.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus Rekening {{ $item->bank_name }} - {{ $item->account_number }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-action-button type="delete" title="Hapus Rekening" />
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
        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
            {{ $bankAccounts->links() }}
        </div>
        @endif
    </x-data-card>

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

