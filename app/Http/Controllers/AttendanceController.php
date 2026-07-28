<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Regional;
use App\Models\Segment;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $dashboardService = new DashboardService();
        $employeeId = session('user.employee_id');

        // Check if logged in user is a regular Employee (Self-Service)
        if ($employeeId) {
            $employee = Employee::findOrFail($employeeId);
            $today = Carbon::today()->toDateString();
            
            // Get today's attendance record
            $todayAttendance = Attendance::where('employee_id', $employeeId)
                ->where('date', $today)
                ->first();

            // Get monthly history for this employee
            $history = Attendance::where('employee_id', $employeeId)
                ->orderBy('date', 'desc')
                ->paginate(10)
                ->withQueryString();

            return view('hr.attendances-employee', [
                'employee' => $employee,
                'todayAttendance' => $todayAttendance,
                'history' => $history,
                'today' => $today,
            ]);
        }

        $date = $request->input('date', Carbon::today()->toDateString());
        $regional = $request->input('regional', 'All');
        $segment = $request->input('segment', 'All');

        // Get filter options
        $regionals = Regional::orderBy('name')->get();
        $segments = Segment::orderBy('name')->get();
        $months = $dashboardService->getMonths();

        $search = $request->input('search');

        // Format selected date's month to match database seeder format
        $monthsIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $time = strtotime($date);
        $monthNum = (int)date('n', $time);
        $year = date('Y', $time);
        $monthStr = $monthsIndo[$monthNum] . ' ' . $year;

        // Get filtered employees list
        $employeesQuery = Employee::where('month', $monthStr);
        if ($regional !== 'All') {
            $employeesQuery->where('regional', $regional);
        }
        if ($segment !== 'All') {
            $employeesQuery->where('segment', $segment);
        }
        if (!empty($search)) {
            $employeesQuery->where('name', 'like', '%' . $search . '%');
        }

        // Get all matching employee IDs for overall statistics
        $allFilteredEmployeeIds = (clone $employeesQuery)->pluck('id')->toArray();

        // Fetch attendance stats for ALL matching employees on selected date
        $allAttendances = Attendance::whereIn('employee_id', $allFilteredEmployeeIds)
            ->where('date', $date)
            ->get();

        $stats = [
            'present' => $allAttendances->where('status', 'Hadir')->count(),
            'sick_permission' => $allAttendances->whereIn('status', ['Sakit', 'Izin'])->count(),
            'absent' => $allAttendances->where('status', 'Alfa')->count(),
            'overtime' => $allAttendances->sum('overtime_hours'),
        ];

        // Paginate employees for table view (10 per page)
        $employees = $employeesQuery->orderBy('name')->paginate(10)->withQueryString();

        // Fetch attendance records for the CURRENT page
        $currentPageEmployeeIds = $employees->pluck('id')->toArray();
        $attendances = Attendance::whereIn('employee_id', $currentPageEmployeeIds)
            ->where('date', $date)
            ->get()
            ->keyBy('employee_id');

        return view('hr.attendances', [
            'employees' => $employees,
            'attendances' => $attendances,
            'stats' => $stats,
            'date' => $date,
            'selectedRegional' => $regional,
            'selectedSegment' => $segment,
            'regionals' => $regionals,
            'segments' => $segments,
            'months' => $months,
            'search' => $search,
        ]);
    }

    public function store(Request $request)
    {
        // Self-Service Clock In / Clock Out
        if ($request->has('action')) {
            $employeeId = session('user.employee_id');
            if (!$employeeId) {
                return redirect()->back()->withErrors(['error' => 'Akses ditolak. Anda tidak terhubung ke data pegawai mana pun.']);
            }
            $date = Carbon::today()->toDateString();
            $action = $request->input('action');

            if ($action === 'clock_in') {
                // Prevent duplicate clock in
                $exists = Attendance::where('employee_id', $employeeId)->where('date', $date)->exists();
                if ($exists) {
                    return redirect()->back()->withErrors(['error' => 'Anda sudah mencatat kehadiran hari ini.']);
                }

                Attendance::create([
                    'employee_id' => $employeeId,
                    'date' => $date,
                    'status' => 'Hadir',
                    'clock_in' => Carbon::now()->toTimeString(),
                ]);
                return redirect()->back()->with('success', 'Clock In berhasil dicatat pada jam ' . Carbon::now()->format('H:i') . '!');
            } elseif ($action === 'clock_out') {
                $attendance = Attendance::where('employee_id', $employeeId)
                    ->where('date', $date)
                    ->first();
                if (!$attendance) {
                    return redirect()->back()->withErrors(['error' => 'Anda belum melakukan Clock In hari ini.']);
                }
                if ($attendance->clock_out) {
                    return redirect()->back()->withErrors(['error' => 'Anda sudah melakukan Clock Out hari ini.']);
                }
                $attendance->update([
                    'clock_out' => Carbon::now()->toTimeString(),
                ]);
                return redirect()->back()->with('success', 'Clock Out berhasil dicatat pada jam ' . Carbon::now()->format('H:i') . '!');
            }
        }

        $validData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|string|in:Hadir,Sakit,Izin,Alfa',
            'clock_in' => 'nullable|string',
            'clock_out' => 'nullable|string',
            'overtime_hours' => 'nullable|numeric|min:0|max:24',
            'notes' => 'nullable|string',
        ]);

        Attendance::updateOrCreate(
            [
                'employee_id' => $validData['employee_id'],
                'date' => $validData['date'],
            ],
            [
                'status' => $validData['status'],
                'clock_in' => $validData['clock_in'] ? Carbon::parse($validData['clock_in'])->toTimeString() : null,
                'clock_out' => $validData['clock_out'] ? Carbon::parse($validData['clock_out'])->toTimeString() : null,
                'overtime_hours' => $validData['overtime_hours'] ?? 0.00,
                'notes' => $validData['notes'],
            ]
        );

        return redirect()->back()->with('success', 'Kehadiran berhasil dicatat!');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->back()->with('success', 'Catatan kehadiran berhasil dihapus!');
    }
}
