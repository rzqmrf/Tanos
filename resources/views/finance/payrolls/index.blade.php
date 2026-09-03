@extends('layouts.app')

@section('title', 'HCM: Period Payroll — Tanos ERP')

@section('content')
<div x-data="{ showCreateModal: false }">
     
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
            <button @click="showCreateModal = true" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 self-start sm:self-auto cursor-pointer border-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Buat Periode Payroll</span>
            </button>
            @endif
        </x-slot:action>
    </x-page-header>

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
                        <div class="flex items-center justify-center">
                            <a href="{{ route('payrolls.show', $item->id) }}" 
                               style="background-color: #0091ea; color: #ffffff;"
                               class="p-2 rounded-lg hover:opacity-90 transition shadow-2xs flex items-center justify-center cursor-pointer" title="View Detail">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </a>
                        </div>
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
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showCreateModal = false">
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

