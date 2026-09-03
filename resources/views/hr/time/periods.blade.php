@extends('layouts.app')

@section('title', 'Time Periods Rekap — Tanos ERP')

@section('content')
<div class="space-y-6 w-full" x-data="{ showCreateModal: false }">
    <!-- Header Block -->
    <x-page-header 
        title="Time Period Rekap" 
        subtitle="Generate hasil rekapitulasi kehadiran bulanan karyawan berdasarkan toleransi parameter evaluasi."
        :breadcrumbs="[
            'General' => '#',
            'Human Capital' => '#',
            'Time Management' => '#',
            'Time Period' => ''
        ]"
    >
        <x-slot:action>
            @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
            <button @click="showCreateModal = true" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 self-start sm:self-auto cursor-pointer border-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Periode Evaluasi</span>
            </button>
            @endif
        </x-slot:action>
    </x-page-header>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Period Table Container -->
    <x-data-card 
        title="Time Period - List" 
        :total="count($periods)"
        :show-per-page="false"
        :show-search="false"
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4">Nama Periode</th>
                        <th class="py-3.5 px-4">Scope Project</th>
                        <th class="py-3.5 px-4">Rentang Tanggal</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center w-36">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($periods as $period)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                        <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-slate-100">
                            <a href="{{ route('org.periods.show', $period->id) }}" class="hover:text-primary transition">
                                {{ $period->name }}
                            </a>
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300 font-semibold">
                            {{ $period->project ? $period->project->project_name : 'All Projects (Seluruh Karyawan)' }}
                        </td>
                        <td class="py-3.5 px-4 font-mono text-slate-600 dark:text-slate-400">
                            {{ $period->start_date->format('d M Y') }} - {{ $period->end_date->format('d M Y') }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if($period->status === 'Completed')
                            <span class="px-2.5 py-1 text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 rounded-lg inline-flex items-center">
                                Completed
                            </span>
                            @else
                            <span class="px-2.5 py-1 text-[10px] font-bold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800 rounded-lg inline-flex items-center">
                                {{ $period->status }}
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            <div class="inline-flex items-center space-x-1.5">
                                @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
                                    <form action="{{ route('org.periods.calculate', $period->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" title="Jalankan Evaluasi Absensi" class="px-2.5 py-1.5 bg-sky-50 dark:bg-sky-950/40 text-sky-700 dark:text-sky-400 font-bold rounded-lg text-xs border border-sky-200 dark:border-sky-800/50 hover:bg-sky-100 transition cursor-pointer flex items-center space-x-1">
                                            <span>⚡ Evaluate</span>
                                        </button>
                                    </form>
                                @endif
                                <x-action-button type="view" :href="route('org.periods.show', $period->id)" title="View Detail" />
                                @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
                                    <form action="{{ route('org.periods.destroy', $period->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus periode ini beserta seluruh hasil rekapitulasinya?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <x-action-button type="delete" title="Hapus Periode" />
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            Belum ada periode evaluasi absensi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-data-card>

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
