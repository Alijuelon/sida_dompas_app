<?php

namespace App\Ai\Tools;

use App\Models\Keluarga;
use Laravel\Ai\Attributes\Tool;

class GetDataKKTool
{
    /**
     * Cari data Kartu Keluarga berdasarkan nama kepala keluarga atau nomor KK.
     * Gunakan tool ini ketika user ingin mencari atau mengetahui data KK tertentu.
     *
     * @param  string  $keyword  Kata kunci pencarian (nama atau nomor KK)
     */
    #[Tool]
    public function cariDataKK(string $keyword): array
    {
        $results = Keluarga::with(['dasawisma', 'verifikasi'])
            ->withCount('anggotaKeluargas')
            ->where('nama_kepala_keluarga', 'like', "%{$keyword}%")
            ->orWhere('no_kk', 'like', "%{$keyword}%")
            ->limit(5)
            ->get()
            ->map(fn ($kk) => [
                'no_kk'                => $kk->no_kk,
                'nama_kepala_keluarga' => $kk->nama_kepala_keluarga,
                'dasawisma'            => $kk->dasawisma?->nama_dasawisma,
                'jumlah_anggota'       => $kk->anggota_keluargas_count,
                'status_verifikasi'    => $kk->verifikasi?->status_verifikasi ?? 'belum',
            ])
            ->toArray();

        return [
            'hasil' => $results,
            'total' => count($results),
        ];
    }
}
