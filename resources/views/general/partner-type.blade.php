@extends('layouts.app')

@section('title', 'Partner Type Master — Tanos ERP')

@section('content')
<div class="space-y-6" x-data="{ 
    showModal: false, 
    editMode: false, 
    form: { id: null, code: '', name: '', description: '', active: true },
    openCreate() {
        this.editMode = false;
        this.form = { id: null, code: '', name: '', description: '', active: true };
        this.showModal = true;
    },
    openEdit(item) {
        this.editMode = true;
        this.form = { 
            id: item.id, 
            code: item.code, 
            name: item.name, 
            description: item.description || '', 
            active: !!item.active 
        };
        this.showModal = true;
    }
}">

    <x-page-header 
        title="Partner Type Master" 
        subtitle="Klasifikasi segmentasi rekanan bisnis, vendor alih daya, pelanggan, serta afiliasi BUMN."
        :breadcrumbs="[
            'General' => '#',
            'Master Data' => '#',
            'Partner Type' => ''
        ]"
        create-label="Tambah Partner Type"
        create-click="openCreate()"
    />

    <!-- Alert Notification -->
    @if(session('success'))
    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-xl text-xs font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    </div>
    @endif

    <x-data-card 
        title="Partner Type - List" 
        :total="$partnerTypes->total()"
        :search-route="route('general.partner-type')"
    >
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-800/60 border-y border-slate-200 dark:border-slate-800 text-[11px] font-extrabold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                        <th class="py-3.5 px-3">Kode Tipe</th>
                        <th class="py-3.5 px-3 min-w-[200px]">Nama Tipe Partner</th>
                        <th class="py-3.5 px-3 min-w-[250px]">Keterangan / Fungsi</th>
                        <th class="py-3.5 px-3 text-center">Jumlah Partner</th>
                        <th class="py-3.5 px-3 text-center">Status</th>
                        <th class="py-3.5 px-3 text-center whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($partnerTypes as $item)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                            <td class="py-3.5 px-3 font-mono font-bold text-primary">
                                {{ $item->code }}
                            </td>
                            <td class="py-3.5 px-3 font-bold text-slate-900 dark:text-slate-100">
                                {{ $item->name }}
                            </td>
                            <td class="py-3.5 px-3 text-slate-600 dark:text-slate-400">
                                {{ $item->description ?? '-' }}
                            </td>
                            <td class="py-3.5 px-3 text-center">
                                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-[11px]">
                                    {{ $item->partners_count }} Partner
                                </span>
                            </td>
                            <td class="py-3.5 px-3 text-center font-bold">
                                @if($item->active)
                                    <span class="text-emerald-600 dark:text-emerald-400">Aktif</span>
                                @else
                                    <span class="text-slate-400">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-3 text-center whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1.5">
                                    <x-action-button type="edit" :click="'openEdit(' . json_encode($item) . ')'" title="Edit Tipe" />
                                    @if($item->partners_count == 0)
                                        <form action="{{ route('general.partner-type.destroy', $item->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Hapus Tipe Partner ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <x-action-button type="delete" title="Hapus Tipe" />
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                Tidak ada data Partner Type ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($partnerTypes->hasPages())
        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
            {{ $partnerTypes->links() }}
        </div>
        @endif
    </x-data-card>

    <!-- MODAL FORM CREATE / EDIT PARTNER TYPE -->
    <div x-show="showModal" x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 dark:bg-slate-950/80 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-lg w-full shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden transform transition-all"
             @click.away="showModal = false">
            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100" x-text="editMode ? 'Edit Partner Type' : 'Tambah Partner Type'"></h3>
                <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-lg font-bold cursor-pointer">&times;</button>
            </div>
            
            <form :action="editMode ? '{{ url('/general/partner-type') }}/' + form.id : '{{ route('general.partner-type.store') }}'" method="POST" class="p-6 space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kode Tipe *</label>
                    <input type="text" name="code" x-model="form.code" required placeholder="misal: BUMN, VENDOR"
                           class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 uppercase">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Tipe Partner *</label>
                    <input type="text" name="name" x-model="form.name" required placeholder="misal: Afiliasi BUMN / Pelindo Group"
                           class="w-full px-3.5 py-2 text-xs font-semibold border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Deskripsi / Peruntukan</label>
                    <textarea name="description" x-model="form.description" rows="3" placeholder="Keterangan singkat tipe rekanan..."
                              class="w-full px-3.5 py-2 text-xs font-medium border border-slate-300 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100"></textarea>
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <input type="checkbox" name="active" value="1" id="active" x-model="form.active" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <label for="active" class="text-xs font-bold text-slate-700 dark:text-slate-300">Status Aktif</label>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showModal = false" class="px-4 py-2 text-xs font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-md border-0 cursor-pointer" x-text="editMode ? 'Simpan Perubahan' : 'Simpan Tipe'"></button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
