<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Bersihkan sisa data lama (kecuali users biar aman)
        Schema::disableForeignKeyConstraints();
        DB::table('projects')->truncate();
        DB::table('employees')->truncate();
        DB::table('invoices')->truncate();
        DB::table('notifications')->truncate();
        DB::table('attendances')->truncate();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        // 2. Tetap bikin User dummy bawaan kemarin buat login demo
        // Pake firstOrCreate biar kalau udah ada di database gak bikin eror duplikat
        $testUser = User::firstOrCreate(
            ['username' => 'rozaq'],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('admin123'), // Pastiin passwordnya lu inget, default 'password'
                'employee_id' => null,
                'role' => 'Admin',
            ]
        );

        // 3. Panggil DashboardSeeder untuk mengisi tabel projects, employees, invoices
        $this->call(DashboardSeeder::class);

        // Link a new employee user to the first seeded employee for demo self-service attendance
        $firstEmployee = \App\Models\Employee::first();
        if ($firstEmployee) {
            User::firstOrCreate(
                ['username' => 'employee'],
                [
                    'name' => $firstEmployee->name ?? 'Employee User',
                    'email' => 'employee@example.com',
                    'password' => bcrypt('password'),
                    'employee_id' => $firstEmployee->id,
                    'role' => 'Employee',
                ]
            );
        }

        // 4. Seed mock notifications for testUser
        \App\Models\Notification::create([
            'user_id' => $testUser->id,
            'title' => 'Invoice baru masuk',
            'message' => 'Invoice #INV-' . date('Y') . '-312 dari Proyek Enterprise Jawa Barat telah diterima.',
            'type' => 'invoice',
            'created_at' => now()->subMinutes(2),
        ]);

        \App\Models\Notification::create([
            'user_id' => $testUser->id,
            'title' => 'Project mendekati deadline',
            'message' => 'Proyek "Digitalisasi Sumatera" batas waktu 3 hari lagi.',
            'type' => 'project',
            'created_at' => now()->subHour(),
        ]);

        \App\Models\Notification::create([
            'user_id' => $testUser->id,
            'title' => 'Pegawai baru ditambahkan',
            'message' => 'Budi Santoso telah bergabung di Regional Jawa Tengah.',
            'type' => 'employee',
            'created_at' => now()->subDay(),
        ]);
    }
}