<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectMasterData;

class ProjectMasterController extends Controller
{
    private function getMetaForCategory($category)
    {
        $configs = [
            'feasibility_metrics' => [
                'title' => 'Feasibility Metrics',
                'subtitle' => 'Pengaturan master data untuk feasibility metrics.',
                'breadcrumb' => 'Feasibility Metrics',
                'route_name' => 'project.master.feasibility-metrics',
                'columns' => ['code', 'name', 'uom', 'description', 'validity_start', 'validity_end'],
                'has_uom' => true,
            ],
            'project_category' => [
                'title' => 'Project Category',
                'subtitle' => 'Pengaturan master data untuk project category.',
                'breadcrumb' => 'Project Category',
                'route_name' => 'project.master.categories',
                'columns' => ['code', 'name', 'description', 'validity_start', 'validity_end'],
            ],
            'project_type' => [
                'title' => 'Project Type',
                'subtitle' => 'Pengaturan master data untuk Project Type.',
                'breadcrumb' => 'Project Type',
                'route_name' => 'project.master.types',
                'columns' => ['code', 'name', 'description', 'validity_start', 'validity_end'],
            ],
            'object_type' => [
                'title' => 'Object Type',
                'subtitle' => 'Pengaturan master data untuk Object Type.',
                'breadcrumb' => 'Object Type',
                'route_name' => 'project.master.object-types',
                'columns' => ['code', 'name', 'scope', 'project_type', 'description', 'validity_start', 'validity_end'],
                'has_scope' => true,
                'has_project_type' => true,
            ],
            'status' => [
                'title' => 'Status',
                'subtitle' => 'Pengaturan master data untuk status.',
                'breadcrumb' => 'Status',
                'route_name' => 'project.master.statuses',
                'columns' => ['seq', 'code', 'name', 'description', 'validity_start', 'validity_end'],
                'has_seq' => true,
            ],
            'master_code' => [
                'title' => 'Master Code',
                'subtitle' => 'Project System - Master Code',
                'breadcrumb' => 'Master Code',
                'route_name' => 'project.master.codes',
                'columns' => ['code', 'validity_start', 'validity_end'],
            ],
            'project_role' => [
                'title' => 'Project Role',
                'subtitle' => 'Project System - Project Role',
                'breadcrumb' => 'Project Role',
                'route_name' => 'project.master.roles',
                'columns' => ['code', 'name', 'description', 'validity_start', 'validity_end'],
            ],
            'wbs_payroll_category' => [
                'title' => 'Master Data WBS Payroll Category',
                'subtitle' => 'Pengelolaan Master Data WBS Payroll Category',
                'breadcrumb' => 'WBS Payroll Category',
                'route_name' => 'project.master.wbs-payroll-categories',
                'columns' => ['id_category', 'category_name', 'coa', 'created_at', 'updated_at'],
                'is_wbs_payroll' => true,
            ],
        ];

        return $configs[$category] ?? [
            'title' => ucwords(str_replace('_', ' ', $category)),
            'subtitle' => 'Pengaturan master data ' . str_replace('_', ' ', $category),
            'breadcrumb' => ucwords(str_replace('_', ' ', $category)),
            'route_name' => 'project.master.' . str_replace('_', '-', $category),
            'columns' => ['code', 'name', 'description', 'validity_start', 'validity_end'],
        ];
    }

    private function renderMasterView($category, Request $request)
    {
        $search = $request->get('search');
        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $query = ProjectMasterData::where('category', $category);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('coa', 'like', "%{$search}%");
            });
        }

        if ($category === 'status') {
            $query->orderBy('seq', 'asc')->orderBy('code', 'asc');
        } else {
            $query->orderBy('id', 'asc');
        }

        $items = $query->paginate($perPage)->withQueryString();
        $meta = $this->getMetaForCategory($category);

        return view('operations.master.index', compact('items', 'meta', 'category', 'search', 'perPage'));
    }

    public function feasibilityMetrics(Request $request)
    {
        return $this->renderMasterView('feasibility_metrics', $request);
    }

    public function projectCategories(Request $request)
    {
        return $this->renderMasterView('project_category', $request);
    }

    public function projectTypes(Request $request)
    {
        return $this->renderMasterView('project_type', $request);
    }

    public function objectTypes(Request $request)
    {
        return $this->renderMasterView('object_type', $request);
    }

    public function statuses(Request $request)
    {
        return $this->renderMasterView('status', $request);
    }

    public function masterCodes(Request $request)
    {
        return $this->renderMasterView('master_code', $request);
    }

    public function projectRoles(Request $request)
    {
        return $this->renderMasterView('project_role', $request);
    }

    public function wbsPayrollCategories(Request $request)
    {
        return $this->renderMasterView('wbs_payroll_category', $request);
    }

    public function wbsPayrollCategoryShow($id)
    {
        $item = ProjectMasterData::where('category', 'wbs_payroll_category')->findOrFail($id);
        $meta = $this->getMetaForCategory('wbs_payroll_category');

        return view('operations.master.wbs-payroll-detail', compact('item', 'meta'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string',
            'code' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'uom' => 'nullable|string|max:50',
            'scope' => 'nullable|string|max:100',
            'project_type' => 'nullable|string|max:100',
            'seq' => 'nullable|integer',
            'coa' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'validity_start' => 'nullable|string|max:50',
            'validity_end' => 'nullable|string|max:50',
        ]);

        if (empty($validated['validity_start'])) {
            $validated['validity_start'] = '2024-01-01 00:00:00';
        }
        if (empty($validated['validity_end'])) {
            $validated['validity_end'] = '9999-12-31 00:00:00';
        }

        ProjectMasterData::create($validated);

        return back()->with('success', 'Data Master berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $item = ProjectMasterData::findOrFail($id);

        $validated = $request->validate([
            'code' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'uom' => 'nullable|string|max:50',
            'scope' => 'nullable|string|max:100',
            'project_type' => 'nullable|string|max:100',
            'seq' => 'nullable|integer',
            'coa' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'validity_start' => 'nullable|string|max:50',
            'validity_end' => 'nullable|string|max:50',
        ]);

        $item->update($validated);

        return back()->with('success', 'Data Master berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $item = ProjectMasterData::findOrFail($id);
        $item->delete();

        return back()->with('success', 'Data Master berhasil dihapus!');
    }

    public function export(Request $request, $category)
    {
        $items = ProjectMasterData::where('category', $category)->orderBy('id', 'asc')->get();
        $meta = $this->getMetaForCategory($category);

        $filename = 'Master_' . str_replace(' ', '_', $meta['title']) . '_' . date('Ymd_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($items, $meta) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Code', 'Name', 'UOM / Scope', 'COA', 'Description', 'Validity Start', 'Validity End', 'Created At']);

            foreach ($items as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->code ?? '-',
                    $item->name,
                    $item->uom ?? $item->scope ?? '-',
                    $item->coa ?? '-',
                    $item->description ?? '-',
                    $item->validity_start,
                    $item->validity_end,
                    $item->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
