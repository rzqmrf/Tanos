<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Regional;
use App\Models\Segment;
use App\Services\DashboardService;

class ProjectController extends Controller
{
    public function index()
    {
        $dashboardService = new DashboardService();

        return view('dashboard.projects', [
            'projects' => Project::latest()->paginate(25),
            'regionals' => Regional::orderBy('name')->get(),
            'segments' => Segment::orderBy('name')->get(),
            'months' => $dashboardService->getMonths(),
        ]);
    }

    public function store(Request $request) 
    {
        $validData = $request->validate([
            'month' => 'required|string|max:255',
            'regional' => 'required|string|max:255',
            'segment' => 'required|string|max:255',
            'cost' => 'required|numeric',
            'active' => 'required|integer', // 1 untuk aktif, 0 untuk non-aktif
        ]);

        $project = Project::create($validData);

        // Trigger notification for all users
        foreach (\App\Models\User::all() as $u) {
            \App\Models\Notification::create([
                'user_id' => $u->id,
                'title' => 'Proyek Baru Aktif',
                'message' => 'Proyek segment ' . $project->segment . ' di ' . $project->regional . ' senilai Rp ' . number_format($project->cost, 0, ',', '.') . ' telah ditambahkan.',
                'type' => 'project',
            ]);
        }

        return redirect()->back()->with('success', 'Project baru sukses dibuat!');
    }

    public function update(Request $request, Project $project) 
    {
        $validData = $request->validate([
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
