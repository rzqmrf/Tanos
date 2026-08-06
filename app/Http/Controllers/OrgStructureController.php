<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\JobPosition;
use App\Models\Employee;
use App\Models\EmployeeMovement;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrgStructureController extends Controller
{
    // ==========================================
    // 1. STO CHART (ORGANIZATION/UNIT)
    // ==========================================
    public function stoIndex()
    {
        $divisions = Division::with('parent')->orderBy('created_at', 'desc')->get();
        // Root units for selection
        $rootDivs = Division::whereNull('parent_id')->get();

        return view('hr.org.sto', compact('divisions', 'rootDivs'));
    }

    public function stoStore(Request $request)
    {
        $valid = $request->validate([
            'code' => 'required|string|unique:divisions,code',
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:divisions,id',
            'description' => 'nullable|string',
            'regional' => 'nullable|string',
            'cost_center' => 'nullable|string',
            'unit_type' => 'required|string',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date',
        ]);

        $valid['active'] = true;
        $valid['sent_to_sap'] = false;

        Division::create($valid);

        return redirect()->back()->with('success', 'Unit/Departemen baru berhasil ditambahkan!');
    }

    public function stoDelimit(Request $request, $id)
    {
        $division = Division::findOrFail($id);
        
        $division->update([
            'active' => false,
            'valid_to' => date('Y-m-d')
        ]);

        return redirect()->back()->with('success', 'Unit berhasil di-delimit (dinonaktifkan secara historis).');
    }

    public function stoSendSap($id)
    {
        $division = Division::findOrFail($id);
        $division->update(['sent_to_sap' => true]);

        // Mock SAP log
        \App\Models\Notification::create([
            'user_id' => session('user')['id'] ?? 1,
            'title' => 'Unit Sent to MDM SAP',
            'message' => 'Unit ' . $division->name . ' (' . $division->code . ') telah dikirim ke MDM SAP.',
            'type' => 'hcm',
        ]);

        return redirect()->back()->with('success', 'Unit berhasil dikirim & disinkronkan ke SAP!');
    }

    // ==========================================
    // 2. JOB FORMATION (JOB POSITIONS)
    // ==========================================
    public function jobIndex()
    {
        $jobPositions = JobPosition::with(['division', 'parent'])->orderBy('created_at', 'desc')->get();
        $divisions = Division::where('active', true)->get();
        $parentJobs = JobPosition::where('active', true)->get();

        return view('hr.org.job', compact('jobPositions', 'divisions', 'parentJobs'));
    }

    public function jobStore(Request $request)
    {
        $valid = $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'parent_id' => 'nullable|exists:job_positions,id',
            'code' => 'required|string|unique:job_positions,code',
            'name' => 'required|string|max:255',
            'regional' => 'nullable|string',
            'cost_center' => 'nullable|string',
            'cost_center_name' => 'nullable|string',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date',
            'is_leader' => 'boolean',
            'no_contract' => 'boolean',
            'non_formation' => 'boolean',
        ]);

        $valid['active'] = true;
        $valid['sent_to_sap'] = false;

        JobPosition::create($valid);

        return redirect()->back()->with('success', 'Jabatan baru berhasil ditambahkan!');
    }

    public function jobDelimit(Request $request, $id)
    {
        $job = JobPosition::findOrFail($id);
        
        $job->update([
            'active' => false,
            'valid_to' => date('Y-m-d')
        ]);

        return redirect()->back()->with('success', 'Jabatan berhasil di-delimit (dinonaktifkan secara historis).');
    }

    public function jobDuplicate(Request $request, $id)
    {
        $job = JobPosition::findOrFail($id);

        // Duplicate the position with a new unique code
        $newJob = $job->replicate();
        $newJob->code = 'POS-' . strtoupper(Str::random(6));
        $newJob->active = true;
        $newJob->sent_to_sap = false;
        $newJob->save();

        return redirect()->back()->with('success', 'Formasi Jabatan berhasil digandakan secara massal!');
    }

    public function jobSendSap($id)
    {
        $job = JobPosition::findOrFail($id);
        $job->update(['sent_to_sap' => true]);

        \App\Models\Notification::create([
            'user_id' => session('user')['id'] ?? 1,
            'title' => 'Job Position Sent to SAP',
            'message' => 'Formasi Jabatan ' . $job->name . ' (' . $job->code . ') telah disinkronkan ke SAP.',
            'type' => 'hcm',
        ]);

        return redirect()->back()->with('success', 'Jabatan berhasil disinkronkan ke SAP!');
    }

    // ==========================================
    // 3. ECN (EMPLOYEE CHANGE NOTICE)
    // ==========================================
    public function ecnIndex()
    {
        $movements = EmployeeMovement::with(['employee', 'fromPosition', 'toPosition', 'fromProject', 'toProject'])
            ->orderBy('created_at', 'desc')->get();
            
        $employees = Employee::orderBy('name', 'asc')->get();
        $jobPositions = JobPosition::where('active', true)->get();
        $projects = Project::where('active', true)->get();

        return view('hr.org.ecn', compact('movements', 'employees', 'jobPositions', 'projects'));
    }

    public function ecnStore(Request $request)
    {
        $valid = $request->validate([
            'ecn_name' => 'required|string|max:255',
            'employee_id' => 'required|exists:employees,id',
            'movement_type' => 'required|string',
            'to_position_id' => 'nullable|exists:job_positions,id',
            'to_project_id' => 'nullable|exists:projects,id',
            'reference_number' => 'required|string|unique:employee_movements,reference_number',
            'effective_date' => 'required|date',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date',
        ]);

        $employee = Employee::findOrFail($valid['employee_id']);

        // Set previous positions/projects
        $valid['from_position_id'] = $employee->job_position_id ?? null;
        $valid['from_project_id'] = $employee->project_id ?? null;
        $valid['status'] = 'Draft';
        $valid['sent_to_sap'] = false;

        EmployeeMovement::create($valid);

        return redirect()->back()->with('success', 'Draft ECN / Usulan Mutasi berhasil diajukan!');
    }

    public function ecnComplete($id)
    {
        $movement = EmployeeMovement::findOrFail($id);
        $employee = Employee::findOrFail($movement->employee_id);

        // Update employee's active position or project based on ECN
        $updateData = [];
        if ($movement->to_position_id) {
            $updateData['job_position_id'] = $movement->to_position_id;
        }
        if ($movement->to_project_id) {
            $updateData['project_id'] = $movement->to_project_id;
        }

        if (!empty($updateData)) {
            $employee->update($updateData);
        }

        $movement->update(['status' => 'Completed']);

        return redirect()->back()->with('success', 'SK Mutasi/Karir (ECN) berhasil diposting! Perubahan jabatan/proyek karyawan telah aktif.');
    }

    public function ecnSendSap($id)
    {
        $movement = EmployeeMovement::findOrFail($id);
        $movement->update(['sent_to_sap' => true]);

        \App\Models\Notification::create([
            'user_id' => session('user')['id'] ?? 1,
            'title' => 'ECN Sent to SAP',
            'message' => 'Dokumen ECN / SK #' . $movement->reference_number . ' telah dikirim & diposting ke SAP.',
            'type' => 'hcm',
        ]);

        return redirect()->back()->with('success', 'ECN berhasil dikirim & diposting ke SAP!');
    }
}
