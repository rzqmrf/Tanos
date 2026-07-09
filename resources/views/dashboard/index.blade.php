@extends('layouts.app')

@section('title', 'Tanos ERP - Dashboard')

@section('content')
<!-- Statistic Cards Grid (Row of 6 Cards) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-5 mb-8">

    <!-- Card 1: Total Project Active -->
    <x-statistic-card title="Total Project Active" field="totalActiveProjects" theme="green">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 .621-.504 1.125-1.125 1.125H4.875c-.621 0-1.125-.504-1.125-1.125v-4.25m16.5 0a2.562 2.562 0 0 0-2.562-2.562H4.875c-.621 0-1.125-.504-1.125-1.125V14.15m16.5 0V9a2.562 2.562 0 0 0-2.562-2.562H4.875A2.562 2.562 0 0 0 2.25 9v5.15M9 6a3 3 0 0 1 6 0" />
        </svg>
    </x-statistic-card>

    <!-- Card 2: Jumlah Pegawai -->
    <x-statistic-card title="Jumlah Pegawai" field="jumlahPegawai" theme="purple">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
        </svg>
    </x-statistic-card>

    <!-- Card 3: Nota P2P -->
    <x-statistic-card title="Nota P2P" field="notaP2P" theme="orange">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5-3h7.5M8.25 9.75h.008v.008H8.25V9.75Z" />
        </svg>
    </x-statistic-card>

    <!-- Card 4: Nota Non P2P -->
    <x-statistic-card title="Nota Non P2P" field="notaNonP2P" theme="red">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
        </svg>
    </x-statistic-card>

    <!-- Card 5: Total Cost -->
    <x-statistic-card title="Total Cost" field="totalCost" theme="blue">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.752.315C10.428 17.072 11.218 17.25 12 17.25c.957 0 1.92-.3 2.508-1.077.58-.766.53-1.897-.263-2.564l-.79-.667a4.275 4.275 0 0 0-2.6-1.344c-.455-.078-.9-.253-1.302-.518-1.092-.725-1.214-2.232-.302-3.178C9.9 8.077 10.966 7.75 12 7.75c.896 0 1.701.21 2.308.625l.483.33M9 16.125h.008v.008H9v-.008Zm0-6.125h.008v.008H9V10Zm6 6v.008h-.008V16H15Zm0-6.125h.008v.008H15V9.875Z" />
        </svg>
    </x-statistic-card>

    <!-- Card 6: Avg Cost / Project -->
    <x-statistic-card title="Avg Cost / Project" field="avgCost" theme="cyan">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
        </svg>
    </x-statistic-card>

</div>

<!-- Charts Grid - Row 1 (Doughnuts) -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- Left Card: Jumlah Project per Segment -->
    <x-chart-card title="Jumlah Project per Segment">
        <div class="w-full flex flex-col sm:flex-row items-center justify-between gap-4">
            <!-- Doughnut Chart Canvas with Center Overlay -->
            <div class="relative w-40 h-40 shrink-0">
                <canvas id="projectSegmentChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none select-none">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Total</span>
                    <span x-text="stats.totalActiveProjects.formatted" class="text-base font-extrabold text-slate-700"></span>
                </div>
            </div>
            <!-- Custom HTML Legend -->
            <div class="flex-1 w-full space-y-2 pl-0 sm:pl-4">
                <template x-for="(item, index) in chartData.projectsPerSegment" :key="item.category">
                    <div class="flex items-center justify-between text-xs font-semibold">
                        <div class="flex items-center space-x-2.5">
                            <span :class="[
                                index === 0 ? 'bg-[#1b3bb6]' : '',
                                index === 1 ? 'bg-emerald-500' : '',
                                index === 2 ? 'bg-amber-500' : '',
                                index === 3 ? 'bg-purple-500' : '',
                                index === 4 ? 'bg-slate-400' : ''
                            ]" class="w-3 h-3 rounded-md shrink-0"></span>
                            <span x-text="item.category" class="text-slate-500 truncate"></span>
                        </div>
                        <div class="text-slate-700 shrink-0 pl-2">
                            <span x-text="item.value"></span>
                            <span class="text-slate-400 font-medium ml-1"
                                  x-text="'(' + (stats.totalActiveProjects.raw > 0 ? ((item.value / stats.totalActiveProjects.raw) * 100).toFixed(1) : 0) + '%)'"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </x-chart-card>

    <!-- Right Card: Jumlah Project per Regional -->
    <x-chart-card title="Jumlah Project per Regional">
        <div class="w-full flex flex-col sm:flex-row items-center justify-between gap-4">
            <!-- Doughnut Chart Canvas with Center Overlay -->
            <div class="relative w-40 h-40 shrink-0">
                <canvas id="projectRegionalChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none select-none">
                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Total</span>
                    <span x-text="stats.totalActiveProjects.formatted" class="text-base font-extrabold text-slate-700"></span>
                </div>
            </div>
            <!-- Custom HTML Legend -->
            <div class="flex-1 w-full space-y-2 pl-0 sm:pl-4">
                <template x-for="(item, index) in chartData.projectsPerRegional" :key="item.category">
                    <div class="flex items-center justify-between text-xs font-semibold">
                        <div class="flex items-center space-x-2.5">
                            <span :class="[
                                index === 0 ? 'bg-[#1b3bb6]' : '',
                                index === 1 ? 'bg-emerald-500' : '',
                                index === 2 ? 'bg-amber-500' : '',
                                index === 3 ? 'bg-purple-500' : '',
                                index === 4 ? 'bg-slate-400' : ''
                            ]" class="w-3 h-3 rounded-md shrink-0"></span>
                            <span x-text="item.category" class="text-slate-500 truncate"></span>
                        </div>
                        <div class="text-slate-700 shrink-0 pl-2">
                            <span x-text="item.value"></span>
                            <span class="text-slate-400 font-medium ml-1"
                                  x-text="'(' + (stats.totalActiveProjects.raw > 0 ? ((item.value / stats.totalActiveProjects.raw) * 100).toFixed(1) : 0) + '%)'"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </x-chart-card>

</div>

<!-- Charts Grid - Row 2 (Bar & Smooth Line) -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    <!-- Left Card: Total Pegawai per Regional -->
    <x-chart-card title="Total Pegawai per Regional">
        <div class="w-full h-64">
            <canvas id="pegawaiRegionalChart"></canvas>
        </div>
    </x-chart-card>

    <!-- Right Card: Total Tagihan per Bulan -->
    <x-chart-card title="Total Tagihan per Bulan">
        <div class="w-full h-64">
            <canvas id="tagihanBulanChart"></canvas>
        </div>
    </x-chart-card>

</div>

<!-- Charts Grid - Row 3 (Three Columns: 2 Bars, 1 Area) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    <!-- Left Card: Total Cost per Regional -->
    <x-chart-card title="Total Cost per Regional">
        <div class="w-full h-60">
            <canvas id="costRegionalChart"></canvas>
        </div>
    </x-chart-card>

    <!-- Middle Card: Total Cost per Segment -->
    <x-chart-card title="Total Cost per Segment">
        <div class="w-full h-60">
            <canvas id="costSegmentChart"></canvas>
        </div>
    </x-chart-card>

    <!-- Right Card: Trend Cost per Bulan -->
    <x-chart-card title="Trend Cost per Bulan">
        <div class="w-full h-60">
            <canvas id="costBulanChart"></canvas>
        </div>
    </x-chart-card>

</div>
@endsection
