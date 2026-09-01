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
    public function index(Request $request)
    {
        $dashboardService = new DashboardService();

        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $query = Employee::query();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhere('identity_card_number', 'like', "%{$search}%")
                  ->orWhere('npwp_number', 'like', "%{$search}%")
                  ->orWhere('place_of_birth', 'like', "%{$search}%")
                  ->orWhere('nipp', 'like', "%{$search}%");
            });
        }

        if ($request->filled('regional')) {
            $query->where('regional', $request->regional);
        }

        if ($request->filled('segment')) {
            $query->where('segment', $request->segment);
        }

        $regionals = Regional::with('subRegionals')->orderBy('name')->get();
        $subRegionals = SubRegional::with('regional')->orderBy('name')->get();
        $segments = Segment::orderBy('name')->get();
        $months = $dashboardService->getMonths();

        $religions = \App\Models\HcMasterData::where('category', 'religion')->where('active', true)->orderBy('name')->pluck('name');
        if ($religions->isEmpty()) {
            $religions = collect(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu']);
        }

        $employees = $query->orderBy('name')->paginate($perPage)->withQueryString();
        $totalAll = Employee::count();
        $totalActive = Employee::where('employment_status', '!=', 'Terminated')->count();

        return view('hr.employees.index', [
            'employees' => $employees,
            'perPage' => $perPage,
            'search' => $request->get('search', ''),
            'regionals' => $regionals,
            'subRegionals' => $subRegionals,
            'segments' => $segments,
            'religions' => $religions,
            'months' => $months,
            'totalAll' => $totalAll,
            'totalActive' => $totalActive,
        ]);
    }

    public function create()
    {
        $dashboardService = new DashboardService();
        $regionals = Regional::with('subRegionals')->orderBy('name')->get();
        $subRegionals = SubRegional::with('regional')->orderBy('name')->get();
        $segments = Segment::orderBy('name')->get();
        $months = $dashboardService->getMonths();

        $religions = \App\Models\HcMasterData::where('category', 'religion')->where('active', true)->orderBy('name')->pluck('name');
        if ($religions->isEmpty()) {
            $religions = collect(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu']);
        }

        return view('hr.employees.create', compact('regionals', 'subRegionals', 'segments', 'months', 'religions'));
    }

    public function show(Employee $employee)
    {
        $employee->load(['user', 'attendances', 'leaveRequests', 'mutations', 'payrollItems']);

        return view('hr.employees.show', [
            'employee' => $employee,
        ]);
    }

    public function edit(Employee $employee)
    {
        $dashboardService = new DashboardService();
        $regionals = Regional::with('subRegionals')->orderBy('name')->get();
        $subRegionals = SubRegional::with('regional')->orderBy('name')->get();
        $segments = Segment::orderBy('name')->get();
        $months = $dashboardService->getMonths();

        $religions = \App\Models\HcMasterData::where('category', 'religion')->where('active', true)->orderBy('name')->pluck('name');
        if ($religions->isEmpty()) {
            $religions = collect(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu']);
        }

        return view('hr.employees.edit', compact('employee', 'regionals', 'subRegionals', 'segments', 'months', 'religions'));
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'segment' => 'required|string|max:255',
            'religion' => 'nullable|string|max:100',
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
            'jabatan'     => $employee->role,
            'email'       => strtolower(str_replace(' ', '', $employee->name)) . $employee->id . '@tanos.local',
            'password'    => Hash::make('password'),
            'employee_id' => $employee->id,
            'role'        => 'Employee',
            'role_groups' => [['role_group' => 'Employee', 'module' => 'Human Capital', 'action' => 'Read', 'table' => '', 'column' => '', 'value' => '']],
        ]);

        return redirect()->route('employees.show', $employee->id)->with('success', 'Berhasil menambah data pegawai baru!');
    }

    public function update(Request $request, Employee $employee)
    {
        $validData = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'segment' => 'required|string|max:255',
            'religion' => 'nullable|string|max:100',
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

        return redirect()->route('employees.show', $employee->id)->with('success', 'Data pegawai berhasil diperbarui!');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Data pegawai berhasil dihapus!');
    }
}
