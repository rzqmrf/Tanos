<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * List of mock user accounts.
     * You can easily add more accounts here!
     */
    private $mockUsers = [
        'rozaq' => [
            'password' => 'admin123',
            'name' => 'Muhammad Rozaq',
            'role' => 'Administrator'
        ],
        'stevec' => [
            'password' => 'supervisor123',
            'name' => 'Steve Connor',
            'role' => 'Supervisor'
        ],
        'user' => [
            'password' => 'user123',
            'name' => 'John Doe',
            'role' => 'Staff Member'
        ]
    ];

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (session()->has('user')) {
            return redirect()->route('dashboard.index');
        }

        return view('auth.login');
    }

    /**
     * Handle authentication attempt.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required'
        ]);

        $username = strtolower($request->input('username'));
        $password = $request->input('password');

        // Verify credentials against the mock accounts array
        if (array_key_exists($username, $this->mockUsers) && $this->mockUsers[$username]['password'] === $password) {
            $user = $this->mockUsers[$username];
            
            session([
                'user' => [
                    'name' => $user['name'],
                    'username' => $username,
                    'role' => $user['role']
                ]
            ]);

            return redirect()->route('dashboard.index');
        }

        // Return back with error
        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.'
        ])->withInput($request->only('username'));
    }

    /**
     * Handle logout.
     */
    public function logout()
    {
        session()->forget('user');
        session()->flush();

        return redirect()->route('login');
    }
}
