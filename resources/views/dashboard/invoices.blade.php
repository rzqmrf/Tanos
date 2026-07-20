@extends('layouts.app')

@section('title', 'Master Data: Invoices — Tanos ERP')

@section('content')
<div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm w-full">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Master Data: Invoices</h1>
                <p class="text-sm text-slate-400">Manajemen pencatatan keuangan internal perusahaan.</p>
            </div>
        </div>
        <button onclick="document.getElementById('modal-create-invoice').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium shadow-sm transition shrink-0">
            + Tambah Invoice
        </button>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Data Table Section -->
    <div class="mt-6 border-t border-slate-100 pt-6">
        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-200">
                        <th class="p-4">Type</th>
                        <th class="p-4">Bulan</th>
                        <th class="p-4">Regional</th>
                        <th class="p-4">Segment</th>
                        <th class="p-4">Amount</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    @forelse($invoices as $item)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4 font-semibold text-slate-800">
                            <span class="px-2.5 py-1 {{ $item->type == 'P2P' ? 'bg-purple-50 text-purple-700' : 'bg-orange-50 text-orange-700' }} rounded-md text-xs font-bold">
                                {{ $item->type }}
                            </span>
                        </td>
                        <td class="p-4 text-slate-500">{{ $item->month }}</td>
                        <td class="p-4 text-slate-500">{{ $item->regional }}</td>
                        <td class="p-4 text-slate-500">{{ $item->segment }}</td>
                        <td class="p-4 font-medium text-slate-900">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                        <td class="p-4 flex items-center justify-center space-x-2">
                            <button onclick="openEditModal({{ json_encode($item) }})" class="p-1.5 text-blue-600 hover:text-blue-800 bg-blue-50 rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" /></svg>
                            </button>
                            <form action="/dashboard/invoices/{{ $item->id }}" method="POST" onsubmit="return confirm('Yakin hapus invoice ini?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 hover:text-red-800 bg-red-50 rounded-lg transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m12 6c0 1.66-1.34 3-3 3H6c-1.34 0-3-1.34-3-3V7h18v8Z" /></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center">
                            <div class="rounded-xl bg-slate-50 border border-slate-200 p-6 max-w-md mx-auto">
                                <p class="text-sm font-medium text-slate-600">Data invoice belum ditambahkan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $invoices->links() }}
        </div>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div id="modal-create-invoice" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-800">Tambah Data Invoice</h3>
            <button onclick="document.getElementById('modal-create-invoice').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form action="/dashboard/invoices" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Type</label>
                <select name="type" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                    <option value="P2P">P2P</option>
                    <option value="Non P2P">Non P2P</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Bulan</label>
                <input type="text" name="month" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Regional</label>
                <input type="text" name="regional" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Segment</label>
                <input type="text" name="segment" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Amount (Angka Saja)</label>
                <input type="number" name="amount" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('modal-create-invoice').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium shadow-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT -->
<div id="modal-edit-invoice" class="hidden fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-800">Edit Data Invoice</h3>
            <button onclick="document.getElementById('modal-edit-invoice').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <form id="form-edit-invoice" action="" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Type</label>
                <select id="edit-type" name="type" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                    <option value="P2P">P2P</option>
                    <option value="Non P2P">Non P2P</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Bulan</label>
                <input type="text" id="edit-month" name="month" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Regional</label>
                <input type="text" id="edit-regional" name="regional" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Segment</label>
                <input type="text" id="edit-segment" name="segment" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-1">Amount</label>
                <input type="number" id="edit-amount" name="amount" required class="w-full px-3 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="document.getElementById('modal-edit-invoice').classList.add('hidden')" class="px-4 py-2 border border-slate-200 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-medium shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(invoice) {
        const modal = document.getElementById('modal-edit-invoice');
        const form = document.getElementById('form-edit-invoice');
        form.action = `/dashboard/invoices/${invoice.id}`;
        document.getElementById('edit-type').value = invoice.type || 'P2P';
        document.getElementById('edit-month').value = invoice.month || '';
        document.getElementById('edit-regional').value = invoice.regional || '';
        document.getElementById('edit-segment').value = invoice.segment || '';
        document.getElementById('edit-amount').value = invoice.amount || '';
        modal.classList.remove('hidden');
    }
</script>
@endsection