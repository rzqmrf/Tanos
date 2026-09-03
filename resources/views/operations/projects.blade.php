@extends('layouts.app')

@section('title', 'Project Definition — Tanos ERP')

@section('content')
<div class="space-y-6 w-full" x-data="projectDefinitionManager()">
    <!-- Header & Action -->
    <x-page-header 
        title="Project Definition" 
        subtitle="Project System - Kelola definisi dan parameter struktur proyek Pelindo."
        :breadcrumbs="[
            'General' => '#',
            'Project System' => '#',
            'Project Definition' => ''
        ]"
    >
        <x-slot:action>
            <button @click="openCreateModal()" 
                    class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 cursor-pointer border-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Create New</span>
            </button>
        </x-slot:action>
    </x-page-header>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-sm font-semibold flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Main Card Container matching Screenshot 2 -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6">
        <!-- Card Title -->
        <h2 class="text-base font-bold text-slate-800 dark:text-slate-100 mb-6">
            Project Definition - List
        </h2>

        <!-- Top Controls: Export Buttons (Left) & Search (Right) -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
            <!-- 3 Export Buttons matching Tanos screenshot -->
            <div class="flex items-center space-x-2">
                <!-- Copy / Text Export -->
                <button type="button" onclick="copyTableToClipboard()" title="Copy Table" class="p-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-lg transition border border-slate-200/80 dark:border-slate-700 shadow-2xs cursor-pointer">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
                </button>
                <!-- PDF Export -->
                <button type="button" onclick="window.print()" title="Export PDF" class="p-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-lg transition border border-slate-200/80 dark:border-slate-700 shadow-2xs cursor-pointer flex items-center space-x-1">
                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 7.5c0 .83-.67 1.5-1.5 1.5H9v2H7.5V7H10c.83 0 1.5.67 1.5 1.5v1zm5 2c0 .83-.67 1.5-1.5 1.5h-2.5V7H15c.83 0 1.5.67 1.5 1.5v3zm4-3H19v1h1.5V11H19v2h-1.5V7h3v1.5z"/></svg>
                    <span class="text-[10px] font-bold text-slate-600 dark:text-slate-400">PDF</span>
                </button>
                <!-- Excel Export -->
                <a href="{{ route('reports.export', ['format' => 'excel']) }}" title="Export Excel" class="p-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-lg transition border border-slate-200/80 dark:border-slate-700 shadow-2xs cursor-pointer flex items-center space-x-1">
                    <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-2.5l-1.5 2.5-1.5-2.5H9l2.5 4L9 21h2.5l1.5-2.5 1.5 2.5H17l-2.5-4 2.5-4z"/></svg>
                    <span class="text-[10px] font-bold text-slate-600 dark:text-slate-400">X</span>
                </a>
            </div>

            <!-- Search matching Tanos layout: Label Search: then input -->
            <form method="GET" action="{{ route('projects.index') }}" class="flex items-center space-x-2">
                <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Search:</label>
                <input type="text" name="search" value="{{ $search ?? '' }}" 
                       class="px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-slate-700 dark:text-slate-200 focus:outline-none focus:border-primary w-48 sm:w-60">
            </form>
        </div>

        <!-- Table matching Screenshot 2: Code, Id Project Humanis, Name, Description, Action -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200/80 dark:border-slate-800 text-slate-800 dark:text-slate-100 font-bold">
                        <th class="py-3 px-4">Code</th>
                        <th class="py-3 px-4">Id Project Humanis</th>
                        <th class="py-3 px-4">Name</th>
                        <th class="py-3 px-4">Description</th>
                        <th class="py-3 px-4 text-center w-28">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-slate-700 dark:text-slate-300">
                    @forelse($projects as $item)
                    <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                        <td class="py-3.5 px-4 font-mono font-medium text-slate-800 dark:text-slate-200 whitespace-nowrap">
                            {{ $item->project_code }}
                        </td>
                        <td class="py-3.5 px-4 font-medium text-slate-600 dark:text-slate-400">
                            {{ $item->id_project_humanis ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-slate-100">
                            {{ $item->project_name }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 dark:text-slate-400">
                            {{ $item->description ?? 'Tenaga Alih Daya' }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <!-- Action Buttons: Blue Magnifier (View), Purple Pencil (Edit), Red Trash (Delete) -->
                            <div class="flex items-center justify-center space-x-1.5">
                                <!-- Blue View Detail -->
                                <a href="{{ route('projects.show', $item->id) }}" 
                                   title="View Detail"
                                   class="p-2 rounded-lg bg-sky-500 hover:bg-sky-600 text-white transition shadow-2xs flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </a>

                                <!-- Purple Edit -->
                                <button type="button" 
                                        @click="openEditModal(@js($item))"
                                        title="Edit"
                                        class="p-2 rounded-lg bg-purple-600 hover:bg-purple-700 text-white transition shadow-2xs flex items-center justify-center cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>

                                <!-- Red Delete -->
                                <form action="{{ route('projects.destroy', $item->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus Project Definition ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            title="Delete"
                                            class="p-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white transition shadow-2xs flex items-center justify-center cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">
                            Tidak ada data Project Definition yang sesuai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-5 pt-4 border-t border-slate-100 dark:border-slate-800">
            {{ $projects->links() }}
        </div>
    </div>

    <!-- MODAL CREATE / EDIT -->
    <div x-show="isModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 rounded-xl max-w-2xl w-full p-6 shadow-xl border border-slate-100 dark:border-slate-800" @click.outside="isModalOpen = false">
            <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-100" x-text="isEdit ? 'Edit Project Definition' : 'Create New Project Definition'"></h3>
                <button @click="isModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form :action="formAction" method="POST" class="space-y-4 text-xs">
                @csrf
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Code *</label>
                        <input type="text" name="project_code" x-model="formData.project_code" required placeholder="Contoh: S/PS-2024-01-00010" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-primary font-mono">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Id Project Humanis</label>
                        <input type="text" name="id_project_humanis" x-model="formData.id_project_humanis" placeholder="Contoh: 108" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-primary">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Name *</label>
                    <input type="text" name="project_name" x-model="formData.project_name" required placeholder="Contoh: TAD - REG3 - JOPRO ..." class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-primary uppercase">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Description</label>
                    <textarea name="description" x-model="formData.description" rows="2" placeholder="Deskripsi proyek..." class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-primary resize-none"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Vendor / Customer *</label>
                        <input type="text" name="vendor" x-model="formData.vendor" list="partner-customer-list" required placeholder="PT Pelabuhan Indonesia (Persero)..." class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-primary">
                        <datalist id="partner-customer-list">
                            @if(isset($partners))
                                @foreach($partners as $partner)
                                    <option value="{{ $partner->name }}">{{ $partner->code }}</option>
                                @endforeach
                            @endif
                        </datalist>
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Location *</label>
                        <input type="text" name="location" x-model="formData.location" placeholder="Contoh: Tanjung Perak" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-primary">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Project Category *</label>
                        <input type="text" name="project_category" x-model="formData.project_category" placeholder="01. Tenaga Alih Daya Operasional" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Contract Type *</label>
                        <input type="text" name="contract_type" x-model="formData.contract_type" placeholder="NON-JOPRO" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-primary">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Regional Unit *</label>
                        <input type="text" name="regional_unit" x-model="formData.regional_unit" placeholder="Regional PDS Jawa" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Unit Kerja *</label>
                        <input type="text" name="unit_kerja" x-model="formData.unit_kerja" placeholder="PT Pelabuhan Indonesia..." class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-primary">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Expected Start</label>
                        <input type="date" name="start_date" x-model="formData.start_date" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-primary">
                    </div>
                    <div>
                        <label class="block font-semibold text-slate-700 dark:text-slate-300 mb-1">Expected End</label>
                        <input type="date" name="end_date" x-model="formData.end_date" class="w-full px-3 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-800 dark:text-slate-100 focus:outline-none focus:border-primary">
                    </div>
                </div>

                <div class="mt-5 pt-3 border-t border-slate-100 dark:border-slate-800 flex justify-end space-x-2">
                    <button type="button" @click="isModalOpen = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-700 dark:text-slate-200 font-bold rounded-lg text-xs transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 font-bold rounded-lg text-xs bg-emerald-600 hover:bg-emerald-700 text-white transition cursor-pointer">
                        Simpan Project
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function projectDefinitionManager() {
    return {
        isModalOpen: false,
        isEdit: false,
        formAction: '{{ route("projects.store") }}',
        formData: {
            project_code: '',
            id_project_humanis: '',
            project_name: '',
            description: 'Tenaga Alih Daya',
            vendor: 'PT Pelabuhan Indonesia (Persero) Regional 3',
            project_category: '01. Tenaga Alih Daya Operasional',
            contract_type: 'NON-JOPRO',
            location: 'Tanjung Perak',
            regional_unit: 'Regional PDS Jawa',
            unit_kerja: 'PT Pelabuhan Indonesia (Persero) Regional 3 - Subreg Jawa - Tanjung Perak',
            start_date: '2025-02-19',
            end_date: '2025-12-31',
        },
        openCreateModal() {
            this.isEdit = false;
            this.formAction = '{{ route("projects.store") }}';
            this.formData = {
                project_code: 'S/PS-2024-01-000' + (Math.floor(Math.random() * 90) + 10),
                id_project_humanis: (Math.floor(Math.random() * 90) + 110).toString(),
                project_name: '',
                description: 'Tenaga Alih Daya',
                vendor: 'PT Pelabuhan Indonesia (Persero) Regional 3',
                project_category: '01. Tenaga Alih Daya Operasional',
                contract_type: 'NON-JOPRO',
                location: 'Tanjung Perak',
                regional_unit: 'Regional PDS Jawa',
                unit_kerja: 'PT Pelabuhan Indonesia (Persero) Regional 3 - Subreg Jawa - Tanjung Perak',
                start_date: '2025-02-19',
                end_date: '2025-12-31',
            };
            this.isModalOpen = true;
        },
        openEditModal(item) {
            this.isEdit = true;
            this.formAction = '/dashboard/projects/' + item.id;
            this.formData = {
                project_code: item.project_code || '',
                id_project_humanis: item.id_project_humanis || '',
                project_name: item.project_name || '',
                description: item.description || 'Tenaga Alih Daya',
                vendor: item.vendor || item.customer_name || '',
                project_category: item.project_category || '01. Tenaga Alih Daya Operasional',
                contract_type: item.contract_type || 'NON-JOPRO',
                location: item.location || '',
                regional_unit: item.regional_unit || '',
                unit_kerja: item.unit_kerja || '',
                start_date: item.start_date ? item.start_date.substring(0, 10) : '',
                end_date: item.end_date ? item.end_date.substring(0, 10) : '',
            };
            this.isModalOpen = true;
        }
    }
}

function copyTableToClipboard() {
    let text = "Code\tId Project Humanis\tName\tDescription\n";
    document.querySelectorAll("tbody tr").forEach(tr => {
        let cells = tr.querySelectorAll("td");
        if (cells.length >= 4) {
            text += `${cells[0].innerText.trim()}\t${cells[1].innerText.trim()}\t${cells[2].innerText.trim()}\t${cells[3].innerText.trim()}\n`;
        }
    });
    navigator.clipboard.writeText(text).then(() => {
        alert("Data tabel Project Definition berhasil di-copy ke clipboard!");
    });
}
</script>
@endsection
