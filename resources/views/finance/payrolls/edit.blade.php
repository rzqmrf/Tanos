@extends('layouts.app')

@section('title', 'Edit Periode Gaji — Tanos ERP')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Page Header -->
    <x-page-header 
        :title="'Edit Periode: ' . $period->name" 
        subtitle="Ubah informasi periode penggajian, tipe payroll, atau rentang tanggal."
        :breadcrumbs="[
            'General' => '#',
            'Human Capital' => '#',
            'Period Payroll' => route('payrolls.index'),
            'Edit' => ''
        ]"
    >
        <x-slot:action>
            <a href="{{ route('payrolls.index') }}" 
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
        <form action="{{ route('payrolls.update', $period->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Project / Segment -->
                <div class="space-y-1.5 md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Pilih Project / Segment <span class="text-rose-500">*</span>
                    </label>
                    <select name="project_id" required 
                            class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-800 dark:text-slate-100 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 transition">
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}" {{ old('project_id', $period->project_id) == $proj->id ? 'selected' : '' }}>
                                {{ $proj->segment }} - {{ $proj->regional }} (Bulan: {{ $proj->month }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama Periode -->
                <div class="space-y-1.5 md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Nama Periode Payroll <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $period->name) }}" required 
                           class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 outline-none text-slate-800 dark:text-slate-100 transition">
                </div>

                <!-- Tipe Payroll -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Tipe Payroll <span class="text-rose-500">*</span>
                    </label>
                    <select name="type" required 
                            class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-800 dark:text-slate-100 focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 transition">
                        <option value="On-Cycle" {{ old('type', $period->type) == 'On-Cycle' ? 'selected' : '' }}>On-Cycle (Penggajian Rutin Bulanan)</option>
                        <option value="Off-Cycle" {{ old('type', $period->type) == 'Off-Cycle' ? 'selected' : '' }}>Off-Cycle (TAD / Bonus / Rapel Tambahan)</option>
                    </select>
                </div>

                <!-- Bulan Proses -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Bulan Proses <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="month" value="{{ old('month', $period->month) }}" required 
                           class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 outline-none text-slate-800 dark:text-slate-100 transition">
                </div>

                <!-- Tanggal Mulai -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Tanggal Mulai Periode <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="start_date" value="{{ old('start_date', $period->start_date ? $period->start_date->format('Y-m-d') : '') }}" required 
                           class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 outline-none text-slate-800 dark:text-slate-100 transition">
                </div>

                <!-- Tanggal Selesai -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">
                        Tanggal Selesai Periode <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="end_date" value="{{ old('end_date', $period->end_date ? $period->end_date->format('Y-m-d') : '') }}" required 
                           class="w-full text-xs px-3.5 py-3 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-sky-500 focus:ring-2 focus:ring-sky-500/10 outline-none text-slate-800 dark:text-slate-100 transition">
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('payrolls.index') }}" 
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
