<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!session()->has('user')) {
            return redirect()->route('login');
        }

        // Available options for filters
        $months = ['Januari 2025', 'Februari 2025', 'Maret 2025', 'April 2025', 'Mei 2025', 'Juni 2025'];
        $regionals = ['Regional 1', 'Regional 2', 'Regional 3', 'Regional 4'];
        $segments = ['Enterprise', 'Corporate', 'Government', 'SME', 'Retail'];

        // Get filter inputs
        $searchMonth = $request->input('month');
        $searchRegional = $request->input('regional');
        $searchSegment = $request->input('segment');
        $searchStatus = $request->input('status');

        $query = Project::query();

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
        if ($searchStatus !== null && $searchStatus !== 'All') {
            $query->where('active', $searchStatus === 'active');
        }

        // Order by latest and paginate
        $projects = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('dashboard.projects', [
            'projects' => $projects,
            'months' => $months,
            'regionals' => $regionals,
            'segments' => $segments,
            'currentMonth' => $searchMonth ?? 'All',
            'currentRegional' => $searchRegional ?? 'All',
            'currentSegment' => $searchSegment ?? 'All',
            'currentStatus' => $searchStatus ?? 'All',
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
            'cost' => 'required|numeric|min:0',
            'active' => 'sometimes|boolean',
        ]);

        // Default active status to true if not provided, or parse boolean
        $validated['active'] = $request->has('active');

        Project::create($validated);

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!session()->has('user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'month' => 'required|string',
            'regional' => 'required|string',
            'segment' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'active' => 'sometimes|boolean',
        ]);

        $validated['active'] = $request->has('active');

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!session()->has('user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Proyek berhasil dihapus!');
    }
}
