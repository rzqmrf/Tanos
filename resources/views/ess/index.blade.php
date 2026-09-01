@extends('layouts.app')

@section('title', 'Employee Self Service (ESS) — Tanos ERP')

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'leaves', leaveModal: false, cicoModal: false }">

    {{-- HEADER BLOCK --}}
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center space-x-3">
            <div class="p-2.5 bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 rounded-xl border border-blue-100/60 dark:border-blue-800/30">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Employee Self Service (ESS)</h1>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Layanan mandiri karyawan untuk pengajuan cuti, izin, dan koreksi jam absensi.</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button @click="leaveModal = true" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition shadow-sm cursor-pointer flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Ajukan Cuti / Izin
            </button>
            <button @click="cicoModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl text-xs font-bold transition shadow-sm cursor-pointer flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Koreksi Jam Absen (CICO)
            </button>
        </div>
    </div>

    {{-- NOTIFICATIONS --}}
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

    {{-- EMPLOYEE CARD SUMMARY --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-xl p-5 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Profil Pegawai Terhubung</span>
            <span class="text-base font-bold text-slate-800 dark:text-slate-200 mt-1 block">{{ $employee->name }}</span>
            <span class="text-xs text-slate-400 dark:text-slate-500 block mt-0.5">{{ $employee->role }} · {{ $employee->regional }} · Segment {{ $employee->segment }}</span>
        </div>
        <div class="px-4 py-2.5 bg-slate-50 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800 rounded-xl text-right">
            <span class="text-[10px] font-semibold text-slate-400 block">Status Akun ERP</span>
            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 mt-0.5 inline-flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Master TANOS Terintegrasi
            </span>
        </div>
    </div>

    {{-- TABS NAVIGATION --}}
    <div class="flex border-b border-slate-200 dark:border-slate-800 gap-6">
        <button @click="activeTab = 'leaves'"
                :class="activeTab === 'leaves' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 font-bold' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 font-medium'"
                class="pb-3 border-b-2 text-xs uppercase tracking-wider transition-all cursor-pointer outline-none">
            Riwayat Cuti & Izin
        </button>
        <button @click="activeTab = 'cico'"
                :class="activeTab === 'cico' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 font-bold' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 font-medium'"
                class="pb-3 border-b-2 text-xs uppercase tracking-wider transition-all cursor-pointer outline-none">
            Riwayat Koreksi Absensi (CICO)
        </button>
    </div>

    {{-- TAB CONTENTS --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-xl shadow-sm overflow-hidden">
        
        {{-- LEAVE REQUEST TAB --}}
        <div x-show="activeTab === 'leaves'">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80">
                            <th class="p-4">Tipe</th>
                            <th class="p-4">Tanggal Mulai</th>
                            <th class="p-4">Tanggal Selesai</th>
                            <th class="p-4">Jumlah Hari</th>
                            <th class="p-4">Alasan</th>
                            <th class="p-4">Lampiran</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Disetujui Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-600 dark:text-slate-300">
                        @forelse($leaveRequests as $req)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                <td class="p-4 font-bold text-slate-800 dark:text-slate-100">{{ $req->type }}</td>
                                <td class="p-4">{{ $req->start_date->format('d M Y') }}</td>
                                <td class="p-4">{{ $req->end_date->format('d M Y') }}</td>
                                <td class="p-4 font-semibold">{{ $req->total_days }} Hari</td>
                                <td class="p-4 max-w-[200px] truncate" title="{{ $req->reason }}">{{ $req->reason }}</td>
                                <td class="p-4">
                                    @if($req->attachment)
                                        <a href="{{ asset('storage/' . $req->attachment) }}" target="_blank" class="text-indigo-600 hover:underline font-semibold flex items-center gap-1">
                                            <span>Lihat Berkas</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider
                                        {{ $req->status === 'Approved' ? 'bg-emerald-55/80 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400' : '' }}
                                        {{ $req->status === 'Rejected' ? 'bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-400' : '' }}
                                        {{ $req->status === 'Submitted' ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400' : '' }}
                                    ">
                                        {{ $req->status }}
                                    </span>
                                </td>
                                <td class="p-4 text-slate-400">
                                    {{ $req->approver ? $req->approver->name : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400">Belum ada riwayat pengajuan cuti atau izin.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- CICO CORRECTION TAB --}}
        <div x-show="activeTab === 'cico'" style="display: none;">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-800/80">
                            <th class="p-4">Tanggal Absensi</th>
                            <th class="p-4">Proposed Clock In</th>
                            <th class="p-4">Proposed Clock Out</th>
                            <th class="p-4">Alasan Koreksi</th>
                            <th class="p-4">Lampiran</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Disetujui Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-600 dark:text-slate-300">
                        @forelse($cicoCorrections as $cor)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                <td class="p-4 font-bold text-slate-800 dark:text-slate-100">{{ $cor->date->format('d M Y') }}</td>
                                <td class="p-4 font-semibold text-indigo-600 dark:text-indigo-400">{{ Carbon\Carbon::parse($cor->clock_in)->format('H:i') }}</td>
                                <td class="p-4 font-semibold text-blue-600 dark:text-blue-400">{{ Carbon\Carbon::parse($cor->clock_out)->format('H:i') }}</td>
                                <td class="p-4 max-w-[200px] truncate" title="{{ $cor->reason }}">{{ $cor->reason }}</td>
                                <td class="p-4">
                                    @if($cor->attachment)
                                        <a href="{{ asset('storage/' . $cor->attachment) }}" target="_blank" class="text-indigo-600 hover:underline font-semibold">
                                            Lihat Berkas
                                        </a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider
                                        {{ $cor->status === 'Approved' ? 'bg-emerald-55/80 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400' : '' }}
                                        {{ $cor->status === 'Rejected' ? 'bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-400' : '' }}
                                        {{ $cor->status === 'Submitted' ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-400' : '' }}
                                    ">
                                        {{ $cor->status }}
                                    </span>
                                </td>
                                <td class="p-4 text-slate-400">
                                    {{ $cor->approver ? $cor->approver->name : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400">Belum ada riwayat pengajuan koreksi CICO.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- MODAL: SUBMIT LEAVE --}}
    <div x-show="leaveModal" 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-md" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl w-full max-w-xl p-6 md:p-8 shadow-2xl relative" 
             @click.away="leaveModal = false"
             x-data="{ 
                 selectedType: 'Cuti Tahunan',
                 startDate: '',
                 endDate: '',
                 fileName: '',
                 get totalDays() {
                     if (!this.startDate || !this.endDate) return 0;
                     const start = new Date(this.startDate);
                     const end = new Date(this.endDate);
                     if (end < start) return 0;
                     const diffTime = Math.abs(end - start);
                     return Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                 }
             }">
            
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-100 dark:border-indigo-800/40">
                        🏖️
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-850 dark:text-slate-100">Pengajuan Cuti & Izin</h3>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium">Lengkapi detail permohonan ketidakhadiran Anda.</p>
                    </div>
                </div>
                <button @click="leaveModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('ess.leave.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                
                {{-- Interactive Leave Type Cards Selector --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2">Pilih Tipe Cuti / Izin</label>
                    <input type="hidden" name="type" :value="selectedType">
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                        <button type="button" @click="selectedType = 'Cuti Tahunan'"
                                :class="selectedType === 'Cuti Tahunan' ? 'border-indigo-600 bg-indigo-50/40 dark:bg-indigo-950/20 text-indigo-700 dark:text-indigo-400 font-bold ring-2 ring-indigo-500/10' : 'border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-50/50 dark:hover:bg-slate-800/30'"
                                class="text-left p-3 border rounded-xl transition cursor-pointer text-xs flex flex-col justify-between min-h-[75px]">
                            <span class="text-lg">🏖️</span>
                            <span>Cuti Tahunan</span>
                        </button>
                        <button type="button" @click="selectedType = 'Sakit'"
                                :class="selectedType === 'Sakit' ? 'border-rose-600 bg-rose-50/40 dark:bg-rose-950/20 text-rose-700 dark:text-rose-400 font-bold ring-2 ring-rose-500/10' : 'border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-50/50 dark:hover:bg-slate-800/30'"
                                class="text-left p-3 border rounded-xl transition cursor-pointer text-xs flex flex-col justify-between min-h-[75px]">
                            <span class="text-lg">🤒</span>
                            <span>Sakit</span>
                        </button>
                        <button type="button" @click="selectedType = 'Cuti Melahirkan'"
                                :class="selectedType === 'Cuti Melahirkan' ? 'border-amber-600 bg-amber-50/40 dark:bg-amber-950/20 text-amber-700 dark:text-amber-400 font-bold ring-2 ring-amber-500/10' : 'border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-50/50 dark:hover:bg-slate-800/30'"
                                class="text-left p-3 border rounded-xl transition cursor-pointer text-xs flex flex-col justify-between min-h-[75px]">
                            <span class="text-lg">👶</span>
                            <span>Cuti Melahirkan</span>
                        </button>
                        <button type="button" @click="selectedType = 'Izin Alasan Penting'"
                                :class="selectedType === 'Izin Alasan Penting' ? 'border-orange-600 bg-orange-50/40 dark:bg-orange-950/20 text-orange-700 dark:text-orange-400 font-bold ring-2 ring-orange-500/10' : 'border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-50/50 dark:hover:bg-slate-800/30'"
                                class="text-left p-3 border rounded-xl transition cursor-pointer text-xs flex flex-col justify-between min-h-[75px]">
                            <span class="text-lg">⚠️</span>
                            <span>Izin Alasan Penting</span>
                        </button>
                        <button type="button" @click="selectedType = 'Cuti Ibadah Keagamaan'"
                                :class="selectedType === 'Cuti Ibadah Keagamaan' ? 'border-teal-600 bg-teal-50/40 dark:bg-teal-950/20 text-teal-700 dark:text-teal-400 font-bold ring-2 ring-teal-500/10' : 'border-slate-200 dark:border-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-50/50 dark:hover:bg-slate-800/30'"
                                class="text-left p-3 border rounded-xl transition cursor-pointer text-xs flex flex-col justify-between min-h-[75px]">
                            <span class="text-lg">🕌</span>
                            <span>Ibadah Keagamaan</span>
                        </button>
                    </div>
                </div>
                
                {{-- Date Pickers with Real-time Count Badge --}}
                <div class="bg-slate-50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/80 rounded-xl p-4 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 mb-1.5">Tanggal Mulai</label>
                            <input type="date" name="start_date" x-model="startDate" required 
                                   class="w-full text-xs px-3.5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 mb-1.5">Tanggal Selesai</label>
                            <input type="date" name="end_date" x-model="endDate" required 
                                   class="w-full text-xs px-3.5 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                        </div>
                    </div>
                    
                    {{-- Real-time count feedback badge --}}
                    <div x-show="totalDays > 0" 
                         x-transition 
                         class="flex items-center justify-between bg-indigo-50/60 dark:bg-indigo-950/20 border border-indigo-100/60 dark:border-indigo-900/30 px-3.5 py-2 rounded-xl">
                        <span class="text-[10px] font-bold text-indigo-700 dark:text-indigo-400">Total Durasi Pengajuan:</span>
                        <span class="text-xs font-black text-indigo-800 dark:text-indigo-300" x-text="totalDays + ' Hari Kerja'"></span>
                    </div>
                </div>

                {{-- Reason field --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Alasan Pengajuan</label>
                    <textarea name="reason" required rows="3" placeholder="Tuliskan keterangan detail alasan pengajuan..." 
                              class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200"></textarea>
                </div>

                {{-- Custom File Upload Input --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Unggah Berkas Pendukung (Opsional)</label>
                    <div class="relative border-2 border-dashed border-slate-200 dark:border-slate-800 hover:border-indigo-500 dark:hover:border-indigo-800 rounded-xl p-4 flex flex-col items-center justify-center transition cursor-pointer text-center bg-slate-50/50 dark:bg-slate-950/10">
                        <input type="file" name="attachment" 
                               @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-slate-400 mb-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                        </svg>
                        <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 block" x-text="fileName || 'Pilih berkas PDF atau gambar (Maks 2MB)'"></span>
                        <span class="text-[9px] text-slate-400 mt-1 block">Seret & taruh berkas di sini untuk unggah</span>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-800/80">
                    <button type="button" @click="leaveModal = false" class="px-4 py-2.5 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-50 cursor-pointer transition">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow transition cursor-pointer">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: SUBMIT CICO CORRECTION --}}
    <div x-show="cicoModal" 
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-md" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl w-full max-w-md p-6 md:p-8 shadow-2xl relative" 
             @click.away="cicoModal = false"
             x-data="{ cicoFileName: '' }">
            
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-blue-800/40">
                        ⏰
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-850 dark:text-slate-100">Koreksi Absensi CICO</h3>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium">Sesuaikan jam masuk/pulang Anda.</p>
                    </div>
                </div>
                <button @click="cicoModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('ess.cico.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Tanggal Absensi Yang Dikoreksi</label>
                    <input type="date" name="date" required 
                           class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Jam Masuk (Clock In)</label>
                        <input type="time" name="clock_in" required 
                               class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1.5">Jam Pulang (Clock Out)</label>
                        <input type="time" name="clock_out" required 
                               class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Alasan Penyesuaian</label>
                    <textarea name="reason" required rows="3" placeholder="Tuliskan alasan penyesuaian (misal: mesin fingerprint error)..." 
                              class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200"></textarea>
                </div>

                {{-- Custom File Upload Zone --}}
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Unggah Foto Bukti/Surat Tugas (Opsional)</label>
                    <div class="relative border-2 border-dashed border-slate-200 dark:border-slate-800 hover:border-indigo-500 dark:hover:border-indigo-800 rounded-xl p-4 flex flex-col items-center justify-center transition cursor-pointer text-center bg-slate-50/50 dark:bg-slate-950/10">
                        <input type="file" name="attachment" 
                               @change="cicoFileName = $event.target.files[0] ? $event.target.files[0].name : ''"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-slate-400 mb-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                        </svg>
                        <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 block" x-text="cicoFileName || 'Pilih berkas PDF atau gambar (Maks 2MB)'"></span>
                        <span class="text-[9px] text-slate-400 mt-1 block">Seret & taruh berkas di sini untuk unggah</span>
                    </div>
                </div>

                {{-- Submit buttons --}}
                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-800/80">
                    <button type="button" @click="cicoModal = false" class="px-4 py-2.5 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-50 cursor-pointer transition">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow transition cursor-pointer">Ajukan Koreksi</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

