@extends('layouts.app')

@section('title', 'Employee Data — Tanos ERP')

@section('content')

{{-- =================== HEADER =================== --}}
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-3">
        <div class="p-2.5 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 shadow-md shadow-blue-500/20">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-white">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
            </svg>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Employee Data</h1>
            <p class="text-xs text-slate-400 dark:text-slate-500">Manajemen data kepegawaian internal</p>
        </div>
    </div>
    @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
    <div class="flex items-center gap-2">
        <button onclick="alert('Fitur Import Data Pegawai siap digunakan')"
            class="inline-flex items-center gap-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-150 shrink-0 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-emerald-600 dark:text-emerald-400"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
            Import Data
        </button>
        <button id="btn-open-create" onclick="document.getElementById('modal-create-employee').classList.remove('hidden')"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-md shadow-blue-500/20 transition-all duration-150 shrink-0 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Tambah Pegawai
        </button>
    </div>
    @endif
</div>

{{-- =================== STAT CARDS =================== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
        $totalEmp = $employees->total();
        $segments = $employees->getCollection()->groupBy('segment');
    @endphp
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Total Pegawai</span>
            <div class="p-1.5 bg-blue-50 dark:bg-blue-950/40 rounded-lg text-blue-600 dark:text-blue-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" /></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $totalEmp }}</p>
        <p class="text-xs text-slate-400 mt-0.5">Aktif terdaftar</p>
    </div>

    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Regional</span>
            <div class="p-1.5 bg-violet-50 dark:bg-violet-950/40 rounded-lg text-violet-600 dark:text-violet-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-2.003 3.5-4.697 3.5-8.327a8 8 0 1 0-16 0c0 3.63 1.556 6.326 3.5 8.327a19.583 19.583 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.144.742ZM11.5 13.5a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" clip-rule="evenodd" /></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $employees->getCollection()->pluck('regional')->filter()->unique()->count() }}</p>
        <p class="text-xs text-slate-400 mt-0.5">Regional aktif</p>
    </div>

    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Segment</span>
            <div class="p-1.5 bg-emerald-50 dark:bg-emerald-950/40 rounded-lg text-emerald-600 dark:text-emerald-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z" /></svg>
            </div>
        </div>
        <p class="text-2xl font-bold text-slate-800 dark:text-slate-100">{{ $employees->getCollection()->pluck('segment')->filter()->unique()->count() }}</p>
        <p class="text-xs text-slate-400 mt-0.5">Segment tersedia</p>
    </div>

    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-4 shadow-md shadow-blue-500/20 text-white">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-semibold text-blue-100 uppercase tracking-wide">Halaman</span>
            <div class="p-1.5 bg-white/20 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M3 6a3 3 0 0 1 3-3h12a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm4.5 7.5a.75.75 0 0 1 .75.75v2.25a.75.75 0 0 1-1.5 0v-2.25a.75.75 0 0 1 .75-.75Zm3.75-1.5a.75.75 0 0 0-1.5 0v4.5a.75.75 0 0 0 1.5 0V12Zm2.25-3a.75.75 0 0 1 .75.75v6.75a.75.75 0 0 1-1.5 0V9.75A.75.75 0 0 1 13.5 9Zm3.75-1.5a.75.75 0 0 0-1.5 0v9a.75.75 0 0 0 1.5 0v-9Z" clip-rule="evenodd" /></svg>
            </div>
        </div>
        <p class="text-2xl font-bold">{{ $employees->currentPage() }} / {{ $employees->lastPage() }}</p>
        <p class="text-xs text-blue-100 mt-0.5">{{ $employees->firstItem() }}–{{ $employees->lastItem() }} dari {{ $totalEmp }} data</p>
    </div>
</div>

{{-- =================== ALERT =================== --}}
@if(session('success'))
<div class="mb-4 flex items-center gap-3 p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900/40 rounded-xl text-sm">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 shrink-0"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" /></svg>
    {{ session('success') }}
</div>
@endif

