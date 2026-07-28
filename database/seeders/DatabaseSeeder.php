<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
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

        // 2. User Admin buat Login Demo
        $testUser = User::firstOrCreate(
            ['username' => 'rozaq'],
            [
                'name'        => 'Test User',
                'email'       => 'test@example.com',
                'password'    => bcrypt('admin123'),
                'employee_id' => null,
                'role'        => 'Admin',
            ]
        );

        
        $this->call(DashboardSeeder::class);

        // truncate 100 orang saja
        $monthsIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $currentMonthStr = $monthsIndo[(int)date('n')] . ' ' . date('Y');

        // Make sure the id = 1 is leo and sync the month
        $firstEmployee = Employee::find(1);
        if ($firstEmployee) {
            $firstEmployee->update([
                'name'  => 'Leo Rajata',
                'month' => $currentMonthStr, // tetap muncul saat terfilter
            ]);
        }

        // 100 id
        $keepIds = Employee::orderBy('id')->take(100)->pluck('id');
        Employee::whereNotIn('id', $keepIds)->delete();
        // ----------------------------------------------------------------------

        // 4. Akun User Employee yang terkoneksi ke id 1
        if ($firstEmployee) {
            User::firstOrCreate(
                ['username' => 'employee'],
                [
                    'name'        => $firstEmployee->name,
                    'email'       => 'employee@example.com',
                    'password'    => bcrypt('password'),
                    'employee_id' => $firstEmployee->id,
                    'role'        => 'Employee',
                ]
            );
        }

        // 5. Seed Notifikasi Mock Admin
        \App\Models\Notification::create([
            'user_id'    => $testUser->id,
            'title'      => 'Invoice baru masuk',
            'message'    => 'Invoice #INV-' . date('Y') . '-312 dari Proyek Enterprise Jawa Barat telah diterima.',
            'type'       => 'invoice',
            'created_at' => now()->subMinutes(2),
        ]);

        \App\Models\Notification::create([
            'user_id'    => $testUser->id,
            'title'      => 'Project mendekati deadline',
            'message'    => 'Proyek "Digitalisasi Sumatera" batas waktu 3 hari lagi.',
            'type'       => 'project',
            'created_at' => now()->subHour(),
        ]);

        \App\Models\Notification::create([
            'user_id'    => $testUser->id,
            'title'      => 'Pegawai baru ditambahkan',
            'message'    => 'Budi Santoso telah bergabung di Regional Jawa Tengah.',
            'type'       => 'employee',
            'created_at' => now()->subDay(),
        ]);
    }
}