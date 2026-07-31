import re

with open('resources/js/pages/Kader/Keluarga/Create.vue', 'r') as f:
    content = f.read()

# Update totalAnggotaAktif computed property
content = content.replace(
    'const totalAnggotaAktif = computed(() => form.anggota.length);',
    "const totalAnggotaAktif = computed(() => form.anggota.filter(a => typeof a.jabatan === 'string' && a.jabatan.trim() !== '').length);"
)

# Also disable jabatan field if jenis_kelamin is 'L', and add a hint
target_input = '<input v-model="anggota.jabatan" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />'
replacement_input = '<input v-model="anggota.jabatan" type="text" :disabled="anggota.jenis_kelamin === \'L\'" :class="{\'opacity-50 cursor-not-allowed bg-gray-100\': anggota.jenis_kelamin === \'L\'}" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />'

content = content.replace(target_input, replacement_input)

# Let's add a watcher to clear jabatan when jenis_kelamin changes to 'L'
watcher = """
watch(() => form.anggota, (newVal) => {
    newVal.forEach(a => {
        if (a.jenis_kelamin === 'L') {
            a.jabatan = '';
        }
    });
}, { deep: true });
"""
# Insert watcher after the computed property
content = content.replace(
    "const totalAnggotaAktif = computed(() => form.anggota.filter(a => typeof a.jabatan === 'string' && a.jabatan.trim() !== '').length);",
    "const totalAnggotaAktif = computed(() => form.anggota.filter(a => typeof a.jabatan === 'string' && a.jabatan.trim() !== '').length);\n" + watcher
)

with open('resources/js/pages/Kader/Keluarga/Create.vue', 'w') as f:
    f.write(content)

