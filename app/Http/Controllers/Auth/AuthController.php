<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        if (session()->has('user')) {
            return redirect()->route('dashboard.index');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required'
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        $dbUser = User::where('email', $username)->orWhere('name', $username)->first();

        // auth
        if ($dbUser && Hash::check($password, $dbUser->password)) {
            session([
                'user' => [
                    'name' => $dbUser->name,
                    'username' => $dbUser->email,
                    'role' => 'Staff Member' // role default user baru
                ]
            ]);

            return redirect()->route('dashboard.index');
        }

        // redirect
        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah!'
        ])->withInput($request->only('username'));
    }


    // logika login
    public function showRegisterForm()
    {
        if (session()->has('user')) {
            return redirect()->route('dashboard.index');
        }
        return view('auth.register');
    }

    // regist
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    // logout
    public function logout()
    {
        session()->forget('user');
        session()->flush();

        return redirect()->route('login');
    }
}