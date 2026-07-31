import re

with open('app/Http/Controllers/Kader/KeluargaController.php', 'r') as f:
    content = f.read()

# Replace withCount('anggotaKeluargas')
# with withCount(['anggotaKeluargas', 'anggotaKeluargas as anggota_aktif_count' => function ($q) { $q->whereNotNull('jabatan'); }])
replacement = """->withCount([
                'anggotaKeluargas',
                'anggotaKeluargas as anggota_aktif_count' => function ($query) {
                    $query->whereNotNull('jabatan')->where('jabatan', '!=', '');
                }
            ]);"""

content = content.replace("->withCount('anggotaKeluargas');", replacement)

with open('app/Http/Controllers/Kader/KeluargaController.php', 'w') as f:
    f.write(content)

