@extends('layouts.app')

@section('title', 'Outline Agreement - View — Tanos ERP')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm">
        <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 dark:text-slate-500 mb-1.5">
                <a href="{{ route('dashboard.index') }}" class="hover:text-primary transition flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Home
                </a>
                <span>/</span>
                <span>Material</span>
                <span>/</span>
                <a href="{{ route('material.outline-agreement') }}" class="hover:text-primary transition">Outline Agreement</a>
                <span>/</span>
                <span class="text-primary font-black">View</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                Outline Agreement - View
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">
                Detail klausul dan plafon kontrak payung pengadaan.
            </p>
        </div>

        <div class="flex items-center space-x-2 self-start sm:self-auto">
            <a href="{{ route('material.outline-agreement') }}"
               class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 shadow-xs cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Back</span>
            </a>

            <a href="{{ route('material.outline-agreement.edit', $agreement->id) }}"
               class="px-4 py-2 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span>Edit</span>
            </a>

            <form action="{{ route('material.outline-agreement.destroy', $agreement->id) }}" method="POST" class="inline"
                  onsubmit="return confirm('Hapus data kontrak payung ini?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 bg-rose-50 dark:bg-rose-950/50 hover:bg-rose-100 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 shadow-xs cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Delete</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 rounded-xl bg-primary-light text-primary font-mono text-sm font-black">
                    {{ $agreement->agreement_number }}
                </span>
                <h2 class="text-base font-black text-slate-800 dark:text-slate-100">
                    {{ $agreement->title }}
                </h2>
            </div>

            <div>
                @if($agreement->status === 'Active')
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400">ACTIVE</span>
                @elseif($agreement->status === 'Draft')
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-slate-150 text-slate-700">DRAFT</span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-rose-100 text-rose-700">{{ strtoupper($agreement->status) }}</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 text-xs">
            <!-- Left Info -->
            <div class="space-y-4">
                <div>
                    <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Vendor Rekanan (Partner)</label>
                    <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl font-bold text-slate-800 dark:text-slate-200">
                        {{ $agreement->partner ? $agreement->partner->name : '-' }}
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Tipe Kontrak</label>
                    <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl text-slate-800 dark:text-slate-200 font-semibold">{{ $agreement->agreement_type }}</div>
                </div>

                <div>
                    <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Target Nilai Kontrak (Plafon)</label>
                    <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl font-mono font-black text-sm text-primary">
                        {{ $agreement->currency }} {{ number_format($agreement->target_value, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Right Info -->
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Tanggal Mulai</label>
                        <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl font-mono text-slate-800 dark:text-slate-200">{{ $agreement->start_date }}</div>
                    </div>
                    <div>
                        <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Tanggal Berakhir</label>
                        <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl font-mono text-slate-800 dark:text-slate-200">{{ $agreement->end_date }}</div>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Syarat Pembayaran (Payment Terms)</label>
                    <div class="p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl text-slate-800 dark:text-slate-200">{{ $agreement->terms ?? '-' }}</div>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
            <label class="block font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1 text-xs">Catatan & Klausul Tambahan</label>
            <div class="p-4 bg-slate-50 dark:bg-slate-800/60 rounded-2xl text-xs text-slate-800 dark:text-slate-200 leading-relaxed min-h-[60px]">
                {{ $agreement->notes ?: 'Tidak ada catatan tambahan untuk kontrak payung ini.' }}
            </div>
        </div>

    </div>

</div>
@endsection
