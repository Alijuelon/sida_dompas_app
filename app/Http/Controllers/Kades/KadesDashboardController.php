<?php

namespace App\Http\Controllers\Kades;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKeluarga;
use App\Models\Dasawisma;
use App\Models\Keluarga;
use App\Models\User;
use App\Models\Verifikasi;
use Inertia\Inertia;
use Inertia\Response;

class KadesDashboardController extends Controller
{
    public function index(): Response
    {
        $totalKK = Keluarga::whereHas('verifikasi', fn ($q) => $q->where('status_verifikasi', 'disetujui'))->count();
        $approvedIds = Keluarga::whereHas('verifikasi', fn ($q) => $q->where('status_verifikasi', 'disetujui'))->pluck('id');
        $totalWarga = AnggotaKeluarga::whereIn('keluarga_id', $approvedIds)->count();
        $totalDasawisma = Dasawisma::count();
        $totalKader = User::where('role', 'kader')->count();

        $dasawismaStats = Dasawisma::withCount(['keluargas' => function ($q) {
            $q->whereHas('verifikasi', fn ($q2) => $q2->where('status_verifikasi', 'disetujui'));
        }])->get();

        return Inertia::render('Kades/Dashboard', [
            'stats' => [
                'total_kk'        => $totalKK,
                'total_warga'     => $totalWarga,
                'total_dasawisma' => $totalDasawisma,
                'disetujui'       => $totalKK, // semua yg disetujui = total_kk
            ],
        ]);
    }
}
