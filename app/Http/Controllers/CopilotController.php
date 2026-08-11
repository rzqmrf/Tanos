<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CopilotController extends Controller
{
    /**
     * Model Gemini yang digunakan untuk chat.
     */
    protected const GEMINI_MODEL = 'gemini-3.5-flash';

    /**
     * URL API Gemini (tanpa key, key dikirim via header).
     */
    protected const GEMINI_ENDPOINT = 'https://generativelanguage.googleapis.com/v1beta';

    /**
     * Menampilkan halaman Copilot.
     */
    public function index()
    {
        $hasKey = $this->hasApiKey();

        return view('copilot.index', compact('hasKey'));
    }

    /**
     * Memproses pesan chat dari user dan mengembalikan jawaban AI.
     */
    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        if (! $this->hasApiKey()) {
            return $this->jsonResponse($this->missingApiKeyMessage());
        }

        try {
            // Cek apakah ada perintah perubahan akses (Permission Command)
            $actionResult = $this->tryHandlePermissionCommand($request->input('message'));
            if ($actionResult !== null) {
                return $this->jsonResponse($actionResult);
            }

            $response = $this->askGemini(
                $request->input('message'),
                $this->buildDatabaseContext()
            );

            if ($response->failed()) {
                return $this->jsonResponse($this->apiErrorMessage($response->json()));
            }

            $aiText = $this->extractGeminiText($response->json())
                ?? 'Maaf, AI tidak mengembalikan respon valid.';

            // Periksa jika AI merekomendasikan aksi perubahan izin secara otomatis
            $aiText = $this->processAiActionTags($aiText);

            return $this->jsonResponse($aiText);
        } catch (\Exception $e) {
            return $this->jsonResponse($this->connectionErrorMessage($e));
        }
    }

    /**
     * Cek apakah GEMINI_API_KEY sudah dikonfigurasi.
     */
    private function hasApiKey(): bool
    {
        return ! empty(config('services.gemini.api_key'));
    }

    /**
     * Mengirim pesan ke Gemini dan mengembalikan response mentah.
     */
    private function askGemini(string $userMessage, string $context)
    {
        return Http::timeout(60)->withHeaders([
            'Content-Type' => 'application/json',
            'X-Goog-Api-Key' => config('services.gemini.api_key'),
        ])->post($this->geminiUrl(), [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $userMessage],
                    ],
                ],
            ],
            'systemInstruction' => [
                'parts' => [
                    ['text' => $context],
                ],
            ],
        ]);
    }

    /**
     * Membangun URL endpoint Gemini untuk model yang digunakan.
     */
    private function geminiUrl(): string
    {
        return self::GEMINI_ENDPOINT.'/models/'.self::GEMINI_MODEL.':generateContent';
    }

    /**
     * Mengekstrak teks jawaban dari response JSON Gemini.
     */
    private function extractGeminiText(array $payload): ?string
    {
        return $payload['candidates'][0]['content']['parts'][0]['text'] ?? null;
    }

    /**
     * Membungkus pesan error dari Gemini API menjadi pesan untuk user.
     */
    private function apiErrorMessage(?array $payload): string
    {
        return $payload['error']['message'] ?? 'Unknown API Error';
    }

    /**
     * Membangun RAG context komprehensif dari seluruh database models Tanos ERP.
     */
    private function buildDatabaseContext()
    {
        $today = Carbon::today()->format('d M Y');

        $projectsText = $this->buildProjectsText();
        $orgText = $this->buildOrgText();
        $employeesText = $this->buildEmployeesText();
        $payrollText = $this->buildPayrollText();
        $billingText = $this->buildBillingText();
        $essText = $this->buildEssText();
        $timeText = $this->buildTimeManagementText();
        $attendanceText = $this->buildAttendanceText($today);
        $crossModuleText = $this->buildCrossModuleMatrixText();

        return "Anda adalah Tanos Copilot, asisten AI cerdas untuk Tanos ERP (Enterprise Resource Planning) yang digunakan oleh PT Pelindo untuk mengelola Tenaga Alih Daya (TAD), Struktur Organisasi, Payroll, Billing, dan Proyek System.

Berikut adalah data terintegrasi secara REAL-TIME dari database Tanos ERP untuk membantu Anda menjawab pertanyaan user secara presisi:

=========================================
1. DATA MATRIKS INTEGRASI LINTAS MODUL (PROYEK + PEGAWAI + PAYROLL + BILLING SAP):
{$crossModuleText}

=========================================
2. DATA PROYEK AKTIF, RAB, & WBS (Project System):
{$projectsText}

=========================================
3. DATA STRUKTUR ORGANISASI (STO, JOB POSITION, ECN):
{$orgText}

=========================================
4. DATA TENAGA ALIH DAYA (TAD / KARYAWAN):
{$employeesText}

=========================================
5. DATA PROSES PAYROLL (GAJI & FORMULASI):
{$payrollText}

=========================================
6. DATA BILLING (PRANOTA & NOTA BILLING SAP):
{$billingText}

=========================================
7. DATA EMPLOYEE SELF SERVICE (ESS APPROVALS):
{$essText}

=========================================
8. DATA TIME MANAGEMENT & JADWAL SHIFT:
{$timeText}

=========================================
9. RINGKASAN ABSENSI HARI INI (DARI DATABASE):
{$attendanceText}
=========================================

Aturan Penting saat Menjawab Pertanyaan User:
1. Jawablah menggunakan Bahasa Indonesia yang ramah, profesional, ringkas, dan sangat presisi.
2. ANALISIS LINTAS MODUL (CROSS-MODULE QUERY): Jika user menanyakan gabungan data antar modul (seperti gabungan proyek + jumlah pegawai + total payroll + SAP billing, atau ringkasan kondisi regional tertentu seperti Regional Jawa), gunakan DATA MATRIKS INTEGRASI LINTAS MODUL di atas untuk menyajikannya secara holistik dan cerdas.
3. JANGAN mengarang data atau memunculkan informasi fiktif di luar data di atas jika user menanyakan data internal yang spesifik. Jika data tidak tersedia di database, katakan dengan jujur bahwa datanya belum diisi.
4. FORMAT DISPLAY: 
   - Jika user meminta data ditampilkan dalam bentuk tabel, buatlah tabel menggunakan tag HTML standar (<table>, <thead>, <tbody>, <tr>, <th>, <td>) dengan class styling Tailwind yang bersih (misal: 'min-w-full divide-y divide-slate-200 border border-slate-200 rounded-lg overflow-hidden' dan 'px-3 py-2 text-left bg-slate-50 font-bold') agar bisa dirender dengan indah di chat bubble.
   - Gunakan format mata uang Rupiah (contoh: Rp 1.500.000.000) untuk nilai nominal anggaran/payroll/billing.
5. Anda dapat menjawab pertanyaan umum di luar sistem ERP (seperti salam, pertanyaan umum, saran manajemen proyek) secara ramah, tetapi arahkan kembali user ke topik Tanos ERP.
6. PERUBAHAN HAK AKSES: Jika user meminta Anda mengubah/membuka/menutup hak akses (role permissions) suatu modul untuk peran tertentu, tambahkan tag khusus di akhir jawaban Anda: [ACTION_PERM:NamaRole:NamaPermission:true|false]. Contoh: [ACTION_PERM:HR Manager:payroll:true].";
    }

    /**
     * Menyusun ringkasan proyek aktif, WBS, dan anggaran RAB.
     */
    private function buildProjectsText(): string
    {
        try {
            $months = \App\Services\DashboardService::generateLast6Months();

            $lines = [];
            foreach ($months as $month) {
                $lines[] = $this->projectSummaryForMonth($month);
            }

            $totalRab = \App\Models\RabBudget::count();
            $sumRab = \App\Models\RabBudget::sum('total_revenue');
            $totalWbs = \App\Models\WbsElement::count();

            $lines[] = "RAB & WBS Overview:\n- Total Rencana Anggaran Biaya (RAB): {$totalRab} dokumen | Alokasi Rp " . number_format($sumRab, 0, ',', '.') . "\n- Total Elemen WBS: {$totalWbs} elemen terstruktur";

            return implode("\n\n", $lines);
        } catch (\Exception $e) {
            return 'Gagal memuat data proyek dari database: '.$e->getMessage();
        }
    }

    /**
     * Menyusun data Struktur Organisasi (STO, Job Position, ECN).
     */
    private function buildOrgText(): string
    {
        try {
            $totalUnits = \App\Models\Division::count();
            $activeUnits = \App\Models\Division::where('active', 1)->count();
            $totalJobs = \App\Models\JobPosition::count();
            $activeJobs = \App\Models\JobPosition::where('active', 1)->count();
            $totalEcn = \App\Models\EmployeeMovement::count();

            return "STO Chart (Unit/Departemen): {$totalUnits} unit ({$activeUnits} aktif)\n"
                ."Job Position (Formasi Jabatan): {$totalJobs} jabatan ({$activeJobs} aktif)\n"
                ."Employee Change Notice (ECN / Mutasi): {$totalEcn} usulan pergerakan tercatat";
        } catch (\Exception $e) {
            return "STO & Organization Data: Belum dapat dimuat.";
        }
    }

    /**
     * Mengambil data rinci karyawan TAD dari database.
     */
    private function buildEmployeesText(): string
    {
        try {
            $totalEmployees = Employee::count();

            $byRegional = Employee::groupBy('regional')
                ->selectRaw('regional, COUNT(*) as total')
                ->pluck('total', 'regional');
            
            $bySegment = Employee::groupBy('segment')
                ->selectRaw('segment, COUNT(*) as total')
                ->pluck('total', 'segment');

            $regStr = $byRegional->map(fn($c, $r) => "{$r}: {$c}")->implode(', ');
            $segStr = $bySegment->map(fn($c, $s) => "{$s}: {$c}")->implode(', ');

            return "Total Tenaga Alih Daya (TAD) terdaftar: {$totalEmployees} orang.\n"
                ."- Per Regional: {$regStr}\n"
                ."- Per Segment: {$segStr}";
        } catch (\Exception $e) {
            return 'Total Tenaga Alih Daya (TAD) terdaftar: '.$e->getMessage();
        }
    }

    /**
     * Data proses payroll & penggajian dari database.
     */
    private function buildPayrollText(): string
    {
        try {
            $periodsCount = \App\Models\PayrollPeriod::count();
            $latestPeriod = \App\Models\PayrollPeriod::latest()->first();

            if (!$latestPeriod) {
                return "Belum ada periode payroll yang dibuat di sistem.";
            }

            $resultsCount = \App\Models\PayrollResult::where('payroll_period_id', $latestPeriod->id)->count();
            $totalTHP = \App\Models\PayrollResult::where('payroll_period_id', $latestPeriod->id)->sum('take_home_pay');

            return "Total Periode Payroll: {$periodsCount} periode\n"
                ."Periode Terbaru: {$latestPeriod->period_name} ({$latestPeriod->status})\n"
                ."- Karyawan Diproses: {$resultsCount} orang\n"
                ."- Total Take Home Pay (THP): Rp " . number_format($totalTHP, 0, ',', '.') . "\n"
                ."- Status SAP Posting: " . ($latestPeriod->is_posted_sap ? 'Sudah Di-Posting ke SAP' : 'Belum Di-Posting');
        } catch (\Exception $e) {
            return "Payroll Data: Belum ada periode payroll aktif.";
        }
    }

    /**
     * Data Pranota & Nota Billing SAP.
     */
    private function buildBillingText(): string
    {
        try {
            $pranotaCount = \App\Models\PranotaBilling::count();
            $pranotaSum = \App\Models\PranotaBilling::sum('total_amount');

            $notaCount = \App\Models\NotaBilling::count();
            $notaSum = \App\Models\NotaBilling::sum('total_amount');
            $postedNotaCount = \App\Models\NotaBilling::where('sap_posted', 1)->count();

            return "Pranota Billing: {$pranotaCount} dokumen | Total Nominal: Rp " . number_format($pranotaSum, 0, ',', '.') . "\n"
                ."Nota Billing (Invoice): {$notaCount} dokumen | Total Nominal: Rp " . number_format($notaSum, 0, ',', '.') . "\n"
                ."- Invoices Ter-posting ke SAP AR: {$postedNotaCount} dari {$notaCount} nota";
        } catch (\Exception $e) {
            return "Billing Data: Belum dapat dimuat.";
        }
    }

    /**
     * Data Employee Self Service (ESS) Approvals.
     */
    private function buildEssText(): string
    {
        try {
            $pendingLeave = \App\Models\LeaveRequest::where('status', 'Pending')->count();
            $approvedLeave = \App\Models\LeaveRequest::where('status', 'Approved')->count();

            $pendingCico = \App\Models\CicoCorrection::where('status', 'Pending')->count();
            $approvedCico = \App\Models\CicoCorrection::where('status', 'Approved')->count();

            return "Permohonan Cuti/Izin: {$pendingLeave} menunggu persetujuan (Approved: {$approvedLeave})\n"
                ."Koreksi Absensi (CICO): {$pendingCico} menunggu persetujuan (Approved: {$approvedCico})";
        } catch (\Exception $e) {
            return "ESS Data: Belum dapat dimuat.";
        }
    }

    /**
     * Data Time Management & Jadwal Shift.
     */
    private function buildTimeManagementText(): string
    {
        try {
            $scheduleGroups = \App\Models\ScheduleGroup::count();
            $assignments = \App\Models\ScheduleAssignment::count();
            $absentTypes = \App\Models\AbsentType::count();

            return "Grup Jadwal Shift: {$scheduleGroups} grup\n"
                ."Penugasan Jadwal ESS: {$assignments} penugasan karyawan\n"
                ."Tipe Absensi & Cuti: {$absentTypes} tipe terdaftar";
        } catch (\Exception $e) {
            return "Time Management Data: Belum dapat dimuat.";
        }
    }

    /**
     * Ringkasan agregat proyek aktif untuk satu bulan.
     */
    private function projectSummaryForMonth(string $month): string
    {
        $query = Project::where('month', $month)->where('active', 1);
        $total = $query->count();
        $budget = $query->sum('cost');

        $byRegional = Project::where('month', $month)->where('active', 1)
            ->groupBy('regional')
            ->selectRaw('regional, COUNT(*) as total')
            ->orderBy('total', 'desc')
            ->pluck('total', 'regional');

        $bySegment = Project::where('month', $month)->where('active', 1)
            ->groupBy('segment')
            ->selectRaw('segment, COUNT(*) as total')
            ->orderBy('total', 'desc')
            ->pluck('total', 'segment');

        $regionalText = $byRegional->isNotEmpty()
            ? $byRegional->map(fn ($c, $r) => "{$r} ({$c})")->implode(', ')
            : 'tidak ada';

        $segmentText = $bySegment->isNotEmpty()
            ? $bySegment->map(fn ($c, $s) => "{$s} ({$c})")->implode(', ')
            : 'tidak ada';

        $budgetFormatted = 'Rp ' . number_format($budget, 0, ',', '.');

        return "Bulan {$month}: {$total} proyek aktif | Total anggaran aktif: {$budgetFormatted}"
            . "\n- Per Regional: {$regionalText}"
            . "\n- Per Segment: {$segmentText}";
    }

    /**
     * Data sertifikasi yang segera habis — belum tersedia di database.
     * Dilaporkan jujur supaya Copilot tidak mengarang nama/nomor.
     */
    private function buildExpiryText(): string
    {
        return 'Data sertifikasi karyawan belum tersedia di database. '
            .'Jika ditanya, jawab dengan jujur bahwa fitur sertifikasi belum diisi datanya. '
            .'JANGAN mengarang nama karyawan atau data sertifikasi apapun.';
    }

    /**
     * Menyusun ringkasan absensi hari ini dari data riil database.
     */
    private function buildAttendanceText(string $today): string
    {
        try {
            $totalEmployees = Employee::count();
            $todayDate = Carbon::today()->format('Y-m-d');

            $hadir = Attendance::where('date', $todayDate)->where('status', 'Hadir')->count();
            $izin = Attendance::where('date', $todayDate)->where('status', 'Izin')->count();
            $sakit = Attendance::where('date', $todayDate)->where('status', 'Sakit')->count();
            $alfa = Attendance::where('date', $todayDate)->where('status', 'Alfa')->count();

            return "Tanggal: {$today}\n"
                ."Total TAD: {$totalEmployees}\n"
                ."Hadir: {$hadir} orang\n"
                ."Izin: {$izin} orang\n"
                ."Sakit: {$sakit} orang\n"
                ."Alfa: {$alfa} orang\n"
                .'(Data riil dari tabel absensi, dihitung otomatis dari database)';
        } catch (\Exception $e) {
            return "Tanggal: {$today}\nTotal TAD: Tidak dapat dimuat (terjadi kesalahan).\nData absensi tidak tersedia saat ini.";
        }
    }

    /**
     * Menyusun matriks integrasi data lintas modul (Project + SDM Penempatan + Budget RAB + Payroll THP + Billing SAP).
     */
    private function buildCrossModuleMatrixText(): string
    {
        try {
            $projects = Project::with(['rabBudget', 'payrollPeriods.results', 'notaBillings'])->get();

            if ($projects->isEmpty()) {
                return "Belum ada data detail proyek terdaftar.";
            }

            $matrix = [];
            foreach ($projects as $prj) {
                // RAB: kolom yang tersedia adalah total_revenue / total_cost (bukan total_budget)
                $rabTotal = $prj->rabBudget ? $prj->rabBudget->total_revenue : $prj->cost;

                // Total THP dari hasil payroll yang terkait dengan proyek ini
                $totalPayroll = 0;
                foreach ($prj->payrollPeriods as $period) {
                    $totalPayroll += $period->results()->sum('net_salary');
                }

                // Status Nota Billing SAP
                $notaCount = $prj->notaBillings->count();
                $notaSum = $prj->notaBillings->sum('total_amount');
                $postedCount = $prj->notaBillings->where('sap_posted', 1)->count();

                // Jumlah pegawai yang ditempatkan di proyek ini (via employees.project_id)
                $employeesCount = \App\Models\Employee::where('project_id', $prj->id)->count();

                $matrix[] = "- Proyek: [ID {$prj->id}] {$prj->project_name} ({$prj->project_code})\n"
                    . "  • Regional: {$prj->regional} | Segment: {$prj->segment} | Status: " . ($prj->active ? 'Aktif' : 'Non-Aktif') . "\n"
                    . "  • Total Anggaran / RAB: Rp " . number_format($rabTotal, 0, ',', '.') . "\n"
                    . "  • Penempatan SDM (TAD): {$employeesCount} orang pegawai\n"
                    . "  • Total Payroll (Net THP): Rp " . number_format($totalPayroll, 0, ',', '.') . "\n"
                    . "  • SAP Billing: {$notaCount} nota (Total Rp " . number_format($notaSum, 0, ',', '.') . ") | Ter-posted SAP: {$postedCount} nota";
            }

            return implode("\n\n", $matrix);
        } catch (\Exception $e) {
            return "Cross-Module Matrix Data: " . $e->getMessage();
        }
    }

    /**
     * Pesan ketika GEMINI_API_KEY belum dikonfigurasi.
     */
    private function missingApiKeyMessage(): string
    {
        return "⚠️ **Kunci API Gemini Belum Dikonfigurasi!**\n\nUntuk menggunakan fitur AI Asli, silakan ikuti langkah berikut:\n1. Buka file `.env` di root direktori proyek Anda (`d:\PROJEK\\tanos-erp\.env`).\n2. Tambahkan baris baru: `GEMINI_API_KEY=masukkan_api_key_disini`\n3. Dapatkan kunci API gratis secara instan di [Google AI Studio](https://aistudio.google.com/).\n4. Simpan file `.env` dan silakan coba kirim pesan kembali! ✨";
    }

    /**
     * Pesan ketika terjadi error saat menghubungi Gemini.
     */
    private function connectionErrorMessage(\Exception $e): string
    {
        return "❌ **Gagal menghubungi Gemini API:**\n\nError: `{$e->getMessage()}`\n\nPastikan Kunci API di file `.env` Anda valid dan memiliki kuota aktif.";
    }

    /**
     * Memproses perintah perubahan hak akses langsung dari pesan user.
     */
    private function tryHandlePermissionCommand(string $userMessage): ?string
    {
        $lower = strtolower($userMessage);

        // Memeriksa kata kunci aksi
        $isEnable = str_contains($lower, 'buka') || str_contains($lower, 'aktif') || str_contains($lower, 'berikan') || str_contains($lower, 'tambah');
        $isDisable = str_contains($lower, 'tutup') || str_contains($lower, 'mati') || str_contains($lower, 'cabut') || str_contains($lower, 'hapus');

        if (! ($isEnable || $isDisable) || ! (str_contains($lower, 'akses') || str_contains($lower, 'izin') || str_contains($lower, 'permission'))) {
            return null;
        }

        $targetState = $isEnable;

        $rolesMap = [
            'hr manager' => 'HR Manager',
            'hr' => 'HR Manager',
            'finance manager' => 'Finance Manager',
            'finance' => 'Finance Manager',
            'project manager' => 'Project Manager',
            'project' => 'Project Manager',
            'employee' => 'Employee',
            'regular employee' => 'Employee',
            'admin' => 'Admin',
        ];

        $permsMap = [
            'payroll' => 'payroll',
            'gaji' => 'payroll',
            'penggajian' => 'payroll',
            'projects' => 'projects',
            'proyek' => 'projects',
            'employees' => 'employees',
            'karyawan' => 'employees',
            'pegawai' => 'employees',
            'invoices' => 'invoices',
            'billing' => 'invoices',
            'nota' => 'invoices',
            'reports' => 'reports',
            'laporan' => 'reports',
            'schedules' => 'schedules',
            'jadwal' => 'schedules',
            'attendance' => 'attendance',
            'absensi' => 'attendance',
            'settings' => 'settings',
            'pengaturan' => 'settings',
        ];

        $matchedRole = null;
        foreach ($rolesMap as $key => $roleName) {
            if (str_contains($lower, $key)) {
                $matchedRole = $roleName;
                break;
            }
        }

        $matchedPerm = null;
        foreach ($permsMap as $key => $permKey) {
            if (str_contains($lower, $key)) {
                $matchedPerm = $permKey;
                break;
            }
        }

        if ($matchedRole && $matchedPerm) {
            \App\Models\RolePermission::updateOrCreate(
                ['role' => $matchedRole, 'permission' => $matchedPerm],
                ['is_enabled' => $targetState]
            );

            $statusText = $targetState ? 'BERHASIL DIAKTIFKAN ✅' : 'BERHASIL DIMATIKAN ❌';
            return "✅ **Pembaruan Hak Akses Diterapkan!**\n\nHak akses modul **'{$matchedPerm}'** untuk peran **'{$matchedRole}'** telah {$statusText}.\n\n*Pengguna dengan peran {$matchedRole} akan langsung melihat perubahan visibilitas menu di sidebar setelah halaman di-refresh.*";
        }

        return null;
    }

    /**
     * Memproses tag aksi otomatis dari AI jika ada.
     */
    private function processAiActionTags(string $aiText): string
    {
        if (preg_match('/\[ACTION_PERM:(.*?):(.*?):(true|false)\]/i', $aiText, $matches)) {
            $role = trim($matches[1]);
            $perm = trim($matches[2]);
            $state = strtolower(trim($matches[3])) === 'true';

            \App\Models\RolePermission::updateOrCreate(
                ['role' => $role, 'permission' => $perm],
                ['is_enabled' => $state]
            );

            $cleanText = preg_replace('/\[ACTION_PERM:.*?\]/i', '', $aiText);
            $statusStr = $state ? 'diaktifkan' : 'dimatikan';
            return trim($cleanText) . "\n\n*(Sistem telah otomatis memperbarui database: Akses '{$perm}' untuk peran '{$role}' telah {$statusStr})*";
        }

        return $aiText;
    }

    /**
     * Membungkus jawaban menjadi JSON response.
     */
    private function jsonResponse(string $response): JsonResponse
    {
        return response()->json(['response' => $response]);
    }
}
