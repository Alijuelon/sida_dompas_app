import re

with open('app/Http/Controllers/Kader/AnggotaKeluargaController.php', 'r') as f:
    content = f.read()

# Make sure Inertia is imported
if 'use Inertia\\Inertia;' not in content:
    content = content.replace('use Illuminate\\Http\\RedirectResponse;', 'use Illuminate\\Http\\RedirectResponse;\nuse Inertia\\Inertia;')

target = "return view('kader.anggota.edit', compact('anggotaKeluarga'));"
replacement = """
        $keluarga = $anggotaKeluarga->keluarga;
        $keluargaAktifCount = $keluarga->anggotaKeluargas()
            ->whereNotNull('jabatan')
            ->where('jabatan', '!=', '')
            ->count();

        return Inertia::render('Kader/Anggota/Edit', [
            'anggotaKeluarga' => $anggotaKeluarga,
            'keluargaAktifCount' => $keluargaAktifCount
        ]);
"""

content = content.replace(target, replacement)

with open('app/Http/Controllers/Kader/AnggotaKeluargaController.php', 'w') as f:
    f.write(content)

