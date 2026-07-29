<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\RolePermission;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $role = 'Employee';
        if (session()->has('user')) {
            $dbUser = \App\Models\User::find(session('user.id'));
            if ($dbUser) {
                $role = $dbUser->role;
            }
        } else {
            return redirect()->route('login')->withErrors(['username' => 'Silakan masuk terlebih dahulu.']);
        }

        // Check permission in matrix
        if (!RolePermission::hasPermission($role, $permission)) {
            abort(403, 'Anda tidak memiliki hak akses untuk halaman ini.');
        }

        return $next($request);
    }
}
