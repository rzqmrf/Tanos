@extends('layouts.app')

@section('title', 'Profit Center Master — Tanos ERP')

@section('content')
<div class="space-y-6" x-data="{
    showModal: false,
    showDetailModal: false,
    editMode: false,
    detailItem: {},
    form: { id: null, code: '', name: '', segment: '', person_in_charge: '', description: '', active: true },
    openCreate() {
        this.editMode = false;
        this.form = { id: null, code: '', name: '', segment: '', person_in_charge: '', description: '', active: true };
        this.showModal = true;
    },
    openEdit(item) {
        this.editMode = true;
        this.form = {
            id: item.id,
            code: item.code,
            name: item.name,
            segment: item.segment || '',
            person_in_charge: item.person_in_charge || '',
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
                <span class="text-primary dark:text-sky-400 font-black">Profit Center</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                Profit Center Master (Pusat Laba)
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 font-medium">Pengelolaan unit bisnis, divisi profit, dan segmen pendapatan korporasi & proyek.</p>
        </div>

        <button @click="openCreate()"
                class="px-4 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center space-x-1.5 self-start sm:self-auto cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Tambah Profit Center</span>
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

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-primary-light text-primary flex items-center justify-center font-bold text-lg shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Profit Center</p>
                <p class="text-xl font-black text-slate-800 dark:text-slate-100 mt-0.5">{{ $totalAll }} <span class="text-xs font-normal text-slate-400">Unit</span></p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-lg shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Status Aktif</p>
                <p class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-0.5">{{ $totalActive }} <span class="text-xs font-normal text-slate-400">Unit</span></p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 p-5 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-lg shadow-inner">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Segmen Bisnis</p>
                <p class="text-xl font-black text-indigo-600 dark:text-indigo-400 mt-0.5">{{ count($segments) }} <span class="text-xs font-normal text-slate-400">Kategori</span></p>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
        <form method="GET" action="{{ route('fa.profit-center') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari kode, nama profit center, segmen, PIC..."
                       class="w-full pl-10 pr-4 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-primary/20">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="segment" class="px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-200 focus:outline-none">
                <option value="">Semua Segmen Bisnis</option>
                @foreach($segments as $s)
                    <option value="{{ $s }}" {{ request('segment') === $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl transition cursor-pointer">
                Filter
            </button>

            @if(request()->hasAny(['search', 'segment']))
            <a href="{{ route('fa.profit-center') }}" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold rounded-xl transition flex items-center justify-center">
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
                        <th class="py-4 px-5">Kode</th>
                        <th class="py-4 px-5">Nama Profit Center</th>
                        <th class="py-4 px-5">Segmen Bisnis</th>
                        <th class="py-4 px-5">Penanggung Jawab (PIC)</th>
                        <th class="py-4 px-5 text-center">Cost Centers</th>
                        <th class="py-4 px-5 text-center">Status</th>
                        <th class="py-4 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($profitCenters as $item)
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                        <td class="py-3.5 px-5 font-mono font-bold text-primary">
                            <button @click="openDetail({{ $item }})" class="hover:underline cursor-pointer">
                                {{ $item->code }}
                            </button>
                        </td>
                        <td class="py-3.5 px-5">
                            <button @click="openDetail({{ $item }})" class="text-left font-bold text-slate-800 dark:text-slate-200 hover:text-primary transition cursor-pointer">
                                {{ $item->name }}
                            </button>
                            @if($item->description)
                            <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">{{ $item->description }}</p>
                            @endif
                        </td>
                        <td class="py-3.5 px-5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                {{ $item->segment ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-5 text-slate-600 dark:text-slate-300 font-medium">
                            {{ $item->person_in_charge ?? '-' }}
                        </td>
                        <td class="py-3.5 px-5 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary-light text-primary">
                                {{ $item->cost_centers_count }} CC
                            </span>
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
                        <td class="py-3.5 px-5 text-right space-x-1 whitespace-nowrap">
                            <!-- View Detail Button -->
                            <button @click="openDetail({{ $item }})"
                                    class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 hover:text-primary rounded-lg transition cursor-pointer" title="Lihat Detail">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>

                            <!-- Edit Button -->
                            <button @click="openEdit({{ $item }})"
                                    class="p-1.5 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 hover:text-primary rounded-lg transition cursor-pointer" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>

                            <!-- Delete Button -->
                            <form action="{{ route('fa.profit-center.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus Profit Center {{ $item->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 hover:bg-rose-50 dark:hover:bg-rose-950/40 text-slate-500 hover:text-rose-600 rounded-lg transition cursor-pointer" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            Tidak ada data Profit Center yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($profitCenters->hasPages())
        <div class="p-4 border-t border-slate-100 dark:border-slate-800">
            {{ $profitCenters->links() }}
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
                        <span class="px-2.5 py-1 rounded-lg bg-primary-light text-primary font-mono font-black text-xs" x-text="detailItem.code"></span>
                        <h3 class="text-base font-black text-slate-800 dark:text-slate-100">Detail Profit Center</h3>
                    </div>
                    <button @click="showDetailModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="mt-4 space-y-4 text-xs">
                    <div>
                        <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Nama Profit Center</span>
                        <p class="text-sm font-black text-slate-800 dark:text-slate-100 mt-0.5" x-text="detailItem.name"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 bg-slate-50 dark:bg-slate-800/50 p-3.5 rounded-xl border border-slate-100 dark:border-slate-800">
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Segmen Bisnis</span>
                            <p class="font-bold text-slate-800 dark:text-slate-200 mt-0.5" x-text="detailItem.segment || '-'"></p>
                        </div>
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Penanggung Jawab (PIC)</span>
                            <p class="font-bold text-slate-800 dark:text-slate-200 mt-0.5" x-text="detailItem.person_in_charge || '-'"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Status</span>
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

                        <div>
                            <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Cost Centers Terkait</span>
                            <p class="font-bold text-primary mt-1" x-text="(detailItem.cost_centers_count || 0) + ' Cost Center'"></p>
                        </div>
                    </div>

                    <div>
                        <span class="text-slate-400 block font-semibold uppercase tracking-wider text-[10px]">Deskripsi / Keterangan</span>
                        <p class="text-slate-700 dark:text-slate-300 mt-0.5 leading-relaxed bg-slate-50 dark:bg-slate-800/30 p-3 rounded-xl border border-slate-100 dark:border-slate-800" x-text="detailItem.description || 'Tidak ada catatan tambahan.'"></p>
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
                    <h3 class="text-base font-black text-slate-800 dark:text-slate-100" x-text="editMode ? 'Edit Profit Center' : 'Tambah Profit Center Baru'"></h3>
                    <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="editMode ? '{{ url('fa/profit-center') }}/' + form.id : '{{ route('fa.profit-center.store') }}'" method="POST" class="mt-4 space-y-4">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kode Profit Center *</label>
                        <input type="text" name="code" x-model="form.code" required placeholder="Contoh: PC-OPS-01"
                               class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-mono font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Profit Center *</label>
                        <input type="text" name="name" x-model="form.name" required placeholder="Contoh: Profit Center Operasional Proyek"
                               class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Segmen Bisnis</label>
                            <input type="text" name="segment" x-model="form.segment" placeholder="Contoh: 01. TAD Operasional"
                                   class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Penanggung Jawab (PIC)</label>
                            <input type="text" name="person_in_charge" x-model="form.person_in_charge" placeholder="Nama Lengkap & Gelar"
                                   class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Keterangan / Deskripsi</label>
                        <textarea name="description" x-model="form.description" rows="2" placeholder="Catatan fungsi unit profit..."
                                  class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none"></textarea>
                    </div>

                    <div class="flex items-center space-x-2 pt-1">
                        <input type="checkbox" name="active" value="1" id="pc_active" x-model="form.active"
                               class="rounded border-slate-300 dark:border-slate-700 text-primary focus:ring-primary h-4 w-4">
                        <label for="pc_active" class="text-xs font-bold text-slate-700 dark:text-slate-300">Status Aktif</label>
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
