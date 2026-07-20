<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.invoices', [
            'invoices' => Invoice::oldest()->paginate(25)]);
    }

    public function store(Request $request) {
        $validData = $request->validate([
            'type' => 'required|in:P2P,Non P2P',
            'month' => 'required|string|max:255',
            'regional' => 'required|string|max:255',
            'segment' => 'required|string|max:255',
            'amount' => 'required|numeric',
        ]);
        Invoice::create($validData);
        return redirect()->back()->with('success', 'Invoice sukses dibuat!');
    }

    public function update(Request $request, Invoice $invoice) {
        $validData = $request->validate([
            'type' => 'required|in:P2P,Non P2P',
            'month' => 'required|string|max:255',
            'regional' => 'required|string|max:255',
            'segment' => 'required|string|max:255',
            'amount' => 'required|numeric',        
        ]);

        $invoice->update($validData);
        return redirect()->back()->with('success', 'Invoice sukses diupdate!');
    }

    public function destroy(Invoice $invoices) {
        $invoices->delete();
        return redirect()->back()->with('success', 'Invoice sukses dihapus!');
    }




}
