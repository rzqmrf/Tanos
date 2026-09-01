@extends('layouts.app')

@section('title', 'Business Partner - View — Tanos ERP')

@section('content')
<div class="space-y-6" x-data="{
    activeTab: '{{ request('tab', 'general') }}',
    showAddBankModal: false,
    showAddSegmentModal: false
}">

    <!-- Page Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 dark:text-slate-400 mb-3">
                <a href="{{ route('dashboard.index') }}" class="hover:text-primary dark:hover:text-sky-400 transition flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Home
                </a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span>General</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span>Master Data</span>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <a href="{{ route('general.partner') }}" class="hover:text-primary dark:hover:text-sky-400 transition">Partner</a>
                <span class="text-slate-300 dark:text-slate-600">/</span>
                <span class="text-primary dark:text-sky-400 font-black">View</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                Business Partner - View
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 font-medium">
                Detail profil legalitas, rekening bank mitra, dan segment bisnis.
            </p>
        </div>

        <div class="flex items-center space-x-2 self-start sm:self-auto">
            <a href="{{ route('general.partner') }}"
               class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 shadow-xs cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Back</span>
            </a>

            <a href="{{ route('general.partner.edit', $partner->id) }}"
               class="px-4 py-2 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 shadow-xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <span>Edit</span>
            </a>
        </div>
    </div>

    <!-- Alert Notification -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-2xl text-xs font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <!-- Main View Card matching Screenshot 2 -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 sm:p-8 space-y-6">
        
        <!-- View Card Header with Edit & Delete actions on Top-Right -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
            <div>
                <h2 class="text-lg font-black text-slate-800 dark:text-slate-100 flex items-center space-x-3">
                    <span>Business Partner - View</span>
                    <span class="px-2.5 py-0.5 rounded-lg bg-primary-light text-primary font-mono text-xs font-bold">
                        {{ $partner->code }}
                    </span>
                </h2>
            </div>

            <div class="flex items-center space-x-2">
                <!-- Edit Button (Purple) -->
                <a href="{{ route('general.partner.edit', $partner->id) }}"
                   class="px-4 py-2 bg-indigo-50 dark:bg-indigo-950/50 hover:bg-indigo-100 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    <span>Edit</span>
                </a>

                <!-- Delete Button (Red outline) -->
                <form action="{{ route('general.partner.destroy', $partner->id) }}" method="POST" class="inline"
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data Partner ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="px-4 py-2 bg-rose-50 dark:bg-rose-950/50 hover:bg-rose-100 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 shadow-xs cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Delete</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- 3 Tabs Navigation matching Tanos Screenshot -->
        <div class="flex items-center space-x-1 border-b border-slate-100 dark:border-slate-800 text-xs font-bold">
            <button @click="activeTab = 'general'"
                    :class="activeTab === 'general' ? 'border-primary text-primary dark:text-white border-b-2 font-black' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200'"
                    class="px-4 py-2.5 transition cursor-pointer">
                General
            </button>
            <button @click="activeTab = 'banks'"
                    :class="activeTab === 'banks' ? 'border-primary text-primary dark:text-white border-b-2 font-black' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200'"
                    class="px-4 py-2.5 transition cursor-pointer flex items-center space-x-1.5">
                <span>Banks</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                    {{ $partner->bankAccounts->count() }}
                </span>
            </button>
            <button @click="activeTab = 'segments'"
                    :class="activeTab === 'segments' ? 'border-primary text-primary dark:text-white border-b-2 font-black' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200'"
                    class="px-4 py-2.5 transition cursor-pointer flex items-center space-x-1.5">
                <span>Business Segments</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                    {{ $partner->businessSegments->count() }}
                </span>
            </button>
        </div>

        <!-- TAB 1: GENERAL VIEW (Matching Tanos Screenshot 2 Layout 100%) -->
        <div x-show="activeTab === 'general'" class="space-y-6">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-4 text-xs">
                
                <!-- LEFT COLUMN -->
                <div class="space-y-4">
                    <!-- Partner Code -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Partner Code <span class="text-rose-500">*</span>
                        </label>
                        <div class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl font-mono font-bold text-slate-800 dark:text-slate-100">
                            {{ $partner->code }}
                        </div>
                    </div>

                    <!-- Partner Type -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Partner Type
                        </label>
                        <div class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100">
                            {{ $partner->partnerType?->name ?? ($partner->partnerType?->code ?? '-') }}
                        </div>
                    </div>

                    <!-- Name -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Name <span class="text-rose-500">*</span>
                        </label>
                        <div class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl font-bold text-slate-900 dark:text-slate-100">
                            {{ $partner->name }}
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Address <span class="text-rose-500">*</span>
                        </label>
                        <div class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200 leading-relaxed">
                            {{ $partner->address ?? '-' }}
                        </div>
                    </div>

                    <!-- City -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            City <span class="text-rose-500">*</span>
                        </label>
                        <div class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100">
                            {{ $partner->city ?? '-' }}
                        </div>
                    </div>

                    <!-- Identity Card -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Identity Card <span class="text-rose-500">*</span>
                        </label>
                        <div class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-200">
                            {{ $partner->identity_card ?? '-' }}
                        </div>
                    </div>

                    <!-- NPWP -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            NPWP <span class="text-rose-500">*</span>
                        </label>
                        <div class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl font-mono font-bold text-slate-800 dark:text-slate-100">
                            {{ $partner->npwp ?? '-' }}
                        </div>
                    </div>

                    <!-- Vendor & Customer Checkboxes -->
                    <div class="flex items-center space-x-6 pt-1">
                        <label class="flex items-center space-x-2 cursor-default">
                            <input type="checkbox" disabled {{ $partner->is_vendor ? 'checked' : '' }}
                                   class="w-4 h-4 text-primary rounded border-slate-300">
                            <span class="font-bold text-slate-800 dark:text-slate-200">Vendor</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-default">
                            <input type="checkbox" disabled {{ $partner->is_customer ? 'checked' : '' }}
                                   class="w-4 h-4 text-primary rounded border-slate-300">
                            <span class="font-bold text-slate-800 dark:text-slate-200">Customer</span>
                        </label>
                    </div>

                    <!-- Chief Name -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Chief Name <span class="text-rose-500">*</span>
                        </label>
                        <div class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100">
                            {{ $partner->chief_name ?? '-' }}
                        </div>
                    </div>

                    <!-- Chief Position -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Chief Position <span class="text-rose-500">*</span>
                        </label>
                        <div class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100">
                            {{ $partner->chief_position ?? '-' }}
                        </div>
                    </div>

                    <!-- Hold Dana & Auto Faktur Checkboxes -->
                    <div class="space-y-2 pt-1">
                        <label class="flex items-center space-x-2 cursor-default">
                            <input type="checkbox" disabled {{ $partner->status_hold_dana ? 'checked' : '' }}
                                   class="w-4 h-4 text-rose-600 rounded border-slate-300">
                            <span class="font-bold text-slate-800 dark:text-slate-200">Status Hold Dana</span>
                        </label>

                        <label class="flex items-center space-x-2 cursor-default">
                            <input type="checkbox" disabled {{ $partner->auto_generate_faktur ? 'checked' : '' }}
                                   class="w-4 h-4 text-primary rounded border-slate-300">
                            <span class="font-bold text-slate-800 dark:text-slate-200">Auto Generate Faktur</span>
                        </label>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div class="space-y-4">
                    <!-- Trading Partner -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Trading Partner
                        </label>
                        <div class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            {{ $partner->trading_partner ?? '-' }}
                        </div>
                    </div>

                    <!-- Partner Group -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Partner Group
                        </label>
                        <div class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-200">
                            {{ $partner->partner_group ?? '-' }}
                        </div>
                    </div>

                    <!-- Phone No.1 -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Phone No.1 <span class="text-rose-500">*</span>
                        </label>
                        <div class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-200">
                            {{ $partner->phone_1 ?? ($partner->phone ?? '0') }}
                        </div>
                    </div>

                    <!-- Phone No.2 -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Phone No.2 <span class="text-rose-500">*</span>
                        </label>
                        <div class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-200">
                            {{ $partner->phone_2 ?? '-' }}
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Email <span class="text-rose-500">*</span>
                        </label>
                        <div class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-200">
                            {{ $partner->email ?? '-' }}
                        </div>
                    </div>

                    <!-- FTP Link -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            ftp_link
                        </label>
                        <div class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-200">
                            {{ $partner->ftp_link ?? '-' }}
                        </div>
                    </div>

                    <!-- FTP Port -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            ftp_port
                        </label>
                        <div class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-200">
                            {{ $partner->ftp_port ?? '-' }}
                        </div>
                    </div>

                    <!-- FTP User -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            ftp_user
                        </label>
                        <div class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-200">
                            {{ $partner->ftp_user ?? '-' }}
                        </div>
                    </div>

                    <!-- FTP Pass -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            ftp_pass
                        </label>
                        <div class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-200">
                            {{ $partner->ftp_pass ? '••••••••' : '-' }}
                        </div>
                    </div>

                    <!-- Kode MDM -->
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Kode MDM
                        </label>
                        <div class="w-full px-3.5 py-2.5 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-xl font-mono font-bold text-primary">
                            {{ $partner->kode_mdm ?? '-' }}
                        </div>
                    </div>
                </div>

            </div>

            <!-- Description (Full width at bottom matching screenshot) -->
            <div class="pt-2">
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1 text-xs">
                    Description <span class="text-rose-500">*</span>
                </label>
                <div class="w-full p-4 bg-slate-50/70 dark:bg-slate-800/50 border border-slate-200/80 dark:border-slate-700 rounded-2xl text-xs text-slate-800 dark:text-slate-200 leading-relaxed min-h-[80px]">
                    {{ $partner->description ?? $partner->name }}
                </div>
            </div>

        </div>

        <!-- TAB 2: BANKS VIEW -->
        <div x-show="activeTab === 'banks'" style="display: none;" class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Daftar Rekening Bank Mitra</h3>
                <button type="button" @click="showAddBankModal = true"
                        class="px-4 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-md shadow-primary transition flex items-center space-x-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    <span>Tambah Rekening</span>
                </button>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-800">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-100 dark:border-slate-800 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase">
                            <th class="py-3 px-4">Nama Bank</th>
                            <th class="py-3 px-4">Nomor Rekening</th>
                            <th class="py-3 px-4">Atas Nama</th>
                            <th class="py-3 px-4">Kantor Cabang</th>
                            <th class="py-3 px-4 text-center">Rekening Utama</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                        @forelse($partner->bankAccounts as $bank)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20">
                            <td class="py-3 px-4 font-bold text-slate-800 dark:text-slate-100">{{ $bank->bank_name }}</td>
                            <td class="py-3 px-4 font-mono font-bold text-primary">{{ $bank->account_number }}</td>
                            <td class="py-3 px-4 font-medium">{{ $bank->account_holder }}</td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">{{ $bank->branch ?? '-' }}</td>
                            <td class="py-3 px-4 text-center">
                                @if($bank->is_primary)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400">UTAMA</span>
                                @else
                                <span class="text-slate-400 text-[11px]">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <form action="{{ route('general.partner.banks.destroy', [$partner->id, $bank->id]) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus rekening bank ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400">Belum ada data rekening bank yang terdaftar untuk mitra ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB 3: BUSINESS SEGMENTS VIEW -->
        <div x-show="activeTab === 'segments'" style="display: none;" class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">Daftar Business Segments</h3>
                <button type="button" @click="showAddSegmentModal = true"
                        class="px-4 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-bold rounded-xl shadow-md shadow-primary transition flex items-center space-x-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    <span>Tambah Segmen</span>
                </button>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-slate-100 dark:border-slate-800">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-100 dark:border-slate-800 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase">
                            <th class="py-3 px-4">Kode Segmen</th>
                            <th class="py-3 px-4">Nama Segmen Bisnis</th>
                            <th class="py-3 px-4">Deskripsi</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                        @forelse($partner->businessSegments as $segment)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20">
                            <td class="py-3 px-4 font-mono font-bold text-primary">{{ $segment->segment_code }}</td>
                            <td class="py-3 px-4 font-bold text-slate-800 dark:text-slate-100">{{ $segment->segment_name }}</td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">{{ $segment->description ?? '-' }}</td>
                            <td class="py-3 px-4 text-right">
                                <form action="{{ route('general.partner.segments.destroy', [$partner->id, $segment->id]) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus segmen bisnis ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 transition cursor-pointer" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-slate-400">Belum ada segmen bisnis yang terdaftar untuk mitra ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Modal Tambah Rekening Bank (Tab Banks) -->
    <div x-show="showAddBankModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="showAddBankModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl p-6">
                <h3 class="text-base font-black text-slate-800 dark:text-slate-100 pb-3 border-b border-slate-100 dark:border-slate-800">
                    Tambah Rekening Bank Rekanan
                </h3>

                <form action="{{ route('general.partner.banks.store', $partner->id) }}" method="POST" class="mt-4 space-y-3.5 text-xs">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Bank *</label>
                        <input type="text" name="bank_name" required placeholder="Contoh: Bank Mandiri / BCA / BNI"
                               class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nomor Rekening *</label>
                        <input type="text" name="account_number" required placeholder="Nomor Rekening Bank"
                               class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Pemilik Rekening *</label>
                        <input type="text" name="account_holder" value="{{ $partner->name }}" required
                               class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Kantor Cabang</label>
                        <input type="text" name="branch" placeholder="Contoh: KC Jakarta Tanjung Priok"
                               class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>

                    <div class="flex items-center space-x-2 pt-1">
                        <input type="checkbox" id="isPrimaryBank" name="is_primary" value="1"
                               class="w-4 h-4 text-primary rounded border-slate-300">
                        <label for="isPrimaryBank" class="font-bold text-slate-700 dark:text-slate-300 cursor-pointer">Set sebagai Rekening Bank Utama</label>
                    </div>

                    <div class="flex justify-end space-x-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="showAddBankModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white font-bold rounded-xl shadow-md shadow-primary">Simpan Rekening</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Business Segment (Tab Segments) -->
    <div x-show="showAddSegmentModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" @click="showAddSegmentModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-md bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-2xl p-6">
                <h3 class="text-base font-black text-slate-800 dark:text-slate-100 pb-3 border-b border-slate-100 dark:border-slate-800">
                    Tambah Business Segment
                </h3>

                <form action="{{ route('general.partner.segments.store', $partner->id) }}" method="POST" class="mt-4 space-y-3.5 text-xs">
                    @csrf
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Kode Segmen *</label>
                        <input type="text" name="segment_code" required placeholder="Contoh: SEG-LOGISTIK"
                               class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono font-bold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Segmen Bisnis *</label>
                        <input type="text" name="segment_name" required placeholder="Contoh: Unit Bongkar Muat & Pergudangan"
                               class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/20">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Deskripsi</label>
                        <textarea name="description" rows="2" placeholder="Keterangan unit bisnis..."
                                  class="w-full px-3.5 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/20"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" @click="showAddSegmentModal = false" class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white font-bold rounded-xl shadow-md shadow-primary">Simpan Segmen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
