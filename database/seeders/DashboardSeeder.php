<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Regional;
use App\Models\Segment;

class DashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. DEKLARASI VARIABEL YANG HILANG (dinamis berdasarkan kalender real)
        $months = \App\Services\DashboardService::generateLast6Months();
        $faker = \Faker\Factory::create('id_ID');

        $regionals = [
            'Regional 1', 'Regional 2', 'Regional 3', 'Regional 4'
        ];

        $segments = [
            'Enterprise', 'Corporate', 'Government', 'SME', 'Retail'
        ];

        foreach ($regionals as $reg) {
            Regional::firstOrCreate(['name' => $reg]);
        }

        foreach ($segments as $seg) {
            Segment::firstOrCreate(['name' => $seg]);
        }

        // Seed Divisions and Job Positions
        $divisionsData = [
            ['name' => 'Divisi Operasional', 'description' => 'Mengelola pelayanan TAD operasional di lapangan'],
            ['name' => 'Divisi Keuangan & SDM', 'description' => 'Mengelola payroll, billing, administrasi, dan pengembangan SDM'],
            ['name' => 'Divisi K3 & Pengamanan', 'description' => 'Mengelola keselamatan kerja dan sistem keamanan fisik Pelindo'],
        ];
        foreach ($divisionsData as $div) {
            $createdDiv = \App\Models\Division::firstOrCreate(['name' => $div['name']], $div);
            
            // Seed Job Positions for each division
            if ($div['name'] === 'Divisi Operasional') {
                \App\Models\JobPosition::firstOrCreate(['code' => 'OP-STAFF'], ['division_id' => $createdDiv->id, 'name' => 'Staff Operasional']);
                \App\Models\JobPosition::firstOrCreate(['code' => 'OP-SPV'], ['division_id' => $createdDiv->id, 'name' => 'Supervisor Operasional']);
            } elseif ($div['name'] === 'Divisi Keuangan & SDM') {
                \App\Models\JobPosition::firstOrCreate(['code' => 'FIN-STAFF'], ['division_id' => $createdDiv->id, 'name' => 'Staff Keuangan']);
                \App\Models\JobPosition::firstOrCreate(['code' => 'HR-STAFF'], ['division_id' => $createdDiv->id, 'name' => 'Staff Administrasi & SDM']);
            } else {
                \App\Models\JobPosition::firstOrCreate(['code' => 'SEC-GUARD'], ['division_id' => $createdDiv->id, 'name' => 'Petugas Keamanan']);
                \App\Models\JobPosition::firstOrCreate(['code' => 'SEC-SPV'], ['division_id' => $createdDiv->id, 'name' => 'Supervisor Keamanan']);
            }
        }

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
                    $roles = ['Staff Operasional', 'Staff Administrasi', 'Supervisor'];
                    $numPegawai = (($mIdx + 1) * 6 + ($rIdx * 12) + ($sIdx * 8) + 15) % 35 + 5;
                    for ($k = 0; $k < $numPegawai; $k++) {
                        $empName = $faker->name;
                        Employee::create([
                            'name' => $empName,
                            'role' => $roles[$k % count($roles)],
                            'month' => $month,
                            'regional' => $reg,
                            'segment' => $seg,
                            'nipp' => 'NIPP-' . $faker->unique()->numberBetween(100000, 999999),
                            'bank_name' => $faker->randomElement(['Bank Mandiri', 'BRI', 'BNI', 'BCA']),
                            'bank_account_number' => $faker->numerify('##########'),
                            'bank_account_name' => $empName,
                            'ptkp_status' => $faker->randomElement(['TK/0', 'TK/1', 'K/0', 'K/1', 'K/2']),
                            'tmt_date' => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                            'bpjs_kesehatan_number' => $faker->numerify('#############'),
                            'bpjs_ketenagakerjaan_number' => $faker->numerify('###########'),
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
