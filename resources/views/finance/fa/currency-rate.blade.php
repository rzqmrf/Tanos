@extends('layouts.app')

@section('title', 'Currency Exchange Rates — Tanos ERP')

@section('content')
<div class="space-y-6" x-data="{
    showModal: false,
    showDetailModal: false,
    editMode: false,
    detailItem: {},
    form: { id: null, currency_id: '', rate_to_idr: '', effective_date: '{{ date('Y-m-d') }}', source: 'Bank Indonesia', notes: '' },
    openCreate() {
        this.editMode = false;
        this.form = { id: null, currency_id: '', rate_to_idr: '', effective_date: '{{ date('Y-m-d') }}', source: 'Bank Indonesia', notes: '' };
        this.showModal = true;
    },
    openEdit(item) {
        this.editMode = true;
        this.form = {
            id: item.id,
            currency_id: item.currency_id,
            rate_to_idr: item.rate_to_idr,
            effective_date: item.effective_date,
            source: item.source || 'Bank Indonesia',
            notes: item.notes || ''
        };
        this.showDetailModal = false;
        this.showModal = true;
    },
    openDetail(item) {
        this.detailItem = item;
        this.showDetailModal = true;
    }
}">    <!-- Page Header & Action -->
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
                <a href="{{ route('fa.currency') }}" class="hover:text-primary dark:hover:text-sky-400 transition">Currency</a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-primary dark:text-sky-400 font-black">Exchange Rates</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                Exchange Rates (Kurs Mata Uang)
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 font-medium">Riwayat dan pembaruan kurs konversi mata uang asing (Valas) ke Rupiah (IDR).</p>
        </div>

        <div class="flex items-center space-x-2">
            <a href="{{ route('fa.currency') }}"
               class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 shadow-xs cursor-pointer">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Master Mata Uang</span>
            </a>

            <button @click="openCreate()"
                    class="px-4 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center space-x-1.5 self-start sm:self-auto cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Update Kurs Harian</span>
            </button>
        </div>
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

    <!-- Filter & Search Bar -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
        <form method="GET" action="{{ route('fa.currency-rate') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari kode valas, nama, atau sumber kurs..."
                       class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/20">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="currency_id" class="px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:outline-none">
                <option value="">Semua Valuta Asing</option>
                @foreach($currencies as $c)
                    <option value="{{ $c->id }}" {{ request('currency_id') == $c->id ? 'selected' : '' }}>{{ $c->code }} - {{ $c->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl transition cursor-pointer">
                Filter
            </button>

            @if(request()->hasAny(['search', 'currency_id']))
            <a href="{{ route('fa.currency-rate') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-xl transition flex items-center justify-center">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200/80 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-4 px-5">Mata Uang (Valas)</th>
                        <th class="py-4 px-5">Tanggal Efektif</th>
                        <th class="py-4 px-5 text-right">Nilai Kurs ke IDR</th>
                        <th class="py-4 px-5">Sumber Kurs</th>
                        <th class="py-4 px-5">Catatan</th>
                        <th class="py-4 px-5 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($rates as $item)
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                        <td class="py-3.5 px-5">
                            <button @click="openDetail({{ $item }})" class="flex items-center space-x-2 text-left hover:opacity-80 transition cursor-pointer">
                                <span class="px-2 py-1 bg-primary-light text-primary font-mono font-black rounded-lg text-xs">
                                    {{ $item->currency?->code ?? '-' }}
                                </span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $item->currency?->name }}</span>
                            </button>
                        </td>
                        <td class="py-3.5 px-5 font-medium text-slate-700 dark:text-slate-300">
                            {{ \Carbon\Carbon::parse($item->effective_date)->format('d F Y') }}
                        </td>
                        <td class="py-3.5 px-5 text-right font-mono font-black text-emerald-600 dark:text-emerald-400 text-sm">
                            Rp {{ number_format($item->rate_to_idr, 2, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                {{ $item->source }}
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-slate-500 dark:text-slate-400 text-[11px]">
                            {{ $item->notes ?? '-' }}
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
                                <form action="{{ route('fa.currency-rate.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus Kurs ini?')">
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
                        <td colspan="6" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            Tidak ada data kurs yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rates->hasPages())
        <div class="p-4 border-t border-slate-100 dark:border-slate-800">
            {{ $rates->links() }}
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
                        <span class="px-2.5 py-1 rounded-lg bg-primary-light text-primary font-mono font-black text-xs" x-text="detailItem.currency ? detailItem.currency.code : '-'"></span>
                        <h3 class="text-base font-black text-slate-800 dark:text-slate-100">Detail Kurs Mata Uang</h3>
                    </div>
                    <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4 text-xs">
                    <div>
                        <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Nilai Kurs ke IDR</span>
                        <p class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-0.5 font-mono"
                           x-text="'Rp ' + (detailItem.rate_to_idr ? Number(detailItem.rate_to_idr).toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 4}) : '0,00')"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-slate-800/50 p-3.5 rounded-xl border border-slate-100 dark:border-slate-800">
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Tanggal Efektif</span>
                            <p class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 font-mono" x-text="detailItem.effective_date"></p>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Sumber Kurs</span>
                            <p class="font-bold text-slate-800 dark:text-slate-200 mt-0.5" x-text="detailItem.source || 'Bank Indonesia'"></p>
                        </div>
                    </div>

                    <div>
                        <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Catatan / Referensi</span>
                        <p class="text-slate-700 dark:text-slate-300 mt-0.5 leading-relaxed bg-slate-50 dark:bg-slate-800/30 p-3 rounded-xl border border-slate-100 dark:border-slate-800" x-text="detailItem.notes || 'Tidak ada catatan tambahan.'"></p>
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
            <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xl p-6 overflow-hidden">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-black text-slate-800 dark:text-slate-100" x-text="editMode ? 'Edit Kurs Mata Uang' : 'Input Kurs Mata Uang Baru'"></h3>
                    <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="editMode ? '{{ url('fa/currency-rate') }}/' + form.id : '{{ route('fa.currency-rate.store') }}'" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Mata Uang (Valas) *</label>
                        <select name="currency_id" x-model="form.currency_id" required
                                class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                            <option value="">Pilih Mata Uang</option>
                            @foreach($currencies as $c)
                                <option value="{{ $c->id }}">{{ $c->code }} - {{ $c->name }} ({{ $c->symbol }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nilai Kurs ke IDR *</label>
                            <input type="number" step="0.0001" name="rate_to_idr" x-model="form.rate_to_idr" required placeholder="Contoh: 16350.00"
                                   class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-mono font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tanggal Efektif *</label>
                            <input type="date" name="effective_date" x-model="form.effective_date" required
                                   class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Sumber Kurs *</label>
                        <input type="text" name="source" x-model="form.source" required placeholder="Contoh: Bank Indonesia / Pajak Kemenkeu"
                               class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Catatan</label>
                        <textarea name="notes" x-model="form.notes" rows="2" placeholder="Catatan acuan kurs..."
                                  class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none"></textarea>
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

