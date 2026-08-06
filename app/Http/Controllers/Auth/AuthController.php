<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

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

        $throttleKey = 'login:' . $request->ip();

        // Rate limit check: block if too many attempts
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return back()->withErrors([
                'username' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . ceil($seconds / 60) . ' menit.'
            ]);
        }

        $username = $request->input('username');
        $password = $request->input('password');

        $dbUser = User::where('username', $username)->orWhere('email', $username)->first();

        // auth
        if ($dbUser && Hash::check($password, $dbUser->password)) {
            RateLimiter::clear($throttleKey);

            session([
                'user' => [
                    'id' => $dbUser->id,
                    'name' => $dbUser->name,
                    'username' => $dbUser->username,
                    'role' => $dbUser->role ?? 'Employee',
                    'employee_id' => $dbUser->employee_id,
                ]
            ]);

            return redirect()->route('dashboard.index');
        }

        // failed login: increment the attempt counter
        RateLimiter::hit($throttleKey);

        // redirect
        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah!'
        ])->withInput($request->only('username'));
    }


    // logout
    public function logout()
    {
        session()->forget('user');
        session()->flush();

        return redirect()->route('login');
    }
}