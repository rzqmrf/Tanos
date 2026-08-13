<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Bersihkan sisa data lama biar fresh
        Schema::disableForeignKeyConstraints();
        DB::table('projects')->truncate();
        DB::table('employees')->truncate();
        DB::table('invoices')->truncate();
        DB::table('notifications')->truncate();
        DB::table('attendances')->truncate();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        // 2. User Admin & Demo Accounts per Role (Unique Passwords per Role)
        $testUser = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator TANOS',
                'email' => 'admin@tanos.local',
                'password' => bcrypt('Admin#Tanos2026!'),
                'employee_id' => null,
                'role' => 'Admin',
            ]
        );

        User::firstOrCreate(
            ['username' => 'rozaq'],
            [
                'name' => 'Rozaq (Admin)',
                'email' => 'rozaq@tanos.local',
                'password' => bcrypt('Admin#Tanos2026!'),
                'employee_id' => null,
                'role' => 'Admin',
            ]
        );

        User::firstOrCreate(
            ['username' => 'hrmanager'],
            [
                'name' => 'HR Manager Demo',
                'email' => 'hrmanager@tanos.local',
                'password' => bcrypt('HR#Secure2026!'),
                'employee_id' => null,
                'role' => 'HR Manager',
            ]
        );

        User::firstOrCreate(
            ['username' => 'financemanager'],
            [
                'name' => 'Finance Manager Demo',
                'email' => 'financemanager@tanos.local',
                'password' => bcrypt('Finance#Paid2026!'),
                'employee_id' => null,
                'role' => 'Finance Manager',
            ]
        );

        User::firstOrCreate(
            ['username' => 'projectmanager'],
            [
                'name' => 'Project Manager Demo',
                'email' => 'projectmanager@tanos.local',
                'password' => bcrypt('Project#Build2026!'),
                'employee_id' => null,
                'role' => 'Project Manager',
            ]
        );

        $this->call(DashboardSeeder::class);

        // Seed WBS elements for projects to support complete workflow demos
        $projects = \App\Models\Project::all();
        foreach ($projects as $proj) {
            $root = \App\Models\WbsElement::create([
                'project_id' => $proj->id,
                'wbs_code' => 'WBS-' . sprintf('%03d', $proj->id),
                'wbs_name' => 'Penyediaan TAD',
                'wbs_category' => 'Upah Pokok',
                'weight' => 50,
                'expected_start' => now()->startOfMonth(),
                'expected_end' => now()->endOfMonth(),
                'sent_to_sap' => true,
            ]);

            \App\Models\WbsElement::create([
                'project_id' => $proj->id,
                'parent_id' => $root->id,
                'wbs_code' => 'WBS-' . sprintf('%03d', $proj->id) . '.1',
                'wbs_name' => 'Transport Karyawan',
                'wbs_category' => 'Uang Transport',
                'weight' => 30,
                'expected_start' => now()->startOfMonth(),
                'expected_end' => now()->endOfMonth(),
                'sent_to_sap' => true,
            ]);

            \App\Models\WbsElement::create([
                'project_id' => $proj->id,
                'parent_id' => $root->id,
                'wbs_code' => 'WBS-' . sprintf('%03d', $proj->id) . '.2',
                'wbs_name' => 'Upah Lembur',
                'wbs_category' => 'Lembur',
                'weight' => 20,
                'expected_start' => now()->startOfMonth(),
                'expected_end' => now()->endOfMonth(),
                'sent_to_sap' => true,
            ]);
        }

        // truncate 100 orang saja
        $monthsIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $currentMonthStr = $monthsIndo[(int) date('n')].' '.date('Y');

        // Realistic enterprise distribution across regionals
        $regionalsMap = [
            'Regional Jawa' => 385,
            'Regional Jakarta' => 310,
            'Regional Sumatra' => 245,
            'Regional Kalimantan' => 190,
            'Regional Sulawesi' => 165,
            'Regional Bali Nusra' => 120,
        ];
        
        $roles = ['Staff Operasional', 'Staff Administrasi', 'Supervisor'];
        $faker = \Faker\Factory::create('id_ID');

        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Employee::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        foreach ($regionalsMap as $rName => $targetCount) {
            $existing = Employee::where('regional', $rName)->count();
            $needed = $targetCount - $existing;
            for ($i = 0; $i < $needed; $i++) {
                $empName = $faker->name;
                $roleName = $roles[$i % count($roles)];
                Employee::create([
                    'name' => $empName,
                    'role' => $roleName,
                    'month' => $currentMonthStr,
                    'regional' => $rName,
                    'segment' => $faker->randomElement([
                        '01. Tenaga Alih Daya Operasional',
                        '02. Tenaga Alih Daya Pengamanan',
                        '03. Pemborongan Pengamanan',
                        '04. Cleaning Service',
                        '05. Pemeliharaan Taman',
                        '06. Pelayanan Pas',
                        '08. Tenaga Hantaran Kendaraan',
                        '09. Tenaga Operator',
                        '11. Lain Lain',
                        '14. Kebersihan',
                        '15. Operasional'
                    ]),
                    'religion' => $faker->randomElement(['Islam', 'Islam', 'Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha']),
                    'nipp' => 'NIPP-' . $faker->unique()->numberBetween(100000, 999999),
                    'bank_name' => $faker->randomElement(['Bank Mandiri', 'BRI', 'BNI', 'BCA']),
                    'bank_account_number' => $faker->numerify('##########'),
                    'bank_account_name' => $empName,
                    'ptkp_status' => $faker->randomElement(['TK/0', 'TK/1', 'K/0', 'K/1', 'K/2']),
                    'tmt_date' => $faker->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
                    'bpjs_kesehatan_number' => $faker->numerify('#############'),
                    'bpjs_ketenagakerjaan_number' => $faker->numerify('###########'),
                ]);
            }
            Employee::where('regional', $rName)->update(['month' => $currentMonthStr]);
        }
        // ----------------------------------------------------------------------

        // 4. Auto-generate User accounts for ALL Employees
        $usedUsernames = [];
        Employee::orderBy('id')->get()->each(function ($emp) use (&$usedUsernames) {
            $firstName = strtolower(explode(' ', $emp->name)[0]);
            $username = $firstName;
            $counter = 2;
            while (User::where('username', $username)->exists() || in_array($username, $usedUsernames)) {
                $username = $firstName.$counter;
                $counter++;
            }
            $usedUsernames[] = $username;

            User::firstOrCreate(
                ['username' => $username],
                [
                    'name' => $emp->name,
                    'email' => strtolower(str_replace(' ', '', $emp->name)).$emp->id.'@tanos.local',
                    'password' => bcrypt('Tanos#Emp2026!'),
                    'employee_id' => $emp->id,
                    'role' => 'Employee',
                ]
            );
        });

        // 5. Akun demo 'employee' (username: employee, password: Tanos#Emp2026!) — selalu link ke Employee id 1
        $firstEmployee = Employee::find(1);
        if ($firstEmployee) {
            User::firstOrCreate(
                ['username' => 'employee'],
                [
                    'name' => $firstEmployee->name,
                    'email' => 'employee@tanos.local',
                    'password' => bcrypt('Tanos#Emp2026!'),
                    'employee_id' => $firstEmployee->id,
                    'role' => 'Employee',
                ]
            );
        }

        // 6. Auto-generate data absensi hari ini untuk semua employee
        //    (mayoritas Hadir, sebagian Izin/Sakit/Alfa — biar copilot jawab dari data riil)
        $attendanceDate = Carbon::today()->format('Y-m-d');
        $attendanceWeights = [
            'Hadir' => 90,
            'Izin' => 5,
            'Sakit' => 3,
            'Alfa' => 2,
        ];

        $attendancePool = [];
        foreach ($attendanceWeights as $status => $weight) {
            for ($i = 0; $i < $weight; $i++) {
                $attendancePool[] = $status;
            }
        }

        Employee::orderBy('id')->get()->each(function ($emp) use ($attendanceDate, $attendancePool) {
            Attendance::firstOrCreate(
                ['employee_id' => $emp->id, 'date' => $attendanceDate],
                [
                    'status' => $attendancePool[array_rand($attendancePool)],
                    'clock_in' => '08:00:00',
                    'clock_out' => '17:00:00',
                    'overtime_hours' => 0.00,
                    'notes' => 'Auto-generate seeder',
                ]
            );
        });

        // 5. Seed Notifikasi Mock Admin
        Notification::create([
            'user_id' => $testUser->id,
            'title' => 'Invoice baru masuk',
            'message' => 'Invoice #INV-'.date('Y').'-312 dari Proyek Enterprise Jawa Barat telah diterima.',
            'type' => 'invoice',
            'created_at' => now()->subMinutes(2),
        ]);

        Notification::create([
            'user_id' => $testUser->id,
            'title' => 'Project mendekati deadline',
            'message' => 'Proyek "Digitalisasi Sumatera" batas waktu 3 hari lagi.',
            'type' => 'project',
            'created_at' => now()->subHour(),
        ]);

        Notification::create([
            'user_id' => $testUser->id,
            'title' => 'Pegawai baru ditambahkan',
            'message' => 'Budi Santoso telah bergabung di Regional Jawa Tengah.',
            'type' => 'employee',
            'created_at' => now()->subDay(),
        ]);
    }
}
