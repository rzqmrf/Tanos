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

        // 2. Tetap bikin User dummy bawaan kemarin buat login demo
        // Pake firstOrCreate biar kalau udah ada di database gak bikin eror duplikat
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'), // Pastiin passwordnya lu inget, default 'password'
            ]
        );

        // 3. Panggil DashboardSeeder untuk mengisi tabel projects, employees, invoices
        $this->call(DashboardSeeder::class);
    }
}