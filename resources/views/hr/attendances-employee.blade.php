@extends('layouts.app')

@section('title', 'Absensi Saya — Tanos ERP')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Greeting & Header Card -->
    <div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-lg select-none shrink-0 uppercase">
                {{ substr($employee->name, 0, 2) }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Halo, {{ $employee->name }}!</h1>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Jabatan: {{ $employee->role }} • Regional: {{ $employee->regional }}</p>
            </div>
        </div>
        <div class="text-right shrink-0">
            <span class="block text-sm font-semibold text-slate-800 dark:text-slate-100 font-mono">{{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</span>
            <span class="block text-xs text-slate-400 font-medium mt-0.5">Sistem Waktu Server</span>
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

    <!-- Clock In / Out Panel Card -->
    <div class="p-8 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm text-center">
        <h2 class="text-sm font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-6">Pencatatan Kehadiran Hari Ini</h2>

        <div class="flex flex-col items-center justify-center space-y-6">
            @if(!$todayAttendance)
                <!-- Not Clocked In Yet -->
                <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 dark:text-slate-500 animate-pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm">Anda belum mencatat jam masuk hari ini. Silakan klik tombol di bawah untuk Clock In.</p>
                
                <form action="{{ route('attendances.store') }}" method="POST" class="w-full max-w-xs">
                    @csrf
                    <input type="hidden" name="action" value="clock_in">
                    <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl text-base font-bold shadow-md shadow-emerald-500/10 hover:shadow-lg transition-all duration-150 transform hover:-translate-y-0.5 cursor-pointer">
                        CLOCK IN MASUK
                    </button>
                </form>
            @elseif(!$todayAttendance->clock_out)
                <!-- Clocked In, waiting for Clock Out -->
                <div class="w-20 h-20 rounded-full bg-emerald-100 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-2xl select-none font-mono">
                    {{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }}
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm">Anda sudah masuk pada jam <span class="font-bold text-slate-700 dark:text-slate-300 font-mono">{{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }}</span>. Jangan lupa untuk Clock Out saat selesai bekerja!</p>
                
                <form action="{{ route('attendances.store') }}" method="POST" class="w-full max-w-xs">
                    @csrf
                    <input type="hidden" name="action" value="clock_out">
                    <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl text-base font-bold shadow-md shadow-blue-500/10 hover:shadow-lg transition-all duration-150 transform hover:-translate-y-0.5 cursor-pointer">
                        CLOCK OUT KELUAR
                    </button>
                </form>
            @else
                <!-- Completed Absensi for Today -->
                <div class="w-20 h-20 rounded-full bg-blue-50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <p class="text-base font-bold text-slate-800 dark:text-slate-100">Absensi Hari Ini Selesai</p>
                    <p class="text-xs text-slate-400 font-medium">Jam Masuk: <span class="font-bold font-mono">{{ \Carbon\Carbon::parse($todayAttendance->clock_in)->format('H:i') }}</span> • Jam Keluar: <span class="font-bold font-mono">{{ \Carbon\Carbon::parse($todayAttendance->clock_out)->format('H:i') }}</span></p>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm italic">"Terima kasih atas dedikasi kerja Anda hari ini!"</p>
            @endif
        </div>
    </div>

    <!-- Personal History Section -->
    <div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
        <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 mb-4">Riwayat Kehadiran Bulan Ini</h3>

        <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Jam Masuk</th>
                        <th class="p-4">Jam Keluar</th>
                        <th class="p-4">Jam Lembur</th>
                        <th class="p-4">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-300">
                    @forelse($history as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                            <td class="p-4 font-semibold text-slate-800 dark:text-slate-100">
                                {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d F Y') }}
                            </td>
                            <td class="p-4">
                                @if($item->status == 'Hadir')
                                    <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-md text-xs font-bold">
                                        {{ $item->status }}
                                    </span>
                                @elseif(in_array($item->status, ['Sakit', 'Izin']))
                                    <span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 rounded-md text-xs font-bold">
                                        {{ $item->status }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-md text-xs font-bold">
                                        {{ $item->status }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 font-mono text-slate-500 dark:text-slate-400">
                                {{ $item->clock_in ? \Carbon\Carbon::parse($item->clock_in)->format('H:i') : '-' }}
                            </td>
                            <td class="p-4 font-mono text-slate-500 dark:text-slate-400">
                                {{ $item->clock_out ? \Carbon\Carbon::parse($item->clock_out)->format('H:i') : '-' }}
                            </td>
                            <td class="p-4 font-semibold text-slate-500 dark:text-slate-400">
                                {{ $item->overtime_hours > 0 ? number_format($item->overtime_hours, 1) . ' Jam' : '-' }}
                            </td>
                            <td class="p-4 text-slate-400 italic">
                                {{ $item->notes ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400">
                                Belum ada riwayat absensi untuk bulan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $history->links() }}
        </div>
    </div>
</div>
@endsection
