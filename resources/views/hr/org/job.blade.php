@extends('layouts.app')

@section('title', 'Job Positions (Formation) — Tanos ERP')

@section('content')
<div class="space-y-6 w-full" x-data="{ showCreateModal: false }">
    <!-- Header Block -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-blue-50 dark:bg-blue-950/30 text-blue-650 dark:text-blue-400 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0 1 10.052 21.12a11.375 11.375 0 0 1-5.022-1.883v-.109A9.375 9.375 0 0 1 15 19.128ZM15 19.128v-.003c-.5.91-.786 1.957-.786 3.07M14.214 16.058A9.337 9.337 0 0 0 10.05 15.25a9.38 9.38 0 0 0-2.624.372 4.125 4.125 0 0 0-7.533 2.493M21.5 7.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM8 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm14.25 9a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0ZM4.5 15a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Job Position (Formasi Jabatan)</h1>
                <p class="text-xs text-slate-400 dark:text-slate-550 font-semibold">Kelola spesifikasi jabatan karyawan, cost center terkait, dan integrasi SAP.</p>
            </div>
        </div>
        
        @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
        <div>
            <button @click="showCreateModal = true" class="bg-blue-650 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition cursor-pointer">
                + Tambah Jabatan
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
            <table class="w-full text-left border-collapse min-w-[1200px]">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <th class="p-4 align-middle">Kode Jabatan</th>
                        <th class="p-4 align-middle">Nama Jabatan</th>
                        <th class="p-4 align-middle">Unit Kerja</th>
                        <th class="p-4 align-middle">Melapor Ke</th>
                        <th class="p-4 align-middle">Regional</th>
                        <th class="p-4 align-middle">Cost Center</th>
                        <th class="p-4 align-middle">Atribut Khusus</th>
                        <th class="p-4 align-middle text-center">Status</th>
                        <th class="p-4 align-middle text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-350">
                    @forelse($jobPositions as $job)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition {{ !$job->active ? 'opacity-60 bg-slate-50/20' : '' }}">
                        <td class="p-4 align-middle font-mono font-bold text-blue-650 dark:text-blue-400 text-[13px]">
                            {{ $job->code }}
                        </td>
                        <td class="p-4 align-middle">
                            <span class="block font-bold text-slate-800 dark:text-slate-150">{{ $job->name }}</span>
                            @if($job->is_leader)
                                <span class="inline-block mt-0.5 px-1.5 py-0.5 bg-indigo-50 dark:bg-indigo-950/20 text-indigo-700 dark:text-indigo-400 rounded text-[9px] font-black uppercase">
                                    Leader / Chief
                                </span>
                            @endif
                        </td>
                        <td class="p-4 align-middle font-semibold text-slate-700 dark:text-slate-400">
                            {{ $job->division ? $job->division->name : '-' }}
                        </td>
                        <td class="p-4 align-middle text-slate-550 dark:text-slate-450">
                            {{ $job->parent ? $job->parent->name : 'N/A (Root Position)' }}
                        </td>
                        <td class="p-4 align-middle font-semibold text-slate-700 dark:text-slate-400">
                            {{ $job->regional ?? '-' }}
                        </td>
                        <td class="p-4 align-middle font-mono text-slate-550 dark:text-slate-450">
                            <span class="block text-slate-600 dark:text-slate-400"><span class="font-bold text-slate-400">CC:</span> {{ $job->cost_center ?? '-' }}</span>
                            @if($job->cost_center_name)
                                <span class="block text-[10px] text-slate-400 mt-0.5">{{ $job->cost_center_name }}</span>
                            @endif
                        </td>
                        <td class="p-4 align-middle whitespace-nowrap">
                            @if($job->no_contract)
                                <span class="inline-block px-1.5 py-0.5 bg-rose-50 text-rose-700 dark:bg-rose-950/20 dark:text-rose-400 text-[9px] font-bold rounded">
                                    No Contract
                                </span>
                            @endif
                            @if($job->non_formation)
                                <span class="inline-block px-1.5 py-0.5 bg-amber-50 text-amber-700 dark:bg-amber-950/20 dark:text-amber-400 text-[9px] font-bold rounded">
                                    Non Formation
                                </span>
                            @endif
                            @if(!$job->no_contract && !$job->non_formation)
                                <span class="text-xs text-slate-400 font-medium">Standard</span>
                            @endif
                        </td>
                        <td class="p-4 align-middle text-center whitespace-nowrap">
                            <span class="inline-block px-2.5 py-1 {{ $job->active ? 'bg-emerald-55/10 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400' : 'bg-rose-55/10 text-rose-600 dark:bg-rose-950/30 dark:text-rose-400' }} rounded-md text-xs font-bold">
                                {{ $job->active ? 'Active' : 'Delimited' }}
                            </span>
                        </td>
                        <td class="p-4 align-middle text-center whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-1.5">
                                @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
                                    @if($job->active)
                                        <form action="{{ route('org.job.delimit', $job->id) }}" method="POST" onsubmit="return confirm('Delimit formasi jabatan ini?')" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/30 dark:hover:bg-amber-900/40 text-amber-700 dark:text-amber-400 text-xs font-bold rounded-lg transition cursor-pointer">
                                                Delimit
                                            </button>
                                        </form>
                                        
                                        <form action="{{ route('org.job.duplicate', $job->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/30 dark:hover:bg-indigo-900/40 text-indigo-700 dark:text-indigo-400 text-xs font-bold rounded-lg transition cursor-pointer" title="Duplicate massal jabatan untuk rekrutmen TAD">
                                                Duplicate
                                            </button>
                                        </form>
                                    @endif
                                    
                                    @if(!$job->sent_to_sap)
                                        <form action="{{ route('org.job.send-sap', $job->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/30 dark:hover:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 text-xs font-bold rounded-lg transition cursor-pointer">
                                                Send to SAP
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-2 py-1 bg-slate-100 dark:bg-slate-800 text-slate-400 text-xs font-bold rounded-lg flex items-center">
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
                        <td colspan="9" class="p-12 text-center">
                            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/10 border border-slate-200 dark:border-slate-800 p-6 max-w-md mx-auto">
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Data jabatan / formasi kosong.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL: CREATE JOB POSITION --}}
    <div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-sm" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showCreateModal = false">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-4 uppercase tracking-wider">Tambah Formasi Jabatan</h3>
            
            <form action="{{ route('org.job.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Kode Jabatan SAP</label>
                        <input type="text" name="code" required placeholder="Contoh: 50000891" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Jabatan</label>
                        <input type="text" name="name" required placeholder="Contoh: Anggota Security" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Unit Kerja (STO)</label>
                        <select name="division_id" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            @foreach($divisions as $div)
                                <option value="{{ $div->id }}">{{ $div->name }} ({{ $div->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Melapor Ke (Chief)</label>
                        <select name="parent_id" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            <option value="">-- No Chief (Root Position) --</option>
                            @foreach($parentJobs as $pJob)
                                <option value="{{ $pJob->id }}">{{ $pJob->name }} ({{ $pJob->code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Regional</label>
                        <input type="text" name="regional" placeholder="Regional 1 Perak" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Mulai Berlaku</label>
                        <input type="date" name="valid_from" value="{{ date('Y-m-d') }}" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Cost Center SAP</label>
                        <input type="text" name="cost_center" placeholder="CC-9901" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Cost Center</label>
                        <input type="text" name="cost_center_name" placeholder="Operational Perak" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="border-t border-slate-100 dark:border-slate-800 pt-3 flex flex-wrap gap-4">
                    <label class="inline-flex items-center space-x-2 text-xs font-bold text-slate-600 dark:text-slate-400 cursor-pointer">
                        <input type="checkbox" name="is_leader" value="1" class="rounded text-blue-600 focus:ring-blue-500/20 border-slate-200 dark:border-slate-700">
                        <span>Leader / Chief</span>
                    </label>
                    <label class="inline-flex items-center space-x-2 text-xs font-bold text-slate-600 dark:text-slate-400 cursor-pointer">
                        <input type="checkbox" name="no_contract" value="1" class="rounded text-blue-600 focus:ring-blue-500/20 border-slate-200 dark:border-slate-700">
                        <span>No Contract</span>
                    </label>
                    <label class="inline-flex items-center space-x-2 text-xs font-bold text-slate-600 dark:text-slate-400 cursor-pointer">
                        <input type="checkbox" name="non_formation" value="1" class="rounded text-blue-600 focus:ring-blue-500/20 border-slate-200 dark:border-slate-700">
                        <span>Non Formation</span>
                    </label>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-650 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                        Simpan Jabatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
