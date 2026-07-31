import re

with open('resources/js/pages/Kader/Anggota/Edit.vue', 'r') as f:
    content = f.read()

# 1. Add keterangan_tabungan to UI
tabungan_target = """                                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 transition-colors hover:border-emerald-200">
                                    <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-700">Memiliki Tabungan?</label>
                                    <select v-model="form.memiliki_tabungan" class="w-full rounded-xl border-gray-200 bg-white p-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                </div>"""

tabungan_replacement = """                                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 transition-colors hover:border-emerald-200">
                                    <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-700">Memiliki Tabungan?</label>
                                    <select v-model="form.memiliki_tabungan" class="w-full rounded-xl border-gray-200 bg-white p-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                    <div v-if="form.memiliki_tabungan === '1'" class="mt-3 border-t border-gray-200 pt-3">
                                        <label class="mb-1 block text-xs font-semibold text-gray-600">Keterangan Tabungan</label>
                                        <input v-model="form.keterangan_tabungan" type="text" class="w-full rounded-xl border border-gray-200 bg-white p-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" placeholder="Misal: Bank Riau Kepri..." />
                                    </div>
                                </div>"""
content = content.replace(tabungan_target, tabungan_replacement)

# 2. Add keterangan_tabungan to useForm
use_form_target = "memiliki_tabungan: props.anggotaKeluarga.memiliki_tabungan ? '1' : '0',"
use_form_replacement = "memiliki_tabungan: props.anggotaKeluarga.memiliki_tabungan ? '1' : '0',\n    keterangan_tabungan: props.anggotaKeluarga.keterangan_tabungan || '',"
content = content.replace(use_form_target, use_form_replacement)

# 3. Add transform to submit function
submit_target = """function submit() {
    form.put(`/kader/anggota/${props.anggotaKeluarga.id}`);
}"""

submit_replacement = """function submit() {
    form.transform((data) => ({
        ...data,
        pendidikan_terakhir: data.pendidikan,
        pekerjaan_utama: data.pekerjaan,
        akseptor_kb: data.akseptor_kb === '1',
        aktif_posyandu: data.aktif_posyandu === '1',
        ikut_kelompok_belajar: data.ikut_kelompok_belajar === '1',
        ikut_koperasi: data.ikut_koperasi === '1',
        ikut_bina_keluarga_balita: data.ikut_bina_keluarga_balita === '1',
        memiliki_tabungan: data.memiliki_tabungan === '1',
        ikut_paud_sejenis: data.ikut_paud_sejenis === '1',
    })).put(`/kader/anggota/${props.anggotaKeluarga.id}`);
}"""
content = content.replace(submit_target, submit_replacement)

with open('resources/js/pages/Kader/Anggota/Edit.vue', 'w') as f:
    f.write(content)

