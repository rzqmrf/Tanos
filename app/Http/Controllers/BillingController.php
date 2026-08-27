<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\PranotaBilling;
use App\Models\NotaBilling;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BillingController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $routeAction = $request->route()->getActionMethod();
            $modifyingActions = ['storePranota', 'approvePranota', 'doNota', 'postNota'];
            if (in_array($routeAction, $modifyingActions)) {
                if (!\App\Models\RolePermission::hasPermission(auth()->user()?->role, 'invoices')) {
                    abort(403, 'Akses ditolak. Role Anda tidak memiliki izin billing/invoices.');
                }
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $project_id = $request->input('project_id');

        // Filter queries
        $pranotaQuery = PranotaBilling::with(['project', 'period', 'items']);
        $notaQuery = NotaBilling::with(['project', 'items']);

        if ($project_id) {
            $pranotaQuery->where('project_id', $project_id);
            $notaQuery->where('project_id', $project_id);
        }

        $pranotas = $pranotaQuery->get();
        $notas = $notaQuery->latest()->get();
        $projects = Project::orderBy('segment')->get();

        // Categorize Pranotas (Page 10)
        $belumTerbilling = $pranotas->where('status', 'Belum Terbilling');
        $siapTerbilling = $pranotas->where('status', 'Siap Terbilling');
        $sudahTerbilling = $pranotas->where('status', 'Sudah Terbilling');

        return view('finance.billing', [
            'belumTerbilling' => $belumTerbilling,
            'siapTerbilling' => $siapTerbilling,
            'sudahTerbilling' => $sudahTerbilling,
            'notas' => $notas,
            'projects' => $projects,
        ]);
    }

    public function storePranota(Request $request)
    {
        $validData = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'pranota_number' => 'required|string|unique:pranota_billings,pranota_number',
            'amount' => 'required|numeric|min:0',
        ]);

        $validData['status'] = 'Belum Terbilling';

        $pranota = PranotaBilling::create($validData);

        // Auto-generate detailed item for manual pranota (reverse calculation)
        $totalVal = $pranota->amount;
        $dpp = $totalVal / 1.221;
        $fee = $dpp * 0.10;
        $ppn = ($dpp + $fee) * 0.11;

        \App\Models\PranotaBillingItem::create([
            'pranota_billing_id' => $pranota->id,
            'item_name' => 'Jasa Penyediaan Tenaga Kerja & Operasional (Manual)',
            'dpp_amount' => $dpp,
            'management_fee_rate' => 10.00,
            'management_fee_amount' => $fee,
            'ppn_rate' => 11.00,
            'ppn_amount' => $ppn,
            'total_amount' => $totalVal,
        ]);

        return redirect()->back()->with('success', 'Pranota Manual sukses dibuat!');
    }

    public function approvePranota($id)
    {
        $pranota = PranotaBilling::findOrFail($id);
        $pranota->update(['status' => 'Siap Terbilling']);

        return redirect()->back()->with('success', 'Status Pranota sukses disetujui (Siap Terbilling)!');
    }

    public function doNota(Request $request)
    {
        $request->validate([
            'pranota_ids' => 'required|array',
            'pranota_ids.*' => 'exists:pranota_billings,id',
            'project_id' => 'required|exists:projects,id',
            'nota_number' => 'required|string|unique:nota_billings,nota_number',
        ]);

        // Pastikan semua pranota berasal dari project yang sama (cegah nota tercampur antar project)
        $pranotas = PranotaBilling::whereIn('id', $request->pranota_ids)
            ->where('project_id', $request->project_id)
            ->with('items')
            ->get();

        // Jika ada pranota dari project berbeda, tolak permintaan
        if ($pranotas->count() !== count($request->pranota_ids)) {
            return redirect()->back()->withErrors([
                'error' => 'Terdapat pranota yang berasal dari project berbeda. Nota hanya boleh berisi pranota dari project yang sama.'
            ]);
        }

        $totalAmount = $pranotas->sum('amount');

        // Create Nota Billing (Page 11-12)
        $nota = NotaBilling::create([
            'project_id' => $request->project_id,
            'nota_number' => $request->nota_number,
            'amount' => $totalAmount,
            'status' => 'Draft',
        ]);

        // Copy Pranota items to Nota Billing items
        foreach ($pranotas as $pranota) {
            foreach ($pranota->items as $pItem) {
                \App\Models\NotaBillingItem::create([
                    'nota_billing_id' => $nota->id,
                    'pranota_billing_id' => $pranota->id,
                    'item_name' => $pItem->item_name,
                    'dpp_amount' => $pItem->dpp_amount,
                    'management_fee_amount' => $pItem->management_fee_amount,
                    'ppn_amount' => $pItem->ppn_amount,
                    'total_amount' => $pItem->total_amount,
                ]);
            }
        }

        // Update Pranotas to Sudah Terbilling
        PranotaBilling::whereIn('id', $request->pranota_ids)->update(['status' => 'Sudah Terbilling']);

        return redirect()->back()->with('success', 'Nota Billing (Invoice) sukses dibuat dari pengelompokan pranota!');
    }

    public function postNota($id)
    {
        $nota = NotaBilling::findOrFail($id);

        // Mock Send to SAP (Posting Jurnal AR SAP)
        $sapDoc = 'SAP-AR-' . Carbon::now()->format('Ymd') . sprintf('%04d', $nota->id);
        
        $nota->update([
            'status' => 'Completed',
            'sap_doc_number' => $sapDoc,
            'posted_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Nota Billing sukses di-posting ke SAP! SAP Document AR: ' . $sapDoc);
    }
}
