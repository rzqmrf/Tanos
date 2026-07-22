@extends('layouts.app')

@section('title', 'Notifications Center — Tanos ERP')

@section('content')
<div class="p-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm w-full space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center space-x-3">
            <div class="p-2 bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 rounded-lg shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-100 flex items-center space-x-2">
                    <span>Notifikasi & Activity Log</span>
                    @if($unreadCount > 0)
                        <span class="px-2.5 py-0.5 bg-rose-500 text-white rounded-full text-xs font-bold">{{ $unreadCount }} Baru</span>
                    @endif
                </h1>
                <p class="text-sm text-slate-400 dark:text-slate-500">Riwayat seluruh aktivitas proyek, pegawai, dan invoice perusahaan.</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center space-x-2 shrink-0">
            @if($unreadCount > 0)
                <form action="{{ route('notifications.readAll') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-blue-50 hover:bg-blue-100 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400 px-3.5 py-2 rounded-xl text-xs font-semibold transition">
                        ✓ Tandai Semua Dibaca
                    </button>
                </form>
            @endif

            @if($totalCount > 0)
                <form action="{{ route('notifications.deleteAll') }}" method="POST" onsubmit="return confirm('Bersihkan seluruh riwayat notifikasi?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-rose-50 hover:bg-rose-100 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400 px-3.5 py-2 rounded-xl text-xs font-semibold transition">
                        ✕ Bersihkan Semua
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Alert Sukses Bawaan Laravel -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30 rounded-xl text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Bar Section -->
    <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-slate-100 dark:border-slate-800">
        <!-- Status Filter Tabs -->
        <div class="flex items-center space-x-1.5 bg-slate-100 dark:bg-slate-800/60 p-1 rounded-xl">
            <a href="{{ route('notifications.page', array_merge(request()->query(), ['status' => ''])) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ !request('status') ? 'bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700' }}">
                Semua Status
            </a>
            <a href="{{ route('notifications.page', array_merge(request()->query(), ['status' => 'unread'])) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request('status') === 'unread' ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700' }}">
                Belum Dibaca
            </a>
            <a href="{{ route('notifications.page', array_merge(request()->query(), ['status' => 'read'])) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ request('status') === 'read' ? 'bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 shadow-sm' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700' }}">
                Sudah Dibaca
            </a>
        </div>

        <!-- Type Filter Pills -->
        <div class="flex items-center space-x-1.5">
            <a href="{{ route('notifications.page', array_merge(request()->query(), ['type' => ''])) }}" class="px-3 py-1.5 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-medium transition {{ !request('type') ? 'bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50' }}">
                Semua Tipe
            </a>
            <a href="{{ route('notifications.page', array_merge(request()->query(), ['type' => 'project'])) }}" class="px-3 py-1.5 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-medium transition {{ request('type') === 'project' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50' }}">
                Proyek
            </a>
            <a href="{{ route('notifications.page', array_merge(request()->query(), ['type' => 'employee'])) }}" class="px-3 py-1.5 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-medium transition {{ request('type') === 'employee' ? 'bg-amber-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50' }}">
                Pegawai
            </a>
            <a href="{{ route('notifications.page', array_merge(request()->query(), ['type' => 'invoice'])) }}" class="px-3 py-1.5 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-medium transition {{ request('type') === 'invoice' ? 'bg-emerald-600 text-white' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50' }}">
                Invoice
            </a>
        </div>
    </div>

    <!-- Notifications List Section -->
    <div class="space-y-3">
        @forelse($notifications as $item)
            <div class="p-4 rounded-xl border transition flex items-start justify-between gap-4 {{ is_null($item->read_at) ? 'bg-blue-50/40 dark:bg-blue-950/20 border-blue-200 dark:border-blue-900/40' : 'bg-white dark:bg-slate-900 border-slate-100 dark:border-slate-800' }}">
                
                <div class="flex items-start space-x-3.5">
                    <!-- Type Icon Badge -->
                    @if($item->type === 'project')
                        <div class="p-2.5 bg-blue-100 dark:bg-blue-950 text-blue-600 dark:text-blue-400 rounded-xl shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.008 1.24l.885 1.77a2.25 2.25 0 0 0 2.007 1.24h1.98a2.25 2.25 0 0 0 2.007-1.24l.885-1.77a2.25 2.25 0 0 1 2.007-1.24h3.86m-18 0h18" /></svg>
                        </div>
                    @elseif($item->type === 'employee')
                        <div class="p-2.5 bg-amber-100 dark:bg-amber-950 text-amber-600 dark:text-amber-400 rounded-xl shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z" /></svg>
                        </div>
                    @else
                        <div class="p-2.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 rounded-xl shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5-3h7.5" /></svg>
                        </div>
                    @endif

                    <div class="space-y-1">
                        <div class="flex items-center space-x-2">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $item->title }}</h3>
                            @if(is_null($item->read_at))
                                <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">{{ $item->message }}</p>
                        <p class="text-[11px] text-slate-400 dark:text-slate-500 font-medium">{{ $item->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <!-- Actions for Item -->
                <div class="flex items-center space-x-2 shrink-0">
                    @if(is_null($item->read_at))
                        <form action="{{ route('notifications.read', $item->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-1.5 text-xs text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/30 rounded-lg transition" title="Tandai Dibaca">
                                ✓ Dibaca
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('notifications.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus notifikasi ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg transition" title="Hapus Notifikasi">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9 9m12 6c0 1.66-1.34 3-3 3H6c-1.34 0-3-1.34-3-3V7h18v8Z" /></svg>
                        </button>
                    </form>
                </div>

            </div>
        @empty
            <div class="p-12 text-center border border-dashed border-slate-200 dark:border-slate-800 rounded-2xl">
                <div class="p-4 bg-slate-50 dark:bg-slate-800/30 text-slate-400 rounded-full inline-block mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                </div>
                <p class="text-sm font-semibold text-slate-600 dark:text-slate-400">Tidak ada notifikasi ditemukan.</p>
                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Riwayat notifikasi akan otomatis muncul saat ada pembaruan proyek, pegawai, atau invoice.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination Links -->
    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
