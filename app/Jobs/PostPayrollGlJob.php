<?php

namespace App\Jobs;

use App\Models\PayrollPeriod;
use App\Models\PayrollResult;
use App\Models\PranotaBilling;
use App\Models\PranotaBillingItem;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PostPayrollGlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $periodId;

    public function __construct($periodId)
    {
        $this->periodId = $periodId;
    }

    public function handle(): void
    {
        $period = PayrollPeriod::find($this->periodId);
        if (!$period) return;

        // H-3 layer-2: cegah double-post — kalau pranota udah ada buat period ini, jangan insert lagi
        // (postSap juga ngeguard; ini tambahan biar aman kalo job di-call langsung / queue retry)
        if (PranotaBilling::where('payroll_period_id', $period->id)->exists()) {
            \App\Helpers\AuditLogger::log('PostPayrollGlJob Skipped (pranota exists)', $period, [], [
                'reason' => 'pranota_already_exists_for_period',
                'period_id' => $period->id,
            ]);
            return;
        }

        $sapDoc = 'SAP-PR-' . Carbon::now()->format('Ymd') . sprintf('%04d', $period->id);
        $period->update([
            'status' => 'Posted'
        ]);

        PayrollResult::where('payroll_period_id', $period->id)->update([
            'sap_doc_number' => $sapDoc,
            'posted_at' => Carbon::now()
        ]);

        $sumBasic = PayrollResult::where('payroll_period_id', $period->id)->sum('basic_salary');
        $sumTransport = PayrollResult::where('payroll_period_id', $period->id)->sum('transport_allowance');
        $sumAllowances = PayrollResult::where('payroll_period_id', $period->id)->sum('allowances');
        $sumOvertime = PayrollResult::where('payroll_period_id', $period->id)->sum('overtime_pay');

        $pranotaNo = 'PRAN-' . Carbon::now()->format('Ymd') . sprintf('%04d', $period->id);
        
        $pranota = PranotaBilling::create([
            'payroll_period_id' => $period->id,
            'project_id' => $period->project_id,
            'pranota_number' => $pranotaNo,
            'amount' => 0,
            'status' => 'Belum Terbilling',
        ]);

        $totalPranotaAmount = 0;
        $itemsToCreate = [
            'Upah Pokok Tenaga Kerja' => $sumBasic,
            'Uang Transport' => $sumTransport,
            'Tunjangan' => $sumAllowances,
            'Uang Lembur / Overtime' => $sumOvertime,
        ];

        foreach ($itemsToCreate as $name => $dpp) {
            if ($dpp > 0) {
                $feeRate = 10.00;
                $feeAmount = $dpp * ($feeRate / 100);
                $ppnRate = 11.00;
                $ppnAmount = ($dpp + $feeAmount) * ($ppnRate / 100);
                $totalItemAmount = $dpp + $feeAmount + $ppnAmount;

                PranotaBillingItem::create([
                    'pranota_billing_id' => $pranota->id,
                    'item_name' => $name,
                    'dpp_amount' => $dpp,
                    'management_fee_rate' => $feeRate,
                    'management_fee_amount' => $feeAmount,
                    'ppn_rate' => $ppnRate,
                    'ppn_amount' => $ppnAmount,
                    'total_amount' => $totalItemAmount,
                ]);

                $totalPranotaAmount += $totalItemAmount;
            }
        }

        $pranota->update(['amount' => $totalPranotaAmount]);

        \App\Helpers\AuditLogger::log('Post Payroll GL & SAP Job', $period, [], ['sap_doc' => $sapDoc, 'pranota' => $pranotaNo]);
    }
}
