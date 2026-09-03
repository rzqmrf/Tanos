@extends('layouts.app')

@section('title', 'Detail ECN #' . $movement->reference_number . ' — Tanos ERP')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Page Header -->
    <x-page-header 
        :title="'SK ECN #' . $movement->reference_number" 
        :subtitle="'Usulan: ' . $movement->ecn_name . ' • Pegawai: ' . ($movement->employee ? $movement->employee->name : 'N/A')"
        :breadcrumbs="[
            'General' => '#',
            'Human Resource' => '#',
            'ECN Management' => route('org.ecn.index'),
            'Detail ECN' => ''
        ]"
    >
        <x-slot:action>
            <div class="flex items-center gap-2">
                <a href="{{ route('org.ecn.index') }}" 
                   class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Kembali</span>
                </a>
                @if(in_array(session('user.role'), ['Admin', 'HR Manager']) && $movement->status !== 'Completed')
                <a href="{{ route('org.ecn.edit', $movement->id) }}" 
                   class="px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 cursor-pointer border-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    <span>Edit SK</span>
                </a>
                <form action="{{ route('org.ecn.complete', $movement->id) }}" method="POST" class="inline" onsubmit="return confirm('Posting SK ECN ini? Perubahan jabatan pegawai akan langsung aktif!')">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 cursor-pointer border-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Approve & Post ECN</span>
                    </button>
                </form>
                @endif
            </div>
        </x-slot:action>
    </x-page-header>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-xl text-xs font-bold">
        {{ session('success') }}
    </div>
    @endif

    <!-- Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- SK Overview Card -->
        <div class="md:col-span-1 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Informasi SK Mutasi</h3>
            
            <div class="space-y-3 text-xs">
                <div>
                    <span class="block text-slate-400">No. Referensi SK</span>
                    <span class="font-mono font-bold text-slate-800 dark:text-slate-100 text-sm">{{ $movement->reference_number }}</span>
                </div>

                <div>
                    <span class="block text-slate-400">Jenis Perubahan</span>
                    <span class="px-2.5 py-1 bg-sky-100 dark:bg-sky-950/60 text-sky-700 dark:text-sky-400 border border-sky-200 dark:border-sky-800 rounded-lg font-bold inline-block mt-0.5">
                        {{ $movement->movement_type }}
                    </span>
                </div>

                <div>
                    <span class="block text-slate-400">Tanggal Efektif</span>
                    <span class="font-mono font-bold text-slate-700 dark:text-slate-200">
                        {{ $movement->effective_date ? $movement->effective_date->format('d F Y') : '—' }}
                    </span>
                </div>

                <div>
                    <span class="block text-slate-400">Status Dokumen</span>
                    @if($movement->status === 'Completed')
                    <span class="px-2.5 py-1 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 rounded-lg font-bold inline-flex items-center mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>COMPLETED / ACTIVE
                    </span>
                    @else
                    <span class="px-2.5 py-1 bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800 rounded-lg font-bold inline-flex items-center mt-0.5">
                        DRAFT APPROVAL
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Before & After Comparison Card -->
        <div class="md:col-span-2 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-6">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Perbandingan Jabatan & Proyek (Before vs After)</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- BEFORE -->
                <div class="p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl space-y-3 text-xs">
                    <span class="px-2 py-0.5 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold rounded uppercase tracking-wider text-[10px]">
                        Posisi Asal (Before)
                    </span>
                    <div>
                        <span class="block text-slate-400">Jabatan Asal</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100">
                            {{ $movement->fromPosition ? $movement->fromPosition->name : '—' }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-slate-400">Proyek Asal</span>
                        <span class="font-bold text-slate-800 dark:text-slate-100">
                            {{ $movement->fromProject ? $movement->fromProject->segment . ' - ' . $movement->fromProject->regional : '—' }}
                        </span>
                    </div>
                </div>

                <!-- AFTER -->
                <div class="p-4 bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800/50 rounded-xl space-y-3 text-xs">
                    <span class="px-2 py-0.5 bg-emerald-600 text-white font-bold rounded uppercase tracking-wider text-[10px]">
                        Posisi Baru (After)
                    </span>
                    <div>
                        <span class="block text-emerald-800 dark:text-emerald-300 font-medium">Jabatan Baru</span>
                        <span class="font-bold text-emerald-900 dark:text-emerald-200">
                            {{ $movement->toPosition ? $movement->toPosition->name : '(Tetap)' }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-emerald-800 dark:text-emerald-300 font-medium">Proyek Baru</span>
                        <span class="font-bold text-emerald-900 dark:text-emerald-200">
                            {{ $movement->toProject ? $movement->toProject->segment . ' - ' . $movement->toProject->regional : '(Tetap)' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
