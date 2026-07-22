<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Regional;
use App\Models\SubRegional;
use App\Models\Segment;
use App\Services\DashboardService;
use Illuminate\Http\Request;

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
            'employees' => Employee::latest()->paginate(25),
            'regionals' => $regionals,
            'subRegionals' => $subRegionals,
            'segments' => $segments,
            'months' => $months,
        ]);
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'segment' => 'required|string|max:255',
            'month' => 'required|string|max:255',
            'regional' => 'required|string|max:255',
            'sub_regional' => 'nullable|string|max:255',
        ]);

        $employee = Employee::create($validData);

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
        $validData = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'segment' => 'required|string|max:255',
            'month' => 'required|string|max:255',
            'regional' => 'required|string|max:255',
            'sub_regional' => 'nullable|string|max:255',
        ]);

        $employee->update($validData);

        return redirect()->back()->with('success', 'Data pegawai berhasil diperbarui!');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->back()->with('success', 'Pegawai berhasil dihapus!');
    }
}
