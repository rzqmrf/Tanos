<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Employee;
use App\Models\Invoice;

class DashboardService
{
    // filter / sorting
    private array $months = [];

    private array $regionals = [
        'Regional 1', 'Regional 2', 'Regional 3', 'Regional 4'
    ];

    private array $segments = [
        'Enterprise', 'Corporate', 'Government', 'SME', 'Retail'
    ];

    public function __construct()
    {
        $this->months = self::generateLast6Months();
    }

    public static function generateLast6Months(): array
    {
        $monthsIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $time = strtotime("-$i months");
            $monthNum = (int)date('n', $time);
            $year = date('Y', $time);
            $months[] = $monthsIndo[$monthNum] . ' ' . $year;
        }
        
        return $months;
    }

    public function getMonths(): array
    {
        return $this->months;
    }

    public function getRegionals(): array
    {
        return $this->regionals;
    }

    public function getSegments(): array
    {
        return $this->segments;
    }

    /**
     * Core function to filter and aggregate dashboard datasets from database.
     */
    public function calculateDashboardData(string $selectedMonth, string $selectedReg, string $selectedSeg): array
    {
        $filter = function ($query) use ($selectedMonth, $selectedReg, $selectedSeg) {
            return $query->when($selectedMonth !== 'All', fn($q) => $q->where('month', $selectedMonth))
                ->when($selectedReg !== 'All', fn($q) => $q->where('regional', $selectedReg))
                ->when($selectedSeg !== 'All', fn($q) => $q->where('segment', $selectedSeg));
        };

        $projectQuery = $filter(Project::query());
        $employeeQuery = $filter(Employee::query());
        $invoiceQuery = $filter(Invoice::query());

        $totalActive = (clone $projectQuery)->where('active', true)->count();
        $totalPegawai = $employeeQuery->count();
        $totalP2P = (clone $invoiceQuery)->where('type', 'P2P')->sum('amount');
        $totalNonP2P = (clone $invoiceQuery)->where('type', 'Non P2P')->sum('amount');
        $totalCost = (clone $projectQuery)->sum('cost');
        $avgCost = $totalActive > 0 ? ($totalCost / $totalActive) : 0;

        $growth = $this->calculateGrowth($selectedMonth, $selectedReg, $selectedSeg, $totalActive, $totalPegawai, $totalP2P, $totalNonP2P, $totalCost, $avgCost);

        // Queries for charts ignoring segment/regional filters depending on the chart dimension
        $projectQueryNoSeg = Project::query()
            ->when($selectedMonth !== 'All', fn($q) => $q->where('month', $selectedMonth))
            ->when($selectedReg !== 'All', fn($q) => $q->where('regional', $selectedReg));

        $projectQueryNoReg = Project::query()
            ->when($selectedMonth !== 'All', fn($q) => $q->where('month', $selectedMonth))
            ->when($selectedSeg !== 'All', fn($q) => $q->where('segment', $selectedSeg));

        $employeeQueryNoReg = Employee::query()
            ->when($selectedMonth !== 'All', fn($q) => $q->where('month', $selectedMonth))
            ->when($selectedSeg !== 'All', fn($q) => $q->where('segment', $selectedSeg));

        return [
            'stats' => [
                'totalActiveProjects' => ['raw' => $totalActive, 'formatted' => number_format($totalActive, 0, ',', '.'), 'growth' => $growth['activeProjects']],
                'jumlahPegawai' => ['raw' => $totalPegawai, 'formatted' => number_format($totalPegawai, 0, ',', '.'), 'growth' => $growth['pegawai']],
                'notaP2P' => ['raw' => $totalP2P, 'formatted' => $this->formatRupiahDisplay($totalP2P), 'growth' => $growth['p2p']],
                'notaNonP2P' => ['raw' => $totalNonP2P, 'formatted' => $this->formatRupiahDisplay($totalNonP2P), 'growth' => $growth['nonP2p']],
                'totalCost' => ['raw' => $totalCost, 'formatted' => $this->formatRupiahDisplay($totalCost), 'growth' => $growth['totalCost']],
                'avgCost' => ['raw' => $avgCost, 'formatted' => $this->formatRupiahDisplay($avgCost), 'growth' => $growth['avgCost']]
            ],
            'charts' => [
                'projectsPerSegment' => collect($this->segments)->map(fn($seg) => ['category' => $seg, 'value' => (clone $projectQueryNoSeg)->where('active', true)->where('segment', $seg)->count()])->all(),
                'projectsPerRegional' => collect($this->regionals)->map(fn($reg) => ['category' => $reg, 'value' => (clone $projectQueryNoReg)->where('active', true)->where('regional', $reg)->count()])->all(),
                'pegawaiPerRegional' => collect($this->regionals)->map(fn($reg) => ['category' => $reg, 'value' => (clone $employeeQueryNoReg)->where('regional', $reg)->count()])->all(),
                'tagihanPerBulan' => collect($this->months)->map(fn($m) => [
                    'category' => substr($m, 0, 3),
                    'value' => Invoice::where('month', $m)
                        ->when($selectedReg !== 'All', fn($q) => $q->where('regional', $selectedReg))
                        ->when($selectedSeg !== 'All', fn($q) => $q->where('segment', $selectedSeg))
                        ->sum('amount')
                ])->all(),
                'costPerRegional' => collect($this->regionals)->map(fn($reg) => ['category' => $reg, 'value' => (clone $projectQueryNoReg)->where('regional', $reg)->sum('cost')])->all(),
                'costPerSegment' => collect($this->segments)->map(fn($seg) => ['category' => $seg, 'value' => (clone $projectQueryNoSeg)->where('segment', $seg)->sum('cost')])->all(),
                'costPerBulan' => collect($this->months)->map(fn($m) => [
                    'category' => substr($m, 0, 3),
                    'value' => Project::where('month', $m)
                        ->when($selectedReg !== 'All', fn($q) => $q->where('regional', $selectedReg))
                        ->when($selectedSeg !== 'All', fn($q) => $q->where('segment', $selectedSeg))
                        ->sum('cost')
                ])->all()
            ]
        ];
    }

    /**
     * Calculate growth rate compared to previous month from database.
     */
    private function calculateGrowth(
        string $selectedMonth,
        string $selectedReg,
        string $selectedSeg,
        int $currActive,
        int $currPegawai,
        float $currP2P,
        float $currNonP2P,
        float $currCost,
        float $currAvgCost
    ): array {
        // fallback nilai default
        $growth = [
            'activeProjects' => 8.2, 'pegawai' => 5.4, 'p2p' => 3.7, 'nonP2p' => 6.1, 'totalCost' => -2.3, 'avgCost' => -1.8
        ];

        if ($selectedMonth === 'All') {
            return $growth;
        }

        $prevMonthName = $this->getPreviousMonthName($selectedMonth);
        if (!$prevMonthName) {
            return $growth;
        }

        // query data dr bulan sebelumnya
        $filter = function ($query) use ($prevMonthName, $selectedReg, $selectedSeg) {
            return $query->where('month', $prevMonthName)
                ->when($selectedReg !== 'All', fn($q) => $q->where('regional', $selectedReg))
                ->when($selectedSeg !== 'All', fn($q) => $q->where('segment', $selectedSeg));
        };

        $prevActiveCount = $filter(Project::where('active', true))->count();
        $prevPegawaiCount = $filter(Employee::query())->count();
        $prevP2PAmount = $filter(Invoice::where('type', 'P2P'))->sum('amount');
        $prevNonP2PAmount = $filter(Invoice::where('type', 'Non P2P'))->sum('amount');
        $prevTotalCost = $filter(Project::query())->sum('cost');
        $prevAvgCost = $prevActiveCount > 0 ? ($prevTotalCost / $prevActiveCount) : 0;

        $calcPct = fn($curr, $prev) => $prev == 0 ? ($curr > 0 ? 100.0 : 0.0) : round((($curr - $prev) / $prev) * 100, 1);

        return [
            'activeProjects' => $calcPct($currActive, $prevActiveCount),
            'pegawai' => $calcPct($currPegawai, $prevPegawaiCount),
            'p2p' => $calcPct($currP2P, $prevP2PAmount),
            'nonP2p' => $calcPct($currNonP2P, $prevNonP2PAmount),
            'totalCost' => $calcPct($currCost, $prevTotalCost),
            'avgCost' => $calcPct($currAvgCost, $prevAvgCost)
        ];
    }

    /**
     * Get previous month name based on the lists.
     */
    private function getPreviousMonthName(string $monthName): ?string
    {
        $key = array_search($monthName, $this->months);
        if ($key !== false && $key > 0) {
            return $this->months[$key - 1];
        }
        return null;
    }

    /**
     * Format values into standard Indonesian business display formats (e.g. 48,75 M, 39,17 Jt).
     */
    private function formatRupiahDisplay(float $value): string
    {
        if ($value >= 1000000000) {
            return 'Rp ' . number_format($value / 1000000000, 2, ',', '.') . ' M';
        } elseif ($value >= 1000000) {
            return 'Rp ' . number_format($value / 1000000, 2, ',', '.') . ' Jt';
        } else {
            return 'Rp ' . number_format($value, 0, ',', '.');
        }
    }
}
