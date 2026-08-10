@extends('layouts.app')

@section('title', 'Time Evaluation Rules — Tanos ERP')

@section('content')
<div class="space-y-6 w-full" x-data="{ showCreateModal: false, showEditModal: false, editItem: {} }">
    <!-- Header Block -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-650 dark:text-indigo-400 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Time Evaluation</h1>
                <p class="text-xs text-slate-400 dark:text-slate-550 font-semibold">Tentukan parameter dispensasi terlambat masuk dan pulang cepat, serta aturan keaktifan rekap absensi.</p>
            </div>
        </div>
        
        @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
        <div>
            <button @click="showCreateModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition cursor-pointer">
                + Create Parameter Rules
            </button>
        </div>
        @endif
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Rules List Section -->
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
        <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <th class="p-4 align-middle">Nama Aturan</th>
                        <th class="p-4 align-middle">Deskripsi</th>
                        <th class="p-4 align-middle text-center">Toleransi Terlambat</th>
                        <th class="p-4 align-middle text-center">Toleransi Pulang Cepat</th>
                        <th class="p-4 align-middle">Masa Berlaku</th>
                        <th class="p-4 align-middle text-center">Status</th>
                        <th class="p-4 align-middle text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-350">
                    @forelse($evaluations as $eval)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition {{ !$eval->is_active ? 'opacity-65' : '' }}">
                        <td class="p-4 align-middle font-bold text-slate-855 dark:text-slate-200">
                            {{ $eval->name }}
                        </td>
                        <td class="p-4 align-middle">
                            {{ $eval->description ?? '-' }}
                        </td>
                        <td class="p-4 align-middle text-center font-bold text-slate-700 dark:text-slate-300">
                            {{ $eval->late_tolerance_minutes }} Menit
                        </td>
                        <td class="p-4 align-middle text-center font-bold text-slate-700 dark:text-slate-300">
                            {{ $eval->early_departure_minutes }} Menit
                        </td>
                        <td class="p-4 align-middle text-xs text-slate-500 dark:text-slate-450 whitespace-nowrap">
                            {{ $eval->valid_from->format('d M Y') }} - {{ $eval->valid_to->format('d M Y') }}
                        </td>
                        <td class="p-4 align-middle text-center whitespace-nowrap">
                            <span class="inline-block px-2.5 py-1 {{ $eval->is_active ? 'bg-emerald-55/10 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-450' }} rounded-md text-xs font-bold">
                                {{ $eval->is_active ? 'Active (Running)' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="p-4 align-middle text-center whitespace-nowrap">
                            @if(in_array(session('user.role'), ['Admin', 'HR Manager']))
                            <div class="flex items-center justify-center space-x-2">
                                <button @click="editItem = { id: '{{ $eval->id }}', name: '{{ $eval->name }}', description: '{{ $eval->description }}', valid_from: '{{ $eval->valid_from->format('Y-m-d') }}', valid_to: '{{ $eval->valid_to->format('Y-m-d') }}', late_tolerance_minutes: '{{ $eval->late_tolerance_minutes }}', early_departure_minutes: '{{ $eval->early_departure_minutes }}', is_active: {{ $eval->is_active ? 1 : 0 }} }; showEditModal = true" class="text-xs bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700/80 text-indigo-650 dark:text-indigo-400 font-bold px-2.5 py-1.5 rounded-lg transition cursor-pointer">
                                    Edit
                                </button>
                                <form action="{{ route('org.evaluations.destroy', $eval->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aturan toleransi ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700/80 text-rose-600 dark:text-rose-400 font-bold px-2.5 py-1.5 rounded-lg transition cursor-pointer">
                                        Delete
                                    </button>
                                </form>
                            </div>
                            @else
                                <span class="text-xs text-slate-400 italic">No Action</span>
                            @endif
                        </td>
                    </tr>
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
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showCreateModal = false">
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
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showEditModal = false">
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
