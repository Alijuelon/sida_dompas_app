<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Http\Requests\AnggotaKeluargaRequest;
use App\Models\AnggotaKeluarga;
use App\Models\Keluarga;
use Illuminate\Http\RedirectResponse;

class AnggotaKeluargaController extends Controller
{
    public function store(AnggotaKeluargaRequest $request, Keluarga $keluarga): RedirectResponse
    {
        $this->authorizeKader($keluarga);

        $data = $request->validated();
        $data['desa_kelurahan'] = $data['desa_kelurahan'] ?? 'Dompas';
        $data['kecamatan']      = $data['kecamatan'] ?? 'Bukit Batu';
        $data['kabupaten_kota'] = $data['kabupaten_kota'] ?? 'Bengkalis';
        $data['provinsi']       = $data['provinsi'] ?? 'Riau';
        $data['akseptor_kb']               = $data['akseptor_kb'] ?? false;
        $data['aktif_posyandu']            = $data['aktif_posyandu'] ?? false;
        $data['ikut_bina_keluarga_balita'] = $data['ikut_bina_keluarga_balita'] ?? false;
        $data['memiliki_tabungan']         = $data['memiliki_tabungan'] ?? false;
        $data['ikut_kelompok_belajar']     = $data['ikut_kelompok_belajar'] ?? false;
        $data['ikut_paud_sejenis']         = $data['ikut_paud_sejenis'] ?? false;
        $data['ikut_koperasi']             = $data['ikut_koperasi'] ?? false;

        $keluarga->anggotaKeluargas()->create($data);

        // Sinkronisasi jumlah_anggota dari count aktual
        $keluarga->update([
            'jumlah_anggota' => $keluarga->anggotaKeluargas()->count(),
        ]);

        return redirect()->route('kader.keluarga.show', $keluarga)
            ->with('success', 'Anggota keluarga berhasil ditambahkan.');
    }

    public function edit(AnggotaKeluarga $anggotaKeluarga)
    {
        $this->authorizeKader($anggotaKeluarga->keluarga);
        
        return view('kader.anggota.edit', compact('anggotaKeluarga'));
    }

    public function update(AnggotaKeluargaRequest $request, AnggotaKeluarga $anggotaKeluarga): RedirectResponse
    {
        $this->authorizeKader($anggotaKeluarga->keluarga);

        $data = $request->validated();
        $data['desa_kelurahan'] = $data['desa_kelurahan'] ?? 'Dompas';
        $data['kecamatan']      = $data['kecamatan'] ?? 'Bukit Batu';
        $data['kabupaten_kota'] = $data['kabupaten_kota'] ?? 'Bengkalis';
        $data['provinsi']       = $data['provinsi'] ?? 'Riau';
        $data['akseptor_kb']               = $data['akseptor_kb'] ?? false;
        $data['aktif_posyandu']            = $data['aktif_posyandu'] ?? false;
        $data['ikut_bina_keluarga_balita'] = $data['ikut_bina_keluarga_balita'] ?? false;
        $data['memiliki_tabungan']         = $data['memiliki_tabungan'] ?? false;
        $data['ikut_kelompok_belajar']     = $data['ikut_kelompok_belajar'] ?? false;
        $data['ikut_paud_sejenis']         = $data['ikut_paud_sejenis'] ?? false;
        $data['ikut_koperasi']             = $data['ikut_koperasi'] ?? false;

        $anggotaKeluarga->update($data);

        return redirect()->route('kader.keluarga.show', $anggotaKeluarga->keluarga_id)
            ->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(AnggotaKeluarga $anggotaKeluarga): RedirectResponse
    {
        $keluarga = $anggotaKeluarga->keluarga;
        $this->authorizeKader($keluarga);

        $anggotaKeluarga->delete();

        // Sinkronisasi jumlah_anggota dari count aktual
        $keluarga->update([
            'jumlah_anggota' => $keluarga->anggotaKeluargas()->count(),
        ]);

        return redirect()->route('kader.keluarga.show', $keluarga)
            ->with('success', 'Anggota keluarga berhasil dihapus.');
    }

    private function authorizeKader(Keluarga $keluarga): void
    {
        if (auth()->user()->isAdmin()) {
            return;
        }

        $kader        = auth()->user()->kader;
        $dasawismaIds = $kader ? $kader->dasawismas()->pluck('id')->toArray() : [];

        if (! $kader || ! in_array($keluarga->dasawisma_id, $dasawismaIds)) {
            abort(403, 'Akses ditolak.');
        }
    }
}
