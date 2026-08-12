<?php

namespace App\Http\Controllers;

use App\Models\HcMasterData;
use Illuminate\Http\Request;

class HcMasterDataController extends Controller
{
    /**
     * Categories map
     */
    protected const CATEGORIES = [
        'regional' => 'Regional',
        'segment' => 'Segment',
        'job_class' => 'Job Class',
        'job_field' => 'Job Field',
        'employee_status' => 'Employee Status',
        'religion' => 'Religion',
        'job_status' => 'Job Status',
    ];

    public function index(Request $request, string $category = 'regional')
    {
        if (!array_key_exists($category, self::CATEGORIES)) {
            $category = 'regional';
        }

        $this->ensureDefaultSeeded($category);

        $query = HcMasterData::where('category', $category);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('name')->paginate(15);
        $totalActive = HcMasterData::where('category', $category)->where('active', 1)->count();
        $totalAll = HcMasterData::where('category', $category)->count();

        return view('hc.master-data', [
            'currentCategory' => $category,
            'categoryTitle' => self::CATEGORIES[$category],
            'categories' => self::CATEGORIES,
            'items' => $items,
            'totalActive' => $totalActive,
            'totalAll' => $totalAll,
        ]);
    }

    public function store(Request $request, string $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        HcMasterData::create([
            'category' => $category,
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'active' => $request->has('active') ? (bool) $request->active : true,
        ]);

        return redirect()->back()->with('success', 'Data ' . self::CATEGORIES[$category] . ' berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $item = HcMasterData::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $item->update([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
            'active' => $request->has('active') ? (bool) $request->active : $item->active,
        ]);

        return redirect()->back()->with('success', 'Data ' . self::CATEGORIES[$item->category] . ' berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $item = HcMasterData::findOrFail($id);
        $cat = $item->category;
        $item->delete();

        return redirect()->back()->with('success', 'Data ' . self::CATEGORIES[$cat] . ' berhasil dihapus!');
    }

