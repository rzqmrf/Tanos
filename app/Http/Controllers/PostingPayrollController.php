<?php

namespace App\Http\Controllers;

use App\Models\PayrollPeriod;
use App\Models\PayrollResult;
use App\Models\PranotaBilling;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PostingPayrollController extends Controller
{
    public function index(Request $request)
    {
        // Get periods with status Completed or Posted (representing general ledger entries)
        $query = PayrollPeriod::with(['project', 'results']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $periods = $query->whereIn('status', ['Completed', 'Posted', 'Voided'])->latest()->paginate(25);

        return view('finance.posting_payrolls', [
            'periods' => $periods,
        ]);
    }

    public function showJournal($id)
    {
        $period = PayrollPeriod::with(['project', 'results.employee'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'period' => $period,
        ]);
    }

    public function uploadPFile(Request $request, $id)
    {
        $period = PayrollPeriod::findOrFail($id);
        
        $request->validate([
            'attachment' => 'required|file|max:2048', // 2MB max
        ]);

        // Mock upload document to SAP P-Files
        return redirect()->back()->with('success', 'Dokumen lampiran payroll P-Files sukses diunggah ke server SAP!');
    }

    public function voidJournal($id)
    {
        $period = PayrollPeriod::findOrFail($id);
        $period->update(['status' => 'Voided']);

        // Cancel associated Pranota if any
        PranotaBilling::where('payroll_period_id', $period->id)->delete();

        return redirect()->back()->with('success', 'Jurnal payroll sukses di-void/dibatalkan!');
    }
}
