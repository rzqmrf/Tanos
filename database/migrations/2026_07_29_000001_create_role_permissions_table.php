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
            'settings'
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
            $enabled = in_array($perm, ['dashboard', 'attendance']);
            DB::table('role_permissions')->insert([
                'role' => 'Employee',
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
