@extends('layouts.app')

@section('title', 'Absent Types — Tanos ERP')

@section('content')
<div class="space-y-6 w-full" x-data="{ showCreateModal: false, showEditModal: false, editItem: {} }">
    <!-- Header Block -->
    <x-page-header 
        title="Absent Type Master" 
        subtitle="Kelola daftar tipe ketidakhadiran, tingkat prioritas, dan pemotongan tunjangan gaji."
        :breadcrumbs="[
            'General' => '#',
            'Human Capital' => '#',
            'Time Management' => '#',
            'Absent Type' => ''
        ]"
    >
        <x-slot:action>
            @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
            <button @click="showCreateModal = true" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 self-start sm:self-auto cursor-pointer border-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah Absent Type</span>
            </button>
            @endif
        </x-slot:action>
    </x-page-header>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Data Table Section -->
    <x-data-card 
        title="Absent Type - List" 
        :total="count($types)"
        :show-per-page="false"
        :show-search="false"
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4">Kode</th>
                        <th class="py-3.5 px-4">Nama Ketidakhadiran</th>
                        <th class="py-3.5 px-4 text-center">Gender Restriction</th>
                        <th class="py-3.5 px-4 text-center">Priority Level</th>
                        <th class="py-3.5 px-4 text-center">Deduction (Potong Gaji)</th>
                        <th class="py-3.5 px-4">Masa Berlaku</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center w-28">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($types as $type)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                        <td class="py-3.5 px-4 font-mono font-bold text-primary">
                            <span class="px-2.5 py-1 bg-primary-light text-primary font-mono font-bold rounded-md text-[11px] border border-primary-subtle">
                                {{ $type->code }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-slate-100">
                            {{ $type->name }}
                        </td>
                        <td class="py-3.5 px-4 text-center font-semibold">
                            <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                {{ $type->gender }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center font-bold">
                            {{ $type->priority_level }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if($type->deduction_absent === 'Yes')
                                <span class="px-2.5 py-1 bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 font-bold rounded-lg text-[10px] border border-rose-200 dark:border-rose-800/50 inline-flex items-center">
                                    Potong Gaji (Yes)
                                </span>
                            @else
                                <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 font-bold rounded-lg text-[10px] border border-emerald-200 dark:border-emerald-800/50 inline-flex items-center">
                                    Tetap Dibayar (No)
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 dark:text-slate-400 font-mono text-[11px]">
                            {{ $type->valid_from ? $type->valid_from->format('d M Y') : '01 Jan 2024' }} - {{ $type->valid_to ? $type->valid_to->format('d M Y') : 'Selamanya' }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if($type->active)
                            <span class="px-2.5 py-1 text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 rounded-lg inline-flex items-center">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Active
                            </span>
                            @else
                            <span class="px-2.5 py-1 text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-lg inline-flex items-center">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>Inactive
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
                            <div class="inline-flex items-center space-x-1.5">
                                <x-action-button type="edit" :click="'editItem = ' . json_encode([
                                    'id' => $type->id,
                                    'code' => $type->code,
                                    'name' => $type->name,
                                    'gender' => $type->gender,
                                    'priority_level' => $type->priority_level,
                                    'deduction_absent' => $type->deduction_absent,
                                    'valid_from' => $type->valid_from ? $type->valid_from->format('Y-m-d') : '',
                                    'valid_to' => $type->valid_to ? $type->valid_to->format('Y-m-d') : '',
                                    'active' => $type->active ? 1 : 0
                                ]) . '; showEditModal = true'" title="Edit Data" />
                                <form action="{{ route('org.absent-types.destroy', $type->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus absent type ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <x-action-button type="delete" title="Hapus Data" />
                                </form>
                            </div>
                            @else
                                <span class="text-xs text-slate-400 italic">No Action</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            Data absent type belum tersedia.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-data-card>v>

    {{-- MODAL: CREATE ABSENT TYPE --}}
    <div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-sm" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showCreateModal = false">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-4 uppercase tracking-wider">Tambah Absent Type</h3>
            
            <form action="{{ route('org.absent-types.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Kode</label>
                        <input type="text" name="code" required placeholder="Contoh: CT" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Tipe</label>
                        <input type="text" name="name" required placeholder="Contoh: Cuti Tahunan" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Gender Restriction</label>
                        <select name="gender" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            <option value="All">All Genders</option>
                            <option value="Male">Male Only</option>
                            <option value="Female">Female Only</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Priority Level</label>
                        <input type="number" name="priority_level" required min="1" value="1" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Potong Gaji?</label>
                        <select name="deduction_absent" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            <option value="No">No (Tetap Dibayar)</option>
                            <option value="Yes">Yes (Dipotong)</option>
                        </select>
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
                        Simpan Type
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: EDIT ABSENT TYPE --}}
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-sm" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showEditModal = false">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-4 uppercase tracking-wider">Edit Absent Type</h3>
            
            <form :action="'{{ url('dashboard/time-management/absent-types') }}/' + editItem.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Kode</label>
                        <input type="text" name="code" required x-model="editItem.code" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Tipe</label>
                        <input type="text" name="name" required x-model="editItem.name" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Gender Restriction</label>
                        <select name="gender" required x-model="editItem.gender" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            <option value="All">All Genders</option>
                            <option value="Male">Male Only</option>
                            <option value="Female">Female Only</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Priority Level</label>
                        <input type="number" name="priority_level" required min="1" x-model="editItem.priority_level" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Potong Gaji?</label>
                        <select name="deduction_absent" required x-model="editItem.deduction_absent" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            <option value="No">No (Tetap Dibayar)</option>
                            <option value="Yes">Yes (Dipotong)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Mulai Berlaku</label>
                        <input type="date" name="valid_from" x-model="editItem.valid_from" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Hingga Tanggal</label>
                        <input type="date" name="valid_to" x-model="editItem.valid_to" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Status Keaktifan</label>
                        <select name="active" required x-model="editItem.active" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

