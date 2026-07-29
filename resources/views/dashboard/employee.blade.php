@extends('layouts.app')

@section('title', 'Dashboard Pegawai — Tanos ERP')

@section('content')
@php
    $name = $employee ? $employee->name : session('user.name', 'Karyawan');
    
    // Extract initials (first letters of the first two words)
    $words = explode(' ', $name);
    $initials = '';
    foreach ($words as $w) {
        if (!empty($w)) {
            $initials .= strtoupper(substr($w, 0, 1));
        }
    }
    $initials = substr($initials, 0, 2);

    // Calculate robust ISO-8601 datetimes for JavaScript clock
    $clockInIso = $todayAttendance && $todayAttendance->clock_in 
        ? \Carbon\Carbon::parse($todayAttendance->date . ' ' . $todayAttendance->clock_in)->toIso8601String() 
        : "";
    $clockOutIso = $todayAttendance && $todayAttendance->clock_out 
        ? \Carbon\Carbon::parse($todayAttendance->date . ' ' . $todayAttendance->clock_out)->toIso8601String() 
        : "";
@endphp

<div class="space-y-6 w-full">
    <!-- Greeting & Info Header Card (Light/Dark Mode Adaptive) -->
    <div class="p-6 bg-white dark:bg-gradient-to-r dark:from-slate-900 dark:via-indigo-950 dark:to-indigo-900 rounded-2xl border border-slate-200/60 dark:border-none shadow-sm flex items-center justify-between flex-wrap gap-4 transition duration-150">
        <div class="space-y-1">
            <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-400 uppercase tracking-widest">Selamat Datang</span>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white flex items-center gap-2">
                <span>{{ $name }}</span>
                <span class="animate-bounce">👋</span>
            </h1>
            <p class="text-xs text-slate-400 dark:text-slate-350 font-medium">Pantau kehadiran dan aktivitas Anda dengan mudah setiap hari.</p>
        </div>

        <div class="flex items-center space-x-3.5 bg-slate-50 dark:bg-white/5 p-3 rounded-2xl border border-slate-100 dark:border-white/10 shadow-inner">
            <div class="p-2 bg-[#E9EFFF] dark:bg-white/10 text-[#1B3BB6] dark:text-white rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
            </div>
            <div>
                <span class="block text-xs font-extrabold text-slate-700 dark:text-white font-mono tracking-tight">{{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</span>
                <span class="block text-[9px] font-bold text-slate-400 dark:text-slate-450 uppercase tracking-widest mt-0.5">SISTEM PORTAL PEGAWAI</span>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-2xl text-sm shadow-sm transition duration-150">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30 rounded-2xl text-sm shadow-sm transition duration-150">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Metrics Row (4 Cards) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Hadir -->
        <div class="relative overflow-hidden p-5 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl shadow-sm transition-all duration-200 hover:shadow-md border-b-4 border-b-emerald-500">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center space-x-3.5">
                    <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>
                    </div>
                    <div class="space-y-0.5">
                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Hadir</span>
                        <span class="block text-2xl font-black text-slate-800 dark:text-slate-100 font-mono tracking-tight">{{ $stats['present'] }}</span>
                        <span class="block text-[9px] font-medium text-slate-400 dark:text-slate-500">Hari ini</span>
                    </div>
                </div>
                <div class="text-right space-y-0.5 shrink-0">
                    <span class="inline-flex items-center text-[10px] font-bold text-emerald-600 dark:text-emerald-400 space-x-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-2.5 h-2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
                        </svg>
                        <span>100%</span>
                    </span>
                    <span class="block text-[8px] text-slate-400 dark:text-slate-500 font-semibold uppercase">dari kemarin</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Sakit / Izin -->
        <div class="relative overflow-hidden p-5 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl shadow-sm transition-all duration-200 hover:shadow-md border-b-4 border-b-amber-500">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center space-x-3.5">
                    <div class="w-10 h-10 bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 rounded-full flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <div class="space-y-0.5">
                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Sakit / Izin</span>
                        <span class="block text-2xl font-black text-slate-800 dark:text-slate-100 font-mono tracking-tight">{{ $stats['sick_permit'] }}</span>
                        <span class="block text-[9px] font-medium text-slate-400 dark:text-slate-500">Hari ini</span>
                    </div>
                </div>
                <div class="text-right space-y-0.5 shrink-0">
                    <span class="inline-flex items-center text-[10px] font-bold text-amber-500 dark:text-amber-400">→ 0%</span>
                    <span class="block text-[8px] text-slate-400 dark:text-slate-505 font-semibold uppercase">dari kemarin</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Alfa / Mangkir -->
        <div class="relative overflow-hidden p-5 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl shadow-sm transition-all duration-200 hover:shadow-md border-b-4 border-b-rose-500">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center space-x-3.5">
                    <div class="w-10 h-10 bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 rounded-full flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div class="space-y-0.5">
                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Alfa / Mangkir</span>
                        <span class="block text-2xl font-black text-slate-800 dark:text-slate-100 font-mono tracking-tight">{{ $stats['absent'] }}</span>
                        <span class="block text-[9px] font-medium text-slate-400 dark:text-slate-500">Hari ini</span>
                    </div>
                </div>
                <div class="text-right space-y-0.5 shrink-0">
                    <span class="inline-flex items-center text-[10px] font-bold text-rose-500 dark:text-rose-400">→ 0%</span>
                    <span class="block text-[8px] text-slate-400 dark:text-slate-550 font-semibold uppercase">dari kemarin</span>
                </div>
            </div>
        </div>

        <!-- Card 4: Total Lembur -->
        <div class="relative overflow-hidden p-5 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl shadow-sm transition-all duration-200 hover:shadow-md border-b-4 border-b-blue-500">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center space-x-3.5">
                    <div class="w-10 h-10 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <div class="space-y-0.5">
                        <span class="block text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Lembur</span>
                        <span class="block text-2xl font-black text-slate-800 dark:text-slate-100 font-mono tracking-tight">{{ number_format($stats['overtime'], 1) }} <span class="text-xs">Jam</span></span>
                        <span class="block text-[9px] font-medium text-slate-400 dark:text-slate-500">Hari ini</span>
                    </div>
                </div>
                <div class="text-right space-y-0.5 shrink-0">
                    <span class="inline-flex items-center text-[10px] font-bold text-blue-600 dark:text-blue-400">→ 0%</span>
                    <span class="block text-[8px] text-slate-400 dark:text-slate-550 font-semibold uppercase">dari kemarin</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Middle Row: Attendance Actions & Announcements -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
        <!-- Card: Presensi Hari Ini Actions -->
        <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl shadow-sm flex flex-col justify-between space-y-6">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-150 dark:border-slate-800/60">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-50/80 dark:bg-white/5 text-[#1B3BB6] dark:text-blue-400 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4.5 h-4.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white">Presensi Hari Ini</h3>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">Ringkasan kehadiran Anda pada hari ini</p>
                        </div>
                    </div>
                    @if($todayAttendance && $todayAttendance->clock_out)
                        <span class="px-2.5 py-1 bg-emerald-500/10 text-emerald-500 rounded-lg text-[10px] font-bold flex items-center space-x-1 border border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Selesai</span>
                        </span>
                    @elseif($todayAttendance)
                        <span class="px-2.5 py-1 bg-amber-500/10 text-amber-500 rounded-lg text-[10px] font-bold flex items-center space-x-1 border border-amber-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            <span>Sedang Bekerja</span>
                        </span>
                    @else
                        <span class="px-2.5 py-1 bg-rose-500/10 text-rose-500 rounded-lg text-[10px] font-bold flex items-center space-x-1 border border-rose-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                            <span>Belum Hadir</span>
                        </span>
                    @endif
                </div>
                
                @if($employee)
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6 py-6">
                        <!-- Clock Visual Ring Widget -->
                        <div class="relative w-40 h-40 shrink-0">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="42" stroke="#f1f5f9" stroke-width="6" fill="transparent" class="dark:stroke-slate-800" />
                                <circle id="circle-progress" cx="50" cy="50" r="42" stroke="#10b981" stroke-width="6" fill="transparent" stroke-dasharray="264" stroke-dashoffset="{{ !$todayAttendance ? '264' : ($todayAttendance->clock_out ? '0' : '132') }}" stroke-linecap="round" />
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-3xl font-black text-slate-800 dark:text-slate-100 font-mono tracking-tight" id="live-timer">00:00</span>
                                <span class="text-[10px] text-slate-400 dark:text-slate-505 font-bold uppercase tracking-wider mt-0.5">WIB</span>
                            </div>
                        </div>

                        <!-- Instruction Status Panel -->
                        <div class="flex-1 w-full">
                            @if(!$todayAttendance)
                                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-850/60 p-5 rounded-2xl space-y-4 shadow-sm">
                                    <div class="space-y-1">
                                        <span class="block text-[9px] font-bold text-slate-450 dark:text-slate-500 uppercase tracking-widest">Status Kehadiran</span>
                                        <h4 class="text-base font-extrabold text-slate-700 dark:text-slate-200">Belum Clock In</h4>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 leading-relaxed">Silakan klik tombol di bawah untuk mencatat jam masuk kerja hari ini.</p>
                                    </div>
                                    <form action="{{ route('attendances.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="clock_in">
                                        <button type="submit" class="w-full py-2.5 bg-[#1b3bb6] hover:bg-[#15309b] text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center justify-center space-x-2 cursor-pointer border-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            <span>Clock In Masuk</span>
                                        </button>
                                    </form>
                                </div>
                            @elseif(!$todayAttendance->clock_out)
                                <div class="bg-[#F0FDF4] dark:bg-emerald-950/10 border border-emerald-100 dark:border-emerald-900/10 p-5 rounded-2xl space-y-4 shadow-sm">
                                    <div class="space-y-1">
                                        <span class="block text-[9px] font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-widest">Status Kehadiran</span>
                                        <h4 class="text-base font-extrabold text-emerald-650 dark:text-emerald-400">Aktif Bekerja</h4>
                                        <p class="text-xs text-slate-550 dark:text-slate-400 leading-relaxed">Anda sudah absen masuk. Terima kasih dan tetap semangat!</p>
                                    </div>
                                    <form action="{{ route('attendances.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="clock_out">
                                        <button type="submit" class="w-full py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center justify-center space-x-2 cursor-pointer border-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                            </svg>
                                            <span>Clock Out Keluar</span>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-850/60 p-5 rounded-2xl flex items-center justify-between shadow-sm">
                                    <div class="space-y-1 pr-4">
                                        <span class="block text-[9px] font-bold text-slate-450 dark:text-slate-500 uppercase tracking-widest">Status Presensi</span>
                                        <h4 class="text-base font-extrabold text-slate-700 dark:text-slate-200">Selesai</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-450 leading-relaxed">Kerja bagus hari ini! Sampai jumpa besok pagi.</p>
                                    </div>
                                    <div class="p-3 bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-450 rounded-full shrink-0 shadow-inner">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.467 5.99 5.99 0 0 0-1.925 3.546 5.974 5.974 0 0 1-2.133-1A3.75 3.75 0 0 0 12 18Z" />
                                        </svg>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center py-12 text-slate-400 font-semibold text-xs">
                        Silakan hubungi administrator untuk memetakan user Anda ke data pegawai.
                    </div>
                @endif
            </div>

            <div class="border-t border-slate-100 dark:border-slate-850/60 pt-4 text-left">
                <a href="{{ route('attendances.index') }}" class="text-xs text-[#1b3bb6] dark:text-blue-400 font-bold hover:underline inline-flex items-center space-x-1">
                    <span>Lihat Riwayat Absensi</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Card: Pengumuman & Notifikasi -->
        <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl shadow-sm flex flex-col justify-between space-y-6">
            <div>
                <div class="flex items-center justify-between pb-4 border-b border-slate-150 dark:border-slate-800/60">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-50/80 dark:bg-white/5 text-[#1B3BB6] dark:text-blue-400 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4.5 h-4.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white">Pengumuman & Notifikasi</h3>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium">Informasi terbaru dari perusahaan</p>
                        </div>
                    </div>
                    
                    <a href="{{ route('notifications.page') }}" class="px-3 py-1.5 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-[#1b3bb6] dark:hover:text-blue-400 transition flex items-center space-x-1.5 shadow-sm">
                        <span>Lihat Semua</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
                
                <div class="py-8 flex flex-col items-center justify-center text-center space-y-4">
                    <!-- Bell SVG Illustration -->
                    <div class="relative w-24 h-24 flex items-center justify-center bg-blue-50/50 dark:bg-blue-950/20 rounded-full border border-blue-100/30">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-[#1B3BB6] dark:text-blue-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-700 dark:text-slate-200">Belum ada pengumuman terbaru</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1 max-w-[240px]">Pengumuman akan muncul di sini jika sudah tersedia.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts for dynamic gauge counting -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Live Time Counter script with universal ISO-8601 parsing
        const clockInTimeStr = '{{ $clockInIso }}';
        const clockOutTimeStr = '{{ $clockOutIso }}';
        const timerEl = document.getElementById('live-timer');
        
        if (timerEl) {
            const clockIn = clockInTimeStr ? new Date(clockInTimeStr) : null;
            const clockOut = clockOutTimeStr ? new Date(clockOutTimeStr) : null;

            if (clockIn && !isNaN(clockIn.getTime()) && (!clockOut || isNaN(clockOut.getTime()))) {
                // State: Clocked-in, active counting
                function updateTimer() {
                    const now = new Date();
                    const diffMs = now - clockIn;
                    if (diffMs > 0) {
                        const diffHrs = Math.floor(diffMs / 3600000);
                        const diffMins = Math.floor((diffMs % 3600000) / 60000);
                        
                        const formattedHrs = String(diffHrs).padStart(2, '0');
                        const formattedMins = String(diffMins).padStart(2, '0');
                        timerEl.textContent = `${formattedHrs}:${formattedMins}`;
                        
                        // 8 hours shift = 28,800,000 ms progress indicator
                        const progress = Math.min(1, diffMs / 28800000);
                        const offset = 264 - (264 * progress);
                        const progressCircle = document.getElementById('circle-progress');
                        if (progressCircle) {
                            progressCircle.setAttribute('stroke-dashoffset', offset);
                        }
                    } else {
                        timerEl.textContent = '00:00';
                    }
                }
                
                updateTimer();
                setInterval(updateTimer, 1000);
            } else if (clockIn && !isNaN(clockIn.getTime()) && clockOut && !isNaN(clockOut.getTime())) {
                // State: Completed attendance, show static duration
                const diffMs = clockOut - clockIn;
                if (diffMs > 0) {
                    const diffHrs = Math.floor(diffMs / 3600000);
                    const diffMins = Math.floor((diffMs % 3600000) / 60000);
                    timerEl.textContent = `${String(diffHrs).padStart(2, '0')}:${String(diffMins).padStart(2, '0')}`;
                    
                    const progressCircle = document.getElementById('circle-progress');
                    if (progressCircle) {
                        progressCircle.setAttribute('stroke-dashoffset', '0');
                    }
                } else {
                    timerEl.textContent = '00:00';
                }
            } else {
                timerEl.textContent = '00:00';
            }
        }
    });
</script>
@endsection
