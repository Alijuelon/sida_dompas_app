<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Dasawisma;
use App\Models\Keluarga;
use App\Models\User;
use App\Models\Verifikasi;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $filter = $request->query('filter', 'harian');

        $totalKK = Keluarga::count();
        $totalWarga = \App\Models\AnggotaKeluarga::count();
        $totalDasawisma = Dasawisma::count();
        $totalKader = User::where('role', 'kader')->count();

        $pendingVerifikasi = Verifikasi::where('status_verifikasi', 'menunggu')->count();
        $disetujuiVerifikasi = Verifikasi::where('status_verifikasi', 'disetujui')->count();
        $ditolakVerifikasi = Verifikasi::where('status_verifikasi', 'ditolak')->count();

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
            'chart_data' => [
                'labels' => $this->getChartLabels($filter),
                'total_kk' => $this->getTimeSeriesData(Keluarga::query(), $filter),
                'total_warga' => $this->getTimeSeriesData(\App\Models\AnggotaKeluarga::query(), $filter),
                'menunggu' => $this->getTimeSeriesData(Verifikasi::query()->where('status_verifikasi', 'menunggu'), $filter),
                'total_dasawisma' => $this->getTimeSeriesData(Dasawisma::query(), $filter),
                'filter' => $filter,
            ],
            'verifikasi_terbaru' => $recentVerifikasi,
        ]);
    }

    private function getTimeSeriesData($query, $filter, $dateColumn = 'created_at')
    {
        if ($filter === 'harian') {
            $data = (clone $query)->where($dateColumn, '>=', now()->subDays(6)->startOfDay())
                ->selectRaw("DATE($dateColumn) as date, count(*) as count")
                ->groupBy('date')
                ->pluck('count', 'date');

            $result = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $result[] = $data[$date] ?? 0;
            }
            return $result;
        }
        
        if ($filter === 'bulanan') {
            $data = (clone $query)->where($dateColumn, '>=', now()->subMonths(11)->startOfMonth())
                ->selectRaw("DATE_FORMAT($dateColumn, '%Y-%m') as date, count(*) as count")
                ->groupBy('date')
                ->pluck('count', 'date');
                
            $result = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i)->format('Y-m');
                $result[] = $data[$date] ?? 0;
            }
            return $result;
        }
        
        if ($filter === 'tahunan') {
            $data = (clone $query)->where($dateColumn, '>=', now()->subYears(4)->startOfYear())
                ->selectRaw("YEAR($dateColumn) as date, count(*) as count")
                ->groupBy('date')
                ->pluck('count', 'date');
                
            $result = [];
            for ($i = 4; $i >= 0; $i--) {
                $date = now()->subYears($i)->format('Y');
                $result[] = $data[$date] ?? 0;
            }
            return $result;
        }
        
        return [];
    }

    private function getChartLabels($filter)
    {
        $labels = [];
        if ($filter === 'harian') {
            for ($i = 6; $i >= 0; $i--) {
                $labels[] = now()->subDays($i)->isoFormat('D MMM');
            }
        } elseif ($filter === 'bulanan') {
            for ($i = 11; $i >= 0; $i--) {
                $labels[] = now()->subMonths($i)->isoFormat('MMM YYYY');
            }
        } elseif ($filter === 'tahunan') {
            for ($i = 4; $i >= 0; $i--) {
                $labels[] = now()->subYears($i)->format('Y');
            }
        }
        return $labels;
    }
}
