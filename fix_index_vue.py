import re

with open('resources/js/pages/Kader/Keluarga/Index.vue', 'r') as f:
    content = f.read()

# Replace Anggota column display
target = """<td class="px-5 py-3.5 text-center">
                                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-600">
                                    {{ kk.anggota_keluargas_count ?? kk.jumlah_anggota ?? 0 }} jiwa
                                </span>
                            </td>"""

replacement = """<td class="px-5 py-3.5 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700 flex items-center gap-1">
                                        <i class="fa-solid fa-users"></i> {{ kk.anggota_aktif_count ?? 0 }} Aktif
                                    </span>
                                    <span class="text-[10px] text-gray-400 font-medium">
                                        dari {{ kk.anggota_keluargas_count ?? kk.jumlah_anggota ?? 0 }} Jiwa
                                    </span>
                                </div>
                            </td>"""

content = content.replace(target, replacement)

with open('resources/js/pages/Kader/Keluarga/Index.vue', 'w') as f:
    f.write(content)