{{-- =================== SEARCH & FILTER BAR =================== --}}
<div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-4 shadow-sm mb-4">
    <div class="flex flex-col sm:flex-row gap-3">
        <div class="relative flex-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <input id="search-employee" type="text" placeholder="Cari nama, NIPP, jabatan..." oninput="filterTable()"
                class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-slate-800 dark:text-slate-100 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
        </div>
        <select id="filter-regional" onchange="filterTable()"
            class="px-3 py-2 text-sm border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
            <option value="">Semua Regional</option>
            @foreach($regionals as $reg)
                <option value="{{ $reg->name }}">{{ $reg->name }}</option>
            @endforeach
        </select>
        <select id="filter-segment" onchange="filterTable()"
            class="px-3 py-2 text-sm border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-slate-700 dark:text-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
            <option value="">Semua Segment</option>
            @foreach($segments as $seg)
                <option value="{{ $seg->name }}">{{ $seg->name }}</option>
            @endforeach
        </select>
    </div>
</div>

{{-- =================== TABLE =================== --}}
<div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="employee-table">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-100 dark:border-slate-800">
                    <th class="px-5 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pegawai</th>
                    <th class="px-5 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Jabatan</th>
                    <th class="px-5 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Regional</th>
                    <th class="px-5 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Segment</th>
                    <th class="px-5 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">TMT</th>
                    <th class="px-5 py-3.5 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 dark:divide-slate-800/80 text-sm" id="employee-tbody">
                @forelse($employees as $item)
                @php
                    $colors = ['blue','violet','emerald','amber','rose','indigo','cyan','teal','orange','pink'];
                    $colorIndex = crc32($item->name ?? '') % count($colors);
                    $color = $colors[abs($colorIndex)];
                    $nameParts = explode(' ', $item->name ?? 'TU');
                    $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : substr($nameParts[0], 1, 1)));
                @endphp
                <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/30 transition-colors duration-100 employee-row"
                    data-name="{{ strtolower($item->name ?? '') }}"
                    data-nipp="{{ strtolower($item->nipp ?? '') }}"
                    data-role="{{ strtolower($item->role ?? '') }}"
                    data-regional="{{ $item->regional ?? '' }}"
                    data-segment="{{ $item->segment ?? '' }}">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-{{ $color }}-100 dark:bg-{{ $color }}-950/40 text-{{ $color }}-700 dark:text-{{ $color }}-400 flex items-center justify-center text-xs font-bold shrink-0 ring-2 ring-{{ $color }}-200 dark:ring-{{ $color }}-900/50">
                                {{ $initials }}
                            </div>
                            <div>
                                <div class="font-semibold text-slate-800 dark:text-slate-100 leading-tight">{{ $item->name ?? 'No Name' }}</div>
                                <div class="text-xs text-slate-400 dark:text-slate-500 font-mono mt-0.5">{{ $item->nipp ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-950/30 text-blue-700 dark:text-blue-300 text-xs font-semibold">
                            {{ $item->role ?? 'Staff' }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="text-slate-700 dark:text-slate-300 font-medium text-xs">{{ $item->regional ?? '-' }}</div>
                        @if(!empty($item->sub_regional))
                        <span class="mt-1 inline-block px-2 py-0.5 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 rounded text-[10px] font-semibold">
                            {{ $item->sub_regional }}
                        </span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300 text-xs font-semibold">
                            {{ $item->segment ?? '-' }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-xs text-slate-500 dark:text-slate-400 font-medium">
                        {{ $item->tmt_date ? \Carbon\Carbon::parse($item->tmt_date)->format('d M Y') : '-' }}
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <button onclick="openDetailModal({{ json_encode($item) }})"
                                class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:text-emerald-600 dark:hover:text-emerald-400 transition cursor-pointer" title="Lihat Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            </button>
                            @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
                            <button onclick="openEditModal({{ json_encode($item) }})"
                                class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-blue-50 dark:hover:bg-blue-950/40 hover:text-blue-600 dark:hover:text-blue-400 transition cursor-pointer" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                            </button>
                            <form action="{{ route('employees.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus data {{ $item->name }}?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="p-1.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-red-50 dark:hover:bg-red-950/40 hover:text-red-600 dark:hover:text-red-400 transition cursor-pointer" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m9.456-9.992-1.734 1M4.544.008 6.278 1M21 3H3m3 0h12M5.25 3l.75 18h12l.75-18H5.25Z" /></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="p-4 bg-slate-100 dark:bg-slate-800 rounded-2xl text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                            </div>
                            <p class="text-slate-500 dark:text-slate-400 font-medium text-sm">Belum ada data pegawai</p>
                            <p class="text-slate-400 dark:text-slate-500 text-xs">Mulai tambah data pegawai pertama</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($employees->hasPages())
    <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20">
        {{ $employees->links() }}
    </div>
    @endif
</div>


{{-- ============= MODAL: DETAIL KARYAWAN ============= --}}
<div id="modal-detail-employee" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full shadow-2xl border border-slate-100 dark:border-slate-800 overflow-hidden">
        {{-- Header gradient --}}
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 px-6 py-5 relative">
            <button onclick="document.getElementById('modal-detail-employee').classList.add('hidden')"
                class="absolute top-4 right-4 p-1.5 rounded-lg bg-white/20 hover:bg-white/30 text-white transition cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
            <div class="flex items-center gap-4">
                <div id="detail-avatar" class="w-14 h-14 rounded-2xl bg-white/20 text-white flex items-center justify-center text-xl font-bold ring-2 ring-white/30">TU</div>
                <div>
                    <h3 id="detail-name" class="text-lg font-bold text-white">Nama Lengkap</h3>
                    <p id="detail-nipp" class="text-sm text-blue-100 font-mono">NIPP-000000</p>
                    <span id="detail-role-badge" class="mt-1 inline-block px-2.5 py-0.5 bg-white/20 text-white rounded-full text-xs font-semibold">Staff</span>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Kepegawaian --}}
                <div>
                    <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <span class="w-1 h-3.5 bg-blue-500 rounded-full inline-block"></span> Info Kepegawaian
                    </h4>
                    <div class="space-y-2">
                        @foreach([
                            ['label'=>'Regional','id'=>'detail-regional'],
                            ['label'=>'Sub-Area','id'=>'detail-sub-regional'],
                            ['label'=>'Segment','id'=>'detail-segment'],
                            ['label'=>'Bulan Proyek','id'=>'detail-month'],
                            ['label'=>'TMT','id'=>'detail-tmt'],
                        ] as $f)
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-50 dark:border-slate-800/80">
                            <span class="text-xs text-slate-400">{{ $f['label'] }}</span>
                            <span id="{{ $f['id'] }}" class="text-xs font-semibold text-slate-700 dark:text-slate-200 text-right">—</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Payroll & BPJS --}}
                <div>
                    <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <span class="w-1 h-3.5 bg-emerald-500 rounded-full inline-block"></span> Payroll & BPJS
                    </h4>
                    <div class="space-y-2">
                        @foreach([
                            ['label'=>'PTKP Pajak','id'=>'detail-ptkp'],
                            ['label'=>'Bank','id'=>'detail-bank-name'],
                            ['label'=>'No. Rekening','id'=>'detail-bank-number'],
                            ['label'=>'Atas Nama','id'=>'detail-bank-holder'],
                            ['label'=>'BPJS Kesehatan','id'=>'detail-bpjs-kes'],
                            ['label'=>'BPJS TK','id'=>'detail-bpjs-tk'],
                        ] as $f)
                        <div class="flex items-center justify-between py-1.5 border-b border-slate-50 dark:border-slate-800/80">
                            <span class="text-xs text-slate-400">{{ $f['label'] }}</span>
                            <span id="{{ $f['id'] }}" class="text-xs font-semibold text-slate-700 dark:text-slate-200 text-right truncate max-w-[160px]">—</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 pb-5">
            <button onclick="document.getElementById('modal-detail-employee').classList.add('hidden')"
                class="w-full py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-sm font-semibold transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>
