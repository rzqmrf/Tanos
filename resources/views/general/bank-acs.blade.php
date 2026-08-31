@extends('layouts.app')

@section('title', 'Bank ACS Customer Master — General Master TANOS ERP')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: false, 
    editMode: false, 
    form: { 
        id: null, 
        partner_id: '{{ $partners->first()->id ?? '' }}', 
        bank_name: 'Bank Mandiri', 
        account_number: '', 
        account_holder: '', 
        branch: 'Kantor Cabang Utama', 
        is_primary: true, 
        active: true 
    },
    openCreate() {
        this.editMode = false;
        this.form = { 
            id: null, 
            partner_id: '{{ $partners->first()->id ?? '' }}', 
            bank_name: 'Bank Mandiri', 
            account_number: '', 
            account_holder: '', 
            branch: 'Kantor Cabang Utama', 
            is_primary: false, 
            active: true 
        };
        this.showModal = true;
    },
    openEdit(item) {
        this.editMode = true;
        this.form = { 
            id: item.id, 
            partner_id: item.partner_id, 
            bank_name: item.bank_name, 
            account_number: item.account_number, 
            account_holder: item.account_holder, 
            branch: item.branch || 'Kantor Cabang Utama', 
            is_primary: !!item.is_primary, 
            active: !!item.active 
        };
        this.showModal = true;
    }
}">

    <!-- Page Header & Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
        <div>
            <div class="flex items-center space-x-2 text-xs font-bold text-[#100b60] dark:text-blue-400 uppercase tracking-wider mb-1">
                <span>General Master</span>
                <span>•</span>
                <span>Banking & ACS</span>
            </div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight flex items-center">
                <span>Bank ACS Customer / Partner</span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Rekening Bank Auto Collection System (ACS) dan rekening tujuan kliring penagihan billing invoice.</p>
        </div>

        <button @click="openCreate()"
                class="px-5 py-2.5 bg-[#100b60] hover:bg-[#0c084d] text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-950/20 transition flex items-center space-x-2 self-start sm:self-auto cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tambah Rekening Bank</span>
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
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Total Rekening</span>
                <span class="text-xl font-black text-slate-800 dark:text-slate-100">{{ $totalAccounts }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Rekening Utama</span>
                <span class="text-xl font-black text-emerald-600 dark:text-emerald-400">{{ $primaryAccounts }}</span>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div>
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Fitur ACS</span>
                <span class="text-sm font-bold text-purple-600 dark:text-purple-400">Host-to-Host Virtual Account</span>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('general.bank-acs') }}" class="flex items-center space-x-2 flex-1 max-w-md">
            <div class="relative w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama bank, no rekening, nama pemilik, mitra..."
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-medium">
                <div class="absolute left-3 top-2.5 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-[#100b60] text-white rounded-xl text-xs font-bold hover:bg-[#0c084d] transition cursor-pointer">Cari</button>
            @if(request('search'))
                <a href="{{ route('general.bank-acs') }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-200 transition">Reset</a>
            @endif
        </form>

        <div class="flex items-center space-x-2">
            <a href="{{ route('general.partner') }}" class="px-3.5 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition flex items-center space-x-1.5">
                <span>Daftar Mitraniaga</span>
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
                        <th class="py-3.5 px-4">Mitra Pemilik</th>
                        <th class="py-3.5 px-4">Nama Bank</th>
                        <th class="py-3.5 px-4">Nomor Rekening</th>
                        <th class="py-3.5 px-4">Atas Nama (Holder)</th>
                        <th class="py-3.5 px-4">Kantor Cabang</th>
                        <th class="py-3.5 px-4 text-center">Tipe Rekening</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($bankAccounts as $index => $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                            <td class="py-3.5 px-4 text-slate-400 font-semibold">{{ $bankAccounts->firstItem() + $index }}</td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800 dark:text-slate-100 block">{{ $item->partner?->name ?? '—' }}</span>
                                <span class="text-[10px] font-mono font-semibold text-blue-600 dark:text-blue-400 block">{{ $item->partner?->code ?? '' }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-slate-200">
                                {{ $item->bank_name }}
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-[#100b60] dark:text-blue-300">
                                {{ $item->account_number }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-700 dark:text-slate-300 font-semibold">
                                {{ $item->account_holder }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400">
                                {{ $item->branch ?? 'Cabang Utama' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($item->is_primary)
                                    <span class="px-2.5 py-1 text-[10px] font-extrabold bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800 rounded-lg inline-flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 mr-1">
                                            <path fill-rule="evenodd" d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z" clip-rule="evenodd" />
                                        </svg>
                                        Utama (Primary)
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-lg inline-flex items-center">
                                        Sekunder
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end space-x-1.5">
                                    <button @click="openEdit({{ json_encode($item) }})"
                                            class="p-1.5 text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/40 rounded-lg transition cursor-pointer" title="Edit Rekening">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>

                                    <form method="POST" action="{{ route('general.bank-acs.destroy', $item->id) }}" onsubmit="return confirm('Hapus data rekening {{ $item->account_number }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition cursor-pointer" title="Hapus Rekening">
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
                                Belum ada data rekening Bank ACS yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bankAccounts->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                {{ $bankAccounts->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form Tambah / Edit Rekening -->
    <div x-show="showModal" x-transition class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
        <div @click.away="showModal = false" class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-base font-black text-slate-800 dark:text-slate-100" x-text="editMode ? 'Edit Rekening Bank ACS' : 'Tambah Rekening Bank ACS Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form :action="editMode ? '{{ url('dashboard/general/bank-acs') }}/' + form.id : '{{ route('general.bank-acs.store') }}'" method="POST" class="p-6 space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Mitraniaga Pemilik <span class="text-rose-500">*</span></label>
                    <select name="partner_id" x-model="form.partner_id" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-bold">
                        @foreach($partners as $partner)
                            <option value="{{ $partner->id }}">{{ $partner->name }} ({{ $partner->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Bank <span class="text-rose-500">*</span></label>
                        <input type="text" name="bank_name" x-model="form.bank_name" required placeholder="Mandiri / BNI / BRI / BCA"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-bold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nomor Rekening <span class="text-rose-500">*</span></label>
                        <input type="text" name="account_number" x-model="form.account_number" required placeholder="Contoh: 120-00-1234567-8"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-mono font-bold">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Atas Nama Rekening <span class="text-rose-500">*</span></label>
                        <input type="text" name="account_holder" x-model="form.account_holder" required placeholder="Nama pemilik rekening sesuai buku tabungan"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Kantor Cabang</label>
                        <input type="text" name="branch" x-model="form.branch" placeholder="KCU Tanjung Priok"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <input type="checkbox" id="primaryToggle" name="is_primary" value="1" x-model="form.is_primary"
                           class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 cursor-pointer">
                    <label for="primaryToggle" class="text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer">Jadikan Rekening Penampungan Utama (Primary)</label>
                </div>

                <div class="pt-4 flex items-center justify-end space-x-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-bold text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#100b60] hover:bg-[#0c084d] text-white text-xs font-bold rounded-xl shadow-md transition cursor-pointer" x-text="editMode ? 'Simpan Perubahan' : 'Tambah Rekening'"></button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
