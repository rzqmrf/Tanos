<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of user accounts.
     */
    public function index(Request $request)
    {
        // Redirect to login if user is not in session or not Admin
        if (!session()->has('user')) {
            return redirect()->route('login');
        }
        if (session('user.role') !== 'Admin') {
            return redirect()->route('dashboard.index')->withErrors(['error' => 'Akses ditolak. Hanya Administrator yang dapat mengakses menu ini.']);
        }

        // Fetch users with their mapped employees
        $users = User::with('employee')->orderBy('name')->paginate(25)->withQueryString();
        
        // Fetch all employees for database mapping
        $employees = Employee::orderBy('name')->get();

        // Calculate statistics
        $stats = [
            'total' => User::count(),
            'admins' => User::where('role', 'Admin')->count(),
            'employees' => User::where('role', 'Employee')->count(),
        ];

        return view('settings.users', [
            'users' => $users,
            'employees' => $employees,
            'stats' => $stats,
        ]);
    }

    /**
     * Store a newly created user account.
     */
    public function store(Request $request)
    {
        if (!session()->has('user') || session('user.role') !== 'Admin') {
            return redirect()->back()->withErrors(['error' => 'Akses ditolak.']);
        }

        $validData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:Admin,Employee',
            'employee_id' => 'nullable|exists:employees,id|unique:users,employee_id',
        ]);

        User::create([
            'name' => $validData['name'],
            'username' => $validData['username'],
            'email' => $validData['email'],
            'password' => Hash::make($validData['password']),
            'role' => $validData['role'],
            'employee_id' => $validData['employee_id'],
        ]);

        return redirect()->back()->with('success', 'Akun user berhasil dibuat!');
    }

    /**
     * Update the specified user account.
     */
    public function update(Request $request, User $user)
    {
        if (!session()->has('user') || session('user.role') !== 'Admin') {
            return redirect()->back()->withErrors(['error' => 'Akses ditolak.']);
        }

        $validData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|string|in:Admin,Employee',
            'employee_id' => 'nullable|exists:employees,id|unique:users,employee_id,' . $user->id,
        ]);

        $updateData = [
            'name' => $validData['name'],
            'username' => $validData['username'],
            'email' => $validData['email'],
            'role' => $validData['role'],
            'employee_id' => $validData['employee_id'],
        ];

        // Only update password if provided
        if (!empty($validData['password'])) {
            $updateData['password'] = Hash::make($validData['password']);
        }

        $user->update($updateData);

        // If currently logged-in user updates their own details, refresh session
        if ($user->id === session('user.id')) {
            session([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'role' => $user->role,
                    'employee_id' => $user->employee_id,
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Akun user berhasil diperbarui!');
    }

    /**
     * Remove the specified user account.
     */
    public function destroy(User $user)
    {
        if (!session()->has('user') || session('user.role') !== 'Admin') {
            return redirect()->back()->withErrors(['error' => 'Akses ditolak.']);
        }

        // Prevent self-deletion
        if ($user->id === session('user.id')) {
            return redirect()->back()->withErrors(['error' => 'Anda tidak diperbolehkan menghapus akun Anda sendiri yang sedang aktif!']);
        }

        $user->delete();

        return redirect()->back()->with('success', 'Akun user berhasil dihapus!');
    }
}
