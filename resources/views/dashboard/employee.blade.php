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
@endphp

<div class="space-y-6 w-full">
    <!-- Greeting & Info Header Card -->
    <div class="p-6 bg-gradient-to-r from-blue-600 via-indigo-600 to-indigo-800 text-white rounded-2xl shadow-md flex items-center justify-between flex-wrap gap-6 transition duration-150">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md text-white flex items-center justify-center font-bold text-xl select-none shrink-0 uppercase border border-white/20 shadow-sm">
                {{ $initials }}
            </div>
            <div class="space-y-1">
                <h1 class="text-2xl font-black tracking-tight">Selamat Datang, {{ $name }}!</h1>
                <div class="flex flex-wrap items-center gap-2">
                    @if($employee)
                        <span class="px-2.5 py-0.5 bg-white/15 backdrop-blur-sm rounded-full text-[10px] font-bold uppercase tracking-wider text-blue-50 border border-white/10">
                            {{ $employee->role }}
                        </span>
                        <span class="px-2.5 py-0.5 bg-white/15 backdrop-blur-sm rounded-full text-[10px] font-bold uppercase tracking-wider text-blue-50 border border-white/10">
                            {{ $employee->regional }}
                        </span>
                        <span class="px-2.5 py-0.5 bg-white/15 backdrop-blur-sm rounded-full text-[10px] font-bold uppercase tracking-wider text-blue-50 border border-white/10">
                            {{ $employee->segment }}
                        </span>
                    @else
                        <span class="px-2.5 py-0.5 bg-rose-500/30 backdrop-blur-sm rounded-full text-[10px] font-bold uppercase tracking-wider text-rose-100 border border-rose-400/20">
                            Belum Terhubung
                        </span>
                        <span class="text-xs text-blue-100 font-medium">Hubungi admin untuk memetakan akun Anda ke data pegawai.</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="shrink-0 bg-white/10 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/10 text-right flex items-center space-x-3">
            <div class="text-blue-100">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 opacity-80">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                </svg>
            </div>
            <div>
                <span class="block text-sm font-extrabold font-mono tracking-tight">{{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}</span>
                <span class="block text-[10px] text-blue-200 font-bold uppercase tracking-wider mt-0.5">{{ \Carbon\Carbon::today()->translatedFormat('l') }}</span>
            </div>
        </div>
    </div>

    <!-- Alert Sukses / Eror -->
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Col 1: Clock In/Out Widget -->
        <div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 shadow-sm flex flex-col justify-between space-y-6">
            <div>
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800/50 pb-3">
                    <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Presensi Hari Ini</h3>
                    <span class="px-2 py-0.5 bg-slate-50 dark:bg-slate-800 rounded-lg text-[9px] font-bold text-slate-500 dark:text-slate-400 uppercase">Live Status</span>
                </div>
                
                @if($employee)
                    <div class="text-center py-6 flex flex-col items-center justify-center space-y-5">
                        @if(!$todayAttendance)
                            <!-- STATE: NOT YET CLOCKED IN -->
                            <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-slate-800/40 text-slate-400 flex items-center justify-center border border-slate-100 dark:border-slate-850 shadow-inner">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-sm font-bold text-slate-700 dark:text-slate-200">Belum Absen Masuk</h4>
                                <p class="text-xs text-slate-400 dark:text-slate-500 max-w-[220px] mx-auto leading-relaxed">Silakan lakukan pencatatan kehadiran masuk hari ini.</p>
                            </div>
                            
                            <form action="{{ route('attendances.store') }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="action" value="clock_in">
                                <button type="submit" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition-all duration-150 cursor-pointer uppercase tracking-wider">
                                    CLOCK IN MASUK
                                </button>
                            </form>
                        @elseif(!$todayAttendance->clock_out)
                            <!-- STATE: CLOCKED IN, PENDING CLOCK OUT -->
                            <div class="w-16 h-16 rounded-full bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-black text-lg font-mono border border-emerald-100 dark:border-emerald-900/30 shadow-inner">
                                {{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }}
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-sm font-bold text-emerald-600 dark:text-emerald-400">Sudah Absen Masuk</h4>
                                <p class="text-xs text-slate-400 dark:text-slate-500 max-w-[220px] mx-auto leading-relaxed">Jangan lupa untuk melakukan Clock Out saat jam pulang kerja selesai.</p>
                            </div>
                            
                            <form action="{{ route('attendances.store') }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="action" value="clock_out">
                                <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition-all duration-150 cursor-pointer uppercase tracking-wider">
                                    CLOCK OUT KELUAR
                                </button>
                            </form>
                        @else
                            <!-- STATE: ATTENDANCE COMPLETED -->
                            <div class="w-16 h-16 rounded-full bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-blue-900/30 shadow-inner">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-sm font-bold text-slate-700 dark:text-slate-200">Presensi Hari Ini Selesai</h4>
                                <div class="inline-flex items-center space-x-2 bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800 px-3 py-1 rounded-full text-[10px] font-bold font-mono text-slate-500 dark:text-slate-400 mt-1">
                                    <span>Masuk: {{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }}</span>
                                    <span class="text-slate-300 dark:text-slate-600">•</span>
                                    <span>Keluar: {{ \Carbon\Carbon::parse($todayAttendance->clock_out)->format('H:i') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-10 text-slate-450 dark:text-slate-500 text-xs font-semibold">
                        Fitur kehadiran terkunci sampai akun Anda terhubung dengan data pegawai.
                    </div>
                @endif
            </div>

            <div class="border-t border-slate-100 dark:border-slate-800/50 pt-4 text-center">
                <a href="{{ route('attendances.index') }}" class="text-xs text-blue-600 dark:text-blue-400 font-bold hover:text-blue-700 dark:hover:text-blue-300 transition flex items-center justify-center space-x-1">
                    <span>Lihat Riwayat Absensi & Cuti</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Col 2: Summary Stats (Monthly) & Announcements -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Monthly Statistics -->
            <div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Statistik Kehadiran Anda (Bulan Ini)</h3>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <!-- Stat Item 1: Hadir -->
                    <div class="p-4 bg-emerald-50/50 dark:bg-emerald-950/10 border border-emerald-100/50 dark:border-emerald-900/10 rounded-2xl flex flex-col items-center justify-center text-center transition hover:scale-[1.01]">
                        <div class="p-2 bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 rounded-xl mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <span class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 font-mono">{{ $stats['present'] }}</span>
                        <span class="text-[9px] font-bold text-slate-450 dark:text-slate-500 uppercase tracking-wider mt-1">Hadir (Hari)</span>
                    </div>

                    <!-- Stat Item 2: Sakit / Izin -->
                    <div class="p-4 bg-amber-50/50 dark:bg-amber-950/10 border border-amber-100/50 dark:border-amber-900/10 rounded-2xl flex flex-col items-center justify-center text-center transition hover:scale-[1.01]">
                        <div class="p-2 bg-amber-100 dark:bg-amber-950 text-amber-600 dark:text-amber-400 rounded-xl mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                        </div>
                        <span class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 font-mono">{{ $stats['sick_permit'] }}</span>
                        <span class="text-[9px] font-bold text-slate-450 dark:text-slate-500 uppercase tracking-wider mt-1">Sakit / Izin</span>
                    </div>

                    <!-- Stat Item 3: Alfa -->
                    <div class="p-4 bg-rose-50/50 dark:bg-rose-950/10 border border-rose-100/50 dark:border-rose-900/10 rounded-2xl flex flex-col items-center justify-center text-center transition hover:scale-[1.01]">
                        <div class="p-2 bg-rose-100 dark:bg-rose-950 text-rose-600 dark:text-rose-400 rounded-xl mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <span class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 font-mono">{{ $stats['absent'] }}</span>
                        <span class="text-[9px] font-bold text-slate-450 dark:text-slate-500 uppercase tracking-wider mt-1">Tanpa Absen</span>
                    </div>

                    <!-- Stat Item 4: Lembur -->
                    <div class="p-4 bg-indigo-50/50 dark:bg-indigo-950/10 border border-indigo-100/50 dark:border-indigo-900/10 rounded-2xl flex flex-col items-center justify-center text-center transition hover:scale-[1.01]">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 rounded-xl mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <span class="text-2xl font-extrabold text-slate-800 dark:text-slate-100 font-mono">{{ number_format($stats['overtime'], 1) }}</span>
                        <span class="text-[9px] font-bold text-slate-450 dark:text-slate-500 uppercase tracking-wider mt-1">Lembur (Jam)</span>
                    </div>
                </div>
            </div>

            <!-- Notifications Feed -->
            <div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Pengumuman & Notifikasi Terbaru</h3>

                <div class="space-y-3.5">
                    @forelse($notifications as $notif)
                        <div class="p-4 bg-slate-50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-850/60 rounded-xl flex items-start space-x-3.5 hover:bg-slate-100/40 dark:hover:bg-slate-850/80 transition">
                            <div class="p-2 bg-blue-50 dark:bg-blue-950 text-blue-600 dark:text-blue-400 rounded-xl shrink-0">
                                @if($notif->type === 'invoice')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                @elseif($notif->type === 'project')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                    </svg>
                                @endif
                            </div>
                            <div class="space-y-0.5">
                                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $notif->title }}</h4>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium leading-relaxed">{{ $notif->message }}</p>
                                <span class="block text-[9px] text-slate-450 font-semibold font-mono pt-1">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-450 dark:text-slate-500 text-xs font-semibold">
                            Belum ada pengumuman terbaru saat ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
