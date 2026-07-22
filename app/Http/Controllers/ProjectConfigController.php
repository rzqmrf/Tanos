<?php

namespace App\Http\Controllers;

use App\Models\Regional;
use App\Models\SubRegional;
use App\Models\Segment;
use Illuminate\Http\Request;

class ProjectConfigController extends Controller
{
    public function index()
    {
        $this->ensureDefaultSeeded();

        $regionals = Regional::withCount('subRegionals')->orderBy('name')->get();
        $subRegionals = SubRegional::with('regional')->orderBy('name')->get();
        $segments = Segment::orderBy('name')->get();

        return view('dashboard.project-config', compact('regionals', 'subRegionals', 'segments'));
    }

    public function storeRegional(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:regionals,name',
        ], [
            'name.unique' => 'Nama regional ini sudah ada.',
            'name.required' => 'Nama regional wajib diisi.',
        ]);

        Regional::create([
            'name' => trim($request->name),
        ]);

        return redirect()->back()->with('success', 'Regional baru berhasil ditambahkan!');
    }

    public function updateRegional(Request $request, Regional $regional)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:regionals,name,' . $regional->id,
        ], [
            'name.unique' => 'Nama regional ini sudah digunakan.',
            'name.required' => 'Nama regional wajib diisi.',
        ]);

        $regional->update([
            'name' => trim($request->name),
        ]);

        return redirect()->back()->with('success', 'Regional berhasil diperbarui!');
    }

    public function destroyRegional(Regional $regional)
    {
        $regional->delete();
        return redirect()->back()->with('success', 'Regional berhasil dihapus!');
    }

    public function storeSubRegional(Request $request)
    {
        $request->validate([
            'regional_id' => 'required|exists:regionals,id',
            'name' => 'required|string|max:255',
        ], [
            'regional_id.required' => 'Parent Regional wajib dipilih.',
            'regional_id.exists' => 'Regional pilihan tidak valid.',
            'name.required' => 'Nama Sub-Regional wajib diisi.',
        ]);

        SubRegional::create([
            'regional_id' => $request->regional_id,
            'name' => trim($request->name),
        ]);

        return redirect()->back()->with('success', 'Sub-Regional baru berhasil ditambahkan!');
    }

    public function updateSubRegional(Request $request, SubRegional $subRegional)
    {
        $request->validate([
            'regional_id' => 'required|exists:regionals,id',
            'name' => 'required|string|max:255',
        ], [
            'regional_id.required' => 'Parent Regional wajib dipilih.',
            'regional_id.exists' => 'Regional pilihan tidak valid.',
            'name.required' => 'Nama Sub-Regional wajib diisi.',
        ]);

        $subRegional->update([
            'regional_id' => $request->regional_id,
            'name' => trim($request->name),
        ]);

        return redirect()->back()->with('success', 'Sub-Regional berhasil diperbarui!');
    }

    public function destroySubRegional(SubRegional $subRegional)
    {
        $subRegional->delete();
        return redirect()->back()->with('success', 'Sub-Regional berhasil dihapus!');
    }

    public function storeSegment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:segments,name',
        ], [
            'name.unique' => 'Nama segment ini sudah ada.',
            'name.required' => 'Nama segment wajib diisi.',
        ]);

        Segment::create([
            'name' => trim($request->name),
        ]);

        return redirect()->back()->with('success', 'Segment baru berhasil ditambahkan!');
    }

    public function updateSegment(Request $request, Segment $segment)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:segments,name,' . $segment->id,
        ], [
            'name.unique' => 'Nama segment ini sudah digunakan.',
            'name.required' => 'Nama segment wajib diisi.',
        ]);

        $segment->update([
            'name' => trim($request->name),
        ]);

        return redirect()->back()->with('success', 'Segment berhasil diperbarui!');
    }

    public function destroySegment(Segment $segment)
    {
        $segment->delete();
        return redirect()->back()->with('success', 'Segment berhasil dihapus!');
    }

    private function ensureDefaultSeeded(): void
    {
        if (Regional::count() === 0) {
            $defaults = ['Regional 1', 'Regional 2', 'Regional 3', 'Regional 4'];
            foreach ($defaults as $name) {
                Regional::create(['name' => $name]);
            }
        }

        if (Segment::count() === 0) {
            $defaults = ['Enterprise', 'Corporate', 'Government', 'SME', 'Retail'];
            foreach ($defaults as $name) {
                Segment::create(['name' => $name]);
            }
        }
    }
}