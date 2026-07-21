<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DashboardService;

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
        // Redirect to login if user is not in session
        if (!session()->has('user')) {
            return redirect()->route('login');
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
        if (!session()->has('user')) {
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
