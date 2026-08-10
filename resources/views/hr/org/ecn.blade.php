@extends('layouts.app')

@section('title', 'Employee Change Notice (ECN) — Tanos ERP')

@section('content')
<div class="space-y-6 w-full" x-data="{ showCreateModal: false }">
    <!-- Header Block -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H9.75m0 18.75h-1.5a3.375 3.375 0 0 1-3.375-3.375V6.108c0-1.58 1.086-2.97 2.658-3.238a42.946 42.946 0 0 1 11.233 0A3.13 3.13 0 0 1 19.5 6.108V18a3.375 3.375 0 0 1-3.375 3.375h-1.5m-9 0-.374.056a3 3 0 0 1-3.396-3.072V6.108c0-1.58 1.086-2.97 2.658-3.238a42.946 42.946 0 0 1 11.233 0a3.13 3.13 0 0 1 2.658 3.238v6.782m-3-1.5H9.75m3 3H9.75m3 3H9.75" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Employee Change Notice (ECN)</h1>
                <p class="text-xs text-slate-400 dark:text-slate-550 font-semibold">Catat dan usulkan mutasi, demosi, promosi jabatan, dan perpindahan proyek karyawan.</p>
            </div>
        </div>
        
        @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
        <div>
            <button @click="showCreateModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition cursor-pointer">
                + Ajukan Usulan Mutasi
            </button>
        </div>
        @endif
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Data Table Section -->
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
        <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <table class="w-full text-left border-collapse min-w-[1300px]">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <th class="p-4 align-middle">Nomor SK / Usulan</th>
                        <th class="p-4 align-middle">Nama Perubahan</th>
                        <th class="p-4 align-middle">Karyawan (NIPP)</th>
                        <th class="p-4 align-middle">Tipe Mutasi</th>
                        <th class="p-4 align-middle">Posisi (Lama → Baru)</th>
                        <th class="p-4 align-middle">Proyek (Lama → Baru)</th>
                        <th class="p-4 align-middle">Tanggal Efektif</th>
                        <th class="p-4 align-middle text-center">Status</th>
                        <th class="p-4 align-middle text-center">SAP Status</th>
                        <th class="p-4 align-middle text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-350">
                    @forelse($movements as $m)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                        <td class="p-4 align-middle font-semibold text-slate-800 dark:text-slate-200">
                            {{ $m->reference_number }}
                        </td>
                        <td class="p-4 align-middle font-semibold text-slate-700 dark:text-slate-300">
                            {{ $m->ecn_name ?? 'Usulan Mutasi' }}
                        </td>
                        <td class="p-4 align-middle">
                            <span class="block font-bold text-slate-850 dark:text-slate-150">{{ $m->employee ? $m->employee->name : 'N/A' }}</span>
                            <span class="block text-xs font-semibold text-slate-400 dark:text-slate-550 mt-0.5 font-mono">NIPP: {{ $m->employee ? $m->employee->nipp : '-' }}</span>
                        </td>
                        <td class="p-4 align-middle whitespace-nowrap">
                            @php
                                $typeClasses = [
                                    'New Hire' => 'bg-emerald-55/10 text-emerald-600 dark:bg-emerald-950/20 dark:text-emerald-400',
                                    'Promotion' => 'bg-blue-55/10 text-blue-600 dark:bg-blue-950/20 dark:text-blue-400',
                                    'Mutation' => 'bg-indigo-55/10 text-indigo-600 dark:bg-indigo-950/20 dark:text-indigo-400',
                                    'Demotion' => 'bg-amber-55/10 text-amber-600 dark:bg-amber-950/20 dark:text-amber-400',
                                    'Resignation' => 'bg-rose-55/10 text-rose-600 dark:bg-rose-950/20 dark:text-rose-400',
                                ];
                                $c = $typeClasses[$m->movement_type] ?? 'bg-slate-100 text-slate-600';
                            @endphp
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $c }}">
                                {{ $m->movement_type }}
                            </span>
                        </td>
                        <td class="p-4 align-middle">
                            <div class="flex items-center space-x-1.5">
                                <span class="text-xs text-slate-400">{{ $m->fromPosition ? $m->fromPosition->name : '-' }}</span>
                                <span class="text-slate-300">→</span>
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $m->toPosition ? $m->toPosition->name : '-' }}</span>
                            </div>
                        </td>
                        <td class="p-4 align-middle">
                            <div class="flex items-center space-x-1.5">
                                <span class="text-xs text-slate-400 truncate max-w-[120px]">{{ $m->fromProject ? $m->fromProject->project_name : '-' }}</span>
                                <span class="text-slate-300">→</span>
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate max-w-[120px]">{{ $m->toProject ? $m->toProject->project_name : '-' }}</span>
                            </div>
                        </td>
                        <td class="p-4 align-middle text-xs whitespace-nowrap text-slate-600 dark:text-slate-450 font-semibold">
                            {{ $m->effective_date ? $m->effective_date->format('d M Y') : '-' }}
                        </td>
                        <td class="p-4 align-middle text-center whitespace-nowrap">
                            @if($m->status == 'Completed')
                                <span class="inline-block px-2.5 py-1 bg-emerald-55/10 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400 rounded-md text-xs font-bold">
                                    Aktif (Posted)
                                </span>
                            @else
                                <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-650 dark:bg-slate-850 dark:text-slate-400 rounded-md text-xs font-bold">
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="p-4 align-middle text-center whitespace-nowrap">
                            @if($m->sent_to_sap)
                                <span class="inline-block px-2.5 py-1 bg-blue-55/10 text-blue-600 dark:bg-blue-950/30 dark:text-blue-400 rounded-md text-xs font-bold">
                                    Sent to SAP
                                </span>
                            @else
                                <span class="inline-block px-2.5 py-1 bg-amber-55/10 text-amber-600 dark:bg-amber-950/30 dark:text-amber-400 rounded-md text-xs font-bold">
                                    Pending SAP
                                </span>
                            @endif
                        </td>
                        <td class="p-4 align-middle text-center whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-1.5">
                                @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
                                    @if($m->status == 'Draft')
                                        <form action="{{ route('org.ecn.complete', $m->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition cursor-pointer shadow-sm">
                                                Post (Aktifkan)
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if(!$m->sent_to_sap)
                                        <form action="{{ route('org.ecn.send-sap', $m->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/30 dark:hover:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded-lg transition cursor-pointer">
                                                Send SAP
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-2 py-1 text-slate-400 text-xs font-bold rounded-lg flex items-center bg-slate-50 dark:bg-slate-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 mr-1">
                                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                                            </svg>
                                            SAP Sent
                                        </span>
                                    @endif
                                @else
                                    <span class="text-xs text-slate-400 italic">No Action</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="p-12 text-center">
                            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/10 border border-slate-200 dark:border-slate-800 p-6 max-w-md mx-auto">
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Data usulan ECN kosong.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL: CREATE ECN --}}
    <div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-sm" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showCreateModal = false">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-4 uppercase tracking-wider">Ajukan SK/ECN Baru</h3>
            
            <form action="{{ route('org.ecn.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Nomor SK / Referensi</label>
                    <input type="text" name="reference_number" required placeholder="Contoh: SK-MUT/2026-0812" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Perubahan</label>
                    <input type="text" name="ecn_name" required placeholder="Contoh: Mutasi Security Perak ke Gresik" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Karyawan</label>
                        <select name="employee_id" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->nipp ?? 'No NIPP' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Tipe Perubahan</label>
                        <select name="movement_type" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            <option value="Mutation">Mutation</option>
                            <option value="Promotion">Promotion</option>
                            <option value="Demotion">Demotion</option>
                            <option value="New Hire">New Hire</option>
                            <option value="Resignation">Resignation</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Tujuan Jabatan Baru</label>
                        <select name="to_position_id" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            <option value="">-- Tetap / Tidak Berubah --</option>
                            @foreach($jobPositions as $pos)
                                <option value="{{ $pos->id }}">{{ $pos->name }} ({{ $pos->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Tujuan Proyek Baru</label>
                        <select name="to_project_id" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            <option value="">-- Tetap / Tidak Berubah --</option>
                            @foreach($projects as $p)
                                <option value="{{ $p->id }}">{{ $p->project_name }} ({{ $p->project_code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Tanggal Efektif</label>
                        <input type="date" name="effective_date" required value="{{ date('Y-m-d') }}" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Mulai Berlaku</label>
                        <input type="date" name="valid_from" value="{{ date('Y-m-d') }}" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                        Ajukan ECN
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
