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
        if (auth()->user()->isAdmin()) {
            return \App\Models\Dasawisma::pluck('id');
        }
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

    public function create()
    {
        if (auth()->user()->isAdmin()) {
            $dasawismas = Dasawisma::orderBy('nama_dasawisma')->get();
        } else {
            $kader      = $this->getKader();
            $dasawismas = $kader ? $kader->dasawismas()->orderBy('nama_dasawisma')->get() : collect();
        }

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
            'rt'                    => ['nullable', 'string', 'max:10'],
            'rw'                    => ['nullable', 'string', 'max:10'],
            'dusun_lingkungan'      => ['nullable', 'string', 'max:255'],
            'desa'                  => ['nullable', 'string', 'max:255'],
            'kecamatan'             => ['nullable', 'string', 'max:255'],
            'kabupaten'             => ['nullable', 'string', 'max:255'],
            'provinsi'              => ['nullable', 'string', 'max:255'],
            'jumlah_kk'             => ['nullable', 'integer', 'min:0'],
            'jumlah_laki_laki'      => ['nullable', 'integer', 'min:0'],
            'jumlah_perempuan'      => ['nullable', 'integer', 'min:0'],
            'jumlah_balita'         => ['nullable', 'integer', 'min:0'],
            'jumlah_pus'            => ['nullable', 'integer', 'min:0'],
            'jumlah_wus'            => ['nullable', 'integer', 'min:0'],
            'jumlah_buta'           => ['nullable', 'integer', 'min:0'],
            'jumlah_ibu_hamil'      => ['nullable', 'integer', 'min:0'],
            'jumlah_ibu_menyusui'   => ['nullable', 'integer', 'min:0'],
            'jumlah_lansia'         => ['nullable', 'integer', 'min:0'],
            'anggota'               => ['required', 'array', 'min:1'],
            'anggota.*.no_reg'      => ['nullable', 'string', 'max:50'],
            'anggota.*.nik'         => ['required', 'string', 'size:16', 'distinct', 'unique:anggota_keluargas,nik'],
            'anggota.*.nama_anggota'=> ['required', 'string', 'max:255'],
            'anggota.*.jenis_kelamin'       => ['required', 'in:L,P'],
            'anggota.*.tanggal_lahir'       => ['required', 'date'],
            'anggota.*.agama'               => ['nullable', 'string', 'max:50'],
            'anggota.*.pendidikan'          => ['nullable', 'string', 'max:100'],
            'anggota.*.pekerjaan'           => ['nullable', 'string', 'max:100'],
            'anggota.*.status_dalam_keluarga' => ['nullable', 'string', 'max:50'],
            'anggota.*.status_perkawinan'   => ['nullable', 'string', 'max:50'],
            'anggota.*.dasa_wisma'          => ['nullable', 'string', 'max:255'],
            'anggota.*.nama_kepala_rumah_tangga' => ['nullable', 'string', 'max:255'],
            'anggota.*.jabatan'             => ['nullable', 'string', 'max:255'],
            'anggota.*.tempat_lahir'        => ['nullable', 'string', 'max:255'],
            'anggota.*.umur'                => ['nullable', 'integer', 'min:0'],
            'anggota.*.alamat_jalan'        => ['nullable', 'string', 'max:255'],
            'anggota.*.rt'                  => ['nullable', 'string', 'max:10'],
            'anggota.*.rw'                  => ['nullable', 'string', 'max:10'],
            'anggota.*.desa_kelurahan'      => ['nullable', 'string', 'max:255'],
            'anggota.*.kecamatan'           => ['nullable', 'string', 'max:255'],
            'anggota.*.kabupaten_kota'      => ['nullable', 'string', 'max:255'],
            'anggota.*.provinsi'            => ['nullable', 'string', 'max:255'],
            'anggota.*.pendidikan_terakhir' => ['nullable', 'string', 'max:255'],
            'anggota.*.pekerjaan_utama'     => ['nullable', 'string', 'max:255'],
            'anggota.*.akseptor_kb'         => ['nullable', 'boolean'],
            'anggota.*.jenis_akseptor_kb'   => ['nullable', 'string', 'max:255'],
            'anggota.*.aktif_posyandu'      => ['nullable', 'boolean'],
            'anggota.*.frekuensi_posyandu'  => ['nullable', 'string', 'max:255'],
            'anggota.*.ikut_bina_keluarga_balita' => ['nullable', 'boolean'],
            'anggota.*.memiliki_tabungan'   => ['nullable', 'boolean'],
            'anggota.*.ikut_kelompok_belajar'=> ['nullable', 'boolean'],
            'anggota.*.jenis_paket_belajar' => ['nullable', 'string', 'max:255'],
            'anggota.*.ikut_paud_sejenis'   => ['nullable', 'boolean'],
            'anggota.*.ikut_koperasi'       => ['nullable', 'boolean'],
            'anggota.*.jenis_koperasi'      => ['nullable', 'string', 'max:255'],
        ], [
            'no_kk.size'        => 'Nomor KK harus 16 digit.',
            'no_kk.unique'      => 'Nomor KK sudah terdaftar.',
            'anggota.*.nik.size'=> 'NIK harus 16 digit.',
            'anggota.*.nik.unique'   => 'NIK sudah terdaftar.',
            'anggota.*.nik.distinct' => 'NIK anggota tidak boleh sama.',
            'anggota.min'       => 'Minimal harus ada 1 anggota keluarga.',
        ]);

        // Pastikan dasawisma milik kader login
        $dasawisma = Dasawisma::findOrFail($validated['dasawisma_id']);
        if (! auth()->user()->isAdmin()) {
            $kader     = $this->getKader();
            if (! $kader || $dasawisma->kader_id !== $kader->id) {
                abort(403, 'Anda tidak memiliki akses ke Dasawisma ini.');
            }
        }

        DB::transaction(function () use ($validated) {
            $keluarga = Keluarga::create([
                'dasawisma_id'         => $validated['dasawisma_id'],
                'no_kk'                => $validated['no_kk'],
                'nama_kepala_keluarga' => $validated['nama_kepala_keluarga'],
                'jumlah_anggota'       => count($validated['anggota']),
                'rt'                   => $validated['rt'] ?? null,
                'rw'                   => $validated['rw'] ?? null,
                'dusun_lingkungan'     => $validated['dusun_lingkungan'] ?? null,
                'desa'                 => $validated['desa'] ?? 'Dompas',
                'kecamatan'            => $validated['kecamatan'] ?? 'Bukit Batu',
                'kabupaten'            => $validated['kabupaten'] ?? 'Bengkalis',
                'provinsi'             => $validated['provinsi'] ?? 'Riau',
                'jumlah_kk'            => $validated['jumlah_kk'] ?? 1,
                'jumlah_laki_laki'     => $validated['jumlah_laki_laki'] ?? 0,
                'jumlah_perempuan'     => $validated['jumlah_perempuan'] ?? 0,
                'jumlah_balita'        => $validated['jumlah_balita'] ?? 0,
                'jumlah_pus'           => $validated['jumlah_pus'] ?? 0,
                'jumlah_wus'           => $validated['jumlah_wus'] ?? 0,
                'jumlah_buta'          => $validated['jumlah_buta'] ?? 0,
                'jumlah_ibu_hamil'     => $validated['jumlah_ibu_hamil'] ?? 0,
                'jumlah_ibu_menyusui'  => $validated['jumlah_ibu_menyusui'] ?? 0,
                'jumlah_lansia'        => $validated['jumlah_lansia'] ?? 0,
            ]);

            foreach ($validated['anggota'] as $anggotaData) {
                // Beri nilai default untuk kolom string yang tidak boleh null
                $anggotaData['desa_kelurahan'] = $anggotaData['desa_kelurahan'] ?? 'Dompas';
                $anggotaData['kecamatan']      = $anggotaData['kecamatan'] ?? 'Bukit Batu';
                $anggotaData['kabupaten_kota'] = $anggotaData['kabupaten_kota'] ?? 'Bengkalis';
                $anggotaData['provinsi']       = $anggotaData['provinsi'] ?? 'Riau';

                // Beri nilai default untuk kolom boolean yang tidak boleh null
                $anggotaData['akseptor_kb']               = $anggotaData['akseptor_kb'] ?? false;
                $anggotaData['aktif_posyandu']            = $anggotaData['aktif_posyandu'] ?? false;
                $anggotaData['ikut_bina_keluarga_balita'] = $anggotaData['ikut_bina_keluarga_balita'] ?? false;
                $anggotaData['memiliki_tabungan']         = $anggotaData['memiliki_tabungan'] ?? false;
                $anggotaData['ikut_kelompok_belajar']     = $anggotaData['ikut_kelompok_belajar'] ?? false;
                $anggotaData['ikut_paud_sejenis']         = $anggotaData['ikut_paud_sejenis'] ?? false;
                $anggotaData['ikut_koperasi']             = $anggotaData['ikut_koperasi'] ?? false;

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
        $keluarga->load('anggotaKeluargas');
        
        if (auth()->user()->isAdmin()) {
            $dasawismas = Dasawisma::orderBy('nama_dasawisma')->get();
        } else {
            $kader = $this->getKader();
            $dasawismas = $kader->dasawismas()->orderBy('nama_dasawisma')->get();
        }

        return Inertia::render('Kader/Keluarga/Edit', [
            'keluarga'   => $keluarga,
            'dasawismas' => $dasawismas,
        ]);
    }

    public function update(KeluargaRequest $request, Keluarga $keluarga): RedirectResponse
    {
        $this->authorizeKader($keluarga);

        // Pastikan dasawisma target milik kader
        $dasawisma = Dasawisma::findOrFail($request->dasawisma_id);
        if (! auth()->user()->isAdmin()) {
            $kader     = $this->getKader();
            if ($dasawisma->kader_id !== $kader->id) {
                abort(403, 'Anda tidak memiliki akses ke Dasawisma tujuan.');
            }
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
        if (auth()->user()->isAdmin()) {
            return;
        }
        $dasawismaIds = $this->getDasawismaIds()->toArray();
        if (! $this->getKader() || ! in_array($keluarga->dasawisma_id, $dasawismaIds)) {
            abort(403, 'Anda tidak memiliki akses ke data KK ini.');
        }
    }
}
