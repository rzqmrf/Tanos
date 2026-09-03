<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AbsentType;
use App\Models\ScheduleGroup;
use App\Models\ScheduleAssignment;
use App\Models\TimeEvaluation;
use App\Models\TimePeriod;
use App\Models\TimePeriodResult;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Project;
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
                $user = Auth::user();
                if (!$user || !in_array($user->role, ['Admin', 'HR Manager'])) {
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
            'active' => 'required|boolean'
        ]);

        AbsentType::create($valid);
        return redirect()->back()->with('success', 'Absent Type baru berhasil ditambahkan!');
    }

    // ==========================================
    // 2. SCHEDULES (SCHEME & ASSIGNMENT)
    // ==========================================
    public function schedulesIndex()
    {
        $groups = ScheduleGroup::withCount('assignments')->orderBy('id', 'desc')->get();
        $assignments = ScheduleAssignment::with(['employee', 'scheduleGroup'])->orderBy('id', 'desc')->paginate(10);
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
            'is_active' => 'required|boolean'
        ]);

        ScheduleGroup::create($valid);
        return redirect()->back()->with('success', 'Kelompok Jadwal baru berhasil ditambahkan!');
    }

    public function scheduleAssignStore(Request $request)
    {
        $valid = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'schedule_group_id' => 'required|exists:schedule_groups,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        ScheduleAssignment::updateOrCreate(
            ['employee_id' => $valid['employee_id']],
            [
                'schedule_group_id' => $valid['schedule_group_id'],
                'start_date' => $valid['start_date'],
                'end_date' => $valid['end_date']
            ]
        );

        return redirect()->back()->with('success', 'Karyawan berhasil ditetapkan ke kelompok jadwal!');
    }

    // ==========================================
    // 3. TIME EVALUATIONS / WORK HOUR TOLERANCE
    // ==========================================
    public function evaluationIndex()
    {
        $evaluations = TimeEvaluation::orderBy('id', 'desc')->get();
        return view('hr.time.evaluation', compact('evaluations'));
    }

    public function evaluationCreate()
    {
        return view('hr.time.evaluation-create');
    }

    public function evaluationShow($id)
    {
        $item = TimeEvaluation::findOrFail($id);
        return view('hr.time.evaluation-show', compact('item'));
    }

    public function evaluationEdit($id)
    {
        $item = TimeEvaluation::findOrFail($id);
        return view('hr.time.evaluation-edit', compact('item'));
    }

    public function evaluationUpdate(Request $request, $id)
    {
        $item = TimeEvaluation::findOrFail($id);
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
            TimeEvaluation::where('id', '!=', $id)->where('is_active', true)->update(['is_active' => false]);
        }

        $item->update($valid);

        return redirect()->route('org.evaluations.show', $item->id)->with('success', 'Parameter toleransi jam kerja berhasil diperbarui!');
    }

    public function evaluationDestroy($id)
    {
        $item = TimeEvaluation::findOrFail($id);
        $item->delete();

        return redirect()->route('org.evaluations.index')->with('success', 'Parameter toleransi jam kerja berhasil dihapus!');
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
            'is_active' => 'required|boolean'
        ]);

        if ($valid['is_active']) {
            TimeEvaluation::where('is_active', true)->update(['is_active' => false]);
        }

        TimeEvaluation::create($valid);
        return redirect()->back()->with('success', 'Parameter toleransi jam kerja baru berhasil disimpan!');
    }

    // ==========================================
    // 4. PERIODS & REKAPITULASI KEHADIRAN (ENGINE)
    // ==========================================
    public function periodsIndex()
    {
        $periods = TimePeriod::with('project')->withCount('results')->orderBy('start_date', 'desc')->paginate(10);
        $projects = Project::orderBy('project_name', 'asc')->get();
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

    public function periodShow(int|string $id)
    {
        $period = TimePeriod::with(['project', 'results.employee'])->findOrFail($id);
        return view('hr.time.period_show', compact('period'));
    }

    public function periodCalculate(int|string $id)
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
            $attendances = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$period->start_date, $period->end_date])
                ->get();

            $present = 0;
            $late = 0;
            $leave = 0;
            $absent = 0;
            $overtimeMinutes = 0;
            $totalLateMinutes = 0;

            // Map attendances by date
            $attByDate = $attendances->keyBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });

            foreach ($datePeriod as $date) {
                $dStr = $date->format('Y-m-d');
                $isWeekend = $date->isWeekend();

                if (isset($attByDate[$dStr])) {
                    $att = $attByDate[$dStr];
                    $status = strtolower($att->status);

                    if (in_array($status, ['present', 'hadir', 'ontime', 'terlambat', 'late'])) {
                        $present++;
                        if ($att->check_in) {
                            $checkIn = Carbon::parse($att->check_in);
                            $standardIn = Carbon::parse($att->date . ' 08:00:00');
                            if ($checkIn->gt($standardIn)) {
                                $diff = $checkIn->diffInMinutes($standardIn);
                                if ($diff > $rule->late_tolerance_minutes) {
                                    $late++;
                                    $totalLateMinutes += ($diff - $rule->late_tolerance_minutes);
                                }
                            }
                        }
                    } elseif (in_array($status, ['leave', 'cuti', 'izin', 'sick', 'sakit'])) {
                        $leave++;
                    } else {
                        if (!$isWeekend) {
                            $absent++;
                        }
                    }

                    // Overtime calculation
                    if ($att->check_out) {
                        $checkOut = Carbon::parse($att->check_out);
                        $standardOut = Carbon::parse($att->date . ' 17:00:00');
                        if ($checkOut->gt($standardOut)) {
                            $overtimeMinutes += $checkOut->diffInMinutes($standardOut);
                        }
                    }
                } else {
                    if (!$isWeekend) {
                        $absent++;
                    }
                }
            }

            // Simple deduction estimation: Rp 50.000 per absent, Rp 1.000 per late minute
            $deduction = ($absent * 50000) + ($totalLateMinutes * 1000);

            TimePeriodResult::updateOrCreate(
                [
                    'time_period_id' => $period->id,
                    'employee_id' => $employee->id
                ],
                [
                    'total_workdays' => $workdays,
                    'total_present' => $present,
                    'total_late' => $late,
                    'total_leave' => $leave,
                    'total_absent' => $absent,
                    'total_overtime_hours' => round($overtimeMinutes / 60, 1),
                    'total_late_minutes' => $totalLateMinutes,
                    'deduction_amount' => $deduction
                ]
            );
        }

        $period->update(['status' => 'Completed']);

        return redirect()->back()->with('success', 'Rekapitulasi kehadiran periode ini berhasil dievaluasi & di-generate!');
    }

    public function absentTypesUpdate(Request $request, int|string $id)
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

    public function absentTypesDestroy(int|string $id)
    {
        $type = AbsentType::findOrFail($id);
        $type->delete();
        return redirect()->back()->with('success', 'Absent Type berhasil dihapus!');
    }

    public function scheduleGroupUpdate(Request $request, int|string $id)
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

    public function scheduleGroupDestroy(int|string $id)
    {
        $group = ScheduleGroup::findOrFail($id);
        $group->delete();
        return redirect()->back()->with('success', 'Kelompok jadwal berhasil dihapus!');
    }

    public function scheduleAssignDestroy(int|string $id)
    {
        $assign = ScheduleAssignment::findOrFail($id);
        $assign->delete();
        return redirect()->back()->with('success', 'Penugasan jadwal karyawan berhasil dihapus!');
    }

    public function evaluationUpdate(Request $request, int|string $id)
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

    public function evaluationDestroy(int|string $id)
    {
        $eval = TimeEvaluation::findOrFail($id);
        $eval->delete();
        return redirect()->back()->with('success', 'Parameter toleransi berhasil dihapus!');
    }

    public function periodDestroy(int|string $id)
    {
        $period = TimePeriod::findOrFail($id);
        $period->delete();
        return redirect()->back()->with('success', 'Periode Rekap Absensi berhasil dihapus!');
    }
}
