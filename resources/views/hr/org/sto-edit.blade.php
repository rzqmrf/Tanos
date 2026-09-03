@extends('layouts.app')

@section('title', 'Edit Unit Kerja — ' . $division->name . ' — Tanos ERP')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Page Header -->
    <x-page-header 
        :title="'Edit Unit: ' . $division->name" 
        subtitle="Ubah informasi atribut unit kerja, parent unit, regional, atau cost center."
        :breadcrumbs="[
            'General' => '#',
            'Human Resource' => '#',
            'Organizational Structure' => route('org.sto.index'),
            'Edit Unit' => ''
        ]"
    >
        <x-slot:action>
            <a href="{{ route('org.sto.show', $division->id) }}" 
               class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Kembali</span>
            </a>
        </x-slot:action>
    </x-page-header>

    <!-- Error Notification -->
    @if($errors->any())
    <div class="p-4 bg-rose-50 dark:bg-rose-950/30 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 rounded-xl text-xs font-bold space-y-1">
        @foreach($errors->all() as $err)
            <p>• {{ $err }}</p>
        @endforeach
    </div>
    @endif

    <!-- Form Container Card -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden p-6 sm:p-8">
        <form action="{{ route('org.sto.update', $division->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Unit -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Kode Unit (SAP / MDM) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="code" value="{{ old('code', $division->code) }}" required 
                           class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 outline-none text-slate-800 dark:text-slate-100 transition">
                </div>

                <!-- Nama Unit -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Nama Unit / Departemen <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $division->name) }}" required 
                           class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 outline-none text-slate-800 dark:text-slate-100 transition">
                </div>

                <!-- Induk Unit (Parent) -->
                <div class="space-y-1.5 md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Induk Unit / Atasan Langsung (Parent)
                    </label>
                    <select name="parent_id" 
                            class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-800 dark:text-slate-100 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 transition">
                        <option value="">-- Tanpa Induk (Root Level / Direksi) --</option>
                        @foreach($rootDivs as $div)
                            <option value="{{ $div->id }}" {{ old('parent_id', $division->parent_id) == $div->id ? 'selected' : '' }}>
                                {{ $div->name }} ({{ $div->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipe Unit -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Tipe Unit <span class="text-rose-500">*</span>
                    </label>
                    <select name="unit_type" required 
                            class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-800 dark:text-slate-100 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 transition">
                        <option value="Division" {{ old('unit_type', $division->unit_type) == 'Division' ? 'selected' : '' }}>Division / Direktorat</option>
                        <option value="Department" {{ old('unit_type', $division->unit_type) == 'Department' ? 'selected' : '' }}>Department / Bagian</option>
                        <option value="Section" {{ old('unit_type', $division->unit_type) == 'Section' ? 'selected' : '' }}>Section / Seksi</option>
                        <option value="Unit" {{ old('unit_type', $division->unit_type) == 'Unit' ? 'selected' : '' }}>Unit Operasional</option>
                    </select>
                </div>

                <!-- Regional -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Regional / Cabang
                    </label>
                    <input type="text" name="regional" value="{{ old('regional', $division->regional) }}" 
                           class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 outline-none text-slate-800 dark:text-slate-100 transition">
                </div>

                <!-- Cost Center -->
                <div class="space-y-1.5 md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Cost Center (SAP FI/CO)
                    </label>
                    <input type="text" name="cost_center" value="{{ old('cost_center', $division->cost_center) }}" 
                           class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 outline-none text-slate-800 dark:text-slate-100 transition">
                </div>

                <!-- Deskripsi / Tugas Utama -->
                <div class="space-y-1.5 md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Deskripsi / Tugas Utama Unit
                    </label>
                    <textarea name="description" rows="3" 
                              class="w-full text-xs p-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 outline-none text-slate-800 dark:text-slate-100 transition">{{ old('description', $division->description) }}</textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('org.sto.show', $division->id) }}" 
                   class="px-5 py-2.5 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-xl shadow-sm transition cursor-pointer border-0 flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
