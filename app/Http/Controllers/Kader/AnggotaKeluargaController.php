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

        $keluarga->anggotaKeluargas()->create($request->validated());

        // Sinkronisasi jumlah_anggota dari count aktual
        $keluarga->update([
            'jumlah_anggota' => $keluarga->anggotaKeluargas()->count(),
        ]);

        return redirect()->route('kader.keluarga.show', $keluarga)
            ->with('success', 'Anggota keluarga berhasil ditambahkan.');
    }

    public function update(AnggotaKeluargaRequest $request, AnggotaKeluarga $anggotaKeluarga): RedirectResponse
    {
        $this->authorizeKader($anggotaKeluarga->keluarga);

        $anggotaKeluarga->update($request->validated());

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
        $kader        = auth()->user()->kader;
        $dasawismaIds = $kader ? $kader->dasawismas()->pluck('id')->toArray() : [];

        if (! $kader || ! in_array($keluarga->dasawisma_id, $dasawismaIds)) {
            abort(403, 'Akses ditolak.');
        }
    }
}
