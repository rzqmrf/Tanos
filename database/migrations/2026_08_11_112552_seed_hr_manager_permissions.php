<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Seed permission untuk role 'HR Manager' — fokus ke HR (bukan settings/payroll/keuangan).
     * Idempotent: pakai DB insert saja kalau belum ada, supaya tidak dobel saat migrate ulang.
     */
    public function up(): void
    {
        // Permission HR Manager — fokus ke data kepegawaian & absensi, bukan keuangan/administrasi sistem.
        $hrPermissions = [
            'dashboard', 'employees', 'attendance', 'recruitment',
            'certifications', 'evaluations', 'schedules', 'reports',
            'rekap_absensi', 'pengajuan_cuti', 'kalender', 'laporan',
        ];

        foreach ($hrPermissions as $permission) {
            DB::table('role_permissions')->updateOrInsert(
                ['role' => 'HR Manager', 'permission' => $permission],
                ['is_enabled' => true]
            );
        }
    }

    /**
     * Reverse — hapus permission HR Manager yang di-seed oleh migration ini.
     */
    public function down(): void
    {
        DB::table('role_permissions')
            ->where('role', 'HR Manager')
            ->whereIn('permission', $hrPermissions ?? [
                'dashboard', 'employees', 'attendance', 'recruitment',
                'certifications', 'evaluations', 'schedules', 'reports',
                'rekap_absensi', 'pengajuan_cuti', 'kalender', 'laporan',
            ])
            ->delete();
    }
};
