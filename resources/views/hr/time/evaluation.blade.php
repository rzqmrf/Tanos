@extends('layouts.app')

@section('title', 'Time Evaluation Rules — Tanos ERP')

@section('content')
<div class="space-y-6 w-full" x-data="{ showCreateModal: false, showEditModal: false, editItem: {} }">
    <!-- Header Block -->
    <x-page-header 
        title="Time Evaluation Rules" 
        subtitle="Tentukan parameter dispensasi terlambat masuk dan pulang cepat, serta aturan keaktifan rekap absensi."
        :breadcrumbs="[
            'General' => '#',
            'Human Resource' => '#',
            'Time Evaluation' => ''
        ]"
            <x-slot:action>
            @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
            <a href="{{ route('org.evaluations.create') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 cursor-pointer border-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Create Parameter Rules</span>
            </a>
            @endif
        </x-slot:action>
    </x-page-header>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Rules List Section -->
    <x-data-card 
        title="Time Evaluation Rules - List" 
        :total="count($evaluations)"
        :show-per-page="false"
        :show-search="false"
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4">Nama Aturan</th>
                        <th class="py-3.5 px-4">Deskripsi</th>
                        <th class="py-3.5 px-4 text-center">Toleransi Terlambat</th>
                        <th class="py-3.5 px-4 text-center">Toleransi Pulang Cepat</th>
                        <th class="py-3.5 px-4">Masa Berlaku</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-center w-28">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($evaluations as $eval)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition {{ !$eval->is_active ? 'opacity-65' : '' }}">
                        <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-slate-100">
                            <a href="{{ route('org.evaluations.show', $eval->id) }}" class="hover:text-primary transition">
                                {{ $eval->name }}
                            </a>
                        </td>
                        <td class="py-3.5 px-4">
                            {{ $eval->description ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-center font-bold text-slate-700 dark:text-slate-300">
                            {{ $eval->late_tolerance_minutes }} Menit
                        </td>
                        <td class="py-3.5 px-4 text-center font-bold text-slate-700 dark:text-slate-300">
                            {{ $eval->early_departure_minutes }} Menit
                        </td>
                        <td class="py-3.5 px-4 text-xs font-mono text-slate-600 dark:text-slate-400 whitespace-nowrap">
                            {{ $eval->valid_from ? $eval->valid_from->format('d M Y') : '—' }} - {{ $eval->valid_to ? $eval->valid_to->format('d M Y') : '—' }}
                        </td>
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            <span class="inline-block px-2.5 py-1 {{ $eval->is_active ? 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }} rounded-lg text-[10px] font-bold">
                                {{ $eval->is_active ? 'ACTIVE (RUNNING)' : 'INACTIVE' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
                            <div class="flex items-center justify-center space-x-1.5">
                                <x-action-button type="view" :href="route('org.evaluations.show', $eval->id)" title="Lihat Detail Parameter" />
                                <x-action-button type="edit" :href="route('org.evaluations.edit', $eval->id)" title="Edit Parameter" />
                                <form action="{{ route('org.evaluations.destroy', $eval->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aturan toleransi ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <x-action-button type="delete" title="Hapus Parameter" />
                                </form>
                            </div>
                            @else
                                <span class="text-xs text-slate-400 italic">No Action</span>
                            @endif
                        </td>
                    </tr>r>
                    @empty
                    <tr>
                        <td colspan="7" class="p-12 text-center text-slate-400 text-xs">
                            Belum ada parameter evaluasi toleransi kehadiran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL: CREATE TIME EVALUATION --}}
    <div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-sm" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showCreateModal = false">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-4 uppercase tracking-wider">Tambah Parameter Toleransi</h3>
            
            <form action="{{ route('org.evaluations.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Aturan Toleransi</label>
                    <input type="text" name="name" required placeholder="Contoh: Dispensasi Proyek BUMN Pelindo" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Toleransi Telambat (Menit)</label>
                        <input type="number" name="late_tolerance_minutes" required min="0" value="15" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Toleransi Pulang Cepat (Menit)</label>
                        <input type="number" name="early_departure_minutes" required min="0" value="15" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Mulai Tanggal</label>
                        <input type="date" name="valid_from" value="{{ date('Y-m-d') }}" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Hingga Tanggal</label>
                        <input type="date" name="valid_to" value="2027-12-31" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Deskripsi / Keterangan</label>
                    <textarea name="description" rows="2" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200" placeholder="Keterangan toleransi..."></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-50 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer">
                        Aktifkan Aturan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: EDIT TIME EVALUATION --}}
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-sm" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showEditModal = false">
            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-4 uppercase tracking-wider">Edit Parameter Toleransi</h3>
            
            <form :action="'{{ url('dashboard/time-management/evaluations') }}/' + editItem.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Nama Aturan Toleransi</label>
                    <input type="text" name="name" required x-model="editItem.name" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Toleransi Telambat (Menit)</label>
                        <input type="number" name="late_tolerance_minutes" required min="0" x-model="editItem.late_tolerance_minutes" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Toleransi Pulang Cepat (Menit)</label>
                        <input type="number" name="early_departure_minutes" required min="0" x-model="editItem.early_departure_minutes" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Mulai Tanggal</label>
                        <input type="date" name="valid_from" x-model="editItem.valid_from" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Hingga Tanggal</label>
                        <input type="date" name="valid_to" x-model="editItem.valid_to" required class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Status Keaktifan</label>
                        <select name="is_active" required x-model="editItem.is_active" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Deskripsi / Keterangan</label>
                    <textarea name="description" rows="2" x-model="editItem.description" class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200"></textarea>
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

