<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Verifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class VerifikasiController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Verifikasi::with([
            'keluarga.dasawisma.kader.user',
            'keluarga.anggotaKeluargas',
            'admin.user',
        ])->latest();

        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('keluarga', function ($q) use ($search) {
                $q->where('no_kk', 'like', "%{$search}%")
                    ->orWhere('nama_kepala_keluarga', 'like', "%{$search}%");
            });
        }

        $verifikasis = $query->paginate(15)->withQueryString();

        return Inertia::render('Admin/Verifikasi/Index', [
            'verifikasis'   => $verifikasis,
            'status_counts' => [
                'semua'    => Verifikasi::count(),
                'menunggu' => Verifikasi::where('status_verifikasi', 'menunggu')->count(),
                'disetujui' => Verifikasi::where('status_verifikasi', 'disetujui')->count(),
                'ditolak'  => Verifikasi::where('status_verifikasi', 'ditolak')->count(),
            ],
            'filter_status' => $request->input('status', ''),
            'filter_search' => $request->input('search', ''),
        ]);
    }

    public function show(Verifikasi $verifikasi): Response
    {
        $verifikasi->load([
            'keluarga.dasawisma.kader.user',
            'keluarga.anggotaKeluargas',
            'admin.user',
        ]);

        return Inertia::render('Admin/Verifikasi/Show', [
            'verifikasi' => $verifikasi,
        ]);
    }

    public function approve(Request $request, Verifikasi $verifikasi): RedirectResponse
    {
        $request->validate([
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $admin = auth()->user()->admin;

        $verifikasi->update([
            'status_verifikasi'  => 'disetujui',
            'admin_id'           => $admin?->id,
            'tanggal_verifikasi' => Carbon::now()->toDateString(),
            'catatan'            => $request->catatan,
        ]);

        return redirect()->route('admin.verifikasi.index')
            ->with('success', 'Data KK berhasil disetujui.');
    }

    public function reject(Request $request, Verifikasi $verifikasi): RedirectResponse
    {
        $request->validate([
            'catatan' => ['required', 'string', 'max:500'],
        ], [
            'catatan.required' => 'Catatan penolakan wajib diisi.',
        ]);

        $admin = auth()->user()->admin;

        $verifikasi->update([
            'status_verifikasi'  => 'ditolak',
            'admin_id'           => $admin?->id,
            'tanggal_verifikasi' => Carbon::now()->toDateString(),
            'catatan'            => $request->catatan,
        ]);

        return redirect()->route('admin.verifikasi.index')
            ->with('success', 'Data KK ditolak dengan catatan.');
    }
}
