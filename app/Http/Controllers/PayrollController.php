<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\PayrollPeriod;
use App\Models\PayrollComponent;
use App\Models\PayrollResult;
use App\Models\PranotaBilling;
use App\Models\WbsElement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = PayrollPeriod::with('project');

        // Filters
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('year')) {
            $query->whereYear('start_date', $request->year);
        }

        $periods = $query->latest()->paginate(25);
        $projects = Project::orderBy('segment')->get();

        return view('finance.payrolls.index', [
            'periods' => $periods,
            'projects' => $projects,
            'years' => [2024, 2025, 2026, 2027],
        ]);
    }

    public function store(Request $request)
    {
        $validData = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:On-Cycle,Off-Cycle',
            'month' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $period = PayrollPeriod::create($validData);

        // Generate default WBS-mapped components
        $project = Project::find($period->project_id);
        $wbsList = WbsElement::where('project_id', $project->id)->get();

        // Helper to find WBS by category
        $findWbsId = function ($category) use ($wbsList) {
            $wbs = $wbsList->firstWhere('wbs_category', $category);
            return $wbs ? $wbs->id : null;
        };

        // Create default payroll components
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

        return redirect()->route('payrolls.show', $period->id)->with('success', 'Periode payroll sukses dibuat dengan komponen bawaan!');
    }

    public function show($id)
    {
        $period = PayrollPeriod::with(['project', 'components.wbsElement', 'results.employee'])->findOrFail($id);
        
        $project = $period->project;
        $employees = Employee::where('month', $project->month)
            ->where('regional', $project->regional)
            ->where('segment', $project->segment)
            ->get();

        $allPeriods = PayrollPeriod::where('id', '!=', $id)->orderBy('name')->get();

        return view('finance.payrolls.show', [
            'period' => $period,
            'employees' => $employees,
            'allPeriods' => $allPeriods,
        ]);
    }

    public function calculate(Request $request, $id)
    {
        $period = PayrollPeriod::findOrFail($id);
        $project = $period->project;
        $action = $request->input('action', 'Simulation'); // Simulation or Payroll

        // Fetch project employees
        $employees = Employee::where('month', $project->month)
            ->where('regional', $project->regional)
            ->where('segment', $project->segment)
            ->get();

        // Get configured payroll components
        $components = PayrollComponent::where('payroll_period_id', $period->id)->get();

        // Clear existing results
        PayrollResult::where('payroll_period_id', $period->id)->delete();

        foreach ($employees as $employee) {
            // Count attendances
            $daysPresent = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$period->start_date, $period->end_date])
                ->where('status', 'Hadir')
                ->count();

            // Sum overtime
            $overtimeHours = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$period->start_date, $period->end_date])
                ->sum('overtime_hours');

            // Calculate components
            $basicSalary = 0;
            $transportAllowance = 0;
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

                // Group by type/name
                if ($comp->code === 'W001' || str_contains(strtolower($comp->name), 'pokok')) {
                    $basicSalary += $compVal;
                } elseif ($comp->code === 'W002' || str_contains(strtolower($comp->name), 'transport')) {
                    $transportAllowance += $compVal;
                } elseif ($comp->code === 'W004' || str_contains(strtolower($comp->name), 'lembur')) {
                    $overtimePay += $compVal;
                } elseif ($compVal < 0) {
                    $deductions += abs($compVal);
                } else {
                    $basicSalary += $compVal; // general allowance
                }
            }

            $netSalary = $basicSalary + $transportAllowance + $overtimePay - $deductions;

            PayrollResult::create([
                'payroll_period_id' => $period->id,
                'employee_id' => $employee->id,
                'days_present' => $daysPresent,
                'overtime_hours' => $overtimeHours,
                'basic_salary' => $basicSalary,
                'transport_allowance' => $transportAllowance,
                'overtime_pay' => $overtimePay,
                'deductions' => $deductions,
                'net_salary' => $netSalary,
            ]);
        }

        // Update Period Status
        if ($action === 'Simulation') {
            $period->update(['status' => 'Simulated']);
            $msg = 'Simulasi perhitungan payroll selesai dilakukan!';
        } else {
            $period->update(['status' => 'Completed']);
            $msg = 'Perhitungan payroll resmi digenerate & dikunci!';
        }

        return redirect()->back()->with('success', $msg);
    }

    public function copyFormula(Request $request, $id)
    {
        $request->validate([
            'source_period_id' => 'required|exists:payroll_periods,id',
        ]);

        $targetPeriod = PayrollPeriod::findOrFail($id);
        $sourceComponents = PayrollComponent::where('payroll_period_id', $request->source_period_id)->get();

        // Clear existing
        PayrollComponent::where('payroll_period_id', $id)->delete();

        // Project WBS mapping lookup
        $project = Project::find($targetPeriod->project_id);
        $targetWbsList = WbsElement::where('project_id', $project->id)->get();

        foreach ($sourceComponents as $sourceComp) {
            // Match WBS by category name if possible
            $sourceWbs = WbsElement::find($sourceComp->wbs_element_id);
            $mappedWbsId = null;
            if ($sourceWbs) {
                $targetWbs = $targetWbsList->firstWhere('wbs_category', $sourceWbs->wbs_category);
                $mappedWbsId = $targetWbs ? $targetWbs->id : null;
            }

            PayrollComponent::create([
                'payroll_period_id' => $id,
                'wbs_element_id' => $mappedWbsId,
                'code' => $sourceComp->code,
                'name' => $sourceComp->name,
                'type' => $sourceComp->type,
                'amount' => $sourceComp->amount,
                'formula_expression' => $sourceComp->formula_expression,
            ]);
        }

        return redirect()->back()->with('success', 'Formulasi payroll sukses disalin dari periode asal!');
    }

    public function postSap($id)
    {
        $period = PayrollPeriod::findOrFail($id);
        
        // Mock Send to SAP (Jurnal Payroll for Payment)
        $sapDoc = 'SAP-PR-' . Carbon::now()->format('Ymd') . sprintf('%04d', $period->id);
        $period->update([
            'status' => 'Posted'
        ]);

        // Save doc number to all results
        PayrollResult::where('payroll_period_id', $period->id)->update([
            'sap_doc_number' => $sapDoc,
            'posted_at' => Carbon::now()
        ]);

        // Core business integration: Auto-Generate PRANOTA Billing (Halaman 9)
        $totalAmount = PayrollResult::where('payroll_period_id', $period->id)->sum('net_salary');
        $pranotaNo = 'PRAN-' . Carbon::now()->format('Ymd') . sprintf('%04d', $period->id);
        
        PranotaBilling::create([
            'payroll_period_id' => $period->id,
            'project_id' => $period->project_id,
            'pranota_number' => $pranotaNo,
            'amount' => $totalAmount * 1.15, // Cost + 15% Management Fee
            'status' => 'Belum Terbilling',
        ]);

        return redirect()->back()->with('success', 'Jurnal Payroll sukses diposting ke SAP (Doc: ' . $sapDoc . ') & Dokumen Pranota Billing telah sukses ter-generate!');
    }
}
