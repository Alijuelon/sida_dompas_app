<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Dasawisma;
use App\Models\Keluarga;
use App\Models\User;
use App\Models\Verifikasi;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    public function index(): Response
    {
        $totalKK = Keluarga::count();
        $totalWarga = \App\Models\AnggotaKeluarga::count();
        $totalDasawisma = Dasawisma::count();
        $totalKader = User::where('role', 'kader')->count();

        $pendingVerifikasi = Verifikasi::where('status_verifikasi', 'menunggu')->count();
        $disetujuiVerifikasi = Verifikasi::where('status_verifikasi', 'disetujui')->count();
        $ditolakVerifikasi = Verifikasi::where('status_verifikasi', 'ditolak')->count();

        // Statistik per dasawisma
        $dasawismaStats = Dasawisma::withCount('keluargas')->get();

        $recentVerifikasi = Verifikasi::with(['keluarga.dasawisma', 'admin.user'])
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'total_kk'         => $totalKK,
                'total_warga'      => $totalWarga,
                'total_dasawisma'  => $totalDasawisma,
                'total_kader'      => $totalKader,
                'menunggu'         => $pendingVerifikasi,
                'disetujui'        => $disetujuiVerifikasi,
                'ditolak'          => $ditolakVerifikasi,
            ],
            'verifikasi_terbaru' => $recentVerifikasi,
        ]);
    }
}
