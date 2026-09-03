@extends('layouts.app')

@section('title', 'Master Data WBS Payroll Category - View Detail — Tanos ERP')

@section('content')
<div class="space-y-6 w-full" x-data="{
    showEditModal: false,
    editData: {
        id: {{ $item->id }},
        code: '{{ $item->code ?? $item->id }}',
        name: '{{ addslashes($item->name) }}',
        coa: '{{ addslashes($item->coa ?? '') }}',
        description: '{{ addslashes($item->description ?? '') }}'
    }
}">

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 dark:text-slate-400 mb-4.5">
                <a href="{{ route('dashboard.index') }}" class="hover:text-primary dark:hover:text-sky-400 transition flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Home
                </a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <a href="{{ route('project.master.wbs-payroll-categories') }}" class="hover:text-primary dark:hover:text-sky-400 transition">WBS Payroll Category</a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-primary dark:text-sky-400 font-black">View Detail</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                Master Data WBS Payroll Category
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 font-medium">Pengelolaan Master Data WBS Payroll Category</p>
        </div>

        <div class="flex items-center space-x-2.5 self-start sm:self-auto">
            <a href="{{ route('project.master.wbs-payroll-categories') }}"
               class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 shadow-xs cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                <span>Back</span>
            </a>
            <button @click="showEditModal = true"
                    class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center space-x-1.5 cursor-pointer border-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Create New</span>
            </button>
        </div>
    </div>

    <!-- Main Card Detail -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
        
        <!-- Card Header Title & Action -->
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 flex items-center justify-between">
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">
                Master Data WBS Payroll Category - View WBS Payroll Category : {{ $item->code ?? $item->id }}
            </h2>
            <button @click="showEditModal = true"
                    class="px-4 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl shadow-sm transition flex items-center space-x-1.5 cursor-pointer border-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                <span>Edit</span>
            </button>
        </div>

        <!-- Form Readonly Content -->
        <div class="p-6 sm:p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <!-- Category WBS Payroll -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                    <label class="sm:w-48 text-xs font-bold text-slate-700 dark:text-slate-300">Category WBS Payroll</label>
                    <div class="flex-1">
                        <input type="text" readonly value="{{ $item->name }}" 
                               class="w-full bg-slate-100 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-700 dark:text-slate-300 cursor-not-allowed">
                    </div>
                </div>

                <!-- Chart of Account -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                    <label class="sm:w-40 text-xs font-bold text-slate-700 dark:text-slate-300">Chart of Account</label>
                    <div class="flex-1">
                        <input type="text" readonly value="{{ $item->coa ?? '-' }}" 
                               class="w-full bg-slate-100 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs font-medium text-slate-700 dark:text-slate-300 cursor-not-allowed">
                    </div>
                </div>
            </div>

            @if($item->description)
            <div class="flex flex-col sm:flex-row sm:items-start gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <label class="sm:w-48 text-xs font-bold text-slate-700 dark:text-slate-300">Deskripsi / Keterangan</label>
                <div class="flex-1">
                    <p class="text-xs text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-800/30 p-3 rounded-xl border border-slate-100 dark:border-slate-800">
                        {{ $item->description }}
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- MODAL: EDIT -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-xs" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl w-full max-w-lg p-6 shadow-2xl relative" @click.away="showEditModal = false">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-base font-black text-slate-800 dark:text-slate-100">Edit WBS Payroll Category</h3>
                <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 font-bold text-lg cursor-pointer">&times;</button>
            </div>

            <form action="{{ route('project.master.update', $item->id) }}" method="POST" class="mt-4 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Id Category / Code</label>
                    <input type="text" name="code" value="{{ $item->code ?? $item->id }}" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Category WBS Payroll</label>
                    <input type="text" name="name" value="{{ $item->name }}" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Chart of Account (COA)</label>
                    <input type="text" name="coa" value="{{ $item->coa }}" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Description / Keterangan</label>
                    <textarea name="description" rows="2" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-2 text-xs text-slate-800 dark:text-slate-200">{{ $item->description }}</textarea>
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
