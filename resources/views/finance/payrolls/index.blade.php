@extends('layouts.app')

@section('title', 'HCM: Period Payroll — Tanos ERP')

@section('content')
<div>
     
    <x-page-header 
        title="Period Payroll" 
        subtitle="Manajemen periode penggajian karyawan Pelindo Group per Project."
        :breadcrumbs="[
            'General' => '#',
            'Human Capital' => '#',
            'Payroll' => '#',
            'Period Payroll' => ''
        ]"
    >
        <x-slot:action>
            @if(\App\Models\RolePermission::hasPermission(session('user.role'), 'payroll'))
            <a href="{{ route('payrolls.create') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 self-start sm:self-auto cursor-pointer border-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Buat Periode Payroll</span>
            </a>
            @endif
        </x-slot:action>
    </x-page-header>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-xs font-semibold">
        {{ session('success') }}
    </div>
    @endif

    <!-- Filters Section -->
    <form action="{{ route('payrolls.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 rounded-xl shadow-xs mb-6">
        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Project / Segment</label>
            <select name="project_id" class="w-full text-xs px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-700 dark:text-slate-300">
                <option value="">Semua Project</option>
                @foreach($projects as $proj)
                    <option value="{{ $proj->id }}" {{ request('project_id') == $proj->id ? 'selected' : '' }}>
                        {{ $proj->segment }} - {{ $proj->regional }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Tahun</label>
            <select name="year" class="w-full text-xs px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-700 dark:text-slate-300">
                <option value="">Semua Tahun</option>
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1.5">Status Dokumen</label>
            <select name="status" class="w-full text-xs px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-700 dark:text-slate-300">
                <option value="">Semua Status</option>
                <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                <option value="Simulated" {{ request('status') == 'Simulated' ? 'selected' : '' }}>Simulated</option>
                <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                <option value="Posted" {{ request('status') == 'Posted' ? 'selected' : '' }}>Posted</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="w-full bg-slate-800 hover:bg-slate-900 text-white py-2 rounded-xl text-xs font-bold transition cursor-pointer">
                Filter
            </button>
            <a href="{{ route('payrolls.index') }}" class="w-full text-center border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 py-2 rounded-xl text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                Reset
            </a>
        </div>
    </form>

    <!-- Data Table Container -->
    <x-data-card 
        title="Period Payroll - List" 
        :total="$periods->total()"
        :show-per-page="false"
        :show-search="false"
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4">Nama Periode</th>
                        <th class="py-3.5 px-4">Project</th>
                        <th class="py-3.5 px-4 text-center">Tipe</th>
                        <th class="py-3.5 px-4">Bulan</th>
                        <th class="py-3.5 px-4">Tanggal Mulai</th>
                        <th class="py-3.5 px-4">Tanggal Selesai</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center w-28">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($periods as $item)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                        <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-slate-100">
                            <a href="{{ route('payrolls.show', $item->id) }}" class="hover:text-primary transition">
                                {{ $item->name }}
                            </a>
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-primary">
                            {{ $item->project ? $item->project->segment . ' - ' . $item->project->regional : '—' }}
                        </td>
                        <td class="py-3.5 px-4 text-center font-bold text-[11px]">
                            <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded font-mono">
                                {{ $item->type }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-slate-700 dark:text-slate-300">
                            {{ $item->month }}
                        </td>
                        <td class="py-3.5 px-4 font-mono text-slate-600 dark:text-slate-400">
                            {{ $item->start_date ? $item->start_date->format('d M Y') : '—' }}
                        </td>
                        <td class="py-3.5 px-4 font-mono text-slate-600 dark:text-slate-400">
                            {{ $item->end_date ? $item->end_date->format('d M Y') : '—' }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if($item->status === 'Completed' || $item->status === 'Posted')
                            <span class="px-2.5 py-1 text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 rounded-lg inline-flex items-center">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>{{ strtoupper($item->status) }}
                            </span>
                            @else
                            <span class="px-2.5 py-1 text-[10px] font-bold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800 rounded-lg inline-flex items-center">
                                {{ strtoupper($item->status) }}
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center space-x-1.5">
                                <x-action-button type="view" :href="route('payrolls.show', $item->id)" title="Lihat Detail Gaji" />
                                <x-action-button type="edit" :href="route('payrolls.edit', $item->id)" title="Edit Periode" />
                                <form action="{{ route('payrolls.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus periode payroll {{ $item->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-action-button type="delete" title="Hapus Periode" />
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400 dark:text-slate-500">Belum ada periode payroll yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($periods->hasPages())
        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
            {{ $periods->links() }}
        </div>
        @endif
    </x-data-card>
</div>
@endsection
