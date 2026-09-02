@extends('layouts.app')

@section('title', 'Project Definition - View — Tanos ERP')

@section('content')
<div class="space-y-6 w-full">
    <!-- Header & Breadcrumbs -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <!-- Breadcrumb matching standard -->
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 dark:text-slate-400 mb-4.5">
                <a href="{{ route('dashboard.index') }}" class="hover:text-primary dark:hover:text-sky-400 transition flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Home
                </a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span>Project System</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <a href="{{ route('projects.index') }}" class="hover:text-primary dark:hover:text-sky-400 transition">Project Definition</a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-primary dark:text-sky-400 font-black">View</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                Project Definition
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2.5 font-medium">Project System - Project Definition</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('projects.index') }}" class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-50 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                <span>Back</span>
            </a>
        </div>
    </div>

    <!-- Main Card View matching Screenshot 1 -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-xs p-6 md:p-8">
        <!-- Card Title -->
        <h2 class="text-base font-bold text-slate-800 dark:text-slate-100 mb-6">
            Project Definition - View
        </h2>

        <!-- Tab: General -->
        <div class="border-b border-slate-200 dark:border-slate-800 mb-6">
            <button class="px-4 py-2 text-xs font-bold text-slate-800 dark:text-slate-100 border-b-2 border-primary -mb-px">
                General
            </button>
        </div>

        <!-- Form View Grid -->
        <div class="space-y-4 text-xs font-medium">
            <!-- Code -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <label class="md:col-span-2 text-slate-700 dark:text-slate-300 font-semibold">Code <span class="text-red-500">*</span></label>
                <div class="md:col-span-10">
                    <input type="text" readonly value="{{ $project->project_code }}" class="w-full px-3.5 py-2.5 bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg focus:outline-none font-mono">
                </div>
            </div>

            <!-- Id Project Humanis -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <label class="md:col-span-2 text-slate-700 dark:text-slate-300 font-semibold">Id Project Humanis</label>
                <div class="md:col-span-10">
                    <input type="text" readonly value="{{ $project->id_project_humanis ?? '-' }}" class="w-full px-3.5 py-2.5 bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg focus:outline-none">
                </div>
            </div>

            <!-- Name -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <label class="md:col-span-2 text-slate-700 dark:text-slate-300 font-semibold">Name <span class="text-red-500">*</span></label>
                <div class="md:col-span-10">
                    <input type="text" readonly value="{{ $project->project_name }}" class="w-full px-3.5 py-2.5 bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg focus:outline-none font-semibold">
                </div>
            </div>

            <!-- Description -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-start">
                <label class="md:col-span-2 text-slate-700 dark:text-slate-300 font-semibold pt-2">Description</label>
                <div class="md:col-span-10">
                    <textarea readonly rows="2" class="w-full px-3.5 py-2.5 bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg focus:outline-none resize-none">{{ $project->description ?? 'Tenaga Alih Daya' }}</textarea>
                </div>
            </div>

            <!-- Vendor -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <label class="md:col-span-2 text-slate-700 dark:text-slate-300 font-semibold">Vendor <span class="text-red-500">*</span></label>
                <div class="md:col-span-10 flex items-center gap-1.5">
                    <input type="text" readonly value="{{ $project->vendor ?? $project->customer_name ?? 'PT Pelabuhan Indonesia (Persero) Regional 3' }}" class="w-full px-3.5 py-2.5 bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg focus:outline-none">
                    <button type="button" class="p-2.5 bg-slate-100 dark:bg-slate-800 text-amber-500 border border-slate-200 dark:border-slate-700 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                    <button type="button" class="p-2.5 bg-rose-500 text-white rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- Project Category & Contract Type -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <label class="md:col-span-2 text-slate-700 dark:text-slate-300 font-semibold">Project Category <span class="text-red-500">*</span></label>
                <div class="md:col-span-4">
                    <input type="text" readonly value="{{ $project->project_category ?? '01. Tenaga Alih Daya Operasional' }}" class="w-full px-3.5 py-2.5 bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg focus:outline-none">
                </div>
                <label class="md:col-span-2 md:text-right text-slate-700 dark:text-slate-300 font-semibold md:pr-2">Contract Type <span class="text-red-500">*</span></label>
                <div class="md:col-span-4">
                    <input type="text" readonly value="{{ $project->contract_type ?? 'NON-JOPRO' }}" class="w-full px-3.5 py-2.5 bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg focus:outline-none font-semibold">
                </div>
            </div>

            <!-- Location -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <label class="md:col-span-2 text-slate-700 dark:text-slate-300 font-semibold">Location <span class="text-red-500">*</span></label>
                <div class="md:col-span-10">
                    <input type="text" readonly value="{{ $project->location ?? 'Tanjung Emas' }}" class="w-full px-3.5 py-2.5 bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg focus:outline-none">
                </div>
            </div>

            <!-- Regional Unit -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <label class="md:col-span-2 text-slate-700 dark:text-slate-300 font-semibold">Regional Unit <span class="text-red-500">*</span></label>
                <div class="md:col-span-10 flex items-center gap-1.5">
                    <input type="text" readonly value="{{ $project->regional_unit ?? 'Regional PDS Jawa' }}" class="w-full px-3.5 py-2.5 bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg focus:outline-none">
                    <button type="button" class="p-2.5 bg-slate-100 dark:bg-slate-800 text-amber-500 border border-slate-200 dark:border-slate-700 rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                    <button type="button" class="p-2.5 bg-rose-500 text-white rounded-lg">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <!-- Unit Kerja -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <label class="md:col-span-2 text-slate-700 dark:text-slate-300 font-semibold">Unit Kerja <span class="text-red-500">*</span></label>
                <div class="md:col-span-10">
                    <input type="text" readonly value="{{ $project->unit_kerja ?? 'PT Pelabuhan Indonesia (Persero) Regional 3 - Subreg Jawa - Tanjung Perak' }}" class="w-full px-3.5 py-2.5 bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg focus:outline-none">
                </div>
            </div>

            <!-- Expected Start & Expected End -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <label class="md:col-span-2 text-slate-700 dark:text-slate-300 font-semibold">Expected Start <span class="text-red-500">*</span></label>
                <div class="md:col-span-4">
                    <input type="text" readonly value="{{ $project->start_date ? $project->start_date->format('d/m/Y') : '19/02/2025' }}" class="w-full px-3.5 py-2.5 bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg focus:outline-none font-mono">
                </div>
                <label class="md:col-span-2 md:text-right text-slate-700 dark:text-slate-300 font-semibold md:pr-2">Expected End <span class="text-red-500">*</span></label>
                <div class="md:col-span-4">
                    <input type="text" readonly value="{{ $project->end_date ? $project->end_date->format('d/m/Y') : '31/12/2025' }}" class="w-full px-3.5 py-2.5 bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg focus:outline-none font-mono">
                </div>
            </div>

            <!-- Validity Period Start & Validity Period End -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <label class="md:col-span-2 text-slate-700 dark:text-slate-300 font-semibold">Validity Period Start <span class="text-red-500">*</span></label>
                <div class="md:col-span-4">
                    <input type="text" readonly value="{{ $project->validity_start ? $project->validity_start->format('d/m/Y') : '01/01/2020' }}" class="w-full px-3.5 py-2.5 bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg focus:outline-none font-mono">
                </div>
                <label class="md:col-span-2 md:text-right text-slate-700 dark:text-slate-300 font-semibold md:pr-2">Validity Period End <span class="text-red-500">*</span></label>
                <div class="md:col-span-4">
                    <input type="text" readonly value="{{ $project->validity_end ? $project->validity_end->format('d/m/Y') : '31/12/2024' }}" class="w-full px-3.5 py-2.5 bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg focus:outline-none font-mono">
                </div>
            </div>

            <!-- Attachment Section -->
            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 mt-6">
                <h3 class="text-xs font-bold text-slate-800 dark:text-slate-200 mb-2">Attachment</h3>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-normal">- No Attachment -</p>
            </div>
        </div>
    </div>
</div>
@endsection
