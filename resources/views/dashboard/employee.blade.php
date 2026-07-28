@extends('layouts.app')

@section('title', 'Dashboard Pegawai — Tanos ERP')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Greeting & Info Header -->
    <div class="p-6 bg-gradient-to-r from-blue-700 to-indigo-800 text-white rounded-2xl shadow-sm flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 rounded-full bg-white/10 text-white flex items-center justify-center font-bold text-xl select-none shrink-0 uppercase border border-white/20">
                {{ $employee ? substr($employee->name, 0, 2) : 'EP' }}
            </div>
            <div>
                <h1 class="text-xl font-bold">Selamat Datang, {{ $employee ? $employee->name : session('user.name') }}!</h1>
                <p class="text-xs text-blue-100 font-medium mt-0.5">
                    @if($employee)
                        Jabatan: {{ $employee->role }} • Regional: {{ $employee->regional }} • Segment: {{ $employee->segment }}
                    @else
                        Akun Anda belum terhubung dengan data master pegawai. Hubungi admin untuk konfigurasi.
                    @endif
                </p>
            </div>
        </div>
        <div class="text-right shrink-0 bg-white/10 px-4 py-2 rounded-xl border border-white/10">
            <span class="block text-sm font-semibold font-mono">{{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</span>
            <span class="block text-[10px] text-blue-200 font-medium text-right uppercase tracking-wider mt-0.5">Sistem Portal Pegawai</span>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Col 1: Clock In/Out Widget -->
        <div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-4">Presensi Hari Ini</h3>
                
                @if($employee)
                    <div class="text-center py-6 flex flex-col items-center justify-center space-y-4">
                        @if(!$todayAttendance)
                            <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800/80 text-slate-400 flex items-center justify-center animate-pulse mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 max-w-[200px]">Anda belum absen masuk hari ini. Silakan klik tombol di bawah.</p>
                            
                            <form action="{{ route('attendances.store') }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="action" value="clock_in">
                                <button type="submit" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm hover:shadow transition cursor-pointer">
                                    CLOCK IN MASUK
                                </button>
                            </form>
                        @elseif(!$todayAttendance->clock_out)
                            <div class="w-16 h-16 rounded-full bg-emerald-100 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-lg font-mono mb-2">
                                {{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }}
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 max-w-[200px]">Anda sudah melakukan Clock In. Jangan lupa untuk Clock Out jika jam pulang.</p>
                            
                            <form action="{{ route('attendances.store') }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="action" value="clock_out">
                                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm hover:shadow transition cursor-pointer">
                                    CLOCK OUT KELUAR
                                </button>
                            </form>
                        @else
                            <div class="w-16 h-16 rounded-full bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Presensi Hari Ini Selesai</span>
                            <span class="block text-[10px] text-slate-400 font-semibold font-mono">Masuk: {{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }} • Keluar: {{ \Carbon\Carbon::parse($todayAttendance->clock_out)->format('H:i') }}</span>
                        @endif
                    </div>
                @else
                    <div class="text-center py-10 text-slate-400">
                        Tidak ada aksi kehadiran tersedia.
                    </div>
                @endif
            </div>

            <div class="mt-4 border-t border-slate-100 dark:border-slate-800/80 pt-4 text-center">
                <a href="{{ route('attendances.index') }}" class="text-xs text-blue-600 dark:text-blue-400 font-bold hover:underline">Lihat Riwayat Absensi &rarr;</a>
            </div>
        </div>

        <!-- Col 2: Summary Stats (Monthly) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-4">Statistik Kehadiran Anda (Bulan Ini)</h3>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <!-- Stat 1 -->
                    <div class="p-4 bg-emerald-50/50 dark:bg-emerald-950/10 border border-emerald-100/50 dark:border-emerald-950/40 rounded-xl text-center">
                        <span class="block text-2xl font-bold text-emerald-600 dark:text-emerald-400 font-mono">{{ $stats['present'] }}</span>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1">Hadir (Hari)</span>
                    </div>

                    <!-- Stat 2 -->
                    <div class="p-4 bg-amber-50/50 dark:bg-amber-950/10 border border-amber-100/50 dark:border-amber-950/40 rounded-xl text-center">
                        <span class="block text-2xl font-bold text-amber-600 dark:text-amber-400 font-mono">{{ $stats['sick_permit'] }}</span>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1">Sakit / Izin</span>
                    </div>

                    <!-- Stat 3 -->
                    <div class="p-4 bg-rose-50/50 dark:bg-rose-950/10 border border-rose-100/50 dark:border-rose-950/40 rounded-xl text-center">
                        <span class="block text-2xl font-bold text-rose-600 dark:text-rose-400 font-mono">{{ $stats['absent'] }}</span>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1">Alfa / Tanpa Keterangan</span>
                    </div>

                    <!-- Stat 4 -->
                    <div class="p-4 bg-indigo-50/50 dark:bg-indigo-950/10 border border-indigo-100/50 dark:border-indigo-950/40 rounded-xl text-center">
                        <span class="block text-2xl font-bold text-indigo-600 dark:text-indigo-400 font-mono">{{ number_format($stats['overtime'], 1) }}</span>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1">Lembur (Jam)</span>
                    </div>
                </div>
            </div>

            <!-- Notifications / Announcement Feed -->
            <div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-4">Pengumuman & Notifikasi Terbaru</h3>

                <div class="space-y-3">
                    @forelse($notifications as $notif)
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-800/20 border border-slate-100 dark:border-slate-800/80 rounded-xl flex items-start space-x-3 hover:bg-slate-100/50 dark:hover:bg-slate-800/40 transition">
                            <div class="p-1.5 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-lg shrink-0">
                                @if($notif->type === 'invoice')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                @elseif($notif->type === 'project')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                                    </svg>
                                @endif
                            </div>
                            <div class="space-y-0.5">
                                <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $notif->title }}</h4>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 font-medium leading-relaxed">{{ $notif->message }}</p>
                                <span class="block text-[9px] text-slate-400 font-semibold font-mono pt-1">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-400 text-xs font-semibold">
                            Belum ada pengumuman terbaru saat ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
