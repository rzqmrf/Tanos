@extends('layouts.app')

@section('title', 'Partner / Mitraniaga Master — General Master TANOS ERP')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: false, 
    editMode: false, 
    form: { 
        id: null, 
        partner_type_id: '{{ $partnerTypes->first()->id ?? '' }}', 
        code: '', 
        name: '', 
        npwp: '', 
        email: '', 
        phone: '', 
        address: '', 
        pic_name: '', 
        pic_phone: '', 
        bank_name: '', 
        bank_account_number: '', 
        bank_account_holder: '', 
        payment_terms_days: 30, 
        active: true 
    },
    openCreate() {
        this.editMode = false;
        this.form = { 
            id: null, 
            partner_type_id: '{{ $partnerTypes->first()->id ?? '' }}', 
            code: 'PRT-' + Math.floor(1000 + Math.random() * 9000), 
            name: '', 
            npwp: '', 
            email: '', 
            phone: '', 
            address: '', 
            pic_name: '', 
            pic_phone: '', 
            bank_name: 'Bank Mandiri', 
            bank_account_number: '', 
            bank_account_holder: '', 
            payment_terms_days: 30, 
            active: true 
        };
        this.showModal = true;
    },
    openEdit(item) {
        this.editMode = true;
        this.form = { 
            id: item.id, 
            partner_type_id: item.partner_type_id, 
            code: item.code, 
            name: item.name, 
            npwp: item.npwp || '', 
            email: item.email || '', 
            phone: item.phone || '', 
            address: item.address || '', 
            pic_name: item.pic_name || '', 
            pic_phone: item.pic_phone || '', 
            bank_name: item.bank_name || '', 
            bank_account_number: item.bank_account_number || '', 
            bank_account_holder: item.bank_account_holder || '', 
            payment_terms_days: item.payment_terms_days || 30, 
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
                <span>Mitraniaga & Vendor</span>
            </div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight flex items-center">
                <span>Partner / Mitraniaga Master</span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">Database rekanan bisnis, vendor alih daya, pelanggan, serta informasi penagihan & termin pembayaran.</p>
        </div>

        <button @click="openCreate()"
                class="px-5 py-2.5 bg-[#100b60] hover:bg-[#0c084d] text-white text-xs font-bold rounded-xl shadow-lg shadow-blue-950/20 transition flex items-center space-x-2 self-start sm:self-auto cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tambah Mitra Baru</span>
        </button>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Total Mitra</span>
            <span class="text-2xl font-black text-slate-800 dark:text-slate-100 mt-1 block">{{ $totalAll }}</span>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Vendor / Subkon</span>
            <span class="text-2xl font-black text-blue-600 dark:text-blue-400 mt-1 block">{{ $totalVendors }}</span>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Customer / Klien</span>
            <span class="text-2xl font-black text-amber-600 dark:text-amber-400 mt-1 block">{{ $totalCustomers }}</span>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
            <span class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block">Mitra Aktif</span>
            <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1 block">{{ $totalActive }}</span>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('general.partner') }}" class="flex flex-wrap items-center gap-2 flex-1">
            <div class="relative min-w-[240px] flex-1 max-w-md">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama mitra, kode, NPWP, PIC..."
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-medium">
                <div class="absolute left-3 top-2.5 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </div>

            <select name="partner_type_id" onchange="this.form.submit()"
                    class="py-2 px-3 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-blue-500">
                <option value="">Semua Tipe Partner</option>
                @foreach($partnerTypes as $t)
                    <option value="{{ $t->id }}" {{ request('partner_type_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-[#100b60] text-white rounded-xl text-xs font-bold hover:bg-[#0c084d] transition cursor-pointer">Filter</button>
            
            @if(request('search') || request('partner_type_id'))
                <a href="{{ route('general.partner') }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-200 transition">Reset</a>
            @endif
        </form>

        <div class="flex items-center space-x-2">
            <a href="{{ route('general.bank-acs') }}" class="px-3.5 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition flex items-center space-x-1.5">
                <span>Bank ACS Master</span>
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
                        <th class="py-3.5 px-4">Kode & NPWP</th>
                        <th class="py-3.5 px-4">Nama Mitraniaga</th>
                        <th class="py-3.5 px-4">Tipe Mitra</th>
                        <th class="py-3.5 px-4">Kontak & PIC</th>
                        <th class="py-3.5 px-4">Rekening Utama</th>
                        <th class="py-3.5 px-4 text-center">TOP (Hari)</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($partners as $index => $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                            <td class="py-3.5 px-4 text-slate-400 font-semibold">{{ $partners->firstItem() + $index }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-950/50 text-[#100b60] dark:text-blue-300 font-mono font-bold rounded text-[11px] border border-blue-100 dark:border-blue-900 block w-max mb-1">
                                    {{ $item->code }}
                                </span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 font-mono">NPWP: {{ $item->npwp ?? '—' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800 dark:text-slate-100 block">{{ $item->name }}</span>
                                <span class="text-[11px] text-slate-400 dark:text-slate-500 truncate max-w-xs block">{{ $item->address ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold uppercase
                                    {{ $item->partnerType?->code === 'VENDOR' ? 'bg-amber-50 dark:bg-amber-950/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800' : '' }}
                                    {{ $item->partnerType?->code === 'CUSTOMER' ? 'bg-blue-50 dark:bg-blue-950/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800' : '' }}
                                    {{ $item->partnerType?->code === 'BUMN' ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800' : '' }}
                                    {{ !in_array($item->partnerType?->code, ['VENDOR', 'CUSTOMER', 'BUMN']) ? 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' : '' }}">
                                    {{ $item->partnerType?->name ?? 'Unassigned' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-semibold text-slate-800 dark:text-slate-200 block">{{ $item->pic_name ?? '—' }}</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400 block">{{ $item->pic_phone ?? $item->phone ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($item->bank_name && $item->bank_account_number)
                                    <span class="font-bold text-slate-800 dark:text-slate-200 block">{{ $item->bank_name }}</span>
                                    <span class="text-[11px] font-mono text-slate-500 dark:text-slate-400 block">{{ $item->bank_account_number }}</span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-[11px]">
                                    {{ $item->payment_terms_days }} Hari
                                </span>
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
                                            class="p-1.5 text-slate-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/40 rounded-lg transition cursor-pointer" title="Edit Data Mitra">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>

                                    <form method="POST" action="{{ route('general.partner.destroy', $item->id) }}" onsubmit="return confirm('Hapus data rekanan {{ $item->name }}?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition cursor-pointer" title="Hapus Mitra">
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
                            <td colspan="9" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                Belum ada data Partner Mitraniaga yang tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($partners->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                {{ $partners->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form Tambah / Edit Mitra -->
    <div x-show="showModal" x-transition class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/60 backdrop-blur-sm flex items-center justify-center p-4" style="display: none;">
        <div @click.away="showModal = false" class="bg-white dark:bg-slate-900 w-full max-w-2xl rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-base font-black text-slate-800 dark:text-slate-100" x-text="editMode ? 'Edit Partner Mitraniaga' : 'Tambah Partner Mitraniaga Baru'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form :action="editMode ? '{{ url('dashboard/general/partner') }}/' + form.id : '{{ route('general.partner.store') }}'" method="POST" class="p-6 space-y-4 overflow-y-auto">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Tipe Partner <span class="text-rose-500">*</span></label>
                        <select name="partner_type_id" x-model="form.partner_type_id" required
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-semibold">
                            @foreach($partnerTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Kode Partner <span class="text-rose-500">*</span></label>
                        <input type="text" name="code" x-model="form.code" required placeholder="Contoh: PRT-VND01"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-mono font-bold uppercase">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Perusahaan / Mitra <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" x-model="form.name" required placeholder="PT / CV Nama Perusahaan"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-bold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nomor NPWP</label>
                        <input type="text" name="npwp" x-model="form.npwp" placeholder="00.000.000.0-000.000"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-mono">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama PIC / Kontak</label>
                        <input type="text" name="pic_name" x-model="form.pic_name" placeholder="Nama Contact Person"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">No. HP PIC</label>
                        <input type="text" name="pic_phone" x-model="form.pic_phone" placeholder="08xxxxxxxxxx"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Email Kantor</label>
                        <input type="email" name="email" x-model="form.email" placeholder="finance@mitra.com"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Telepon Kantor</label>
                        <input type="text" name="phone" x-model="form.phone" placeholder="021-xxxxxxx"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Alamat Lengkap Kantor</label>
                    <textarea name="address" x-model="form.address" rows="2" placeholder="Gedung / Jalan, Kota, Provinsi..."
                              class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500"></textarea>
                </div>

                <!-- Informasi Bank & Termin Pembayaran -->
                <div class="p-4 bg-slate-50 dark:bg-slate-950/60 rounded-2xl border border-slate-200/80 dark:border-slate-800/80 space-y-3">
                    <span class="text-xs font-extrabold text-[#100b60] dark:text-blue-400 uppercase tracking-wider block">Informasi Rekening Bank & TOP</span>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Nama Bank</label>
                            <input type="text" name="bank_name" x-model="form.bank_name" placeholder="Mandiri / BNI / BCA"
                                   class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-semibold">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">No. Rekening</label>
                            <input type="text" name="bank_account_number" x-model="form.bank_account_number" placeholder="123-456-7890"
                                   class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-mono">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-600 dark:text-slate-400 mb-1">Termin Bayar (Hari)</label>
                            <input type="number" name="payment_terms_days" x-model="form.payment_terms_days" min="0" placeholder="30"
                                   class="w-full px-3 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 font-bold">
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-2 pt-1">
                    <input type="checkbox" id="partnerActiveToggle" name="active" value="1" x-model="form.active"
                           class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500 cursor-pointer">
                    <label for="partnerActiveToggle" class="text-xs font-bold text-slate-700 dark:text-slate-300 cursor-pointer">Status Rekanan Aktif</label>
                </div>

                <div class="pt-4 flex items-center justify-end space-x-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-bold text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-[#100b60] hover:bg-[#0c084d] text-white text-xs font-bold rounded-xl shadow-md transition cursor-pointer" x-text="editMode ? 'Simpan Perubahan' : 'Tambah Mitra'"></button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
