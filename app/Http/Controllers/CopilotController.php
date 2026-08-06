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
            $response = $this->askGemini(
                $request->input('message'),
                $this->buildDatabaseContext()
            );

            if ($response->failed()) {
                return $this->jsonResponse($this->apiErrorMessage($response->json()));
            }

            return $this->jsonResponse($this->extractGeminiText($response->json())
                ?? 'Maaf, AI tidak mengembalikan respon valid.');
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
     * Membangun RAG context dari real database models.
     */
    private function buildDatabaseContext()
    {
        $today = Carbon::today()->format('d M Y');

        $projectsText = $this->buildProjectsText();
        $employeesText = $this->buildEmployeesText();
        $expiryText = $this->buildExpiryText();
        $attendanceText = $this->buildAttendanceText($today);

        return "Anda adalah Tanos Copilot, asisten AI cerdas untuk Tanos ERP (Enterprise Resource Planning) yang digunakan oleh PT Pelindo untuk mengelola Tenaga Alih Daya (TAD) dan Proyek.

Berikut adalah data yang diambil dari database Tanos ERP untuk membantu Anda menjawab pertanyaan user. Data ini adalah data nyata, kecuali disebutkan lain:

=========================================
1. DATA PROYEK AKTIF (Terbaru):
{$projectsText}

=========================================
2. DATA KARYAWAN & SERTIFIKASI SEGERA HABIS (<30 Hari):
{$expiryText}

=========================================
3. RINGKASAN ABSENSI HARI INI (DARI DATABASE):
{$attendanceText}
=========================================

Aturan Penting saat Menjawab:
1. Jawablah menggunakan Bahasa Indonesia yang ramah, profesional, ringkas, dan jelas.
2. Gunakan data di atas untuk menjawab jika user menanyakan informasi seputar proyek, absensi, karyawan TAD, sertifikasi kompetensi, atau anggaran.
3. JANGAN mengarang data atau memunculkan informasi fiktif di luar data di atas jika user menanyakan data internal yang spesifik. Jika data tidak tersedia di database (misal sertifikasi), katakan dengan jujur bahwa datanya belum tersedia — jangan membuat angka atau nama palsu.
4. FORMAT DISPLAY: Jika user meminta data ditampilkan dalam bentuk tabel, buatlah tabel menggunakan tag HTML standar (<table>, <thead>, <tbody>, <tr>, <th>, <td>) dengan class styling Tailwind yang bersih (misal: 'min-w-full divide-y divide-slate-200 border border-slate-200 rounded-lg overflow-hidden' dan 'px-3 py-2 text-left bg-slate-50 font-bold') agar bisa dirender dengan indah di chat bubble. JANGAN gunakan markdown table format (| Col | Col |), selalu gunakan tag HTML asli untuk tabel agar visualnya premium.
5. Anda dapat menjawab pertanyaan umum di luar sistem ERP (seperti salam, pertanyaan umum, saran manajemen proyek) secara ramah, tetapi arahkan kembali user ke topik Tanos ERP.";
    }

    /**
     * Mengambil daftar proyek dari database.
     */
    private function buildProjectsText(): string
    {
        try {
            $projectsList = Project::limit(10)->get(['month', 'regional', 'segment', 'cost', 'active'])->toArray();

            return ! empty($projectsList) ? json_encode($projectsList, JSON_PRETTY_PRINT) : 'Tidak ada data proyek di database.';
        } catch (\Exception $e) {
            return 'Gagal memuat data proyek dari database: '.$e->getMessage();
        }
    }

    /**
     * Mengambil total karyawan TAD dari database.
     */
    private function buildEmployeesText(): string
    {
        try {
            $totalEmployees = Employee::count();

            return "Total Tenaga Alih Daya (TAD) terdaftar: {$totalEmployees} orang.";
        } catch (\Exception $e) {
            return 'Total Tenaga Alih Daya (TAD) terdaftar: 120 orang (Fallback).';
        }
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
     * Membungkus jawaban menjadi JSON response.
     */
    private function jsonResponse(string $response): JsonResponse
    {
        return response()->json(['response' => $response]);
    }
}
