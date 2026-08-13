<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Project;
use App\Models\AbsentType;
use App\Models\ScheduleGroup;
use App\Models\ScheduleAssignment;
use App\Models\TimeEvaluation;
use App\Models\TimePeriod;
use App\Models\TimeResult;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class TimeManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $routeAction = $request->route()->getActionMethod();
            $modifyingActions = [
                'absentTypesStore', 'absentTypesUpdate', 'absentTypesDestroy',
                'scheduleGroupStore', 'scheduleGroupUpdate', 'scheduleGroupDestroy',
                'scheduleAssignStore', 'scheduleAssignDestroy',
                'evaluationStore', 'evaluationUpdate', 'evaluationDestroy',
                'periodStore', 'periodCalculate', 'periodDestroy'
            ];
            if (in_array($routeAction, $modifyingActions)) {
                if (!in_array(auth()->user()?->role, ['Admin', 'HR Manager'])) {
                    abort(403, 'Akses ditolak. Hanya Admin dan HR Manager yang dapat melakukan aksi ini.');
                }
            }
            return $next($request);
        });
    }

    // ==========================================
    // 1. ABSENT TYPES
    // ==========================================
    public function absentTypesIndex()
    {
        $types = AbsentType::orderBy('priority_level', 'asc')->get();
        return view('hr.time.absent_types', compact('types'));
    }

    public function absentTypesStore(Request $request)
    {
        $valid = $request->validate([
            'code' => 'required|string|unique:absent_types,code',
            'name' => 'required|string|max:255',
            'gender' => 'required|string',
            'priority_level' => 'required|integer|min:1',
            'deduction_absent' => 'required|string',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date',
        ]);

        $valid['active'] = true;
        AbsentType::create($valid);

        return redirect()->back()->with('success', 'Absent Type baru berhasil ditambahkan!');
    }

    // ==========================================
    // 2. SCHEDULES (GROUPS & ASSIGNMENTS)
    // ==========================================
    public function schedulesIndex()
    {
        $groups = ScheduleGroup::orderBy('name', 'asc')->get();
        $assignments = ScheduleAssignment::with(['employee', 'scheduleGroup'])->orderBy('created_at', 'desc')->get();
        $employees = Employee::orderBy('name', 'asc')->get();

        return view('hr.time.schedules', compact('groups', 'assignments', 'employees'));
    }

    public function scheduleGroupStore(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'work_start' => 'nullable|date_format:H:i',
            'work_end' => 'nullable|date_format:H:i',
        ]);

        $valid['is_active'] = true;
        ScheduleGroup::create($valid);

        return redirect()->back()->with('success', 'Kelompok jadwal baru berhasil dibuat!');
    }

    public function scheduleAssignStore(Request $request)
    {
        $valid = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'schedule_group_id' => 'required|exists:schedule_groups,id',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date',
        ]);

        ScheduleAssignment::create($valid);

        return redirect()->back()->with('success', 'Penugasan jadwal karyawan berhasil disimpan!');
    }

    // ==========================================
    // 3. TIME EVALUATIONS (TOLERANCE PARAMETERS)
    // ==========================================
    public function evaluationIndex()
    {
        $evaluations = TimeEvaluation::orderBy('valid_from', 'desc')->get();
        return view('hr.time.evaluation', compact('evaluations'));
    }

    public function evaluationStore(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date',
            'late_tolerance_minutes' => 'required|integer|min:0',
            'early_departure_minutes' => 'required|integer|min:0',
        ]);

        $valid['is_active'] = true;

        // Deactivate other evaluations
        TimeEvaluation::query()->update(['is_active' => false]);

        TimeEvaluation::create($valid);

        return redirect()->back()->with('success', 'Parameter toleransi keterlambatan baru berhasil diaktifkan!');
    }

    // ==========================================
    // 4. TIME PERIODS & EVALUATION CALCULATOR
    // ==========================================
    public function periodsIndex()
    {
        $periods = TimePeriod::with('project')->orderBy('start_date', 'desc')->get();
        $projects = Project::where('active', true)->get();

        return view('hr.time.periods', compact('periods', 'projects'));
    }

    public function periodStore(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'nullable|exists:projects,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $valid['status'] = 'Draft';
        TimePeriod::create($valid);

        return redirect()->back()->with('success', 'Periode Rekap Absensi baru berhasil dibuat!');
    }

    public function periodShow($id)
    {
        $period = TimePeriod::with(['project', 'results.employee'])->findOrFail($id);
        return view('hr.time.period_show', compact('period'));
    }

    public function periodCalculate($id)
    {
        $period = TimePeriod::findOrFail($id);
        
        // 1. Get employees
        if ($period->project_id) {
            $employees = Employee::where('project_id', $period->project_id)->get();
        } else {
            $employees = Employee::all();
        }

        // 2. Count standard workdays (weekdays only in start_date to end_date)
        $startDate = Carbon::parse($period->start_date);
        $endDate = Carbon::parse($period->end_date);
        $workdays = 0;
        $datePeriod = CarbonPeriod::create($startDate, $endDate);
        foreach ($datePeriod as $date) {
            if (!$date->isWeekend()) {
                $workdays++;
            }
        }

        // 3. Get active evaluation rules
        $rule = TimeEvaluation::where('is_active', true)->first() ?? new TimeEvaluation([
            'late_tolerance_minutes' => 15,
            'early_departure_minutes' => 15
        ]);

        // 4. Calculate stats for each employee
        foreach ($employees as $employee) {
            // Get active schedule group assignment
            $assignment = ScheduleAssignment::with('scheduleGroup')
                ->where('employee_id', $employee->id)
                ->where('valid_from', '<=', $period->end_date)
                ->where('valid_to', '>=', $period->start_date)
                ->first();

            $sched = $assignment ? $assignment->scheduleGroup : null;
            $stdStart = $sched ? $sched->work_start : '08:00:00';
            $stdEnd = $sched ? $sched->work_end : '17:00:00';

            // Get attendances
            $atts = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$period->start_date, $period->end_date])
                ->get();

            // Count CICO check-ins
            $present = 0;
            $late = 0;
            $early = 0;
            $otHours = 0.00;

            foreach ($atts as $att) {
                if ($att->status === 'Hadir') {
                    $present++;
                    $otHours += (float) $att->overtime_hours;

                    // Late calculation
                    if ($att->clock_in && $stdStart) {
                        $diffInMins = Carbon::parse($att->clock_in)->diffInMinutes(Carbon::parse($stdStart), false);
                        // if diff is negative, it means they clocked in AFTER stdStart
                        if ($diffInMins < 0 && abs($diffInMins) > $rule->late_tolerance_minutes) {
                            $late++;
                        }
                    }

                    // Early departure calculation
                    if ($att->clock_out && $stdEnd) {
                        $diffOutMins = Carbon::parse($att->clock_out)->diffInMinutes(Carbon::parse($stdEnd), false);
                        // if diff is negative, it means they clocked out BEFORE stdEnd
                        if ($diffOutMins > 0 && $diffOutMins > $rule->early_departure_minutes) {
                            $early++;
                        }
                    }
                }
            }

            // Get approved leaves
            $leavesCount = 0;
            $leaves = LeaveRequest::where('employee_id', $employee->id)
                ->where('status', 'Approved')
                ->where('start_date', '<=', $period->end_date)
                ->where('end_date', '>=', $period->start_date)
                ->get();

            foreach ($leaves as $lv) {
                $lvStart = Carbon::parse($lv->start_date)->max($startDate);
                $lvEnd = Carbon::parse($lv->end_date)->min($endDate);
                $lvPeriod = CarbonPeriod::create($lvStart, $lvEnd);
                foreach ($lvPeriod as $d) {
                    if (!$d->isWeekend()) {
                        $leavesCount++;
                    }
                }
            }

            // Calculate absent days
            $absent = max(0, $workdays - $present - $leavesCount);

            // Deductions rule (denda):
            // Late: Rp 50.000 / day
            // Absent (alfa): Rp 150.000 / day
            // Early departure: Rp 50.000 / day
            $deduction = ($late * 50000) + ($absent * 150000) + ($early * 50000);

            // Update or create result
            TimeResult::updateOrCreate(
                [
                    'time_period_id' => $period->id,
                    'employee_id' => $employee->id
                ],
                [
                    'workdays' => $workdays,
                    'present_days' => $present,
                    'absent_days' => $absent,
                    'late_days' => $late,
                    'early_departure_days' => $early,
                    'leave_days' => $leavesCount,
                    'overtime_hours' => $otHours,
                    'deduction_amount' => $deduction
                ]
            );
        }

        $period->update(['status' => 'Completed']);

        return redirect()->back()->with('success', 'Rekapitulasi kehadiran periode ini berhasil dievaluasi & di-generate!');
    }

    public function absentTypesUpdate(Request $request, $id)
    {
        $type = AbsentType::findOrFail($id);
        $valid = $request->validate([
            'code' => 'required|string|unique:absent_types,code,' . $id,
            'name' => 'required|string|max:255',
            'gender' => 'required|string',
            'priority_level' => 'required|integer|min:1',
            'deduction_absent' => 'required|string',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date',
            'active' => 'required|boolean'
        ]);

        $type->update($valid);
        return redirect()->back()->with('success', 'Absent Type berhasil diperbarui!');
    }

    public function absentTypesDestroy($id)
    {
        $type = AbsentType::findOrFail($id);
        $type->delete();
        return redirect()->back()->with('success', 'Absent Type berhasil dihapus!');
    }

    public function scheduleGroupUpdate(Request $request, $id)
    {
        $group = ScheduleGroup::findOrFail($id);
        $valid = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'work_start' => 'nullable|date_format:H:i',
            'work_end' => 'nullable|date_format:H:i',
            'is_active' => 'required|boolean'
        ]);

        $group->update($valid);
        return redirect()->back()->with('success', 'Kelompok jadwal berhasil diperbarui!');
    }

    public function scheduleGroupDestroy($id)
    {
        $group = ScheduleGroup::findOrFail($id);
        $group->delete();
        return redirect()->back()->with('success', 'Kelompok jadwal berhasil dihapus!');
    }

    public function scheduleAssignDestroy($id)
    {
        $assign = ScheduleAssignment::findOrFail($id);
        $assign->delete();
        return redirect()->back()->with('success', 'Penugasan jadwal karyawan berhasil dihapus!');
    }

    public function evaluationUpdate(Request $request, $id)
    {
        $eval = TimeEvaluation::findOrFail($id);
        $valid = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date',
            'late_tolerance_minutes' => 'required|integer|min:0',
            'early_departure_minutes' => 'required|integer|min:0',
            'is_active' => 'required|boolean'
        ]);

        if ($valid['is_active']) {
            TimeEvaluation::where('id', '!=', $id)->update(['is_active' => false]);
        }

        $eval->update($valid);
        return redirect()->back()->with('success', 'Parameter toleransi berhasil diperbarui!');
    }

    public function evaluationDestroy($id)
    {
        $eval = TimeEvaluation::findOrFail($id);
        $eval->delete();
        return redirect()->back()->with('success', 'Parameter toleransi berhasil dihapus!');
    }

    public function periodDestroy($id)
    {
        $period = TimePeriod::findOrFail($id);
        $period->delete();
        return redirect()->back()->with('success', 'Periode Rekap Absensi berhasil dihapus!');
    }
}
