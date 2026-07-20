<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        return view('dashboard.projects', [
            'projects' => Project::oldest()->paginate(25)
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

        Project::create($validData);
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