@extends('layouts.app')

@section('title', 'Notifikasi — Tanos ERP')

@section('content')
<div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm w-full">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100">Notifikasi & Activity Log</h1>
                <p class="text-sm text-slate-400 dark:text-slate-500">Riwayat seluruh aktivitas proyek, pegawai, dan invoice perusahaan.</p>
            </div>
        </div>
        
        <!-- Header Actions -->
        <div class="flex items-center space-x-2 shrink-0">
            @if($unreadCount > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3.5 py-2 rounded-xl text-xs font-semibold shadow-sm transition">
                        ✓ Tandai Semua Dibaca
                    </button>
                </form>
            @endif

            @if($totalCount > 0)
                <form action="{{ route('notifications.deleteAll') }}" method="POST" onsubmit="return confirm('Bersihkan seluruh riwayat notifikasi?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-950/30 dark:text-red-400 px-3.5 py-2 rounded-xl text-xs font-semibold transition">
                        Hapus Semua
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Alert Sukses Bawaan Laravel -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Data Table Section -->
    <div class="mt-6 border-t border-slate-100 dark:border-slate-800 pt-6">
        <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <th class="p-4">Tipe</th>
                        <th class="p-4">Judul Notifikasi</th>
                        <th class="p-4">Pesan Detail</th>
                        <th class="p-4">Waktu</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-300">
                    @forelse($notifications as $item)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition {{ is_null($item->read_at) ? 'bg-blue-50/20 dark:bg-blue-950/10 font-medium' : '' }}">
                        <td class="p-4">
                            @if($item->type === 'project')
                                <span class="px-2.5 py-1 bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 rounded-md text-xs font-semibold">Proyek</span>
                            @elseif($item->type === 'employee')
                                <span class="px-2.5 py-1 bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 rounded-md text-xs font-semibold">Pegawai</span>
                            @else
                                <span class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-md text-xs font-semibold">Invoice</span>
                            @endif
                        </td>
                        <td class="p-4 font-semibold text-slate-800 dark:text-slate-100">
                            {{ $item->title }}
                            @if(is_null($item->read_at))
                                <span class="ml-1 px-2 py-0.5 bg-blue-600 text-white text-[10px] rounded-full font-bold">Baru</span>
                            @endif
                        </td>
                        <td class="p-4 text-slate-600 dark:text-slate-300">{{ $item->message }}</td>
                        <td class="p-4 text-slate-400 dark:text-slate-500 text-xs">{{ $item->created_at->diffForHumans() }}</td>
                        <td class="p-4 flex items-center justify-center space-x-2">
                            @if(is_null($item->read_at))
                                <form action="{{ route('notifications.read', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1.5 text-blue-600 dark:text-blue-400 hover:text-blue-800 bg-blue-50 dark:bg-blue-950/30 rounded-lg transition" title="Tandai Dibaca">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('notifications.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-600 dark:text-red-400 hover:text-red-800 bg-red-50 dark:bg-red-950/30 rounded-lg transition" title="Hapus Notifikasi">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m12 6c0 1.66-1.34 3-3 3H6c-1.34 0-3-1.34-3-3V7h18v8Z" /></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center">
                            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/10 border border-slate-200 dark:border-slate-800 p-6 max-w-md mx-auto">
                                <p class="text-sm font-medium text-slate-600 dark:text-slate-400">Tidak ada riwayat notifikasi.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection
