@extends('layouts.app')

@section('title', 'Time Results Details — Tanos ERP')

@section('content')
<div class="space-y-6 w-full">
    <!-- Header Block -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm">
        <div class="flex items-center space-x-3">
            <a href="{{ route('org.periods.index') }}" class="p-2.5 bg-slate-50 hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700/80 text-slate-500 dark:text-slate-400 rounded-lg transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ $period->name }}</h1>
                <p class="text-xs text-slate-400 dark:text-slate-550 font-semibold font-mono">
                    {{ $period->start_date->format('d M Y') }} - {{ $period->end_date->format('d M Y') }} 
                    | Scope: {{ $period->project ? $period->project->project_name : 'Seluruh Karyawan' }}
                </p>
            </div>
        </div>
        
        <div class="flex items-center space-x-2">
            <span class="inline-block px-3 py-1.5 rounded-xl text-xs font-bold {{ $period->status === 'Completed' ? 'bg-emerald-55/10 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-400' : 'bg-amber-55/10 text-amber-600 dark:bg-amber-950/30 dark:text-amber-400' }}">
                Status: {{ $period->status }}
            </span>
            <form action="{{ route('org.periods.calculate', $period->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-sm transition cursor-pointer">
                    🔄 Recalculate
                </button>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl p-6 shadow-sm">
        <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
            <table class="w-full text-left border-collapse min-w-[1000px] align-middle">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                        <th class="p-4">Karyawan (NIPP)</th>
                        <th class="p-4 text-center">Workdays</th>
                        <th class="p-4 text-center">Present</th>
                        <th class="p-4 text-center text-rose-500">Alfa</th>
                        <th class="p-4 text-center text-amber-500">Late</th>
                        <th class="p-4 text-center">Early Out</th>
                        <th class="p-4 text-center text-sky-500 font-bold">Leave/Cuti</th>
                        <th class="p-4 text-center">Lembur (Hrs)</th>
                        <th class="p-4 text-right">Potongan Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm text-slate-600 dark:text-slate-350">
                    @forelse($period->results as $res)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                        <td class="p-4 whitespace-nowrap">
                            <span class="block font-bold text-slate-850 dark:text-slate-250">{{ $res->employee ? $res->employee->name : 'N/A' }}</span>
                            <span class="block text-xs font-semibold text-slate-400 dark:text-slate-550 font-mono mt-0.5">{{ $res->employee ? $res->employee->nipp : '-' }}</span>
                        </td>
                        <td class="p-4 text-center font-bold">
                            {{ $res->workdays }}
                        </td>
                        <td class="p-4 text-center font-bold text-emerald-600 dark:text-emerald-450">
                            {{ $res->present_days }}
                        </td>
                        <td class="p-4 text-center font-bold text-rose-650 dark:text-rose-450">
                            {{ $res->absent_days }}
                        </td>
                        <td class="p-4 text-center font-bold text-amber-600 dark:text-amber-450">
                            {{ $res->late_days }}
                        </td>
                        <td class="p-4 text-center font-bold text-slate-500">
                            {{ $res->early_departure_days }}
                        </td>
                        <td class="p-4 text-center font-bold text-sky-600 dark:text-sky-400">
                            {{ $res->leave_days }}
                        </td>
                        <td class="p-4 text-center font-bold font-mono">
                            {{ $res->overtime_hours }} h
                        </td>
                        <td class="p-4 text-right font-bold font-mono text-rose-650 dark:text-rose-400">
                            Rp {{ number_format($res->deduction_amount, 2, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-12 text-center text-slate-400 text-xs">
                            Hasil evaluasi belum digenerate. Silakan klik tombol "Generate/Evaluate" atau "Recalculate".
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
