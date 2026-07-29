<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RolePermission;

class AccessControlController extends Controller
{
    public function index() {
        $roles = RolePermission::select('role')->distinct()->pluck('role');
        
        $permissionsList = [
            'dashboard' => 'Dashboard Utama',
            'reports' => 'Laporan & Analitik',
            'projects' => 'Operations - Projects',
            'clients' => 'Operations - Clients',
            'schedules' => 'Operations - Shift Scheduling',
            'employees' => 'Human Resources - Employees',
            'attendance' => 'Human Resources - Attendance',
            'recruitment' => 'Human Resources - Recruitment',
            'evaluations' => 'Human Resources - Performance Appraisal',
            'certifications' => 'Human Resources - Training & Certs',
            'invoices' => 'Keuangan - Invoices',
            'payroll' => 'Keuangan - Payroll',
            'expenses' => 'Keuangan - Expenses',
            'settings' => 'Pengaturan Aplikasi'
        ];

        // Fetch all permissions, grouped by role
        $rolePermissions = RolePermission::all()->groupBy('role');

        return view('settings.access-controls', compact('roles', 'permissionsList', 'rolePermissions'));
    }

    public function store(Request $request) {
        $request->validate([
            'permissions' => 'required|array',
        ]);

        $permissionsList = [
            'dashboard', 'reports', 'projects', 'clients', 'schedules',
            'employees', 'attendance', 'recruitment', 'evaluations', 'certifications',
            'invoices', 'payroll', 'expenses', 'settings'
        ];

        $inputPermissions = $request->input('permissions');
        $roles = RolePermission::select('role')->distinct()->pluck('role');

        foreach ($roles as $role) {
            foreach ($permissionsList as $perm) {
                // If it is Admin role, force enable all permissions to prevent locking admin out
                if ($role === 'Admin') {
                    $isEnabled = true;
                } else {
                    $isEnabled = isset($inputPermissions[$role][$perm]) && $inputPermissions[$role][$perm] == '1';
                }

                RolePermission::updateOrCreate(
                    ['role' => $role, 'permission' => $perm],
                    ['is_enabled' => $isEnabled]
                );
            }
        }

        return redirect()->back()->with('success', 'Matriks hak akses peran berhasil diperbarui!');
    }

    public function addRole(Request $request) {
        $request->validate([
            'new_role' => 'required|string|max:50'
        ]);

        $newRole = trim($request->input('new_role'));

        // Check uniqueness in database manually
        $exists = RolePermission::whereRaw('LOWER(role) = ?', [strtolower($newRole)])->exists();
        if ($exists) {
            return redirect()->back()->withErrors(['new_role' => "Peran '{$newRole}' sudah ada di database."]);
        }

        $permissionsList = [
            'dashboard', 'reports', 'projects', 'clients', 'schedules',
            'employees', 'attendance', 'recruitment', 'evaluations', 'certifications',
            'invoices', 'payroll', 'expenses', 'settings'
        ];

        foreach ($permissionsList as $perm) {
            RolePermission::create([
                'role' => $newRole,
                'permission' => $perm,
                'is_enabled' => false
            ]);
        }

        return redirect()->back()->with('success', "Peran baru '{$newRole}' berhasil ditambahkan ke matriks!");
    }
}