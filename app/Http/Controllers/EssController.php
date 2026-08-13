<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\CicoCorrection;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class EssController extends Controller
{
    private function getSessionUser()
    {
        return \Illuminate\Support\Facades\Auth::user();
    }

    /**
     * Display ESS Dashboard for the employee.
     */
    public function index(Request $request)
    {
        $user = $this->getSessionUser();
        if (!$user) {
            return redirect()->route('login');
        }

        // Karyawan hanya bisa melihat data mereka sendiri
        $employee = $user->employee;
        if (!$employee) {
            return redirect()->route('dashboard.index')->withErrors(['error' => 'User Anda belum terhubung dengan data Pegawai Master TANOS.']);
        }

        $leaveRequests = LeaveRequest::where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $cicoCorrections = CicoCorrection::where('employee_id', $employee->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ess.index', compact('leaveRequests', 'cicoCorrections', 'employee'));
    }

    /**
     * Store Leave Request from Employee.
     */
    public function storeLeave(Request $request)
    {
        $user = $this->getSessionUser();
        if (!$user) {
            return redirect()->route('login');
        }

        $employee = $user->employee;
        if (!$employee) {
            return redirect()->back()->withErrors(['error' => 'Pegawai tidak ditemukan.']);
        }

        $request->validate([
            'type' => 'required|string|in:Cuti Tahunan,Sakit,Cuti Melahirkan,Izin Alasan Penting,Cuti Ibadah Keagamaan',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $totalDays = $start->diffInDays($end) + 1;

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('ess_attachments', 'public');
        }

        LeaveRequest::create([
            'employee_id' => $employee->id,
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'attachment' => $attachmentPath,
            'status' => 'Submitted',
        ]);

        return redirect()->route('ess.index')->with('success', 'Pengajuan cuti/izin berhasil diajukan.');
    }

    /**
     * Store CICO Correction from Employee.
     */
    public function storeCico(Request $request)
    {
        $user = $this->getSessionUser();
        if (!$user) {
            return redirect()->route('login');
        }

        $employee = $user->employee;
        if (!$employee) {
            return redirect()->back()->withErrors(['error' => 'Pegawai tidak ditemukan.']);
        }

        $request->validate([
            'date' => 'required|date|before_or_equal:today',
            'clock_in' => 'required|date_format:H:i',
            'clock_out' => 'required|date_format:H:i|after:clock_in',
            'reason' => 'required|string|max:500',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('ess_attachments', 'public');
        }

        CicoCorrection::create([
            'employee_id' => $employee->id,
            'date' => $request->date,
            'clock_in' => $request->clock_in,
            'clock_out' => $request->clock_out,
            'reason' => $request->reason,
            'attachment' => $attachmentPath,
            'status' => 'Submitted',
        ]);

        return redirect()->route('ess.index')->with('success', 'Pengajuan koreksi jam absensi CICO berhasil diajukan.');
    }

    /**
     * Admin/HR view: List of all leave & CICO requests.
     */
    public function adminIndex(Request $request)
    {
        $user = $this->getSessionUser();
        if (!$user || !in_array($user->role, ['Admin', 'HR'])) {
            return redirect()->route('dashboard.index')->withErrors(['error' => 'Akses ditolak. Menu ini hanya untuk Admin/HR.']);
        }

        $leaveRequests = LeaveRequest::with('employee')->orderBy('created_at', 'desc')->get();
        $cicoCorrections = CicoCorrection::with('employee')->orderBy('created_at', 'desc')->get();

        return view('ess.admin', compact('leaveRequests', 'cicoCorrections'));
    }

    /**
     * Action (Approve/Reject) Leave Request.
     */
    public function actionLeave(Request $request, $id, $status)
    {
        $user = $this->getSessionUser();
        if (!$user || !in_array($user->role, ['Admin', 'HR'])) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        if (!in_array($status, ['Approved', 'Rejected'])) {
            return redirect()->back()->withErrors(['error' => 'Status tidak valid.']);
        }

        $leave = LeaveRequest::findOrFail($id);
        $leave->update([
            'status' => $status,
            'approved_by' => $user->id,
            'approval_date' => Carbon::now(),
        ]);

        // Jika disetujui, update/insert tabel Attendances
        if ($status === 'Approved') {
            $start = Carbon::parse($leave->start_date);
            $end = Carbon::parse($leave->end_date);
            $statusAbsen = $leave->type === 'Sakit' ? 'Sakit' : 'Izin';

            for ($date = $start; $date->lte($end); $date->addDay()) {
                Attendance::updateOrCreate(
                    [
                        'employee_id' => $leave->employee_id,
                        'date' => $date->format('Y-m-d'),
                    ],
                    [
                        'status' => $statusAbsen,
                        'clock_in' => null,
                        'clock_out' => null,
                        'overtime_hours' => 0.00,
                        'notes' => 'Cuti/Izin ESS: ' . $leave->reason,
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Status pengajuan cuti berhasil diubah menjadi ' . $status);
    }

    /**
     * Action (Approve/Reject) CICO Correction.
     */
    public function actionCico(Request $request, $id, $status)
    {
        $user = $this->getSessionUser();
        if (!$user || !in_array($user->role, ['Admin', 'HR'])) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        if (!in_array($status, ['Approved', 'Rejected'])) {
            return redirect()->back()->withErrors(['error' => 'Status tidak valid.']);
        }

        $cico = CicoCorrection::findOrFail($id);
        $cico->update([
            'status' => $status,
            'approved_by' => $user->id,
            'approval_date' => Carbon::now(),
        ]);

        // Jika disetujui, update/insert tabel Attendances
        if ($status === 'Approved') {
            Attendance::updateOrCreate(
                [
                    'employee_id' => $cico->employee_id,
                    'date' => $cico->date->format('Y-m-d'),
                ],
                [
                    'status' => 'Hadir',
                    'clock_in' => $cico->clock_in,
                    'clock_out' => $cico->clock_out,
                    'notes' => 'Koreksi CICO ESS: ' . $cico->reason,
                ]
            );
        }

        return redirect()->back()->with('success', 'Status pengajuan koreksi CICO berhasil diubah menjadi ' . $status);
    }
}
