<?php

namespace App\Http\Controllers;

use App\Models\PeoSetting;
use App\Models\Regional;
use App\Models\Segment;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class PeoSettingController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->get('tab', 'Berita Acara');
        if (!in_array($tab, ['Berita Acara', 'Surat Keluar'])) {
            $tab = 'Berita Acara';
        }

        $perPage = (int) $request->get('per_page', 10);
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $query = PeoSetting::where('tab_category', $tab);

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('customer', 'like', "%{$search}%")
                  ->orWhere('project_name', 'like', "%{$search}%")
                  ->orWhere('project_code', 'like', "%{$search}%")
                  ->orWhere('document_type', 'like', "%{$search}%");
            });
        }

        $peoSettings = $query->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

        $dashboardService = new DashboardService();
        $regionals = Regional::orderBy('name')->get();
        $segments = Segment::orderBy('name')->get();
        $months = $dashboardService->getMonths();

        return view('general.peo-settings', [
            'peoSettings' => $peoSettings,
            'activeTab' => $tab,
            'perPage' => $perPage,
            'search' => $request->get('search', ''),
            'regionals' => $regionals,
            'segments' => $segments,
            'months' => $months,
        ]);
    }

    public function show($id)
    {
        $peoSetting = PeoSetting::with(['signers', 'initials'])->findOrFail($id);

        return view('general.peo-settings-detail', [
            'peoSetting' => $peoSetting,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_type' => 'required|string|max:255',
            'customer' => 'required|string|max:255',
            'project_name' => 'required|string|max:255',
            'project_code' => 'required|string|max:255',
            'tab_category' => 'required|in:Berita Acara,Surat Keluar',
        ]);

        PeoSetting::create($validated);

        return redirect()->back()->with('success', 'Data Mapping PEO Setting berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $setting = PeoSetting::findOrFail($id);

        $validated = $request->validate([
            'document_type' => 'required|string|max:255',
            'customer' => 'required|string|max:255',
            'project_name' => 'required|string|max:255',
            'project_code' => 'required|string|max:255',
            'tab_category' => 'required|in:Berita Acara,Surat Keluar',
        ]);

        $setting->update($validated);

        return redirect()->back()->with('success', 'Data Mapping PEO Setting berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $setting = PeoSetting::findOrFail($id);
        $setting->delete();

        return redirect()->route('peo.index')->with('success', 'Data Mapping PEO Setting berhasil dihapus.');
    }
}
