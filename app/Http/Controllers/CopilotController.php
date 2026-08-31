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
    protected const GEMINI_MODEL = 'gemini-flash-latest';

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
        set_time_limit(60);

        $request->validate([
            'message' => 'required|string',
        ]);

        if (! $this->hasApiKey()) {
            return $this->jsonResponse($this->missingApiKeyMessage());
        }

        try {
            $msg = strtolower(trim($request->input('message')));

            // Short-circuit greeting — tanpa panggil Gemini
            if (in_array($msg, ['hi', 'hai', 'oi', 'halo', 'hello', 'p'])) {
                return $this->jsonResponse('Halo! Ada yang bisa dibantu soal Tanos ERP hari ini?');
            }

            // Short-circuit absensi — jawab langsung dari database
            $attendanceResponse = $this->tryHandleAttendanceQuery($request->input('message'));
            if ($attendanceResponse !== null) {
                return $this->jsonResponse($attendanceResponse);
            }

            // Barulah panggil Gemini (RBAC ada di route middleware)
            $response = $this->askGemini(
                $request->input('message'),
                $this->buildDatabaseContext()
            );

            if ($response->failed()) {
                return $this->jsonResponse($this->apiErrorMessage($response->json()));
            }

            $aiText = $this->extractGeminiText($response->json())
                ?? 'Maaf, AI tidak mengembalikan respon valid.';

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
     * Membangun RAG context — kecil, cuma 3 seksi.
     */
    private function buildDatabaseContext()
    {
        return cache()->remember('tanos_copilot_db_context', 600, function () {
            $today = Carbon::today()->format('d M Y');

            $projectsText = $this->buildProjectsText();
            $employeesText = $this->buildEmployeesText();
            $attendanceText = $this->buildAttendanceText($today);

            return <<<EOD
Anda adalah Tanos Copilot, asisten AI cerdas untuk Tanos ERP (Enterprise Resource Planning) yang digunakan oleh PT Pelindo untuk mengelola Tenaga Alih Daya (TAD) dan Proyek.

Berikut adalah data riil terupdate dari database Tanos ERP untuk membantu Anda menjawab pertanyaan user secara presisi:

=========================================
1. DATA PROYEK AKTIF (Terbaru):
{$projectsText}

=========================================
2. DATA KARYAWAN & SERTIFIKASI:
{$employeesText}

=========================================
3. RINGKASAN ABSENSI HARI INI:
{$attendanceText}
=========================================

Aturan Penting saat Menjawab Pertanyaan User:
1. Jawablah menggunakan Bahasa Indonesia yang ramah, profesional, ringkas, dan sangat presisi.
2. Gunakan data di atas untuk menjawab jika user menanyakan informasi seputar proyek, absensi, karyawan TAD, atau anggaran.
3. JANGAN mengarang data atau memunculkan informasi fiktif di luar data di atas jika user menanyakan data internal yang spesifik. Jika data tidak tersedia di database, katakan dengan jujur bahwa datanya belum diisi.
4. FORMAT DISPLAY: Jika user meminta data ditampilkan dalam bentuk tabel, buatlah tabel menggunakan tag HTML standar (<table>, <thead>, <tbody>, <tr>, <th>, <td>) dengan class styling Tailwind yang bersih (misal: 'min-w-full divide-y divide-slate-200 border border-slate-200 rounded-lg overflow-hidden' dan 'px-3 py-2 text-left bg-slate-50 font-bold') agar bisa dirender dengan indah di chat bubble. Gunakan format mata uang Rupiah (contoh: Rp 1.500.000.000) untuk nilai nominal anggaran/payroll/billing.
5. Anda dapat menjawab pertanyaan umum di luar sistem ERP (seperti salam, pertanyaan umum, saran manajemen proyek) secara ramah, tetapi arahkan kembali user ke topik Tanos ERP.
EOD;
        });
    }

    /**
     * Menyusun detail proyek terbaru (limit 10) dari database.
     */
    private function buildProjectsText(): string
    {
        try {
            $projects = Project::select('id', 'project_name', 'project_code', 'regional', 'segment', 'active')
                ->where('active', 1)
                ->latest()
                ->take(10)
                ->get();

            if ($projects->isEmpty()) {
                return 'Belum ada proyek aktif terdaftar.';
            }

            $lines = [];
            foreach ($projects as $project) {
                $lines[] = "- {$project->project_name} ({$project->project_code}) | {$project->regional} | {$project->segment} | Status: Aktif";
            }

            $totalActive = Project::where('active', 1)->count();
            $byRegional = Project::where('active', 1)
                ->selectRaw('regional, COUNT(*) as total')
                ->groupBy('regional')
                ->pluck('total', 'regional')
                ->map(fn ($c, $r) => "{$r}: {$c}")->implode(', ');

            return "Total proyek aktif: {$totalActive} (per regional: {$byRegional})\n\n" . implode("\n", $lines);
        } catch (\Exception $e) {
            return 'Gagal memuat data proyek dari database: '.$e->getMessage();
        }
    }

    /**
     * Total karyawan TAD terdaftar.
     */
    private function buildEmployeesText(): string
    {
        try {
            $totalEmployees = Employee::count();

            return "Total Tenaga Alih Daya (TAD) terdaftar: {$totalEmployees} orang.\n"
                ."Data sertifikasi belum tersedia di database — jika ditanya, jawab jujur bahwa fitur sertifikasi belum diisi datanya. JANGAN mengarang nama karyawan atau nomor sertifikasi apapun.";
        } catch (\Exception $e) {
            return "Total Tenaga Alih Daya (TAD) terdaftar: Tidak dapat dimuat.\n"
                ."Data sertifikasi belum tersedia di database — JANGAN mengarang.";
        }
    }

    /**
     * Ringkasan absensi hari ini dari data riil database.
     */
    private function buildAttendanceText(string $today): string
    {
        try {
            $todayDate = Carbon::today()->format('Y-m-d');

            $hadir = Attendance::where('date', $todayDate)->where('status', 'Hadir')->count();
            $izin = Attendance::where('date', $todayDate)->where('status', 'Izin')->count();
            $sakit = Attendance::where('date', $todayDate)->where('status', 'Sakit')->count();
            $alfa = Attendance::where('date', $todayDate)->where('status', 'Alfa')->count();

            return "Tanggal: {$today}\n"
                ."Hadir: {$hadir} orang\n"
                ."Izin: {$izin} orang\n"
                ."Sakit: {$sakit} orang\n"
                ."Alfa: {$alfa} orang\n"
                .'(Data riil dari tabel absensi, dihitung otomatis dari database)';
        } catch (\Exception $e) {
            return "Tanggal: {$today}\nData absensi tidak tersedia saat ini.";
        }
    }

    /**
     * Menangani query absensi singkat tanpa panggil Gemini.
     */
    private function tryHandleAttendanceQuery(string $userMessage): ?string
    {
        $lower = strtolower(trim($userMessage));

        if (! str_contains($lower, 'absensi')
            && ! str_contains($lower, 'absen')
            && ! str_contains($lower, 'attendance')
            && ! str_contains($lower, 'presensi')) {
            return null;
        }

        // Handler tanggal spesifik (misal: "27 Agustus 2026")
        if (preg_match('/(\d{1,2})\s+(januari|februari|maret|april|mei|juni|juli|agustus|september|oktober|november|desember)(?:\s+(\d{4}))?/i', $lower, $m)) {
            $day = (int) $m[1];
            $months = ['januari'=>1,'februari'=>2,'maret'=>3,'april'=>4,'mei'=>5,'juni'=>6,'juli'=>7,'agustus'=>8,'september'=>9,'oktober'=>10,'november'=>11,'desember'=>12];
            $month = $months[strtolower($m[2])];
            $year = !empty($m[3]) ? (int) $m[3] : (int) date('Y');

            if (checkdate($month, $day, $year)) {
                $dateStr = Carbon::create($year, $month, $day)->format('Y-m-d');
                $labelStr = Carbon::create($year, $month, $day)->format('d M Y');
                return $this->buildAttendanceDateText($dateStr, $labelStr);
            }
        }

        if (str_contains($lower, 'hari ini') || str_contains($lower, 'today')) {
            return $this->buildAttendanceText(Carbon::today()->format('d M Y'));
        }

        if (str_contains($lower, 'bulan ini')) {
            return $this->buildAttendanceMonthText(Carbon::now());
        }

        if (str_contains($lower, 'bulan lalu')) {
            return $this->buildAttendanceMonthText(Carbon::now()->subMonthNoOverflow());
        }

        if (str_contains($lower, 'bulan')) {
            $target = $this->parseMonthFromText($lower);
            if ($target !== null) {
                return $this->buildAttendanceMonthText($target);
            }
        }

        return 'Saya bisa bantu rekap absensi harian (misal: "27 Agustus 2026"), bulan ini, bulan lalu, atau bulan tertentu. Sebutkan periodenya.';
    }

    /**
     * Menyusun ringkasan absensi untuk tanggal tertentu dari data riil database.
     */
    private function buildAttendanceDateText(string $date, string $label): string
    {
        try {
            $hadir = Attendance::where('date', $date)->where('status', 'Hadir')->count();
            $izin = Attendance::where('date', $date)->where('status', 'Izin')->count();
            $sakit = Attendance::where('date', $date)->where('status', 'Sakit')->count();
            $alfa = Attendance::where('date', $date)->where('status', 'Alfa')->count();
            $total = $hadir + $izin + $sakit + $alfa;

            return "Tanggal: {$label}\n"
                ."Total record absensi: {$total}\n"
                ."Hadir: {$hadir} orang\n"
                ."Izin: {$izin} orang\n"
                ."Sakit: {$sakit} orang\n"
                ."Alfa: {$alfa} orang\n"
                .'(Data riil dari tabel absensi, dihitung otomatis dari database)';
        } catch (\Exception $e) {
            return "Tanggal: {$label}\nData absensi tidak tersedia saat ini.";
        }
    }

    /**
     * Menyusun ringkasan absensi untuk bulan tertentu dari data riil database.
     */
    private function buildAttendanceMonthText(?Carbon $targetMonth = null): string
    {
        try {
            $targetMonth = $targetMonth ?? Carbon::now();
            $monthLabel = $targetMonth->format('F Y');
            $start = $targetMonth->copy()->startOfMonth()->format('Y-m-d');
            $end = $targetMonth->copy()->endOfMonth()->format('Y-m-d');

            $baseQuery = Attendance::whereBetween('date', [$start, $end]);

            $totals = $baseQuery
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');

            $totalRecords = Attendance::whereBetween('date', [$start, $end])->count();
            $hadir = (int) ($totals['Hadir'] ?? 0);
            $izin = (int) ($totals['Izin'] ?? 0);
            $sakit = (int) ($totals['Sakit'] ?? 0);
            $alfa = (int) ($totals['Alfa'] ?? 0);

            return "Periode: {$monthLabel}\n"
                ."Total record absensi: {$totalRecords}\n"
                ."Hadir: {$hadir} record\n"
                ."Izin: {$izin} record\n"
                ."Sakit: {$sakit} record\n"
                ."Alfa: {$alfa} record\n"
                .'(Data riil dari tabel absensi, dihitung otomatis dari database)';
        } catch (\Exception $e) {
            return "Periode: " . Carbon::now()->format('F Y') . "\n"
                ."Data absensi bulan ini tidak tersedia saat ini.";
        }
    }

    /**
     * Memetakan nama bulan Indonesia dalam teks user menjadi Carbon bulan yang diminta.
     */
    private function parseMonthFromText(string $lower): ?Carbon
    {
        $months = [
            'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4, 'mei' => 5, 'juni' => 6,
            'juli' => 7, 'agustus' => 8, 'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12,
        ];

        foreach ($months as $name => $num) {
            if (str_contains($lower, $name)) {
                $year = (int) date('Y');
                if (preg_match('/20\d{2}/', $lower, $m)) {
                    $year = (int) $m[0];
                }

                return Carbon::create($year, $num, 1);
            }
        }

        return null;
    }

    /**
     * Pesan ketika GEMINI_API_KEY belum dikonfigurasi.
     */
    private function missingApiKeyMessage(): string
    {
        return "⚠️ **Kunci API Gemini Belum Dikonfigurasi!**\n\nUntuk menggunakan fitur AI Asli, silakan ikuti langkah berikut:\n1. Buka file `.env` di root direktori proyek Anda (`d:\PROJEK\\tanos-erp\.env`).\n2. Tambahkan baris baru: `GEMINI_API_KEY=masukkan_api_key_di sini`\n3. Dapatkan kunci API gratis secara instan di [Google AI Studio](https://aistudio.google.com/).\n4. Simpan file `.env` dan silakan coba kirim pesan kembali! ✨";
    }

    /**
     * Pesan ketika terjadi error saat menghubungi Gemini.
     */
    private function connectionErrorMessage(\Exception $e): string
    {
        return "❌ **Gagal menghubungi Gemini API:**\n\nError: `{$e->getMessage()}`\n\nPastikan Kunci API di file `.env` Anda valid dan memiliki kuota aktif.";
    }

    /**
     * Membungkus jawaban menjadi JSON response.
     */
    private function jsonResponse(string $response): JsonResponse
    {
        return response()->json(['response' => $response]);
    }
}