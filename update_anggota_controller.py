import re

with open('app/Http/Controllers/Kader/AnggotaKeluargaController.php', 'r') as f:
    content = f.read()

# Add Jabatan PKK logic in store and update methods
logic = """
        // Logika Jabatan PKK: Hanya Perempuan yang bisa memiliki jabatan PKK
        if (isset($data['jenis_kelamin']) && $data['jenis_kelamin'] === 'L') {
            $data['jabatan'] = null;
        }

        $anggotaKeluarga->update($data);"""

# Replace in update method
content = content.replace('$anggotaKeluarga->update($data);', logic.strip())

logic_store = """
        // Logika Jabatan PKK: Hanya Perempuan yang bisa memiliki jabatan PKK
        if (isset($data['jenis_kelamin']) && $data['jenis_kelamin'] === 'L') {
            $data['jabatan'] = null;
        }

        $keluarga->anggotaKeluargas()->create($data);"""

content = content.replace('$keluarga->anggotaKeluargas()->create($data);', logic_store.strip())

with open('app/Http/Controllers/Kader/AnggotaKeluargaController.php', 'w') as f:
    f.write(content)

