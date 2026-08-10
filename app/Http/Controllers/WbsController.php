<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\WbsElement;
use Illuminate\Http\Request;

class WbsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $routeAction = $request->route()->getActionMethod();
            $modifyingActions = ['store', 'update', 'destroy', 'sendToSap'];
            if (in_array($routeAction, $modifyingActions)) {
                if (!in_array(session('user.role'), ['Admin', 'Project Manager', 'Finance Manager'])) {
                    abort(403, 'Akses ditolak. Hanya Admin, Project Manager, dan Finance Manager yang dapat melakukan aksi ini.');
                }
            }
            return $next($request);
        });
    }

    public function index($projectId)
    {
        $project = Project::findOrFail($projectId);
        // Get root WBS elements (parent_id is null) with children
        $rootWbs = WbsElement::where('project_id', $projectId)
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        return view('operations.wbs', [
            'project' => $project,
            'rootWbs' => $rootWbs,
            // Categories for mapping
            'categories' => [
                'Upah Pokok',
                'Uang Transport',
                'Tunjangan Kinerja',
                'Lembur',
                'BPJS Kesehatan',
                'BPJS Ketenagakerjaan',
                'Non-Payroll Expense',
                'Revenue'
            ]
        ]);
    }

    public function store(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);

        $validData = $request->validate([
            'parent_id' => 'nullable|exists:wbs_elements,id',
            'wbs_code' => 'required|string|max:255',
            'wbs_name' => 'required|string|max:255',
            'wbs_category' => 'required|string',
            'weight' => 'required|integer|min:0|max:100',
            'expected_start' => 'nullable|date',
            'expected_end' => 'nullable|date',
        ]);

        $validData['project_id'] = $projectId;

        WbsElement::create($validData);

        return redirect()->back()->with('success', 'Struktur WBS baru sukses ditambahkan!');
    }

    public function update(Request $request, $projectId, $id)
    {
        $wbs = WbsElement::where('project_id', $projectId)->findOrFail($id);

        $validData = $request->validate([
            'wbs_code' => 'required|string|max:255',
            'wbs_name' => 'required|string|max:255',
            'wbs_category' => 'required|string',
            'weight' => 'required|integer|min:0|max:100',
            'expected_start' => 'nullable|date',
            'expected_end' => 'nullable|date',
        ]);

        $wbs->update($validData);

        return redirect()->back()->with('success', 'Data WBS sukses diperbarui!');
    }

    public function destroy($projectId, $id)
    {
        $wbs = WbsElement::where('project_id', $projectId)->findOrFail($id);
        $wbs->delete();

        return redirect()->back()->with('success', 'WBS dan sub-strukturnya sukses dihapus!');
    }

    public function sendToSap($projectId)
    {
        $project = Project::findOrFail($projectId);
        
        // Mock sending to SAP
        WbsElement::where('project_id', $projectId)->update(['sent_to_sap' => true]);

        // Create notification
        foreach (\App\Models\User::all() as $u) {
            \App\Models\Notification::create([
                'user_id' => $u->id,
                'title' => 'WBS Sent to SAP',
                'message' => 'Struktur WBS untuk proyek segment ' . $project->segment . ' telah sukses dibuat & diposting ke SAP.',
                'type' => 'project',
            ]);
        }

        return redirect()->back()->with('success', 'Struktur WBS sukses dikirim & disinkronkan ke SAP! Status: WBS Structure SAP Created.');
    }
}
