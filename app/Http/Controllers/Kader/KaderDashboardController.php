<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\AnggotaKeluarga;
use App\Models\Dasawisma;
use App\Models\Keluarga;
use App\Models\Verifikasi;
use Inertia\Inertia;
use Inertia\Response;

class KaderDashboardController extends Controller
{
    public function index(): Response
    {
        $kader = auth()->user()->kader;

        $dasawismaIds = $kader ? $kader->dasawismas()->pluck('id') : collect();

        $totalKK = Keluarga::whereIn('dasawisma_id', $dasawismaIds)->count();
        $totalWarga = AnggotaKeluarga::whereHas('keluarga', function ($q) use ($dasawismaIds) {
            $q->whereIn('dasawisma_id', $dasawismaIds);
        })->count();

        $keluargaIds = Keluarga::whereIn('dasawisma_id', $dasawismaIds)->pluck('id');

        $pendingCount = Verifikasi::whereIn('keluarga_id', $keluargaIds)->where('status_verifikasi', 'menunggu')->count();
        $disetujuiCount = Verifikasi::whereIn('keluarga_id', $keluargaIds)->where('status_verifikasi', 'disetujui')->count();
        $ditolakCount = Verifikasi::whereIn('keluarga_id', $keluargaIds)->where('status_verifikasi', 'ditolak')->count();

        $recentKK = Keluarga::whereIn('dasawisma_id', $dasawismaIds)
            ->with(['dasawisma', 'verifikasi'])
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('Kader/Dashboard', [
            'kader' => $kader,
            'stats' => [
                'total_kk'      => $totalKK,
                'total_warga'   => $totalWarga,
                'menunggu'      => $pendingCount,
                'disetujui'     => $disetujuiCount,
                'ditolak'       => $ditolakCount,
                'total_dasawisma' => $dasawismaIds->count(),
            ],
            'keluarga_terbaru' => $recentKK,
        ]);
    }
}
