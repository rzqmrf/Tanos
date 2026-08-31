<?php

namespace App\Services;

use App\Models\NotaBilling;
use App\Models\NotaBillingItem;
use App\Models\PranotaBilling;
use Illuminate\Http\Request;

class BillingService
{
    /**
     * Kumpulkan pranota se-project, buat Nota Billing + copy items + update status.
     * Return RedirectResponse kalau pranota beda project (validasi gagal).
     */
    public function createNotaFromPranotas(Request $request)
    {
        $request->validate([
            'pranota_ids' => 'required|array',
            'pranota_ids.*' => 'exists:pranota_billings,id',
            'project_id' => 'required|exists:projects,id',
            'nota_number' => 'required|string|unique:nota_billings,nota_number',
        ]);

        // Pastikan semua pranota berasal dari project yang sama & berstatus Siap Terbilling
        // (cegah double-billing — pranota yang sudah masuk nota lain gak boleh dipakai lagi)
        $pranotas = PranotaBilling::whereIn('id', $request->pranota_ids)
            ->where('project_id', $request->project_id)
            ->where('status', 'Siap Terbilling')
            ->with('items')
            ->get();

        if ($pranotas->count() !== count($request->pranota_ids)) {
            return redirect()->back()->withErrors([
                'error' => 'Terdapat pranota yang bukan berstatus Siap Terbilling (mungkin sudah dipakai di nota lain). Nota hanya boleh berisi pranota Siap Terbilling.'
            ]);
        }

        $totalAmount = $pranotas->sum('amount');

        // Semua operasi nota di transaction biar atomic — gagal tengah gak nyisain state partial
        return \DB::transaction(function () use ($request, $pranotas, $totalAmount) {
            // Create Nota Billing
            $nota = NotaBilling::create([
                'project_id' => $request->project_id,
                'nota_number' => $request->nota_number,
                'amount' => $totalAmount,
                'status' => 'Draft',
            ]);

            // Copy Pranota items to Nota Billing items
            foreach ($pranotas as $pranota) {
                foreach ($pranota->items as $pItem) {
                    NotaBillingItem::create([
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

            return $nota;
        });
    }
}
