<?php

namespace App\Ai\Tools;

use App\Models\AnggotaKeluarga;
use App\Models\Dasawisma;
use App\Models\Keluarga;
use App\Models\Verifikasi;
use Laravel\Ai\Attributes\Tool;

class GetStatistikTool
{
    /**
     * Ambil statistik data populasi SIDA-Dompas.
     * Gunakan tool ini ketika user bertanya tentang jumlah data, statistik, atau ringkasan.
     */
    #[Tool]
    public function getStatistik(): array
    {
        $totalKK       = Keluarga::count();
        $totalWarga    = AnggotaKeluarga::count();
        $totalDasawisma= Dasawisma::count();
        $menunggu      = Verifikasi::where('status_verifikasi', 'menunggu')->count();
        $disetujui     = Verifikasi::where('status_verifikasi', 'disetujui')->count();
        $ditolak       = Verifikasi::where('status_verifikasi', 'ditolak')->count();

        return [
            'total_kk'        => $totalKK,
            'total_warga'     => $totalWarga,
            'total_dasawisma' => $totalDasawisma,
            'verifikasi'      => [
                'menunggu'  => $menunggu,
                'disetujui' => $disetujui,
                'ditolak'   => $ditolak,
            ],
        ];
    }
}
