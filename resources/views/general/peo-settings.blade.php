@extends('layouts.app')

@section('title', 'Mapping PEO Setting — Tanos ERP')

@section('content')
<div class="space-y-6">
    {{-- ALERT MESSAGES --}}
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs font-semibold flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-200 font-bold text-base leading-none">&times;</button>
    </div>
    @endif
    <x-page-header 
        title="Mapping PEO Setting" 
        subtitle="Pengelolaan Mapping Integrasi Dokumen PEO"
        :breadcrumbs="[
            'General' => '#',
            'Settings' => '#',
            'PEO Setting' => ''
        ]"
    >
        <x-slot:action>
            <div class="flex items-center gap-2.5 shrink-0 flex-wrap self-start md:self-auto">
                <button onclick="document.getElementById('modal-create-peo').classList.remove('hidden')" 
                    class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center space-x-1.5 cursor-pointer border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Create New</span>
                </button>
                <button onclick="alert('Dokumen Header Setting telah dikonfigurasi.')" 
                    class="px-4 py-2.5 bg-primary hover:bg-primary-hover text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center space-x-1.5 cursor-pointer border-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                    <span>Document Header Setting</span>
                </button>
            </div>
        </x-slot:action>
    </x-page-header>

    {{-- MAIN CONTENT CARD --}}
    <x-data-card 
        title="PEO Setting - List" 
        :total="$peoSettings->total()"
        :search-route="route('peo.index')"
    >
        {{-- TABS NAV --}}
        <div class="pb-3 border-b border-slate-200/80 dark:border-slate-800 flex items-center gap-6">
            <a href="{{ route('peo.index', ['tab' => 'Berita Acara', 'per_page' => $perPage, 'search' => $search]) }}" 
                class="pb-2.5 text-xs font-bold transition-all border-b-2 {{ $activeTab === 'Berita Acara' ? 'border-primary text-primary dark:text-sky-400 font-black' : 'border-transparent text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">
                Berita Acara
            </a>
            <a href="{{ route('peo.index', ['tab' => 'Surat Keluar', 'per_page' => $perPage, 'search' => $search]) }}" 
                class="pb-2.5 text-xs font-bold transition-all border-b-2 {{ $activeTab === 'Surat Keluar' ? 'border-primary text-primary dark:text-sky-400 font-black' : 'border-transparent text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200' }}">
                Surat Keluar
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-5 text-center">NO</th>
                        <th class="py-3.5 px-5">NAMA CUSTOMER</th>
                        <th class="py-3.5 px-5">NAMA PROYEK</th>
                        <th class="py-3.5 px-5 text-center w-28">ACTION</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($peoSettings as $index => $item)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                        <td class="py-3.5 px-5 text-center font-semibold text-slate-400">
                            {{ $peoSettings->firstItem() + $index }}
                        </td>
                        <td class="py-3.5 px-5 font-bold text-slate-800 dark:text-slate-100 uppercase">
                            <a href="{{ route('peo.show', $item->id) }}" class="hover:text-primary transition">
                                {{ $item->customer }}
                            </a>
                        </td>
                        <td class="py-3.5 px-5 text-slate-700 dark:text-slate-300 uppercase font-semibold">
                            {{ $item->project_name }}
                        </td>
                        <td class="py-3.5 px-5 text-center whitespace-nowrap">
                            <div class="inline-flex items-center space-x-1.5">
                                <x-action-button type="view" :href="route('peo.show', $item->id)" title="View Detail" />
                                <x-action-button type="edit" :click="'openEditModal(' . json_encode($item) . ')'" title="Edit Setting" />
                                <form action="{{ route('peo.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus mapping PEO ini?')">
                                    @csrf @method('DELETE')
                                    <x-action-button type="delete" title="Hapus" />
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            Tidak ada data PEO Setting untuk kategori <strong>{{ $activeTab }}</strong>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION FOOTER --}}
        @if($peoSettings->hasPages())
        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
            {{ $peoSettings->links() }}
        </div>
        @endif
    </x-data-card>
</div>

{{-- MODAL CREATE PEO SETTING --}}
<div id="modal-create-peo" class="hidden fixed inset-0 z-50 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-xl max-w-lg w-full shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="px-6 py-4 bg-primary text-white flex items-center justify-between">
            <h3 class="text-sm font-bold text-white">+ Tambah Mapping PEO Setting</h3>
            <button onclick="document.getElementById('modal-create-peo').classList.add('hidden')" class="text-white hover:text-slate-200 font-bold">&times;</button>
        </div>
        <form action="{{ route('peo.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tipe Dokumen PEO *</label>
                <select name="document_type" required class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
                    <option value="Berita Acara">Berita Acara</option>
                    <option value="Surat Keluar">Surat Keluar</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Customer *</label>
                <input type="text" name="customer" required placeholder="Contoh: PT PELINDO MARINE SERVICE" class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 uppercase">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kode Proyek *</label>
                <input type="text" name="project_code" required placeholder="P002" class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 uppercase">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Proyek *</label>
                <input type="text" name="project_name" required placeholder="JASA TUNTUN & TUNDA PELINDO" class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 uppercase">
            </div>
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modal-create-peo').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md border-0 cursor-pointer">Simpan Mapping</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT PEO SETTING --}}
<div id="modal-edit-peo" class="hidden fixed inset-0 z-50 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-xl max-w-lg w-full shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden">
        <div class="px-6 py-4 bg-primary text-white flex items-center justify-between">
            <h3 class="text-sm font-bold text-white">Edit Mapping PEO Setting</h3>
            <button onclick="document.getElementById('modal-edit-peo').classList.add('hidden')" class="text-white hover:text-slate-200 font-bold">&times;</button>
        </div>
        <form id="form-edit-peo" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tipe Dokumen PEO *</label>
                <select id="edit-document_type" name="document_type" required class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
                    <option value="Berita Acara">Berita Acara</option>
                    <option value="Surat Keluar">Surat Keluar</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Customer *</label>
                <input type="text" id="edit-customer" name="customer" required class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 uppercase">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kode Proyek *</label>
                <input type="text" id="edit-project_code" name="project_code" required class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 uppercase">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Proyek *</label>
                <input type="text" id="edit-project_name" name="project_name" required class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 uppercase">
            </div>
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modal-edit-peo').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-purple-600 hover:bg-purple-700 rounded-xl shadow-md border-0 cursor-pointer">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditModal(item) {
    document.getElementById('form-edit-peo').action = '/dashboard/peo-settings/' + item.id;
    document.getElementById('edit-document_type').value = item.document_type || '';
    document.getElementById('edit-customer').value = item.customer || '';
    document.getElementById('edit-project_code').value = item.project_code || '';
    document.getElementById('edit-project_name').value = item.project_name || '';
    document.getElementById('modal-edit-peo').classList.remove('hidden');
}

function exportToCSV() {
    const table = document.getElementById('peo-table');
    if (!table) return;
    let csv = [];
    table.querySelectorAll('tr').forEach(row => {
        const cols = row.querySelectorAll('th, td');
        const rowData = [];
        cols.forEach((col, index) => {
            if (index < 3) {
                let text = col.innerText.replace(/"/g, '""').trim();
                rowData.push('"' + text + '"');
            }
        });
        if (rowData.length) csv.push(rowData.join(','));
    });
    const blob = new Blob(["\uFEFF" + csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'PEO_Setting_List_' + new Date().toISOString().slice(0,10) + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection
