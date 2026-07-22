@extends('layouts.app')

@section('title', 'Master Data: Projects — Tanos ERP')

@section('content')
<div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm w-full">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18M2.25 13.5a2.25 2.25 0 0 0-2.25 2.25v3.75A2.25 2.25 0 0 0 2.25 21.75h19.5a2.25 2.25 0 0 0 2.25-2.25v-3.75a2.25 2.25 0 0 0-2.25-2.25M2.25 13.5V4.5A2.25 2.25 0 0 1 4.5 2.25h15a2.25 2.25 0 0 1 2.25 2.25v9m-18 0J" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Master Data: Projects</h1>
                <p class="text-sm text-slate-400 dark:text-slate-500">Manajemen alokasi biaya dan status project perusahaan.</p>
            </div>
        </div>
        <button onclick="document.getElementById('modal-create-project').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium shadow-sm transition shrink-0">
            + Tambah Project
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Data Table Section -->
    <div class="mt-6 border-t border-slate-100 dark:border-slate-800 pt-6">
        <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <th class="p-4">Bulan</th>
                        <th class="p-4">Regional</th>
                        <th class="p-4">Segment</th>
                        <th class="p-4">Cost</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-300">
                    @forelse($projects as $item)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                        <td class="p-4 text-slate-500 dark:text-slate-400">{{ $item->month }}</td>
                        <td class="p-4 text-slate-500 dark:text-slate-400">{{ $item->regional }}</td>
                        <td class="p-4 text-slate-500 dark:text-slate-400">{{ $item->segment }}</td>
                        <td class="p-4 font-semibold text-slate-900 dark:text-slate-100">Rp {{ number_format($item->cost, 0, ',', '.') }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 {{ $item->active == 1 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-400' }} rounded-md text-xs font-bold">
                                {{ $item->active == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="p-4 flex items-center justify-center space-x-2">
                            <button onclick="openEditModal({{ json_encode($item) }})" class="p-1.5 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 bg-blue-50 dark:bg-blue-950/30 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                            </button>
                            <form action="/dashboard/projects/{{ $item->id }}" method="POST" onsubmit="return confirm('Yakin hapus project ini?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 bg-red-50 dark:bg-red-950/30 rounded-lg transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m12 6c0 1.66-1.34 3-3 3H6c-1.34 0-3-1.34-3-3V7h18v8Z" /></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center">
                            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/10 border border-slate-200 dark:border-slate-800 p-6 max-w-md mx-auto">
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Data project belum ditambahkan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $projects->links() }}
        </div>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div id="modal-create-project" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-100 dark:border-slate-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Tambah Data Project</h3>
            <button onclick="document.getElementById('modal-create-project').classList.add('hidden')" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form action="/dashboard/projects" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Bulan</label>
                <select name="month" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    <option value="">-- Pilih Bulan --</option>
                    @foreach($months as $m)
                        <option value="{{ $m }}">{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Regional</label>
                <select name="regional" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    <option value="">-- Pilih Regional --</option>
                    @foreach($regionals as $reg)
                        <option value="{{ $reg->name }}">{{ $reg->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Segment</label>
                <select name="segment" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    <option value="">-- Pilih Segment --</option>
                    @foreach($segments as $seg)
                        <option value="{{ $seg->name }}">{{ $seg->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Cost (Angka Saja)</label>
                <input type="number" name="cost" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Status</label>
                <select name="active" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('modal-create-project').classList.add('hidden')" class="px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium shadow-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT -->
<div id="modal-edit-project" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-100 dark:border-slate-800">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Edit Data Project</h3>
            <button onclick="document.getElementById('modal-edit-project').classList.add('hidden')" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form id="form-edit-project" action="" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Bulan</label>
                <select id="edit-month" name="month" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    @foreach($months as $m)
                        <option value="{{ $m }}">{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Regional</label>
                <select id="edit-regional" name="regional" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    @foreach($regionals as $reg)
                        <option value="{{ $reg->name }}">{{ $reg->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Segment</label>
                <select id="edit-segment" name="segment" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    @foreach($segments as $seg)
                        <option value="{{ $seg->name }}">{{ $seg->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Cost</label>
                <input type="number" id="edit-cost" name="cost" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase mb-1">Status</label>
                <select id="edit-active" name="active" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-sm focus:outline-none focus:border-blue-500 dark:focus:border-blue-400">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('modal-edit-project').classList.add('hidden')" class="px-4 py-2 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(project) {
        const modal = document.getElementById('modal-edit-project');
        const form = document.getElementById('form-edit-project');
        form.action = `/dashboard/projects/${project.id}`;
        document.getElementById('edit-month').value = project.month || '';
        document.getElementById('edit-regional').value = project.regional || '';
        document.getElementById('edit-segment').value = project.segment || '';
        document.getElementById('edit-cost').value = project.cost || '';
        document.getElementById('edit-active').value = project.active !== undefined ? project.active : '1';
        modal.classList.remove('hidden');
    }
</script>
@endsection
