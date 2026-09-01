<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Regional;
use App\Models\Segment;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $dashboardService = new DashboardService();

        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $query = Invoice::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('type', 'like', "%{$search}%")
                  ->orWhere('regional', 'like', "%{$search}%")
                  ->orWhere('segment', 'like', "%{$search}%")
                  ->orWhere('month', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('regional')) {
            $query->where('regional', $request->regional);
        }

        $invoices = $query->latest('id')->paginate($perPage)->withQueryString();
        $regionals = Regional::orderBy('name')->get();
        $segments = Segment::orderBy('name')->get();
        $months = $dashboardService->getMonths();

        $totalAll = Invoice::count();
        $totalP2P = Invoice::where('type', 'P2P')->sum('amount');
        $totalNonP2P = Invoice::where('type', 'Non P2P')->sum('amount');
        $totalNominal = Invoice::sum('amount');

        return view('finance.invoices.index', compact(
            'invoices',
            'regionals',
            'segments',
            'months',
            'totalAll',
            'totalP2P',
            'totalNonP2P',
            'totalNominal',
            'perPage'
        ));
    }

    public function create()
    {
        $dashboardService = new DashboardService();
        $regionals = Regional::orderBy('name')->get();
        $segments = Segment::orderBy('name')->get();
        $months = $dashboardService->getMonths();

        return view('finance.invoices.create', compact('regionals', 'segments', 'months'));
    }

    public function show(Invoice $invoice)
    {
        return view('finance.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $dashboardService = new DashboardService();
        $regionals = Regional::orderBy('name')->get();
        $segments = Segment::orderBy('name')->get();
        $months = $dashboardService->getMonths();

        return view('finance.invoices.edit', compact('invoice', 'regionals', 'segments', 'months'));
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'type' => 'required|in:P2P,Non P2P',
            'month' => 'required|string|max:255',
            'regional' => 'required|string|max:255',
            'segment' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $invoice = Invoice::create($validData);

        // Trigger notification for all users
        foreach (\App\Models\User::all() as $u) {
            \App\Models\Notification::create([
                'user_id' => $u->id,
                'title' => 'Invoice Baru Masuk',
                'message' => 'Invoice ' . $invoice->type . ' di ' . $invoice->regional . ' senilai Rp ' . number_format($invoice->amount, 0, ',', '.') . ' telah dibuat.',
                'type' => 'invoice',
            ]);
        }

        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Invoice baru berhasil dibuat!');
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validData = $request->validate([
            'type' => 'required|in:P2P,Non P2P',
            'month' => 'required|string|max:255',
            'regional' => 'required|string|max:255',
            'segment' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $invoice->update($validData);
        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Invoice berhasil diperbarui!');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil dihapus!');
    }
}
