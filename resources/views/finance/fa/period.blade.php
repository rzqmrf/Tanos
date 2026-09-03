@extends('layouts.app')

@section('title', 'Fiscal Period Management — Tanos ERP')

@section('content')
<div class="space-y-6" x-data="{
    showModal: false,
    showDetailModal: false,
    detailItem: {},
    form: { year: {{ $year }}, month: {{ (int) date('n') }}, status: 'Open' },
    openCreate() {
        this.form = { year: {{ $year }}, month: {{ (int) date('n') }}, status: 'Open' };
        this.showModal = true;
    },
    openDetail(item) {
        this.detailItem = item;
        this.showDetailModal = true;
    }
}">
    <x-page-header 
        title="Fiscal Period Management" 
        subtitle="Buka / Tutup buku periode akuntansi bulanan untuk memvalidasi transaksi jurnal & posting payroll."
        :breadcrumbs="[
            'General' => '#',
            'Master FA' => '#',
            'Fiscal Period' => ''
        ]"
    >
        <x-slot:action>
            <div class="flex items-center space-x-2">
                <!-- Year Selector -->
                <form method="GET" action="{{ route('fa.period') }}" class="flex items-center space-x-2">
                    <select name="year" onchange="this.form.submit()"
                            class="px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 font-bold text-xs rounded-xl focus:outline-none cursor-pointer shadow-xs">
                        @foreach($availableYears as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Tahun Buku {{ $y }}</option>
                        @endforeach
                    </select>
                </form>

                <button @click="openCreate()"
                        class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 self-start sm:self-auto cursor-pointer border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Set Periode</span>
                </button>
            </div>
        </x-slot:action>
    </x-page-header>

    <!-- Alert Notifications -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <x-data-card 
        title="Fiscal Period - List" 
        :total="count($periods)"
        :show-per-page="false"
        :show-search="false"
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-5 text-center">Bulan #</th>
                        <th class="py-3.5 px-5">Nama Periode</th>
                        <th class="py-3.5 px-5">Rentang Tanggal</th>
                        <th class="py-3.5 px-5 text-center">Status Periode</th>
                        <th class="py-3.5 px-5">Tanggal Tutup Buku</th>
                        <th class="py-3.5 px-5 text-center">Aksi / Kontrol</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($periods as $item)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                        <td class="py-3.5 px-5 text-center">
                            <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 font-mono font-black text-slate-700 dark:text-slate-300 rounded-lg text-xs">
                                {{ sprintf('%02d', $item->month) }}
                            </span>
                        </td>
                        <td class="py-3.5 px-5 font-bold text-slate-800 dark:text-slate-100">
                            <button @click="openDetail({{ json_encode($item) }})" class="hover:text-primary transition cursor-pointer text-left">
                                {{ $item->period_name }}
                            </button>
                        </td>
                        <td class="py-3.5 px-5 font-mono text-slate-600 dark:text-slate-400">
                            {{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }}
                        </td>
                        <td class="py-3.5 px-5 text-center">
                            @if($item->status === 'Open')
                                <span class="px-2.5 py-1 text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 rounded-lg inline-flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>OPEN (BISA JURNAL)
                                </span>
                            @else
                                <span class="px-2.5 py-1 text-[10px] font-bold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800 rounded-lg inline-flex items-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>CLOSED (TUTUP BUKU)
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 font-mono text-slate-500 dark:text-slate-400">
                            {{ $item->closed_at ? \Carbon\Carbon::parse($item->closed_at)->format('d M Y H:i') : '—' }}
                        </td>
                        <td class="py-3.5 px-5 text-center whitespace-nowrap">
                            <div class="inline-flex items-center space-x-2">
                                <x-action-button type="view" :click="'openDetail(' . json_encode($item) . ')'" title="Lihat Detail" />

                                <form action="{{ route('fa.period.toggle-status', $item->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm(&quot;Apakah Anda yakin ingin {{ $item->status === 'Open' ? 'MENUTUP' : 'MEMBUKA KEMBALI' }} periode {{ $item->period_name }}?&quot;)">
                                    @csrf
                                    @if($item->status === 'Open')
                                    <button type="submit" 
                                            style="background-color: #ff1744; color: #ffffff;"
                                            class="px-3 py-1.5 hover:opacity-90 font-bold rounded-lg text-xs transition shadow-2xs cursor-pointer">
                                        Tutup Periode
                                    </button>
                                    @else
                                    <button type="submit" 
                                            style="background-color: #00c853; color: #ffffff;"
                                            class="px-3 py-1.5 hover:opacity-90 font-bold rounded-lg text-xs transition shadow-2xs cursor-pointer">
                                        Buka Periode
                                    </button>
                                    @endif
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            Belum ada konfigurasi periode untuk tahun {{ $year }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-data-card>

    <!-- Modal View Detail -->
    <div x-show="showDetailModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="showDetailModal = false"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-lg bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xl p-6 overflow-hidden">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-1 rounded-lg bg-primary-light text-primary font-bold text-xs" x-text="'Tahun ' + detailItem.year"></span>
                        <h3 class="text-base font-black text-slate-800 dark:text-slate-100" x-text="detailItem.period_name"></h3>
                    </div>
                    <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4 text-xs">
                    <div class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-slate-800/50 p-3.5 rounded-xl border border-slate-100 dark:border-slate-800">
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Rentang Mulai</span>
                            <p class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 font-mono" x-text="detailItem.start_date"></p>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Rentang Berakhir</span>
                            <p class="font-bold text-slate-800 dark:text-slate-200 mt-0.5 font-mono" x-text="detailItem.end_date"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Status Pembukuan</span>
                            <template x-if="detailItem.status === 'Open'">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 mt-1">
                                    OPEN (BUKA)
                                </span>
                            </template>
                            <template x-if="detailItem.status === 'Closed'">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 mt-1">
                                    CLOSED (TUTUP BUKU)
                                </span>
                            </template>
                            <template x-if="detailItem.status === 'Special'">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 mt-1">
                                    SPECIAL ADJUSTMENT
                                </span>
                            </template>
                        </div>

                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Bulan Buku</span>
                            <p class="font-bold text-slate-800 dark:text-slate-200 mt-1" x-text="'Bulan ke-' + detailItem.month"></p>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-slate-100 dark:border-slate-800 text-[11px] text-slate-400 flex justify-between">
                        <span>Waktu Ditutup: <span class="font-mono" x-text="detailItem.closed_at ? new Date(detailItem.closed_at).toLocaleString('id-ID') : '-'"></span></span>
                        <span>Diupdate: <span class="font-mono" x-text="detailItem.updated_at ? new Date(detailItem.updated_at).toLocaleString('id-ID') : '-'"></span></span>
                    </div>
                </div>

                <div class="flex justify-end space-x-2 pt-4 mt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showDetailModal = false"
                            class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Set Periode -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" @click="showModal = false"></div>

        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-2xl p-6 overflow-hidden">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-base font-black text-slate-800 dark:text-slate-100">Konfigurasi Periode Pembukuan</h3>
                    <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form action="{{ route('fa.period.store') }}" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tahun *</label>
                            <input type="number" name="year" x-model="form.year" required min="2020" max="2050"
                                   class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Bulan *</label>
                            <select name="month" x-model="form.month" required
                                    class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                <option value="1">01 - Januari</option>
                                <option value="2">02 - Februari</option>
                                <option value="3">03 - Maret</option>
                                <option value="4">04 - April</option>
                                <option value="5">05 - Mei</option>
                                <option value="6">06 - Juni</option>
                                <option value="7">07 - Juli</option>
                                <option value="8">08 - Agustus</option>
                                <option value="9">09 - September</option>
                                <option value="10">10 - Oktober</option>
                                <option value="11">11 - November</option>
                                <option value="12">12 - Desember</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Status Awal *</label>
                        <select name="status" x-model="form.status" required
                                class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                            <option value="Open">Open (Diizinkan Transaksi)</option>
                            <option value="Closed">Closed (Tutup Buku)</option>
                            <option value="Special">Special Adjustment</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="showModal = false"
                                class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-md shadow-primary transition cursor-pointer">
                            Simpan Periode
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