</div>


{{-- ============= MODAL: TAMBAH PEGAWAI ============= --}}
<div id="modal-create-employee" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-start justify-center p-4 overflow-y-auto">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full my-8 shadow-2xl border border-slate-100 dark:border-slate-800">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-2.5">
                <div class="p-1.5 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Tambah Data Pegawai</h3>
            </div>
            <button onclick="document.getElementById('modal-create-employee').classList.add('hidden')" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form action="{{ route('employees.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Kolom Kiri --}}
                <div class="space-y-4">
                    <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-800">
                        <span class="w-1 h-3.5 bg-blue-500 rounded-full"></span>
                        <h4 class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Data Kepegawaian</h4>
                    </div>
                    @foreach([
                        ['label'=>'Nama Pegawai','name'=>'name','type'=>'text','placeholder'=>'Nama lengkap pegawai','required'=>true],
                        ['label'=>'NIPP / Perner ID','name'=>'nipp','type'=>'text','placeholder'=>'Contoh: NIPP-123456','required'=>true],
                        ['label'=>'Jabatan / Role','name'=>'role','type'=>'text','placeholder'=>'Manager, Staff, Developer...','required'=>true],
                        ['label'=>'Tanggal TMT','name'=>'tmt_date','type'=>'date','placeholder'=>'','required'=>true],
                    ] as $f)
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">{{ $f['label'] }} @if(!empty($f['required']))<span class="text-red-400">*</span>@endif</label>
                        <input type="{{ $f['type'] }}" name="{{ $f['name'] }}" {{ !empty($f['required']) ? 'required' : '' }} placeholder="{{ $f['placeholder'] }}"
                            class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
                    </div>
                    @endforeach
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Bulan <span class="text-red-400">*</span></label>
                            <select name="month" required class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
                                @foreach($months as $m)<option value="{{ $m }}">{{ $m }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Segment <span class="text-red-400">*</span></label>
                            <select name="segment" required class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
                                @foreach($segments as $seg)<option value="{{ $seg->name }}">{{ $seg->name }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Regional <span class="text-red-400">*</span></label>
                            <select name="regional" required class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
                                @foreach($regionals as $reg)<option value="{{ $reg->name }}">{{ $reg->name }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Sub-Area</label>
                            <select name="sub_regional" class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
                                <option value="">Opsional</option>
                                @foreach($subRegionals as $sub)<option value="{{ $sub->name }}">{{ $sub->name }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-4">
                    <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-800">
                        <span class="w-1 h-3.5 bg-emerald-500 rounded-full"></span>
                        <h4 class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Payroll, Pajak & BPJS</h4>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">PTKP Pajak <span class="text-red-400">*</span></label>
                        <select name="ptkp_status" required class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
                            <option value="TK/0">TK/0 — Belum Menikah, 0 Tanggungan</option>
                            <option value="TK/1">TK/1 — Belum Menikah, 1 Tanggungan</option>
                            <option value="K/0">K/0 — Menikah, 0 Tanggungan</option>
                            <option value="K/1">K/1 — Menikah, 1 Tanggungan</option>
                            <option value="K/2">K/2 — Menikah, 2 Tanggungan</option>
                            <option value="K/3">K/3 — Menikah, 3 Tanggungan</option>
                        </select>
                    </div>
                    @foreach([
                        ['label'=>'Nama Bank','name'=>'bank_name','placeholder'=>'Contoh: Bank Mandiri'],
                        ['label'=>'Nomor Rekening','name'=>'bank_account_number','placeholder'=>'Nomor rekening bank'],
                        ['label'=>'Atas Nama Rekening','name'=>'bank_account_name','placeholder'=>'Nama pemilik rekening'],
                    ] as $f)
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">{{ $f['label'] }}</label>
                        <input type="text" name="{{ $f['name'] }}" placeholder="{{ $f['placeholder'] }}"
                            class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
                    </div>
                    @endforeach
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">No. BPJS Kes</label>
                            <input type="text" name="bpjs_kesehatan_number" placeholder="No BPJS Kes"
                                class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">No. BPJS TK</label>
                            <input type="text" name="bpjs_ketenagakerjaan_number" placeholder="No BPJS TK"
                                class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2.5 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modal-create-employee').classList.add('hidden')"
                    class="px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition cursor-pointer">Batal</button>
                <button type="submit"
                    class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl text-sm font-semibold shadow-md shadow-blue-500/20 transition cursor-pointer">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ============= MODAL: EDIT PEGAWAI ============= --}}
<div id="modal-edit-employee" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-start justify-center p-4 overflow-y-auto">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-2xl w-full my-8 shadow-2xl border border-slate-100 dark:border-slate-800">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-2.5">
                <div class="p-1.5 bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Edit Data Pegawai</h3>
            </div>
            <button onclick="document.getElementById('modal-edit-employee').classList.add('hidden')" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <form id="form-edit-employee" method="POST" class="p-6 space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-4">
                    <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-800">
                        <span class="w-1 h-3.5 bg-blue-500 rounded-full"></span>
                        <h4 class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">Data Kepegawaian</h4>
                    </div>
                    @foreach([
                        ['label'=>'Nama Pegawai','id'=>'edit-name','name'=>'name','type'=>'text','required'=>true],
                        ['label'=>'NIPP / Perner ID','id'=>'edit-nipp','name'=>'nipp','type'=>'text','required'=>true],
                        ['label'=>'Jabatan / Role','id'=>'edit-role','name'=>'role','type'=>'text','required'=>true],
                        ['label'=>'Tanggal TMT','id'=>'edit-tmt-date','name'=>'tmt_date','type'=>'date','required'=>true],
                    ] as $f)
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">{{ $f['label'] }}</label>
                        <input type="{{ $f['type'] }}" id="{{ $f['id'] }}" name="{{ $f['name'] }}" {{ !empty($f['required']) ? 'required' : '' }}
                            class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
                    </div>
                    @endforeach
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Bulan</label>
                            <select id="edit-month" name="month" required class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
                                @foreach($months as $m)<option value="{{ $m }}">{{ $m }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Segment</label>
                            <select id="edit-segment" name="segment" required class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
                                @foreach($segments as $seg)<option value="{{ $seg->name }}">{{ $seg->name }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Regional</label>
                            <select id="edit-regional" name="regional" required class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
                                @foreach($regionals as $reg)<option value="{{ $reg->name }}">{{ $reg->name }}</option>@endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">Sub-Area</label>
                            <select id="edit-sub-regional" name="sub_regional" class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
                                <option value="">Opsional</option>
                                @foreach($subRegionals as $sub)<option value="{{ $sub->name }}">{{ $sub->name }}</option>@endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-2 pb-1 border-b border-slate-100 dark:border-slate-800">
                        <span class="w-1 h-3.5 bg-emerald-500 rounded-full"></span>
                        <h4 class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Payroll, Pajak & BPJS</h4>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">PTKP Pajak</label>
                        <select id="edit-ptkp-status" name="ptkp_status" required class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
                            <option value="TK/0">TK/0 — Belum Menikah, 0 Tanggungan</option>
                            <option value="TK/1">TK/1 — Belum Menikah, 1 Tanggungan</option>
                            <option value="K/0">K/0 — Menikah, 0 Tanggungan</option>
                            <option value="K/1">K/1 — Menikah, 1 Tanggungan</option>
                            <option value="K/2">K/2 — Menikah, 2 Tanggungan</option>
                            <option value="K/3">K/3 — Menikah, 3 Tanggungan</option>
                        </select>
                    </div>
                    @foreach([
                        ['label'=>'Nama Bank','id'=>'edit-bank-name','name'=>'bank_name'],
                        ['label'=>'Nomor Rekening','id'=>'edit-bank-account-number','name'=>'bank_account_number'],
                        ['label'=>'Atas Nama Rekening','id'=>'edit-bank-account-name','name'=>'bank_account_name'],
                    ] as $f)
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">{{ $f['label'] }}</label>
                        <input type="text" id="{{ $f['id'] }}" name="{{ $f['name'] }}"
                            class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
                    </div>
                    @endforeach
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">No. BPJS Kes</label>
                            <input type="text" id="edit-bpjs-kesehatan-number" name="bpjs_kesehatan_number"
                                class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1.5">No. BPJS TK</label>
                            <input type="text" id="edit-bpjs-ketenagakerjaan-number" name="bpjs_ketenagakerjaan_number"
                                class="w-full px-3 py-2.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-400 transition">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2.5 pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modal-edit-employee').classList.add('hidden')"
                    class="px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition cursor-pointer">Batal</button>
                <button type="submit"
                    class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-xl text-sm font-semibold shadow-md shadow-amber-500/20 transition cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>


<script>
// ========= Filter Table (client-side) =========
function filterTable() {
    const search = document.getElementById('search-employee').value.toLowerCase();
    const regional = document.getElementById('filter-regional').value;
    const segment = document.getElementById('filter-segment').value;

    document.querySelectorAll('.employee-row').forEach(row => {
        const matchSearch = !search ||
            row.dataset.name.includes(search) ||
            row.dataset.nipp.includes(search) ||
            row.dataset.role.includes(search);
        const matchRegional = !regional || row.dataset.regional === regional;
        const matchSegment = !segment || row.dataset.segment === segment;

        row.classList.toggle('hidden', !(matchSearch && matchRegional && matchSegment));
    });
}

// ========= Modal Detail =========
function openDetailModal(emp) {
    const nameParts = (emp.name || 'TU').split(' ');
    const initials = (nameParts[0][0] + (nameParts[1] ? nameParts[1][0] : nameParts[0][1] || '')).toUpperCase();

    document.getElementById('detail-avatar').innerText = initials;
    document.getElementById('detail-name').innerText = emp.name || 'No Name';
    document.getElementById('detail-nipp').innerText = emp.nipp || 'Belum diatur';
    document.getElementById('detail-role-badge').innerText = emp.role || 'Staff';
    document.getElementById('detail-regional').innerText = emp.regional || '—';
    document.getElementById('detail-sub-regional').innerText = emp.sub_regional || 'Tidak ada';
    document.getElementById('detail-segment').innerText = emp.segment || '—';
    document.getElementById('detail-month').innerText = emp.month || '—';
    document.getElementById('detail-tmt').innerText = emp.tmt_date
        ? new Date(emp.tmt_date).toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric'})
        : 'Belum diatur';
    document.getElementById('detail-ptkp').innerText = emp.ptkp_status || 'TK/0';
    document.getElementById('detail-bank-name').innerText = emp.bank_name || 'Belum diatur';
    document.getElementById('detail-bank-number').innerText = emp.bank_account_number || 'Belum diatur';
    document.getElementById('detail-bank-holder').innerText = emp.bank_account_name || 'Belum diatur';
    document.getElementById('detail-bpjs-kes').innerText = emp.bpjs_kesehatan_number || 'Belum terdaftar';
    document.getElementById('detail-bpjs-tk').innerText = emp.bpjs_ketenagakerjaan_number || 'Belum terdaftar';

    document.getElementById('modal-detail-employee').classList.remove('hidden');
}

// ========= Modal Edit =========
function openEditModal(emp) {
    const form = document.getElementById('form-edit-employee');
    form.action = `/dashboard/employees/${emp.id}`;

    document.getElementById('edit-name').value = emp.name || '';
    document.getElementById('edit-nipp').value = emp.nipp || '';
    document.getElementById('edit-role').value = emp.role || '';
    document.getElementById('edit-tmt-date').value = emp.tmt_date ? emp.tmt_date.split('T')[0] : '';
    document.getElementById('edit-month').value = emp.month || '';
    document.getElementById('edit-regional').value = emp.regional || '';
    document.getElementById('edit-sub-regional').value = emp.sub_regional || '';
    document.getElementById('edit-segment').value = emp.segment || '';
    document.getElementById('edit-ptkp-status').value = emp.ptkp_status || 'TK/0';
    document.getElementById('edit-bank-name').value = emp.bank_name || '';
    document.getElementById('edit-bank-account-number').value = emp.bank_account_number || '';
    document.getElementById('edit-bank-account-name').value = emp.bank_account_name || '';
    document.getElementById('edit-bpjs-kesehatan-number').value = emp.bpjs_kesehatan_number || '';
    document.getElementById('edit-bpjs-ketenagakerjaan-number').value = emp.bpjs_ketenagakerjaan_number || '';

    document.getElementById('modal-edit-employee').classList.remove('hidden');
}

// ========= Close modal on backdrop click =========
['modal-detail-employee','modal-create-employee','modal-edit-employee'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
});
</script>
@endsection
