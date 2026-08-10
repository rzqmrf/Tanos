@extends('layouts.app')

@section('title', 'Project WBS Structure — Tanos ERP')

@section('content')
<div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm w-full"
     x-data="{ 
         showCreateModal: false, 
         showEditModal: false,
         parentId: '',
         parentName: 'Root',
         wbsId: '',
         wbsCode: '',
         wbsName: '',
         wbsCategory: 'Upah Pokok',
         weight: 0,
         expStart: '',
         expEnd: '',
         openAddModal(id, name) {
             this.parentId = id || '';
             this.parentName = name || 'Root';
             this.wbsCode = '';
             this.wbsName = '';
             this.wbsCategory = 'Upah Pokok';
             this.weight = 0;
             this.expStart = '';
             this.expEnd = '';
             this.showCreateModal = true;
         },
         openEditModal(id, code, name, category, weight, start, end) {
             this.wbsId = id;
             this.wbsCode = code;
             this.wbsName = name;
             this.wbsCategory = category;
             this.weight = weight;
             this.expStart = start || '';
             this.expEnd = end || '';
             this.showEditModal = true;
         }
     }">
     
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Work Breakdown Structure (WBS)</h1>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Proyek: {{ $project->segment }} - {{ $project->regional }} ({{ $project->month }})</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('projects.index') }}" class="px-3.5 py-2 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 rounded-xl text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                Kembali
            </a>
            @if(in_array(session('user.role'), ['Admin', 'Project Manager', 'Finance Manager']))
            <button @click="openAddModal('', 'Root')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition">
                + Tambah WBS Root
            </button>
            <form action="{{ route('projects.wbs.send-sap', $project->id) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition">
                    Send to SAP
                </button>
            </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-5 p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tree View Structure -->
    <div class="bg-slate-50/50 dark:bg-slate-950/20 border border-slate-150 dark:border-slate-800/80 rounded-2xl p-6">
        <div class="mb-4 flex items-center justify-between border-b border-slate-100 dark:border-slate-800/80 pb-3">
            <span class="text-xs font-bold text-slate-400 uppercase">Hierarki WBS</span>
            <span class="text-xs font-bold text-slate-500 bg-white dark:bg-slate-900 px-2.5 py-1 rounded-md border border-slate-200/60 dark:border-slate-850">
                Total Root: {{ $rootWbs->count() }}
            </span>
        </div>

        @if($rootWbs->isEmpty())
            <div class="text-center py-10 text-slate-400 text-xs">
                Belum ada struktur WBS. Klik "+ Tambah WBS Root" untuk memulai.
            </div>
        @else
            <div class="space-y-4">
                @foreach($rootWbs as $wbs)
                    @include('operations.wbs_node', ['node' => $wbs, 'depth' => 0])
                @endforeach
            </div>
        @endif
    </div>

    {{-- MODAL: CREATE WBS --}}
    <div x-show="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-md" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showCreateModal = false">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-100">Tambah WBS Node</h3>
                <span class="text-[10px] bg-slate-100 dark:bg-slate-800 text-slate-500 px-2 py-0.5 rounded font-semibold" x-text="'Parent: ' + parentName"></span>
            </div>
            
            <form action="{{ route('projects.wbs.store', $project->id) }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="parent_id" :value="parentId">
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">WBS Code</label>
                    <input type="text" name="wbs_code" required placeholder="Contoh: S/PS-2024-01-00001.1" 
                           class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">WBS Name</label>
                    <input type="text" name="wbs_name" required placeholder="Contoh: Upah Pokok TAD" 
                           class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">WBS Category</label>
                        <select name="wbs_category" required 
                                class="w-full text-xs px-3 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Weight (%)</label>
                        <input type="number" name="weight" required min="0" max="100" value="0"
                               class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Expected Start</label>
                        <input type="date" name="expected_start"
                               class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Expected End</label>
                        <input type="date" name="expected_end"
                               class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800/80">
                    <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-slate-500 text-xs font-bold rounded-xl hover:bg-slate-50 transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow transition cursor-pointer">Simpan WBS</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: EDIT WBS --}}
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-md" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showEditModal = false">
            <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 mb-4">Edit WBS Node</h3>
            
            <form :action="'/dashboard/projects/{{ $project->id }}/wbs/' + wbsId" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">WBS Code</label>
                    <input type="text" name="wbs_code" x-model="wbsCode" required 
                           class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">WBS Name</label>
                    <input type="text" name="wbs_name" x-model="wbsName" required 
                           class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">WBS Category</label>
                        <select name="wbs_category" x-model="wbsCategory" required 
                                class="w-full text-xs px-3 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Weight (%)</label>
                        <input type="number" name="weight" x-model="weight" required min="0" max="100"
                               class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Expected Start</label>
                        <input type="date" name="expected_start" x-model="expStart"
                               class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Expected End</label>
                        <input type="date" name="expected_end" x-model="expEnd"
                               class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800/80">
                    <button type="button" @click="showEditModal = false" class="px-4 py-2 text-slate-500 text-xs font-bold rounded-xl hover:bg-slate-50 transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow transition cursor-pointer">Perbarui WBS</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
