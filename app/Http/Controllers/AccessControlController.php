<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccessControlController extends Controller
{
    public function index() {
        // Tampilkan daftar role permission bawaan aplikasi
        return view('settings.access-controls');
    }

    public function store(Request $request) {
        // Logika atur hak akses user/role
        return redirect()->back()->with('success', 'Hak akses berhasil diubah!');
    }
}