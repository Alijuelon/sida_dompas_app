<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsKades
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || $request->user()->role !== 'kades') {
            abort(403, 'Akses ditolak. Hanya Kepala Desa yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
