@extends('layouts.app')

@section('title', 'Project WBS Structure — Tanos ERP')

@section('content')
<div class="space-y-6 w-full"
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
     
    <!-- Page Header & Action -->
    <x-page-header 
        title="Work Breakdown Structure (WBS)" 
        :subtitle="'Proyek: ' . $project->segment . ' - ' . $project->regional . ' (' . $project->month . ')'"
        :breadcrumbs="[
            'General' => '#',
            'Project System' => '#',
            'Master Projects' => route('projects.index'),
            'WBS' => ''
        ]"
    >
        <x-slot:action>
            <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap">
                <a href="{{ route('projects.index') }}" class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Kembali</span>
                </a>
                @if(in_array(session('user.role'), ['Admin', 'Project Manager', 'Finance Manager']))
                <button @click="openAddModal('', 'Root')" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 cursor-pointer border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Tambah WBS Root</span>
                </button>
                <form action="{{ route('projects.wbs.send-sap', $project->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 cursor-pointer border-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/></svg>
                        <span>Send to SAP</span>
                    </button>
                </form>
                @endif
            </div>
        </x-slot:action>
    </x-page-header>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tree View Structure -->
    <div class="bg-slate-50/50 dark:bg-slate-950/20 border border-slate-150 dark:border-slate-800/80 rounded-xl p-6">
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
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showCreateModal = false">
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
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showEditModal = false">
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
