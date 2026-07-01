<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsKader
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, ['kader', 'admin'])) {
            abort(403, 'Akses ditolak. Hanya Kader Dasawisma dan Admin yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}
