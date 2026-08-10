@extends('layouts.app')

@section('title', 'Schedule Assignments — Tanos ERP')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 w-full" x-data="{ showGroupModal: false, showEditGroupModal: false, showAssignModal: false, editGroup: {} }">
    <!-- Left Column: Schedule Groups List & Create -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Kelompok Jadwal (Groups)</h3>
                @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
                <button @click="showGroupModal = true" class="text-xs bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/30 text-indigo-650 dark:text-indigo-400 font-bold px-2.5 py-1.5 rounded-lg transition cursor-pointer">
                    + Group
                </button>
                @endif
            </div>
            
            <div class="space-y-3">
                @forelse($groups as $g)
                    <div class="p-3 bg-slate-50 dark:bg-slate-855/55 rounded-xl border border-slate-200/50 dark:border-slate-800/40 relative group">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-100">{{ $g->name }}</span>
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase {{ $g->type === 'Shift' ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/20' : 'bg-slate-150 text-slate-700 dark:bg-slate-800' }}">
                                {{ $g->type }}
                            </span>
                        </div>
                        <div class="flex items-center text-[10px] text-slate-400 mt-2 font-mono justify-between">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 mr-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                {{ $g->work_start ? Carbon\Carbon::parse($g->work_start)->format('H:i') : '-' }} - {{ $g->work_end ? Carbon\Carbon::parse($g->work_end)->format('H:i') : '-' }} WIB
                            </span>
                            @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
                            <div class="flex space-x-1 opacity-0 group-hover:opacity-100 transition duration-150">
                                <button @click="editGroup = { id: '{{ $g->id }}', name: '{{ $g->name }}', type: '{{ $g->type }}', work_start: '{{ $g->work_start ? Carbon\Carbon::parse($g->work_start)->format('H:i') : '' }}', work_end: '{{ $g->work_end ? Carbon\Carbon::parse($g->work_end)->format('H:i') : '' }}', is_active: {{ $g->is_active ? 1 : 0 }} }; showEditGroupModal = true" class="text-[9px] text-blue-600 hover:underline font-bold cursor-pointer">Edit</button>
                                <span class="text-slate-300">|</span>
                                <form action="{{ route('org.schedules.group.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Hapus kelompok jadwal ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[9px] text-rose-600 hover:underline font-bold cursor-pointer">Delete</button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-4">Belum ada kelompok jadwal.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Assignments List -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Penugasan Jadwal Karyawan</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-550 font-semibold mt-0.5">Daftar pemetaan jadwal Shift/Reguler aktif karyawan.</p>
                </div>
                @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
                <button @click="showAssignModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition cursor-pointer self-start sm:self-auto">
                    Assign Schedule
                </button>
                @endif
            </div>

            @if(session('success'))
                <div class="mb-5 p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-xs font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                            <th class="p-4 align-middle">Karyawan (NIPP)</th>
                            <th class="p-4 align-middle">Kelompok Jadwal</th>
                            <th class="p-4 align-middle">Jam Kerja</th>
                            <th class="p-4 align-middle">Tipe Jadwal</th>
                            <th class="p-4 align-middle">Masa Penugasan</th>
                            <th class="p-4 align-middle text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-350">
                        @forelse($assignments as $assign)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                            <td class="p-4 align-middle">
                                <span class="block font-bold text-slate-800 dark:text-slate-150">{{ $assign->employee ? $assign->employee->name : 'N/A' }}</span>
                                <span class="block text-xs font-semibold text-slate-400 dark:text-slate-550 mt-0.5 font-mono">NIPP: {{ $assign->employee ? $assign->employee->nipp : '-' }}</span>
                            </td>
                            <td class="p-4 align-middle font-semibold text-slate-800 dark:text-slate-200">
                                {{ $assign->scheduleGroup ? $assign->scheduleGroup->name : 'N/A' }}
                            </td>
                            <td class="p-4 align-middle font-mono text-xs whitespace-nowrap text-slate-555 dark:text-slate-455">
                                {{ $assign->scheduleGroup && $assign->scheduleGroup->work_start ? Carbon\Carbon::parse($assign->scheduleGroup->work_start)->format('H:i') : '08:00' }} - {{ $assign->scheduleGroup && $assign->scheduleGroup->work_end ? Carbon\Carbon::parse($assign->scheduleGroup->work_end)->format('H:i') : '17:00' }}
                            </td>
                            <td class="p-4 align-middle">
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $assign->scheduleGroup && $assign->scheduleGroup->type === 'Shift' ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/20' : 'bg-slate-150 text-slate-700 dark:bg-slate-800' }}">
                                    {{ $assign->scheduleGroup ? $assign->scheduleGroup->type : 'Reguler' }}
                                </span>
                            </td>
                            <td class="p-4 align-middle text-xs whitespace-nowrap text-slate-500 dark:text-slate-455">
                                {{ $assign->valid_from ? $assign->valid_from->format('d M Y') : '-' }} - {{ $assign->valid_to ? $assign->valid_to->format('d M Y') : '-' }}
                            </td>
                            <td class="p-4 align-middle text-center whitespace-nowrap">
                                @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
                                <form action="{{ route('org.schedules.assign.destroy', $assign->id) }}" method="POST" onsubmit="return confirm('Hapus penugasan jadwal karyawan ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs bg-slate-55/65 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700/80 text-rose-600 dark:text-rose-400 font-bold px-2 py-1.5 rounded-lg transition cursor-pointer">
                                        Remove
                                    </button>
                                </form>
                                @else
                                    <span class="text-xs text-slate-400 italic">No Action</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-slate-400 text-xs">
                                Penugasan jadwal belum diatur.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL: CREATE SCHEDULE GROUP --}}
    <div x-show="showGroupModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-sm" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl relative" @click.away="showGroupModal = false">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-4 uppercase tracking-wider">Tambah Kelompok Jadwal</h3>
            
            <form action="{{ route('org.schedules.group.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Kelompok Jadwal</label>
                    <input type="text" name="name" required placeholder="Contoh: Shift Security Pagi A" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Tipe Jadwal</label>
                        <select name="type" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-855 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            <option value="Reguler">Reguler Office</option>
                            <option value="Shift">Shift Kerja</option>
                            <option value="Manual Shift">Manual Shift</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Jam Masuk (Start)</label>
                        <input type="text" name="work_start" placeholder="08:00" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-855 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Jam Pulang (End)</label>
                    <input type="text" name="work_end" placeholder="17:00" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-855 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showGroupModal = false" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                        Simpan Group
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: EDIT SCHEDULE GROUP --}}
    <div x-show="showEditGroupModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-sm" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl relative" @click.away="showEditGroupModal = false">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-4 uppercase tracking-wider">Edit Kelompok Jadwal</h3>
            
            <form :action="'{{ url('dashboard/time-management/schedules/group') }}/' + editGroup.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Kelompok Jadwal</label>
                    <input type="text" name="name" required x-model="editGroup.name" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-855 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Tipe Jadwal</label>
                        <select name="type" required x-model="editGroup.type" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-855 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            <option value="Reguler">Reguler Office</option>
                            <option value="Shift">Shift Kerja</option>
                            <option value="Manual Shift">Manual Shift</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Jam Masuk (Start)</label>
                        <input type="text" name="work_start" x-model="editGroup.work_start" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-855 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Jam Pulang (End)</label>
                        <input type="text" name="work_end" x-model="editGroup.work_end" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-855 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Status Aktif</label>
                        <select name="is_active" required x-model="editGroup.is_active" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-855 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showEditGroupModal = false" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: ASSIGN SCHEDULE TO EMPLOYEE --}}
    <div x-show="showAssignModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-sm" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl w-full max-w-sm p-6 shadow-2xl relative" @click.away="showAssignModal = false">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-4 uppercase tracking-wider">Penugasan Jadwal Karyawan</h3>
            
            <form action="{{ route('org.schedules.assign.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Karyawan</label>
                    <select name="employee_id" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-855 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->nipp ?? 'No NIPP' }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Kelompok Jadwal</label>
                    <select name="schedule_group_id" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-855 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                        @foreach($groups as $g)
                            <option value="{{ $g->id }}">{{ $g->name }} ({{ $g->type }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Mulai Tanggal</label>
                        <input type="date" name="valid_from" value="{{ date('Y-m-d') }}" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-855 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Hingga Tanggal</label>
                        <input type="date" name="valid_to" value="2027-12-31" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-855 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showAssignModal = false" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-650 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                        Simpan Penugasan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
