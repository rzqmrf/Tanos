<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role');
            $table->string('permission');
            $table->boolean('is_enabled')->default(false);
            $table->timestamps();
            
            $table->unique(['role', 'permission']);
        });

        // Seed default permissions
        $permissions = [
            'dashboard',
            'reports',
            'projects',
            'clients',
            'schedules',
            'employees',
            'attendance',
            'recruitment',
            'evaluations',
            'certifications',
            'invoices',
            'payroll',
            'expenses',
            'settings',
            'rekap_absensi',
            'pengajuan_cuti',
            'kalender',
            'laporan'
        ];

        // Seed Admin permissions (all enabled)
        foreach ($permissions as $perm) {
            DB::table('role_permissions')->insert([
                'role' => 'Admin',
                'permission' => $perm,
                'is_enabled' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed Employee permissions
        foreach ($permissions as $perm) {
            $enabled = in_array($perm, ['dashboard', 'rekap_absensi', 'pengajuan_cuti', 'kalender', 'laporan']);
            DB::table('role_permissions')->insert([
                'role' => 'Employee',
                'permission' => $perm,
                'is_enabled' => $enabled,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed HR Manager permissions
        foreach ($permissions as $perm) {
            $enabled = in_array($perm, ['dashboard', 'employees', 'attendance', 'recruitment', 'evaluations', 'certifications', 'rekap_absensi', 'pengajuan_cuti', 'kalender']);
            DB::table('role_permissions')->insert([
                'role' => 'HR Manager',
                'permission' => $perm,
                'is_enabled' => $enabled,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed Finance Manager permissions
        foreach ($permissions as $perm) {
            $enabled = in_array($perm, ['dashboard', 'invoices', 'payroll', 'expenses', 'rekap_absensi', 'pengajuan_cuti', 'kalender']);
            DB::table('role_permissions')->insert([
                'role' => 'Finance Manager',
                'permission' => $perm,
                'is_enabled' => $enabled,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Seed Project Manager permissions
        foreach ($permissions as $perm) {
            $enabled = in_array($perm, ['dashboard', 'projects', 'clients', 'rekap_absensi', 'pengajuan_cuti', 'kalender']);
            DB::table('role_permissions')->insert([
                'role' => 'Project Manager',
                'permission' => $perm,
                'is_enabled' => $enabled,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
