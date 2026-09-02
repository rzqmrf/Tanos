<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Regional;
use App\Models\Segment;
use App\Models\Partner;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $dashboardService = new DashboardService();
        $search = $request->input('search');

        $query = Project::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('project_code', 'like', "%{$search}%")
                  ->orWhere('id_project_humanis', 'like', "%{$search}%")
                  ->orWhere('project_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('vendor', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $projects = $query->orderByRaw('CAST(id_project_humanis AS UNSIGNED) ASC')
                          ->orderBy('id', 'asc')
                          ->paginate(25)
                          ->withQueryString();

        return view('operations.projects', [
            'projects' => $projects,
            'search' => $search,
            'regionals' => Regional::orderBy('name')->get(),
            'segments' => Segment::orderBy('name')->get(),
            'partners' => Partner::orderBy('name')->get(),
            'months' => $dashboardService->getMonths(),
        ]);
    }

    public function show(Project $project)
    {
        return view('operations.project-view', [
            'project' => $project,
        ]);
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'project_code' => 'required|string|max:255|unique:projects,project_code',
            'id_project_humanis' => 'nullable|string|max:255',
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'customer_name' => 'nullable|string|max:255',
            'vendor' => 'nullable|string|max:255',
            'contract_number' => 'nullable|string|max:255',
            'project_category' => 'nullable|string|max:255',
            'contract_type' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'regional_unit' => 'nullable|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'validity_start' => 'nullable|date',
            'validity_end' => 'nullable|date',
            'cost_center' => 'nullable|string|max:255',
            'fund_center' => 'nullable|string|max:255',
            'month' => 'nullable|string|max:255',
            'regional' => 'nullable|string|max:255',
            'segment' => 'nullable|string|max:255',
            'cost' => 'nullable|numeric',
            'active' => 'nullable|integer',
        ]);

        if (empty($validData['customer_name']) && !empty($validData['vendor'])) {
            $validData['customer_name'] = $validData['vendor'];
        }
        if (empty($validData['vendor']) && !empty($validData['customer_name'])) {
            $validData['vendor'] = $validData['customer_name'];
        }
        if (empty($validData['cost_center'])) {
            $validData['cost_center'] = 'CC-' . strtoupper(str_replace([' ', '-', '/'], '', $validData['project_code']));
        }
        if (empty($validData['fund_center'])) {
            $validData['fund_center'] = 'FC-' . strtoupper(str_replace([' ', '-', '/'], '', $validData['project_code']));
        }
        if (empty($validData['month'])) {
            $validData['month'] = date('F Y');
        }
        if (empty($validData['regional'])) {
            $validData['regional'] = $validData['regional_unit'] ?? 'Regional 3';
        }
        if (empty($validData['segment'])) {
            $validData['segment'] = $validData['project_category'] ?? 'TAD Operasional';
        }
        if (!isset($validData['cost'])) {
            $validData['cost'] = 1000000000;
        }
        if (!isset($validData['active'])) {
            $validData['active'] = 1;
        }

        $project = Project::create($validData);

        // Notification
        foreach (\App\Models\User::all() as $u) {
            \App\Models\Notification::create([
                'user_id' => $u->id,
                'title' => 'Project Definition Ditambahkan',
                'message' => 'Proyek ' . $project->project_name . ' (' . $project->project_code . ') telah didaftarkan.',
                'type' => 'project',
            ]);
        }

        return redirect()->route('projects.index')->with('success', 'Project Definition baru sukses dibuat!');
    }

    public function update(Request $request, Project $project)
    {
        $validData = $request->validate([
            'project_code' => 'required|string|max:255|unique:projects,project_code,' . $project->id,
            'id_project_humanis' => 'nullable|string|max:255',
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'customer_name' => 'nullable|string|max:255',
            'vendor' => 'nullable|string|max:255',
            'contract_number' => 'nullable|string|max:255',
            'project_category' => 'nullable|string|max:255',
            'contract_type' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'regional_unit' => 'nullable|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'validity_start' => 'nullable|date',
            'validity_end' => 'nullable|date',
            'cost_center' => 'nullable|string|max:255',
            'fund_center' => 'nullable|string|max:255',
            'month' => 'nullable|string|max:255',
            'regional' => 'nullable|string|max:255',
            'segment' => 'nullable|string|max:255',
            'cost' => 'nullable|numeric',
            'active' => 'nullable|integer',
        ]);

        if (empty($validData['customer_name']) && !empty($validData['vendor'])) {
            $validData['customer_name'] = $validData['vendor'];
        }
        if (empty($validData['vendor']) && !empty($validData['customer_name'])) {
            $validData['vendor'] = $validData['customer_name'];
        }

        $project->update($validData);
        return redirect()->route('projects.index')->with('success', 'Project Definition sukses diperbarui!');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Project Definition sukses dihapus!');
    }
}
