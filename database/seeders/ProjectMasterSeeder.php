<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProjectMasterData;
use Illuminate\Support\Facades\DB;

class ProjectMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        ProjectMasterData::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Feasibility Metrics
        $feasibilityMetrics = [
            ['code' => 'IRR', 'name' => 'Internal Rate of Return', 'uom' => '%', 'description' => 'Tingkat pengembalian internal proyek'],
            ['code' => 'NPV', 'name' => 'Net Present Value', 'uom' => 'IDR', 'description' => 'Nilai bersih saat ini dari arus kas proyek'],
            ['code' => 'PBP', 'name' => 'Payback Period', 'uom' => 'Tahun', 'description' => 'Jangka waktu pengembalian investasi modal'],
            ['code' => 'BCR', 'name' => 'Benefit Cost Ratio', 'uom' => 'Ratio', 'description' => 'Perbandingan antara manfaat dan biaya proyek'],
            ['code' => 'PI', 'name' => 'Profitability Index', 'uom' => 'Index', 'description' => 'Indeks profitabilitas proyek'],
        ];
        foreach ($feasibilityMetrics as $item) {
            ProjectMasterData::create(array_merge($item, ['category' => 'feasibility_metrics']));
        }

        // 2. Project Category (From real screenshot)
        $projectCategories = [
            ['code' => '01', 'name' => '01. Tenaga Alih Daya Operasional', 'description' => '01. Tenaga Alih Daya Operasional'],
            ['code' => '02', 'name' => '02. Tenaga Alih Daya Pengamanan', 'description' => '02. Tenaga Alih Daya Pengamanan'],
            ['code' => '03', 'name' => '03. Pemborongan Pengamanan', 'description' => '03. Pemborongan Pengamanan'],
            ['code' => '04', 'name' => '04. Cleaning Service', 'description' => '04. Cleaning Service'],
            ['code' => '05', 'name' => '05. Pemeliharaan Taman', 'description' => '05. Pemeliharaan Taman'],
            ['code' => '06', 'name' => '06. Pelayanan Pas', 'description' => '06. Pelayanan Pas'],
            ['code' => '07', 'name' => '07. Pengelolaan Parkir', 'description' => '07. Pengelolaan Parkir'],
            ['code' => '08', 'name' => '08. Tenaga Hantaran Kendaraan', 'description' => '08. Tenaga Hantaran Kendaraan'],
            ['code' => '09', 'name' => '09. Tenaga Operator', 'description' => '09. Tenaga Operator'],
            ['code' => '10', 'name' => '10. Kepil', 'description' => '10. Kepil'],
        ];
        foreach ($projectCategories as $item) {
            ProjectMasterData::create(array_merge($item, ['category' => 'project_category']));
        }

        // 3. Project Type (From real screenshot)
        $projectTypes = [
            ['code' => '01', 'name' => 'Revenue', 'description' => 'Revenue', 'validity_start' => '01-Jan-2024', 'validity_end' => '31-Dec-9999'],
            ['code' => '02', 'name' => 'Expense', 'description' => 'Expense', 'validity_start' => '01-Jan-2024', 'validity_end' => '31-Dec-9999'],
        ];
        foreach ($projectTypes as $item) {
            ProjectMasterData::create(array_merge($item, ['category' => 'project_type']));
        }

        // 4. Object Type (From real screenshot)
        $objectTypes = [
            ['code' => '01', 'name' => 'Dermaga & Fasilitas Pelabuhan', 'scope' => 'Pelabuhan Regional', 'project_type' => 'Revenue', 'description' => 'Fasilitas tambat dan bongkar muat kapal'],
            ['code' => '02', 'name' => 'Gedung & Fasilitas Bangunan', 'scope' => 'Kantor & Gudang', 'project_type' => 'Expense', 'description' => 'Pemeliharaan dan konstruksi gedung'],
            ['code' => '03', 'name' => 'IT Infrastructure & Telecommunication', 'scope' => 'Sistem IT Port', 'project_type' => 'Expense', 'description' => 'Peralatan server, jaringan fiber optik port'],
            ['code' => '04', 'name' => 'Alat Berat & Crane Pelabuhan', 'scope' => 'Equipment Port', 'project_type' => 'Expense', 'description' => 'RTG, CC, Reach Stacker, Forklift'],
            ['code' => '05', 'name' => 'Jasa Alih Daya (TAD) & Security', 'scope' => 'Human Capital', 'project_type' => 'Revenue', 'description' => 'Penyediaan tenaga alih daya dan pengamanan'],
        ];
        foreach ($objectTypes as $item) {
            ProjectMasterData::create(array_merge($item, ['category' => 'object_type']));
        }

        // 5. Status (From real screenshot)
        $statuses = [
            ['seq' => 1, 'code' => '01', 'name' => 'Draft', 'description' => 'Draft', 'validity_start' => '2021-01-01 00:00:00', 'validity_end' => '9999-12-31 00:00:00'],
            ['seq' => 2, 'code' => '02', 'name' => 'In Progress', 'description' => 'In Progress', 'validity_start' => '2021-01-01 00:00:00', 'validity_end' => '9999-12-31 00:00:00'],
            ['seq' => 3, 'code' => '03', 'name' => 'Completed', 'description' => 'Completed', 'validity_start' => '2021-01-01 00:00:00', 'validity_end' => '9999-12-31 00:00:00'],
        ];
        foreach ($statuses as $item) {
            ProjectMasterData::create(array_merge($item, ['category' => 'status']));
        }

        // 6. Master Code (From real screenshot)
        $masterCodes = [
            ['code' => 'CAPEX-PORT', 'name' => 'Capital Expenditure Port', 'description' => 'Belanja modal pengembangan pelabuhan'],
            ['code' => 'OPEX-TAD', 'name' => 'Operational Expenditure TAD', 'description' => 'Beban operasional tenaga alih daya'],
            ['code' => 'MAINT-EQP', 'name' => 'Maintenance Equipment', 'description' => 'Perawatan rutin alat bongkar muat'],
        ];
        foreach ($masterCodes as $item) {
            ProjectMasterData::create(array_merge($item, ['category' => 'master_code']));
        }

        // 7. Project Role (From real screenshot)
        $projectRoles = [
            ['code' => 'PM', 'name' => 'Project Manager', 'description' => 'Penanggung jawab utama eksekusi proyek'],
            ['code' => 'SE', 'name' => 'Site Engineer', 'description' => 'Pengawas teknis dan operasional lapangan'],
            ['code' => 'QHSSE', 'name' => 'QHSSE Officer', 'description' => 'Pengawas keselamatan, kesehatan kerja, dan lingkungan'],
            ['code' => 'ADM', 'name' => 'Project Administrator', 'description' => 'Pengelola administrasi, penagihan, dan dokumentasi proyek'],
            ['code' => 'QS', 'name' => 'Quantity Surveyor', 'description' => 'Estimator biaya, volume fisik, dan verifikasi RAB'],
        ];
        foreach ($projectRoles as $item) {
            ProjectMasterData::create(array_merge($item, ['category' => 'project_role']));
        }

        // 8. WBS Payroll Category (From real screenshot)
        $wbsPayrollCategories = [
            ['code' => '13', 'name' => 'BPJS', 'coa' => '-', 'description' => 'Alokasi BPJS Induk'],
            ['code' => '14', 'name' => 'BPJS Kesehatan', 'coa' => '5050902000 - BPJS Kesehatan', 'description' => 'Beban Iuran BPJS Kesehatan Pegawai TAD'],
            ['code' => '15', 'name' => 'BPJS Ketenagakerjaan', 'coa' => '5050903000 - BPJS Ketenagakerjaan', 'description' => 'Beban Iuran BPJS Ketenagakerjaan JHT, JKK, JKM, JP'],
            ['code' => '26', 'name' => 'Bantuan Cuti', 'coa' => '5061004100 - Bantuan Cuti', 'description' => 'Beban Tunjangan / Bantuan Cuti Tahunan'],
            ['code' => '55', 'name' => 'Bantuan Pendidikan', 'coa' => '5010310000 - Beban Tunjangan Pendidikan', 'description' => 'Beban Tunjangan Pendidikan Anak Pegawai'],
            ['code' => '67', 'name' => 'Bantuan Penugasan Khusus', 'coa' => '-', 'description' => 'Tunjangan Penugasan Khusus Lapangan'],
            ['code' => '25', 'name' => 'Bantuan Pph21', 'coa' => '5010301000 - Beban Tunjangan Pph. 21', 'description' => 'Tunjangan Pajak Penghasilan PPh Pasal 21'],
            ['code' => '43', 'name' => 'Biaya Bahan Bakar', 'coa' => '-', 'description' => 'Beban BBM Operasional Kendaraan & Genset Proyek'],
            ['code' => '34', 'name' => 'Biaya Diklat', 'coa' => '-', 'description' => 'Beban Pendidikan & Pelatihan Sertifikasi Port'],
        ];
        foreach ($wbsPayrollCategories as $item) {
            ProjectMasterData::create(array_merge($item, ['category' => 'wbs_payroll_category']));
        }
    }
}
