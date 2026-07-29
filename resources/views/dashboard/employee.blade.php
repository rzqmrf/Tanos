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

    // Calculate dynamic line chart data for current month
    $daysInMonth = \Carbon\Carbon::now()->daysInMonth;
    $chartData = array_fill(0, $daysInMonth, 0.0);
    
    if ($employee) {
        $startOfMonth = \Carbon\Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = \Carbon\Carbon::now()->endOfMonth()->toDateString();
        
        $monthlyRecords = \App\Models\Attendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();
            
        foreach ($monthlyRecords as $record) {
            $day = \Carbon\Carbon::parse($record->date)->day;
            if ($record->status === 'Hadir') {
                $chartData[$day - 1] = 4.0; // Matches the target visual graph height (4.0)
            } elseif (in_array($record->status, ['Sakit', 'Izin'])) {
                $chartData[$day - 1] = 1.2; // Sakit/Izin has lower amplitude
            }
        }
    }
    
    $chartDataJson = json_encode($chartData);
    $chartLabelsJson = json_encode(range(1, $daysInMonth));
    
    // Calculate attendance percentage
    $attendanceRate = $daysInMonth > 0 ? ($stats['present'] / $daysInMonth) * 100 : 0;
@endphp

<div class="space-y-6 w-full">
    <!-- Greeting & Info Header Card -->
    <div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 shadow-sm flex items-center justify-between flex-wrap gap-4 transition duration-150">
        <div class="space-y-1">
            <h1 class="text-2xl font-black text-slate-800 dark:text-slate-100 flex items-center gap-2">
                <span>Selamat Datang, {{ $name }}</span>
                <span class="animate-bounce">👋</span>
            </h1>
            <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Pantau kehadiran dan aktivitas Anda dengan mudah setiap hari.</p>
        </div>

        <div class="flex items-center space-x-3.5 bg-slate-50 dark:bg-slate-950 p-3 rounded-2xl border border-slate-100 dark:border-slate-850/60 shadow-inner">
            <div class="p-2 bg-[#E9EFFF] text-[#1B3BB6] rounded-xl">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
            </div>
            <div>
                <span class="block text-xs font-extrabold text-slate-700 dark:text-slate-200 font-mono tracking-tight">{{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</span>
                <span class="block text-[10px] text-slate-400 dark:text-slate-505 font-bold uppercase tracking-wider mt-0.5">Sistem Portal Pegawai</span>
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
        <div class="p-5 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl shadow-sm space-y-4">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 rounded-2xl shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </div>
                <div class="space-y-0.5">
                    <span class="block text-[11px] font-bold text-slate-400 dark:text-slate-505 uppercase tracking-wider">Hadir</span>
                    <span class="block text-3xl font-black text-slate-800 dark:text-slate-100 font-mono tracking-tight">{{ $stats['present'] }}</span>
                    <span class="block text-[10px] font-medium text-slate-400 dark:text-slate-500">Hari ini</span>
                </div>
            </div>
            <div class="flex items-center text-[10px] font-bold space-x-1 border-t border-slate-100 dark:border-slate-850/60 pt-3">
                <span class="text-emerald-600 dark:text-emerald-400 flex items-center space-x-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3 h-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
                    </svg>
                    <span>100%</span>
                </span>
                <span class="text-slate-400 dark:text-slate-500">dari kemarin</span>
            </div>
        </div>

        <!-- Card 2: Sakit / Izin -->
        <div class="p-5 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl shadow-sm space-y-4">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 rounded-2xl shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div class="space-y-0.5">
                    <span class="block text-[11px] font-bold text-slate-400 dark:text-slate-550 uppercase tracking-wider">Sakit / Izin</span>
                    <span class="block text-3xl font-black text-slate-800 dark:text-slate-100 font-mono tracking-tight">{{ $stats['sick_permit'] }}</span>
                    <span class="block text-[10px] font-medium text-slate-400 dark:text-slate-500">Hari ini</span>
                </div>
            </div>
            <div class="flex items-center text-[10px] font-bold space-x-1 border-t border-slate-100 dark:border-slate-850/60 pt-3">
                <span class="text-amber-500 dark:text-amber-400">→ 0%</span>
                <span class="text-slate-400 dark:text-slate-505">dari kemarin</span>
            </div>
        </div>

        <!-- Card 3: Alfa / Mangkir -->
        <div class="p-5 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl shadow-sm space-y-4">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 rounded-2xl shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </div>
                <div class="space-y-0.5">
                    <span class="block text-[11px] font-bold text-slate-400 dark:text-slate-505 uppercase tracking-wider">Alfa / Mangkir</span>
                    <span class="block text-3xl font-black text-slate-800 dark:text-slate-100 font-mono tracking-tight">{{ $stats['absent'] }}</span>
                    <span class="block text-[10px] font-medium text-slate-400 dark:text-slate-500">Hari ini</span>
                </div>
            </div>
            <div class="flex items-center text-[10px] font-bold space-x-1 border-t border-slate-100 dark:border-slate-850/60 pt-3">
                <span class="text-rose-500 dark:text-rose-400">→ 0%</span>
                <span class="text-slate-400 dark:text-slate-505">dari kemarin</span>
            </div>
        </div>

        <!-- Card 4: Total Lembur -->
        <div class="p-5 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl shadow-sm space-y-4">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-2xl shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div class="space-y-0.5">
                    <span class="block text-[11px] font-bold text-slate-400 dark:text-slate-505 uppercase tracking-wider">Total Lembur</span>
                    <span class="block text-3xl font-black text-slate-800 dark:text-slate-100 font-mono tracking-tight">{{ number_format($stats['overtime'], 1) }} <span class="text-xs">Jam</span></span>
                    <span class="block text-[10px] font-medium text-slate-400 dark:text-slate-500">Hari ini</span>
                </div>
            </div>
            <div class="flex items-center text-[10px] font-bold space-x-1 border-t border-slate-100 dark:border-slate-850/60 pt-3">
                <span class="text-blue-600 dark:text-blue-450">→ 0%</span>
                <span class="text-slate-400 dark:text-slate-505">dari kemarin</span>
            </div>
        </div>
    </div>

    <!-- Middle Row: Attendance Actions & Announcements -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Card: Presensi Hari Ini Actions -->
        <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl shadow-sm flex flex-col justify-between space-y-6">
            <div>
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-505 uppercase tracking-wider pb-3 border-b border-slate-100 dark:border-slate-850/60">Presensi Hari Ini</h3>
                
                @if($employee)
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6 py-6">
                        <!-- Clock Visual Ring Widget -->
                        <div class="relative w-40 h-40 shrink-0">
                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="42" stroke="#f1f5f9" stroke-width="6" fill="transparent" class="dark:stroke-slate-850" />
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
                                <div class="bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-850/60 p-5 rounded-2xl space-y-4">
                                    <div class="space-y-1">
                                        <span class="block text-[10px] font-bold text-slate-450 dark:text-slate-500 uppercase tracking-wider">Status Kehadiran</span>
                                        <h4 class="text-base font-bold text-slate-700 dark:text-slate-200">Belum Clock In</h4>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 leading-relaxed">Silakan klik tombol di bawah untuk mencatat jam masuk kerja hari ini.</p>
                                    </div>
                                    <form action="{{ route('attendances.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="clock_in">
                                        <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center justify-center space-x-2 cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            <span>Clock In Masuk</span>
                                        </button>
                                    </form>
                                </div>
                            @elseif(!$todayAttendance->clock_out)
                                <div class="bg-[#F0FDF4] dark:bg-emerald-950/10 border border-emerald-100 dark:border-emerald-900/10 p-5 rounded-2xl space-y-4">
                                    <div class="space-y-1">
                                        <span class="block text-[10px] font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-wider">Anda sudah melakukan</span>
                                        <h4 class="text-base font-bold text-emerald-600 dark:text-emerald-450">Clock In</h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Terima kasih! Tetap semangat dalam bekerja.</p>
                                    </div>
                                    <form action="{{ route('attendances.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="clock_out">
                                        <button type="submit" class="w-full py-2.5 bg-[#1b3bb6] hover:bg-[#15309b] text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center justify-center space-x-2 cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                            </svg>
                                            <span>Clock Out Keluar</span>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="bg-blue-50/40 dark:bg-blue-950/10 border border-blue-100/50 dark:border-blue-900/10 p-5 rounded-2xl space-y-2">
                                    <span class="block text-[10px] font-bold text-[#1b3bb6] dark:text-blue-400 uppercase tracking-wider">Presensi Hari Ini</span>
                                    <h4 class="text-base font-bold text-slate-700 dark:text-slate-200">Selesai</h4>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 leading-relaxed">Kerja bagus hari ini! Sampai jumpa besok pagi.</p>
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
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-505 uppercase tracking-wider pb-3 border-b border-slate-100 dark:border-slate-850/60">Pengumuman & Notifikasi</h3>
                
                <div class="py-6 flex flex-col items-center justify-center text-center space-y-4">
                    <!-- Bell SVG Illustration -->
                    <div class="relative w-28 h-28 flex items-center justify-center bg-[#E9F0FE] dark:bg-blue-950/20 rounded-full border border-blue-100/30">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-[#1B3BB6] dark:text-blue-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                        </svg>
                        <div class="absolute -top-1.5 -right-1.5 w-6 h-6 rounded-full bg-[#1B3BB6] text-white font-bold text-[10px] flex items-center justify-center border-2 border-white dark:border-slate-900 shadow">
                            3
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 dark:text-slate-505 font-semibold max-w-[240px]">Belum ada pengumuman terbaru saat ini.</p>
                </div>
            </div>

            <div class="border-t border-slate-100 dark:border-slate-850/60 pt-4 text-left">
                <a href="{{ route('notifications.page') }}" class="text-xs text-[#1b3bb6] dark:text-blue-400 font-bold hover:underline inline-flex items-center space-x-1">
                    <span>Lihat Semua</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Row: Statistics & Tips -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Statistik Kehadiran (Line Chart) -->
        <div class="lg:col-span-2 p-6 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-850/60 pb-3">
                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">Statistik Kehadiran Anda (Bulan Ini)</h3>
                
                <div class="flex items-center space-x-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl px-3 py-1.5 text-xs font-medium text-slate-700 dark:text-slate-200 cursor-pointer shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-400 mr-1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    <span class="font-bold tracking-tight font-mono">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3 text-slate-400 ml-1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                    </svg>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Line Chart Area -->
                <div class="md:col-span-2 w-full min-h-[220px] flex items-center justify-center">
                    <div id="attendanceLineChart" class="w-full"></div>
                </div>

                <!-- Right Side Summary Blocks -->
                <div class="flex flex-col justify-center space-y-5 border-l border-slate-100 dark:border-slate-850/60 pl-0 md:pl-6">
                    <div class="space-y-1">
                        <span class="block text-[10px] font-bold text-slate-450 dark:text-slate-500 uppercase tracking-wider">Total Hari</span>
                        <div class="text-3xl font-black text-[#1b3bb6] dark:text-blue-400 font-mono tracking-tight">
                            {{ $daysInMonth }}
                        </div>
                        <span class="text-xs font-bold text-slate-500 dark:text-slate-450">Hari Kerja</span>
                    </div>
                    
                    <div class="space-y-1">
                        <span class="block text-[10px] font-bold text-slate-450 dark:text-slate-500 uppercase tracking-wider">Rata-rata Kehadiran</span>
                        <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono tracking-tight">
                            {{ number_format($attendanceRate, 1) }}%
                        </div>
                        <span class="block text-[10px] text-slate-400 dark:text-slate-500 font-medium">Dari total hari kerja</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tips Hari Ini -->
        <div class="p-6 bg-white dark:bg-slate-900 border border-slate-200/60 dark:border-slate-800/80 rounded-2xl shadow-sm flex flex-col justify-between relative overflow-hidden min-h-[300px]">
            <div class="flex items-center space-x-3.5 pb-3 border-b border-slate-100 dark:border-slate-850/60">
                <div class="p-2.5 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.467 5.99 5.99 0 0 0-1.925 3.546 5.974 5.974 0 0 1-2.133-1A3.75 3.75 0 0 0 12 18Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.467 5.99 5.99 0 0 0-1.925 3.546 5.974 5.974 0 0 1-2.133-1A3.75 3.75 0 0 0 12 18ZM12 18c-1.657 0-3-1.343-3-3 0-1.657 1.343-3 3-3m0 6c1.657 0 3-1.343 3-3 0-1.657-1.343-3-3-3" />
                    </svg>
                </div>
                <h3 class="text-xs font-bold text-slate-700 dark:text-slate-200 uppercase tracking-wider">Tips Hari Ini</h3>
            </div>

            <div class="my-auto py-4">
                <blockquote class="text-slate-600 dark:text-slate-300 font-medium italic text-base leading-relaxed">
                    "Disiplin hari ini akan membentuk hasil yang lebih besar esok."
                </blockquote>
            </div>

            <!-- Absolute leaf element positioned in bottom right corner -->
            <div class="absolute bottom-2 right-2 w-20 h-20 opacity-10 text-emerald-500 pointer-events-none select-none">
                <svg viewBox="0 0 24 24" fill="currentColor" class="w-full h-full">
                    <path d="M12 2C6.48 2 2 6.48 2 12c0 4.42 2.87 8.17 6.84 9.39.04-.32.06-.64.06-.97 0-4.04-3.28-7.31-7.31-7.31-.33 0-.65.02-.97.06C1.83 9.13 5.58 6.25 10 6.25c4.04 0 7.31 3.28 7.31 7.31 0 .33-.02.65-.06.97 3.97-1.22 6.84-4.97 6.84-9.39 0-5.52-4.48-10-10-10Z" />
                </svg>
            </div>

            <div class="border-t border-slate-100 dark:border-slate-850/60 pt-4 text-left">
                <a href="#" class="text-xs text-[#1b3bb6] dark:text-blue-400 font-bold hover:underline inline-flex items-center space-x-1">
                    <span>Lihat Tips Lainnya</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Scripts for dynamic gauge counting & ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Live Time Counter script
        const clockInTimeStr = '{{ $todayAttendance ? $todayAttendance->clock_in : "" }}';
        const clockOutTimeStr = '{{ $todayAttendance ? $todayAttendance->clock_out : "" }}';
        const timerEl = document.getElementById('live-timer');
        
        if (timerEl) {
            if (clockInTimeStr && !clockOutTimeStr) {
                const clockIn = new Date(clockInTimeStr.replace(/-/g, '/'));
                
                function updateTimer() {
                    const now = new Date();
                    const diffMs = now - clockIn;
                    if (diffMs > 0) {
                        const diffHrs = Math.floor(diffMs / 3600000);
                        const diffMins = Math.floor((diffMs % 3600000) / 60000);
                        
                        const formattedHrs = String(diffHrs).padStart(2, '0');
                        const formattedMins = String(diffMins).padStart(2, '0');
                        timerEl.textContent = `${formattedHrs}:${formattedMins}`;
                        
                        // 8 hours shift = 28,800,000 ms
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
            } else if (clockInTimeStr && clockOutTimeStr) {
                const clockIn = new Date(clockInTimeStr.replace(/-/g, '/'));
                const clockOut = new Date(clockOutTimeStr.replace(/-/g, '/'));
                const diffMs = clockOut - clockIn;
                const diffHrs = Math.floor(diffMs / 3600000);
                const diffMins = Math.floor((diffMs % 3600000) / 60000);
                timerEl.textContent = `${String(diffHrs).padStart(2, '0')}:${String(diffMins).padStart(2, '0')}`;
            } else {
                timerEl.textContent = '00:00';
            }
        }

        // 2. Attendance Line Chart Render
        const options = {
            chart: {
                type: 'line',
                height: 220,
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            series: [{
                name: 'Hari Kerja',
                data: {!! $chartDataJson !!}
            }],
            stroke: {
                width: 3,
                curve: 'smooth'
            },
            colors: ['#2563eb'],
            markers: {
                size: 5,
                colors: ['#2563eb'],
                strokeWidth: 2,
                strokeColors: '#ffffff',
                hover: { size: 7 }
            },
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'left',
                fontSize: '11px',
                fontWeight: 600,
                markers: {
                    width: 8,
                    height: 8,
                    radius: 12
                },
                itemMargin: {
                    vertical: 8
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            },
            xaxis: {
                categories: {!! $chartLabelsJson !!},
                labels: {
                    style: { colors: '#94a3b8', fontSize: '9px', fontWeight: 600 }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                min: 0,
                max: 5,
                tickAmount: 5,
                labels: {
                    formatter: function(val) {
                        return Math.round(val);
                    },
                    style: { colors: '#94a3b8', fontSize: '9px', fontWeight: 600 }
                }
            },
            tooltip: {
                theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                x: { show: true, formatter: (val) => `Hari ${val}` }
            }
        };

        const chart = new ApexCharts(document.querySelector("#attendanceLineChart"), options);
        chart.render();
    });
</script>
@endsection
