@extends('layouts.app')

@section('title', 'Create Business Partner — Tanos ERP')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action -->
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
                <span class="text-primary font-black">Create</span>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 tracking-tight">
                Create Business Partner
            </h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">
                Pendaftaran data rekanan baru (Vendor, Customer, Afiliasi BUMN / Swasta).
            </p>
        </div>

        <a href="{{ route('general.partner') }}"
           class="px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl transition flex items-center space-x-1.5 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Batal & Kembali</span>
        </a>
    </div>

    <!-- Main Create Form Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-sm p-6 sm:p-8">
        
        <form action="{{ route('general.partner.store') }}" method="POST" class="space-y-6 text-xs">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-4">
                
                <!-- LEFT COLUMN -->
                <div class="space-y-4">
                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Partner Code <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="code" value="{{ old('code', '200' . rand(1000000, 9999999)) }}" required placeholder="Contoh: 2000000104"
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
                                <option value="{{ $type->id }}" {{ old('partner_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->code }} - {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: PT KRAKATAU BANDAR SAMUDERA"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Address <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="address" rows="2" required placeholder="Alamat lengkap instansi/perusahaan..."
                                  class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">{{ old('address') }}</textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            City <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="city" value="{{ old('city') }}" required placeholder="Contoh: JAKARTA SELATAN / BADUNG / CILEGON"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Identity Card (KTP / NIK)
                        </label>
                        <input type="text" name="identity_card" value="{{ old('identity_card') }}" placeholder="Nomor KTP / NIK PIC Pimpinan"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            NPWP
                        </label>
                        <input type="text" name="npwp" value="{{ old('npwp') }}" placeholder="Contoh: 015221914905000"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono font-bold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <!-- Vendor & Customer Checkboxes -->
                    <div class="flex items-center space-x-6 pt-1">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="is_vendor" value="1" checked
                                   class="w-4 h-4 text-primary rounded border-slate-300 focus:ring-primary">
                            <span class="font-bold text-slate-800 dark:text-slate-200">Vendor</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="is_customer" value="1" checked
                                   class="w-4 h-4 text-primary rounded border-slate-300 focus:ring-primary">
                            <span class="font-bold text-slate-800 dark:text-slate-200">Customer</span>
                        </label>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Chief Name
                        </label>
                        <input type="text" name="chief_name" value="{{ old('chief_name') }}" placeholder="Nama Direktur Utama / Pimpinan"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Chief Position
                        </label>
                        <input type="text" name="chief_position" value="{{ old('chief_position') }}" placeholder="Jabatan (contoh: Direktur Utama / Kepala Cabang)"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <!-- Hold Dana & Auto Faktur Checkboxes -->
                    <div class="space-y-2 pt-1">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="status_hold_dana" value="1"
                                   class="w-4 h-4 text-rose-600 rounded border-slate-300 focus:ring-rose-500">
                            <span class="font-bold text-slate-800 dark:text-slate-200">Status Hold Dana</span>
                        </label>

                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="auto_generate_faktur" value="1" checked
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
                        <input type="text" name="trading_partner" value="{{ old('trading_partner') }}" placeholder="Afiliasi Trading Partner"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Partner Group
                        </label>
                        <input type="text" name="partner_group" value="{{ old('partner_group') }}" placeholder="Contoh: Pelindo Group / Krakatau Steel Group"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Phone No.1 <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="phone_1" value="{{ old('phone_1', '0') }}" required placeholder="Nomor Telepon Kantor Primer"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Phone No.2
                        </label>
                        <input type="text" name="phone_2" value="{{ old('phone_2') }}" placeholder="Nomor Telepon Kantor Sekunder"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            Email
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@perusahaan.com"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                            ftp_link
                        </label>
                        <input type="text" name="ftp_link" value="{{ old('ftp_link') }}" placeholder="ftp.domain.com"
                               class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">ftp_port</label>
                            <input type="text" name="ftp_port" value="{{ old('ftp_port', '21') }}" placeholder="21"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Kode MDM</label>
                            <input type="text" name="kode_mdm" value="{{ old('kode_mdm') }}" placeholder="Contoh: 00045583"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono font-bold text-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">ftp_user</label>
                            <input type="text" name="ftp_user" value="{{ old('ftp_user') }}" placeholder="Username FTP"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">ftp_pass</label>
                            <input type="password" name="ftp_pass" placeholder="Password FTP"
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
                <textarea name="description" rows="3" required placeholder="Deskripsi lengkap rekanan..."
                          class="w-full p-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-800 dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:outline-none leading-relaxed">{{ old('description') }}</textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('general.partner') }}"
                   class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold rounded-xl transition cursor-pointer">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-primary hover:bg-primary-hover text-white font-bold rounded-xl shadow-lg shadow-primary transition cursor-pointer">
                    Simpan Business Partner
                </button>
            </div>

        </form>

    </div>

</div>
@endsection
