<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Regional;
use App\Models\Segment;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $employeeId = Auth::user()?->employee_id;

        // 1. Tampilan Self-Service Karyawan Regular
        if ($employeeId) {
            $employee = Employee::findOrFail($employeeId);
            $today = Carbon::today()->toDateString();

            return view('hr.attendances-employee', [
                'employee'        => $employee,
                'todayAttendance' => Attendance::where('employee_id', $employeeId)->where('date', $today)->first(),
                'history'         => Attendance::where('employee_id', $employeeId)
                                        ->where('date', '<=', $today)
                                        ->latest('date')->paginate(10)->withQueryString(),
                'upcoming'        => Attendance::where('employee_id', $employeeId)
                                        ->where('date', '>', $today)
                                        ->oldest('date')->get(),
                'today'           => $today,
            ]);
        }

        // 2. Tampilan Dashboard Admin / HR
        $date     = $request->input('date', Carbon::today()->toDateString());
        $regional = $request->input('regional', 'All');
        $segment  = $request->input('segment', 'All');
        $search   = $request->input('search');

        // Query Utama Employee
        $employeesQuery = Employee::query();

        if ($regional !== 'All') {
            $employeesQuery->where('regional', $regional);
        }
        if ($segment !== 'All') {
            $employeesQuery->where('segment', $segment);
        }
        if (!empty($search)) {
            $employeesQuery->where('name', 'like', '%' . $search . '%');
        }

        // Hitung Statistik Keseluruhan
        $allFilteredIds = (clone $employeesQuery)->pluck('id')->toArray();
        $allAttendances = Attendance::whereIn('employee_id', $allFilteredIds)->where('date', $date)->get();

        $stats = [
            'present'         => $allAttendances->where('status', 'Hadir')->count(),
            'sick_permission' => $allAttendances->whereIn('status', ['Sakit', 'Izin'])->count(),
            'absent'          => $allAttendances->where('status', 'Alfa')->count(),
            'overtime'        => $allAttendances->sum('overtime_hours'),
        ];

        // Paginate & Ambil Data Presensi (Urut ID biar sejajar sama Master Data)
        $employees   = $employeesQuery->orderBy('id')->paginate(25)->withQueryString();
        $attendances = Attendance::whereIn('employee_id', $employees->pluck('id')->toArray())
            ->where('date', $date)
            ->get()
            ->keyBy('employee_id');

        return view('hr.attendances', [
            'employees'        => $employees,
            'attendances'      => $attendances,
            'stats'            => $stats,
            'date'             => $date,
            'selectedRegional' => $regional,
            'selectedSegment'  => $segment,
            'regionals'        => Regional::orderBy('name')->get(),
            'segments'         => Segment::orderBy('name')->get(),
            'months'           => (new DashboardService())->getMonths(),
            'search'           => $search,
        ]);
    }

    public function store(Request $request)
    {
        // A. Self-Service Clock In / Clock Out dari User Karyawan
        if ($request->has('action')) {
            $employeeId = Auth::user()?->employee_id;
            if (!$employeeId) {
                return back()->withErrors(['error' => 'Akses ditolak. Lu gak terhubung ke data pegawai mana pun!']);
            }

            $date   = Carbon::today()->toDateString();
            $action = $request->input('action');

            if ($action === 'clock_in') {
                if (Attendance::where('employee_id', $employeeId)->where('date', $date)->exists()) {
                    return back()->withErrors(['error' => 'Lu udah mencatat kehadiran hari ini.']);
                }

                Attendance::create([
                    'employee_id' => $employeeId,
                    'date'        => $date,
                    'status'      => 'Hadir',
                    'clock_in'    => Carbon::now()->toTimeString(),
                ]);

                return back()->with('success', 'Clock In berhasil dicatat jam ' . Carbon::now()->format('H:i') . '!');
            } elseif ($action === 'clock_out') {
                $attendance = Attendance::where('employee_id', $employeeId)->where('date', $date)->first();

                if (!$attendance) {
                    return back()->withErrors(['error' => 'Lu belum melakukan Clock In hari ini.']);
                }
                if ($attendance->clock_out) {
                    return back()->withErrors(['error' => 'Lu udah melakukan Clock Out hari meletup!']);
                }

                $attendance->update(['clock_out' => Carbon::now()->toTimeString()]);

                return back()->with('success', 'Clock Out berhasil dicatat jam ' . Carbon::now()->format('H:i') . '!');
            }
        }

        // B. CRUD Input/Edit Presensi Manual dari Admin (HR/Admin only)
        if (!in_array(Auth::user()?->role, ['Admin', 'HR Manager'])) {
            abort(403, 'Hanya Admin/HR yang boleh input manual presensi.');
        }

        $validData = $request->validate([
            'employee_id'    => 'required|exists:employees,id',
            'date'           => 'required',
            'status'         => 'required',
            'clock_in'       => 'nullable',
            'clock_out'      => 'nullable',
            'overtime_hours' => 'nullable',
            'notes'          => 'nullable',
        ]);

        // Helper pintar konversi jam (Support AM/PM & 24 jam)
        $parseTime = function ($timeString) {
            if (empty($timeString) || $timeString === '-') return null;
            try {
                return Carbon::parse($timeString)->format('H:i:s');
            } catch (\Exception $e) {
                return null;
            }
        };

        // Format Tanggal
        $formattedDate = Carbon::parse($validData['date'])->toDateString();

        // Simpan / Update Data Absensi
        Attendance::updateOrCreate(
            [
                'employee_id' => $validData['employee_id'],
                'date'        => $formattedDate,
            ],
            [
                'status'         => $validData['status'],
                'clock_in'       => $parseTime($request->input('clock_in')),
                'clock_out'      => $parseTime($request->input('clock_out')),
                'overtime_hours' => is_numeric($request->input('overtime_hours')) ? $request->input('overtime_hours') : 0,
                'notes'          => $request->input('notes'),
            ]
        );

        return back()->with('success', 'Kehadiran berhasil dicatat!');
    }

    public function destroy(Attendance $attendance)
    {
        if (!in_array(Auth::user()?->role, ['Admin', 'HR Manager'])) {
            abort(403, 'Hanya Admin/HR yang boleh hapus presensi.');
        }

        $attendance->delete();
        return back()->with('success', 'Catatan kehadiran berhasil dihapus!');
    }
}