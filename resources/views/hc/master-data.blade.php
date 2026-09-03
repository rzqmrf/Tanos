@extends('layouts.app')

@section('title', 'Master Data ' . $categoryTitle . ' — Human Capital TANOS ERP')

@section('content')
<div class="space-y-6" x-data="{ showModal: false, editMode: false, form: { id: null, code: '', name: '', description: '', active: true } }">

    <!-- Page Header & Action -->
    <x-page-header 
        :title="'Master Data ' . $categoryTitle" 
        :subtitle="'Kelola data referensi ' . $categoryTitle . ' untuk sistem pengelolaan Tenaga Alih Daya (TAD) Pelindo Group.'"
        :breadcrumbs="[
            'General' => '#',
            'Human Capital' => '#',
            'Master Data ' . $categoryTitle => ''
        ]"
    >
        <x-slot:action>
            <button @click="editMode = false; form = { id: null, code: '', name: '', description: '', active: true }; showModal = true"
                    class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition flex items-center space-x-1.5 self-start sm:self-auto cursor-pointer border-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Tambah {{ $categoryTitle }}</span>
            </button>
        </x-slot:action>
    </x-page-header>

    <!-- Search & Summary Bar -->
    <div class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('hc.master.index', ['category' => $currentCategory]) }}" class="flex items-center space-x-2 flex-1 max-w-md">
            <div class="relative w-full">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau kode {{ strtolower($categoryTitle) }}..."
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 rounded-xl text-xs focus:outline-none focus:border-primary font-medium">
                <div class="absolute left-3 top-2.5 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-700 transition">Cari</button>
            @if(request('search'))
                <a href="{{ route('hc.master.index', ['category' => $currentCategory]) }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-200 transition">Reset</a>
            @endif
        </form>

        <div class="flex items-center space-x-4 text-xs font-bold text-slate-500 dark:text-slate-400">
            <span>Total: <strong class="text-slate-800 dark:text-slate-100">{{ $totalAll }}</strong> {{ $categoryTitle }}</span>
            <span>•</span>
            <span class="text-emerald-600 dark:text-emerald-400">Aktif: <strong>{{ $totalActive }}</strong></span>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4">No</th>
                        <th class="py-3.5 px-4">Kode Referensi</th>
                        <th class="py-3.5 px-4">Nama {{ $categoryTitle }}</th>
                        <th class="py-3.5 px-4">Keterangan / Deskripsi</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-center w-28">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60 text-xs font-medium text-slate-700 dark:text-slate-300">
                    @forelse($items as $index => $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition">
                            <td class="py-3.5 px-4 text-slate-400 font-semibold">{{ $items->firstItem() + $index }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 bg-primary-light text-primary font-mono font-bold rounded-md text-[11px] border border-primary-subtle">
                                    {{ $item->code ?? '—' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-slate-100">{{ $item->name }}</td>
                            <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400 max-w-xs truncate">{{ $item->description ?? '-' }}</td>
                            <td class="py-3.5 px-4">
                                @if($item->active)
                                    <span class="px-2.5 py-1 text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 rounded-lg inline-flex items-center">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>Aktif
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-500 rounded-lg inline-flex items-center">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>Non-Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center space-x-1.5">
                                    <button @click="editMode = true; form = { id: {{ $item->id }}, code: '{{ addslashes($item->code) }}', name: '{{ addslashes($item->name) }}', description: '{{ addslashes($item->description) }}', active: {{ $item->active ? 'true' : 'false' }} }; showModal = true"
                                            class="p-2 rounded-lg bg-purple-600 hover:bg-purple-700 text-white transition shadow-2xs flex items-center justify-center cursor-pointer" title="Edit Data">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>
                                    <form method="POST" action="{{ route('hc.master.destroy', $item->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white transition shadow-2xs flex items-center justify-center cursor-pointer" title="Hapus Data">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400 font-medium">
                                Belum ada data {{ $categoryTitle }} yang tersimpan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-800">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form (Create / Edit) -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[1050] overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div x-show="showModal" @click.away="showModal = false"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
             class="bg-white dark:bg-slate-900 rounded-xl max-w-md w-full p-6 shadow-2xl border border-slate-200 dark:border-slate-800">
            
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-100" x-text="editMode ? 'Edit Data {{ $categoryTitle }}' : 'Tambah {{ $categoryTitle }} Baru'"></h3>
                <button type="button" @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form :action="editMode ? '{{ url('dashboard/hc-master/update') }}/' + form.id : '{{ route('hc.master.store', $currentCategory) }}'" method="POST" class="space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Kode Referensi</label>
                    <input type="text" name="code" x-model="form.code" placeholder="Misal: REG-1, JC-01" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-primary font-mono font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Nama {{ $categoryTitle }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="form.name" required placeholder="Nama {{ strtolower($categoryTitle) }}" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-primary font-semibold">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-1">Keterangan / Deskripsi</label>
                    <textarea name="description" x-model="form.description" rows="3" placeholder="Deskripsi singkat atau catatan opsional" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-100 rounded-xl text-xs focus:outline-none focus:border-primary font-medium"></textarea>
                </div>

                <div class="flex items-center space-x-2 pt-1">
                    <input type="checkbox" name="active" value="1" id="activeCheck" x-model="form.active" class="w-4 h-4 text-primary rounded border-slate-300 focus:ring-primary">
                    <label for="activeCheck" class="text-xs font-bold text-slate-700 dark:text-slate-300 select-none">Status Aktif</label>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showModal = false" class="px-4 py-2 border border-slate-200 dark:border-slate-800 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 transition">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-primary hover:bg-primary-hover text-white rounded-xl text-xs font-bold shadow-md shadow-primary transition">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

