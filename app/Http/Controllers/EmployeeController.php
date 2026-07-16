<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!session()->has('user')) {
            return redirect()->route('login');
        }

        // Filter options
        $months = ['Januari 2025', 'Februari 2025', 'Maret 2025', 'April 2025', 'Mei 2025', 'Juni 2025'];
        $regionals = ['Jawa Barat', 'Jawa Timur', 'Jawa Tengah', 'Sumatera', 'Kalimantan'];
        $segments = ['Enterprise', 'Corporate', 'Government', 'SME', 'Retail'];

        // Get filter inputs
        $searchMonth = $request->input('month');
        $searchRegional = $request->input('regional');
        $searchSegment = $request->input('segment');

        $query = Employee::query();

        // Apply filters
        if ($searchMonth && $searchMonth !== 'All') {
            $query->where('month', $searchMonth);
        }
        if ($searchRegional && $searchRegional !== 'All') {
            $query->where('regional', $searchRegional);
        }
        if ($searchSegment && $searchSegment !== 'All') {
            $query->where('segment', $searchSegment);
        }

        // Order by latest and paginate
        $employees = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('dashboard.employees', [
            'employees' => $employees,
            'months' => $months,
            'regionals' => $regionals,
            'segments' => $segments,
            'currentMonth' => $searchMonth ?? 'All',
            'currentRegional' => $searchRegional ?? 'All',
            'currentSegment' => $searchSegment ?? 'All',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!session()->has('user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'month' => 'required|string',
            'regional' => 'required|string',
            'segment' => 'required|string',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')->with('success', 'Data pegawai berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!session()->has('user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $employee = Employee::findOrFail($id);

        $validated = $request->validate([
            'month' => 'required|string',
            'regional' => 'required|string',
            'segment' => 'required|string',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')->with('success', 'Data pegawai berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!session()->has('user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $employee = Employee::findOrFail($id);
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Data pegawai berhasil dihapus!');
    }
}
