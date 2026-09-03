@extends('layouts.app')

@section('title', 'Business Partner — Tanos ERP')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action -->
    <x-page-header 
        title="Business Partner" 
        subtitle="Master Data Business Partner (Vendor, Customer, Mitraniaga BUMN & Swasta)."
        :breadcrumbs="[
            'General' => '#',
            'Master Data' => '#',
            'Partner' => ''
        ]"
        create-label="Create New"
        :create-href="route('general.partner.create')"
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

    <!-- Data Table Container -->
    <x-data-card 
        title="Business Partner - List" 
        :total="$partners->total()"
        :search-route="route('general.partner')"
    >
        <!-- Main Table matching Tanos Screenshot -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 dark:bg-slate-800/60 border-y border-slate-200 dark:border-slate-800 text-[11px] font-extrabold text-slate-600 dark:text-slate-300 uppercase tracking-wider">
                        <th class="py-3.5 px-3 whitespace-nowrap">Partner Code</th>
                        <th class="py-3.5 px-3 min-w-[200px]">Name</th>
                        <th class="py-3.5 px-3 min-w-[220px]">Address</th>
                        <th class="py-3.5 px-3 text-center">Vendor</th>
                        <th class="py-3.5 px-3 text-center">Customer</th>
                        <th class="py-3.5 px-3 text-center">Partner Type</th>
                        <th class="py-3.5 px-3 min-w-[180px]">Description</th>
                        <th class="py-3.5 px-3">Email</th>
                        <th class="py-3.5 px-3">Phone No.1</th>
                        <th class="py-3.5 px-3">City</th>
                        <th class="py-3.5 px-3">Identity Card</th>
                        <th class="py-3.5 px-3 font-mono">NPWP</th>
                        <th class="py-3.5 px-3 text-center whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    @forelse($partners as $item)
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                        <!-- Partner Code -->
                        <td class="py-3.5 px-3 font-mono font-bold text-primary">
                            <a href="{{ route('general.partner.show', $item->id) }}" class="hover:underline">
                                {{ $item->code }}
                            </a>
                        </td>

                        <!-- Name -->
                        <td class="py-3.5 px-3 font-bold text-slate-900 dark:text-slate-100">
                            <a href="{{ route('general.partner.show', $item->id) }}" class="hover:text-primary dark:hover:text-sky-400 transition">
                                {{ $item->name }}
                            </a>
                        </td>

                        <!-- Address -->
                        <td class="py-3.5 px-3 text-slate-600 dark:text-slate-400">
                            {{ $item->address ?? '-' }}
                        </td>

                        <!-- Vendor -->
                        <td class="py-3.5 px-3 text-center font-bold">
                            @if($item->is_vendor)
                                <span class="text-emerald-600 dark:text-emerald-400">Yes</span>
                            @else
                                <span class="text-slate-400">No</span>
                            @endif
                        </td>

                        <!-- Customer -->
                        <td class="py-3.5 px-3 text-center font-bold">
                            @if($item->is_customer)
                                <span class="text-emerald-600 dark:text-emerald-400">Yes</span>
                            @else
                                <span class="text-slate-400">No</span>
                            @endif
                        </td>

                        <!-- Partner Type -->
                        <td class="py-3.5 px-3 text-center font-mono font-semibold">
                            <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-[11px]">
                                {{ $item->partnerType?->code ?? '-' }}
                            </span>
                        </td>

                        <!-- Description -->
                        <td class="py-3.5 px-3 text-slate-500 dark:text-slate-400 truncate max-w-xs">
                            {{ $item->description ?? $item->name }}
                        </td>

                        <!-- Email -->
                        <td class="py-3.5 px-3 text-slate-600 dark:text-slate-400 font-mono">
                            {{ $item->email ?? '-' }}
                        </td>

                        <!-- Phone No.1 -->
                        <td class="py-3.5 px-3 font-mono">
                            {{ $item->phone_1 ?? $item->phone ?? '0' }}
                        </td>

                        <!-- City -->
                        <td class="py-3.5 px-3 font-bold text-slate-800 dark:text-slate-200">
                            {{ $item->city ?? '-' }}
                        </td>

                        <!-- Identity Card -->
                        <td class="py-3.5 px-3 font-mono text-slate-500 dark:text-slate-400">
                            {{ $item->identity_card ?? '-' }}
                        </td>

                        <!-- NPWP -->
                        <td class="py-3.5 px-3 font-mono font-semibold text-slate-800 dark:text-slate-200">
                            {{ $item->npwp ?? '-' }}
                        </td>

                        <!-- Action Buttons matching Screenshot style -->
                        <td class="py-3.5 px-3 text-center whitespace-nowrap">
                            <div class="inline-flex items-center space-x-1.5">
                                <x-action-button type="view" :href="route('general.partner.show', $item->id)" title="View Partner" />
                                <x-action-button type="edit" :href="route('general.partner.edit', $item->id)" title="Edit Partner" />
                                <form action="{{ route('general.partner.destroy', $item->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data Partner {{ $item->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-action-button type="delete" title="Delete Partner" />
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="13" class="py-8 text-center text-slate-400 dark:text-slate-500">
                            Tidak ada data Partner yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($partners->hasPages())
        <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
            {{ $partners->links() }}
        </div>
        @endif
    </x-data-card>

</div>
@endsection
