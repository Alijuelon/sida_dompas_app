<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsKader
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || $request->user()->role !== 'kader') {
            abort(403, 'Akses ditolak. Hanya Kader Dasawisma yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
