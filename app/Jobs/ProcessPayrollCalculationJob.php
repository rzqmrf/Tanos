<?php

namespace App\Jobs;

use App\Models\PayrollPeriod;
use App\Models\PayrollComponent;
use App\Models\PayrollResult;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\TimeResult;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPayrollCalculationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $periodId;
    protected $action;

    public function __construct($periodId, $action = 'Simulation')
    {
        $this->periodId = $periodId;
        $this->action = $action;
    }

    public function handle(): void
    {
        $period = PayrollPeriod::find($this->periodId);
        if (!$period) return;

        $project = $period->project;
        $employees = Employee::where('month', $project->month)
            ->where('regional', $project->regional)
            ->where('segment', $project->segment)
            ->get();

        $components = PayrollComponent::where('payroll_period_id', $period->id)->get();

        PayrollResult::where('payroll_period_id', $period->id)->delete();

        foreach ($employees as $employee) {
            $timeResult = TimeResult::where('employee_id', $employee->id)
                ->whereHas('timePeriod', function($q) use ($period) {
                    $q->where('start_date', $period->start_date)
                      ->where('end_date', $period->end_date);
                })->first();

            if ($timeResult) {
                $daysPresent = $timeResult->present_days;
                $overtimeHours = (float) $timeResult->overtime_hours;
                $extraDeductions = (float) $timeResult->deduction_amount;
            } else {
                $daysPresent = Attendance::where('employee_id', $employee->id)
                    ->whereBetween('date', [$period->start_date, $period->end_date])
                    ->where('status', 'Hadir')
                    ->count();

                $overtimeHours = Attendance::where('employee_id', $employee->id)
                    ->whereBetween('date', [$period->start_date, $period->end_date])
                    ->sum('overtime_hours');

                $extraDeductions = 0.00;
            }

            $basicSalary = 0;
            $transportAllowance = 0;
            $allowances = 0;
            $overtimePay = 0;
            $deductions = 0;

            foreach ($components as $comp) {
                $val = floatval($comp->amount);
                if ($comp->type === 'Formula') {
                    if (str_contains($comp->formula_expression, '{days_present}')) {
                        $compVal = $daysPresent * $val;
                    } elseif (str_contains($comp->formula_expression, '{overtime_hours}')) {
                        $compVal = $overtimeHours * $val;
                    } else {
                        $compVal = $val;
                    }
                } else {
                    $compVal = $val;
                }

                if ($comp->code === 'W001' || str_contains(strtolower($comp->name), 'pokok')) {
                    $basicSalary += $compVal;
                } elseif ($comp->code === 'W002' || str_contains(strtolower($comp->name), 'transport')) {
                    $transportAllowance += $compVal;
                } elseif ($comp->code === 'W003' || str_contains(strtolower($comp->name), 'tunjangan')) {
                    $allowances += $compVal;
                } elseif ($comp->code === 'W004' || str_contains(strtolower($comp->name), 'lembur')) {
                    $overtimePay += $compVal;
                } elseif ($compVal < 0) {
                    $deductions += abs($compVal);
                } else {
                    $basicSalary += $compVal;
                }
            }

            $deductionsTotal = $deductions + $extraDeductions;
            $netSalary = $basicSalary + $transportAllowance + $allowances + $overtimePay - $deductionsTotal;

            PayrollResult::create([
                'payroll_period_id' => $period->id,
                'employee_id' => $employee->id,
                'days_present' => $daysPresent,
                'overtime_hours' => $overtimeHours,
                'basic_salary' => $basicSalary,
                'transport_allowance' => $transportAllowance,
                'allowances' => $allowances,
                'overtime_pay' => $overtimePay,
                'deductions' => $deductionsTotal,
                'net_salary' => $netSalary,
            ]);
        }

        if ($this->action === 'Simulation') {
            $period->update(['status' => 'Simulated']);
        } else {
            $period->update(['status' => 'Completed']);
        }

        \App\Helpers\AuditLogger::log('Process Payroll Calculation Job', $period, [], ['status' => $period->status]);
    }
}
