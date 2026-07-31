import re

with open('resources/js/pages/Kader/Keluarga/Show.vue', 'r') as f:
    content = f.read()

target = """<div class="rounded-xl bg-gray-50 p-3 text-center">
                        <p class="text-[10px] text-gray-500 uppercase font-bold">Anggota</p>
                        <p class="text-lg font-black text-gray-700">{{ keluarga.jumlah_anggota ?? 0 }}</p>
                    </div>"""

replacement = """<div class="rounded-xl bg-gray-50 p-3 text-center">
                        <p class="text-[10px] text-gray-500 uppercase font-bold">Total Jiwa</p>
                        <p class="text-lg font-black text-gray-700">{{ keluarga.anggota_keluargas?.length ?? keluarga.jumlah_anggota ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl bg-emerald-50 p-3 text-center border border-emerald-100">
                        <p class="text-[10px] text-emerald-600 uppercase font-bold">Aktif PKK</p>
                        <p class="text-lg font-black text-emerald-700">{{ keluarga.anggota_keluargas?.filter(a => a.jabatan && a.jabatan.trim() !== '').length ?? 0 }}</p>
                    </div>"""

content = content.replace(target, replacement)

# We also need to fix the grid layout if we added one more item.
# It was grid-cols-3 sm:grid-cols-5. Let's make it grid-cols-3 sm:grid-cols-6.
content = content.replace(
    '<div class="grid grid-cols-3 sm:grid-cols-5 gap-3">',
    '<div class="grid grid-cols-3 sm:grid-cols-6 gap-3">'
)

with open('resources/js/pages/Kader/Keluarga/Show.vue', 'w') as f:
    f.write(content)

