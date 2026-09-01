<?php

namespace App\Http\Controllers;

use App\Models\MaterialEquipment;
use App\Models\MaterialOutlineAgreement;
use App\Models\Partner;
use App\Models\Project;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /* -------------------------------------------------------------------------- */
    /* EQUIPMENT MASTER                                                           */
    /* -------------------------------------------------------------------------- */
    public function equipmentIndex(Request $request)
    {
        $query = MaterialEquipment::with('project');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('equipment_code', 'like', "%{$search}%")
                  ->orWhere('brand_model', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $perPage = (int) $request->input('per_page', 10);
        if ($perPage < 5 || $perPage > 100) {
            $perPage = 10;
        }

        $equipments = $query->orderBy('equipment_code')->paginate($perPage)->withQueryString();
        $projects = Project::orderBy('project_name')->get();
        $categories = MaterialEquipment::distinct()->pluck('category');

        $totalAll = MaterialEquipment::count();
        $totalOperational = MaterialEquipment::where('condition', 'Operational')->count();
        $totalMaintenance = MaterialEquipment::where('condition', 'Maintenance')->count();
        $totalValuation = MaterialEquipment::where('active', true)->sum('purchase_cost');

        return view('material.equipment.index', compact(
            'equipments',
            'projects',
            'categories',
            'totalAll',
            'totalOperational',
            'totalMaintenance',
            'totalValuation',
            'perPage'
        ));
    }

    public function equipmentCreate()
    {
        $projects = Project::orderBy('project_name')->get();
        return view('material.equipment.create', compact('projects'));
    }

    public function equipmentStore(Request $request)
    {
        $request->validate([
            'equipment_code' => 'required|string|max:50|unique:materials_equipment,equipment_code',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'brand_model' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'project_id' => 'nullable|exists:projects,id',
            'condition' => 'required|in:Operational,Maintenance,Broken,Standby',
            'purchase_date' => 'nullable|date',
            'purchase_cost' => 'nullable|numeric|min:0',
            'last_service_date' => 'nullable|date',
            'next_service_date' => 'nullable|date',
            'certification_expiry' => 'nullable|date',
            'notes' => 'nullable|string',
            'active' => 'nullable',
        ]);

        $equipment = MaterialEquipment::create([
            'equipment_code' => strtoupper(trim($request->equipment_code)),
            'name' => $request->name,
            'category' => $request->category,
            'brand_model' => $request->brand_model,
            'serial_number' => $request->serial_number,
            'project_id' => $request->project_id,
            'condition' => $request->condition,
            'purchase_date' => $request->purchase_date,
            'purchase_cost' => $request->purchase_cost ?? 0,
            'last_service_date' => $request->last_service_date,
            'next_service_date' => $request->next_service_date,
            'certification_expiry' => $request->certification_expiry,
            'notes' => $request->notes,
            'active' => $request->has('active') ? (bool) $request->active : true,
        ]);

        return redirect()->route('material.equipment.show', $equipment->id)->with('success', 'Data Peralatan / Alat Berat baru berhasil ditambahkan!');
    }

    public function equipmentShow(int $id)
    {
        $equipment = MaterialEquipment::with('project')->findOrFail($id);
        return view('material.equipment.show', compact('equipment'));
    }

    public function equipmentEdit(int $id)
    {
        $equipment = MaterialEquipment::with('project')->findOrFail($id);
        $projects = Project::orderBy('project_name')->get();
        return view('material.equipment.edit', compact('equipment', 'projects'));
    }

    public function equipmentUpdate(Request $request, int $id)
    {
        $equipment = MaterialEquipment::findOrFail($id);

        $request->validate([
            'equipment_code' => 'required|string|max:50|unique:materials_equipment,equipment_code,' . $id,
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'brand_model' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'project_id' => 'nullable|exists:projects,id',
            'condition' => 'required|in:Operational,Maintenance,Broken,Standby',
            'purchase_date' => 'nullable|date',
            'purchase_cost' => 'nullable|numeric|min:0',
            'last_service_date' => 'nullable|date',
            'next_service_date' => 'nullable|date',
            'certification_expiry' => 'nullable|date',
            'notes' => 'nullable|string',
            'active' => 'nullable',
        ]);

        $equipment->update([
            'equipment_code' => strtoupper(trim($request->equipment_code)),
            'name' => $request->name,
            'category' => $request->category,
            'brand_model' => $request->brand_model,
            'serial_number' => $request->serial_number,
            'project_id' => $request->project_id,
            'condition' => $request->condition,
            'purchase_date' => $request->purchase_date,
            'purchase_cost' => $request->purchase_cost ?? 0,
            'last_service_date' => $request->last_service_date,
            'next_service_date' => $request->next_service_date,
            'certification_expiry' => $request->certification_expiry,
            'notes' => $request->notes,
            'active' => $request->has('active') ? (bool) $request->active : false,
        ]);

        return redirect()->route('material.equipment.show', $equipment->id)->with('success', 'Data Peralatan berhasil diperbarui!');
    }

    public function equipmentDestroy(int $id)
    {
        $equipment = MaterialEquipment::findOrFail($id);
        $equipment->delete();

        return redirect()->route('material.equipment')->with('success', 'Data Peralatan berhasil dihapus!');
    }

    /* -------------------------------------------------------------------------- */
    /* OUTLINE AGREEMENTS (KONTRAK PAYUNG)                                        */
    /* -------------------------------------------------------------------------- */
    public function outlineAgreementIndex(Request $request)
    {
        $query = MaterialOutlineAgreement::with('partner');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('agreement_number', 'like', "%{$search}%")
                  ->orWhereHas('partner', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('agreement_type')) {
            $query->where('agreement_type', $request->agreement_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = (int) $request->input('per_page', 10);
        if ($perPage < 5 || $perPage > 100) {
            $perPage = 10;
        }

        $agreements = $query->orderByDesc('start_date')->paginate($perPage)->withQueryString();
        $partners = Partner::where('active', true)->orderBy('name')->get();

        $totalAll = MaterialOutlineAgreement::count();
        $totalActive = MaterialOutlineAgreement::where('status', 'Active')->count();
        $totalTargetValue = MaterialOutlineAgreement::where('status', 'Active')->sum('target_value');

        return view('material.outline-agreement.index', compact('agreements', 'partners', 'totalAll', 'totalActive', 'totalTargetValue', 'perPage'));
    }

    public function outlineAgreementCreate()
    {
        $partners = Partner::where('active', true)->orderBy('name')->get();
        return view('material.outline-agreement.create', compact('partners'));
    }

    public function outlineAgreementStore(Request $request)
    {
        $request->validate([
            'agreement_number' => 'required|string|max:50|unique:materials_outline_agreements,agreement_number',
            'partner_id' => 'required|exists:partners,id',
            'title' => 'required|string|max:255',
            'agreement_type' => 'required|in:Quantity Contract,Value Contract',
            'target_value' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:Draft,Active,Expired,Terminated',
            'terms' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $agreement = MaterialOutlineAgreement::create([
            'agreement_number' => strtoupper(trim($request->agreement_number)),
            'partner_id' => $request->partner_id,
            'title' => $request->title,
            'agreement_type' => $request->agreement_type,
            'target_value' => $request->target_value,
            'currency' => strtoupper(trim($request->currency)),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'terms' => $request->terms,
            'notes' => $request->notes,
        ]);

        return redirect()->route('material.outline-agreement.show', $agreement->id)->with('success', 'Kontrak Payung (Outline Agreement) baru berhasil ditambahkan!');
    }

    public function outlineAgreementShow(int $id)
    {
        $agreement = MaterialOutlineAgreement::with('partner')->findOrFail($id);
        return view('material.outline-agreement.show', compact('agreement'));
    }

    public function outlineAgreementEdit(int $id)
    {
        $agreement = MaterialOutlineAgreement::with('partner')->findOrFail($id);
        $partners = Partner::where('active', true)->orderBy('name')->get();
        return view('material.outline-agreement.edit', compact('agreement', 'partners'));
    }

    public function outlineAgreementUpdate(Request $request, int $id)
    {
        $agreement = MaterialOutlineAgreement::findOrFail($id);

        $request->validate([
            'agreement_number' => 'required|string|max:50|unique:materials_outline_agreements,agreement_number,' . $id,
            'partner_id' => 'required|exists:partners,id',
            'title' => 'required|string|max:255',
            'agreement_type' => 'required|in:Quantity Contract,Value Contract',
            'target_value' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:Draft,Active,Expired,Terminated',
            'terms' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $agreement->update([
            'agreement_number' => strtoupper(trim($request->agreement_number)),
            'partner_id' => $request->partner_id,
            'title' => $request->title,
            'agreement_type' => $request->agreement_type,
            'target_value' => $request->target_value,
            'currency' => strtoupper(trim($request->currency)),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'terms' => $request->terms,
            'notes' => $request->notes,
        ]);

        return redirect()->route('material.outline-agreement.show', $agreement->id)->with('success', 'Data Kontrak Payung berhasil diperbarui!');
    }

    public function outlineAgreementDestroy(int $id)
    {
        $agreement = MaterialOutlineAgreement::findOrFail($id);
        $agreement->delete();

        return redirect()->route('material.outline-agreement')->with('success', 'Kontrak Payung berhasil dihapus!');
    }
}
