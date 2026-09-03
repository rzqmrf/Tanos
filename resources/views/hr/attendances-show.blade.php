@extends('layouts.app')

@section('title', 'Detail Presensi — ' . ($attendance->employee ? $attendance->employee->name : 'N/A') . ' — Tanos ERP')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Page Header -->
    <x-page-header 
        :title="'Presensi: ' . ($attendance->employee ? $attendance->employee->name : 'N/A')" 
        :subtitle="'Tanggal: ' . ($attendance->date ? \Carbon\Carbon::parse($attendance->date)->format('d F Y') : '—') . ' • Status: ' . $attendance->status"
        :breadcrumbs="[
            'General' => '#',
            'Human Resource' => '#',
            'Absensi & Cuti' => route('attendances.index'),
            'Detail Presensi' => ''
        ]"
    >
        <x-slot:action>
            <div class="flex items-center gap-2">
                <a href="{{ route('attendances.index') }}" 
                   class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Kembali</span>
                </a>
                @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
                <a href="{{ route('attendances.edit', $attendance->id) }}" 
                   class="px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 cursor-pointer border-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    <span>Edit Presensi</span>
                </a>
                @endif
            </div>
        </x-slot:action>
    </x-page-header>

    @if(session('success'))
    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-xl text-xs font-bold">
        {{ session('success') }}
    </div>
    @endif

    <!-- Container Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kartu Presensi Harian</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-800 text-center">
                <span class="block text-[10px] font-bold text-slate-400 uppercase">Jam Masuk (Clock In)</span>
                <span class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-1 block font-mono">
                    {{ $attendance->clock_in ?? '—' }}
                </span>
            </div>

            <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-800 text-center">
                <span class="block text-[10px] font-bold text-slate-400 uppercase">Jam Keluar (Clock Out)</span>
                <span class="text-xl font-black text-rose-600 dark:text-rose-400 mt-1 block font-mono">
                    {{ $attendance->clock_out ?? '—' }}
                </span>
            </div>

            <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-800 text-center">
                <span class="block text-[10px] font-bold text-slate-400 uppercase">Status Kehadiran</span>
                <span class="text-sm font-bold text-slate-800 dark:text-slate-100 mt-1 block">
                    {{ $attendance->status }}
                </span>
            </div>

            <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-800 text-center">
                <span class="block text-[10px] font-bold text-slate-400 uppercase">Jam Lembur</span>
                <span class="text-xl font-black text-sky-600 dark:text-sky-400 mt-1 block font-mono">
                    {{ $attendance->overtime_hours ?? 0 }} Jam
                </span>
            </div>
        </div>

        @if($attendance->notes)
        <div class="p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-100 dark:border-slate-800 text-xs">
            <span class="block font-bold text-slate-400 uppercase tracking-wider mb-1">Catatan / Keterangan:</span>
            <p class="text-slate-700 dark:text-slate-300">{{ $attendance->notes }}</p>
        </div>
        @endif
    </div>

</div>
@endsection
