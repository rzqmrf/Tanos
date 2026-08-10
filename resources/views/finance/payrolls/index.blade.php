@extends('layouts.app')

@section('title', 'HCM: Period Payroll — Tanos ERP')

@section('content')
<div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm w-full"
     x-data="{ showCreateModal: false }">
     
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-yellow-50 dark:bg-yellow-950/30 text-yellow-600 dark:text-yellow-400 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Period Payroll</h1>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Manajemen periode penggajian karyawan Pelindo Group per Project.</p>
            </div>
        </div>
        @if(in_array(session('user.role'), ['Admin', 'Finance Manager']))
        <button @click="showCreateModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition cursor-pointer">
            + Buat Periode Payroll
        </button>
        @endif
    </div>

    <!-- Filters Section -->
    <form action="{{ route('payrolls.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 mb-6 bg-slate-50/50 dark:bg-slate-950/10 border border-slate-100 dark:border-slate-800 p-4 rounded-xl">
        <div>
            <label class="block text-[10px] font-bold text-slate-450 uppercase mb-1.5">Project / Segment</label>
            <select name="project_id" class="w-full text-xs px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-700 dark:text-slate-350">
                <option value="">Semua Project</option>
                @foreach($projects as $proj)
                    <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }}>
                        {{ $proj->segment }} - {{ $proj->regional }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-slate-450 uppercase mb-1.5">Tahun</label>
            <select name="year" class="w-full text-xs px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-700 dark:text-slate-350">
                <option value="">Semua Tahun</option>
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-slate-450 uppercase mb-1.5">Status Dokumen</label>
            <select name="status" class="w-full text-xs px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-700 dark:text-slate-350">
                <option value="">Semua Status</option>
                <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                <option value="Simulated" {{ request('status') == 'Simulated' ? 'selected' : '' }}>Simulated</option>
                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                <option value="Posted" {{ request('status') == 'Posted' ? 'selected' : '' }}>Posted</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="w-full bg-slate-850 hover:bg-slate-800 text-white py-2 rounded-xl text-xs font-bold transition cursor-pointer">
                Filter
            </button>
            <a href="{{ route('payrolls.index') }}" class="w-full text-center border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 py-2 rounded-xl text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                Reset
            </a>
        </div>
    </form>

    <!-- Table List -->
    <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                    <th class="p-4">Nama Periode</th>
                    <th class="p-4">Project</th>
                    <th class="p-4">Tipe</th>
                    <th class="p-4">Bulan</th>
                    <th class="p-4">Tanggal Mulai</th>
                    <th class="p-4">Tanggal Selesai</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-600 dark:text-slate-350">
                @forelse($periods as $item)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                    <td class="p-4 font-bold text-slate-800 dark:text-slate-200">{{ $item->name }}</td>
                    <td class="p-4 font-semibold text-indigo-600 dark:text-indigo-400">{{ $item->project->segment }} - {{ $item->project->regional }}</td>
                    <td class="p-4">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $item->type === 'On-Cycle' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/20 dark:text-indigo-400' : 'bg-pink-50 text-pink-700 dark:bg-pink-950/20 dark:text-pink-400' }}">
                            {{ $item->type }}
                        </span>
                    </td>
                    <td class="p-4">{{ $item->month }}</td>
                    <td class="p-4">{{ $item->start_date->format('d M Y') }}</td>
                    <td class="p-4">{{ $item->end_date->format('d M Y') }}</td>
                    <td class="p-4">
                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider
                            {{ $item->status === 'Draft' ? 'bg-slate-100 text-slate-700 dark:bg-slate-850 dark:text-slate-300' : '' }}
                            {{ $item->status === 'Simulated' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400' : '' }}
                            {{ $item->status === 'Completed' ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/20 dark:text-amber-400' : '' }}
                            {{ $item->status === 'Posted' ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/20 dark:text-blue-400' : '' }}
                            {{ $item->status === 'Voided' ? 'bg-rose-50 text-rose-700 dark:bg-rose-950/20 dark:text-rose-400' : '' }}
                        ">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <a href="{{ route('payrolls.show', $item->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-750 text-[10px] font-bold rounded-lg transition inline-block">
                            View Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-12 text-center text-slate-400">Belum ada periode payroll yang terdaftar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MODAL: CREATE PERIOD --}}
    <div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-md" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showCreateModal = false">
            <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 mb-4">Buat Periode Gaji Baru</h3>
            
            <form action="{{ route('payrolls.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Pilih Project / Segment</label>
                    <select name="project_id" required 
                            class="w-full text-xs px-3 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-800 dark:text-slate-200">
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}">{{ $proj->segment }} - {{ $proj->regional }} ({{ $proj->month }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Periode</label>
                    <input type="text" name="name" required placeholder="Contoh: Gaji Security Perak Ags 2026" 
                           class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Tipe Payroll</label>
                        <select name="type" required 
                                class="w-full text-xs px-3 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-800 dark:text-slate-200">
                            <option value="On-Cycle">On-Cycle</option>
                            <option value="Off-Cycle">Off-Cycle</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Bulan Proses</label>
                        <input type="text" name="month" required placeholder="Contoh: Agustus 2026" 
                               class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Tanggal Mulai</label>
                        <input type="date" name="start_date" required 
                               class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Tanggal Selesai</label>
                        <input type="date" name="end_date" required 
                               class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800/80">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-slate-500 text-xs font-bold rounded-xl hover:bg-slate-50 transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow transition cursor-pointer">Buat Periode</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
