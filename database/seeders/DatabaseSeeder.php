<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Bersihkan sisa data lama (kecuali users biar aman)
        DB::table('projects')->truncate();
        DB::table('employees')->truncate();
        DB::table('invoices')->truncate();
        DB::table('notifications')->truncate();

        // 2. Tetap bikin User dummy bawaan kemarin buat login demo
        // Pake firstOrCreate biar kalau udah ada di database gak bikin eror duplikat
        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'), // Pastiin passwordnya lu inget, default 'password'
            ]
        );

        // 3. Panggil DashboardSeeder untuk mengisi tabel projects, employees, invoices
        $this->call(DashboardSeeder::class);

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