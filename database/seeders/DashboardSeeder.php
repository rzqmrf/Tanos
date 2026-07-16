<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Invoice;

class DashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. DEKLARASI VARIABEL YANG HILANG
        $months = [
            'Januari 2025', 'Februari 2025', 'Maret 2025', 'April 2025', 'Mei 2025', 'Juni 2025'
        ];

        $regionals = [
            'Regional 1', 'Regional 2', 'Regional 3', 'Regional 4'
        ];

        $segments = [
            'Enterprise', 'Corporate', 'Government', 'SME', 'Retail'
        ];

        // 2. LOOPING UNTUK MENGISI TABEL PROJECTS
        foreach ($months as $mIdx => $month) {
            foreach ($regionals as $rIdx => $reg) {
                foreach ($segments as $sIdx => $seg) {

                    // Seed-based deterministic number of projects
                    $numProjects = (($mIdx + 1) * 3 + ($rIdx * 2) + ($sIdx * 4) + 7) % 12 + 2;

                    for ($k = 0; $k < $numProjects; $k++) {
                        $baseCost = (($mIdx + 1) * 15 + ($rIdx + 1) * 25 + ($sIdx + 1) * 20 + $k * 8) * 1000000;
                        $active = ($k % 8 !== 0);

                        Project::create([
                            'month' => $month,
                            'regional' => $reg,
                            'segment' => $seg,
                            'cost' => $baseCost,
                            'active' => $active
                        ]);
                    }

                    // 3. LOOPING UNTUK MENGISI TABEL EMPLOYEES
                    $numPegawai = (($mIdx + 1) * 6 + ($rIdx * 12) + ($sIdx * 8) + 15) % 35 + 5;
                    for ($k = 0; $k < $numPegawai; $k++) {
                        Employee::create([
                            'month' => $month,
                            'regional' => $reg,
                            'segment' => $seg
                            
                        ]);
                    }

                    // 4. LOOPING UNTUK MENGISI TABEL INVOICES (NOTA)
                    // Nota P2P
                    $numP2P = (($mIdx + 1) * 4 + ($rIdx * 6) + ($sIdx * 5) + 10) % 20 + 3;
                    for ($k = 0; $k < $numP2P; $k++) {
                        Invoice::create([
                            'type' => 'P2P',
                            'month' => $month,
                            'regional' => $reg,
                            'segment' => $seg,
                            'amount' => (($mIdx + 1) * 6 + ($rIdx + 1) * 10 + ($sIdx + 1) * 5 + $k * 3) * 1000000
                        ]);
                    }

                    // Nota Non P2P
                    $numNonP2P = (($mIdx + 1) * 2 + ($rIdx * 4) + ($sIdx * 3) + 7) % 15 + 2;
                    for ($k = 0; $k < $numNonP2P; $k++) {
                        Invoice::create([
                            'type' => 'Non P2P',
                            'month' => $month,
                            'regional' => $reg,
                            'segment' => $seg,
                            'amount' => (($mIdx + 1) * 10 + ($rIdx + 1) * 15 + ($sIdx + 1) * 7 + $k * 4) * 1000000
                        ]);
                    }

                }
            }
        }
    }
}
