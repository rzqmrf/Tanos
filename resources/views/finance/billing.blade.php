@extends('layouts.app')

@section('title', 'PS: Billing Pranota & Nota — Tanos ERP')

@section('content')
<div class="space-y-6" x-data="{ 
    activeTab: 'pranota',
    pranotaTab: 'belum',
    showManualModal: false,
    showDoNotaModal: false,
    selectedPranotas: [],
    selectedProject: '',
    totalSelectedAmount: 0,
    updateSelected(id, amount, project) {
        let index = this.selectedPranotas.indexOf(id);
        if (index > -1) {
            this.selectedPranotas.splice(index, 1);
            this.totalSelectedAmount -= amount;
            if (this.selectedPranotas.length === 0) {
                this.selectedProject = '';
            }
        } else {
            // Must belong to the same project to group into one nota
            if (this.selectedProject && this.selectedProject != project) {
                alert('Semua pranota yang dipilih harus berada pada proyek yang sama!');
                return false;
            }
            this.selectedPranotas.push(id);
            this.selectedProject = project;
            this.totalSelectedAmount += amount;
        }
    }
}">

    <!-- Header Section -->
    <div class="flex items-center justify-between bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 p-6 rounded-2xl shadow-sm">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-19.5 5.25h19.5m-19.5 0h19.5M2.25 12h19.5m-19.5 0h19.5m-19.5 5.25h19.5m-19.5 0h19.5M3 19.5h18a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 21 4.5H3a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 3 19.5Z" />
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">Billing: Pranota & Nota</h1>
                <p class="text-xs text-slate-400 dark:text-slate-500 font-medium">Manajemen pembuatan Pranota Billing proyek hingga posting Invoice AR ke SAP.</p>
            </div>
        </div>
        
        <div class="flex gap-2">
            <button @click="showManualModal = true" class="bg-indigo-650 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition cursor-pointer">
                + Pranota Manual
            </button>
        </div>
    </div>

    <!-- Navigation Tabs (Pranota vs Nota) -->
    <div class="flex border-b border-slate-200 dark:border-slate-800">
        <button @click="activeTab = 'pranota'"
                :class="activeTab === 'pranota' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 font-bold border-b-2' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-350'"
                class="pb-3 px-6 text-xs transition cursor-pointer">
            Pranota Billing
        </button>
        <button @click="activeTab = 'nota'"
                :class="activeTab === 'nota' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400 font-bold border-b-2' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-350'"
                class="pb-3 px-6 text-xs transition cursor-pointer">
            Nota Billing (Invoices)
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-xs font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- TAB 1: PRANOTA BILLING (Halaman 10-11) -->
    <div x-show="activeTab === 'pranota'" class="space-y-6">
        
        <!-- Pranota Sub-tabs (Belum Terbilling, Siap Terbilling, Sudah Terbilling) -->
        <div class="flex gap-2 bg-slate-50 dark:bg-slate-950/40 p-1 rounded-xl w-fit">
            <button @click="pranotaTab = 'belum'"
                    :class="pranotaTab === 'belum' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 font-bold shadow-sm' : 'text-slate-550 dark:text-slate-400'"
                    class="px-4 py-2 rounded-lg text-[10px] uppercase font-bold transition cursor-pointer">
                Belum Terbilling ({{ $belumTerbilling->count() }})
            </button>
            <button @click="pranotaTab = 'siap'"
                    :class="pranotaTab === 'siap' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 font-bold shadow-sm' : 'text-slate-550 dark:text-slate-400'"
                    class="px-4 py-2 rounded-lg text-[10px] uppercase font-bold transition cursor-pointer">
                Siap Terbilling ({{ $siapTerbilling->count() }})
            </button>
            <button @click="pranotaTab = 'sudah'"
                    :class="pranotaTab === 'sudah' ? 'bg-white dark:bg-slate-900 text-indigo-600 dark:text-indigo-400 font-bold shadow-sm' : 'text-slate-550 dark:text-slate-400'"
                    class="px-4 py-2 rounded-lg text-[10px] uppercase font-bold transition cursor-pointer">
                Sudah Terbilling ({{ $sudahTerbilling->count() }})
            </button>
        </div>

        <!-- DO NOTA Action Trigger (Only visible on Siap Terbilling tab) -->
        <div x-show="pranotaTab === 'siap' && selectedPranotas.length > 0" x-transition
             class="flex items-center justify-between p-4 bg-indigo-50/60 dark:bg-indigo-950/20 border border-indigo-100/60 dark:border-indigo-900/30 rounded-2xl shadow-sm">
            <div class="text-xs font-bold text-slate-800 dark:text-slate-200">
                Terpilih <span class="text-indigo-600" x-text="selectedPranotas.length"></span> Pranota | Total: <span class="text-indigo-600" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(totalSelectedAmount)"></span>
            </div>
            <button @click="showDoNotaModal = true" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm cursor-pointer transition">
                Do Nota (Gabung Invoice)
            </button>
        </div>

        <!-- Pranota Tables -->
        <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <th class="p-4 text-center w-12" x-show="pranotaTab === 'siap'">Pilih</th>
                        <th class="p-4">Nomor Pranota</th>
                        <th class="p-4">Project</th>
                        <th class="p-4">Periode Asal</th>
                        <th class="p-4">Tanggal Pengajuan</th>
                        <th class="p-4">Nilai Pranota</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center" x-show="pranotaTab === 'belum'">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-600 dark:text-slate-350">
                    <!-- Tab Belum Terbilling -->
                    <template x-if="pranotaTab === 'belum'">
                        @forelse($belumTerbilling as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                            <td class="p-4 font-bold text-slate-800 dark:text-slate-200">{{ $item->pranota_number }}</td>
                            <td class="p-4 font-medium">{{ $item->project->segment }} - {{ $item->project->regional }}</td>
                            <td class="p-4 text-slate-400">{{ $item->period ? $item->period->name : 'Pranota Manual' }}</td>
                            <td class="p-4">{{ $item->created_at->format('d M Y') }}</td>
                            <td class="p-4 font-bold">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <form action="{{ route('billing.pranota.approve', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold rounded-lg transition cursor-pointer">
                                        Approve (Ready)
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-400">Belum ada pranota dalam proses pengajuan.</td>
                        </tr>
                        @endforelse
                    </template>

                    <!-- Tab Siap Terbilling -->
                    <template x-if="pranotaTab === 'siap'">
                        @forelse($siapTerbilling as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                            <td class="p-4 text-center">
                                <input type="checkbox" :checked="selectedPranotas.includes({{ $item->id }})" 
                                       @change="updateSelected({{ $item->id }}, {{ $item->amount }}, '{{ $item->project_id }}')"
                                       class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                            </td>
                            <td class="p-4 font-bold text-slate-800 dark:text-slate-200">{{ $item->pranota_number }}</td>
                            <td class="p-4 font-medium">{{ $item->project->segment }} - {{ $item->project->regional }}</td>
                            <td class="p-4 text-slate-400">{{ $item->period ? $item->period->name : 'Pranota Manual' }}</td>
                            <td class="p-4">{{ $item->created_at->format('d M Y') }}</td>
                            <td class="p-4 font-bold">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-700">
                                    {{ $item->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-400">Belum ada pranota siap billing. Selesaikan/Setujui pranota terlebih dahulu.</td>
                        </tr>
                        @endforelse
                    </template>

                    <!-- Tab Sudah Terbilling -->
                    <template x-if="pranotaTab === 'sudah'">
                        @forelse($sudahTerbilling as $item)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                            <td class="p-4 font-bold text-slate-800 dark:text-slate-200">{{ $item->pranota_number }}</td>
                            <td class="p-4 font-medium">{{ $item->project->segment }} - {{ $item->project->regional }}</td>
                            <td class="p-4 text-slate-400">{{ $item->period ? $item->period->name : 'Pranota Manual' }}</td>
                            <td class="p-4">{{ $item->created_at->format('d M Y') }}</td>
                            <td class="p-4 font-bold">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                            <td class="p-4">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700">
                                    {{ $item->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="p-12 text-center text-slate-400">Belum ada pranota yang resmi ditagihkan.</td>
                        </tr>
                        @endforelse
                    </template>
                </tbody>
            </table>
        </div>

    </div>

    <!-- TAB 2: NOTA BILLING (INVOICES - Halaman 12-13) -->
    <div x-show="activeTab === 'nota'" class="space-y-6" style="display: none;">
        
        <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <th class="p-4">Nomor Nota (AR Invoice)</th>
                        <th class="p-4">Project</th>
                        <th class="p-4">Tax Code</th>
                        <th class="p-4">Nilai Tagihan (Grand Total)</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Doc SAP AR</th>
                        <th class="p-4">Tanggal Posting SAP</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-xs text-slate-600 dark:text-slate-350">
                    @forelse($notas as $nota)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                        <td class="p-4 font-bold text-slate-800 dark:text-slate-200">{{ $nota->nota_number }}</td>
                        <td class="p-4 font-medium">{{ $nota->project->segment }} - {{ $nota->project->regional }}</td>
                        <td class="p-4">{{ $nota->tax_code }}</td>
                        <td class="p-4 font-bold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($nota->amount, 0, ',', '.') }}</td>
                        <td class="p-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold
                                {{ $nota->status === 'Draft' ? 'bg-slate-100 text-slate-700' : 'bg-emerald-50 text-emerald-700' }}
                            ">
                                {{ $nota->status }}
                            </span>
                        </td>
                        <td class="p-4 text-slate-450 font-mono">{{ $nota->sap_doc_number ?: '-' }}</td>
                        <td class="p-4 text-slate-400">{{ $nota->posted_at ? $nota->posted_at->format('d M Y H:i') : '-' }}</td>
                        <td class="p-4 text-center">
                            @if($nota->status === 'Draft')
                                <form action="{{ route('billing.nota.post-sap', $nota->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-[10px] font-bold rounded-lg transition cursor-pointer">
                                        Send to SAP
                                    </button>
                                </form>
                            @else
                                <span class="text-[10px] font-bold text-emerald-600 flex items-center justify-center gap-1">
                                    ✔️ Terposting SAP
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-12 text-center text-slate-400">Belum ada nota billing dibuat. Buat Nota dari kumpulan Pranota (Do Nota).</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    {{-- MODAL: CREATE PRANOTA MANUAL --}}
    <div x-show="showManualModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-md" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showManualModal = false">
            <h3 class="text-base font-bold text-slate-850 dark:text-slate-100 mb-4">Buat Pranota Manual</h3>
            
            <form action="{{ route('billing.pranota.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Pilih Project / Cost Center</label>
                    <select name="project_id" required 
                            class="w-full text-xs px-3 py-2.5 bg-slate-50 dark:bg-slate-855 border border-slate-200 dark:border-slate-700 rounded-xl outline-none text-slate-800 dark:text-slate-200">
                        @foreach($projects as $proj)
                            <option value="{{ $proj->id }}">{{ $proj->segment }} - {{ $proj->regional }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Nomor Pranota</label>
                    <input type="text" name="pranota_number" required placeholder="Contoh: PRAN-MANUAL-202608001" 
                           class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Nilai Harga Jasa (Amount)</label>
                    <input type="number" name="amount" required min="0" placeholder="Masukkan nilai tagihan..." 
                           class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800/80">
                    <button type="button" @click="showManualModal = false" class="px-4 py-2 text-slate-500 text-xs font-bold rounded-xl hover:bg-slate-50 transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow transition cursor-pointer">Simpan Pranota</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: DO NOTA ACTION --}}
    <div x-show="showDoNotaModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/65 backdrop-blur-md" style="display: none;">
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-3xl w-full max-w-md p-6 shadow-2xl relative" @click.away="showDoNotaModal = false">
            <h3 class="text-base font-bold text-slate-850 dark:text-slate-100 mb-2">Konfirmasi Pengelompokan Nota</h3>
            <p class="text-xs text-slate-400 mb-4 font-medium">Buat 1 Invoice Nota Billing final untuk dikirimkan ke SAP.</p>
            
            <form action="{{ route('billing.nota.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="project_id" :value="selectedProject">
                <template x-for="id in selectedPranotas" :key="id">
                    <input type="hidden" name="pranota_ids[]" :value="id">
                </template>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1.5">Nomor Nota Billing</label>
                    <input type="text" name="nota_number" required placeholder="Contoh: NOTA-AR-202608001" 
                           class="w-full text-xs px-3.5 py-2.5 bg-slate-50 dark:bg-slate-850 border border-slate-200 dark:border-slate-700 rounded-xl focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/10 outline-none text-slate-800 dark:text-slate-200">
                </div>

                <div class="p-4 bg-slate-50 dark:bg-slate-950/20 border border-slate-150 dark:border-slate-850 rounded-2xl text-xs space-y-2">
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-semibold">Total DPP (Tagihan):</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(totalSelectedAmount)"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400 font-semibold">Pajak (PPN 11%):</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(totalSelectedAmount * 0.11)"></span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-slate-100 dark:border-slate-800/40 text-sm">
                        <span class="text-slate-500 font-bold">Grand Total AR:</span>
                        <span class="font-black text-indigo-600 dark:text-indigo-400" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(totalSelectedAmount * 1.11)"></span>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800/80">
                    <button type="button" @click="showDoNotaModal = false" class="px-4 py-2 text-slate-500 text-xs font-bold rounded-xl hover:bg-slate-50 transition cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-650 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow transition cursor-pointer">Do Nota</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
