<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Regional;
use App\Models\SubRegional;
use App\Models\Segment;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $dashboardService = new DashboardService();

        $regionals = Regional::with('subRegionals')->orderBy('name')->get();
        $subRegionals = SubRegional::with('regional')->orderBy('name')->get();
        $segments = Segment::orderBy('name')->get();
        $months = $dashboardService->getMonths();

        return view('hr.employees', [
            'employees' => Employee::oldest()->paginate(25),
            'regionals' => $regionals,
            'subRegionals' => $subRegionals,
            'segments' => $segments,
            'months' => $months,
        ]);
    }

    public function store(Request $request)
    {
        if (!in_array(session('user.role'), ['Admin', 'HR Manager'])) {
            abort(403, 'Akses ditolak. Hanya Admin dan HR Manager yang dapat memodifikasi data karyawan.');
        }

        $validData = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'segment' => 'required|string|max:255',
            'month' => 'required|string|max:255',
            'regional' => 'required|string|max:255',
            'sub_regional' => 'nullable|string|max:255',
            'nipp' => 'required|string|max:50|unique:employees,nipp',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
            'ptkp_status' => 'required|string|max:10',
            'tmt_date' => 'nullable|date',
            'bpjs_kesehatan_number' => 'nullable|string|max:50',
            'bpjs_ketenagakerjaan_number' => 'nullable|string|max:50',
        ]);

        $employee = Employee::create($validData);

        // Auto-create User account supaya employee baru bisa login
        // (username = nama depan, password default = 'password', role = Employee)
        $firstName = strtolower(explode(' ', $employee->name)[0]);
        $baseUsername = $firstName;
        $username = $baseUsername;
        $counter = 2;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        User::create([
            'username'    => $username,
            'name'        => $employee->name,
            'email'       => strtolower(str_replace(' ', '', $employee->name)) . $employee->id . '@tanos.local',
            'password'    => Hash::make('password'),
            'employee_id' => $employee->id,
            'role'        => 'Employee',
        ]);

        // Trigger notification for all users
        foreach (\App\Models\User::all() as $u) {
            \App\Models\Notification::create([
                'user_id' => $u->id,
                'title' => 'Pegawai Baru Bergabung',
                'message' => $employee->name . ' telah bergabung sebagai ' . $employee->role . ' di ' . $employee->regional . ($employee->sub_regional ? ' (' . $employee->sub_regional . ')' : '') . '.',
                'type' => 'employee',
            ]);
        }

        return redirect()->back()->with('success', 'Berhasil menambah data pegawai!');
    }

    public function update(Request $request, Employee $employee)
    {
        if (!in_array(session('user.role'), ['Admin', 'HR Manager'])) {
            abort(403, 'Akses ditolak. Hanya Admin dan HR Manager yang dapat memodifikasi data karyawan.');
        }

        $validData = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'segment' => 'required|string|max:255',
            'month' => 'required|string|max:255',
            'regional' => 'required|string|max:255',
            'sub_regional' => 'nullable|string|max:255',
            'nipp' => 'required|string|max:50|unique:employees,nipp,' . $employee->id,
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_name' => 'nullable|string|max:255',
            'ptkp_status' => 'required|string|max:10',
            'tmt_date' => 'nullable|date',
            'bpjs_kesehatan_number' => 'nullable|string|max:50',
            'bpjs_ketenagakerjaan_number' => 'nullable|string|max:50',
        ]);

        $employee->update($validData);

        return redirect()->back()->with('success', 'Data pegawai berhasil diperbarui!');
    }

    public function destroy(Employee $employee)
    {
        if (!in_array(session('user.role'), ['Admin', 'HR Manager'])) {
            abort(403, 'Akses ditolak. Hanya Admin dan HR Manager yang dapat memodifikasi data karyawan.');
        }

        $employee->delete();
        return redirect()->back()->with('success', 'Pegawai berhasil dihapus!');
    }
}
