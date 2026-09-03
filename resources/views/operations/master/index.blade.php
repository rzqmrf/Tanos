@extends('layouts.app')

@section('title', $meta['title'] . ' — Tanos ERP')

@section('content')
<div class="space-y-6 w-full" x-data="{
    showCreateModal: false,
    showEditModal: false,
    editData: {
        id: null,
        code: '',
        name: '',
        uom: '',
        scope: '',
        project_type: '',
        seq: '',
        coa: '',
        description: '',
        validity_start: '2024-01-01 00:00:00',
        validity_end: '9999-12-31 00:00:00'
    },
    openEdit(item) {
        this.editData = {
            id: item.id,
            code: item.code || '',
            name: item.name || '',
            uom: item.uom || '',
            scope: item.scope || '',
            project_type: item.project_type || '',
            seq: item.seq || '',
            coa: item.coa || '',
            description: item.description || '',
            validity_start: item.validity_start || '2024-01-01 00:00:00',
            validity_end: item.validity_end || '9999-12-31 00:00:00'
        };
        this.showEditModal = true;
    }
}">

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs font-semibold flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-200 font-bold text-base leading-none cursor-pointer">&times;</button>
    </div>
    @endif

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 dark:text-slate-400 mb-4.5">
                <a href="{{ route('dashboard.index') }}" class="hover:text-primary dark:hover:text-sky-400 transition flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Home
                </a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span>{{ $meta['breadcrumb'] }}</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-primary dark:text-sky-400 font-black">Index</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                {{ $meta['title'] }}
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 font-medium">{{ $meta['subtitle'] }}</p>
        </div>

        <button @click="showCreateModal = true"
                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center space-x-1.5 self-start sm:self-auto cursor-pointer border-0">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Create New</span>
        </button>
    </div>

    <!-- Main Card & Data Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
        
        <!-- Card Header Title -->
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $meta['title'] }} - List</h2>
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ $items->total() }} total records</span>
        </div>

        <!-- Table Toolbar -->
        <div class="p-4 sm:px-6 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
            <!-- Export & Length Controls -->
            <div class="flex items-center space-x-2 w-full sm:w-auto">
                <div class="flex items-center space-x-1 bg-slate-100 dark:bg-slate-800 p-1 rounded-xl">
                    <a href="{{ route('project.master.export', $category) }}" title="Export CSV / Excel" class="p-1.5 text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 rounded-lg transition shadow-xs cursor-pointer">
                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zM6 20V4h7v5h5v11H6z"/></svg>
                    </a>
                    <button onclick="window.print()" title="Print / PDF" class="p-1.5 text-slate-600 dark:text-slate-300 hover:bg-white dark:hover:bg-slate-700 rounded-lg transition shadow-xs cursor-pointer">
                        <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
                    </button>
                </div>

                <form method="GET" class="flex items-center">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <select name="per_page" onchange="this.form.submit()" class="bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-xs rounded-xl px-2.5 py-1.5 font-medium focus:ring-1 focus:ring-primary">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </form>
            </div>

            <!-- Search Form -->
            <form method="GET" class="w-full sm:w-64 flex items-center space-x-2">
                <input type="hidden" name="per_page" value="{{ $perPage }}">
                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 shrink-0">Search:</span>
                <input type="text" name="search" value="{{ $search }}" placeholder="Ketik kata kunci..." 
                       class="w-full bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-1.5 text-xs text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-primary transition">
            </form>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 dark:bg-slate-800/50 text-slate-600 dark:text-slate-300 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                        @if($category === 'status')
                            <th class="py-3 px-4">Seq</th>
                            <th class="py-3 px-4">Code</th>
                            <th class="py-3 px-4">Name</th>
                            <th class="py-3 px-4">Description</th>
                            <th class="py-3 px-4">Validity Period Start</th>
                            <th class="py-3 px-4">Validity Period End</th>
                        @elseif($category === 'feasibility_metrics')
                            <th class="py-3 px-4">Code</th>
                            <th class="py-3 px-4">Name</th>
                            <th class="py-3 px-4">UOM</th>
                            <th class="py-3 px-4">Description</th>
                            <th class="py-3 px-4">Validity Period Start</th>
                            <th class="py-3 px-4">Validity Period End</th>
                        @elseif($category === 'object_type')
                            <th class="py-3 px-4">Code</th>
                            <th class="py-3 px-4">Name</th>
                            <th class="py-3 px-4">Scope</th>
                            <th class="py-3 px-4">Project Type</th>
                            <th class="py-3 px-4">Description</th>
                            <th class="py-3 px-4">Validity Period Start</th>
                            <th class="py-3 px-4">Validity Period End</th>
                        @elseif($category === 'master_code')
                            <th class="py-3 px-4">Code</th>
                            <th class="py-3 px-4">Validity Period Start</th>
                            <th class="py-3 px-4">Validity Period End</th>
                        @elseif($category === 'wbs_payroll_category')
                            <th class="py-3 px-4">Id Category</th>
                            <th class="py-3 px-4">Category WBS Payroll</th>
                            <th class="py-3 px-4">coa</th>
                            <th class="py-3 px-4">Time Create</th>
                            <th class="py-3 px-4">Time Update</th>
                        @else
                            <th class="py-3 px-4">Code</th>
                            <th class="py-3 px-4">Name</th>
                            <th class="py-3 px-4">Description</th>
                            <th class="py-3 px-4">Validity Period Start</th>
                            <th class="py-3 px-4">Validity Period End</th>
                        @endif
                        <th class="py-3 px-4 text-center w-28">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                    @forelse($items as $item)
                        <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition">
                            @if($category === 'status')
                                <td class="py-3 px-4 font-bold text-slate-700 dark:text-slate-300">{{ $item->seq ?? '-' }}</td>
                                <td class="py-3 px-4 font-mono font-bold text-slate-800 dark:text-slate-200">{{ $item->code ?? '-' }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-800 dark:text-slate-100">{{ $item->name }}</td>
                                <td class="py-3 px-4 text-slate-600 dark:text-slate-400">{{ $item->description ?? '-' }}</td>
                                <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">{{ $item->validity_start }}</td>
                                <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">{{ $item->validity_end }}</td>
                            @elseif($category === 'feasibility_metrics')
                                <td class="py-3 px-4 font-mono font-bold text-slate-800 dark:text-slate-200">{{ $item->code ?? '-' }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-800 dark:text-slate-100">{{ $item->name }}</td>
                                <td class="py-3 px-4 font-bold text-indigo-600 dark:text-indigo-400">{{ $item->uom ?? '-' }}</td>
                                <td class="py-3 px-4 text-slate-600 dark:text-slate-400">{{ $item->description ?? '-' }}</td>
                                <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">{{ $item->validity_start }}</td>
                                <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">{{ $item->validity_end }}</td>
                            @elseif($category === 'object_type')
                                <td class="py-3 px-4 font-mono font-bold text-slate-800 dark:text-slate-200">{{ $item->code ?? '-' }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-800 dark:text-slate-100">{{ $item->name }}</td>
                                <td class="py-3 px-4 text-slate-700 dark:text-slate-300">{{ $item->scope ?? '-' }}</td>
                                <td class="py-3 px-4 font-bold text-slate-700 dark:text-slate-300">{{ $item->project_type ?? '-' }}</td>
                                <td class="py-3 px-4 text-slate-600 dark:text-slate-400">{{ $item->description ?? '-' }}</td>
                                <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">{{ $item->validity_start }}</td>
                                <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">{{ $item->validity_end }}</td>
                            @elseif($category === 'master_code')
                                <td class="py-3 px-4 font-mono font-bold text-slate-800 dark:text-slate-200">{{ $item->code ?? '-' }}</td>
                                <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">{{ $item->validity_start }}</td>
                                <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">{{ $item->validity_end }}</td>
                            @elseif($category === 'wbs_payroll_category')
                                <td class="py-3 px-4 font-bold text-slate-700 dark:text-slate-300">{{ $item->code ?? $item->id }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-800 dark:text-slate-100">{{ $item->name }}</td>
                                <td class="py-3 px-4 font-mono text-slate-700 dark:text-slate-300">{{ $item->coa ?? '-' }}</td>
                                <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">{{ $item->created_at->format('Y-F-d H:i:s') }}</td>
                                <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">{{ $item->updated_at->eq($item->created_at) ? 'Belum Ada Perubahan Data' : $item->updated_at->format('Y-F-d H:i:s') }}</td>
                            @else
                                <td class="py-3 px-4 font-mono font-bold text-slate-800 dark:text-slate-200">{{ $item->code ?? '-' }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-800 dark:text-slate-100">{{ $item->name }}</td>
                                <td class="py-3 px-4 text-slate-600 dark:text-slate-400">{{ $item->description ?? '-' }}</td>
                                <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">{{ $item->validity_start }}</td>
                                <td class="py-3 px-4 text-slate-500 font-mono text-[11px]">{{ $item->validity_end }}</td>
                            @endif

                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center space-x-1.5">
                                    @if($category === 'wbs_payroll_category')
                                        <a href="{{ route('project.master.wbs-payroll-categories.show', $item->id) }}" 
                                           class="p-2 rounded-lg bg-sky-500 hover:bg-sky-600 text-white transition shadow-2xs flex items-center justify-center cursor-pointer" title="Lihat Detail">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                                        </a>
                                    @else
                                        <button @click="openEdit(@js($item))" 
                                                class="p-2 rounded-lg bg-sky-500 hover:bg-sky-600 text-white transition shadow-2xs flex items-center justify-center cursor-pointer" title="Lihat Detail">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                                        </button>
                                    @endif

                                    <button @click="openEdit(@js($item))" 
                                            class="p-2 rounded-lg bg-purple-600 hover:bg-purple-700 text-white transition shadow-2xs flex items-center justify-center cursor-pointer" title="Edit Data">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                                    </button>

                                    <form action="{{ route('project.master.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white transition shadow-2xs flex items-center justify-center cursor-pointer" title="Hapus Data">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-10 text-center text-slate-400 font-medium">
                                No data available in table
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs text-slate-500 dark:text-slate-400">
            <div>
                @if($items->total() > 0)
                    Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} records
                @else
                    Showing no records
                @endif
            </div>
            <div>
                {{ $items->links() }}
            </div>
        </div>
    </div>

    <!-- MODAL: CREATE -->
    <div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-xs" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl w-full max-w-lg p-6 shadow-2xl relative" @click.away="showCreateModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-base font-black text-slate-800 dark:text-slate-100">Tambah {{ $meta['title'] }} Baru</h3>
                <button @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 font-bold text-lg cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('project.master.store') }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <input type="hidden" name="category" value="{{ $category }}">

                @if($category === 'status')
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Sequence / Urutan</label>
                    <input type="number" name="seq" required placeholder="Contoh: 1" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200">
                </div>
                @endif

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Code / Kode</label>
                        <input type="text" name="code" placeholder="Contoh: 01" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Name / Nama Data</label>
                        <input type="text" name="name" required placeholder="Nama item..." class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                @if($category === 'feasibility_metrics')
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">UOM (Unit of Measurement)</label>
                    <input type="text" name="uom" placeholder="Contoh: %, IDR, Tahun" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200">
                </div>
                @endif

                @if($category === 'object_type')
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Scope</label>
                        <input type="text" name="scope" placeholder="Contoh: Pelabuhan Regional" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Project Type</label>
                        <input type="text" name="project_type" placeholder="Contoh: Revenue / Expense" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200">
                    </div>
                </div>
                @endif

                @if($category === 'wbs_payroll_category')
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Chart of Account (COA)</label>
                    <input type="text" name="coa" placeholder="Contoh: 5050902000 - BPJS Kesehatan" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200">
                </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Description / Keterangan</label>
                    <textarea name="description" rows="2" placeholder="Keterangan..." class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Validity Period Start</label>
                        <input type="text" name="validity_start" value="2024-01-01 00:00:00" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Validity Period End</label>
                        <input type="text" name="validity_end" value="9999-12-31 00:00:00" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200 font-mono">
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition cursor-pointer border-0">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: EDIT -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-xs" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl w-full max-w-lg p-6 shadow-2xl relative" @click.away="showEditModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-base font-black text-slate-800 dark:text-slate-100">Edit Data {{ $meta['title'] }}</h3>
                <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 font-bold text-lg cursor-pointer">&times;</button>
            </div>

            <form :action="'/projects/master/items/' + editData.id" method="POST" class="mt-4 space-y-4">
                @csrf
                @method('PUT')

                @if($category === 'status')
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Sequence / Urutan</label>
                    <input type="number" name="seq" x-model="editData.seq" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200">
                </div>
                @endif

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Code / Kode</label>
                        <input type="text" name="code" x-model="editData.code" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Name / Nama Data</label>
                        <input type="text" name="name" x-model="editData.name" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                @if($category === 'feasibility_metrics')
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">UOM (Unit of Measurement)</label>
                    <input type="text" name="uom" x-model="editData.uom" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200">
                </div>
                @endif

                @if($category === 'object_type')
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Scope</label>
                        <input type="text" name="scope" x-model="editData.scope" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Project Type</label>
                        <input type="text" name="project_type" x-model="editData.project_type" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200">
                    </div>
                </div>
                @endif

                @if($category === 'wbs_payroll_category')
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Chart of Account (COA)</label>
                    <input type="text" name="coa" x-model="editData.coa" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200">
                </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Description / Keterangan</label>
                    <textarea name="description" x-model="editData.description" rows="2" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Validity Period Start</label>
                        <input type="text" name="validity_start" x-model="editData.validity_start" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Validity Period End</label>
                        <input type="text" name="validity_end" x-model="editData.validity_end" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200 font-mono">
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold shadow-sm transition cursor-pointer border-0">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
