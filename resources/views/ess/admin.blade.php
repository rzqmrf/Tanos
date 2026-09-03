@extends('layouts.app')

@section('title', 'ESS Approvals Panel — Tanos ERP')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'leaves' }">

    {{-- HEADER BLOCK --}}
    <x-page-header 
        title="Panel Persetujuan ESS" 
        subtitle="Halaman Admin & HR untuk memproses persetujuan cuti/izin serta koreksi absensi TAD Pelindo."
        :breadcrumbs="[
            'General' => '#',
            'ESS' => '#',
            'Panel Persetujuan' => ''
        ]"
    />

    {{-- NOTIFICATIONS --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30 rounded-xl text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- TABS NAVIGATION --}}
    <div class="flex border-b border-slate-200 dark:border-slate-800 gap-6">
        <button @click="activeTab = 'leaves'"
                :class="activeTab === 'leaves' ? 'border-violet-600 text-violet-600 dark:text-violet-400 font-bold' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 font-medium'"
                class="pb-3 border-b-2 text-xs uppercase tracking-wider transition-all cursor-pointer outline-none">
            Verifikasi Cuti & Izin
        </button>
        <button @click="activeTab = 'cico'"
                :class="activeTab === 'cico' ? 'border-violet-600 text-violet-600 dark:text-violet-400 font-bold' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 font-medium'"
                class="pb-3 border-b-2 text-xs uppercase tracking-wider transition-all cursor-pointer outline-none">
            Verifikasi Koreksi CICO
        </button>
    </div>

    {{-- TAB CONTENTS --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-xl shadow-sm overflow-hidden">
        
        {{-- LEAVE VERIFICATION TAB --}}
        <div x-show="activeTab === 'leaves'">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80">
                            <th class="p-4">Pegawai</th>
                            <th class="p-4">Tipe</th>
                            <th class="p-4">Rentang Tanggal</th>
                            <th class="p-4">Total Hari</th>
                            <th class="p-4">Keterangan/Alasan</th>
                            <th class="p-4">Lampiran</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-600 dark:text-slate-300">
                        @forelse($leaveRequests as $req)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                <td class="p-4">
                                    <span class="block font-bold text-slate-800 dark:text-slate-100">{{ $req->employee->name }}</span>
                                    <span class="block text-[10px] text-slate-400 mt-0.5">{{ $req->employee->role }} · Segment {{ $req->employee->segment }}</span>
                                </td>
                                <td class="p-4 font-semibold text-slate-850">{{ $req->type }}</td>
                                <td class="p-4">{{ $req->start_date->format('d M Y') }} s/d {{ $req->end_date->format('d M Y') }}</td>
                                <td class="p-4 font-bold">{{ $req->total_days }} Hari</td>
                                <td class="p-4 max-w-[180px] truncate" title="{{ $req->reason }}">{{ $req->reason }}</td>
                                <td class="p-4">
                                    @if($req->attachment)
                                        <a href="{{ asset('storage/' . $req->attachment) }}" target="_blank" class="text-violet-600 hover:underline font-semibold">
                                            Unduh Berkas
                                        </a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider
                                        {{ $req->status === 'Approved' ? 'bg-emerald-55/80 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400' : '' }}
                                        {{ $req->status === 'Rejected' ? 'bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-400' : '' }}
                                        {{ $req->status === 'Submitted' ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400' : '' }}
                                    ">
                                        {{ $req->status }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    @if($req->status === 'Submitted')
                                        <div class="flex items-center justify-center gap-2">
                                            <form action="{{ route('ess.admin.leave.action', [$req->id, 'Approved']) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-bold cursor-pointer transition">
                                                    Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('ess.admin.leave.action', [$req->id, 'Rejected']) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-[10px] font-bold cursor-pointer transition">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="text-center text-slate-400">
                                            Selesai oleh: {{ $req->approver ? $req->approver->name : 'Sistem' }}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400">Tidak ada pengajuan cuti/izin yang masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- CICO VERIFICATION TAB --}}
        <div x-show="activeTab === 'cico'" style="display: none;">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80">
                            <th class="p-4">Pegawai</th>
                            <th class="p-4">Tanggal Absen</th>
                            <th class="p-4">Usulan Clock In</th>
                            <th class="p-4">Usulan Clock Out</th>
                            <th class="p-4">Alasan Koreksi</th>
                            <th class="p-4">Lampiran Bukti</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-600 dark:text-slate-300">
                        @forelse($cicoCorrections as $cor)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                <td class="p-4">
                                    <span class="block font-bold text-slate-800 dark:text-slate-100">{{ $cor->employee->name }}</span>
                                    <span class="block text-[10px] text-slate-400 mt-0.5">{{ $cor->employee->role }} · Segment {{ $cor->employee->segment }}</span>
                                </td>
                                <td class="p-4 font-semibold">{{ $cor->date->format('d M Y') }}</td>
                                <td class="p-4 text-indigo-650 dark:text-indigo-400 font-bold">{{ Carbon\Carbon::parse($cor->clock_in)->format('H:i') }}</td>
                                <td class="p-4 text-blue-650 dark:text-blue-400 font-bold">{{ Carbon\Carbon::parse($cor->clock_out)->format('H:i') }}</td>
                                <td class="p-4 max-w-[180px] truncate" title="{{ $cor->reason }}">{{ $cor->reason }}</td>
                                <td class="p-4">
                                    @if($cor->attachment)
                                        <a href="{{ asset('storage/' . $cor->attachment) }}" target="_blank" class="text-violet-600 hover:underline font-semibold">
                                            Unduh Berkas
                                        </a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider
                                        {{ $cor->status === 'Approved' ? 'bg-emerald-55/80 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400' : '' }}
                                        {{ $cor->status === 'Rejected' ? 'bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-400' : '' }}
                                        {{ $cor->status === 'Submitted' ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400' : '' }}
                                    ">
                                        {{ $cor->status }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    @if($cor->status === 'Submitted')
                                        <div class="flex items-center justify-center gap-2">
                                            <form action="{{ route('ess.admin.cico.action', [$cor->id, 'Approved']) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[10px] font-bold cursor-pointer transition">
                                                    Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route('ess.admin.cico.action', [$cor->id, 'Rejected']) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-[10px] font-bold cursor-pointer transition">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="text-center text-slate-400">
                                            Selesai oleh: {{ $cor->approver ? $cor->approver->name : 'Sistem' }}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400">Tidak ada pengajuan koreksi CICO yang masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
