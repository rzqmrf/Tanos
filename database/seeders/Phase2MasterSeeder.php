<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use App\Models\FaCompanyBankAccount;
use App\Models\FaCostCenter;
use App\Models\FaCurrency;
use App\Models\FaCurrencyRate;
use App\Models\FaFiscalPeriod;
use App\Models\FaFundCenter;
use App\Models\FaProfitCenter;
use App\Models\MaterialEquipment;
use App\Models\MaterialOutlineAgreement;
use App\Models\Partner;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class Phase2MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Profit Centers
        $pcs = [
            [
                'code' => 'PC-OPS-01',
                'name' => 'Profit Center Operasional Proyek',
                'segment' => '01. Tenaga Alih Daya Operasional',
                'person_in_charge' => 'Ir. Hendra Gunawan',
                'description' => 'Pusat laba unit operasi dan layanan pengelolaan tenaga alih daya proyek',
                'active' => true,
            ],
            [
                'code' => 'PC-CONS-01',
                'name' => 'Profit Center Konstruksi & Fabrikasi',
                'segment' => '02. Pemborongan & Konstruksi',
                'person_in_charge' => 'Bambang Sutejo, S.T.',
                'description' => 'Pusat pendapatan proyek konstruksi fisik, sipil, dan fabrikasi',
                'active' => true,
            ],
            [
                'code' => 'PC-TECH-01',
                'name' => 'Profit Center Teknologi & Digital',
                'segment' => '03. IT & Managed Services',
                'person_in_charge' => 'Farhan Ramadhan, M.Kom',
                'description' => 'Pusat laba divisi pengembangan software, IoT, dan infrastruktur IT',
                'active' => true,
            ],
            [
                'code' => 'PC-CORP-01',
                'name' => 'Profit Center Kantor Pusat & Holding',
                'segment' => 'Corporate Management',
                'person_in_charge' => 'Dewi Sartika, S.E., M.Ak',
                'description' => 'Pusat administrasi umum, investasi, dan pendapatan korporasi',
                'active' => true,
            ],
        ];

        $createdPcs = [];
        foreach ($pcs as $pcData) {
            $createdPcs[$pcData['code']] = FaProfitCenter::firstOrCreate(['code' => $pcData['code']], $pcData);
        }

        // 2. Seed Cost Centers
        $ccs = [
            [
                'profit_center_id' => $createdPcs['PC-CORP-01']->id ?? null,
                'code' => 'CC-ADM-01',
                'name' => 'Departemen HR & General Affairs',
                'department' => 'Human Capital & GA',
                'person_in_charge' => 'Anisa Rahmawati',
                'description' => 'Pusat biaya pengelolaan SDM, rekrutmen, dan fasilitas kantor pusat',
                'active' => true,
            ],
            [
                'profit_center_id' => $createdPcs['PC-CORP-01']->id ?? null,
                'code' => 'CC-FIN-01',
                'name' => 'Departemen Keuangan & Perpajakan',
                'department' => 'Finance & Tax',
                'person_in_charge' => 'Rahmat Hidayat, C.A.',
                'description' => 'Pusat biaya operasional pembukuan, treasury, dan kepatuhan pajak',
                'active' => true,
            ],
            [
                'profit_center_id' => $createdPcs['PC-OPS-01']->id ?? null,
                'code' => 'CC-OPS-FLD',
                'name' => 'Divisi Lapangan & Logistik Proyek',
                'department' => 'Field Operations',
                'person_in_charge' => 'Suryo Nugroho',
                'description' => 'Pusat biaya akomodasi, mobilisasi, dan konsumsi tim lapangan proyek',
                'active' => true,
            ],
            [
                'profit_center_id' => $createdPcs['PC-CONS-01']->id ?? null,
                'code' => 'CC-EQP-MAINT',
                'name' => 'Workshop & Pemeliharaan Alat Berat',
                'department' => 'Equipment & Maintenance',
                'person_in_charge' => 'Dedi Iskandar',
                'description' => 'Pusat biaya servis berkala, bahan bakar solar, dan suku cadang alat proyek',
                'active' => true,
            ],
            [
                'profit_center_id' => $createdPcs['PC-TECH-01']->id ?? null,
                'code' => 'CC-IT-INFRA',
                'name' => 'Infrastruktur Cloud & Server',
                'department' => 'Information Technology',
                'person_in_charge' => 'Wahyu Hidayat',
                'description' => 'Pusat biaya sewa cloud AWS/GCP, lisensi software, dan konektivitas VPN',
                'active' => true,
            ],
        ];

        foreach ($ccs as $ccData) {
            FaCostCenter::firstOrCreate(['code' => $ccData['code']], $ccData);
        }

        // 3. Seed Fund Centers
        $fcs = [
            [
                'code' => 'FC-CAPEX-2026',
                'name' => 'Dana Belanja Modal (CAPEX 2026)',
                'budget_limit' => 5000000000.00,
                'currency' => 'IDR',
                'description' => 'Pagu anggaran belanja aset tetap, mesin fabrikasi, dan ekspansi fasilitas',
                'active' => true,
            ],
            [
                'code' => 'FC-OPEX-2026',
                'name' => 'Dana Operasional Rutin (OPEX 2026)',
                'budget_limit' => 12000000000.00,
                'currency' => 'IDR',
                'description' => 'Pagu anggaran belanja rutin gaji, operasional harian, dan utilitas kantor',
                'active' => true,
            ],
            [
                'code' => 'FC-PROJ-2026',
                'name' => 'Dana Pengadaan & Material Proyek 2026',
                'budget_limit' => 25000000000.00,
                'currency' => 'IDR',
                'description' => 'Pagu likuiditas pembelian bahan baku, sewa alat, dan pembayaran subkontraktor',
                'active' => true,
            ],
        ];

        foreach ($fcs as $fcData) {
            FaFundCenter::firstOrCreate(['code' => $fcData['code']], $fcData);
        }

        // 4. Seed Currencies & Exchange Rates
        $currencies = [
            ['code' => 'IDR', 'name' => 'Rupiah Indonesia', 'symbol' => 'Rp', 'is_default' => true, 'active' => true],
            ['code' => 'USD', 'name' => 'US Dollar', 'symbol' => '$', 'is_default' => false, 'active' => true],
            ['code' => 'EUR', 'name' => 'Euro', 'symbol' => '€', 'is_default' => false, 'active' => true],
            ['code' => 'SGD', 'name' => 'Singapore Dollar', 'symbol' => 'S$', 'is_default' => false, 'active' => true],
            ['code' => 'JPY', 'name' => 'Japanese Yen', 'symbol' => '¥', 'is_default' => false, 'active' => true],
        ];

        $createdCurrencies = [];
        foreach ($currencies as $currData) {
            $createdCurrencies[$currData['code']] = FaCurrency::firstOrCreate(['code' => $currData['code']], $currData);
        }

        // Seed Rates for Foreign Currencies
        $rates = [
            ['code' => 'USD', 'rate' => 16350.00, 'source' => 'Kurs Tengah Bank Indonesia'],
            ['code' => 'EUR', 'rate' => 17820.50, 'source' => 'Kurs Tengah Bank Indonesia'],
            ['code' => 'SGD', 'rate' => 12410.00, 'source' => 'Kurs Tengah Bank Indonesia'],
            ['code' => 'JPY', 'rate' => 108.45, 'source' => 'Kurs Tengah Bank Indonesia'],
        ];

        foreach ($rates as $r) {
            if (isset($createdCurrencies[$r['code']])) {
                FaCurrencyRate::firstOrCreate(
                    [
                        'currency_id' => $createdCurrencies[$r['code']]->id,
                        'effective_date' => Carbon::today()->format('Y-m-d'),
                    ],
                    [
                        'rate_to_idr' => $r['rate'],
                        'source' => $r['source'],
                        'notes' => 'Kurs acuan transaksi awal bulan',
                    ]
                );
            }
        }

        // 5. Seed Company Bank Accounts
        $coaKasBank = ChartOfAccount::where('code', 'like', '11%')->first();
        $bankAccounts = [
            [
                'bank_name' => 'Bank Mandiri (Persero) Tbk',
                'account_number' => '142-00-9821882-1',
                'account_holder' => 'PT TANOS TEKNOLOGI INTEGRASI (OPERASIONAL)',
                'branch' => 'KCP Surabaya Basuki Rahmat',
                'currency' => 'IDR',
                'chart_of_account_id' => $coaKasBank?->id,
                'is_primary' => true,
                'active' => true,
            ],
            [
                'bank_name' => 'Bank Rakyat Indonesia (BRI)',
                'account_number' => '0096-01-002891-30-8',
                'account_holder' => 'PT TANOS TEKNOLOGI INTEGRASI (PAYROLL)',
                'branch' => 'KC Jakarta Sudirman',
                'currency' => 'IDR',
                'chart_of_account_id' => $coaKasBank?->id,
                'is_primary' => false,
                'active' => true,
            ],
            [
                'bank_name' => 'Bank Central Asia (BCA)',
                'account_number' => '088-721-9901',
                'account_holder' => 'PT TANOS TEKNOLOGI INTEGRASI (PENERIMAAN BILLING)',
                'branch' => 'KCU Surabaya Darmo',
                'currency' => 'IDR',
                'chart_of_account_id' => $coaKasBank?->id,
                'is_primary' => false,
                'active' => true,
            ],
        ];

        foreach ($bankAccounts as $baData) {
            FaCompanyBankAccount::firstOrCreate(['account_number' => $baData['account_number']], $baData);
        }

        // 6. Seed Fiscal Periods for 2026
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        foreach ($months as $mNum => $mName) {
            $startDate = Carbon::create(2026, $mNum, 1)->startOfMonth();
            $endDate = Carbon::create(2026, $mNum, 1)->endOfMonth();
            $status = $mNum < 8 ? 'Closed' : 'Open';

            FaFiscalPeriod::firstOrCreate(
                ['year' => 2026, 'month' => $mNum],
                [
                    'period_name' => "{$mName} 2026",
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'status' => $status,
                    'closed_at' => $status === 'Closed' ? $endDate->setTime(23, 59, 59) : null,
                    'closed_by' => $status === 'Closed' ? 1 : null,
                ]
            );
        }

        // 7. Seed Materials Equipment
        $firstProject = Project::first();
        $equipments = [
            [
                'equipment_code' => 'EQ-EXC-001',
                'name' => 'Hydraulic Excavator CAT 320D',
                'category' => 'Heavy Equipment',
                'brand_model' => 'Caterpillar 320D GC',
                'serial_number' => 'CAT0320DGK9921',
                'project_id' => $firstProject?->id,
                'condition' => 'Operational',
                'purchase_date' => '2024-03-15',
                'purchase_cost' => 1850000000.00,
                'last_service_date' => '2026-07-10',
                'next_service_date' => '2026-10-10',
                'certification_expiry' => '2027-03-15',
                'notes' => 'Unit prima, kelengkapan bucket standard 0.9m3 & breaker',
                'active' => true,
            ],
            [
                'equipment_code' => 'EQ-TRK-002',
                'name' => 'Dump Truck Hino 500 FM 260 TI',
                'category' => 'Vehicle',
                'brand_model' => 'Hino FM 260 TI 6x4',
                'serial_number' => 'HN500FM2609012',
                'project_id' => $firstProject?->id,
                'condition' => 'Operational',
                'purchase_date' => '2024-06-20',
                'purchase_cost' => 920000000.00,
                'last_service_date' => '2026-08-01',
                'next_service_date' => '2026-11-01',
                'certification_expiry' => '2026-12-31',
                'notes' => 'Pajak & uji KIR aktif, kapasitas bak 24 ton',
                'active' => true,
            ],
            [
                'equipment_code' => 'EQ-GEN-003',
                'name' => 'Silent Diesel Genset 150 kVA',
                'category' => 'Power Equipment',
                'brand_model' => 'Perkins 1106A-70TAG2',
                'serial_number' => 'PRK150KVA-8812',
                'project_id' => $firstProject?->id,
                'condition' => 'Operational',
                'purchase_date' => '2025-01-10',
                'purchase_cost' => 280000000.00,
                'last_service_date' => '2026-08-15',
                'next_service_date' => '2026-11-15',
                'certification_expiry' => '2027-01-10',
                'notes' => 'Dilengkapi Automatic Transfer Switch (ATS) & soundproof canopy',
                'active' => true,
            ],
            [
                'equipment_code' => 'EQ-SURV-004',
                'name' => 'Total Station Robotic Leica TS07',
                'category' => 'Survey Instrument',
                'brand_model' => 'Leica FlexLine TS07 2"',
                'serial_number' => 'LCA-TS07-77218',
                'project_id' => $firstProject?->id,
                'condition' => 'Standby',
                'purchase_date' => '2025-04-18',
                'purchase_cost' => 145000000.00,
                'last_service_date' => '2026-06-12',
                'next_service_date' => '2026-12-12',
                'certification_expiry' => '2027-04-18',
                'notes' => 'Terkalibrasi laboratorium terakreditasi KAN',
                'active' => true,
            ],
            [
                'equipment_code' => 'EQ-WLD-005',
                'name' => 'Mesin Las Inverter 400A Industri',
                'category' => 'Workshop Equipment',
                'brand_model' => 'Miller Gold Star 402',
                'serial_number' => 'MLR-402-1982',
                'project_id' => $firstProject?->id,
                'condition' => 'Maintenance',
                'purchase_date' => '2024-09-05',
                'purchase_cost' => 65000000.00,
                'last_service_date' => '2026-08-20',
                'next_service_date' => '2026-09-10',
                'certification_expiry' => '2026-09-30',
                'notes' => 'Sedang penggantian modul IGBT & pendingin kipas di workshop',
                'active' => true,
            ],
        ];

        foreach ($equipments as $eqData) {
            MaterialEquipment::firstOrCreate(['equipment_code' => $eqData['equipment_code']], $eqData);
        }

        // 8. Seed Materials Outline Agreements
        $vendorPartner = Partner::first();
        $agreements = [
            [
                'agreement_number' => 'OA-2026-001',
                'partner_id' => $vendorPartner?->id,
                'title' => 'Kontrak Payung Pasokan Suku Cadang & Pelumas Alat Berat',
                'agreement_type' => 'Value Contract',
                'target_value' => 1500000000.00,
                'currency' => 'IDR',
                'start_date' => '2026-01-01',
                'end_date' => '2026-12-31',
                'status' => 'Active',
                'terms' => 'Pemesanan via PO bertahap, diskon volume 12%, TOP 30 hari kalender setelah invoice diterima.',
                'notes' => 'Kontrak pasokan suku cadang resmi genuine OEM Caterpillar dan Hino',
            ],
            [
                'agreement_number' => 'OA-2026-002',
                'partner_id' => $vendorPartner?->id,
                'title' => 'Perjanjian Kerjasama Sewa Armada Transportasi & Crane',
                'agreement_type' => 'Quantity Contract',
                'target_value' => 3200000000.00,
                'currency' => 'IDR',
                'start_date' => '2026-02-01',
                'end_date' => '2027-01-31',
                'status' => 'Active',
                'terms' => 'Sewa crane 25T-50T dan armada tronton flatbed per shift / bulanan include operator tersertifikasi.',
                'notes' => 'Termasuk klausa asuransi all-risk peralatan dan pihak ketiga',
            ],
            [
                'agreement_number' => 'OA-2026-003',
                'partner_id' => $vendorPartner?->id,
                'title' => 'Pengadaan Perlengkapan K3 & Seragam Proyek Terpadu',
                'agreement_type' => 'Value Contract',
                'target_value' => 450000000.00,
                'currency' => 'IDR',
                'start_date' => '2026-03-01',
                'end_date' => '2026-12-31',
                'status' => 'Active',
                'terms' => 'Standard safety helmet SNI, rompi reflektif, safety boots, dan sarung tangan tahan potong.',
                'notes' => 'Pengiriman batch langsung ke site regional masing-masing',
            ],
        ];

        foreach ($agreements as $oaData) {
            MaterialOutlineAgreement::firstOrCreate(['agreement_number' => $oaData['agreement_number']], $oaData);
        }
    }
}
