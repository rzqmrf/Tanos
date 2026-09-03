@extends('layouts.app')

@section('title', 'Detail Jabatan — ' . $job->name . ' — Tanos ERP')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Page Header -->
    <x-page-header 
        :title="$job->name" 
        :subtitle="'Kode Jabatan: ' . $job->code . ' • Unit: ' . ($job->division ? $job->division->name : 'N/A')"
        :breadcrumbs="[
            'General' => '#',
            'Human Resource' => '#',
            'Job Positions' => route('org.job.index'),
            'Detail Jabatan' => ''
        ]"
    >
        <x-slot:action>
            <div class="flex items-center gap-2">
                <a href="{{ route('org.job.index') }}" 
                   class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Kembali</span>
                </a>
                @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
                <a href="{{ route('org.job.edit', $job->id) }}" 
                   class="px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 cursor-pointer border-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    <span>Edit Jabatan</span>
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

    <!-- Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Overview Card -->
        <div class="md:col-span-1 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm space-y-4">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Atribut Formasi Jabatan</h3>
            
            <div class="space-y-3 text-xs">
                <div>
                    <span class="block text-slate-400">Kode Formasi</span>
                    <span class="font-mono font-bold text-slate-800 dark:text-slate-100 text-sm">{{ $job->code }}</span>
                </div>

                <div>
                    <span class="block text-slate-400">Unit Kerja (Division)</span>
                    <span class="font-bold text-primary">
                        {{ $job->division ? $job->division->name . ' (' . $job->division->code . ')' : '—' }}
                    </span>
                </div>

                <div>
                    <span class="block text-slate-400">Atasan Langsung</span>
                    <span class="font-bold text-slate-700 dark:text-slate-200">
                        {{ $job->parent ? $job->parent->name . ' (' . $job->parent->code . ')' : 'Top Level (Direktur Utama)' }}
                    </span>
                </div>

                <div>
                    <span class="block text-slate-400">Cost Center (SAP FI/CO)</span>
                    <span class="font-mono font-bold text-slate-800 dark:text-slate-100">
                        {{ $job->cost_center ?? '—' }}
                    </span>
                </div>

                <div>
                    <span class="block text-slate-400">Status Integrasi SAP</span>
                    @if($job->sent_to_sap)
                    <span class="px-2.5 py-1 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 rounded-lg font-bold inline-flex items-center mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Synced to SAP
                    </span>
                    @else
                    <span class="px-2.5 py-1 bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800 rounded-lg font-bold inline-flex items-center mt-0.5">
                        Draft Local
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Incumbents / Employees occupying this position -->
        <div class="md:col-span-2 space-y-6">
            <x-data-card 
                title="Pegawai Pemegang Jabatan Ini" 
                :total="count($job->employees ?? [])"
                :show-per-page="false"
                :show-search="false"
            >
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                <th class="py-3 px-4">NIP</th>
                                <th class="py-3 px-4">Nama Pegawai</th>
                                <th class="py-3 px-4 text-center">Status Pegawai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                            @forelse($job->employees ?? [] as $emp)
                            <tr>
                                <td class="py-3 px-4 font-mono font-bold text-slate-800 dark:text-slate-100">{{ $emp->nip }}</td>
                                <td class="py-3 px-4 font-bold text-primary">{{ $emp->name }}</td>
                                <td class="py-3 px-4 text-center">
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 rounded text-[10px] font-bold">Aktif</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-slate-400">Belum ada pegawai yang menempati formasi jabatan ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-data-card>
        </div>
    </div>

</div>
@endsection
