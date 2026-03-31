<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use App\Models\Dasawisma;
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
        $kader = $this->getKader();
        $dasawismas = $kader
            ? Dasawisma::where('kader_id', $kader->id)->withCount('keluargas')->latest()->get()
            : collect();

        return Inertia::render('Kader/Dasawisma/Index', [
            'dasawismas' => $dasawismas,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Kader/Dasawisma/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_dasawisma' => 'required|string|max:255',
            'rt' => 'required|string|max:10',
            'rw' => 'required|string|max:10',
            'desa' => 'required|string|max:255',
        ]);

        $kader = $this->getKader();

        if (! $kader) {
            return redirect()->back()->with('error', 'Data kader tidak ditemukan.');
        }

        Dasawisma::create(array_merge($validated, ['kader_id' => $kader->id]));

        return redirect()->route('kader.dasawisma.index')->with('success', 'Dasawisma berhasil ditambahkan.');
    }

    public function edit(Dasawisma $dasawisma): Response
    {
        $this->authorizeKader($dasawisma);

        return Inertia::render('Kader/Dasawisma/Edit', [
            'dasawisma' => $dasawisma,
        ]);
    }

    public function update(Request $request, Dasawisma $dasawisma): RedirectResponse
    {
        $this->authorizeKader($dasawisma);

        $validated = $request->validate([
            'nama_dasawisma' => 'required|string|max:255',
            'rt' => 'required|string|max:10',
            'rw' => 'required|string|max:10',
            'desa' => 'required|string|max:255',
        ]);

        $dasawisma->update($validated);

        return redirect()->route('kader.dasawisma.index')->with('success', 'Dasawisma berhasil diperbarui.');
    }

    public function destroy(Dasawisma $dasawisma): RedirectResponse
    {
        $this->authorizeKader($dasawisma);
        $dasawisma->delete();

        return redirect()->route('kader.dasawisma.index')->with('success', 'Dasawisma berhasil dihapus.');
    }

    private function authorizeKader(Dasawisma $dasawisma): void
    {
        $kader = $this->getKader();
        if (! $kader || $dasawisma->kader_id !== $kader->id) {
            abort(403);
        }
    }
}
