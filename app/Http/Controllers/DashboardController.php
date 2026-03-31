<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): RedirectResponse
    {
        $user = $request->user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'kader' => redirect()->route('kader.dashboard'),
            'kades' => redirect()->route('kades.dashboard'),
            default => redirect()->route('home'),
        };
    }
}
