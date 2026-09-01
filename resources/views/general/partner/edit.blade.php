@extends('layouts.app')

@section('title', 'Edit Business Partner — Tanos ERP')

@section('content')
<div class="space-y-6">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <!-- Breadcrumbs -->
            <div class="flex items-center space-x-2 text-xs font-bold text-slate-400 dark:text-slate-500 mb-1.5">
                <a href="{{ route('dashboard.index') }}" class="hover:text-primary transition flex items-center">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    Home
                </a>
                <span>/</span>
                <span>General</span>
                <span>/</span>
                <span>Master Data</span>
                <span>/</span>
                <a href="{{ route('general.partner') }}" class="hover:text-primary transition">Partner</a>
                <span>/</span>
                <span class="text-primary font-black">Edit</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight flex items-center space-x-3">
                <span>Business Partner - Edit</span>
                <span class="px-2.5 py-0.5 rounded-lg bg-primary-light text-primary font-mono text-xs font-bold">
                    {{ $partner->code }}
                </span>
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">
                Perbarui informasi rekanan, legalitas, pimpinan, dan konfigurasi integrasi.
            </p>
        </div>

        <div class="flex items-center space-x-2 self-start sm:self-auto">
            <a href="{{ route('general.partner.show', $partner->id) }}"
               class="px-4 py-2 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 shadow-xs cursor-pointer">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Lihat Detail</span>
            </a>
        </div>
    </div>

    <!-- Main Edit Form Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 sm:p-8">
        
        <form action="{{ route('general.partner.update', $partner->id) }}" method="POST" class="space-y-6 text-xs">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-4">
                
                <!-- LEFT COLUMN -->
                <div class="space-y-4">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Partner Code <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="code" value="{{ old('code', $partner->code) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Partner Type
                        </label>
                        <select name="partner_type_id"
                                class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                            <option value="">Pilih Tipe Partner</option>
                            @foreach($partnerTypes as $type)
                                <option value="{{ $type->id }}" {{ old('partner_type_id', $partner->partner_type_id) == $type->id ? 'selected' : '' }}>
                                    {{ $type->code }} - {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $partner->name) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Address <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="address" rows="2" required
                                  class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">{{ old('address', $partner->address) }}</textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            City <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="city" value="{{ old('city', $partner->city) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Identity Card (KTP / NIK)
                        </label>
                        <input type="text" name="identity_card" value="{{ old('identity_card', $partner->identity_card) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            NPWP
                        </label>
                        <input type="text" name="npwp" value="{{ old('npwp', $partner->npwp) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <!-- Vendor & Customer Checkboxes -->
                    <div class="flex items-center space-x-6 pt-1">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="is_vendor" value="1" {{ old('is_vendor', $partner->is_vendor) ? 'checked' : '' }}
                                   class="w-4 h-4 text-primary rounded border-slate-300 focus:ring-primary">
                            <span class="font-bold text-slate-800 dark:text-slate-200">Vendor</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="is_customer" value="1" {{ old('is_customer', $partner->is_customer) ? 'checked' : '' }}
                                   class="w-4 h-4 text-primary rounded border-slate-300 focus:ring-primary">
                            <span class="font-bold text-slate-800 dark:text-slate-200">Customer</span>
                        </label>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Chief Name
                        </label>
                        <input type="text" name="chief_name" value="{{ old('chief_name', $partner->chief_name) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Chief Position
                        </label>
                        <input type="text" name="chief_position" value="{{ old('chief_position', $partner->chief_position) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <!-- Hold Dana & Auto Faktur Checkboxes -->
                    <div class="space-y-2 pt-1">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="status_hold_dana" value="1" {{ old('status_hold_dana', $partner->status_hold_dana) ? 'checked' : '' }}
                                   class="w-4 h-4 text-rose-600 rounded border-slate-300 focus:ring-rose-500">
                            <span class="font-bold text-slate-800 dark:text-slate-200">Status Hold Dana</span>
                        </label>

                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="auto_generate_faktur" value="1" {{ old('auto_generate_faktur', $partner->auto_generate_faktur) ? 'checked' : '' }}
                                   class="w-4 h-4 text-primary rounded border-slate-300 focus:ring-primary">
                            <span class="font-bold text-slate-800 dark:text-slate-200">Auto Generate Faktur</span>
                        </label>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div class="space-y-4">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Trading Partner
                        </label>
                        <input type="text" name="trading_partner" value="{{ old('trading_partner', $partner->trading_partner) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Partner Group
                        </label>
                        <input type="text" name="partner_group" value="{{ old('partner_group', $partner->partner_group) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Phone No.1 <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="phone_1" value="{{ old('phone_1', $partner->phone_1 ?? $partner->phone) }}" required
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Phone No.2
                        </label>
                        <input type="text" name="phone_2" value="{{ old('phone_2', $partner->phone_2) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Email
                        </label>
                        <input type="email" name="email" value="{{ old('email', $partner->email) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            ftp_link
                        </label>
                        <input type="text" name="ftp_link" value="{{ old('ftp_link', $partner->ftp_link) }}"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">ftp_port</label>
                            <input type="text" name="ftp_port" value="{{ old('ftp_port', $partner->ftp_port ?? '21') }}"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Kode MDM</label>
                            <input type="text" name="kode_mdm" value="{{ old('kode_mdm', $partner->kode_mdm) }}"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono font-bold text-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">ftp_user</label>
                            <input type="text" name="ftp_user" value="{{ old('ftp_user', $partner->ftp_user) }}"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">ftp_pass (Isi jika diubah)</label>
                            <input type="password" name="ftp_pass" placeholder="••••••••"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Description -->
            <div class="pt-2">
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                    Description <span class="text-rose-500">*</span>
                </label>
                <textarea name="description" rows="3" required
                          class="w-full p-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none leading-relaxed">{{ old('description', $partner->description ?? $partner->name) }}</textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('general.partner.show', $partner->id) }}"
                   class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold rounded-xl transition cursor-pointer">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-primary hover:bg-primary-hover text-white font-bold rounded-xl shadow-lg shadow-primary transition cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
