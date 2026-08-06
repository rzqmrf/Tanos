<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\PranotaBilling;
use App\Models\NotaBilling;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $project_id = $request->input('project_id');

        // Filter queries
        $pranotaQuery = PranotaBilling::with(['project', 'period']);
        $notaQuery = NotaBilling::with('project');

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

        PranotaBilling::create($validData);

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

        $pranotas = PranotaBilling::whereIn('id', $request->pranota_ids)->get();
        $totalAmount = $pranotas->sum('amount');

        // Create Nota Billing (Page 11-12)
        $nota = NotaBilling::create([
            'project_id' => $request->project_id,
            'nota_number' => $request->nota_number,
            'amount' => $totalAmount,
            'status' => 'Draft',
        ]);

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
