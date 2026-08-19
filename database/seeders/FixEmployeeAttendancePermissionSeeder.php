<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixEmployeeAttendancePermissionSeeder extends Seeder
{
    /**
     * Employee sebelumnya tidak punya permission `attendance` di seed awal,
     * padahal route /attendances mengecek `permission:attendance` sehingga
     * employee kena 403. Seeder ini mengaktifkan akses tersebut untuk Employee
     * agar bisa melihat riwayat & clock in/out sendiri.
     */
    public function run(): void
    {
        DB::table('role_permissions')
            ->updateOrInsert(
                ['role' => 'Employee', 'permission' => 'attendance'],
                ['is_enabled' => true, 'updated_at' => now()]
            );
    }
}
