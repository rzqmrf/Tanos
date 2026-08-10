<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\RabBudget;
use App\Models\RabBudgetItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RabBudgetController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $routeAction = $request->route()->getActionMethod();
            $modifyingActions = ['storeItem', 'updateItem', 'destroyItem', 'sendToSap'];
            if (in_array($routeAction, $modifyingActions)) {
                if (!in_array(session('user.role'), ['Admin', 'Project Manager', 'Finance Manager'])) {
                    abort(403, 'Akses ditolak. Hanya Admin, Project Manager, dan Finance Manager yang dapat melakukan aksi ini.');
                }
            }
            return $next($request);
        });
    }

    public function index()
    {
        // Get all projects with their RAB budget
        $projects = Project::with('rabBudget')->orderBy('created_at', 'desc')->get();

        return view('finance.rab.index', compact('projects'));
    }

    public function show($id)
    {
        $project = Project::findOrFail($id);
        
        // Find or create a RAB Budget for this project
        $rab = RabBudget::firstOrCreate(
            ['project_id' => $project->id],
            [
                'document_number' => 'RAB-' . strtoupper(Str::random(8)),
                'name' => 'Anggaran Biaya ' . ($project->project_name ?? 'Proyek'),
                'year' => date('Y'),
                'total_revenue' => 0,
                'total_cost' => 0,
                'sap_status' => 'Draft',
                'doc_status' => 'Draft'
            ]
        );

        // Load items
        $rab->load('items');

        // Accounts list for reference
        $coaAccounts = [
            '510100' => 'Upah Pokok',
            '510200' => 'Uang Transport',
            '510300' => 'Lembur',
            '510400' => 'Tunjangan Kinerja',
            '510500' => 'BPJS Kesehatan',
            '510600' => 'BPJS Ketenagakerjaan',
            '519900' => 'Non-Payroll Expense',
            '410100' => 'Revenue Pelanggan'
        ];

        return view('finance.rab.show', compact('project', 'rab', 'coaAccounts'));
    }

    public function storeItem(Request $request, $rabId)
    {
        $rab = RabBudget::findOrFail($rabId);

        $valid = $request->validate([
            'coa_code' => 'required|string',
            'fund_center' => 'nullable|string',
            'cost_center' => 'nullable|string',
            'profit_center' => 'nullable|string',
            'jan' => 'numeric|min:0',
            'feb' => 'numeric|min:0',
            'mar' => 'numeric|min:0',
            'apr' => 'numeric|min:0',
            'may' => 'numeric|min:0',
            'jun' => 'numeric|min:0',
            'jul' => 'numeric|min:0',
            'aug' => 'numeric|min:0',
            'sep' => 'numeric|min:0',
            'oct' => 'numeric|min:0',
            'nov' => 'numeric|min:0',
            'dec' => 'numeric|min:0',
        ]);

        // Calculate total amount
        $months = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
        $total = 0;
        foreach ($months as $m) {
            $total += (float) ($valid[$m] ?? 0);
        }
        $valid['total_amount'] = $total;
        $valid['rab_budget_id'] = $rab->id;

        RabBudgetItem::create($valid);

        $this->updateRabTotals($rab);

        return redirect()->back()->with('success', 'Komponen anggaran berhasil ditambahkan!');
    }

    public function updateItem(Request $request, $rabId, $itemId)
    {
        $rab = RabBudget::findOrFail($rabId);
        $item = RabBudgetItem::where('rab_budget_id', $rabId)->findOrFail($itemId);

        $valid = $request->validate([
            'coa_code' => 'required|string',
            'fund_center' => 'nullable|string',
            'cost_center' => 'nullable|string',
            'profit_center' => 'nullable|string',
            'jan' => 'numeric|min:0',
            'feb' => 'numeric|min:0',
            'mar' => 'numeric|min:0',
            'apr' => 'numeric|min:0',
            'may' => 'numeric|min:0',
            'jun' => 'numeric|min:0',
            'jul' => 'numeric|min:0',
            'aug' => 'numeric|min:0',
            'sep' => 'numeric|min:0',
            'oct' => 'numeric|min:0',
            'nov' => 'numeric|min:0',
            'dec' => 'numeric|min:0',
        ]);

        // Calculate total amount
        $months = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
        $total = 0;
        foreach ($months as $m) {
            $total += (float) ($valid[$m] ?? 0);
        }
        $valid['total_amount'] = $total;

        $item->update($valid);

        $this->updateRabTotals($rab);

        return redirect()->back()->with('success', 'Rincian anggaran berhasil diperbarui!');
    }

    public function destroyItem($rabId, $itemId)
    {
        $rab = RabBudget::findOrFail($rabId);
        $item = RabBudgetItem::where('rab_budget_id', $rabId)->findOrFail($itemId);
        $item->delete();

        $this->updateRabTotals($rab);

        return redirect()->back()->with('success', 'Komponen anggaran berhasil dihapus.');
    }

    public function sendToSap($id)
    {
        $rab = RabBudget::findOrFail($id);
        
        $rab->update([
            'sap_status' => 'Sent',
            'doc_status' => 'Approved'
        ]);

        // Mock SAP success notification
        foreach (\App\Models\User::all() as $u) {
            \App\Models\Notification::create([
                'user_id' => $u->id,
                'title' => 'RAB Budget Posted & Locked',
                'message' => 'Anggaran RAB #' . $rab->document_number . ' telah berhasil dikirim ke SAP dan status anggaran dikunci.',
                'type' => 'project',
            ]);
        }

        return redirect()->back()->with('success', 'RAB Budget berhasil diposting ke SAP! Status: Budget Locked.');
    }

    private function updateRabTotals(RabBudget $rab)
    {
        $items = $rab->items()->get();
        $totalCost = 0;
        $totalRevenue = 0;

        foreach ($items as $item) {
            // Revenue account starts with 4
            if (str_starts_with($item->coa_code, '4')) {
                $totalRevenue += (float) $item->total_amount;
            } else {
                $totalCost += (float) $item->total_amount;
            }
        }

        $rab->update([
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost
        ]);
    }
}
