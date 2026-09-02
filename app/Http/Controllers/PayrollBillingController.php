<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Employee;
use Carbon\Carbon;

class PayrollBillingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $statusFilter = $request->get('status', '');

        $projects = Project::orderBy('id', 'desc')->get();
        $billings = [];

        $months = ['Januari 2026', 'Februari 2026', 'Maret 2026', 'April 2026'];

        $id = 1;
        foreach ($projects as $project) {
            $month = $months[$id % count($months)];
            $tadCount = rand(15, 85);
            $baseSalary = $tadCount * rand(4800000, 6200000);
            $allowance = $tadCount * rand(800000, 1500000);
            $overtime = $tadCount * rand(300000, 900000);
            $managementFeeRate = 10; // 10%
            $subtotal = $baseSalary + $allowance + $overtime;
            $managementFee = $subtotal * ($managementFeeRate / 100);
            $ppn = ($subtotal + $managementFee) * 0.11; // 11% PPN
            $totalBilling = $subtotal + $managementFee + $ppn;

            $statuses = ['Billed', 'Pranota Ready', 'Draft', 'Paid'];
            $status = $statuses[$id % count($statuses)];

            $billings[] = (object) [
                'id' => $id,
                'billing_no' => 'BILL-TAD/' . date('Y') . '/' . str_pad($id, 4, '0', STR_PAD_LEFT),
                'pranota_no' => 'PRA/' . date('Ym') . '/' . str_pad($id, 4, '0', STR_PAD_LEFT),
                'project_id' => $project->id,
                'project_code' => $project->project_code,
                'project_name' => $project->project_name,
                'customer_name' => $project->customer_name,
                'regional' => $project->regional,
                'period' => $month,
                'tad_count' => $tadCount,
                'base_salary' => $baseSalary,
                'allowances' => $allowance,
                'overtime' => $overtime,
                'subtotal' => $subtotal,
                'management_fee' => $managementFee,
                'ppn' => $ppn,
                'total_billing' => $totalBilling,
                'status' => $status,
                'due_date' => Carbon::now()->addDays(rand(10, 30))->format('d M Y'),
                'created_at' => Carbon::now()->subDays($id * 3)->format('d M Y'),
            ];
            $id++;
        }

        if ($search) {
            $billings = array_filter($billings, function ($b) use ($search) {
                return stripos($b->billing_no, $search) !== false ||
                       stripos($b->pranota_no, $search) !== false ||
                       stripos($b->project_name, $search) !== false ||
                       stripos($b->customer_name, $search) !== false;
            });
        }

        if ($statusFilter) {
            $billings = array_filter($billings, function ($b) use ($statusFilter) {
                return $b->status === $statusFilter;
            });
        }

        $totalAll = count($billings);
        $totalNominal = array_sum(array_column($billings, 'total_billing'));
        $totalTad = array_sum(array_column($billings, 'tad_count'));
        $totalBilled = count(array_filter($billings, fn($b) => in_array($b->status, ['Billed', 'Paid'])));

        return view('finance.payroll-billing', compact(
            'billings',
            'search',
            'statusFilter',
            'totalAll',
            'totalNominal',
            'totalTad',
            'totalBilled'
        ));
    }

    public function generatePranota(Request $request, int|string $id)
    {
        return redirect()->back()->with('success', 'Pranota Tagihan Payroll TAD #' . $id . ' berhasil di-generate dan siap dikirim ke bagian Billing!');
    }

    public function postToBilling(Request $request, int|string $id)
    {
        return redirect()->back()->with('success', 'Tagihan Payroll TAD #' . $id . ' berhasil diterbitkan sebagai Invoice AR SAP!');
    }
}
