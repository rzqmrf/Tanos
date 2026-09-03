@extends('layouts.app')

@section('title', 'Time Periods Rekap — Tanos ERP')

@section('content')
<div class="space-y-6 w-full" x-data="{ showCreateModal: false }">
    <!-- Header Block -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-100 dark:border-slate-800 shadow-sm">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-650 dark:text-indigo-400 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Time Period</h1>
                <p class="text-xs text-slate-400 dark:text-slate-550 font-semibold">Generate hasil rekapitulasi kehadiran bulanan karyawan berdasarkan toleransi parameter evaluasi.</p>
            </div>
        </div>
        
        @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
        <div>
            <button @click="showCreateModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition cursor-pointer">
                + Create Time Period
            </button>
        </div>
        @endif
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Period Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-xl p-6 shadow-sm">
        <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <th class="p-4 align-middle">Nama Periode</th>
                        <th class="p-4 align-middle">Scope Project</th>
                        <th class="p-4 align-middle">Rentang Tanggal</th>
                        <th class="p-4 align-middle text-center">Status</th>
                        <th class="p-4 align-middle text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-350">
                    @forelse($periods as $period)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                        <td class="p-4 align-middle font-bold text-slate-850 dark:text-slate-200">
                            {{ $period->name }}
                        </td>
                        <td class="p-4 align-middle font-semibold text-slate-600 dark:text-slate-300">
                            {{ $period->project ? $period->project->project_name : 'All Projects (Seluruh Karyawan)' }}
                        </td>
                        <td class="p-4 align-middle font-mono text-xs text-slate-550 dark:text-slate-450 whitespace-nowrap">
                            {{ $period->start_date->format('d M Y') }} - {{ $period->end_date->format('d M Y') }}
                        </td>
                        <td class="p-4 align-middle text-center whitespace-nowrap">
                            <span class="inline-block px-2.5 py-1 {{ $period->status === 'Completed' ? 'bg-emerald-55/10 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400' : 'bg-amber-55/10 text-amber-600 dark:bg-amber-950/30 dark:text-amber-400' }} rounded-md text-xs font-bold">
                                {{ $period->status }}
                            </span>
                        </td>
                        <td class="p-4 align-middle text-center whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-1.5">
                                @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
                                    <form action="{{ route('org.periods.calculate', $period->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/30 text-indigo-650 dark:text-indigo-400 font-bold px-2.5 py-1.5 rounded-lg text-xs transition cursor-pointer">
                                            ⚡ Evaluate
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('org.periods.show', $period->id) }}" 
                                   class="p-2 rounded-lg bg-sky-500 hover:bg-sky-600 text-white transition shadow-2xs flex items-center justify-center cursor-pointer" title="View Detail">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </a>
                                @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
                                    <form action="{{ route('org.periods.destroy', $period->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus periode ini beserta seluruh hasil rekapitulasinya?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white transition shadow-2xs flex items-center justify-center cursor-pointer" title="Hapus Periode">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-slate-400 text-xs">
                            Belum ada periode evaluasi absensi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL: CREATE TIME PERIOD --}}
    <div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-sm" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showCreateModal = false">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-4 uppercase tracking-wider">Tambah Periode Evaluasi</h3>
            
            <form action="{{ route('org.periods.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Periode</label>
                    <input type="text" name="name" required placeholder="Contoh: Absensi Agustus 2026" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Scope Project (Optional)</label>
                    <select name="project_id" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                        <option value="">All Projects (Seluruh Pegawai)</option>
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}">{{ $proj->project_name }} ({{ $proj->project_code }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Mulai Tanggal</label>
                        <input type="date" name="start_date" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Hingga Tanggal</label>
                        <input type="date" name="end_date" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                        Buat Periode
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
