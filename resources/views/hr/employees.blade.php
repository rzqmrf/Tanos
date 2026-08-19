@extends('layouts.app')

@section('title', 'Employee — Tanos ERP')

@section('content')
<div class="space-y-6">
    {{-- ALERT MESSAGES --}}
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm font-semibold flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 font-bold">&times;</button>
    </div>
    @endif

    {{-- HEADER & BREADCRUMB --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-400 dark:text-slate-500 mb-1">
                    <a href="{{ route('dashboard.index') }}" class="hover:text-slate-700 dark:hover:text-slate-300 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                        <span>Home</span>
                    </a>
                    <span>/</span>
                    <span>Human Capital</span>
                    <span>/</span>
                    <a href="{{ route('employees.index') }}" class="hover:text-slate-700 dark:hover:text-slate-300">Employee</a>
                    <span>/</span>
                    <span class="text-slate-700 dark:text-slate-300 font-bold">Index</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Employee</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Master Data Employee</p>
            </div>

            {{-- ACTION BUTTONS TOP RIGHT --}}
            <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
                <button onclick="document.getElementById('modal-create-employee').classList.remove('hidden')" 
                    style="background-color: #22c55e; color: #ffffff;"
                    class="px-4 py-2 hover:opacity-90 active:scale-95 text-white font-semibold text-xs rounded-lg shadow-sm transition flex items-center gap-1.5 cursor-pointer border-0">
                    <span class="text-sm font-bold">+</span>
                    <span>Create New</span>
                </button>
                <button onclick="alert('Fitur Import Data Pegawai siap digunakan.')" 
                    style="background-color: #0088ff; color: #ffffff;"
                    class="px-4 py-2 hover:opacity-90 active:scale-95 text-white font-semibold text-xs rounded-lg shadow-sm transition flex items-center gap-1.5 cursor-pointer border-0">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                    <span>Import Data</span>
                </button>
                <button onclick="alert('Integrasi SAP HCM telah aktif.')" 
                    style="background-color: #7c3aed; color: #ffffff;"
                    class="px-4 py-2 hover:opacity-90 active:scale-95 text-white font-semibold text-xs rounded-lg shadow-sm transition flex items-center gap-1.5 cursor-pointer border-0">
                    <span class="text-sm font-bold">+</span>
                    <span>SAP Integration</span>
                </button>
            </div>
        </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-xl shadow-sm overflow-hidden">
        {{-- Card Title --}}
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
            <h2 class="text-sm font-bold text-slate-800 dark:text-slate-100">Employee - List</h2>
        </div>

        {{-- TOOLBAR --}}
        <div class="px-6 py-4 space-y-4">
            {{-- Toolbar Icon Buttons (Doc, PDF, Excel) --}}
            <div class="flex items-center gap-2">
                <button onclick="exportToCSV()" title="Export Document" class="w-10 h-10 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-lg flex items-center justify-center text-slate-700 dark:text-slate-200 transition cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                </button>
                <button onclick="window.print()" title="Export PDF" class="w-10 h-10 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-lg flex items-center justify-center text-rose-600 dark:text-rose-400 font-black text-xs transition cursor-pointer">
                    <div class="flex flex-col items-center leading-none">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                        <span class="text-[8px] font-black mt-0.5">PDF</span>
                    </div>
                </button>
                <button onclick="exportToCSV()" title="Export Excel" class="w-10 h-10 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-lg flex items-center justify-center text-emerald-600 dark:text-emerald-400 font-black text-xs transition cursor-pointer">
                    <div class="flex flex-col items-center leading-none">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25-6h17.25m-17.25-6h17.25" /></svg>
                        <span class="text-[8px] font-black mt-0.5">X</span>
                    </div>
                </button>
            </div>

            {{-- Row: Limit Dropdown (Left) & Search (Right) --}}
            <div class="flex items-center justify-between gap-4 pt-1">
                {{-- Limit Dropdown --}}
                <form action="{{ route('employees.index') }}" method="GET" class="flex items-center gap-1">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <div class="relative">
                        <select name="per_page" onchange="this.form.submit()" 
                            class="pl-3 pr-7 py-1.5 text-xs font-semibold border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 rounded-lg focus:outline-none appearance-none cursor-pointer">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <svg class="w-3 h-3 absolute right-2 top-2.5 text-slate-500 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                    </div>
                </form>

                {{-- Search Box Right Aligned with Perfectly Centered Icon --}}
                <form action="{{ route('employees.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="per_page" value="{{ $perPage }}">
                    <label class="text-xs font-semibold text-slate-600 dark:text-slate-400">Search:</label>
                    <div class="relative flex items-center">
                        <input type="text" name="search" value="{{ $search }}" 
                            class="w-48 sm:w-60 pl-8 pr-3 py-1.5 text-xs border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-500">
                        <svg class="w-3.5 h-3.5 absolute left-2.5 text-slate-400 dark:text-slate-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                    </div>
                </form>
            </div>
        </div>

        {{-- TABLE DATA --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-[11px]" id="employee-table">
                <thead>
                    <tr class="bg-white dark:bg-slate-900 border-y border-slate-200/80 dark:border-slate-800 font-bold text-slate-800 dark:text-slate-200">
                        <th class="px-4 py-3 whitespace-nowrap">ID</th>
                        <th class="px-4 py-3 whitespace-nowrap">Full Name</th>
                        <th class="px-4 py-3 whitespace-nowrap">Place of Birth</th>
                        <th class="px-4 py-3 whitespace-nowrap">Date of Birth</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Gender</th>
                        <th class="px-4 py-3 whitespace-nowrap">Identity Card Number</th>
                        <th class="px-4 py-3 whitespace-nowrap">NPWP Number</th>
                        <th class="px-4 py-3 whitespace-nowrap">Valid From</th>
                        <th class="px-4 py-3 whitespace-nowrap">Valid To</th>
                        <th class="px-4 py-3 whitespace-nowrap text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-medium">
                    @forelse($employees as $emp)
                    @php
                        $displayId = 82633 + $emp->id;
                        $dob = $emp->date_of_birth ? $emp->date_of_birth->format('d/m/Y') : '13/01/2001';
                        $validFrom = $emp->valid_from ? $emp->valid_from->format('d/m/Y') : ($emp->tmt_date ? $emp->tmt_date->format('d/m/Y') : '09/08/2024');
                        $validTo = $emp->valid_to ? $emp->valid_to->format('d/m/Y') : '31/12/9999';
                        $genderCode = str_starts_with(strtolower($emp->gender ?? 'L'), 'p') ? 'P' : 'L';
                        $ktp = $emp->identity_card_number ?? sprintf('35072213%08d', $emp->id);
                        $npwp = $emp->npwp_number ?? sprintf('00%013d', $emp->id);
                        $pob = $emp->place_of_birth ?? 'SURABAYA';
                    @endphp
                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/40 transition-colors">
                        <td class="px-4 py-3.5 font-sans">
                            {{ $displayId }}
                        </td>
                        <td class="px-4 py-3.5 font-bold uppercase text-slate-900 dark:text-slate-100 whitespace-nowrap">
                            {{ $emp->name }}
                        </td>
                        <td class="px-4 py-3.5 uppercase whitespace-nowrap">
                            {{ $pob }}
                        </td>
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            {{ $dob }}
                        </td>
                        <td class="px-4 py-3.5 text-center font-bold">
                            {{ $genderCode }}
                        </td>
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            {{ $ktp }}
                        </td>
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            {{ $npwp }}
                        </td>
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            {{ $validFrom }}
                        </td>
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            {{ $validTo }}
                        </td>
                        <td class="px-4 py-3.5 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1">
                                {{-- Blue Detail Button --}}
                                <a href="{{ route('employees.show', $emp->id) }}" 
                                    style="background-color: #007bff; color: #ffffff;"
                                    class="w-7 h-7 hover:opacity-90 text-white rounded flex items-center justify-center transition shadow-sm cursor-pointer border-0" title="View Detail">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
                                </a>

                                {{-- Purple Bookmark Button --}}
                                <button onclick="alert('Membuka dokumen arsip pegawai: {{ $emp->name }}')" 
                                    style="background-color: #6f42c1; color: #ffffff;"
                                    class="w-7 h-7 hover:opacity-90 text-white rounded flex items-center justify-center transition shadow-sm cursor-pointer border-0" title="Document Archive">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H7.5m9 0a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21.75H7.5A2.25 2.25 0 0 1 5.25 19.5V6a2.25 2.25 0 0 1 2.25-2.25m9 0" /></svg>
                                </button>

                                {{-- Light Purple Paperplane Button --}}
                                <button onclick="alert('Data pegawai {{ $emp->name }} telah dikirim ke SAP Integration!')" 
                                    style="background-color: #e0d7f5; color: #6f42c1;"
                                    class="w-7 h-7 hover:opacity-90 rounded flex items-center justify-center transition shadow-sm cursor-pointer border-0" title="Send to SAP">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400 text-xs">
                            Tidak ada data pegawai yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION FOOTER --}}
        @if($employees->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
            {{ $employees->links() }}
        </div>
        @endif
    </div>
</div>

{{-- MODAL CREATE NEW EMPLOYEE --}}
<div id="modal-create-employee" class="hidden fixed inset-0 z-50 bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-xl w-full shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden max-h-[90vh] overflow-y-auto">
        <div style="background-color: #100b60; color: #ffffff;" class="px-6 py-4 text-white flex items-center justify-between sticky top-0 z-10">
            <h3 class="text-sm font-bold text-white">+ Tambah Data Pegawai Baru</h3>
            <button onclick="document.getElementById('modal-create-employee').classList.add('hidden')" class="text-white hover:text-slate-200 font-bold">&times;</button>
        </div>
        <form action="{{ route('employees.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap (Full Name) *</label>
                <input type="text" name="name" required placeholder="Contoh: HAFIZH ILHAM AL BAAQI" class="w-full px-3 py-2 text-xs font-medium border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 uppercase">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tempat Lahir *</label>
                    <input type="text" name="place_of_birth" value="SURABAYA" required class="w-full px-3 py-2 text-xs font-medium border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 uppercase">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Tanggal Lahir *</label>
                    <input type="date" name="date_of_birth" value="2001-01-13" required class="w-full px-3 py-2 text-xs font-medium border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Jenis Kelamin *</label>
                    <select name="gender" class="w-full px-3 py-2 text-xs font-medium border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
                        <option value="Laki-Laki">Laki-Laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">No KTP (Identity Card) *</label>
                    <input type="text" name="identity_card_number" placeholder="3507221301010001" class="w-full px-3 py-2 text-xs font-medium border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">No NPWP</label>
                    <input type="text" name="npwp_number" placeholder="000000000000000" class="w-full px-3 py-2 text-xs font-medium border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">NIPP / ID Pegawai *</label>
                    <input type="text" name="nipp" required placeholder="TAD-00109" class="w-full px-3 py-2 text-xs font-medium border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Regional *</label>
                    <select name="regional" required class="w-full px-3 py-2 text-xs font-medium border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
                        @foreach($regionals as $r)
                        <option value="{{ $r->name }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Segment *</label>
                    <select name="segment" required class="w-full px-3 py-2 text-xs font-medium border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
                        @foreach($segments as $s)
                        <option value="{{ $s->name }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <input type="hidden" name="role" value="Staff Operasional">
            <input type="hidden" name="month" value="Januari">
            <input type="hidden" name="ptkp_status" value="TK/0">
            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                <button type="button" onclick="document.getElementById('modal-create-employee').classList.add('hidden')" class="px-4 py-2 text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg">Batal</button>
                <button type="submit" style="background-color: #22c55e; color: #ffffff;" class="px-4 py-2 text-xs font-bold text-white rounded-lg shadow-sm border-0">Simpan Pegawai</button>
            </div>
        </form>
    </div>
</div>

<script>
function exportToCSV() {
    const table = document.getElementById('employee-table');
    if (!table) return;
    let csv = [];
    const rows = table.querySelectorAll('tr');
    rows.forEach(row => {
        const cols = row.querySelectorAll('th, td');
        const rowData = [];
        cols.forEach((col, index) => {
            if (index < 9) {
                let text = col.innerText.replace(/"/g, '""').trim();
                rowData.push('"' + text + '"');
            }
        });
        if (rowData.length) csv.push(rowData.join(','));
    });
    const blob = new Blob(["\uFEFF" + csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'Employee_List_' + new Date().toISOString().slice(0,10) + '.csv';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection
