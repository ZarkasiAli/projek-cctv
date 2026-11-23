<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login')->withErrors([
                'auth_error' => 'Silakan login terlebih dahulu sebagai Super Admin untuk mengakses halaman Kelola User.',
            ]);
        }

        if (! Auth::user()->isSuperAdmin()) {
            abort(403, 'Akses ditolak. Fitur Kelola User hanya dapat diakses oleh role Super Admin.');
        }

        return $next($request);
    }
}
