<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of user accounts.
     */
    public function index(Request $request)
    {
        $query = User::with('employee');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('employee', fn($emp) => $emp->where('name', 'like', "%{$search}%")->orWhere('nipp', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $perPage = (int) $request->input('per_page', 10);
        if ($perPage < 5 || $perPage > 100) {
            $perPage = 10;
        }

        $users = $query->orderBy('name')->paginate($perPage)->withQueryString();
        
        $totalAll = User::count();
        $totalActive = User::where('active', true)->count();
        $totalAdmin = User::where('role', 'Admin')->count();
        $totalEmployee = User::where('role', 'Employee')->count();

        $roleGroups = [
            'Super Admin',
            'Admin FA',
            'Admin Material',
            'Admin HC',
            'Project Manager',
            'IT Pemagang',
            'Komersial',
            'Keuangan',
            'Operasional',
            'Staff',
            'Employee',
        ];

        return view('settings.users.index', compact(
            'users',
            'totalAll',
            'totalActive',
            'totalAdmin',
            'totalEmployee',
            'roleGroups',
            'perPage'
        ));
    }

    /**
     * Show Create User Page.
     */
    public function create()
    {
        $employees = Employee::orderBy('name')->get();

        $availableRoleGroups = [
            'IT Pemagang',
            'Komersial',
            'Keuangan',
            'Operasional',
            'Super Admin',
            'Admin FA',
            'Admin Material',
            'Admin HC',
            'Project Manager',
            'Staff',
            'Employee',
        ];

        $modules = [
            'General',
            'Material',
            'Human Capital',
            'Finance & Accounting',
            'Project System',
            'All Modules',
        ];

        $actions = [
            'All',
            'Read',
            'Create',
            'Update',
            'Delete',
            'Approval',
        ];

        return view('settings.users.create', compact('employees', 'availableRoleGroups', 'modules', 'actions'));
    }

    /**
     * Store a newly created user account.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'jabatan' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'employee_id' => 'nullable|exists:employees,id|unique:users,employee_id',
            'role_groups' => 'nullable|array',
            'approval_authority' => 'nullable|array',
        ]);

        $roleGroups = [];
        if ($request->has('role_groups_data')) {
            $roleGroups = json_decode($request->role_groups_data, true) ?? [];
        } elseif ($request->has('role_groups')) {
            $roleGroups = $request->role_groups;
        }

        // Determine primary role from first role group or default
        $primaryRole = 'Admin';
        if (!empty($roleGroups) && isset($roleGroups[0]['role_group'])) {
            $rg = $roleGroups[0]['role_group'];
            $primaryRole = ($rg === 'Employee' || $rg === 'Staff') ? 'Employee' : 'Admin';
        }

        $user = User::create([
            'name' => $request->name,
            'username' => trim($request->username),
            'jabatan' => $request->jabatan ?? 'Staff',
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $primaryRole,
            'employee_id' => $request->employee_id,
            'role_groups' => $roleGroups,
            'approval_authority' => $request->approval_authority ?? [],
            'active' => true,
        ]);

        return redirect()->route('users.show', $user->id)->with('success', 'User ' . $user->name . ' berhasil didaftarkan!');
    }

    /**
     * Display User Detail (Show Page).
     */
    public function show(User $user)
    {
        $user->load('employee');

        return view('settings.users.show', compact('user'));
    }

    /**
     * Show Edit User Page matching Tanos screenshot.
     */
    public function edit(User $user)
    {
        $user->load('employee');
        $employees = Employee::orderBy('name')->get();

        $availableRoleGroups = [
            'IT Pemagang',
            'Komersial',
            'Keuangan',
            'Operasional',
            'Super Admin',
            'Admin FA',
            'Admin Material',
            'Admin HC',
            'Project Manager',
            'Staff',
            'Employee',
        ];

        $modules = [
            'General',
            'Material',
            'Human Capital',
            'Finance & Accounting',
            'Project System',
            'All Modules',
        ];

        $actions = [
            'All',
            'Read',
            'Create',
            'Update',
            'Delete',
            'Approval',
        ];

        // Seed default role groups if empty to match screenshot
        $userRoleGroups = $user->role_groups;
        if (empty($userRoleGroups)) {
            $userRoleGroups = [
                ['role_group' => 'IT Pemagang', 'module' => 'General', 'action' => 'All', 'table' => '', 'column' => '', 'value' => ''],
                ['role_group' => 'Komersial', 'module' => 'Project System', 'action' => 'Read', 'table' => '', 'column' => '', 'value' => ''],
                ['role_group' => 'Keuangan', 'module' => 'Finance & Accounting', 'action' => 'Read', 'table' => '', 'column' => '', 'value' => ''],
            ];
        }

        return view('settings.users.edit', compact(
            'user',
            'employees',
            'availableRoleGroups',
            'modules',
            'actions',
            'userRoleGroups'
        ));
    }

    /**
     * Update the specified user account.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'jabatan' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'employee_id' => 'nullable|exists:employees,id|unique:users,employee_id,' . $user->id,
            'password' => $request->filled('password') ? 'nullable|string|min:6|confirmed' : 'nullable',
        ]);

        $roleGroups = [];
        if ($request->has('role_groups_data')) {
            $roleGroups = json_decode($request->role_groups_data, true) ?? [];
        } elseif ($request->has('role_groups')) {
            $roleGroups = $request->role_groups;
        }

        $updateData = [
            'name' => $request->name,
            'username' => trim($request->username),
            'jabatan' => $request->jabatan,
            'email' => $request->email,
            'employee_id' => $request->employee_id,
            'role_groups' => $roleGroups,
        ];

        // Update password only if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        // Adjust primary role if necessary
        if (!empty($roleGroups) && isset($roleGroups[0]['role_group'])) {
            $rg = $roleGroups[0]['role_group'];
            $updateData['role'] = ($rg === 'Employee' || $rg === 'Staff') ? 'Employee' : 'Admin';
        }

        $user->update($updateData);

        return redirect()->route('users.show', $user->id)->with('success', 'Data User ' . $user->name . ' berhasil diperbarui!');
    }

    /**
     * Remove the specified user account.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->withErrors(['error' => 'Anda tidak diperbolehkan menghapus akun Anda sendiri yang sedang aktif!']);
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Akun user ' . $name . ' berhasil dihapus!');
    }
}
