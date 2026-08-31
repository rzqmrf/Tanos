<?php

namespace App\Http\Controllers;

use App\Models\PartnerType;
use App\Models\Partner;
use App\Models\PartnerBankAccount;
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
     * Display Partner / Mitraniaga Master
     */
    public function partnerIndex(Request $request)
    {
        $this->ensurePartnerTypesSeeded();
        $this->ensurePartnersSeeded();

        $query = Partner::with('partnerType');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('pic_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('partner_type_id')) {
            $query->where('partner_type_id', $request->partner_type_id);
        }

        $partners = $query->orderBy('name')->paginate(12)->withQueryString();
        $partnerTypes = PartnerType::where('active', true)->orderBy('name')->get();

        $totalAll = Partner::count();
        $totalActive = Partner::where('active', true)->count();
        $totalVendors = Partner::whereHas('partnerType', fn($q) => $q->where('code', 'VENDOR'))->count();
        $totalCustomers = Partner::whereHas('partnerType', fn($q) => $q->where('code', 'CUSTOMER'))->count();

        return view('general.partner', compact('partners', 'partnerTypes', 'totalAll', 'totalActive', 'totalVendors', 'totalCustomers'));
    }

    public function partnerStore(Request $request)
    {
        $request->validate([
            'partner_type_id' => 'required|exists:partner_types,id',
            'code' => 'required|string|max:50|unique:partners,code',
            'name' => 'required|string|max:255',
            'npwp' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'pic_name' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:100',
            'bank_account_holder' => 'nullable|string|max:255',
            'payment_terms_days' => 'nullable|integer|min:0',
            'active' => 'nullable',
        ]);

        $partner = Partner::create([
            'partner_type_id' => $request->partner_type_id,
            'code' => strtoupper(trim($request->code)),
            'name' => $request->name,
            'npwp' => $request->npwp,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'pic_name' => $request->pic_name,
            'pic_phone' => $request->pic_phone,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_holder' => $request->bank_account_holder,
            'payment_terms_days' => $request->payment_terms_days ?? 30,
            'active' => $request->has('active') ? (bool) $request->active : true,
        ]);

        if (!empty($request->bank_name) && !empty($request->bank_account_number)) {
            PartnerBankAccount::create([
                'partner_id' => $partner->id,
                'bank_name' => $request->bank_name,
                'account_number' => $request->bank_account_number,
                'account_holder' => $request->bank_account_holder ?? $partner->name,
                'branch' => 'Kantor Cabang Utama',
                'is_primary' => true,
                'active' => true,
            ]);
        }

        return redirect()->back()->with('success', 'Partner Mitraniaga baru berhasil ditambahkan!');
    }

    public function partnerUpdate(Request $request, int $id)
    {
        $partner = Partner::findOrFail($id);

        $request->validate([
            'partner_type_id' => 'required|exists:partner_types,id',
            'code' => 'required|string|max:50|unique:partners,code,' . $id,
            'name' => 'required|string|max:255',
            'npwp' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'pic_name' => 'nullable|string|max:255',
            'pic_phone' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:100',
            'bank_account_holder' => 'nullable|string|max:255',
            'payment_terms_days' => 'nullable|integer|min:0',
            'active' => 'nullable',
        ]);

        $partner->update([
            'partner_type_id' => $request->partner_type_id,
            'code' => strtoupper(trim($request->code)),
            'name' => $request->name,
            'npwp' => $request->npwp,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'pic_name' => $request->pic_name,
            'pic_phone' => $request->pic_phone,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_holder' => $request->bank_account_holder,
            'payment_terms_days' => $request->payment_terms_days ?? 30,
            'active' => $request->has('active') ? (bool) $request->active : false,
        ]);

        return redirect()->back()->with('success', 'Data Partner berhasil diperbarui!');
    }

    public function partnerDestroy(int $id)
    {
        $partner = Partner::findOrFail($id);
        $partner->delete();

        return redirect()->back()->with('success', 'Data Partner berhasil dihapus!');
    }

    /**
     * Display Bank ACS Master
     */
    public function bankAcsIndex(Request $request)
    {
        $this->ensurePartnersSeeded();

        $query = PartnerBankAccount::with('partner');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('bank_name', 'like', "%{$search}%")
                  ->orWhere('account_number', 'like', "%{$search}%")
                  ->orWhere('account_holder', 'like', "%{$search}%")
                  ->orWhereHas('partner', fn($pq) => $pq->where('name', 'like', "%{$search}%"));
            });
        }

        $bankAccounts = $query->orderBy('bank_name')->paginate(10)->withQueryString();
        $partners = Partner::where('active', true)->orderBy('name')->get();
        $totalAccounts = PartnerBankAccount::count();
        $primaryAccounts = PartnerBankAccount::where('is_primary', true)->count();

        return view('general.bank-acs', compact('bankAccounts', 'partners', 'totalAccounts', 'primaryAccounts'));
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
            'branch' => $request->branch ?? 'Cabang Utama',
            'is_primary' => $request->has('is_primary') ? (bool) $request->is_primary : false,
            'active' => $request->has('active') ? (bool) $request->active : true,
        ]);

        return redirect()->back()->with('success', 'Rekening Bank ACS Mitra berhasil ditambahkan!');
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

        if ($request->has('is_primary') && $request->is_primary) {
            PartnerBankAccount::where('partner_id', $request->partner_id)->where('id', '!=', $id)->update(['is_primary' => false]);
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
            ['code' => 'VENDOR', 'name' => 'Vendor / Penyedia Jasa', 'description' => 'Mitra penyedia barang & jasa operasional pelabuhan'],
            ['code' => 'CUSTOMER', 'name' => 'Customer / Pengguna Jasa', 'description' => 'Klien penerima layanan dan pemesan proyek TAD / alih daya'],
            ['code' => 'SUBCON', 'name' => 'Subkontraktor Spesialis', 'description' => 'Mitra pelaksana sub-pekerjaan teknik & operasional'],
            ['code' => 'BUMN', 'name' => 'Afiliasi BUMN / Pelindo Group', 'description' => 'Perusahaan afiliasi dan anak perusahaan BUMN'],
            ['code' => 'GOVERNMENT', 'name' => 'Instansi / Regulator', 'description' => 'Otoritas pelabuhan, kementerian, dan dinas ketenagakerjaan'],
        ];

        foreach ($defaults as $type) {
            PartnerType::create($type + ['active' => true]);
        }
    }

    /**
     * Auto Seed Initial Partners if table is empty
     */
    private function ensurePartnersSeeded(): void
    {
        $this->ensurePartnerTypesSeeded();

        if (Partner::exists()) {
            return;
        }

        $vendorType = PartnerType::where('code', 'VENDOR')->first();
        $custType = PartnerType::where('code', 'CUSTOMER')->first();
        $bumnType = PartnerType::where('code', 'BUMN')->first();

        $defaults = [
            [
                'partner_type_id' => $bumnType?->id,
                'code' => 'PRT-PLD01',
                'name' => 'PT Pelabuhan Indonesia (Persero) Regional 2',
                'npwp' => '01.001.600.4-051.000',
                'email' => 'finance.reg2@pelindo.co.id',
                'phone' => '021-4301080',
                'address' => 'Jl. Pasoso No. 1 Tanjung Priok, Jakarta Utara',
                'pic_name' => 'Bambang Sudiro',
                'pic_phone' => '081288990011',
                'bank_name' => 'Bank Mandiri',
                'bank_account_number' => '120-00-1122334-4',
                'bank_account_holder' => 'PT Pelabuhan Indonesia (Persero)',
                'payment_terms_days' => 30,
            ],
            [
                'partner_type_id' => $custType?->id,
                'code' => 'PRT-IPC01',
                'name' => 'PT IPC Terminal Petikemas',
                'npwp' => '02.345.678.9-012.000',
                'email' => 'corsec@ipctpk.co.id',
                'phone' => '021-43900222',
                'address' => 'Gedung Terminal Petikemas Lt. 3, Tanjung Priok',
                'pic_name' => 'Dwi Handayani',
                'pic_phone' => '081377889900',
                'bank_name' => 'Bank BNI',
                'bank_account_number' => '019-8877665-0',
                'bank_account_holder' => 'PT IPC Terminal Petikemas',
                'payment_terms_days' => 45,
            ],
            [
                'partner_type_id' => $vendorType?->id,
                'code' => 'PRT-SEC01',
                'name' => 'PT Garda Cipta Pengamanan Mandiri',
                'npwp' => '31.222.333.4-022.000',
                'email' => 'ops@gardacipta.com',
                'phone' => '021-88445566',
                'address' => 'Kawasan Industri Pulogadung Blok B No. 4, Jakarta Timur',
                'pic_name' => 'Agus Priyono',
                'pic_phone' => '081199887766',
                'bank_name' => 'Bank BCA',
                'bank_account_number' => '542-0192837',
                'bank_account_holder' => 'PT Garda Cipta Pengamanan Mandiri',
                'payment_terms_days' => 14,
            ],
        ];

        foreach ($defaults as $partnerData) {
            $partner = Partner::create($partnerData + ['active' => true]);
            if ($partner->bank_name && $partner->bank_account_number) {
                PartnerBankAccount::create([
                    'partner_id' => $partner->id,
                    'bank_name' => $partner->bank_name,
                    'account_number' => $partner->bank_account_number,
                    'account_holder' => $partner->bank_account_holder,
                    'branch' => 'Kantor Cabang Utama',
                    'is_primary' => true,
                    'active' => true,
                ]);
            }
        }
    }
}
