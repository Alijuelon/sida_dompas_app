<?php

namespace App\Http\Controllers;

use App\Models\AnggotaKeluarga;
use App\Models\Dasawisma;
use App\Models\Keluarga;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LaporanController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Keluarga::whereHas('verifikasi', function ($q) {
            $q->where('status_verifikasi', 'disetujui');
        })->with(['dasawisma', 'anggotaKeluargas', 'verifikasi.admin.user']);

        if ($request->filled('rt')) {
            $query->whereHas('dasawisma', fn ($q) => $q->where('rt', $request->rt));
        }

        if ($request->filled('rw')) {
            $query->whereHas('dasawisma', fn ($q) => $q->where('rw', $request->rw));
        }

        if ($request->filled('dasawisma_id')) {
            $query->where('dasawisma_id', $request->dasawisma_id);
        }

        // Gunakan get() agar Vue mudah melakukan search/pagination frontend
        $keluargas = $query->get();

        // Statistik warga (Database Agnostic)
        $approvedIds = $keluargas->pluck('id');

        $totalWarga = AnggotaKeluarga::whereIn('keluarga_id', $approvedIds)->count();

        $limaTahunLalu = \Carbon\Carbon::now()->subYears(5)->format('Y-m-d');
        $enamPuluhTahunLalu = \Carbon\Carbon::now()->subYears(60)->format('Y-m-d');

        $balitaLaki = AnggotaKeluarga::whereIn('keluarga_id', $approvedIds)
            ->whereDate('tanggal_lahir', '>', $limaTahunLalu)
            ->where('jenis_kelamin', 'L')
            ->count();

        $balitaPerempuan = AnggotaKeluarga::whereIn('keluarga_id', $approvedIds)
            ->whereDate('tanggal_lahir', '>', $limaTahunLalu)
            ->where('jenis_kelamin', 'P')
            ->count();

        $lansia = AnggotaKeluarga::whereIn('keluarga_id', $approvedIds)
            ->whereDate('tanggal_lahir', '<=', $enamPuluhTahunLalu)
            ->count();

        $lakiLaki = AnggotaKeluarga::whereIn('keluarga_id', $approvedIds)
            ->where('jenis_kelamin', 'L')->count();

        $perempuan = AnggotaKeluarga::whereIn('keluarga_id', $approvedIds)
            ->where('jenis_kelamin', 'P')->count();

        $dasawismas = Dasawisma::all();
        $rtList = Dasawisma::distinct()->pluck('rt')->filter()->values();
        $rwList = Dasawisma::distinct()->pluck('rw')->filter()->values();

        return Inertia::render('Laporan/Index', [
            'keluargas'  => $keluargas,
            'stats'      => [
                'total_kk'        => $approvedIds->count(),
                'total_warga'     => $totalWarga,
                'balita_laki'     => $balitaLaki,
                'balita_perempuan'=> $balitaPerempuan,
                'lansia'          => $lansia,
                'laki_laki'       => $lakiLaki,
                'perempuan'       => $perempuan,
            ],
            'dasawismas'    => $dasawismas,
            'rt_list'       => $rtList,
            'rw_list'       => $rwList,
            'active_filters' => $request->only('rt', 'rw', 'dasawisma_id'),
        ]);
    }

    public function downloadPdf(Request $request)
    {
        $query = Keluarga::whereHas('verifikasi', function ($q) {
            $q->where('status_verifikasi', 'disetujui');
        })->with(['dasawisma', 'anggotaKeluargas', 'verifikasi.admin.user']);

        if ($request->filled('rt')) {
            $query->whereHas('dasawisma', fn ($q) => $q->where('rt', $request->rt));
        }

        if ($request->filled('rw')) {
            $query->whereHas('dasawisma', fn ($q) => $q->where('rw', $request->rw));
        }

        if ($request->filled('dasawisma_id')) {
            $query->where('dasawisma_id', $request->dasawisma_id);
        }

        $keluargas = $query->get();
        $approvedIds = $keluargas->pluck('id');

        $totalWarga = AnggotaKeluarga::whereIn('keluarga_id', $approvedIds)->count();

        $limaTahunLalu = \Carbon\Carbon::now()->subYears(5)->format('Y-m-d');
        $enamPuluhTahunLalu = \Carbon\Carbon::now()->subYears(60)->format('Y-m-d');

        $balitaLaki = AnggotaKeluarga::whereIn('keluarga_id', $approvedIds)
            ->whereDate('tanggal_lahir', '>', $limaTahunLalu)
            ->where('jenis_kelamin', 'L')
            ->count();

        $balitaPerempuan = AnggotaKeluarga::whereIn('keluarga_id', $approvedIds)
            ->whereDate('tanggal_lahir', '>', $limaTahunLalu)
            ->where('jenis_kelamin', 'P')
            ->count();

        $lansia = AnggotaKeluarga::whereIn('keluarga_id', $approvedIds)
            ->whereDate('tanggal_lahir', '<=', $enamPuluhTahunLalu)
            ->count();

        $lakiLaki = AnggotaKeluarga::whereIn('keluarga_id', $approvedIds)
            ->where('jenis_kelamin', 'L')->count();

        $perempuan = AnggotaKeluarga::whereIn('keluarga_id', $approvedIds)
            ->where('jenis_kelamin', 'P')->count();

        $stats = [
            'total_kk'        => $approvedIds->count(),
            'total_warga'     => $totalWarga,
            'balita_laki'     => $balitaLaki,
            'balita_perempuan'=> $balitaPerempuan,
            'lansia'          => $lansia,
            'laki_laki'       => $lakiLaki,
            'perempuan'       => $perempuan,
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('laporan.pdf', compact('keluargas', 'stats'));
        
        return $pdf->download('laporan-rekap.pdf');
    }
}
