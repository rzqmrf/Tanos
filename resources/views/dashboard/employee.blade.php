@extends('layouts.app')

@section('title', 'Dashboard Pegawai — Tanos ERP')

@section('content')
@php
    $name = $employee ? $employee->name : session('user.name', 'Karyawan');
@endphp

<div class="space-y-5 w-full">

    {{-- ═══════════════════════════════════════════════════════════════
         1. GREETING HEADER
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="p-6 bg-white dark:bg-slate-900 rounded-xl border border-slate-200/60 dark:border-slate-800/80 shadow-sm flex items-center justify-between flex-wrap gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-800 dark:text-white leading-tight">
                Selamat Datang, {{ $name }} 👋
            </h1>
            <p class="text-[13px] text-slate-400 dark:text-slate-400 mt-1">Pantau kehadiran dan aktivitas Anda dengan mudah setiap hari.</p>
        </div>

        <div class="flex items-center space-x-3 bg-slate-50 dark:bg-slate-800/50 px-4 py-2.5 rounded-xl border border-slate-100 dark:border-slate-700/50">
            <div class="p-2 bg-[#E9EFFF] dark:bg-blue-900/30 text-[#1B3BB6] dark:text-blue-400 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
            </div>
            <div>
                <span class="block text-xs font-bold text-slate-700 dark:text-white">{{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</span>
                <span class="block text-[10px] text-slate-400 dark:text-slate-500 font-semibold mt-0.5">Sistem Portal Pegawai</span>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         ALERT MESSAGES
    ═══════════════════════════════════════════════════════════════ --}}
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

    {{-- ═══════════════════════════════════════════════════════════════
         2. METRICS ROW (4 equal-width cards)
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- Hadir --}}
        <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-xl shadow-sm">
            <div class="flex items-center space-x-3.5">
                <div class="w-11 h-11 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </div>
                <div>
                    <span class="block text-[11px] font-semibold text-slate-400 dark:text-slate-500">Hadir</span>
                    <span class="block text-2xl font-extrabold text-slate-800 dark:text-white leading-tight">{{ $stats['present'] }}</span>
                    <span class="block text-[10px] text-slate-400 dark:text-slate-500">Hari ini</span>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800/60 flex items-center text-[11px]">
                <span class="text-emerald-600 dark:text-emerald-400 font-bold flex items-center space-x-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" /></svg>
                    <span>100%</span>
                </span>
                <span class="ml-1.5 text-slate-400 dark:text-slate-500">dari kemarin</span>
            </div>
        </div>

        {{-- Sakit / Izin --}}
        <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-xl shadow-sm">
            <div class="flex items-center space-x-3.5">
                <div class="w-11 h-11 bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 rounded-full flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div>
                    <span class="block text-[11px] font-semibold text-slate-400 dark:text-slate-500">Sakit / Izin</span>
                    <span class="block text-2xl font-extrabold text-slate-800 dark:text-white leading-tight">{{ $stats['sick_permit'] }}</span>
                    <span class="block text-[10px] text-slate-400 dark:text-slate-500">Hari ini</span>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800/60 flex items-center text-[11px]">
                <span class="text-amber-500 dark:text-amber-400 font-bold">→ 0%</span>
                <span class="ml-1.5 text-slate-400 dark:text-slate-500">dari kemarin</span>
            </div>
        </div>

        {{-- Alfa / Mangkir --}}
        <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-xl shadow-sm">
            <div class="flex items-center space-x-3.5">
                <div class="w-11 h-11 bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400 rounded-full flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    <span class="block text-[11px] font-semibold text-slate-400 dark:text-slate-500">Alfa / Mangkir</span>
                    <span class="block text-2xl font-extrabold text-slate-800 dark:text-white leading-tight">{{ $stats['absent'] }}</span>
                    <span class="block text-[10px] text-slate-400 dark:text-slate-500">Hari ini</span>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800/60 flex items-center text-[11px]">
                <span class="text-rose-500 dark:text-rose-400 font-bold">→ 0%</span>
                <span class="ml-1.5 text-slate-400 dark:text-slate-500">dari kemarin</span>
            </div>
        </div>

        {{-- Total Lembur --}}
        <div class="p-4 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-xl shadow-sm">
            <div class="flex items-center space-x-3.5">
                <div class="w-11 h-11 bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div>
                    <span class="block text-[11px] font-semibold text-slate-400 dark:text-slate-500">Total Lembur</span>
                    <span class="block text-2xl font-extrabold text-slate-800 dark:text-white leading-tight">{{ number_format($stats['overtime'], 1) }} <span class="text-sm font-bold">Jam</span></span>
                    <span class="block text-[10px] text-slate-400 dark:text-slate-500">Hari ini</span>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 dark:border-slate-800/60 flex items-center text-[11px]">
                <span class="text-blue-500 dark:text-blue-400 font-bold">→ 0%</span>
                <span class="ml-1.5 text-slate-400 dark:text-slate-500">dari kemarin</span>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════
         3. MIDDLE ROW — Presensi + Pengumuman (equal-height)
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-stretch">

        {{-- ── Presensi Hari Ini ────────────────────────────────────── --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-xl shadow-sm flex flex-col">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800/60">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white">Presensi Hari Ini</h3>
                @if($todayAttendance && $todayAttendance->clock_out)
                    <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full text-[10px] font-bold flex items-center space-x-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>Selesai</span>
                    </span>
                @endif
            </div>

            {{-- Body --}}
            <div class="flex-1 px-6 py-5">
                @if($employee)
                    <div class="flex flex-col md:flex-row items-center gap-6">
                        {{-- Circular Clock Gauge --}}
                        <div class="relative w-36 h-36 shrink-0">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="42" stroke="#e2e8f0" stroke-width="6" fill="transparent" class="dark:stroke-slate-800" />
                                <circle id="circle-progress" cx="50" cy="50" r="42" stroke="#10b981" stroke-width="6" fill="transparent" stroke-dasharray="264" stroke-dashoffset="{{ !$todayAttendance ? '264' : ($todayAttendance->clock_out ? '0' : '132') }}" stroke-linecap="round" />
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                {{-- NAMPILIN JAM MASUK DARI DB (CLOCK_IN) --}}
                                <span class="text-2xl font-extrabold text-slate-800 dark:text-white font-mono">
                                    {{ $todayAttendance && $todayAttendance->clock_in ? \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') : '00:00' }}
                                </span>
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-semibold mt-0.5">WIB</span>
                            </div>
                        </div>

                        {{-- Status Panel --}}
                        <div class="flex-1 w-full space-y-3">
    @if(!$todayAttendance)
        {{-- Kondisi 1: Belum Presensi Sama Sekali --}}
        <div>
            <span class="block text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Status Kehadiran</span>
            <h4 class="text-base font-bold text-slate-700 dark:text-slate-200 mt-0.5">Belum Clock In</h4>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 leading-relaxed">Silakan klik tombol di bawah untuk mencatat jam masuk kerja hari ini.</p>
        </div>
        <form action="{{ route('attendances.store') }}" method="POST">
            @csrf
            <input type="hidden" name="action" value="clock_in">
            <button type="submit" class="w-full py-2.5 bg-[#1b3bb6] hover:bg-[#15309b] text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center justify-center space-x-2 cursor-pointer border-none">
                <span>Clock In Masuk</span>
            </button>
        </form>

    @elseif(in_array($todayAttendance->status, ['Sakit', 'Izin', 'Alfa', 'Cuti']))
        {{-- Kondisi 2: Kalo Statusnya Sakit / Izin / Alfa (cannot clock in/out) --}}
        <div>
            <span class="block text-[10px] font-semibold text-amber-500 uppercase tracking-wider">Keterangan Hari Ini</span>
            <h4 class="text-base font-bold text-amber-600 dark:text-amber-400 mt-0.5">Status: {{ $todayAttendance->status }}</h4>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 leading-relaxed">
                Anda tercatat <span class="font-bold text-slate-200">{{ $todayAttendance->status }}</span> pada hari ini. Tidak perlu melakukan presensi Clock In / Clock Out.
            </p>
        </div>
        <div class="w-full py-2.5 bg-slate-800 text-slate-400 rounded-xl text-xs font-semibold text-center border border-slate-700">
            Presensi Diliburkan ({{ $todayAttendance->status }})
        </div>

    @elseif(!$todayAttendance->clock_out)
        {{-- Kondisi 3: Sudah Clock In, Belum Clock Out --}}
        <div>
            <span class="block text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Anda sudah melakukan</span>
            <h4 class="text-base font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">Clock In ({{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }})</h4>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 leading-relaxed">Terima kasih! Tetap semangat dalam bekerja.</p>
        </div>
        <form action="{{ route('attendances.store') }}" method="POST">
            @csrf
            <input type="hidden" name="action" value="clock_out">
            <button type="submit" class="w-full py-2.5 bg-[#1b3bb6] hover:bg-[#15309b] text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center justify-center space-x-2 cursor-pointer border-none">
                <span>Clock Out Keluar</span>
            </button>
        </form>

    @else
        {{-- Kondisi 4: Sudah Clock Out (Selesai) --}}
        <div>
            <span class="block text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Status Presensi</span>
            <h4 class="text-base font-bold text-slate-700 dark:text-slate-200 mt-0.5">Selesai Kerja</h4>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1 leading-relaxed">Kerja bagus hari ini! Sampai jumpa besok pagi.</p>
        </div>
    @endif
</div>
                    </div>
                @else
                    <div class="text-center py-10 text-slate-400 text-xs font-medium">
                        Silakan hubungi administrator untuk memetakan user Anda ke data pegawai.
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="px-6 py-3.5 border-t border-slate-100 dark:border-slate-800/60">
                <a href="{{ route('attendances.index') }}" class="text-xs text-[#1b3bb6] dark:text-blue-400 font-bold hover:underline inline-flex items-center space-x-1">
                    <span>Lihat Riwayat Absensi</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                </a>
            </div>
        </div>

        {{-- ── Pengumuman & Notifikasi ─────────────────────────────── --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-xl shadow-sm flex flex-col">
            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800/60">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white">Pengumuman & Notifikasi</h3>
                <a href="{{ route('notifications.page') }}" class="text-xs text-[#1b3bb6] dark:text-blue-400 font-bold hover:underline inline-flex items-center space-x-1">
                    <span>Lihat Semua</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                </a>
            </div>

            {{-- Body (empty state) --}}
            <div class="flex-1 px-6 py-8 flex flex-col items-center justify-center text-center space-y-4">
                <div class="w-20 h-20 bg-blue-50 dark:bg-blue-950/20 rounded-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-9 h-9 text-[#1B3BB6] dark:text-blue-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">Belum ada pengumuman terbaru</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Pengumuman akan muncul di sini jika sudah tersedia.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection