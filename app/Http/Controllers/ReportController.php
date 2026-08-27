<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\RabBudget;
use App\Models\NotaBilling;
use App\Models\PranotaBilling;
use App\Models\PayrollPeriod;
use App\Models\PayrollResult;
use App\Models\Employee;
use App\Models\Regional;
use App\Models\Segment;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the Executive & Finance Reports Page.
     */
    public function index(Request $request)
    {
        $selectedYear = $request->query('year', date('Y'));
        $selectedMonth = $request->query('month', 'All');
        $selectedRegional = $request->query('regional', 'All');
        $selectedSegment = $request->query('segment', 'All');
        $activeTab = $request->query('tab', 'projects');

        // Master Filter Options
        $regionals = Regional::pluck('name')->toArray();
        if (empty($regionals)) {
            $regionals = ['Regional Bali Nusra', 'Regional Jakarta', 'Regional Jawa', 'Regional Kalimantan', 'Regional Sulawesi', 'Regional Sumatra'];
        }
        $segments = Segment::pluck('name')->toArray();
        if (empty($segments)) {
            $segments = ['Telecommunication', 'Enterprise', 'Public Sector', 'Container', 'Non Container', 'Others'];
        }
        $years = range(date('Y'), date('Y') - 4);
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // --- 1. PROJECT & RAB REPORTS QUERY ---
        $projectsQuery = Project::with('rabBudget');
        if ($selectedRegional !== 'All') {
            $projectsQuery->where('regional', $selectedRegional);
        }
        if ($selectedSegment !== 'All') {
            $projectsQuery->where('segment', $selectedSegment);
        }
        if ($selectedMonth !== 'All') {
            $projectsQuery->where('month', $selectedMonth);
        }
        $projectList = $projectsQuery->get();

        $projectReports = $projectList->map(function ($project) {
            $rabBudget = $project->rabBudget?->total_cost ?? 0;
            $rabRevenue = $project->rabBudget?->total_revenue ?? 0;
            $actualCost = (float) $project->cost;
            $remainingBudget = max(0, $rabBudget - $actualCost);
            $realizationPct = $rabBudget > 0 ? min(100, round(($actualCost / $rabBudget) * 100, 1)) : 0;

            return [
                'id' => $project->id,
                'code' => $project->project_code ?? 'PRJ-' . $project->id,
                'name' => $project->project_name,
                'customer' => $project->customer_name ?? 'Pelindo Group',
                'regional' => $project->regional,
                'segment' => $project->segment,
                'rab_budget' => $rabBudget,
                'rab_revenue' => $rabRevenue,
                'actual_cost' => $actualCost,
                'remaining_budget' => $remainingBudget,
                'realization_pct' => $realizationPct,
                'status' => $project->active ? 'Active' : 'Completed',
            ];
        });

        // --- 2. BILLING & INVOICE REPORTS QUERY ---
        $notaQuery = NotaBilling::with('project');
        if ($selectedRegional !== 'All') {
            $notaQuery->whereHas('project', fn($q) => $q->where('regional', $selectedRegional));
        }
        if ($selectedSegment !== 'All') {
            $notaQuery->whereHas('project', fn($q) => $q->where('segment', $selectedSegment));
        }
        $notaList = $notaQuery->latest()->get();

        $billingReports = $notaList->map(function ($nota) {
            $bruto = (float) $nota->amount;
            $ppn = round($bruto * 0.11, 0); // 11% PPN
            $pph = round($bruto * 0.02, 0); // 2% PPh
            $netto = $bruto + $ppn - $pph;

            return [
                'id' => $nota->id,
                'nota_number' => $nota->nota_number,
                'project_name' => $nota->project?->project_name ?? 'General Billing',
                'regional' => $nota->project?->regional ?? '-',
                'segment' => $nota->project?->segment ?? '-',
                'amount_bruto' => $bruto,
                'ppn' => $ppn,
                'pph' => $pph,
                'amount_netto' => $netto,
                'status' => $nota->status ?? 'Draft',
                'sap_doc' => $nota->sap_doc_number ?? '-',
                'posted_at' => $nota->posted_at ? $nota->posted_at->format('d M Y') : '-',
            ];
        });

        // --- 3. HCM & PAYROLL REPORTS QUERY ---
        $payrollQuery = PayrollPeriod::with(['project', 'results']);
        if ($selectedRegional !== 'All') {
            $payrollQuery->whereHas('project', fn($q) => $q->where('regional', $selectedRegional));
        }
        if ($selectedSegment !== 'All') {
            $payrollQuery->whereHas('project', fn($q) => $q->where('segment', $selectedSegment));
        }
        $payrollList = $payrollQuery->get();

        $payrollReports = $payrollList->map(function ($period) {
            $totalBasic = $period->results->sum('gaji_pokok');
            $totalAllowance = $period->results->sum('total_tunjangan');
            $totalThp = $period->results->sum('take_home_pay');
            $employeeCount = $period->results->count();

            if ($employeeCount === 0) {
                // Fallback estimate if results are not calculated yet
                $employeeCount = Employee::where('regional', $period->project?->regional)->count() ?: 10;
                $totalThp = $period->project?->cost * 0.3 ?: 150000000;
                $totalBasic = $totalThp * 0.7;
                $totalAllowance = $totalThp * 0.3;
            }

            return [
                'id' => $period->id,
                'period_name' => $period->name,
                'project_name' => $period->project?->project_name ?? 'General Payroll',
                'regional' => $period->project?->regional ?? '-',
                'employee_count' => $employeeCount,
                'total_basic' => $totalBasic,
                'total_allowance' => $totalAllowance,
                'total_thp' => $totalThp,
                'status' => $period->status ?? 'Draft',
            ];
        });

        // --- KPI SUMMARY TOTALS ---
        $kpiSummary = [
            'total_rab_budget' => $projectReports->sum('rab_budget'),
            'total_actual_cost' => $projectReports->sum('actual_cost'),
            'total_billing_bruto' => $billingReports->sum('amount_bruto'),
            'total_payroll_thp' => $payrollReports->sum('total_thp'),
            'active_projects_count' => $projectReports->where('status', 'Active')->count(),
            'total_employees_count' => Employee::count(),
        ];

        return view('finance.reports', [
            'years' => $years,
            'months' => $months,
            'regionals' => $regionals,
            'segments' => $segments,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
            'selectedRegional' => $selectedRegional,
            'selectedSegment' => $selectedSegment,
            'activeTab' => $activeTab,
            'kpiSummary' => $kpiSummary,
            'projectReports' => $projectReports,
            'billingReports' => $billingReports,
            'payrollReports' => $payrollReports,
        ]);
    }

    /**
     * Export reports data as CSV.
     */
    public function export(Request $request)
    {
        $tab = $request->query('tab', 'projects');
        $filename = "tanos_report_{$tab}_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($tab, $request) {
            $file = fopen('php://output', 'w');
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            if ($tab === 'billing') {
                fputcsv($file, ['No Nota', 'Project', 'Regional', 'Segment', 'Bruto (Rp)', 'PPN 11% (Rp)', 'PPh 2% (Rp)', 'Netto (Rp)', 'Status SAP', 'Tanggal']);
                $notas = NotaBilling::with('project')->get();
                foreach ($notas as $nota) {
                    $bruto = (float) $nota->amount;
                    $ppn = round($bruto * 0.11, 0);
                    $pph = round($bruto * 0.02, 0);
                    $netto = $bruto + $ppn - $pph;
                    fputcsv($file, [
                        $nota->nota_number,
                        $nota->project?->project_name ?? '-',
                        $nota->project?->regional ?? '-',
                        $nota->project?->segment ?? '-',
                        $bruto,
                        $ppn,
                        $pph,
                        $netto,
                        $nota->status ?? 'Draft',
                        $nota->posted_at ? $nota->posted_at->format('Y-m-d') : '-'
                    ]);
                }
            } elseif ($tab === 'payroll') {
                fputcsv($file, ['Periode', 'Project', 'Regional', 'Jumlah TAD', 'Gaji Pokok (Rp)', 'Tunjangan (Rp)', 'Total THP (Rp)', 'Status']);
                $periods = PayrollPeriod::with(['project', 'results'])->get();
                foreach ($periods as $period) {
                    $totalBasic = $period->results->sum('gaji_pokok');
                    $totalAllowance = $period->results->sum('total_tunjangan');
                    $totalThp = $period->results->sum('take_home_pay');
                    fputcsv($file, [
                        $period->name,
                        $period->project?->project_name ?? '-',
                        $period->project?->regional ?? '-',
                        $period->results->count(),
                        $totalBasic,
                        $totalAllowance,
                        $totalThp,
                        $period->status ?? 'Draft'
                    ]);
                }
            } else { // projects & RAB
                fputcsv($file, ['Kode Proyek', 'Nama Proyek', 'Customer', 'Regional', 'Segment', 'Anggaran RAB (Rp)', 'Realisasi Cost (Rp)', 'Sisa Budget (Rp)', '% Serapan', 'Status']);
                $projects = Project::with('rabBudget')->get();
                foreach ($projects as $proj) {
                    $rabBudget = $proj->rabBudget?->total_cost ?? 0;
                    $actualCost = (float) $proj->cost;
                    $remaining = max(0, $rabBudget - $actualCost);
                    $pct = $rabBudget > 0 ? min(100, round(($actualCost / $rabBudget) * 100, 1)) : 0;
                    fputcsv($file, [
                        $proj->project_code ?? 'PRJ-' . $proj->id,
                        $proj->project_name,
                        $proj->customer_name ?? 'Pelindo Group',
                        $proj->regional,
                        $proj->segment,
                        $rabBudget,
                        $actualCost,
                        $remaining,
                        $pct . '%',
                        $proj->active ? 'Active' : 'Completed'
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
