<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Regional;
use App\Models\Segment;
use App\Models\Division;
use App\Models\JobPosition;
use App\Models\RabBudget;
use App\Models\RabBudgetItem;
use App\Models\EmployeeMovement;
use Carbon\Carbon;

class DashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key constraints to safely fresh seed
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\TimeResult::truncate();
        \App\Models\TimePeriod::truncate();
        \App\Models\TimeEvaluation::truncate();
        \App\Models\ScheduleAssignment::truncate();
        \App\Models\ScheduleGroup::truncate();
        \App\Models\AbsentType::truncate();
        EmployeeMovement::truncate();
        JobPosition::truncate();
        Division::truncate();
        RabBudgetItem::truncate();
        RabBudget::truncate();
        Project::truncate();
        Employee::truncate();
        Invoice::truncate();
        Regional::truncate();
        Segment::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

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

        // Seed Absent Types
        $absents = [
            ['code' => 'CT', 'name' => 'Cuti Tahunan', 'gender' => 'All', 'priority_level' => 1, 'deduction_absent' => 'No', 'valid_from' => '2024-01-01', 'valid_to' => '9999-12-31', 'active' => true],
            ['code' => 'SK', 'name' => 'Sakit (Surat Dokter)', 'gender' => 'All', 'priority_level' => 2, 'deduction_absent' => 'No', 'valid_from' => '2024-01-01', 'valid_to' => '9999-12-31', 'active' => true],
            ['code' => 'CM', 'name' => 'Cuti Melahirkan', 'gender' => 'Female', 'priority_level' => 3, 'deduction_absent' => 'No', 'valid_from' => '2024-01-01', 'valid_to' => '9999-12-31', 'active' => true],
            ['code' => 'IP', 'name' => 'Izin Penting', 'gender' => 'All', 'priority_level' => 4, 'deduction_absent' => 'Yes', 'valid_from' => '2024-01-01', 'valid_to' => '9999-12-31', 'active' => true],
        ];
        foreach ($absents as $ab) {
            \App\Models\AbsentType::create($ab);
        }

        // Seed Schedule Groups
        $regGroup = \App\Models\ScheduleGroup::create([
            'name' => 'Reguler Pelindo (Mon-Fri)',
            'type' => 'Reguler',
            'work_start' => '08:00:00',
            'work_end' => '17:00:00',
            'is_active' => true
        ]);
        
        $shiftGroup = \App\Models\ScheduleGroup::create([
            'name' => 'Shift Security Group A',
            'type' => 'Shift',
            'work_start' => '07:00:00',
            'work_end' => '19:00:00',
            'is_active' => true
        ]);

        // Seed Time Evaluation parameter
        \App\Models\TimeEvaluation::create([
            'name' => 'Aturan Dispensasi Keterlambatan Pelindo',
            'description' => 'Dispensasi 15 menit untuk jam masuk reguler maupun shift.',
            'valid_from' => '2024-01-01',
            'valid_to' => '9999-12-31',
            'late_tolerance_minutes' => 15,
            'early_departure_minutes' => 15,
            'is_active' => true
        ]);

        // 1. Seed Divisions (Unit Kerja STO)
        $divisionsData = [
            [
                'code' => 'DIV-OPS', 
                'name' => 'Divisi Operasional', 
                'description' => 'Mengelola pelayanan TAD operasional di lapangan Pelindo',
                'parent_id' => null,
                'regional' => 'Regional Jawa',
                'cost_center' => 'CC-OPS-01',
                'unit_type' => 'Wilayah',
                'valid_from' => '2024-01-01',
                'valid_to' => '9999-12-31',
                'active' => true,
                'sent_to_sap' => true
            ],
            [
                'code' => 'DIV-FIN-HR', 
                'name' => 'Divisi Keuangan & SDM', 
                'description' => 'Mengelola payroll, billing, administrasi, dan pengembangan SDM',
                'parent_id' => null,
                'regional' => 'Regional Jawa',
                'cost_center' => 'CC-FIN-01',
                'unit_type' => 'Wilayah',
                'valid_from' => '2024-01-01',
                'valid_to' => '9999-12-31',
                'active' => true,
                'sent_to_sap' => true
            ],
            [
                'code' => 'DIV-SEC', 
                'name' => 'Divisi K3 & Pengamanan', 
                'description' => 'Mengelola keselamatan kerja dan sistem keamanan fisik Pelindo',
                'parent_id' => null,
                'regional' => 'Regional Jawa',
                'cost_center' => 'CC-SEC-01',
                'unit_type' => 'Perusahaan User',
                'valid_from' => '2024-01-01',
                'valid_to' => '9999-12-31',
                'active' => true,
                'sent_to_sap' => true
            ],
        ];
        
        $seededDivs = [];
        foreach ($divisionsData as $div) {
            $seededDivs[$div['code']] = Division::create($div);
        }

        // 2. Seed Job Positions (Formasi Jabatan & reporting hierarchies)
        // Ops Hierarchy
        $opsSpv = JobPosition::create([
            'division_id' => $seededDivs['DIV-OPS']->id,
            'parent_id' => null,
            'code' => 'OP-SPV',
            'name' => 'Supervisor Operasional',
            'regional' => 'Regional Jawa',
            'is_leader' => true,
            'cost_center' => 'CC-OPS-SPV',
            'cost_center_name' => 'Ops Supervision',
            'valid_from' => '2024-01-01',
            'valid_to' => '9999-12-31',
            'active' => true,
            'sent_to_sap' => true
        ]);
        
        $opsStaff = JobPosition::create([
            'division_id' => $seededDivs['DIV-OPS']->id,
            'parent_id' => $opsSpv->id,
            'code' => 'OP-STAFF',
            'name' => 'Staff Operasional',
            'regional' => 'Regional Jawa',
            'is_leader' => false,
            'cost_center' => 'CC-OPS-STAFF',
            'cost_center_name' => 'Ops Execution',
            'valid_from' => '2024-01-01',
            'valid_to' => '9999-12-31',
            'active' => true,
            'sent_to_sap' => true
        ]);

        // HR/Finance Hierarchy
        $hrStaff = JobPosition::create([
            'division_id' => $seededDivs['DIV-FIN-HR']->id,
            'parent_id' => null,
            'code' => 'HR-STAFF',
            'name' => 'Staff Administrasi & SDM',
            'regional' => 'Regional Jawa',
            'is_leader' => false,
            'cost_center' => 'CC-HR',
            'cost_center_name' => 'HR Support',
            'valid_from' => '2024-01-01',
            'valid_to' => '9999-12-31',
            'active' => true,
            'sent_to_sap' => true
        ]);
        
        $finStaff = JobPosition::create([
            'division_id' => $seededDivs['DIV-FIN-HR']->id,
            'parent_id' => null,
            'code' => 'FIN-STAFF',
            'name' => 'Staff Keuangan',
            'regional' => 'Regional Jawa',
            'is_leader' => false,
            'cost_center' => 'CC-FIN',
            'cost_center_name' => 'Financial Support',
            'valid_from' => '2024-01-01',
            'valid_to' => '9999-12-31',
            'active' => true,
            'sent_to_sap' => true
        ]);

        // Security Hierarchy
        $secSpv = JobPosition::create([
            'division_id' => $seededDivs['DIV-SEC']->id,
            'parent_id' => null,
            'code' => 'SEC-SPV',
            'name' => 'Supervisor Keamanan',
            'regional' => 'Regional Jawa',
            'is_leader' => true,
            'cost_center' => 'CC-SEC-SPV',
            'cost_center_name' => 'Security Supervision',
            'valid_from' => '2024-01-01',
            'valid_to' => '9999-12-31',
            'active' => true,
            'sent_to_sap' => true
        ]);
        
        $secGuard = JobPosition::create([
            'division_id' => $seededDivs['DIV-SEC']->id,
            'parent_id' => $secSpv->id,
            'code' => 'SEC-GUARD',
            'name' => 'Petugas Keamanan',
            'regional' => 'Regional Jawa',
            'is_leader' => false,
            'cost_center' => 'CC-SEC-GUARD',
            'cost_center_name' => 'Security Protection',
            'valid_from' => '2024-01-01',
            'valid_to' => '9999-12-31',
            'active' => true,
            'sent_to_sap' => true
        ]);

        $jobMapping = [
            'Staff Operasional' => $opsStaff->id,
            'Staff Administrasi' => $hrStaff->id,
            'Supervisor' => $opsSpv->id
        ];

        // 3. Loop over Months/Regionals/Segments to seed Projects, Employees & Invoices
        foreach ($months as $mIdx => $month) {
            foreach ($regionals as $rIdx => $reg) {
                foreach ($segments as $sIdx => $seg) {

                    // Seed projects count
                    $numProjects = (($mIdx + 1) * 3 + ($rIdx * 2) + ($sIdx * 4) + 7) % 12 + 2;

                    for ($k = 0; $k < $numProjects; $k++) {
                        $baseCost = (($mIdx + 1) * 15 + ($rIdx + 1) * 25 + ($sIdx + 1) * 20 + $k * 8) * 1000000;
                        $active = ($k % 8 !== 0);

                        $startDate = $faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d');
                        $endDate = Carbon::parse($startDate)->addYear()->format('Y-m-d');

                        // Create project
                        $proj = Project::create([
                            'project_code' => 'S/PS-2026-' . sprintf('%02d', $mIdx+1) . '-' . sprintf('%02d', $rIdx+1) . '-' . sprintf('%02d', $sIdx+1) . '-' . sprintf('%03d', $k),
                            'project_name' => 'Jasa Penyediaan Tenaga Kerja ' . $seg . ' ' . $reg . ' P-' . ($k+1),
                            'customer_name' => 'PT Pelindo ' . $faker->randomElement(['Terminal Petikemas', 'Multi Terminal', 'Regional ' . ($rIdx+1)]),
                            'contract_number' => 'OA-2026-' . sprintf('%02d', $mIdx+1) . '-' . sprintf('%02d', $rIdx+1) . '-' . sprintf('%03d', $k),
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'cost_center' => 'CC-' . sprintf('%02d', $rIdx+1) . sprintf('%02d', $sIdx+1) . sprintf('%02d', $k),
                            'fund_center' => 'FC-' . sprintf('%02d', $rIdx+1) . sprintf('%02d', $sIdx+1) . sprintf('%02d', $k),
                            'month' => $month,
                            'regional' => $reg,
                            'segment' => $seg,
                            'cost' => $baseCost,
                            'active' => $active
                        ]);

                        // Seed RAB Budget for the project
                        $totalRev = $proj->cost * 1.15;
                        $totalCost = $proj->cost;
                        $rab = RabBudget::create([
                            'project_id' => $proj->id,
                            'document_number' => 'RAB-' . sprintf('%04d', $proj->id) . '-' . date('Y'),
                            'name' => 'Anggaran Biaya ' . $proj->project_name,
                            'year' => date('Y'),
                            'total_revenue' => $totalRev,
                            'total_cost' => $totalCost,
                            'sap_status' => ($k % 3 === 0) ? 'Sent' : 'Draft',
                            'doc_status' => ($k % 3 === 0) ? 'Approved' : 'Draft'
                        ]);

                        // Create monthly allocation items
                        // Revenue item
                        RabBudgetItem::create([
                            'rab_budget_id' => $rab->id,
                            'coa_code' => '410100',
                            'fund_center' => $proj->fund_center,
                            'cost_center' => $proj->cost_center,
                            'profit_center' => 'PC-9901',
                            'jan' => $totalRev / 12, 'feb' => $totalRev / 12, 'mar' => $totalRev / 12, 
                            'apr' => $totalRev / 12, 'may' => $totalRev / 12, 'jun' => $totalRev / 12,
                            'jul' => $totalRev / 12, 'aug' => $totalRev / 12, 'sep' => $totalRev / 12,
                            'oct' => $totalRev / 12, 'nov' => $totalRev / 12, 'dec' => $totalRev / 12,
                            'total_amount' => $totalRev
                        ]);

                        // Cost item
                        RabBudgetItem::create([
                            'rab_budget_id' => $rab->id,
                            'coa_code' => '510100',
                            'fund_center' => $proj->fund_center,
                            'cost_center' => $proj->cost_center,
                            'profit_center' => 'PC-9901',
                            'jan' => $totalCost / 12, 'feb' => $totalCost / 12, 'mar' => $totalCost / 12, 
                            'apr' => $totalCost / 12, 'may' => $totalCost / 12, 'jun' => $totalCost / 12,
                            'jul' => $totalCost / 12, 'aug' => $totalCost / 12, 'sep' => $totalCost / 12,
                            'oct' => $totalCost / 12, 'nov' => $totalCost / 12, 'dec' => $totalCost / 12,
                            'total_amount' => $totalCost
                        ]);
                    }

                    // 4. Seed Employees and ECN Movements
                    $roles = ['Staff Operasional', 'Staff Administrasi', 'Supervisor'];
                    $numPegawai = (($mIdx + 1) * 6 + ($rIdx * 12) + ($sIdx * 8) + 15) % 35 + 5;
                    for ($k = 0; $k < $numPegawai; $k++) {
                        $empName = $faker->name;
                        $roleName = $roles[$k % count($roles)];
                        $jPosId = $jobMapping[$roleName] ?? $opsStaff->id;

                        $employee = Employee::create([
                            'name' => $empName,
                            'role' => $roleName,
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
                            'project_id' => isset($proj) ? $proj->id : null,
                            'job_position_id' => $jPosId
                        ]);

                        // Assign schedule assignment group
                        \App\Models\ScheduleAssignment::create([
                            'employee_id' => $employee->id,
                            'schedule_group_id' => ($roleName === 'Supervisor') ? $shiftGroup->id : $regGroup->id,
                            'valid_from' => '2024-01-01',
                            'valid_to' => '2027-12-31'
                        ]);

                        // Seed active ECN career movements randomly
                        if ($k % 8 === 0 && isset($proj)) {
                            EmployeeMovement::create([
                                'ecn_name' => 'SK Penugasan / Mutasi ' . $employee->name,
                                'employee_id' => $employee->id,
                                'movement_type' => 'Mutation',
                                'status' => 'Completed',
                                'from_position_id' => $opsStaff->id,
                                'to_position_id' => $opsSpv->id,
                                'from_project_id' => null,
                                'to_project_id' => $proj->id,
                                'reference_number' => 'SK-MUT-' . sprintf('%04d', $employee->id) . '-' . date('Y'),
                                'effective_date' => date('Y-m-d'),
                                'valid_from' => date('Y-m-d'),
                                'valid_to' => '9999-12-31',
                                'sent_to_sap' => true
                            ]);
                        }
                    }

                    // 5. Seed Invoices (Nota Billings)
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
