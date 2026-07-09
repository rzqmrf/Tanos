<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Define filter choices
    private array $months = [
        'Januari 2025', 'Februari 2025', 'Maret 2025', 'April 2025', 'Mei 2025', 'Juni 2025'
    ];

    private array $regionals = [
        'Jawa Barat', 'Jawa Timur', 'Jawa Tengah', 'Sumatera', 'Kalimantan'
    ];

    private array $segments = [
        'Enterprise', 'Corporate', 'Government', 'SME', 'Retail'
    ];

    /**
     * Display the dashboard home.
     */
    public function index()
    {
        // Redirect to login if user is not in session
        if (!session()->has('user')) {
            return redirect()->route('login');
        }

        // Default initial filters
        $defaultMonth = 'Juni 2025';
        $defaultRegional = 'All';
        $defaultSegment = 'All';

        $data = $this->calculateDashboardData($defaultMonth, $defaultRegional, $defaultSegment);

        return view('dashboard.index', [
            'months' => $this->months,
            'regionals' => $this->regionals,
            'segments' => $this->segments,
            'currentMonth' => $defaultMonth,
            'currentRegional' => $defaultRegional,
            'currentSegment' => $defaultSegment,
            'initialData' => $data
        ]);
    }

    /**
     * API endpoint to get filtered dashboard data.
     */
    public function apiData(Request $request)
    {
        // Return 401 Unauthorized for API requests if not logged in
        if (!session()->has('user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $month = $request->query('month', 'Juni 2025');
        $regional = $request->query('regional', 'All');
        $segment = $request->query('segment', 'All');

        $data = $this->calculateDashboardData($month, $regional, $segment);

        return response()->json($data);
    }

    /**
     * Generate mock projects (represents a Projects DB table).
     */
    private function getMockProjects(): array
    {
        $projects = [];
        $id = 1;

        foreach ($this->months as $mIdx => $month) {
            foreach ($this->regionals as $rIdx => $reg) {
                foreach ($this->segments as $sIdx => $seg) {
                    // Seed-based deterministic number of projects to make graphs look realistic
                    $numProjects = (($mIdx + 1) * 3 + ($rIdx * 2) + ($sIdx * 4) + 7) % 12 + 2;

                    for ($k = 0; $k < $numProjects; $k++) {
                        // Project cost in Rp (e.g. 5M - 200M)
                        $baseCost = (($mIdx + 1) * 15 + ($rIdx + 1) * 25 + ($sIdx + 1) * 20 + $k * 8) * 1000000;
                        $active = ($k % 8 !== 0); // ~87.5% active

                        $projects[] = [
                            'id' => $id++,
                            'month' => $month,
                            'regional' => $reg,
                            'segment' => $seg,
                            'cost' => $baseCost,
                            'active' => $active
                        ];
                    }
                }
            }
        }
        return $projects;
    }

    /**
     * Generate mock employees (represents an Employees DB table).
     */
    private function getMockPegawai(): array
    {
        $pegawai = [];
        $id = 1;

        foreach ($this->months as $mIdx => $month) {
            foreach ($this->regionals as $rIdx => $reg) {
                foreach ($this->segments as $sIdx => $seg) {
                    $numPegawai = (($mIdx + 1) * 6 + ($rIdx * 12) + ($sIdx * 8) + 15) % 35 + 5;

                    for ($k = 0; $k < $numPegawai; $k++) {
                        $pegawai[] = [
                            'id' => $id++,
                            'month' => $month,
                            'regional' => $reg,
                            'segment' => $seg
                        ];
                    }
                }
            }
        }
        return $pegawai;
    }

    /**
     * Generate mock invoices/transactions (represents a billing DB table).
     */
    private function getMockNota(): array
    {
        $nota = [];
        $id = 1;

        foreach ($this->months as $mIdx => $month) {
            foreach ($this->regionals as $rIdx => $reg) {
                foreach ($this->segments as $sIdx => $seg) {
                    // Nota P2P
                    $numP2P = (($mIdx + 1) * 4 + ($rIdx * 6) + ($sIdx * 5) + 10) % 20 + 3;
                    for ($k = 0; $k < $numP2P; $k++) {
                        $nota[] = [
                            'id' => $id++,
                            'type' => 'P2P',
                            'month' => $month,
                            'regional' => $reg,
                            'segment' => $seg,
                            'amount' => (($mIdx + 1) * 6 + ($rIdx + 1) * 10 + ($sIdx + 1) * 5 + $k * 3) * 1000000
                        ];
                    }

                    // Nota Non P2P
                    $numNonP2P = (($mIdx + 1) * 2 + ($rIdx * 4) + ($sIdx * 3) + 7) % 15 + 2;
                    for ($k = 0; $k < $numNonP2P; $k++) {
                        $nota[] = [
                            'id' => $id++,
                            'type' => 'Non P2P',
                            'month' => $month,
                            'regional' => $reg,
                            'segment' => $seg,
                            'amount' => (($mIdx + 1) * 10 + ($rIdx + 1) * 15 + ($sIdx + 1) * 7 + $k * 4) * 1000000
                        ];
                    }
                }
            }
        }
        return $nota;
    }

    /**
     * Core function to filter and aggregate dashboard datasets.
     */
    private function calculateDashboardData(string $selectedMonth, string $selectedReg, string $selectedSeg): array
    {
        $projects = $this->getMockProjects();
        $pegawai = $this->getMockPegawai();
        $nota = $this->getMockNota();

        // 1. Define Filter Function
        $filterFn = function ($item) use ($selectedMonth, $selectedReg, $selectedSeg) {
            if ($selectedMonth !== 'All' && $item['month'] !== $selectedMonth) {
                return false;
            }
            if ($selectedReg !== 'All' && $item['regional'] !== $selectedReg) {
                return false;
            }
            if ($selectedSeg !== 'All' && $item['segment'] !== $selectedSeg) {
                return false;
            }
            return true;
        };

        // 2. Filter Collections
        $filteredProjects = array_filter($projects, $filterFn);
        $filteredPegawai = array_filter($pegawai, $filterFn);
        $filteredNota = array_filter($nota, $filterFn);

        // 3. Calculate Core Summary Values
        $activeProjects = array_filter($filteredProjects, function ($p) {
            return $p['active'];
        });
        $totalActive = count($activeProjects);
        $totalPegawai = count($filteredPegawai);

        $p2pNotas = array_filter($filteredNota, function ($n) {
            return $n['type'] === 'P2P';
        });
        $totalP2P = count($p2pNotas);

        $nonP2PNotas = array_filter($filteredNota, function ($n) {
            return $n['type'] === 'Non P2P';
        });
        $totalNonP2P = count($nonP2PNotas);

        $totalCost = array_reduce($filteredProjects, function ($carry, $p) {
            return $carry + $p['cost'];
        }, 0);

        $avgCost = $totalActive > 0 ? ($totalCost / $totalActive) : 0;

        // 4. Calculate Growth Rates (Compare selectedMonth vs previousMonth)
        $growth = $this->calculateGrowth($projects, $pegawai, $nota, $selectedMonth, $selectedReg, $selectedSeg, $totalActive, $totalPegawai, $totalP2P, $totalNonP2P, $totalCost, $avgCost);

        // 5. Aggregate Chart Data
        // Doughnut 1: Projects per Segment
        $chartProjectsPerSegment = [];
        foreach ($this->segments as $seg) {
            $chartProjectsPerSegment[] = [
                'category' => $seg,
                'value' => count(array_filter($filteredProjects, function ($p) use ($seg) {
                    return $p['active'] && $p['segment'] === $seg;
                }))
            ];
        }

        // Doughnut 2: Projects per Regional
        $chartProjectsPerRegional = [];
        foreach ($this->regionals as $reg) {
            $chartProjectsPerRegional[] = [
                'category' => $reg,
                'value' => count(array_filter($filteredProjects, function ($p) use ($reg) {
                    return $p['active'] && $p['regional'] === $reg;
                }))
            ];
        }

        // Bar 1: Pegawai per Regional
        $chartPegawaiPerRegional = [];
        foreach ($this->regionals as $reg) {
            $chartPegawaiPerRegional[] = [
                'category' => $reg,
                'value' => count(array_filter($filteredPegawai, function ($emp) use ($reg) {
                    return $emp['regional'] === $reg;
                }))
            ];
        }

        // Line 1: Tagihan per Bulan (Spans all months, filtered by Regional and Segment)
        $chartTagihanPerBulan = [];
        foreach ($this->months as $m) {
            $mFilterFn = function ($n) use ($m, $selectedReg, $selectedSeg) {
                if ($n['month'] !== $m) return false;
                if ($selectedReg !== 'All' && $n['regional'] !== $selectedReg) return false;
                if ($selectedSeg !== 'All' && $n['segment'] !== $selectedSeg) return false;
                return true;
            };
            $mNotas = array_filter($nota, $mFilterFn);
            $sum = array_reduce($mNotas, function ($carry, $n) { return $carry + $n['amount']; }, 0);
            $chartTagihanPerBulan[] = [
                'category' => substr($m, 0, 3), // e.g. "Jun"
                'value' => $sum
            ];
        }

        // Bar 2: Cost per Regional
        $chartCostPerRegional = [];
        foreach ($this->regionals as $reg) {
            $sum = array_reduce(array_filter($filteredProjects, function ($p) use ($reg) {
                return $p['regional'] === $reg;
            }), function ($carry, $p) { return $carry + $p['cost']; }, 0);
            $chartCostPerRegional[] = [
                'category' => $reg,
                'value' => $sum
            ];
        }

        // Bar 3: Cost per Segment
        $chartCostPerSegment = [];
        foreach ($this->segments as $seg) {
            $sum = array_reduce(array_filter($filteredProjects, function ($p) use ($seg) {
                return $p['segment'] === $seg;
            }), function ($carry, $p) { return $carry + $p['cost']; }, 0);
            $chartCostPerSegment[] = [
                'category' => $seg,
                'value' => $sum
            ];
        }

        // Area 1: Trend Cost per Bulan (Spans all months, filtered by Regional and Segment)
        $chartCostPerBulan = [];
        foreach ($this->months as $m) {
            $mFilterFn = function ($p) use ($m, $selectedReg, $selectedSeg) {
                if ($p['month'] !== $m) return false;
                if ($selectedReg !== 'All' && $p['regional'] !== $selectedReg) return false;
                if ($selectedSeg !== 'All' && $p['segment'] !== $selectedSeg) return false;
                return true;
            };
            $mProjects = array_filter($projects, $mFilterFn);
            $sum = array_reduce($mProjects, function ($carry, $p) { return $carry + $p['cost']; }, 0);
            $chartCostPerBulan[] = [
                'category' => substr($m, 0, 3), // e.g. "Jun"
                'value' => $sum
            ];
        }

        return [
            'stats' => [
                'totalActiveProjects' => [
                    'raw' => $totalActive,
                    'formatted' => number_format($totalActive, 0, ',', '.'),
                    'growth' => $growth['activeProjects']
                ],
                'jumlahPegawai' => [
                    'raw' => $totalPegawai,
                    'formatted' => number_format($totalPegawai, 0, ',', '.'),
                    'growth' => $growth['pegawai']
                ],
                'notaP2P' => [
                    'raw' => $totalP2P,
                    'formatted' => number_format($totalP2P, 0, ',', '.'),
                    'growth' => $growth['p2p']
                ],
                'notaNonP2P' => [
                    'raw' => $totalNonP2P,
                    'formatted' => number_format($totalNonP2P, 0, ',', '.'),
                    'growth' => $growth['nonP2p']
                ],
                'totalCost' => [
                    'raw' => $totalCost,
                    'formatted' => $this->formatRupiahDisplay($totalCost),
                    'growth' => $growth['totalCost']
                ],
                'avgCost' => [
                    'raw' => $avgCost,
                    'formatted' => $this->formatRupiahDisplay($avgCost),
                    'growth' => $growth['avgCost']
                ]
            ],
            'charts' => [
                'projectsPerSegment' => $chartProjectsPerSegment,
                'projectsPerRegional' => $chartProjectsPerRegional,
                'pegawaiPerRegional' => $chartPegawaiPerRegional,
                'tagihanPerBulan' => $chartTagihanPerBulan,
                'costPerRegional' => $chartCostPerRegional,
                'costPerSegment' => $chartCostPerSegment,
                'costPerBulan' => $chartCostPerBulan
            ]
        ];
    }

    /**
     * Calculate growth rate compared to previous month.
     */
    private function calculateGrowth(
        array $projects,
        array $pegawai,
        array $nota,
        string $selectedMonth,
        string $selectedReg,
        string $selectedSeg,
        int $currActive,
        int $currPegawai,
        int $currP2P,
        int $currNonP2P,
        float $currCost,
        float $currAvgCost
    ): array {
        // Fallback default values
        $growth = [
            'activeProjects' => 8.2,
            'pegawai' => 5.4,
            'p2p' => 3.7,
            'nonP2p' => 6.1,
            'totalCost' => -2.3,
            'avgCost' => -1.8
        ];

        if ($selectedMonth === 'All') {
            return $growth;
        }

        $prevMonthName = $this->getPreviousMonthName($selectedMonth);
        if (!$prevMonthName) {
            return $growth;
        }

        // Previous month filter function
        $prevFilterFn = function ($item) use ($prevMonthName, $selectedReg, $selectedSeg) {
            if ($item['month'] !== $prevMonthName) {
                return false;
            }
            if ($selectedReg !== 'All' && $item['regional'] !== $selectedReg) {
                return false;
            }
            if ($selectedSeg !== 'All' && $item['segment'] !== $selectedSeg) {
                return false;
            }
            return true;
        };

        $prevProjects = array_filter($projects, $prevFilterFn);
        $prevPegawai = array_filter($pegawai, $prevFilterFn);
        $prevNota = array_filter($nota, $prevFilterFn);

        $prevActiveProjects = array_filter($prevProjects, function ($p) { return $p['active']; });
        $prevActiveCount = count($prevActiveProjects);
        $prevPegawaiCount = count($prevPegawai);

        $prevP2PCount = count(array_filter($prevNota, function ($n) { return $n['type'] === 'P2P'; }));
        $prevNonP2PCount = count(array_filter($prevNota, function ($n) { return $n['type'] === 'Non P2P'; }));

        $prevTotalCost = array_reduce($prevProjects, function ($carry, $p) { return $carry + $p['cost']; }, 0);
        $prevAvgCost = $prevActiveCount > 0 ? ($prevTotalCost / $prevActiveCount) : 0;

        $calcPct = function ($curr, $prev) {
            if ($prev == 0) return $curr > 0 ? 100.0 : 0.0;
            return round((($curr - $prev) / $prev) * 100, 1);
        };

        return [
            'activeProjects' => $calcPct($currActive, $prevActiveCount),
            'pegawai' => $calcPct($currPegawai, $prevPegawaiCount),
            'p2p' => $calcPct($currP2P, $prevP2PCount),
            'nonP2p' => $calcPct($currNonP2P, $prevNonP2PCount),
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
            return 'Rp' . number_format($value / 1000000000, 2, ',', '.') . ' M';
        } elseif ($value >= 1000000) {
            return 'Rp' . number_format($value / 1000000, 2, ',', '.') . ' Jt';
        } else {
            return 'Rp' . number_format($value, 0, ',', '.');
        }
    }
}
