<script setup lang="ts">
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import SidaModal from '@/components/SidaModal.vue';
import SidaConfirm from '@/components/SidaConfirm.vue';
import SidaAlert from '@/components/SidaAlert.vue';
import { computed, ref, watch } from 'vue';

const props = defineProps<{ keluarga: any }>();

const page = usePage();
const flash = computed(() => (page.props as any).flash);
const showAlert = ref(false);
watch(() => flash.value, () => { showAlert.value = true; });

// ===================== DELETE ANGGOTA =====================
const confirmAnggotaId = ref<number | null>(null);
const confirmAnggotaName = ref('');
function openDeleteAnggota(id: number, name: string) {
    confirmAnggotaId.value = id;
    confirmAnggotaName.value = name;
}
function doDeleteAnggota() {
    router.delete(`/kader/anggota/${confirmAnggotaId.value}`, { preserveScroll: true });
    confirmAnggotaId.value = null;
}

// ===================== EDIT ANGGOTA =====================
const showEditAnggota = ref(false);
const editAnggotaId = ref<number | null>(null);
const editAnggotaForm = useForm({
    nik: '',
    nama_anggota: '',
    jenis_kelamin: 'L',
    tanggal_lahir: '',
    agama: '',
    pendidikan: '',
    pekerjaan: '',
    status_dalam_keluarga: '',
    status_perkawinan: '',
    dasa_wisma: '',
    nama_kepala_rumah_tangga: '',
    jabatan: '',
    tempat_lahir: '',
    umur: '',
    alamat_jalan: '',
    rt: '',
    rw: '',
    desa_kelurahan: '',
    kecamatan: '',
    kabupaten_kota: '',
    provinsi: '',
    pendidikan_terakhir: '',
    pekerjaan_utama: '',
    akseptor_kb: '0',
    jenis_akseptor_kb: '',
    aktif_posyandu: '0',
    frekuensi_posyandu: '',
    ikut_bina_keluarga_balita: '0',
    memiliki_tabungan: '0',
    ikut_kelompok_belajar: '0',
    jenis_paket_belajar: '',
    ikut_paud_sejenis: '0',
    ikut_koperasi: '0',
    jenis_koperasi: '',
});

function openEditAnggota(ak: any) {
    editAnggotaId.value = ak.id;
    editAnggotaForm.nik = ak.nik;
    editAnggotaForm.nama_anggota = ak.nama_anggota;
    editAnggotaForm.jenis_kelamin = ak.jenis_kelamin;
    editAnggotaForm.tanggal_lahir = ak.tanggal_lahir?.split('T')[0] ?? ak.tanggal_lahir;
    editAnggotaForm.agama = ak.agama ?? '';
    editAnggotaForm.pendidikan = ak.pendidikan ?? '';
    editAnggotaForm.pekerjaan = ak.pekerjaan ?? '';
    editAnggotaForm.status_dalam_keluarga = ak.status_dalam_keluarga ?? '';
    editAnggotaForm.status_perkawinan = ak.status_perkawinan ?? '';
    editAnggotaForm.dasa_wisma = ak.dasa_wisma ?? '';
    editAnggotaForm.nama_kepala_rumah_tangga = ak.nama_kepala_rumah_tangga ?? '';
    editAnggotaForm.jabatan = ak.jabatan ?? '';
    editAnggotaForm.tempat_lahir = ak.tempat_lahir ?? '';
    editAnggotaForm.umur = ak.umur ?? '';
    editAnggotaForm.alamat_jalan = ak.alamat_jalan ?? '';
    editAnggotaForm.rt = ak.rt ?? '';
    editAnggotaForm.rw = ak.rw ?? '';
    editAnggotaForm.desa_kelurahan = ak.desa_kelurahan ?? '';
    editAnggotaForm.kecamatan = ak.kecamatan ?? '';
    editAnggotaForm.kabupaten_kota = ak.kabupaten_kota ?? '';
    editAnggotaForm.provinsi = ak.provinsi ?? '';
    editAnggotaForm.pendidikan_terakhir = ak.pendidikan_terakhir ?? '';
    editAnggotaForm.pekerjaan_utama = ak.pekerjaan_utama ?? '';
    editAnggotaForm.akseptor_kb = ak.akseptor_kb ? '1' : '0';
    editAnggotaForm.jenis_akseptor_kb = ak.jenis_akseptor_kb ?? '';
    editAnggotaForm.aktif_posyandu = ak.aktif_posyandu ? '1' : '0';
    editAnggotaForm.frekuensi_posyandu = ak.frekuensi_posyandu ?? '';
    editAnggotaForm.ikut_bina_keluarga_balita = ak.ikut_bina_keluarga_balita ? '1' : '0';
    editAnggotaForm.memiliki_tabungan = ak.memiliki_tabungan ? '1' : '0';
    editAnggotaForm.ikut_kelompok_belajar = ak.ikut_kelompok_belajar ? '1' : '0';
    editAnggotaForm.jenis_paket_belajar = ak.jenis_paket_belajar ?? '';
    editAnggotaForm.ikut_paud_sejenis = ak.ikut_paud_sejenis ? '1' : '0';
    editAnggotaForm.ikut_koperasi = ak.ikut_koperasi ? '1' : '0';
    editAnggotaForm.jenis_koperasi = ak.jenis_koperasi ?? '';

    showEditAnggota.value = true;
}
function submitEditAnggota() {
    editAnggotaForm.put(`/kader/anggota/${editAnggotaId.value}`, {
        onSuccess: () => { showEditAnggota.value = false; },
        preserveScroll: true,
    });
}

