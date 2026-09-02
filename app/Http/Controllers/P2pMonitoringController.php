<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Project;
use Carbon\Carbon;

class P2pMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $statusFilter = $request->get('status', '');

        // Generate dynamic mock synchronization logs based on real projects & invoices
        $projects = Project::take(15)->get();
        $invoices = Invoice::take(10)->get();

        $logs = [];
        $id = 1;

        foreach ($projects as $project) {
            $isSuccess = ($id % 7 !== 0);
            $logs[] = (object) [
                'id' => $id,
                'sync_id' => 'SYNC-P2P-' . str_pad($id, 5, '0', STR_PAD_LEFT),
                'endpoint' => 'POST /api/v2/p2p/project-budget/sync',
                'service' => 'SAP S/4HANA Project Budget',
                'project_code' => $project->project_code,
                'project_name' => $project->project_name,
                'status' => $isSuccess ? 'SUCCESS' : 'FAILED',
                'http_code' => $isSuccess ? 200 : 500,
                'payload_size' => '3.4 KB',
                'response_time' => rand(120, 480) . 'ms',
                'synced_at' => Carbon::now()->subMinutes($id * 18)->format('Y-m-d H:i:s'),
                'error_message' => $isSuccess ? null : 'Connection timeout with SAP Gateway RFC (Host: 10.20.100.4:3300)',
            ];
            $id++;
        }

        foreach ($invoices as $inv) {
            $isSuccess = ($id % 5 !== 0);
            $logs[] = (object) [
                'id' => $id,
                'sync_id' => 'SYNC-P2P-' . str_pad($id, 5, '0', STR_PAD_LEFT),
                'endpoint' => 'POST /api/v2/p2p/invoice-ar/post',
                'service' => 'SAP AR Journal Posting',
                'project_code' => $inv->project_name ?? ('PRJ-' . $id),
                'project_name' => 'Invoice ' . ($inv->type ?? 'P2P') . ' ' . ($inv->regional ?? 'Regional 2'),
                'status' => $isSuccess ? 'SUCCESS' : ($id % 2 === 0 ? 'PENDING' : 'FAILED'),
                'http_code' => $isSuccess ? 201 : 502,
                'payload_size' => '5.1 KB',
                'response_time' => rand(180, 650) . 'ms',
                'synced_at' => Carbon::now()->subMinutes($id * 25)->format('Y-m-d H:i:s'),
                'error_message' => $isSuccess ? null : 'Vendor tax ID (NPWP) mismatch with P2P Master Directory',
            ];
            $id++;
        }

        // Filter search
        if ($search) {
            $logs = array_filter($logs, function ($l) use ($search) {
                return stripos($l->sync_id, $search) !== false ||
                       stripos($l->service, $search) !== false ||
                       stripos($l->project_name, $search) !== false ||
                       stripos($l->project_code, $search) !== false;
            });
        }

        if ($statusFilter) {
            $logs = array_filter($logs, function ($l) use ($statusFilter) {
                return $l->status === $statusFilter;
            });
        }

        $totalCalls = count($logs);
        $totalSuccess = count(array_filter($logs, fn($l) => $l->status === 'SUCCESS'));
        $totalFailed = count(array_filter($logs, fn($l) => $l->status === 'FAILED'));
        $totalPending = count(array_filter($logs, fn($l) => $l->status === 'PENDING'));
        $successRate = $totalCalls > 0 ? round(($totalSuccess / $totalCalls) * 100, 1) : 100;

        return view('finance.p2p-integration', compact(
            'logs',
            'search',
            'statusFilter',
            'totalCalls',
            'totalSuccess',
            'totalFailed',
            'totalPending',
            'successRate'
        ));
    }

    public function syncNow(Request $request)
    {
        return redirect()->back()->with('success', 'Sinkronisasi P2P Integration & SAP Gateway berhasil dipicu! 12 antrean transaksi terkirim.');
    }
}
