@extends('layouts.app')

@section('title', 'Access Controls — Tanos ERP')

@section('content')
<div class="space-y-6">
    <!-- Header Block -->
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center space-x-3">
            <div class="p-2.5 bg-purple-50 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400 rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Access Controls</h1>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Manajemen hak akses dan peran pengguna secara terpusat.</p>
            </div>
        </div>
    </div>

    <!-- Alert Sukses / Eror -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30 rounded-xl text-sm">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Matrix Card -->
    <div class="p-6 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-2xl shadow-sm space-y-4">
        <div class="flex items-center justify-between flex-wrap gap-4 pb-2">
            <div>
                <h2 class="text-base font-bold text-slate-800 dark:text-slate-100">Matriks Hak Akses Peran</h2>
                <p class="text-xs text-slate-400 dark:text-slate-500">Centang modul yang diizinkan untuk setiap peran (role).</p>
            </div>
            <!-- Inline Add Role Form -->
            <form action="{{ route('access.controls.add-role') }}" method="POST" class="flex items-center space-x-2">
                @csrf
                <input type="text" name="new_role" required placeholder="Tambah Peran Baru..." class="px-3 py-2 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-xl text-xs font-bold transition duration-150 flex items-center space-x-1 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Tambah Peran</span>
                </button>
            </form>
        </div>

        <form action="{{ route('access-controls.store') }}" method="POST">
            @csrf
            <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                            <th class="p-4">Nama Modul / Fitur</th>
                            @foreach($roles as $role)
                                <th class="p-4 text-center">{{ $role }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-sm text-slate-600 dark:text-slate-300">
                        @foreach($permissionsList as $permKey => $permLabel)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                                <td class="p-4 font-semibold text-slate-700 dark:text-slate-200">
                                    {{ $permLabel }}
                                </td>
                                @foreach($roles as $role)
                                    @php
                                        $isEnabled = isset($rolePermissions[$role]) && $rolePermissions[$role]->firstWhere('permission', $permKey)?->is_enabled;
                                    @endphp
                                    <td class="p-4 text-center">
                                        @if($role === 'Admin')
                                            <!-- Admin always has all permissions checked and disabled -->
                                            <input type="checkbox" checked disabled class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500 opacity-60">
                                            <input type="hidden" name="permissions[{{ $role }}][{{ $permKey }}]" value="1">
                                        @else
                                            <input type="checkbox" name="permissions[{{ $role }}][{{ $permKey }}]" value="1" {{ $isEnabled ? 'checked' : '' }} class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 dark:bg-slate-950 dark:border-slate-800 cursor-pointer">
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold transition duration-150 shadow-sm cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection