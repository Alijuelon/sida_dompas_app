<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Dasawisma;
use App\Models\Keluarga;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DasawismaController extends Controller
{
    private function getKader()
    {
        return auth()->user()->kader;
    }

    public function index(): Response
    {
        $user = auth()->user();
        if ($user->isAdmin()) {
            $dasawismas = Dasawisma::with('kader.user')->withCount('keluargas')->latest()->get();
            $kaders = \App\Models\Kader::with('user')->get();
        } else {
            $kader = $this->getKader();
            $dasawismas = $kader
                ? Dasawisma::where('kader_id', $kader->id)
                    ->with('kader.user')
                    ->withCount('keluargas')
                    ->latest()
                    ->get()
                : collect();
            $kaders = [];
        }

        return Inertia::render('Kader/Dasawisma/Index', [
            'dasawismas' => $dasawismas,
            'kaders' => $kaders,
        ]);
    }

    /**
     * Menampilkan detail Dasawisma beserta daftar KK.
     * Kader bisa melihat detail + daftar KK + pencarian.
     */
    public function show(Request $request, Dasawisma $dasawisma): Response
    {
        $this->authorizeKader($dasawisma);

        $dasawisma->load('kader.user');

        // Query keluargas with search
        $search = $request->input('search', '');
        $keluargasQuery = Keluarga::where('dasawisma_id', $dasawisma->id)
            ->withCount('anggotaKeluargas');

        if ($search) {
            $keluargasQuery->where(function ($q) use ($search) {
                $q->where('nama_kepala_keluarga', 'like', "%{$search}%")
                  ->orWhere('no_kk', 'like', "%{$search}%")
                  ->orWhere('rt', 'like', "%{$search}%")
                  ->orWhere('rw', 'like', "%{$search}%");
            });
        }

        $keluargas = $keluargasQuery->latest()->paginate(10)->withQueryString();

        // Stats
        $totalKK = Keluarga::where('dasawisma_id', $dasawisma->id)->count();
        $totalAnggota = \App\Models\AnggotaKeluarga::whereHas('keluarga', function ($q) use ($dasawisma) {
            $q->where('dasawisma_id', $dasawisma->id);
        })->count();

        return Inertia::render('Kader/Dasawisma/Show', [
            'dasawisma' => $dasawisma,
            'keluargas' => $keluargas,
            'filters' => ['search' => $search],
            'stats' => [
                'total_kk' => $totalKK,
                'total_anggota' => $totalAnggota,
            ],
        ]);
    }

    public function create(): Response
    {
        $this->authorizeAdmin();

        return Inertia::render('Kader/Dasawisma/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin();

        $rules = [
            'nama_dasawisma' => 'required|string|max:255',
            'rt' => 'required|string|max:10',
            'rw' => 'required|string|max:10',
            'desa' => 'required|string|max:255',
        ];

        $user = auth()->user();
        if ($user->isAdmin()) {
            $rules['kader_id'] = 'required|exists:kaders,id';
        }

        $validated = $request->validate($rules);

        Dasawisma::create($validated);

        return redirect()->back()->with('success', 'Dasawisma berhasil ditambahkan.');
    }

    public function edit(Dasawisma $dasawisma): Response
    {
        $this->authorizeAdmin();

        return Inertia::render('Kader/Dasawisma/Edit', [
            'dasawisma' => $dasawisma,
        ]);
    }

    public function update(Request $request, Dasawisma $dasawisma): RedirectResponse
    {
        $this->authorizeAdmin();

        $rules = [
            'nama_dasawisma' => 'required|string|max:255',
            'rt' => 'required|string|max:10',
            'rw' => 'required|string|max:10',
            'desa' => 'required|string|max:255',
        ];

        if (auth()->user()->isAdmin()) {
            $rules['kader_id'] = 'required|exists:kaders,id';
        }

        $validated = $request->validate($rules);

        $dasawisma->update($validated);

        return redirect()->back()->with('success', 'Dasawisma berhasil diperbarui.');
    }

    public function destroy(Dasawisma $dasawisma): RedirectResponse
    {
        $this->authorizeAdmin();
        $dasawisma->delete();

        return redirect()->back()->with('success', 'Dasawisma berhasil dihapus.');
    }

    /**
     * Memastikan hanya Admin yang bisa mengakses CRUD Dasawisma.
     */
    private function authorizeAdmin(): void
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Hanya Admin yang dapat mengelola data Dasawisma.');
        }
    }

    /**
     * Memastikan kader hanya bisa melihat dasawisma miliknya.
     * Admin bisa lihat semua.
     */
    private function authorizeKader(Dasawisma $dasawisma): void
    {
        if (auth()->user()->isAdmin()) {
            return;
        }

        $kader = $this->getKader();
        if (! $kader || $dasawisma->kader_id !== $kader->id) {
            abort(403);
        }
    }
}
