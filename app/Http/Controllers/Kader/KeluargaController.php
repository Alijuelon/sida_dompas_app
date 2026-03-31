<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Http\Requests\KeluargaRequest;
use App\Models\Dasawisma;
use App\Models\Keluarga;
use App\Models\Verifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class KeluargaController extends Controller
{
    private function getKader()
    {
        return auth()->user()->kader;
    }

    private function getDasawismaIds(): \Illuminate\Support\Collection
    {
        $kader = $this->getKader();

        return $kader ? $kader->dasawismas()->pluck('id') : collect();
    }

    public function index(Request $request): Response
    {
        $dasawismaIds = $this->getDasawismaIds();

        $query = Keluarga::whereIn('dasawisma_id', $dasawismaIds)
            ->with(['dasawisma', 'verifikasi'])
            ->withCount('anggotaKeluargas');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_kk', 'like', "%{$search}%")
                    ->orWhere('nama_kepala_keluarga', 'like', "%{$search}%");
            });
        }

        if ($request->filled('dasawisma_id')) {
            $query->where('dasawisma_id', $request->dasawisma_id);
        }

        if ($request->filled('status')) {
            $query->whereHas('verifikasi', function ($q) use ($request) {
                $q->where('status_verifikasi', $request->status);
            });
        }

        $keluargas  = $query->latest()->paginate(10)->withQueryString();
        $dasawismas = Dasawisma::whereIn('id', $dasawismaIds)->orderBy('nama_dasawisma')->get();

        return Inertia::render('Kader/Keluarga/Index', [
            'keluargas'  => $keluargas,
            'dasawismas' => $dasawismas,
            'filters'    => $request->only('search', 'dasawisma_id', 'status'),
        ]);
    }

    public function create(): Response
    {
        $kader      = $this->getKader();
        $dasawismas = $kader ? $kader->dasawismas()->orderBy('nama_dasawisma')->get() : collect();

        return Inertia::render('Kader/Keluarga/Create', [
            'dasawismas' => $dasawismas,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'dasawisma_id'          => ['required', 'exists:dasawismas,id'],
            'no_kk'                 => ['required', 'string', 'size:16', 'unique:keluargas,no_kk'],
            'nama_kepala_keluarga'  => ['required', 'string', 'max:255'],
            'anggota'               => ['required', 'array', 'min:1'],
            'anggota.*.nik'         => ['required', 'string', 'size:16', 'distinct', 'unique:anggota_keluargas,nik'],
            'anggota.*.nama_anggota'=> ['required', 'string', 'max:255'],
            'anggota.*.jenis_kelamin'       => ['required', 'in:L,P'],
            'anggota.*.tanggal_lahir'       => ['required', 'date'],
            'anggota.*.agama'               => ['nullable', 'string', 'max:50'],
            'anggota.*.pendidikan'          => ['nullable', 'string', 'max:100'],
            'anggota.*.pekerjaan'           => ['nullable', 'string', 'max:100'],
            'anggota.*.status_dalam_keluarga' => ['nullable', 'string', 'max:50'],
            'anggota.*.status_perkawinan'   => ['nullable', 'string', 'max:50'],
        ], [
            'no_kk.size'        => 'Nomor KK harus 16 digit.',
            'no_kk.unique'      => 'Nomor KK sudah terdaftar.',
            'anggota.*.nik.size'=> 'NIK harus 16 digit.',
            'anggota.*.nik.unique'   => 'NIK sudah terdaftar.',
            'anggota.*.nik.distinct' => 'NIK anggota tidak boleh sama.',
            'anggota.min'       => 'Minimal harus ada 1 anggota keluarga.',
        ]);

        // Pastikan dasawisma milik kader login
        $kader     = $this->getKader();
        $dasawisma = Dasawisma::findOrFail($validated['dasawisma_id']);
        if (! $kader || $dasawisma->kader_id !== $kader->id) {
            abort(403, 'Anda tidak memiliki akses ke Dasawisma ini.');
        }

        DB::transaction(function () use ($validated) {
            $keluarga = Keluarga::create([
                'dasawisma_id'         => $validated['dasawisma_id'],
                'no_kk'                => $validated['no_kk'],
                'nama_kepala_keluarga' => $validated['nama_kepala_keluarga'],
                'jumlah_anggota'       => count($validated['anggota']),
            ]);

            foreach ($validated['anggota'] as $anggotaData) {
                $keluarga->anggotaKeluargas()->create($anggotaData);
            }

            // Otomatis buat record verifikasi
            Verifikasi::create([
                'keluarga_id'       => $keluarga->id,
                'status_verifikasi' => 'menunggu',
            ]);
        });

        return redirect()->route('kader.keluarga.index')
            ->with('success', 'Data KK berhasil disimpan dan menunggu verifikasi admin.');
    }

    public function show(Keluarga $keluarga): Response
    {
        $this->authorizeKader($keluarga);
        $keluarga->load(['dasawisma', 'anggotaKeluargas', 'verifikasi.admin.user']);

        return Inertia::render('Kader/Keluarga/Show', [
            'keluarga' => $keluarga,
        ]);
    }

    public function edit(Keluarga $keluarga): Response
    {
        $this->authorizeKader($keluarga);
        $kader = $this->getKader();
        $keluarga->load('anggotaKeluargas');

        return Inertia::render('Kader/Keluarga/Edit', [
            'keluarga'   => $keluarga,
            'dasawismas' => $kader->dasawismas()->orderBy('nama_dasawisma')->get(),
        ]);
    }

    public function update(KeluargaRequest $request, Keluarga $keluarga): RedirectResponse
    {
        $this->authorizeKader($keluarga);

        // Pastikan dasawisma target milik kader
        $kader     = $this->getKader();
        $dasawisma = Dasawisma::findOrFail($request->dasawisma_id);
        if ($dasawisma->kader_id !== $kader->id) {
            abort(403, 'Anda tidak memiliki akses ke Dasawisma tujuan.');
        }

        $keluarga->update($request->validated());

        // Reset verifikasi ke menunggu jika data diubah
        if ($keluarga->verifikasi) {
            $keluarga->verifikasi->update([
                'status_verifikasi' => 'menunggu',
                'admin_id'          => null,
                'tanggal_verifikasi'=> null,
                'catatan'           => null,
            ]);
        }

        return redirect()->route('kader.keluarga.show', $keluarga)
            ->with('success', 'Data KK berhasil diperbarui. Status verifikasi direset ke Menunggu.');
    }

    public function destroy(Keluarga $keluarga): RedirectResponse
    {
        $this->authorizeKader($keluarga);
        $keluarga->delete();

        return redirect()->route('kader.keluarga.index')
            ->with('success', 'Data KK dan semua anggota berhasil dihapus.');
    }

    public function statusVerifikasi(Request $request): Response
    {
        $dasawismaIds = $this->getDasawismaIds();
        $keluargaIds  = Keluarga::whereIn('dasawisma_id', $dasawismaIds)->pluck('id');

        $query = Verifikasi::whereIn('keluarga_id', $keluargaIds)
            ->with(['keluarga.dasawisma', 'admin.user'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        $verifikasis = $query->paginate(15)->withQueryString();

        return Inertia::render('Kader/StatusVerifikasi', [
            'verifikasis' => $verifikasis,
            'filters'     => $request->only('status'),
        ]);
    }

    private function authorizeKader(Keluarga $keluarga): void
    {
        $dasawismaIds = $this->getDasawismaIds()->toArray();
        if (! $this->getKader() || ! in_array($keluarga->dasawisma_id, $dasawismaIds)) {
            abort(403, 'Anda tidak memiliki akses ke data KK ini.');
        }
    }
}
