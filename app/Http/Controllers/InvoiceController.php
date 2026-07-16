<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!session()->has('user')) {
            return redirect()->route('login');
        }

        // Filter options
        $months = ['Januari 2025', 'Februari 2025', 'Maret 2025', 'April 2025', 'Mei 2025', 'Juni 2025'];
        $regionals = ['Jawa Barat', 'Jawa Timur', 'Jawa Tengah', 'Sumatera', 'Kalimantan'];
        $segments = ['Enterprise', 'Corporate', 'Government', 'SME', 'Retail'];
        $types = ['P2P', 'Non P2P'];

        // Get filter inputs
        $searchMonth = $request->input('month');
        $searchRegional = $request->input('regional');
        $searchSegment = $request->input('segment');
        $searchType = $request->input('type');

        $query = Invoice::query();

        // Apply filters
        if ($searchMonth && $searchMonth !== 'All') {
            $query->where('month', $searchMonth);
        }
        if ($searchRegional && $searchRegional !== 'All') {
            $query->where('regional', $searchRegional);
        }
        if ($searchSegment && $searchSegment !== 'All') {
            $query->where('segment', $searchSegment);
        }
        if ($searchType && $searchType !== 'All') {
            $query->where('type', $searchType);
        }

        // Order by latest and paginate
        $invoices = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

        return view('dashboard.invoices', [
            'invoices' => $invoices,
            'months' => $months,
            'regionals' => $regionals,
            'segments' => $segments,
            'types' => $types,
            'currentMonth' => $searchMonth ?? 'All',
            'currentRegional' => $searchRegional ?? 'All',
            'currentSegment' => $searchSegment ?? 'All',
            'currentType' => $searchType ?? 'All',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!session()->has('user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'type' => 'required|string|in:P2P,Non P2P',
            'month' => 'required|string',
            'regional' => 'required|string',
            'segment' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        Invoice::create($validated);

        return redirect()->route('invoices.index')->with('success', 'Invoice baru berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!session()->has('user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $invoice = Invoice::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|string|in:P2P,Non P2P',
            'month' => 'required|string',
            'regional' => 'required|string',
            'segment' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $invoice->update($validated);

        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!session()->has('user')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil dihapus!');
    }
}
