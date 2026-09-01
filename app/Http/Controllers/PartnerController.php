<?php

namespace App\Http\Controllers;

use App\Models\PartnerType;
use App\Models\Partner;
use App\Models\PartnerBankAccount;
use App\Models\PartnerBusinessSegment;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * Display Partner Types Master
     */
    public function partnerTypeIndex(Request $request)
    {
        $this->ensurePartnerTypesSeeded();

        $query = PartnerType::withCount('partners');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $partnerTypes = $query->orderBy('code')->paginate(10)->withQueryString();
        $totalAll = PartnerType::count();
        $totalActive = PartnerType::where('active', true)->count();

        return view('general.partner-type', compact('partnerTypes', 'totalAll', 'totalActive'));
    }

    public function partnerTypeStore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:partner_types,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'nullable',
        ]);

        PartnerType::create([
            'code' => strtoupper(trim($request->code)),
            'name' => $request->name,
            'description' => $request->description,
            'active' => $request->has('active') ? (bool) $request->active : true,
        ]);

        return redirect()->back()->with('success', 'Partner Type baru berhasil ditambahkan!');
    }

    public function partnerTypeUpdate(Request $request, int $id)
    {
        $type = PartnerType::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:partner_types,code,' . $id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'active' => 'nullable',
        ]);

        $type->update([
            'code' => strtoupper(trim($request->code)),
            'name' => $request->name,
            'description' => $request->description,
            'active' => $request->has('active') ? (bool) $request->active : false,
        ]);

        return redirect()->back()->with('success', 'Data Partner Type berhasil diperbarui!');
    }

    public function partnerTypeDestroy(int $id)
    {
        $type = PartnerType::findOrFail($id);

        if ($type->partners()->exists()) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus Partner Type karena masih memiliki relasi Partner aktif.');
        }

        $type->delete();
        return redirect()->back()->with('success', 'Partner Type berhasil dihapus!');
    }

    /**
     * Display Business Partner - List (Index Page)
     */
    public function partnerIndex(Request $request)
    {
        $this->ensurePartnerTypesSeeded();
        $this->ensurePartnersSeeded();

        $query = Partner::with(['partnerType', 'bankAccounts', 'businessSegments']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('npwp', 'like', "%{$search}%")
                  ->orWhere('identity_card', 'like', "%{$search}%")
                  ->orWhere('chief_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_1', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('kode_mdm', 'like', "%{$search}%");
            });
        }

        if ($request->filled('partner_type_id')) {
            $query->where('partner_type_id', $request->partner_type_id);
        }

        if ($request->filled('role')) {
            if ($request->role === 'vendor') {
                $query->where('is_vendor', true);
            } elseif ($request->role === 'customer') {
                $query->where('is_customer', true);
            }
        }

        $perPage = (int) $request->input('per_page', 10);
        if ($perPage < 5 || $perPage > 100) {
            $perPage = 10;
        }

        $partners = $query->orderBy('code')->paginate($perPage)->withQueryString();
        $partnerTypes = PartnerType::where('active', true)->orderBy('name')->get();

        $totalAll = Partner::count();
        $totalActive = Partner::where('active', true)->count();
        $totalVendors = Partner::where('is_vendor', true)->count();
        $totalCustomers = Partner::where('is_customer', true)->count();

        return view('general.partner.index', compact(
            'partners',
            'partnerTypes',
            'totalAll',
            'totalActive',
            'totalVendors',
            'totalCustomers',
            'perPage'
        ));
    }

    /**
     * Show Create Partner Page
     */
    public function partnerCreate()
    {
        $this->ensurePartnerTypesSeeded();
        $partnerTypes = PartnerType::where('active', true)->orderBy('name')->get();

        return view('general.partner.create', compact('partnerTypes'));
    }

    /**
     * Store a newly created Partner
     */
    public function partnerStore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:partners,code',
            'name' => 'required|string|max:255',
            'partner_type_id' => 'nullable|exists:partner_types,id',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'identity_card' => 'nullable|string|max:50',
            'npwp' => 'nullable|string|max:50',
            'chief_name' => 'nullable|string|max:255',
            'chief_position' => 'nullable|string|max:255',
            'trading_partner' => 'nullable|string|max:255',
            'partner_group' => 'nullable|string|max:255',
            'phone_1' => 'nullable|string|max:50',
            'phone_2' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'ftp_link' => 'nullable|string|max:255',
            'ftp_port' => 'nullable|string|max:20',
            'ftp_user' => 'nullable|string|max:255',
            'ftp_pass' => 'nullable|string|max:255',
            'kode_mdm' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'payment_terms_days' => 'nullable|integer|min:0',
        ]);

        $partner = Partner::create([
            'partner_type_id' => $request->partner_type_id,
            'code' => trim($request->code),
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'identity_card' => $request->identity_card,
            'npwp' => $request->npwp,
            'is_vendor' => $request->has('is_vendor') ? (bool) $request->is_vendor : false,
            'is_customer' => $request->has('is_customer') ? (bool) $request->is_customer : false,
            'chief_name' => $request->chief_name,
            'chief_position' => $request->chief_position,
            'status_hold_dana' => $request->has('status_hold_dana') ? (bool) $request->status_hold_dana : false,
            'auto_generate_faktur' => $request->has('auto_generate_faktur') ? (bool) $request->auto_generate_faktur : false,
            'trading_partner' => $request->trading_partner,
            'partner_group' => $request->partner_group,
            'phone_1' => $request->phone_1,
            'phone_2' => $request->phone_2,
            'phone' => $request->phone_1 ?? $request->phone_2,
            'email' => $request->email,
            'ftp_link' => $request->ftp_link,
            'ftp_port' => $request->ftp_port,
            'ftp_user' => $request->ftp_user,
            'ftp_pass' => $request->ftp_pass,
            'kode_mdm' => $request->kode_mdm,
            'description' => $request->description,
            'payment_terms_days' => $request->payment_terms_days ?? 30,
            'active' => true,
        ]);

        // Optional initial bank account
        if ($request->filled('bank_name') && $request->filled('bank_account_number')) {
            PartnerBankAccount::create([
                'partner_id' => $partner->id,
                'bank_name' => $request->bank_name,
                'account_number' => $request->bank_account_number,
                'account_holder' => $request->bank_account_holder ?? $partner->name,
                'branch' => $request->bank_branch ?? 'Kantor Cabang Utama',
                'is_primary' => true,
                'active' => true,
            ]);
        }

        return redirect()->route('general.partner.show', $partner->id)->with('success', 'Data Business Partner berhasil dibuat!');
    }

    /**
     * Show Business Partner - View Page (3 Tabs: General, Banks, Business Segments)
     */
    public function partnerShow(int $id)
    {
        $partner = Partner::with(['partnerType', 'bankAccounts', 'businessSegments'])->findOrFail($id);

        return view('general.partner.show', compact('partner'));
    }

    /**
     * Show Edit Partner Page
     */
    public function partnerEdit(int $id)
    {
        $partner = Partner::with(['partnerType', 'bankAccounts', 'businessSegments'])->findOrFail($id);
        $partnerTypes = PartnerType::where('active', true)->orderBy('name')->get();

        return view('general.partner.edit', compact('partner', 'partnerTypes'));
    }

    /**
     * Update Partner Data
     */
    public function partnerUpdate(Request $request, int $id)
    {
        $partner = Partner::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:partners,code,' . $id,
            'name' => 'required|string|max:255',
            'partner_type_id' => 'nullable|exists:partner_types,id',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'identity_card' => 'nullable|string|max:50',
            'npwp' => 'nullable|string|max:50',
            'chief_name' => 'nullable|string|max:255',
            'chief_position' => 'nullable|string|max:255',
            'trading_partner' => 'nullable|string|max:255',
            'partner_group' => 'nullable|string|max:255',
            'phone_1' => 'nullable|string|max:50',
            'phone_2' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'ftp_link' => 'nullable|string|max:255',
            'ftp_port' => 'nullable|string|max:20',
            'ftp_user' => 'nullable|string|max:255',
            'ftp_pass' => 'nullable|string|max:255',
            'kode_mdm' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'payment_terms_days' => 'nullable|integer|min:0',
        ]);

        $partner->update([
            'partner_type_id' => $request->partner_type_id,
            'code' => trim($request->code),
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'identity_card' => $request->identity_card,
            'npwp' => $request->npwp,
            'is_vendor' => $request->has('is_vendor'),
            'is_customer' => $request->has('is_customer'),
            'chief_name' => $request->chief_name,
            'chief_position' => $request->chief_position,
            'status_hold_dana' => $request->has('status_hold_dana'),
            'auto_generate_faktur' => $request->has('auto_generate_faktur'),
            'trading_partner' => $request->trading_partner,
            'partner_group' => $request->partner_group,
            'phone_1' => $request->phone_1,
            'phone_2' => $request->phone_2,
            'phone' => $request->phone_1 ?? $request->phone_2,
            'email' => $request->email,
            'ftp_link' => $request->ftp_link,
            'ftp_port' => $request->ftp_port,
            'ftp_user' => $request->ftp_user,
            'ftp_pass' => $request->ftp_pass,
            'kode_mdm' => $request->kode_mdm,
            'description' => $request->description,
            'payment_terms_days' => $request->payment_terms_days ?? 30,
            'active' => $request->has('active') ? (bool) $request->active : $partner->active,
        ]);

        return redirect()->route('general.partner.show', $partner->id)->with('success', 'Data Business Partner berhasil diperbarui!');
    }

    /**
     * Delete Partner
     */
    public function partnerDestroy(int $id)
    {
        $partner = Partner::findOrFail($id);
        $partner->delete();

        return redirect()->route('general.partner')->with('success', 'Data Business Partner berhasil dihapus!');
    }

    /**
     * Add Bank Account from Partner Show View (Tab Banks)
     */
    public function partnerBankStore(Request $request, int $id)
    {
        $partner = Partner::findOrFail($id);

        $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:100',
            'account_holder' => 'required|string|max:255',
            'branch' => 'nullable|string|max:255',
            'is_primary' => 'nullable',
        ]);

        if ($request->has('is_primary')) {
            PartnerBankAccount::where('partner_id', $partner->id)->update(['is_primary' => false]);
        }

        PartnerBankAccount::create([
            'partner_id' => $partner->id,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'branch' => $request->branch ?? 'Cabang Utama',
            'is_primary' => $request->has('is_primary'),
            'active' => true,
        ]);

        return redirect()->route('general.partner.show', ['id' => $partner->id, 'tab' => 'banks'])->with('success', 'Rekening Bank baru berhasil ditambahkan ke partner ini!');
    }

    /**
     * Delete Bank Account from Partner Show View (Tab Banks)
     */
    public function partnerBankDestroy(int $partnerId, int $bankId)
    {
        $bank = PartnerBankAccount::where('partner_id', $partnerId)->findOrFail($bankId);
        $bank->delete();

        return redirect()->route('general.partner.show', ['id' => $partnerId, 'tab' => 'banks'])->with('success', 'Rekening Bank berhasil dihapus!');
    }

    /**
     * Add Business Segment from Partner Show View (Tab Business Segments)
     */
    public function partnerSegmentStore(Request $request, int $id)
    {
        $partner = Partner::findOrFail($id);

        $request->validate([
            'segment_code' => 'required|string|max:50',
            'segment_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        PartnerBusinessSegment::create([
            'partner_id' => $partner->id,
            'segment_code' => strtoupper(trim($request->segment_code)),
            'segment_name' => $request->segment_name,
            'description' => $request->description,
            'active' => true,
        ]);

        return redirect()->route('general.partner.show', ['id' => $partner->id, 'tab' => 'segments'])->with('success', 'Business Segment berhasil ditambahkan!');
    }

    /**
     * Delete Business Segment from Partner Show View (Tab Business Segments)
     */
    public function partnerSegmentDestroy(int $partnerId, int $segmentId)
    {
        $segment = PartnerBusinessSegment::where('partner_id', $partnerId)->findOrFail($segmentId);
        $segment->delete();

        return redirect()->route('general.partner.show', ['id' => $partnerId, 'tab' => 'segments'])->with('success', 'Business Segment berhasil dihapus!');
    }

    /**
     * Display Bank ACS Customer (General Bank ACS Master)
     */
    public function bankAcsIndex(Request $request)
    {
        $this->ensurePartnerTypesSeeded();
        $this->ensurePartnersSeeded();

        $query = PartnerBankAccount::with('partner');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('bank_name', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%")
                  ->orWhere('account_holder', 'like', "%{$search}%")
                  ->orWhere('branch', 'like', "%{$search}%")
                  ->orWhereHas('partner', fn($sub) => $sub->where('name', 'like', "%{$search}%"));
            });
        }

        $bankAccounts = $query->orderBy('bank_name')->paginate(12)->withQueryString();
        $partners = Partner::where('active', true)->orderBy('name')->get();

        $totalAll = PartnerBankAccount::count();
        $totalActive = PartnerBankAccount::where('active', true)->count();
        $totalPrimary = PartnerBankAccount::where('is_primary', true)->count();

        $totalAccounts = $totalAll;
        $primaryAccounts = $totalPrimary;
        $activeAccounts = $totalActive;

        return view('general.bank-acs', compact('bankAccounts', 'partners', 'totalAll', 'totalActive', 'totalPrimary', 'totalAccounts', 'primaryAccounts', 'activeAccounts'));
    }

    public function bankAcsStore(Request $request)
    {
        $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:100',
            'account_holder' => 'required|string|max:255',
            'branch' => 'nullable|string|max:255',
            'is_primary' => 'nullable',
            'active' => 'nullable',
        ]);

        if ($request->has('is_primary')) {
            PartnerBankAccount::where('partner_id', $request->partner_id)->update(['is_primary' => false]);
        }

        PartnerBankAccount::create([
            'partner_id' => $request->partner_id,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'branch' => $request->branch ?? 'Kantor Cabang Utama',
            'is_primary' => $request->has('is_primary') ? (bool) $request->is_primary : false,
            'active' => $request->has('active') ? (bool) $request->active : true,
        ]);

        return redirect()->back()->with('success', 'Rekening Bank ACS baru berhasil ditambahkan!');
    }

    public function bankAcsUpdate(Request $request, int $id)
    {
        $account = PartnerBankAccount::findOrFail($id);

        $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:100',
            'account_holder' => 'required|string|max:255',
            'branch' => 'nullable|string|max:255',
            'is_primary' => 'nullable',
            'active' => 'nullable',
        ]);

        if ($request->has('is_primary')) {
            PartnerBankAccount::where('partner_id', $request->partner_id)
                ->where('id', '!=', $id)
                ->update(['is_primary' => false]);
        }

        $account->update([
            'partner_id' => $request->partner_id,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'branch' => $request->branch ?? $account->branch,
            'is_primary' => $request->has('is_primary') ? (bool) $request->is_primary : false,
            'active' => $request->has('active') ? (bool) $request->active : false,
        ]);

        return redirect()->back()->with('success', 'Rekening Bank ACS berhasil diperbarui!');
    }

    public function bankAcsDestroy(int $id)
    {
        $account = PartnerBankAccount::findOrFail($id);
        $account->delete();

        return redirect()->back()->with('success', 'Rekening Bank ACS berhasil dihapus!');
    }

    /**
     * Auto Seed Partner Types if table is empty
     */
    private function ensurePartnerTypesSeeded(): void
    {
        if (PartnerType::exists()) {
            return;
        }

        $defaults = [
            ['code' => '001', 'name' => 'BUMN / Instansi Pemerintah', 'description' => 'Perusahaan BUMN, kementerian dan lembaga negara'],
            ['code' => '002', 'name' => 'SWASTA', 'description' => 'Perusahaan Swasta Nasional & Multinasional'],
            ['code' => '003', 'name' => 'KOPERASI', 'description' => 'Koperasi Karyawan & Usaha Bersama'],
            ['code' => '007', 'name' => 'PERBANKAN / FINANSIAL', 'description' => 'Bank BUMN, Bank Swasta, dan Lembaga Keuangan'],
            ['code' => 'VENDOR', 'name' => 'Vendor / Penyedia Barang & Jasa', 'description' => 'Penyedia material, alat berat, dan jasa logistik'],
            ['code' => 'CUSTOMER', 'name' => 'Customer / Klien Pelanggan', 'description' => 'Pengguna jasa alih daya dan operasional pelabuhan'],
        ];

        foreach ($defaults as $type) {
            PartnerType::create($type + ['active' => true]);
        }
    }

    /**
     * Auto Seed Initial Partners matching real Tanos screenshots
     */
    private function ensurePartnersSeeded(): void
    {
        $this->ensurePartnerTypesSeeded();

        if (Partner::exists()) {
            return;
        }

        $typeSwasta = PartnerType::where('code', '002')->orWhere('code', 'SWASTA')->first();
        $typeBank = PartnerType::where('code', '007')->orWhere('code', 'PERBANKAN / FINANSIAL')->first();
        $typeKoperasi = PartnerType::where('code', '003')->orWhere('code', 'KOPERASI')->first();

        $defaults = [
            [
                'partner_type_id' => $typeSwasta?->id,
                'code' => '2000000104',
                'name' => 'KOP PEGAWAI NEGRI PERUM PELABUHAN III BENOA',
                'address' => 'JL. PELABUHAN BENOA, BADUNG',
                'city' => 'BADUNG',
                'identity_card' => null,
                'npwp' => '015221914905000',
                'is_vendor' => true,
                'is_customer' => true,
                'chief_name' => 'I Wayan Sudiarta',
                'chief_position' => 'Ketua Koperasi',
                'status_hold_dana' => false,
                'auto_generate_faktur' => true,
                'trading_partner' => null,
                'partner_group' => null,
                'phone_1' => '0',
                'phone_2' => null,
                'phone' => '0361-720123',
                'email' => 'kop.benoa@pelindo.co.id',
                'ftp_link' => null,
                'ftp_port' => null,
                'ftp_user' => null,
                'ftp_pass' => null,
                'kode_mdm' => '00045583',
                'description' => 'KOP PEGAWAI NEGRI PERUM PELABUHAN III BENOA',
                'payment_terms_days' => 30,
            ],
            [
                'partner_type_id' => $typeSwasta?->id,
                'code' => '2000012154',
                'name' => 'PERSEK KAP PURWANTONO, SUNGKONO & SURJA',
                'address' => 'GD BEI TOWER II LT 7, JL JEND. SUDIRMAN',
                'city' => 'JAKARTA SELATAN',
                'identity_card' => null,
                'npwp' => '021077698062000',
                'is_vendor' => true,
                'is_customer' => true,
                'chief_name' => 'Purwantono Sungkono',
                'chief_position' => 'Managing Partner',
                'status_hold_dana' => false,
                'auto_generate_faktur' => true,
                'trading_partner' => null,
                'partner_group' => 'EY Indonesia',
                'phone_1' => '0',
                'phone_2' => null,
                'phone' => '021-52895000',
                'email' => 'contact@id.ey.com',
                'ftp_link' => null,
                'ftp_port' => null,
                'ftp_user' => null,
                'ftp_pass' => null,
                'kode_mdm' => '00046892',
                'description' => 'PERSEK KAP PURWANTONO, SUNGKONO & SURJA',
                'payment_terms_days' => 30,
            ],
            [
                'partner_type_id' => $typeBank?->id,
                'code' => '1000000103',
                'name' => 'PT BANK MANDIRI (PERSERO)',
                'address' => 'JL. JEND. GATOT SUBROTO KAV. 36-38 PLAZA MANDIRI SENAYAN, KEBAYORAN BARU',
                'city' => 'JAKARTA SELATAN',
                'identity_card' => null,
                'npwp' => '010611739093000',
                'is_vendor' => true,
                'is_customer' => true,
                'chief_name' => 'Darmawan Junaidi',
                'chief_position' => 'Direktur Utama',
                'status_hold_dana' => false,
                'auto_generate_faktur' => true,
                'trading_partner' => null,
                'partner_group' => 'Himbara',
                'phone_1' => '0',
                'phone_2' => null,
                'phone' => '14000',
                'email' => 'mandiricare@bankmandiri.co.id',
                'ftp_link' => null,
                'ftp_port' => null,
                'ftp_user' => null,
                'ftp_pass' => null,
                'kode_mdm' => '00010022',
                'description' => 'PT BANK MANDIRI (PERSERO)',
                'payment_terms_days' => 14,
            ],
            [
                'partner_type_id' => $typeSwasta?->id,
                'code' => '2000070678',
                'name' => 'PT KRAKATAU BANDAR SAMUDERA',
                'address' => 'JL. RAYA ANYER KM 13 TEGAL KEL. RATU CIWANDAN',
                'city' => 'CILEGON',
                'identity_card' => null,
                'npwp' => '017548058051000',
                'is_vendor' => true,
                'is_customer' => true,
                'chief_name' => 'Akbar Djohan',
                'chief_position' => 'Direktur Utama',
                'status_hold_dana' => false,
                'auto_generate_faktur' => true,
                'trading_partner' => null,
                'partner_group' => 'Krakatau Steel Group',
                'phone_1' => '0',
                'phone_2' => null,
                'phone' => '0254-311121',
                'email' => 'commercial@cigadingport.com',
                'ftp_link' => null,
                'ftp_port' => null,
                'ftp_user' => null,
                'ftp_pass' => null,
                'kode_mdm' => '00049912',
                'description' => 'PT KRAKATAU BANDAR SAMUDERA',
                'payment_terms_days' => 30,
            ],
            [
                'partner_type_id' => $typeSwasta?->id,
                'code' => '1000001107',
                'name' => 'PT VARIA USAHA BAHARI',
                'address' => 'JL. VETERAN BLOK A NO. 171 RT:002 RW:001 KEL. GENDING',
                'city' => 'GRESIK',
                'identity_card' => null,
                'npwp' => '015682073641000',
                'is_vendor' => true,
                'is_customer' => true,
                'chief_name' => 'Hadi Sucipto',
                'chief_position' => 'Direktur',
                'status_hold_dana' => false,
                'auto_generate_faktur' => true,
                'trading_partner' => null,
                'partner_group' => 'SIG Group',
                'phone_1' => '0',
                'phone_2' => null,
                'phone' => '031-3981887',
                'email' => 'sekretariat@variausaha.co.id',
                'ftp_link' => null,
                'ftp_port' => null,
                'ftp_user' => null,
                'ftp_pass' => null,
                'kode_mdm' => '00048102',
                'description' => 'PT VARIA USAHA BAHARI',
                'payment_terms_days' => 30,
            ],
        ];

        foreach ($defaults as $data) {
            $partner = Partner::create($data + ['active' => true]);

            // Add sample bank account
            PartnerBankAccount::create([
                'partner_id' => $partner->id,
                'bank_name' => 'Bank Mandiri',
                'account_number' => '120-00-' . rand(1000000, 9999999) . '-1',
                'account_holder' => $partner->name,
                'branch' => 'KC ' . ($partner->city ?? 'Jakarta'),
                'is_primary' => true,
                'active' => true,
            ]);

            // Add sample business segment
            PartnerBusinessSegment::create([
                'partner_id' => $partner->id,
                'segment_code' => 'SEG-' . substr($partner->code, -3),
                'segment_name' => 'Logistik & Layanan Pelabuhan',
                'description' => 'Unit bisnis operasional bongkar muat & supply chain',
                'active' => true,
            ]);
        }
    }
}
