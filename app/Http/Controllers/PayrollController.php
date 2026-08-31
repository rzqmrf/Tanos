<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Employee;
use App\Models\PayrollPeriod;
use App\Models\PayrollResult;
use App\Services\PayrollService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $routeAction = $request->route()->getActionMethod();
            $modifyingActions = ['store', 'calculate', 'copyFormula', 'postSap'];
            if (in_array($routeAction, $modifyingActions)) {
                if (!\App\Models\RolePermission::hasPermission(auth()->user()?->role, 'payroll')) {
                    abort(403, 'Akses ditolak. Role Anda tidak memiliki izin payroll.');
                }
            }
            return $next($request);
        });
    }

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

        $period = app(PayrollService::class)->createPeriodWithDefaults($validData);

        return redirect()->route('payrolls.show', $period->id)->with('success', 'Periode payroll sukses dibuat dengan komponen bawaan!');
    }

    public function show($id)
    {
        $period = PayrollPeriod::with(['project', 'components.wbsElement', 'results.employee'])->findOrFail($id);
        
        $project = $period->project;
        $employees = Employee::where('month', $period->month)
            ->where('regional', $project->regional)
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
        $action = $request->input('action', 'Simulation'); // Simulation or Payroll

        \App\Jobs\ProcessPayrollCalculationJob::dispatchSync($period->id, $action);

        // Update Period Status message
        if ($action === 'Simulation') {
            $msg = 'Simulasi perhitungan payroll selesai dilakukan!';
        } else {
            $msg = 'Perhitungan payroll resmi digenerate & dikunci!';
        }

        \App\Helpers\AuditLogger::log('Calculate Payroll', $period, [], ['action' => $action]);

        return redirect()->back()->with('success', $msg);
    }

    public function copyFormula(Request $request, $id)
    {
        $request->validate([
            'source_period_id' => 'required|exists:payroll_periods,id',
        ]);

        app(PayrollService::class)->copyFormula($id, $request->source_period_id);

        return redirect()->back()->with('success', 'Formulasi payroll sukses disalin dari periode asal!');
    }

    public function postSap($id)
    {
        $period = PayrollPeriod::findOrFail($id);

        // Cegah double-post: cek pranota existing (periode yang sudah pernah bikin pranota gak boleh diposting ulang)
        if (\App\Models\PranotaBilling::where('payroll_period_id', $period->id)->exists()) {
            return redirect()->back()->with('error', 'Periode ini sudah pernah di-posting (Pranota sudah ada untuk periode ini). Tidak dapat di-posting ulang.');
        }

        // Cegah posting ulang periode yang sudah di-Post ke SAP (hindari pranota ganda)
        if (in_array($period->status, ['Posted', 'Voided'])) {
            return redirect()->back()->with('error', 'Periode sudah di-posting ke SAP (status: ' . $period->status . '), tidak dapat di-posting ulang.');
        }

        \App\Jobs\PostPayrollGlJob::dispatchSync($id);

        $sapDoc = PayrollResult::where('payroll_period_id', $period->id)->value('sap_doc_number') ?? 'SAP-PR-' . Carbon::now()->format('Ymd');

        return redirect()->back()->with('success', 'Jurnal Payroll sukses diposting ke SAP (Doc: ' . $sapDoc . ') & Dokumen Pranota Billing telah sukses ter-generate!');
    }
}