// ===================== TAMBAH ANGGOTA =====================
const showTambahAnggota = ref(false);
const tambahForm = useForm({
    nik: '',
    nama_anggota: '',
    jenis_kelamin: 'L',
    tanggal_lahir: '',
    agama: '',
    pendidikan: '',
    pekerjaan: '',
    status_dalam_keluarga: '',
    status_perkawinan: '',
    dasa_wisma: '',
    nama_kepala_rumah_tangga: '',
    jabatan: '',
    tempat_lahir: '',
    umur: '',
    alamat_jalan: '',
    rt: '',
    rw: '',
    desa_kelurahan: '',
    kecamatan: '',
    kabupaten_kota: '',
    provinsi: '',
    pendidikan_terakhir: '',
    pekerjaan_utama: '',
    akseptor_kb: '0',
    jenis_akseptor_kb: '',
    aktif_posyandu: '0',
    frekuensi_posyandu: '',
    ikut_bina_keluarga_balita: '0',
    memiliki_tabungan: '0',
    ikut_kelompok_belajar: '0',
    jenis_paket_belajar: '',
    ikut_paud_sejenis: '0',
    ikut_koperasi: '0',
    jenis_koperasi: '',
});
function submitTambahAnggota() {
    tambahForm.post(`/kader/keluarga/${props.keluarga.id}/anggota`, {
        onSuccess: () => {
            showTambahAnggota.value = false;
            tambahForm.reset();
        },
        preserveScroll: true,
    });
}

// ===================== OPTIONS =====================
const agamaOptions = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
const pendidikanOptions = ['Tidak Sekolah', 'SD', 'SMP', 'SMA/SMK', 'D1/D2/D3', 'S1', 'S2', 'S3'];
const pekerjaanOptions = ['Tidak Bekerja', 'Petani', 'Nelayan', 'Pedagang', 'PNS', 'Swasta', 'Wiraswasta', 'TNI/Polri', 'Pelajar/Mahasiswa', 'Ibu Rumah Tangga', 'Lainnya'];
const statusKKOptions = ['Kepala Keluarga', 'Istri', 'Anak', 'Menantu', 'Cucu', 'Orang Tua', 'Mertua', 'Anggota Keluarga', 'Lainnya'];
const statusKawinOptions = ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'];

function statusBadge(status: string) {
    return {
        menunggu: 'bg-amber-100 text-amber-700 ring-1 ring-amber-200',
        disetujui: 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-200',
        ditolak: 'bg-red-100 text-red-700 ring-1 ring-red-200',
    }[status] ?? 'bg-gray-100 text-gray-500';
}

function formatDate(d: string) {
    if (!d) return '-';
    return new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
}
function yn(v: any) { return v ? 'Ya' : 'Tidak'; }
</script>

