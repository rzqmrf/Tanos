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
                <span class="text-primary dark:text-sky-400 font-black">Fiscal Period</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                Fiscal Period Management
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 font-medium">Buka / Tutup buku periode akuntansi bulanan untuk memvalidasi transaksi jurnal & posting payroll.</p>
        </div>

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
                    class="px-4 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center space-x-1.5 self-start sm:self-auto cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Set Periode</span>
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

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-lg shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Periode Terbuka (Open Transaksi)</p>
                <p class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-0.5">{{ $totalOpen }} <span class="text-xs font-normal text-slate-400">Bulan</span></p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center font-bold text-lg shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Periode Tutup Buku (Closed)</p>
                <p class="text-xl font-black text-rose-600 dark:text-rose-400 mt-0.5">{{ $totalClosed }} <span class="text-xs font-normal text-slate-400">Bulan</span></p>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200/80 dark:border-slate-800 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[11px]">
                        <th class="py-4 px-5">Bulan</th>
                        <th class="py-4 px-5">Nama Periode</th>
                        <th class="py-4 px-5">Rentang Tanggal</th>
                        <th class="py-4 px-5 text-center">Status Pembukuan</th>
                        <th class="py-4 px-5">Informasi Tutup Buku</th>
                        <th class="py-4 px-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($periods as $item)
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                        <td class="py-3.5 px-5 font-bold text-slate-800 dark:text-slate-100">
                            Bulan #{{ sprintf('%02d', $item->month) }}
                        </td>
                        <td class="py-3.5 px-5">
                            <button @click="openDetail({{ $item }})" class="font-black text-primary text-sm hover:underline cursor-pointer">
                                {{ $item->period_name }}
                            </button>
                        </td>
                        <td class="py-3.5 px-5 text-slate-600 dark:text-slate-300 font-medium">
                            {{ \Carbon\Carbon::parse($item->start_date)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($item->end_date)->format('d/m/Y') }}
                        </td>
                        <td class="py-3.5 px-5 text-center">
                            @if($item->status === 'Open')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 animate-pulse">
                                OPEN (BUKA)
                            </span>
                            @elseif($item->status === 'Closed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400">
                                CLOSED (TUTUP BUKU)
                            </span>
                            @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400">
                                SPECIAL ADJUSTMENT
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 text-[11px] text-slate-500 dark:text-slate-400">
                            @if($item->closed_at)
                                <span>Ditutup: {{ \Carbon\Carbon::parse($item->closed_at)->format('d M Y, H:i') }}</span>
                                @if($item->closedByUser)
                                    <span class="block font-semibold text-slate-700 dark:text-slate-300">oleh {{ $item->closedByUser->name }}</span>
                                @endif
                            @else
                                <span class="italic text-slate-400">Transaksi masih diizinkan</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 text-right space-x-1.5 whitespace-nowrap">
                            <!-- View Detail Button -->
                            <button @click="openDetail({{ json_encode($item) }})"
                                    class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 hover:text-primary rounded-lg transition cursor-pointer" title="Lihat Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>

                            <form action="{{ route('fa.period.toggle-status', $item->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm(&quot;Apakah Anda yakin ingin {{ $item->status === 'Open' ? 'MENUTUP' : 'MEMBUKA KEMBALI' }} periode {{ $item->period_name }}?&quot;)">
                                @csrf
                                @if($item->status === 'Open')
                                <button type="submit" class="px-3 py-1.5 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 text-rose-700 dark:text-rose-300 font-bold rounded-lg text-xs transition border border-rose-200 dark:border-rose-800 cursor-pointer">
                                    Tutup Periode
                                </button>
                                @else
                                <button type="submit" class="px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 text-emerald-700 dark:text-emerald-300 font-bold rounded-lg text-xs transition border border-emerald-200 dark:border-emerald-800 cursor-pointer">
                                    Buka Periode
                                </button>
                                @endif
                            </form>
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
    </div>

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