    /**
     * Seeds initial standard data if category is empty
     */
    private function ensureDefaultSeeded(string $category): void
    {
        if (HcMasterData::where('category', $category)->exists()) {
            return;
        }

        $defaults = [
            'regional' => [
                ['code' => 'REG-JAWA', 'name' => 'Regional Jawa', 'description' => 'Wilayah Kerja Operasional Regional Jawa'],
                ['code' => 'REG-JAKARTA', 'name' => 'Regional Jakarta', 'description' => 'Wilayah Kerja Operasional Regional Jakarta'],
                ['code' => 'REG-SUMATRA', 'name' => 'Regional Sumatra', 'description' => 'Wilayah Kerja Operasional Regional Sumatra'],
                ['code' => 'REG-KALIMANTAN', 'name' => 'Regional Kalimantan', 'description' => 'Wilayah Kerja Operasional Regional Kalimantan'],
                ['code' => 'REG-BALINUSRA', 'name' => 'Regional Bali Nusra', 'description' => 'Wilayah Kerja Operasional Regional Bali Nusra'],
                ['code' => 'REG-SULAWESI', 'name' => 'Regional Sulawesi', 'description' => 'Wilayah Kerja Operasional Regional Sulawesi'],
            ],
            'segment' => [
                ['code' => 'SEG-01', 'name' => '01. Tenaga Alih Daya Operasional', 'description' => 'Segmentasi Layanan TAD Operasional Pelabuhan'],
                ['code' => 'SEG-02', 'name' => '02. Tenaga Alih Daya Pengamanan', 'description' => 'Segmentasi Layanan TAD Keamanan & Security'],
                ['code' => 'SEG-03', 'name' => '03. Pemborongan Pengamanan', 'description' => 'Segmentasi Layanan Pemborongan Sistem Pengamanan'],
                ['code' => 'SEG-04', 'name' => '04. Cleaning Service', 'description' => 'Segmentasi Layanan Kebersihan Fasilitas Pelabuhan'],
                ['code' => 'SEG-05', 'name' => '05. Pemeliharaan Taman', 'description' => 'Segmentasi Layanan Pertamanan & RTH Pelabuhan'],
                ['code' => 'SEG-06', 'name' => '06. Pelayanan Pas', 'description' => 'Segmentasi Layanan Pas Masuk & Gate Terminal'],
                ['code' => 'SEG-08', 'name' => '08. Tenaga Hantaran Kendaraan', 'description' => 'Segmentasi Layanan Pengemudi & Hantaran Armada'],
                ['code' => 'SEG-09', 'name' => '09. Tenaga Operator', 'description' => 'Segmentasi Operator Alat Berat Dermaga & Container'],
                ['code' => 'SEG-11', 'name' => '11. Lain Lain', 'description' => 'Segmentasi Layanan Pendukung Lainnya'],
                ['code' => 'SEG-14', 'name' => '14. Kebersihan', 'description' => 'Segmentasi Kebersihan & Hygiene Lingkungan Kerja'],
                ['code' => 'SEG-15', 'name' => '15. Operasional', 'description' => 'Segmentasi Layanan Teknis Operasional Lapangan'],
            ],
            'job_class' => [
                ['code' => 'JC-01', 'name' => 'Grade 1 - Executive / Direksi', 'description' => 'Kelas Jabatan Manajemen Eksekutif'],
                ['code' => 'JC-02', 'name' => 'Grade 2 - Senior Manager', 'description' => 'Kelas Jabatan Manajerial Senior'],
                ['code' => 'JC-03', 'name' => 'Grade 3 - Manager / Supervisor', 'description' => 'Kelas Jabatan Pengawas Operasional'],
                ['code' => 'JC-04', 'name' => 'Grade 4 - Officer / Specialist', 'description' => 'Kelas Jabatan Spesialis Fungsional'],
                ['code' => 'JC-05', 'name' => 'Grade 5 - Staff Operasional / TAD', 'description' => 'Kelas Jabatan Pelaksana Pelayanan Pelabuhan'],
            ],
            'job_field' => [
                ['code' => 'JF-OPS', 'name' => 'Operasional Pelabuhan & Terminal', 'description' => 'Bidang Pekerjaan Layanan Dermaga & Petikemas'],
                ['code' => 'JF-ENG', 'name' => 'Teknik & Pemeliharaan Peralatan', 'description' => 'Bidang Pekerjaan Maintenance Alat Berat Pelabuhan'],
                ['code' => 'JF-FIN', 'name' => 'Keuangan, Billing & Akuntansi', 'description' => 'Bidang Pekerjaan Financial Management & Invoicing'],
                ['code' => 'JF-HCM', 'name' => 'Human Capital & Pengelolaan TAD', 'description' => 'Bidang Pekerjaan Manajemen Sumber Daya Manusia'],
                ['code' => 'JF-[#100b60]', 'name' => 'Teknologi Informasi & Digitalisasi', 'description' => 'Bidang Pekerjaan IT Infrastructure & ERP Systems'],
            ],
            'employee_status' => [
                ['code' => 'ST-TAD', 'name' => 'Tenaga Alih Daya (TAD)', 'description' => 'Status Pegawai Mitra Vendor Alih Daya'],
                ['code' => 'ST-ORGANIK', 'name' => 'Karyawan Organik / Tetap', 'description' => 'Status Pegawai Tetap Pelindo Group'],
                ['code' => 'ST-PKWT', 'name' => 'Pegawai Kontrak (PKWT)', 'description' => 'Status Perjanjian Kerja Waktu Tertentu'],
            ],
            'religion' => [
                ['code' => 'REL-ISLAM', 'name' => 'Islam', 'description' => 'Agama Islam'],
                ['code' => 'REL-PROTESTANT', 'name' => 'Kristen Protestan', 'description' => 'Agama Kristen Protestan'],
                ['code' => 'REL-CATHOLIC', 'name' => 'Katolik', 'description' => 'Agama Katolik'],
                ['code' => 'REL-HINDU', 'name' => 'Hindu', 'description' => 'Agama Hindu'],
                ['code' => 'REL-BUDDHA', 'name' => 'Buddha', 'description' => 'Agama Buddha'],
                ['code' => 'REL-KHONGHUCU', 'name' => 'Khonghucu', 'description' => 'Agama Khonghucu'],
            ],
            'job_status' => [
                ['code' => 'JS-ACTIVE', 'name' => 'Aktif Berdinas', 'description' => 'Pegawai Aktif Melaksanakan Penugasan'],
                ['code' => 'JS-DELIMIT', 'name' => 'Delimit / Non-Aktif', 'description' => 'Masa Berlaku Penugasan Selesai'],
                ['code' => 'JS-MUTATION', 'name' => 'Dalam Proses Mutasi (ECN)', 'description' => 'Pegawai Sedang Mengikuti Proses Pergerakan'],
            ],
        ];

        if (isset($defaults[$category])) {
            foreach ($defaults[$category] as $item) {
                HcMasterData::create([
                    'category' => $category,
                    'code' => $item['code'],
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'active' => true,
                ]);
            }
        }
    }
}
