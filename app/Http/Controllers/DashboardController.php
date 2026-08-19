<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DashboardService;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Notification;
use Carbon\Carbon;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService; // get dari class service

    /**
     * Inject DashboardService.
     */
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the dashboard home.
     */
    public function index()
    {
        $user = auth()->user();
        if (! $user) {
            return redirect()->route('login');
        }

        // If logged-in user is an Employee, serve their custom dashboard view
        if ($user->role === 'Employee') {
            $employeeId = $user->employee_id;
            $employee = $employeeId ? Employee::find($employeeId) : null;
            $userId = $user->id;


            // Fetch dynamic filters for navbar layout alignment
            $months = $this->dashboardService->getMonths();
            $regionals = $this->dashboardService->getRegionals();
            $segments = $this->dashboardService->getSegments();
            $defaultMonth = end($months);

            if ($employeeId && $employee) {
                $today = Carbon::today()->toDateString();
                
                // Get today's attendance
                $todayAttendance = Attendance::where('employee_id', $employeeId)
                    ->where('date', $today)
                    ->first();

                // Get monthly stats
                $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
                $endOfMonth = Carbon::now()->endOfMonth()->toDateString();
                
                $attendancesThisMonth = Attendance::where('employee_id', $employeeId)
                    ->whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->get();

                $stats = [
                    'present' => $attendancesThisMonth->where('status', 'Hadir')->count(),
                    'sick_permit' => $attendancesThisMonth->whereIn('status', ['Sakit', 'Izin'])->count(),
                    'absent' => $attendancesThisMonth->where('status', 'Alfa')->count(),
                    'overtime' => $attendancesThisMonth->sum('overtime_hours'),
                ];
            } else {
                $todayAttendance = null;
                $stats = [
                    'present' => 0,
                    'sick_permit' => 0,
                    'absent' => 0,
                    'overtime' => 0,
                ];
            }

            // Get recent user notifications
            $notifications = $userId 
                ? Notification::where('user_id', $userId)->latest()->take(5)->get() 
                : collect();

            return view('dashboard.employee', [
                'employee' => $employee,
                'todayAttendance' => $todayAttendance,
                'stats' => $stats,
                'notifications' => $notifications,
                'months' => $months,
                'regionals' => $regionals,
                'segments' => $segments,
                'currentMonth' => $defaultMonth,
                'currentRegional' => 'All',
                'currentSegment' => 'All',
            ]);
        }

        // Default initial filters (dynamic based on current real-time month)
        $months = $this->dashboardService->getMonths();
        $defaultMonth = end($months);
        $defaultRegional = 'All';
        $defaultSegment = 'All';

        $data = $this->dashboardService->calculateDashboardData($defaultMonth, $defaultRegional, $defaultSegment);

        return view('dashboard.index', [
            'months' => $this->dashboardService->getMonths(),
            'regionals' => $this->dashboardService->getRegionals(),
            'segments' => $this->dashboardService->getSegments(),
            'currentMonth' => $defaultMonth,
            'currentRegional' => $defaultRegional,
            'currentSegment' => $defaultSegment,
            'initialData' => $data
        ]);
    }

    /**
     * API endpoint to get filtered dashboard data.
     */
    public function apiData(Request $request)
    {
        // Return 401 Unauthorized for API requests if not logged in
        if (!auth()->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $months = $this->dashboardService->getMonths();
        $defaultMonth = end($months);

        $month = $request->query('month', $defaultMonth);
        $regional = $request->query('regional', 'All');
        $segment = $request->query('segment', 'All');

        $data = $this->dashboardService->calculateDashboardData($month, $regional, $segment);

        return response()->json($data);
    }
}
