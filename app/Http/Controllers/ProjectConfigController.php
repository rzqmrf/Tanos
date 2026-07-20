<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectConfigController extends Controller
{
    public function index() {
        // Karena ini config halaman setelan, lempar data statis atau setting row database
        return view('dashboard.project-config');
    }
    
    public function store(Request $request) {
        // Logika simpan konfigurasi threshold project
        return redirect()->back()->with('success', 'Konfigurasi project disimpan!');
    }
}