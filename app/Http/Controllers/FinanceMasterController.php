<?php

namespace App\Http\Controllers;

use App\Models\FaAccountGroup;
use App\Models\ChartOfAccount;
use App\Models\TaxMaster;
use Illuminate\Http\Request;

class FinanceMasterController extends Controller
{
    /**
     * Display Account Groups Master
     */
    public function accountGroupIndex(Request $request)
    {
        $this->ensureFaAccountGroupsSeeded();

        $query = FaAccountGroup::withCount('accounts');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $accountGroups = $query->orderBy('code')->paginate(10)->withQueryString();
        $totalAll = FaAccountGroup::count();
        $totalBalanceSheet = FaAccountGroup::where('category', 'balance_sheet')->count();
        $totalIncomeStatement = FaAccountGroup::where('category', 'income_statement')->count();

        return view('finance.fa.account-group', compact('accountGroups', 'totalAll', 'totalBalanceSheet', 'totalIncomeStatement'));
    }

    public function accountGroupStore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:fa_account_groups,code',
            'name' => 'required|string|max:255',
            'category' => 'required|in:balance_sheet,income_statement',
            'description' => 'nullable|string',
            'active' => 'nullable',
        ]);

        FaAccountGroup::create([
            'code' => strtoupper(trim($request->code)),
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'active' => $request->has('active') ? (bool) $request->active : true,
        ]);

        return redirect()->back()->with('success', 'Account Group baru berhasil ditambahkan!');
    }

    public function accountGroupUpdate(Request $request, $id)
    {
        $group = FaAccountGroup::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:fa_account_groups,code,' . $id,
            'name' => 'required|string|max:255',
            'category' => 'required|in:balance_sheet,income_statement',
            'description' => 'nullable|string',
            'active' => 'nullable',
        ]);

        $group->update([
            'code' => strtoupper(trim($request->code)),
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'active' => $request->has('active') ? (bool) $request->active : false,
        ]);

        return redirect()->back()->with('success', 'Data Account Group berhasil diperbarui!');
    }

    public function accountGroupDestroy($id)
    {
        $group = FaAccountGroup::findOrFail($id);

        if ($group->accounts()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus Account Group karena masih memiliki akun CoA terkait.');
        }

        $group->delete();
        return redirect()->back()->with('success', 'Account Group berhasil dihapus!');
    }

    /**
     * Display Chart of Accounts (CoA) Master
     */
    public function coaIndex(Request $request)
    {
        $this->ensureFaAccountGroupsSeeded();
        $this->ensureCoaSeeded();

        $query = ChartOfAccount::with(['accountGroup', 'parent']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('account_group_id')) {
            $query->where('account_group_id', $request->account_group_id);
        }

        if ($request->filled('normal_balance')) {
            $query->where('normal_balance', $request->normal_balance);
        }

        $accounts = $query->orderBy('code')->paginate(15)->withQueryString();
        $accountGroups = FaAccountGroup::where('active', true)->orderBy('code')->get();
        $headerAccounts = ChartOfAccount::where('is_header', true)->orderBy('code')->get();

        $totalAll = ChartOfAccount::count();
        $totalHeaders = ChartOfAccount::where('is_header', true)->count();
        $totalPosting = ChartOfAccount::where('is_header', false)->count();

        return view('finance.fa.coa', compact('accounts', 'accountGroups', 'headerAccounts', 'totalAll', 'totalHeaders', 'totalPosting'));
    }

    public function coaStore(Request $request)
    {
        $request->validate([
            'account_group_id' => 'required|exists:fa_account_groups,id',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'code' => 'required|string|max:50|unique:chart_of_accounts,code',
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:1|max:5',
            'normal_balance' => 'required|in:debit,credit',
            'is_header' => 'nullable',
            'description' => 'nullable|string',
            'active' => 'nullable',
        ]);

        ChartOfAccount::create([
            'account_group_id' => $request->account_group_id,
            'parent_id' => $request->parent_id,
            'code' => strtoupper(trim($request->code)),
            'name' => $request->name,
            'level' => $request->level,
            'normal_balance' => $request->normal_balance,
            'is_header' => $request->has('is_header') ? (bool) $request->is_header : false,
            'description' => $request->description,
            'active' => $request->has('active') ? (bool) $request->active : true,
        ]);

        return redirect()->back()->with('success', 'Akun CoA baru berhasil ditambahkan!');
    }

    public function coaUpdate(Request $request, $id)
    {
        $account = ChartOfAccount::findOrFail($id);

        $request->validate([
            'account_group_id' => 'required|exists:fa_account_groups,id',
            'parent_id' => 'nullable|exists:chart_of_accounts,id|different:id',
            'code' => 'required|string|max:50|unique:chart_of_accounts,code,' . $id,
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:1|max:5',
            'normal_balance' => 'required|in:debit,credit',
            'is_header' => 'nullable',
            'description' => 'nullable|string',
            'active' => 'nullable',
        ]);

        $account->update([
            'account_group_id' => $request->account_group_id,
            'parent_id' => $request->parent_id,
            'code' => strtoupper(trim($request->code)),
            'name' => $request->name,
            'level' => $request->level,
            'normal_balance' => $request->normal_balance,
            'is_header' => $request->has('is_header') ? (bool) $request->is_header : false,
            'description' => $request->description,
            'active' => $request->has('active') ? (bool) $request->active : false,
        ]);

        return redirect()->back()->with('success', 'Data Akun CoA berhasil diperbarui!');
    }

    public function coaDestroy($id)
    {
        $account = ChartOfAccount::findOrFail($id);

        if ($account->children()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus Akun CoA karena memiliki sub-akun bawahan.');
        }

        $account->delete();
        return redirect()->back()->with('success', 'Akun CoA berhasil dihapus!');
    }

    /**
     * Display Tax Master
     */
    public function taxIndex(Request $request)
    {
        $this->ensureTaxesSeeded();

        $query = TaxMaster::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tax_type')) {
            $query->where('tax_type', $request->tax_type);
        }

        $taxes = $query->orderBy('code')->paginate(10)->withQueryString();
        $totalAll = TaxMaster::count();
        $totalActive = TaxMaster::where('active', true)->count();

        return view('finance.fa.tax', compact('taxes', 'totalAll', 'totalActive'));
    }

    public function taxStore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:tax_masters,code',
            'name' => 'required|string|max:255',
            'rate_percent' => 'required|numeric|min:0|max:100',
            'tax_type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'active' => 'nullable',
        ]);

        TaxMaster::create([
            'code' => strtoupper(trim($request->code)),
            'name' => $request->name,
            'rate_percent' => $request->rate_percent,
            'tax_type' => $request->tax_type,
            'description' => $request->description,
            'active' => $request->has('active') ? (bool) $request->active : true,
        ]);

        return redirect()->back()->with('success', 'Master Tarif Pajak baru berhasil ditambahkan!');
    }

    public function taxUpdate(Request $request, $id)
    {
        $tax = TaxMaster::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:tax_masters,code,' . $id,
            'name' => 'required|string|max:255',
            'rate_percent' => 'required|numeric|min:0|max:100',
            'tax_type' => 'required|string|max:50',
            'description' => 'nullable|string',
            'active' => 'nullable',
        ]);

        $tax->update([
            'code' => strtoupper(trim($request->code)),
            'name' => $request->name,
            'rate_percent' => $request->rate_percent,
            'tax_type' => $request->tax_type,
            'description' => $request->description,
            'active' => $request->has('active') ? (bool) $request->active : false,
        ]);

        return redirect()->back()->with('success', 'Data Tarif Pajak berhasil diperbarui!');
    }

    public function taxDestroy($id)
    {
        $tax = TaxMaster::findOrFail($id);
        $tax->delete();

        return redirect()->back()->with('success', 'Tarif Pajak berhasil dihapus!');
    }

    /**
     * Auto Seed Account Groups
     */
    private function ensureFaAccountGroupsSeeded(): void
    {
        if (FaAccountGroup::exists()) {
            return;
        }

        $groups = [
            ['code' => '100', 'name' => '100 - Aset Lancar (Current Assets)', 'category' => 'balance_sheet', 'description' => 'Kas, Bank, Piutang Usaha, Persediaan & Uang Muka'],
            ['code' => '150', 'name' => '150 - Aset Tetap & Tidak Lancar', 'category' => 'balance_sheet', 'description' => 'Peralatan, Mesin Pelabuhan, Gedung, Akumulasi Penyusutan'],
            ['code' => '200', 'name' => '200 - Liabilitas Jangka Pendek (Current Liabilities)', 'category' => 'balance_sheet', 'description' => 'Hutang Usaha Vendor, Hutang Gaji/TAD, Hutang Pajak PPN/PPh'],
            ['code' => '250', 'name' => '250 - Liabilitas Jangka Panjang', 'category' => 'balance_sheet', 'description' => 'Hutang Bank Jangka Panjang & Imbalan Pasca Kerja'],
            ['code' => '300', 'name' => '300 - Ekuitas & Modal Pemegang Saham', 'category' => 'balance_sheet', 'description' => 'Modal Disetor, Saldo Laba Ditahan, Cadangan Umum'],
            ['code' => '400', 'name' => '400 - Pendapatan Usaha (Revenue)', 'category' => 'income_statement', 'description' => 'Pendapatan Jasa Alih Daya Pelabuhan, Billing Proyek'],
            ['code' => '500', 'name' => '500 - Beban Pokok Pendapatan (Cost of Goods Sold / PS Cost)', 'category' => 'income_statement', 'description' => 'Biaya Langsung Tenaga Kerja TAD, Biaya Perlengkapan Operasional'],
            ['code' => '600', 'name' => '600 - Beban Umum, Administrasi & Operasional', 'category' => 'income_statement', 'description' => 'Gaji Karyawan Organik, Biaya IT, Sewa Kantor, Utilitas'],
            ['code' => '700', 'name' => '700 - Pendapatan & Beban Lain-lain', 'category' => 'income_statement', 'description' => 'Bunga Bank, Selisih Kurs Valas, Pajak Final'],
        ];

        foreach ($groups as $group) {
            FaAccountGroup::create($group + ['active' => true]);
        }
    }

    /**
     * Auto Seed Standard Indonesian BUMN/Pelindo Chart of Accounts
     */
    private function ensureCoaSeeded(): void
    {
        $this->ensureFaAccountGroupsSeeded();

        if (ChartOfAccount::exists()) {
            return;
        }

        $grp100 = FaAccountGroup::where('code', '100')->first();
        $grp200 = FaAccountGroup::where('code', '200')->first();
        $grp300 = FaAccountGroup::where('code', '300')->first();
        $grp400 = FaAccountGroup::where('code', '400')->first();
        $grp500 = FaAccountGroup::where('code', '500')->first();
        $grp600 = FaAccountGroup::where('code', '600')->first();

        // 1. Kas & Bank (Header)
        $hKas = ChartOfAccount::create([
            'account_group_id' => $grp100?->id,
            'code' => '11000',
            'name' => 'Kas dan Setara Kas',
            'level' => 1,
            'normal_balance' => 'debit',
            'is_header' => true,
            'description' => 'Header Akun Kas & Bank Operasional',
            'active' => true,
        ]);

        ChartOfAccount::create([
            'account_group_id' => $grp100?->id,
            'parent_id' => $hKas->id,
            'code' => '11101',
            'name' => 'Kas Kecil (Petty Cash)',
            'level' => 2,
            'normal_balance' => 'debit',
            'is_header' => false,
            'description' => 'Kas kecil operasional kantor pusat & cabang',
            'active' => true,
        ]);

        ChartOfAccount::create([
            'account_group_id' => $grp100?->id,
            'parent_id' => $hKas->id,
            'code' => '11201',
            'name' => 'Bank Mandiri Giro Operasional (IDR)',
            'level' => 2,
            'normal_balance' => 'debit',
            'is_header' => false,
            'description' => 'Rekening giro utama penerimaan & pengeluaran',
            'active' => true,
        ]);

        ChartOfAccount::create([
            'account_group_id' => $grp100?->id,
            'parent_id' => $hKas->id,
            'code' => '11202',
            'name' => 'Bank BNI Giro Payroll (IDR)',
            'level' => 2,
            'normal_balance' => 'debit',
            'is_header' => false,
            'description' => 'Rekening penampungan payroll tenaga alih daya',
            'active' => true,
        ]);

        // 2. Piutang Usaha
        $hPiutang = ChartOfAccount::create([
            'account_group_id' => $grp100?->id,
            'code' => '12000',
            'name' => 'Piutang Usaha (Account Receivables)',
            'level' => 1,
            'normal_balance' => 'debit',
            'is_header' => true,
            'description' => 'Header Piutang Billing Proyek & Layanan',
            'active' => true,
        ]);

        ChartOfAccount::create([
            'account_group_id' => $grp100?->id,
            'parent_id' => $hPiutang->id,
            'code' => '12101',
            'name' => 'Piutang Usaha Pelanggan Pelindo Group',
            'level' => 2,
            'normal_balance' => 'debit',
            'is_header' => false,
            'description' => 'Piutang tagihan invoice nota billing BUMN',
            'active' => true,
        ]);

        // 3. Hutang Usaha & Gaji (Liabilitas)
        $hHutang = ChartOfAccount::create([
            'account_group_id' => $grp200?->id,
            'code' => '21000',
            'name' => 'Hutang Lancar & Akrual (Current Liabilities)',
            'level' => 1,
            'normal_balance' => 'credit',
            'is_header' => true,
            'description' => 'Header Kewajiban Jangka Pendek',
            'active' => true,
        ]);

        ChartOfAccount::create([
            'account_group_id' => $grp200?->id,
            'parent_id' => $hHutang->id,
            'code' => '21101',
            'name' => 'Hutang Usaha Vendor / Rekanan',
            'level' => 2,
            'normal_balance' => 'credit',
            'is_header' => false,
            'description' => 'Hutang pengadaan barang/jasa operasional',
            'active' => true,
        ]);

        ChartOfAccount::create([
            'account_group_id' => $grp200?->id,
            'parent_id' => $hHutang->id,
            'code' => '21201',
            'name' => 'Hutang Gaji & Upah Tenaga Alih Daya',
            'level' => 2,
            'normal_balance' => 'credit',
            'is_header' => false,
            'description' => 'Akun perantara posting payroll ke GL',
            'active' => true,
        ]);

        // 4. Pendapatan (Revenue)
        $hRev = ChartOfAccount::create([
            'account_group_id' => $grp400?->id,
            'code' => '41000',
            'name' => 'Pendapatan Jasa Tenaga Alih Daya (TAD)',
            'level' => 1,
            'normal_balance' => 'credit',
            'is_header' => true,
            'description' => 'Header Pendapatan Operasional Proyek',
            'active' => true,
        ]);

        ChartOfAccount::create([
            'account_group_id' => $grp400?->id,
            'parent_id' => $hRev->id,
            'code' => '41101',
            'name' => 'Pendapatan TAD Jasa Operasional Pelabuhan',
            'level' => 2,
            'normal_balance' => 'credit',
            'is_header' => false,
            'description' => 'Realisasi pendapatan segmen TAD Operasional',
            'active' => true,
        ]);

        ChartOfAccount::create([
            'account_group_id' => $grp400?->id,
            'parent_id' => $hRev->id,
            'code' => '41102',
            'name' => 'Pendapatan TAD Jasa Pengamanan',
            'level' => 2,
            'normal_balance' => 'credit',
            'is_header' => false,
            'description' => 'Realisasi pendapatan segmen TAD Security',
            'active' => true,
        ]);

        // 5. Beban Langsung Proyek (Cost of Service)
        $hCost = ChartOfAccount::create([
            'account_group_id' => $grp500?->id,
            'code' => '51000',
            'name' => 'Beban Pokok Proyek & Realisasi RAB',
            'level' => 1,
            'normal_balance' => 'debit',
            'is_header' => true,
            'description' => 'Header Biaya Langsung Proyek Lapangan',
            'active' => true,
        ]);

        ChartOfAccount::create([
            'account_group_id' => $grp500?->id,
            'parent_id' => $hCost->id,
            'code' => '51101',
            'name' => 'Biaya Upah & Tunjangan Pegawai TAD',
            'level' => 2,
            'normal_balance' => 'debit',
            'is_header' => false,
            'description' => 'Beban langsung gaji dari kalkulasi payroll',
            'active' => true,
        ]);

        ChartOfAccount::create([
            'account_group_id' => $grp500?->id,
            'parent_id' => $hCost->id,
            'code' => '51102',
            'name' => 'Biaya Seragam & APD K3 Proyek',
            'level' => 2,
            'normal_balance' => 'debit',
            'is_header' => false,
            'description' => 'Perlengkapan keselamatan kerja lapangan',
            'active' => true,
        ]);
    }

    /**
     * Auto Seed Tax Masters
     */
    private function ensureTaxesSeeded(): void
    {
        if (TaxMaster::exists()) {
            return;
        }

        $taxes = [
            ['code' => 'PPN-11', 'name' => 'PPN Masukan / Keluaran 11%', 'rate_percent' => 11.00, 'tax_type' => 'ppn', 'description' => 'Pajak Pertambahan Nilai tarif standar reguler'],
            ['code' => 'PPN-12', 'name' => 'PPN Penyesuaian 12%', 'rate_percent' => 12.00, 'tax_type' => 'ppn', 'description' => 'Pajak Pertambahan Nilai UU HPP terbaru'],
            ['code' => 'PPH23-JASA', 'name' => 'PPh Pasal 23 Jasa Tenaga Ahli / Manajemen (2%)', 'rate_percent' => 2.00, 'tax_type' => 'pph23', 'description' => 'Pemotongan PPh 23 atas imbalan jasa dan alih daya'],
            ['code' => 'PPH42-SEWA', 'name' => 'PPh Final Pasal 4 Ayat (2) Sewa Tanah/Bangunan (10%)', 'rate_percent' => 10.00, 'tax_type' => 'pph4_2', 'description' => 'Pemotongan pajak final atas persewaan aset properti'],
            ['code' => 'PPH42-KONSTR', 'name' => 'PPh Final Jasa Konstruksi / Pemborongan (2.65%)', 'rate_percent' => 2.65, 'tax_type' => 'pph4_2', 'description' => 'Pajak penghasilan final pekerjaan konstruksi & instalasi'],
            ['code' => 'PPH21-TAD', 'name' => 'PPh Pasal 21 Tenaga Kerja Alih Daya', 'rate_percent' => 5.00, 'tax_type' => 'pph21', 'description' => 'Tarif progresif pemotongan pajak penghasilan karyawan TAD'],
        ];

        foreach ($taxes as $tax) {
            TaxMaster::create($tax + ['active' => true]);
        }
    }
}