<template>

    <Head :title="`KK - ${keluarga.nama_kepala_keluarga}`" />
    <AppLayout>
        <SidaAlert :show="showAlert" :type="flash?.error ? 'error' : 'success'"
            :message="flash?.success || flash?.error" @close="showAlert = false" />
        <SidaConfirm :show="confirmAnggotaId !== null" :message="`Hapus data anggota '${confirmAnggotaName}'?`"
            @confirm="doDeleteAnggota" @cancel="confirmAnggotaId = null" />

        <!-- Back button -->
        <a href="/kader/keluarga"
            class="mb-5 inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-emerald-600">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Daftar KK
        </a>

        <div class="flex flex-col gap-5">
            <!-- ====== HEADER KK CARD ====== -->
            <div class="overflow-hidden rounded-2xl shadow-sm ring-1 ring-gray-200">
                <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h1 class="text-xl font-bold text-white">{{ keluarga.nama_kepala_keluarga }}</h1>
                            <p class="mt-0.5 font-mono text-sm text-emerald-100">No KK: {{ keluarga.no_kk }}</p>
                        </div>
                        <span :class="statusBadge(keluarga.verifikasi?.status_verifikasi ?? 'menunggu')"
                            class="shrink-0 rounded-full px-3 py-1 text-xs font-semibold capitalize">
                            {{ keluarga.verifikasi?.status_verifikasi ?? 'menunggu' }}
                        </span>
                    </div>
                </div>
                <div class="bg-white px-6 py-4">
                    <div class="grid grid-cols-2 gap-4 text-sm sm:grid-cols-4">
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase">Dasawisma</p>
                            <p class="mt-0.5 font-semibold text-gray-700">{{ keluarga.dasawisma?.nama_dasawisma ?? '-'
                                }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase">RT/RW</p>
                            <p class="mt-0.5 font-semibold text-gray-700">{{ keluarga.rt ?? keluarga.dasawisma?.rt ??
                                '-' }}/{{ keluarga.rw ?? keluarga.dasawisma?.rw ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-400 uppercase">Dusun</p>
                            <p class="mt-0.5 font-semibold text-gray-700">{{ keluarga.dusun_lingkungan ?? '-' }}</p>
                        </div>
                        <div class="flex items-end">
                            <a :href="`/kader/keluarga/${keluarga.id}/edit`"
                                class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-white px-4 py-2 text-xs font-semibold text-gray-600 shadow-sm transition hover:bg-gray-50 hover:text-emerald-600">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit KK
                            </a>
                        </div>
                    </div>
                    <div class="mt-3 grid grid-cols-2 gap-4 text-sm sm:grid-cols-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Desa</p>
                            <p class="mt-0.5 text-gray-700">{{ keluarga.desa ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Kecamatan</p>
                            <p class="mt-0.5 text-gray-700">{{ keluarga.kecamatan ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Kabupaten</p>
                            <p class="mt-0.5 text-gray-700">{{ keluarga.kabupaten ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Provinsi</p>
                            <p class="mt-0.5 text-gray-700">{{ keluarga.provinsi ?? '-' }}</p>
                        </div>
                    </div>
                    <div v-if="keluarga.verifikasi?.status_verifikasi === 'ditolak' && keluarga.verifikasi?.catatan"
                        class="mt-4 flex items-start gap-3 rounded-xl bg-red-50 p-3 text-sm text-red-700 ring-1 ring-red-200">
                        <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div><strong>Catatan Admin:</strong> {{ keluarga.verifikasi.catatan }}</div>
                    </div>
                </div>
            </div>

            <!-- ====== REKAPITULASI ====== -->
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 p-6">
                <h2 class="mb-4 text-base font-semibold text-gray-800">Rekapitulasi Kependudukan</h2>
                <div class="grid grid-cols-3 sm:grid-cols-5 gap-3">
                    <div class="rounded-xl bg-blue-50 p-3 text-center">
                        <p class="text-[10px] text-blue-500 uppercase font-bold">Jml KK</p>
                        <p class="text-lg font-black text-blue-700">{{ keluarga.jumlah_kk ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl bg-gray-50 p-3 text-center">
                        <p class="text-[10px] text-gray-500 uppercase font-bold">Anggota</p>
                        <p class="text-lg font-black text-gray-700">{{ keluarga.jumlah_anggota ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl bg-sky-50 p-3 text-center">
                        <p class="text-[10px] text-sky-500 uppercase font-bold">Laki-laki</p>
                        <p class="text-lg font-black text-sky-700">{{ keluarga.jumlah_laki_laki ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl bg-pink-50 p-3 text-center">
                        <p class="text-[10px] text-pink-500 uppercase font-bold">Perempuan</p>
                        <p class="text-lg font-black text-pink-700">{{ keluarga.jumlah_perempuan ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl bg-orange-50 p-3 text-center">
                        <p class="text-[10px] text-orange-500 uppercase font-bold">Balita</p>
                        <p class="text-lg font-black text-orange-700">{{ keluarga.jumlah_balita ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl bg-purple-50 p-3 text-center">
                        <p class="text-[10px] text-purple-500 uppercase font-bold">PUS</p>
                        <p class="text-lg font-black text-purple-700">{{ keluarga.jumlah_pus ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl bg-fuchsia-50 p-3 text-center">
                        <p class="text-[10px] text-fuchsia-500 uppercase font-bold">WUS</p>
                        <p class="text-lg font-black text-fuchsia-700">{{ keluarga.jumlah_wus ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl bg-rose-50 p-3 text-center">
                        <p class="text-[10px] text-rose-500 uppercase font-bold">Ibu Hamil</p>
                        <p class="text-lg font-black text-rose-700">{{ keluarga.jumlah_ibu_hamil ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl bg-amber-50 p-3 text-center">
                        <p class="text-[10px] text-amber-500 uppercase font-bold">Ibu Menyusui</p>
                        <p class="text-lg font-black text-amber-700">{{ keluarga.jumlah_ibu_menyusui ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl bg-teal-50 p-3 text-center">
                        <p class="text-[10px] text-teal-500 uppercase font-bold">Lansia</p>
                        <p class="text-lg font-black text-teal-700">{{ keluarga.jumlah_lansia ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <!-- ====== KRITERIA RUMAH & KEGIATAN ====== -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 p-6">
                    <h2 class="mb-3 text-base font-semibold text-gray-800">Kriteria Rumah & Sanitasi</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Sehat Layak Huni</span><span
                                class="font-semibold"
                                :class="keluarga.sehat_layak_huni ? 'text-emerald-600' : 'text-red-500'">{{
                                    yn(keluarga.sehat_layak_huni) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Tempat Sampah</span><span
                                class="font-semibold"
                                :class="keluarga.memiliki_tempat_sampah ? 'text-emerald-600' : 'text-red-500'">{{
                                    yn(keluarga.memiliki_tempat_sampah) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">SPAL</span><span
                                class="font-semibold"
                                :class="keluarga.memiliki_spal ? 'text-emerald-600' : 'text-red-500'">{{
                                yn(keluarga.memiliki_spal) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Jamban</span><span
                                class="font-semibold"
                                :class="keluarga.memiliki_jamban ? 'text-emerald-600' : 'text-red-500'">{{
                                    yn(keluarga.memiliki_jamban) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Stiker P4K</span><span
                                class="font-semibold"
                                :class="keluarga.menempel_stiker_p4k ? 'text-emerald-600' : 'text-red-500'">{{
                                    yn(keluarga.menempel_stiker_p4k) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Sumber Air</span><span
                                class="font-semibold text-gray-800">{{ keluarga.sumber_air ?? '-' }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Makanan Pokok</span><span
                                class="font-semibold text-gray-800">{{ keluarga.makanan_pokok ?? '-' }}</span></div>
                    </div>
                </div>
                <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 p-6">
                    <h2 class="mb-3 text-base font-semibold text-gray-800">Kegiatan</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">UP2K</span><span
                                class="font-semibold"
                                :class="keluarga.ikut_up2k ? 'text-emerald-600' : 'text-red-500'">{{
                                yn(keluarga.ikut_up2k) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Pemanfaatan Pekarangan</span><span
                                class="font-semibold"
                                :class="keluarga.ikut_pekarangan ? 'text-emerald-600' : 'text-red-500'">{{
                                    yn(keluarga.ikut_pekarangan) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Industri Rumah Tangga</span><span
                                class="font-semibold"
                                :class="keluarga.ikut_industri ? 'text-emerald-600' : 'text-red-500'">{{
                                yn(keluarga.ikut_industri) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Kerja Bakti</span><span
                                class="font-semibold"
                                :class="keluarga.ikut_kerja_bakti ? 'text-emerald-600' : 'text-red-500'">{{
                                    yn(keluarga.ikut_kerja_bakti) }}</span></div>
                    </div>
                </div>
            </div>

            <!-- ====== DAFTAR ANGGOTA ====== -->
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-gray-200">
                <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4">
                    <h2 class="text-base font-semibold text-gray-800">
                        Anggota Keluarga
                        <span class="ml-2 rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-blue-600">
                            {{ keluarga.anggota_keluargas?.length ?? 0 }}
                        </span>
                    </h2>
                    <button @click="showTambahAnggota = true"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700 active:scale-95">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Anggota
                    </button>
                </div>

                <div v-if="!keluarga.anggota_keluargas?.length" class="py-12 text-center text-sm text-gray-400">
                    Belum ada anggota keluarga terdaftar.
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="w-full text-sm whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50/50">
                                <th
                                    class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                    #</th>
                                <th
                                    class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                    NIK</th>
                                <th
                                    class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                    Nama</th>
                                <th
                                    class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                    L/P</th>
                                <th
                                    class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                    Tgl Lahir</th>
                                <th
                                    class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                    Status KK</th>
                                <th
                                    class="px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                    Pekerjaan</th>
                                <th
                                    class="px-5 py-3 text-center text-[11px] font-semibold uppercase tracking-wide text-gray-400">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <tr v-for="(ak, idx) in keluarga.anggota_keluargas" :key="ak.id"
                                class="group transition hover:bg-gray-50/60">
                                <td class="px-5 py-3.5 text-xs text-gray-400">{{ idx + 1 }}</td>
                                <td class="px-5 py-3.5 font-mono text-xs text-gray-500">{{ ak.nik }}</td>
                                <td class="px-5 py-3.5 font-semibold text-gray-800">{{ ak.nama_anggota }}</td>
                                <td class="px-5 py-3.5">
                                    <span
                                        :class="ak.jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600'"
                                        class="rounded-full px-2 py-0.5 text-[11px] font-semibold">
                                        {{ ak.jenis_kelamin === 'L' ? 'L' : 'P' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-gray-500">{{ formatDate(ak.tanggal_lahir) }}</td>
                                <td class="px-5 py-3.5 text-xs text-gray-500">{{ ak.status_dalam_keluarga ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-xs text-gray-500">{{ ak.pekerjaan || ak.pekerjaan_utama || '-' }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a :href="`/kader/anggota/${ak.id}/edit`"
                                            class="rounded-lg bg-amber-50 px-2.5 py-1.5 text-[11px] font-semibold text-amber-700 transition hover:bg-amber-100">
                                            Edit
                                        </a>
                                        <button @click="openDeleteAnggota(ak.id, ak.nama_anggota)"
                                            class="rounded-lg bg-red-50 px-2.5 py-1.5 text-[11px] font-semibold text-red-600 transition hover:bg-red-100">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ====== MODAL EDIT ANGGOTA ====== -->
        <SidaModal :show="showEditAnggota" title="Edit Data Anggota" max-width="max-w-2xl"
            @close="showEditAnggota = false">
            <form @submit.prevent="submitEditAnggota" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">NIK (16 digit) *</label>
                        <input v-model="editAnggotaForm.nik" type="text" maxlength="16"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 font-mono text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition" />
                        <p v-if="editAnggotaForm.errors.nik" class="mt-1 text-xs text-red-500">{{
                            editAnggotaForm.errors.nik }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Nama Lengkap *</label>
                        <input v-model="editAnggotaForm.nama_anggota" type="text"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition" />
                        <p v-if="editAnggotaForm.errors.nama_anggota" class="mt-1 text-xs text-red-500">{{
                            editAnggotaForm.errors.nama_anggota }}</p>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Jenis Kelamin *</label>
                        <select v-model="editAnggotaForm.jenis_kelamin"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Tanggal Lahir *</label>
                        <input v-model="editAnggotaForm.tanggal_lahir" type="date"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Agama</label>
                        <select v-model="editAnggotaForm.agama"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition">
                            <option value="">-- Pilih --</option>
                            <option v-for="a in agamaOptions" :key="a" :value="a">{{ a }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Pendidikan</label>
                        <select v-model="editAnggotaForm.pendidikan"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition">
                            <option value="">-- Pilih --</option>
                            <option v-for="p in pendidikanOptions" :key="p" :value="p">{{ p }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Pekerjaan</label>
                        <select v-model="editAnggotaForm.pekerjaan"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition">
                            <option value="">-- Pilih --</option>
                            <option v-for="pk in pekerjaanOptions" :key="pk" :value="pk">{{ pk }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Status dalam Keluarga</label>
                        <select v-model="editAnggotaForm.status_dalam_keluarga"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition">
                            <option value="">-- Pilih --</option>
                            <option v-for="s in statusKKOptions" :key="s" :value="s">{{ s }}</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Status Perkawinan</label>
                        <select v-model="editAnggotaForm.status_perkawinan"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition">
                            <option value="">-- Pilih --</option>
                            <option v-for="sk in statusKawinOptions" :key="sk" :value="sk">{{ sk }}</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 border-t border-gray-100 pt-4">
                    <button type="button" @click="showEditAnggota = false"
                        class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" :disabled="editAnggotaForm.processing"
                        class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 disabled:opacity-60 transition">
                        {{ editAnggotaForm.processing ? 'Menyimpan...' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </SidaModal>

        <!-- ====== MODAL TAMBAH ANGGOTA ====== -->
        <SidaModal :show="showTambahAnggota" title="Tambah Anggota Keluarga" max-width="max-w-2xl"
            @close="showTambahAnggota = false">
            <form @submit.prevent="submitTambahAnggota" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">NIK (16 digit) *</label>
                        <input v-model="tambahForm.nik" type="text" maxlength="16" placeholder="16 digit NIK"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 font-mono text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition" />
                        <p v-if="tambahForm.errors.nik" class="mt-1 text-xs text-red-500">{{ tambahForm.errors.nik }}
                        </p>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Nama Lengkap *</label>
                        <input v-model="tambahForm.nama_anggota" type="text"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Jenis Kelamin *</label>
                        <select v-model="tambahForm.jenis_kelamin"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Tanggal Lahir *</label>
                        <input v-model="tambahForm.tanggal_lahir" type="date"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition" />
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Agama</label>
                        <select v-model="tambahForm.agama"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition">
                            <option value="">-- Pilih --</option>
                            <option v-for="a in agamaOptions" :key="a" :value="a">{{ a }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Pendidikan</label>
                        <select v-model="tambahForm.pendidikan"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition">
                            <option value="">-- Pilih --</option>
                            <option v-for="p in pendidikanOptions" :key="p" :value="p">{{ p }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Pekerjaan</label>
                        <select v-model="tambahForm.pekerjaan"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition">
                            <option value="">-- Pilih --</option>
                            <option v-for="pk in pekerjaanOptions" :key="pk" :value="pk">{{ pk }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Status dalam Keluarga</label>
                        <select v-model="tambahForm.status_dalam_keluarga"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition">
                            <option value="">-- Pilih --</option>
                            <option v-for="s in statusKKOptions" :key="s" :value="s">{{ s }}</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-xs font-semibold text-gray-600">Status Perkawinan</label>
                        <select v-model="tambahForm.status_perkawinan"
                            class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition">
                            <option value="">-- Pilih --</option>
                            <option v-for="sk in statusKawinOptions" :key="sk" :value="sk">{{ sk }}</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 border-t border-gray-100 pt-4">
                    <button type="button" @click="showTambahAnggota = false"
                        class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition">Batal</button>
                    <button type="submit" :disabled="tambahForm.processing"
                        class="rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 disabled:opacity-60 transition">
                        {{ tambahForm.processing ? 'Menyimpan...' : 'Tambah Anggota' }}
                    </button>
                </div>
            </form>
        </SidaModal>
    </AppLayout>
</template>
