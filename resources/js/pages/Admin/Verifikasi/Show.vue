<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SidaModal from '@/components/SidaModal.vue';
import SidaConfirm from '@/components/SidaConfirm.vue';

const props = defineProps<{ verifikasi: any }>();
const kk = props.verifikasi.keluarga;

const approveForm = useForm({ catatan: '' });
const rejectForm = useForm({ catatan: '' });
const showRejectModal = ref(false);
const showApproveConfirm = ref(false);

function approve() {
    approveForm.post(`/admin/verifikasi/${props.verifikasi.id}/approve`, {
        onSuccess: () => { showApproveConfirm.value = false; }
    });
}
function reject() {
    rejectForm.post(`/admin/verifikasi/${props.verifikasi.id}/reject`, {
        onSuccess: () => { showRejectModal.value = false; }
    });
}
function yn(v: any) { return v ? 'Ya' : 'Tidak'; }
function formatDate(d: string) {
    if (!d) return '-';
    return new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
}
</script>

<template>
    <Head title="Review Verifikasi" />
    <AppLayout>
        <div class="mx-auto max-w-5xl flex flex-col gap-5">
            <a href="/admin/verifikasi" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-gray-800 transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Verifikasi
            </a>

            <!-- Status Banner -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <div>
                    <h1 class="text-xl font-black text-gray-800 uppercase tracking-tight">Review Data KK</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        <span class="font-bold text-gray-800">{{ kk?.nama_kepala_keluarga }}</span>
                        <span class="mx-2 text-gray-300">|</span>
                        No KK: <span class="font-mono font-bold text-gray-800">{{ kk?.no_kk }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="rounded-full px-4 py-1.5 text-[10px] font-black uppercase tracking-widest shadow-sm ring-1"
                        :class="{
                            'bg-amber-100 text-amber-700 ring-amber-200': verifikasi.status_verifikasi === 'menunggu',
                            'bg-emerald-100 text-emerald-700 ring-emerald-200': verifikasi.status_verifikasi === 'disetujui',
                            'bg-red-100 text-red-700 ring-red-200': verifikasi.status_verifikasi === 'ditolak'
                        }">
                        {{ verifikasi.status_verifikasi }}
                    </span>
                    <template v-if="verifikasi.status_verifikasi === 'menunggu'">
                        <button @click="showApproveConfirm = true" :disabled="approveForm.processing" class="rounded-xl bg-emerald-600 px-6 py-2.5 text-xs font-black text-white hover:bg-emerald-700 disabled:opacity-50 transition-all shadow-sm active:scale-95">SETUJUI</button>
                        <button @click="showRejectModal = true" class="rounded-xl bg-red-50 text-red-600 border border-red-100 px-6 py-2.5 text-xs font-black hover:bg-red-100 transition-all active:scale-95">TOLAK</button>
                    </template>
                </div>
            </div>

            <!-- Catatan -->
            <div v-if="verifikasi.catatan" class="rounded-xl p-5 text-sm shadow-sm border" :class="verifikasi.status_verifikasi === 'ditolak' ? 'bg-red-50 text-red-700 border-red-100' : 'bg-green-50 text-green-700 border-green-100'">
                <strong class="block mb-1">Catatan Verifikasi:</strong> {{ verifikasi.catatan }}
            </div>

            <!-- SECTION 1: Informasi KK & Alamat -->
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-base font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                    Informasi Kartu Keluarga
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                    <div><p class="text-xs text-gray-400 uppercase font-medium">No. KK</p><p class="mt-0.5 font-mono font-semibold text-gray-800">{{ kk?.no_kk }}</p></div>
                    <div><p class="text-xs text-gray-400 uppercase font-medium">Kepala Keluarga</p><p class="mt-0.5 font-semibold text-gray-800">{{ kk?.nama_kepala_keluarga }}</p></div>
                    <div><p class="text-xs text-gray-400 uppercase font-medium">Dasawisma</p><p class="mt-0.5 text-gray-700">{{ kk?.dasawisma?.nama_dasawisma }}</p></div>
                    <div><p class="text-xs text-gray-400 uppercase font-medium">Kader Pendata</p><p class="mt-0.5 text-gray-700">{{ kk?.dasawisma?.kader?.user?.name }}</p></div>
                    <div><p class="text-xs text-gray-400 uppercase font-medium">RT / RW</p><p class="mt-0.5 text-gray-700">{{ kk?.rt ?? '-' }} / {{ kk?.rw ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400 uppercase font-medium">Dusun/Lingkungan</p><p class="mt-0.5 text-gray-700">{{ kk?.dusun_lingkungan ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400 uppercase font-medium">Desa</p><p class="mt-0.5 text-gray-700">{{ kk?.desa ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400 uppercase font-medium">Kecamatan</p><p class="mt-0.5 text-gray-700">{{ kk?.kecamatan ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400 uppercase font-medium">Kabupaten</p><p class="mt-0.5 text-gray-700">{{ kk?.kabupaten ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-400 uppercase font-medium">Provinsi</p><p class="mt-0.5 text-gray-700">{{ kk?.provinsi ?? '-' }}</p></div>
                </div>
            </div>

            <!-- SECTION 2: Rekapitulasi Jumlah -->
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-base font-semibold text-gray-800 flex items-center gap-2">
                    <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Rekapitulasi Kependudukan
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-5 gap-3">
                    <div class="rounded-xl bg-blue-50 p-3 text-center"><p class="text-[10px] text-blue-500 uppercase font-bold">Jumlah KK</p><p class="text-lg font-black text-blue-700">{{ kk?.jumlah_kk ?? 0 }}</p></div>
                    <div class="rounded-xl bg-gray-50 p-3 text-center"><p class="text-[10px] text-gray-500 uppercase font-bold">Anggota</p><p class="text-lg font-black text-gray-700">{{ kk?.jumlah_anggota ?? 0 }}</p></div>
                    <div class="rounded-xl bg-sky-50 p-3 text-center"><p class="text-[10px] text-sky-500 uppercase font-bold">Laki-laki</p><p class="text-lg font-black text-sky-700">{{ kk?.jumlah_laki_laki ?? 0 }}</p></div>
                    <div class="rounded-xl bg-pink-50 p-3 text-center"><p class="text-[10px] text-pink-500 uppercase font-bold">Perempuan</p><p class="text-lg font-black text-pink-700">{{ kk?.jumlah_perempuan ?? 0 }}</p></div>
                    <div class="rounded-xl bg-sky-50 p-3 text-center"><p class="text-[10px] text-sky-500 uppercase font-bold">Balita Laki-laki</p><p class="text-lg font-black text-sky-700">{{ kk?.jumlah_balita_laki ?? 0 }}</p></div>
                    <div class="rounded-xl bg-pink-50 p-3 text-center"><p class="text-[10px] text-pink-500 uppercase font-bold">Balita Perempuan</p><p class="text-lg font-black text-pink-700">{{ kk?.jumlah_balita_perempuan ?? 0 }}</p></div>
                    <div class="rounded-xl bg-purple-50 p-3 text-center"><p class="text-[10px] text-purple-500 uppercase font-bold">PUS</p><p class="text-lg font-black text-purple-700">{{ kk?.jumlah_pus ?? 0 }}</p></div>
                    <div class="rounded-xl bg-fuchsia-50 p-3 text-center"><p class="text-[10px] text-fuchsia-500 uppercase font-bold">WUS</p><p class="text-lg font-black text-fuchsia-700">{{ kk?.jumlah_wus ?? 0 }}</p></div>
                    <div class="rounded-xl bg-rose-50 p-3 text-center"><p class="text-[10px] text-rose-500 uppercase font-bold">Ibu Hamil</p><p class="text-lg font-black text-rose-700">{{ kk?.jumlah_ibu_hamil ?? 0 }}</p></div>
                    <div class="rounded-xl bg-amber-50 p-3 text-center"><p class="text-[10px] text-amber-500 uppercase font-bold">Ibu Menyusui</p><p class="text-lg font-black text-amber-700">{{ kk?.jumlah_ibu_menyusui ?? 0 }}</p></div>
                    <div class="rounded-xl bg-teal-50 p-3 text-center"><p class="text-[10px] text-teal-500 uppercase font-bold">Lansia</p><p class="text-lg font-black text-teal-700">{{ kk?.jumlah_lansia ?? 0 }}</p></div>
                    <div class="rounded-xl bg-red-50 p-3 text-center"><p class="text-[10px] text-red-500 uppercase font-bold">Buta Aksara</p><p class="text-lg font-black text-red-700">{{ kk?.jumlah_buta ?? 0 }}</p></div>
                    <div class="rounded-xl bg-indigo-50 p-3 text-center"><p class="text-[10px] text-indigo-500 uppercase font-bold">Berkebutuhan Khusus</p><p class="text-lg font-black text-indigo-700">{{ kk?.jumlah_berkebutuhan_khusus ?? 0 }}</p></div>
                </div>
            </div>

            <!-- SECTION 3: Kriteria Rumah & Kegiatan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-base font-semibold text-gray-800">Kriteria Rumah & Sanitasi</h2>
                    <div class="space-y-2.5 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Sehat Layak Huni</span><span class="font-semibold" :class="kk?.sehat_layak_huni ? 'text-emerald-600' : 'text-red-500'">{{ yn(kk?.sehat_layak_huni) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Tempat Sampah</span><span class="font-semibold" :class="kk?.memiliki_tempat_sampah ? 'text-emerald-600' : 'text-red-500'">{{ yn(kk?.memiliki_tempat_sampah) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">SPAL</span><span class="font-semibold" :class="kk?.memiliki_spal ? 'text-emerald-600' : 'text-red-500'">{{ yn(kk?.memiliki_spal) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Jamban</span><span class="font-semibold" :class="kk?.memiliki_jamban ? 'text-emerald-600' : 'text-red-500'">{{ yn(kk?.memiliki_jamban) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Stiker P4K</span><span class="font-semibold" :class="kk?.menempel_stiker_p4k ? 'text-emerald-600' : 'text-red-500'">{{ yn(kk?.menempel_stiker_p4k) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Sumber Air</span><span class="font-semibold text-gray-800">{{ kk?.sumber_air ?? '-' }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Makanan Pokok</span><span class="font-semibold text-gray-800">{{ kk?.makanan_pokok ?? '-' }}</span></div>
                    </div>
                </div>
                <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <h2 class="mb-4 text-base font-semibold text-gray-800">Kegiatan</h2>
                    <div class="space-y-2.5 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">UP2K</span><span class="font-semibold" :class="kk?.ikut_up2k ? 'text-emerald-600' : 'text-red-500'">{{ yn(kk?.ikut_up2k) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Pemanfaatan Pekarangan</span><span class="font-semibold" :class="kk?.ikut_pekarangan ? 'text-emerald-600' : 'text-red-500'">{{ yn(kk?.ikut_pekarangan) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Industri Rumah Tangga</span><span class="font-semibold" :class="kk?.ikut_industri ? 'text-emerald-600' : 'text-red-500'">{{ yn(kk?.ikut_industri) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Kerja Bakti</span><span class="font-semibold" :class="kk?.ikut_kerja_bakti ? 'text-emerald-600' : 'text-red-500'">{{ yn(kk?.ikut_kerja_bakti) }}</span></div>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: Anggota Keluarga -->
            <div class="rounded-2xl border border-gray-100 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-gray-100 px-6 py-5 bg-gray-50/50">
                    <h2 class="text-base font-semibold text-gray-800">Anggota Keluarga ({{ kk?.anggota_keluargas?.length ?? 0 }})</h2>
                </div>
                <div v-for="(ak, idx) in kk?.anggota_keluargas" :key="ak.id" class="border-b border-gray-50 last:border-0">
                    <div class="px-6 py-4">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700">{{ idx + 1 }}</span>
                            <div>
                                <p class="font-bold text-gray-800">{{ ak.nama_anggota }}</p>
                                <p class="font-mono text-[11px] text-gray-400">NIK: {{ ak.nik }}</p>
                            </div>
                            <span class="ml-auto rounded-md px-2 py-1 text-xs font-medium" :class="ak.jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600'">{{ ak.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-x-4 gap-y-2 text-sm">
                            <div><span class="text-xs text-gray-400">Tempat Lahir</span><p class="text-gray-700">{{ ak.tempat_lahir ?? '-' }}</p></div>
                            <div><span class="text-xs text-gray-400">Tanggal Lahir</span><p class="text-gray-700">{{ formatDate(ak.tanggal_lahir) }}</p></div>
                            <div><span class="text-xs text-gray-400">Umur</span><p class="text-gray-700">{{ ak.umur ?? '-' }} tahun</p></div>
                            <div><span class="text-xs text-gray-400">Agama</span><p class="text-gray-700">{{ ak.agama ?? '-' }}</p></div>
                            <div><span class="text-xs text-gray-400">Status dlm Keluarga</span><p class="text-gray-700">{{ ak.status_dalam_keluarga ?? '-' }}</p></div>
                            <div><span class="text-xs text-gray-400">Status Perkawinan</span><p class="text-gray-700">{{ ak.status_perkawinan ?? '-' }}</p></div>
                            <div><span class="text-xs text-gray-400">Pendidikan Terakhir</span><p class="text-gray-700">{{ ak.pendidikan_terakhir ?? '-' }}</p></div>
                            <div><span class="text-xs text-gray-400">Pekerjaan Utama</span><p class="text-gray-700">{{ ak.pekerjaan_utama ?? '-' }}</p></div>
                            <div><span class="text-xs text-gray-400">Akseptor KB</span><p :class="ak.akseptor_kb ? 'text-emerald-600 font-semibold' : 'text-gray-500'">{{ yn(ak.akseptor_kb) }}{{ ak.akseptor_kb && ak.jenis_akseptor_kb ? ' (' + ak.jenis_akseptor_kb + ')' : '' }}</p></div>
                            <div><span class="text-xs text-gray-400">Aktif Posyandu</span><p :class="ak.aktif_posyandu ? 'text-emerald-600 font-semibold' : 'text-gray-500'">{{ yn(ak.aktif_posyandu) }}{{ ak.aktif_posyandu && ak.frekuensi_posyandu ? ' (' + ak.frekuensi_posyandu + ')' : '' }}</p></div>
                            <div><span class="text-xs text-gray-400">Bina Keluarga Balita</span><p :class="ak.ikut_bina_keluarga_balita ? 'text-emerald-600 font-semibold' : 'text-gray-500'">{{ yn(ak.ikut_bina_keluarga_balita) }}</p></div>
                            <div><span class="text-xs text-gray-400">Tabungan</span><p :class="ak.memiliki_tabungan ? 'text-emerald-600 font-semibold' : 'text-gray-500'">{{ yn(ak.memiliki_tabungan) }}</p></div>
                            <div><span class="text-xs text-gray-400">Kelompok Belajar</span><p :class="ak.ikut_kelompok_belajar ? 'text-emerald-600 font-semibold' : 'text-gray-500'">{{ yn(ak.ikut_kelompok_belajar) }}{{ ak.ikut_kelompok_belajar && ak.jenis_paket_belajar ? ' (' + ak.jenis_paket_belajar + ')' : '' }}</p></div>
                            <div><span class="text-xs text-gray-400">PAUD Sejenis</span><p :class="ak.ikut_paud_sejenis ? 'text-emerald-600 font-semibold' : 'text-gray-500'">{{ yn(ak.ikut_paud_sejenis) }}</p></div>
                            <div><span class="text-xs text-gray-400">Koperasi</span><p :class="ak.ikut_koperasi ? 'text-emerald-600 font-semibold' : 'text-gray-500'">{{ yn(ak.ikut_koperasi) }}{{ ak.ikut_koperasi && ak.jenis_koperasi ? ' (' + ak.jenis_koperasi + ')' : '' }}</p></div>
                        </div>
                    </div>
                </div>
                <div v-if="!kk?.anggota_keluargas?.length" class="py-12 text-center text-sm text-gray-400">Tidak ada data anggota keluarga</div>
            </div>
        </div>

        <!-- Modal Tolak -->
        <SidaModal :show="showRejectModal" title="Tolak Verifikasi KK" max-width="max-w-md" @close="showRejectModal = false">
            <form @submit.prevent="reject">
                <div class="mb-4 rounded-xl bg-red-50 p-4 border border-red-100">
                    <p class="text-sm text-red-700">Berikan catatan alasan penolakan agar kader bisa memperbaiki data KK ini.</p>
                </div>
                <div class="mb-5">
                    <label class="mb-1.5 block text-xs font-medium text-gray-700">Alasan Penolakan <span class="text-red-500">*</span></label>
                    <textarea v-model="rejectForm.catatan" required rows="4" placeholder="Contoh: NIK anggota atas nama Budi tidak valid..." class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-red-400 focus:outline-none focus:ring-2 focus:ring-red-100"></textarea>
                    <p v-if="rejectForm.errors.catatan" class="mt-1 text-xs text-red-600">{{ rejectForm.errors.catatan }}</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="showRejectModal = false" class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" :disabled="rejectForm.processing" class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-red-700 disabled:opacity-60 shadow-sm transition">
                        {{ rejectForm.processing ? 'Memproses...' : 'Tolak Data' }}
                    </button>
                </div>
            </form>
        </SidaModal>

        <!-- Konfirmasi Setujui -->
        <SidaConfirm
            :show="showApproveConfirm"
            type="success"
            title="Setujui Data KK"
            message="Apakah Anda yakin ingin menyetujui data KK ini? Data yang disetujui akan dianggap valid."
            confirm-text="Ya, Setujui"
            confirm-class="bg-emerald-600 hover:bg-emerald-700 text-white"
            @confirm="approve"
            @cancel="showApproveConfirm = false"
        />
    </AppLayout>
</template>
