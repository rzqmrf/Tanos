<?php

namespace App\Services;

use App\Models\PayrollPeriod;
use App\Models\PayrollComponent;
use App\Models\WbsElement;
use App\Models\Project;

class PayrollService
{
    /**
     * Buat periode payroll + default komponen (W001-W006) dengan mapping WBS.
     */
    public function createPeriodWithDefaults(array $validData): PayrollPeriod
    {
        $period = PayrollPeriod::create($validData);

        $project = Project::find($period->project_id);
        $wbsList = WbsElement::where('project_id', $project->id)->get();

        $findWbsId = function ($category) use ($wbsList) {
            $wbs = $wbsList->firstWhere('wbs_category', $category);
            return $wbs ? $wbs->id : null;
        };

        $defaultComponents = [
            ['code' => 'W001', 'name' => 'Upah Pokok', 'type' => 'Valuation', 'amount' => 4500000, 'wbs_cat' => 'Upah Pokok'],
            ['code' => 'W002', 'name' => 'Uang Transport', 'type' => 'Formula', 'amount' => 25000, 'wbs_cat' => 'Uang Transport', 'formula' => '{days_present} * {amount}'],
            ['code' => 'W003', 'name' => 'Tunjangan Kinerja', 'type' => 'Valuation', 'amount' => 500000, 'wbs_cat' => 'Tunjangan Kinerja'],
            ['code' => 'W004', 'name' => 'Uang Lembur', 'type' => 'Formula', 'amount' => 30000, 'wbs_cat' => 'Lembur', 'formula' => '{overtime_hours} * {amount}'],
            ['code' => 'W005', 'name' => 'BPJS Kesehatan', 'type' => 'Valuation', 'amount' => -100000, 'wbs_cat' => 'BPJS Kesehatan'],
            ['code' => 'W006', 'name' => 'BPJS Ketenagakerjaan', 'type' => 'Valuation', 'amount' => -150000, 'wbs_cat' => 'BPJS Ketenagakerjaan'],
        ];

        foreach ($defaultComponents as $comp) {
            PayrollComponent::create([
                'payroll_period_id' => $period->id,
                'wbs_element_id' => $findWbsId($comp['wbs_cat']),
                'code' => $comp['code'],
                'name' => $comp['name'],
                'type' => $comp['type'],
                'amount' => $comp['amount'],
                'formula_expression' => $comp['formula'] ?? null,
            ]);
        }

        return $period;
    }

    /**
     * Salin formulasi komponen dari periode asal ke periode target (dengan remap WBS).
     */
    public function copyFormula(int $targetPeriodId, int $sourcePeriodId): void
    {
        $targetPeriod = PayrollPeriod::findOrFail($targetPeriodId);
        $sourceComponents = PayrollComponent::where('payroll_period_id', $sourcePeriodId)->get();

        PayrollComponent::where('payroll_period_id', $targetPeriodId)->delete();

        $project = Project::find($targetPeriod->project_id);
        $targetWbsList = WbsElement::where('project_id', $project->id)->get();

        foreach ($sourceComponents as $sourceComp) {
            $sourceWbs = WbsElement::find($sourceComp->wbs_element_id);
            $mappedWbsId = null;
            if ($sourceWbs) {
                $targetWbs = $targetWbsList->firstWhere('wbs_category', $sourceWbs->wbs_category);
                $mappedWbsId = $targetWbs ? $targetWbs->id : null;
            }

            PayrollComponent::create([
                'payroll_period_id' => $targetPeriodId,
                'wbs_element_id' => $mappedWbsId,
                'code' => $sourceComp->code,
                'name' => $sourceComp->name,
                'type' => $sourceComp->type,
                'amount' => $sourceComp->amount,
                'formula_expression' => $sourceComp->formula_expression,
            ]);
        }
    }
}
