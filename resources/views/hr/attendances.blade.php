@extends('layouts.app')

@section('title', 'Absensi & Cuti — Tanos ERP')

@section('content')
    <!-- Header Section -->
    <x-page-header 
        title="Absensi & Cuti Pegawai" 
        subtitle="Pencatatan dan pemantauan kehadiran pegawai harian."
        :breadcrumbs="[
            'General' => '#',
            'Human Resource' => '#',
            'Absensi & Cuti' => ''
        ]"
    >
        <x-slot:action>
            <a href="{{ route('attendances.create') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 cursor-pointer border-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Catat Kehadiran</span>
            </a>
        </x-slot:action>
    </x-page-header>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter and Search Bar Row -->
    <div class="mb-6 bg-slate-50 dark:bg-slate-800/25 p-4 rounded-xl border border-slate-100 dark:border-slate-800">
        <form method="GET" action="{{ route('attendances.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Tanggal Absensi</label>
                <input type="date" name="date" value="{{ $date }}" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 rounded-lg text-xs focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Regional</label>
                <select name="regional" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 rounded-lg text-xs focus:outline-none focus:border-blue-500 cursor-pointer">
                    <option value="All" {{ $selectedRegional == 'All' ? 'selected' : '' }}>Semua Regional</option>
                    @foreach($regionals as $reg)
                        @php $rName = is_object($reg) ? ($reg->name ?? '') : $reg; @endphp
                        <option value="{{ $rName }}" {{ $selectedRegional == $rName ? 'selected' : '' }}>{{ $rName }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Segment Bisnis</label>
                <select name="segment" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 rounded-lg text-xs focus:outline-none focus:border-blue-500 cursor-pointer">
                    <option value="All" {{ $selectedSegment == 'All' ? 'selected' : '' }}>Semua Segment</option>
                    @foreach($segments as $seg)
                        @php $sName = is_object($seg) ? ($seg->name ?? '') : $seg; @endphp
                        <option value="{{ $sName }}" {{ $selectedSegment == $sName ? 'selected' : '' }}>{{ $sName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-xs font-semibold shadow-sm transition cursor-pointer">
                    Filter
                </button>
                <a href="{{ route('attendances.index') }}" class="flex-1 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 py-2 rounded-lg text-xs font-semibold text-center transition cursor-pointer">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Search Controls Bar -->
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-sm font-semibold text-slate-750 dark:text-slate-200">Daftar Kehadiran</h2>
        
        <form method="GET" action="{{ route('attendances.index') }}" class="relative w-full max-w-xs">
            <input type="hidden" name="employee_id" id="edit_employee_id" value="...">
            <input type="hidden" name="date" value="{{ $date }}">
            <input type="hidden" name="regional" value="{{ $selectedRegional }}">
            <input type="hidden" name="segment" value="{{ $selectedSegment }}">

            <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama pegawai..." class="w-full pl-3 pr-8 py-1.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-850 dark:text-slate-200 rounded-lg text-xs focus:outline-none focus:border-blue-500">
            <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 dark:text-slate-500 hover:text-slate-650 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.604 10.604Z" />
                </svg>
            </button>
        </form>
    </div>

    <!-- Data Table Section -->
    <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                    <th class="p-4">Nama Pegawai</th>
                    <th class="p-4">Jabatan & Sub-Area</th>
                    <th class="p-4">Status Kehadiran</th>
                    <th class="p-4">Jam Masuk</th>
                    <th class="p-4">Jam Keluar</th>
                    <th class="p-4">Lembur</th>
                    <th class="p-4">Catatan</th>
                    <th class="p-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-300">
                @forelse($employees as $emp)
                    @php
                        $attendance = $attendances->get($emp->id);
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                        <td class="p-4 font-semibold text-slate-800 dark:text-slate-100">{{ $emp->name }}</td>
                        <td class="p-4 text-xs">
                            <span class="block font-medium text-slate-700 dark:text-slate-300">{{ $emp->role }}</span>
                            <span class="block text-slate-400 mt-0.5">{{ $emp->regional }} @if($emp->sub_regional) • {{ $emp->sub_regional }} @endif</span>
                        </td>
                        <td class="p-4">
                            @if($attendance)
                                @if($attendance->status == 'Hadir')
                                    <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400 rounded-md text-xs font-medium border border-emerald-100/50 dark:border-emerald-900/30">
                                        Hadir
                                    </span>
                                @elseif(in_array($attendance->status, ['Sakit', 'Izin']))
                                    <span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 rounded-md text-xs font-medium border border-amber-100/50 dark:border-amber-900/30">
                                        {{ $attendance->status }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-rose-50 dark:bg-rose-950/30 text-rose-700 dark:text-rose-400 rounded-md text-xs font-medium border border-rose-100/50 dark:border-rose-900/30">
                                        Alfa
                                    </span>
                                @endif
                            @else
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800/55 text-slate-400 dark:text-slate-500 rounded-md text-xs font-medium border border-slate-200 dark:border-slate-800">
                                    Belum Presensi
                                </span>
                            @endif
                        </td>
                        <td class="p-4 text-slate-700 dark:text-slate-300 font-mono">{{ $attendance && $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '-' }}</td>
                        <td class="p-4 text-slate-550 dark:text-slate-400 font-mono">{{ $attendance && $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '-' }}</td>
                        <td class="p-4 text-slate-550 dark:text-slate-400 font-medium">{{ $attendance && $attendance->overtime_hours > 0 ? number_format($attendance->overtime_hours, 1) . ' Jam' : '-' }}</td>
                        <td class="p-4 text-slate-400 dark:text-slate-500 italic max-w-xs truncate" title="{{ $attendance->notes ?? '' }}">{{ $attendance->notes ?? '-' }}</td>
                        <td class="p-4 flex items-center justify-center space-x-2">
                            <!-- Button Quick Log / Edit -->
                            <button onclick="openEditModal({{ json_encode($emp) }}, {{ json_encode($attendance) }})" class="p-1.5 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 bg-blue-50 dark:bg-blue-950/30 rounded-lg transition cursor-pointer" title="Log/Edit Absensi">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                </svg>
                              </button>
                              
                              <!-- Delete log button if exists -->
                              @if($attendance)
                                  <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST" onsubmit="return confirm('Hapus catatan kehadiran pegawai ini?')" class="inline">
                                      @csrf @method('DELETE')
                                      <button type="submit" class="p-1.5 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 bg-red-50 dark:bg-red-950/30 rounded-lg transition cursor-pointer" title="Hapus Log">
                                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.244 2.244 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                          </svg>
                                      </button>
                                  </form>
                              @endif
                          </td>
                      </tr>
                  @empty
                      <tr>
                          <td colspan="8" class="p-12 text-center text-slate-500">
                              Tidak ada data pegawai yang terdaftar pada Regional / Segment terpilih.
                          </td>
                      </tr>
                  @endforelse
              </tbody>
          </table>
      </div>

      <!-- Footer pagination info -->
      <div class="flex items-center justify-between flex-wrap gap-4 mt-6 pt-6 border-t border-slate-100 dark:border-slate-800">
          <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">
              Menampilkan {{ $employees->firstItem() ?? 0 }} - {{ $employees->lastItem() ?? 0 }} dari {{ $employees->total() }} data
          </span>
          <div>
              {{ $employees->links() }}
          </div>
      </div>
  </div>
  </div>

  <!-- ================= MODAL LOG KEHADIRAN ================= -->
  <div id="modal-log-attendance" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-xl max-w-md w-full p-6 shadow-xl border border-slate-100 dark:border-slate-800">
      <div class="flex items-center justify-between mb-4">
          <h3 id="modal-title" class="text-sm font-bold text-slate-800 dark:text-slate-100">Catat Kehadiran</h3>
          <button onclick="closeModal()" class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 cursor-pointer">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
          </button>
      </div>
      <form action="{{ route('attendances.store') }}" method="POST" class="space-y-4">
          @csrf

            <!-- hidden input -->
          <input type="hidden" name="employee_id" id="hidden-employee-id">
          
          <!-- Employee selection -->
          <div>
              <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Pegawai</label>
              <select name="employee_id" id="input-employee-id" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 cursor-pointer">
                  <option value="">-- Pilih Pegawai --</option>
                  @foreach($employees as $emp)
                      <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->role }})</option>
                  @endforeach
              </select>
          </div>

          <!-- Date -->
          <div>
              <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Tanggal</label>
              <input type="date" name="date" id="input-date" value="{{ $date }}" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
          </div>

          <!-- Status selection -->
          <div>
              <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Status Kehadiran</label>
              <select name="status" id="input-status" required class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500 cursor-pointer">
                  <option value="Hadir">Hadir</option>
                  <option value="Sakit">Sakit</option>
                  <option value="Izin">Izin</option>
                  <option value="Alfa">Alfa</option>
              </select>
          </div>

          <!-- Time Inputs (Clock In & Out) -->
          <div class="grid grid-cols-2 gap-4" id="time-inputs-wrapper">
              <div>
                  <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Jam Masuk</label>
                  <input type="time" name="clock_in" id="input-clock-in" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
              </div>
              <div>
                  <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Jam Keluar</label>
                  <input type="time" name="clock_out" id="input-clock-out" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
              </div>
          </div>

          <!-- Overtime hours -->
          <div>
              <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Jam Lembur <span class="text-slate-400 lowercase font-normal">(opsional)</span></label>
              <input type="number" step="0.5" name="overtime_hours" id="input-overtime-hours" min="0" max="24" placeholder="0.0" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500">
          </div>

          <!-- Notes -->
          <div>
              <label class="block text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Catatan</label>
              <textarea name="notes" id="input-notes" rows="2" placeholder="Catatan opsional (misal: alasan izin, tugas lembur)" class="w-full px-3 py-2 border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-blue-500"></textarea>
          </div>

          <div class="flex justify-end space-x-2 pt-2">
              <button type="button" onclick="closeModal()" class="px-4 py-2 border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl text-xs font-semibold cursor-pointer">Batal</button>
              <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition cursor-pointer">Simpan</button>
          </div>
      </form>
  </div>
</div>

<script>
// Toggle clock input fields based on status
const statusSelect = document.getElementById('input-status');
const timeInputsWrapper = document.getElementById('time-inputs-wrapper');
const clockInInput = document.getElementById('input-clock-in');
const clockOutInput = document.getElementById('input-clock-out');

statusSelect.addEventListener('change', function() {
    if (this.value !== 'Hadir') {
        clockInInput.value = '';
        clockOutInput.value = '';
        timeInputsWrapper.style.opacity = '0.5';
        clockInInput.disabled = true;
        clockOutInput.disabled = true;
    } else {
        timeInputsWrapper.style.opacity = '1';
        clockInInput.disabled = false;
        clockOutInput.disabled = false;
    }
});

function openCreateModal() {
    document.getElementById('modal-title').textContent = 'Catat Kehadiran';
    document.getElementById('input-employee-id').value = '';
    document.getElementById('hidden-employee-id').value = '';
    document.getElementById('input-employee-id').disabled = false;
    document.getElementById('input-status').value = 'Hadir';
    document.getElementById('input-clock-in').value = '08:00';
    document.getElementById('input-clock-out').value = '17:00';
    document.getElementById('input-overtime-hours').value = '0';
    document.getElementById('input-notes').value = '';
    
    statusSelect.dispatchEvent(new Event('change'));
    document.getElementById('modal-log-attendance').classList.remove('hidden');
}

function openEditModal(employee, attendance) {
    document.getElementById('modal-title').textContent = 'Ubah Catatan Kehadiran';
    
    // hidden input agar tidak auto disable
    document.getElementById('input-employee-id').value = employee.id;
    document.getElementById('hidden-employee-id').value = employee.id; 
    
    document.getElementById('input-employee-id').disabled = true;
    
    // If an attendance record exists, fill the details
    if (attendance) {
        document.getElementById('input-status').value = attendance.status;
        document.getElementById('input-clock-in').value = attendance.clock_in ? attendance.clock_in.substring(0, 5) : '';
        document.getElementById('input-clock-out').value = attendance.clock_out ? attendance.clock_out.substring(0, 5) : '';
        document.getElementById('input-overtime-hours').value = attendance.overtime_hours;
        document.getElementById('input-notes').value = attendance.notes || '';
    } else {
        // Default setup for logging new attendance for this specific employee
        document.getElementById('input-status').value = 'Hadir';
        document.getElementById('input-clock-in').value = '08:00';
        document.getElementById('input-clock-out').value = '17:00';
        document.getElementById('input-overtime-hours').value = '0';
        document.getElementById('input-notes').value = '';
    }
    
    statusSelect.dispatchEvent(new Event('change'));
    document.getElementById('modal-log-attendance').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modal-log-attendance').classList.add('hidden');
}
</script>
@endsection

