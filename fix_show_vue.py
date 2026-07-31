import re

with open('resources/js/pages/Kader/Keluarga/Show.vue', 'r') as f:
    content = f.read()

# Fix Total Aktif badge
content = content.replace(
    'Total Aktif: {{ keluarga.anggota_keluargas?.length ?? 0 }} Orang',
    "Total Aktif: {{ keluarga.anggota_keluargas?.filter(a => a.jabatan && a.jabatan.trim() !== '').length ?? 0 }} Orang"
)

# In Show.vue there are two fields for jabatan: editAnggotaForm and tambahForm
# We need to bind disabled and class for both.

# Let's find the inputs for jabatan in Show.vue
# Actually, it's probably similar: v-model="editAnggotaForm.jabatan" and v-model="tambahForm.jabatan"

# For Edit Form:
content = content.replace(
    '<input v-model="editAnggotaForm.jabatan" type="text"',
    '<input v-model="editAnggotaForm.jabatan" type="text" :disabled="editAnggotaForm.jenis_kelamin === \'L\'" :class="{\'opacity-50 cursor-not-allowed bg-gray-100\': editAnggotaForm.jenis_kelamin === \'L\'}"'
)

# For Tambah Form:
content = content.replace(
    '<input v-model="tambahForm.jabatan" type="text"',
    '<input v-model="tambahForm.jabatan" type="text" :disabled="tambahForm.jenis_kelamin === \'L\'" :class="{\'opacity-50 cursor-not-allowed bg-gray-100\': tambahForm.jenis_kelamin === \'L\'}"'
)

# Also add watchers to clear the fields
watcher_code = """
watch(() => editAnggotaForm.jenis_kelamin, (newVal) => {
    if (newVal === 'L') {
        editAnggotaForm.jabatan = '';
    }
});

watch(() => tambahForm.jenis_kelamin, (newVal) => {
    if (newVal === 'L') {
        tambahForm.jabatan = '';
    }
});
"""

# Insert watcher code after showTambahAnggota definition
content = content.replace(
    'const showTambahAnggota = ref(false);',
    'const showTambahAnggota = ref(false);\n' + watcher_code
)

with open('resources/js/pages/Kader/Keluarga/Show.vue', 'w') as f:
    f.write(content)

