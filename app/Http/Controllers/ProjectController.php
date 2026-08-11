<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Regional;
use App\Models\Segment;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $dashboardService = new DashboardService();

        return view('operations.projects', [
            'projects' => Project::oldest()->paginate(25),
            'regionals' => Regional::orderBy('name')->get(),
            'segments' => Segment::orderBy('name')->get(),
            'months' => $dashboardService->getMonths(),
        ]);
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'project_code' => 'required|string|max:255|unique:projects,project_code',
            'project_name' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'contract_number' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'cost_center' => 'nullable|string|max:255',
            'fund_center' => 'nullable|string|max:255',
            'month' => 'required|string|max:255',
            'regional' => 'required|string|max:255',
            'segment' => 'required|string|max:255',
            'cost' => 'required|numeric',
            'active' => 'required|integer',  // 1 untuk aktif, 0 untuk non-aktif
        ]);

        if (empty($validData['cost_center'])) {
            $validData['cost_center'] = 'CC-' . strtoupper(str_replace([' ', '-'], '', $validData['project_code']));
        }
        if (empty($validData['fund_center'])) {
            $validData['fund_center'] = 'FC-' . strtoupper(str_replace([' ', '-'], '', $validData['project_code']));
        }

        $project = Project::create($validData);

        // Trigger notification for all users
        foreach (\App\Models\User::all() as $u) {
            \App\Models\Notification::create([
                'user_id' => $u->id,
                'title' => 'Proyek Baru Aktif',
                'message' => 'Proyek ' . $project->project_name . ' (' . $project->project_code . ') di ' . $project->regional . ' senilai Rp ' . number_format($project->cost, 0, ',', '.') . ' telah ditambahkan.',
                'type' => 'project',
            ]);
        }

        return redirect()->back()->with('success', 'Project baru sukses dibuat!');
    }

    public function update(Request $request, Project $project)
    {
        $validData = $request->validate([
            'project_code' => 'required|string|max:255|unique:projects,project_code,' . $project->id,
            'project_name' => 'required|string|max:255',
            'customer_name' => 'required|string|max:255',
            'contract_number' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'cost_center' => 'required|string|max:255',
            'fund_center' => 'required|string|max:255',
            'month' => 'required|string|max:255',
            'regional' => 'required|string|max:255',
            'segment' => 'required|string|max:255',
            'cost' => 'required|numeric',
            'active' => 'required|integer',
        ]);

        $project->update($validData);
        return redirect()->back()->with('success', 'Project sukses diupdate!');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->back()->with('success', 'Project sukses dihapus!');
    }
}
