<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    dasawismas: Array<{ id: number; nama_dasawisma: string; rt: string; rw: string }>;
}>();

const step = ref(1);

const anggotaTemplate = () => ({
    no_reg: '',
    nik: '',
    nama_anggota: '',
    status_dalam_keluarga: 'Anggota Keluarga',
    pekerjaan: '',
    status_perkawinan: 'Lajang',
    jenis_kelamin: 'L',
    agama: 'Islam',
    tempat_lahir: '',
    tanggal_lahir: '',
    umur: '',
    jabatan: '',
    pendidikan: '',
    pekerjaan: '',
    pendidikan_terakhir: '',
    pekerjaan_utama: '',
    dasa_wisma: '',
    nama_kepala_rumah_tangga: '',
    alamat_jalan: '',
    rt: '',
    rw: '',
    desa_kelurahan: '',
    kecamatan: '',
    kabupaten_kota: '',
    provinsi: '',
    akseptor_kb: '0',
    jenis_akseptor_kb: '',
    aktif_posyandu: '0',
    frekuensi_posyandu: '',
    ikut_kelompok_belajar: '0',
    jenis_paket_belajar: '',
    ikut_koperasi: '0',
    jenis_koperasi: '',
    ikut_bina_keluarga_balita: '0',
    memiliki_tabungan: '0',
    ikut_paud_sejenis: '0',
});

const form = useForm({
    no_kk: '',
    nama_kepala_keluarga: '',
    dasawisma_id: '',
    rt: '',
    rw: '',
    dusun_lingkungan: '',
    desa: 'Dompas',
    kecamatan: 'Bukit Batu',
    kabupaten: 'Bengkalis',
    provinsi: 'Riau',
    
    jumlah_kk: 1,
    jumlah_laki_laki: 0,
    jumlah_perempuan: 0,
    jumlah_balita_laki: 0,
    jumlah_balita_perempuan: 0,
    jumlah_pus: 0,
    jumlah_wus: 0,
    jumlah_buta: 0,
    jumlah_ibu_hamil: 0,
    jumlah_ibu_menyusui: 0,
    jumlah_lansia: 0,
    jumlah_berkebutuhan_khusus: 0,
    sehat_layak_huni: false,
    memiliki_tempat_sampah: false,
    memiliki_spal: false,
    memiliki_jamban: false,
    menempel_stiker_p4k: false,
    sumber_air: '',
    makanan_pokok: '',
    ikut_up2k: false,
    ikut_pekarangan: false,
    ikut_industri: false,
    ikut_kerja_bakti: false,
    
    anggota: [anggotaTemplate()],
});

onMounted(() => {
    form.anggota[0].status_dalam_keluarga = 'Kepala Rumah Tangga';
});

function tambahAnggota() {
    const t = anggotaTemplate();
    if (form.anggota.length === 0) {
        t.status_dalam_keluarga = 'Kepala Rumah Tangga';
    }
    form.anggota.push(t);
}

const toast = ref({ show: false, message: '' });
let toastTimeout: any = null;

function showErrorToast(msg: string) {
    toast.value.message = msg;
    toast.value.show = true;
    if (toastTimeout) clearTimeout(toastTimeout);
    toastTimeout = setTimeout(() => {
        toast.value.show = false;
    }, 4000);
}

function hapusAnggota(idx: number) {
    if (form.anggota.length > 1) {
        form.anggota.splice(idx, 1);
    } else {
        showErrorToast('Minimal harus ada 1 anggota (Kepala Rumah Tangga)!');
    }
}

function nextStep() {
    form.clearErrors();
    if (step.value === 1) {
        let hasError = false;
        
        if (!form.no_kk) { form.setError('no_kk', 'Nomor KK wajib diisi.'); hasError = true; }
        else if (form.no_kk.length !== 16) { form.setError('no_kk', 'Nomor KK harus tepat 16 digit.'); hasError = true; }
        
        if (!form.nama_kepala_keluarga) { form.setError('nama_kepala_keluarga', 'Nama Kepala Rumah Tangga wajib diisi.'); hasError = true; }
        if (!form.dasawisma_id) { form.setError('dasawisma_id', 'Dasawisma wajib dipilih.'); hasError = true; }
        
        if (hasError) {
            showErrorToast('Mohon lengkapi field yang ditandai bintang (*) sebelum melanjutkan.');
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return;
        }

        if (form.anggota.length > 0 && form.nama_kepala_keluarga && !form.anggota[0].nama_anggota) {
            form.anggota[0].nama_anggota = form.nama_kepala_keluarga;
        }
    }
    window.scrollTo({ top: 0, behavior: 'smooth' });
    step.value++;
}

function prevStep() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    step.value--;
}

function submit() {
    form.post('/kader/keluarga');
}

const progressWidth = () => {
    if (step.value === 1) return '0%';
    if (step.value === 2) return '50%';
    return '100%';
};
</script>

<template>
    <Head title="Input KK Baru" />
    <AppLayout>
        <!-- Local Toast Alert -->


        <div class="mx-auto max-w-5xl">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Formulir Pendataan Keluarga TP PKK</h2>
                    <p class="text-sm text-gray-500 mt-1">Lengkapi data keluarga dan anggota dengan benar.</p>
                </div>
                <a href="/kader/keluarga" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-emerald-600 transition-colors shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i> Batal & Kembali
                </a>
            </div>
            
            <!-- Modern Error Alert -->
            <div v-if="Object.keys(form.errors).length > 0" class="mb-6 rounded-2xl bg-red-50/80 backdrop-blur-sm border border-red-200 p-5 shadow-sm transition-all duration-300">
                <div class="flex items-start gap-4">
                    <div class="bg-red-100 text-red-600 rounded-xl p-2.5 mt-0.5 shadow-sm">
                        <i class="fa-solid fa-triangle-exclamation text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-red-800">Terdapat Kesalahan Pengisian</h3>
                        <p class="text-xs text-red-600 mt-1 mb-3">Mohon periksa kembali form Anda:</p>
                        <ul class="text-xs text-red-700 space-y-1.5 grid grid-cols-1 md:grid-cols-2 gap-x-4">
                            <li v-for="(error, key) in form.errors" :key="key" class="flex items-start gap-2 bg-white/50 p-2 rounded-lg border border-red-100">
                                <i class="fa-solid fa-circle-xmark text-red-400 mt-0.5 shrink-0"></i>
                                <span class="font-medium leading-tight">{{ error }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- STEPPER INDICATOR -->
            <div class="mb-8">
                <div class="flex items-center justify-between relative">
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 w-full h-1 bg-gray-200 rounded-full z-0"></div>
                    <div class="absolute left-0 top-1/2 transform -translate-y-1/2 h-1 bg-emerald-600 rounded-full z-0 transition-all duration-500 ease-in-out" :style="{ width: progressWidth() }"></div>
                    
                    <div class="relative z-10 flex flex-col items-center">
                        <div :class="['w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md transition-colors duration-300 ring-4 ring-gray-50', step >= 1 ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-500']">
                            <i v-if="step > 1" class="fa-solid fa-check"></i>
                            <span v-else>1</span>
                        </div>
                        <span :class="['mt-2 text-xs font-semibold px-1', step >= 1 ? 'text-emerald-700' : 'text-gray-500']">Data Wilayah</span>
                    </div>
                    
                    <div class="relative z-10 flex flex-col items-center">
                        <div :class="['w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md transition-colors duration-300 ring-4 ring-gray-50', step >= 2 ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-500']">
                            <i v-if="step > 2" class="fa-solid fa-check"></i>
                            <span v-else>2</span>
                        </div>
                        <span :class="['mt-2 text-xs font-semibold px-1', step >= 2 ? 'text-emerald-700' : 'text-gray-500']">Rekap Keluarga</span>
                    </div>
                    
                    <div class="relative z-10 flex flex-col items-center">
                        <div :class="['w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md transition-colors duration-300 ring-4 ring-gray-50', step >= 3 ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-500']">
                            3
                        </div>
                        <span :class="['mt-2 text-xs font-semibold px-1', step >= 3 ? 'text-emerald-700' : 'text-gray-500']">Warga PKK</span>
                    </div>
                </div>
            </div>

            <!-- FORM START -->
            <form @submit.prevent="submit" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                
                <!-- ================= STEP 1 ================= -->
                <div v-show="step === 1" class="p-6 md:p-8 transition-all duration-300">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="bg-indigo-50 text-emerald-600 w-10 h-10 rounded-xl flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-map-location-dot text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Identitas Wilayah & Keluarga</h3>
                            <p class="text-sm text-gray-500">Masukkan data pokok kartu keluarga dan wilayah.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="relative">
                            <input v-model="form.no_kk" type="text" maxlength="16" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer font-mono" placeholder=" " required />
                            <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">No. Kartu Keluarga <span class="text-red-500">*</span></label>
                        </div>
                        
                        <div class="relative lg:col-span-2">
                            <input v-model="form.nama_kepala_keluarga" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " required />
                            <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Nama Kepala Rumah Tangga <span class="text-red-500">*</span></label>
                        </div>
                        
                        <div class="relative">
                            <select v-model="form.dasawisma_id" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer appearance-none" required>
                                <option value="" disabled>-- Pilih Dasawisma --</option>
                                <option v-for="ds in dasawismas" :key="ds.id" :value="ds.id">{{ ds.nama_dasawisma }}</option>
                            </select>
                            <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 font-medium">Dasawisma <span class="text-red-500">*</span></label>
                        </div>
                        
                        <div class="flex gap-4">
                            <div class="relative w-1/2">
                                <input v-model="form.rt" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">RT</label>
                            </div>
                            <div class="relative w-1/2">
                                <input v-model="form.rw" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">RW</label>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <select v-model="form.dusun_lingkungan" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer appearance-none" required>
                                <option value="" disabled>-- Pilih Dusun --</option>
                                <option value="Murni">Murni</option>
                                <option value="Lestari">Lestari</option>
                            </select>
                            <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 font-medium">Dusun / Lingkungan <span class="text-red-500">*</span></label>
                        </div>

                        <div class="relative">
                            <input v-model="form.desa" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-500 bg-gray-100 border border-gray-200" readonly />
                            <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 font-medium">Desa</label>
                        </div>
                        <div class="relative">
                            <input v-model="form.kecamatan" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-500 bg-gray-100 border border-gray-200" readonly />
                            <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 font-medium">Kecamatan</label>
                        </div>
                        <div class="relative">
                            <input :value="form.kabupaten + ', ' + form.provinsi" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-500 bg-gray-100 border border-gray-200" readonly />
                            <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 font-medium">Kab/Kota & Provinsi</label>
                        </div>
                    </div>
                </div>

                <!-- ================= STEP 2 ================= -->
                <div v-show="step === 2" class="p-6 md:p-8 transition-all duration-300">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="bg-teal-50 text-teal-600 w-10 h-10 rounded-xl flex items-center justify-center shadow-sm">
                            <i class="fa-solid fa-chart-pie text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Rekapitulasi Keluarga</h3>
                            <p class="text-sm text-gray-500">Jumlah status dan kategori dalam keluarga.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col items-center justify-center hover:border-emerald-300 transition-colors group">
                            <i class="fa-solid fa-users text-gray-400 group-hover:text-emerald-500 text-xl mb-3 transition-colors"></i>
                            <label class="text-xs font-semibold text-gray-600 mb-2 text-center h-8 flex items-center">Jml KK</label>
                            <input v-model="form.jumlah_kk" type="number" class="w-20 text-center rounded-xl border border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white p-2 font-mono text-lg font-semibold text-emerald-700">
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col items-center justify-center hover:border-emerald-300 transition-colors group">
                            <i class="fa-solid fa-mars text-gray-400 group-hover:text-emerald-500 text-xl mb-3 transition-colors"></i>
                            <label class="text-xs font-semibold text-gray-600 mb-2 text-center h-8 flex items-center">Laki-Laki</label>
                            <input v-model="form.jumlah_laki_laki" type="number" class="w-20 text-center rounded-xl border border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white p-2 font-mono text-lg font-semibold text-emerald-700">
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col items-center justify-center hover:border-emerald-300 transition-colors group">
                            <i class="fa-solid fa-venus text-gray-400 group-hover:text-emerald-500 text-xl mb-3 transition-colors"></i>
                            <label class="text-xs font-semibold text-gray-600 mb-2 text-center h-8 flex items-center">Perempuan</label>
                            <input v-model="form.jumlah_perempuan" type="number" class="w-20 text-center rounded-xl border border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white p-2 font-mono text-lg font-semibold text-emerald-700">
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col items-center justify-center hover:border-emerald-300 transition-colors group">
                            <i class="fa-solid fa-baby text-gray-400 group-hover:text-emerald-500 text-xl mb-3 transition-colors"></i>
                            <label class="text-xs font-semibold text-gray-600 mb-2 text-center h-8 flex items-center">Balita Laki-laki</label>
                            <input v-model="form.jumlah_balita_laki" type="number" class="w-20 text-center rounded-xl border border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white p-2 font-mono text-lg font-semibold text-emerald-700">
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col items-center justify-center hover:border-emerald-300 transition-colors group">
                            <i class="fa-solid fa-baby text-gray-400 group-hover:text-emerald-500 text-xl mb-3 transition-colors"></i>
                            <label class="text-xs font-semibold text-gray-600 mb-2 text-center h-8 flex items-center">Balita Perempuan</label>
                            <input v-model="form.jumlah_balita_perempuan" type="number" class="w-20 text-center rounded-xl border border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white p-2 font-mono text-lg font-semibold text-emerald-700">
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col items-center justify-center hover:border-emerald-300 transition-colors group">
                            <i class="fa-solid fa-venus-mars text-gray-400 group-hover:text-emerald-500 text-xl mb-3 transition-colors"></i>
                            <label class="text-xs font-semibold text-gray-600 mb-2 text-center h-8 flex items-center">Pasangan Usia Subur</label>
                            <input v-model="form.jumlah_pus" type="number" class="w-20 text-center rounded-xl border border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white p-2 font-mono text-lg font-semibold text-emerald-700">
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col items-center justify-center hover:border-emerald-300 transition-colors group">
                            <i class="fa-solid fa-person-dress text-gray-400 group-hover:text-emerald-500 text-xl mb-3 transition-colors"></i>
                            <label class="text-xs font-semibold text-gray-600 mb-2 text-center h-8 flex items-center">Wanita Usia Subur</label>
                            <input v-model="form.jumlah_wus" type="number" class="w-20 text-center rounded-xl border border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white p-2 font-mono text-lg font-semibold text-emerald-700">
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col items-center justify-center hover:border-emerald-300 transition-colors group">
                            <i class="fa-solid fa-eye-slash text-gray-400 group-hover:text-emerald-500 text-xl mb-3 transition-colors"></i>
                            <label class="text-xs font-semibold text-gray-600 mb-2 text-center h-8 flex items-center">3 Buta</label>
                            <input v-model="form.jumlah_buta" type="number" class="w-20 text-center rounded-xl border border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white p-2 font-mono text-lg font-semibold text-emerald-700">
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col items-center justify-center hover:border-emerald-300 transition-colors group">
                            <i class="fa-solid fa-person-pregnant text-gray-400 group-hover:text-emerald-500 text-xl mb-3 transition-colors"></i>
                            <label class="text-xs font-semibold text-gray-600 mb-2 text-center h-8 flex items-center">Ibu Hamil</label>
                            <input v-model="form.jumlah_ibu_hamil" type="number" class="w-20 text-center rounded-xl border border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white p-2 font-mono text-lg font-semibold text-emerald-700">
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col items-center justify-center hover:border-emerald-300 transition-colors group">
                            <i class="fa-solid fa-person-breastfeeding text-gray-400 group-hover:text-emerald-500 text-xl mb-3 transition-colors"></i>
                            <label class="text-xs font-semibold text-gray-600 mb-2 text-center h-8 flex items-center">Ibu Menyusui</label>
                            <input v-model="form.jumlah_ibu_menyusui" type="number" class="w-20 text-center rounded-xl border border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white p-2 font-mono text-lg font-semibold text-emerald-700">
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col items-center justify-center hover:border-emerald-300 transition-colors group">
                            <i class="fa-solid fa-person-cane text-gray-400 group-hover:text-emerald-500 text-xl mb-3 transition-colors"></i>
                            <label class="text-xs font-semibold text-gray-600 mb-2 text-center h-8 flex items-center">Lansia</label>
                            <input v-model="form.jumlah_lansia" type="number" class="w-20 text-center rounded-xl border border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white p-2 font-mono text-lg font-semibold text-emerald-700">
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex flex-col items-center justify-center hover:border-emerald-300 transition-colors group">
                            <i class="fa-solid fa-wheelchair text-gray-400 group-hover:text-emerald-500 text-xl mb-3 transition-colors"></i>
                            <label class="text-xs font-semibold text-gray-600 mb-2 text-center h-8 flex items-center">Berkebutuhan Khusus</label>
                            <input v-model="form.jumlah_berkebutuhan_khusus" type="number" class="w-20 text-center rounded-xl border border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white p-2 font-mono text-lg font-semibold text-emerald-700">
                        </div>
                    </div>

                    <!-- Kriteria Rumah -->
                    <div class="mt-8">
                        <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Kriteria Rumah</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-200">
                                <input v-model="form.sehat_layak_huni" type="checkbox" class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="text-sm font-medium text-gray-700">Sehat / Layak Huni</label>
                            </div>
                            <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-200">
                                <input v-model="form.memiliki_tempat_sampah" type="checkbox" class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="text-sm font-medium text-gray-700">Memiliki Tempat Sampah</label>
                            </div>
                            <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-200">
                                <input v-model="form.memiliki_spal" type="checkbox" class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="text-sm font-medium text-gray-700">Memiliki SPAL</label>
                            </div>
                            <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-200">
                                <input v-model="form.memiliki_jamban" type="checkbox" class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="text-sm font-medium text-gray-700">Memiliki Jamban Keluarga</label>
                            </div>
                            <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-200">
                                <input v-model="form.menempel_stiker_p4k" type="checkbox" class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="text-sm font-medium text-gray-700">Menempel Stiker P4K/PMI/PMK</label>
                            </div>
                        </div>
                    </div>

                    <!-- Sumber Air & Makanan -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Sumber Air Keluarga</h4>
                            <select v-model="form.sumber_air" class="w-full rounded-xl border-gray-300 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none">
                                <option value="">- Pilih Sumber Air -</option>
                                <option value="PDAM">PDAM</option>
                                <option value="Sumur">Sumur</option>
                                <option value="Lainnya">DLL (Lainnya)</option>
                            </select>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Makanan Pokok</h4>
                            <select v-model="form.makanan_pokok" class="w-full rounded-xl border-gray-300 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none">
                                <option value="">- Pilih Makanan Pokok -</option>
                                <option value="Beras">Beras</option>
                                <option value="Non Beras">Non-Beras</option>
                            </select>
                        </div>
                    </div>

                    <!-- Kegiatan Warga -->
                    <div class="mt-8">
                        <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Kegiatan Warga</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-200">
                                <input v-model="form.ikut_up2k" type="checkbox" class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="text-sm font-medium text-gray-700">UP2K</label>
                            </div>
                            <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-200">
                                <input v-model="form.ikut_pekarangan" type="checkbox" class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="text-sm font-medium text-gray-700">Pemanfaatan Tanah Pekarangan</label>
                            </div>
                            <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-200">
                                <input v-model="form.ikut_industri" type="checkbox" class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="text-sm font-medium text-gray-700">Industri Rumah Tangga</label>
                            </div>
                            <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-200">
                                <input v-model="form.ikut_kerja_bakti" type="checkbox" class="w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                                <label class="text-sm font-medium text-gray-700">Kerja Bakti</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= STEP 3 ================= -->
                <div v-show="step === 3" class="p-6 md:p-8 bg-gray-50/50 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
                        <div class="flex items-center gap-3">
                            <div class="bg-emerald-50 text-emerald-600 w-10 h-10 rounded-xl flex items-center justify-center shadow-sm">
                                <i class="fa-solid fa-users-viewfinder text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Detail Anggota Warga PKK</h3>
                                <p class="text-sm text-gray-500">Masukkan detail tiap anggota keluarga.</p>
                            </div>
                        </div>
                        <button type="button" @click="tambahAnggota" class="inline-flex items-center justify-center gap-2 bg-white border border-emerald-200 text-emerald-700 hover:bg-emerald-50 px-4 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm">
                            <i class="fa-solid fa-user-plus"></i> Tambah Anggota
                        </button>
                    </div>
                    
                    <div class="space-y-6">
                        <div v-for="(anggota, idx) in form.anggota" :key="idx" class="bg-white p-6 rounded-3xl border border-gray-200 shadow-sm relative group transition-all duration-300">
                            <button type="button" @click="hapusAnggota(idx)" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 bg-gray-50 hover:bg-red-50 p-2.5 rounded-xl transition-all border border-transparent hover:border-red-200 shadow-sm" title="Hapus Anggota">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                            
                            <div class="flex items-center mb-6 pb-4 border-b border-gray-100 gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-sm">{{ idx + 1 }}</div>
                                <span :class="['text-sm font-bold uppercase tracking-wide', idx === 0 ? 'text-emerald-700' : 'text-gray-800']">
                                    {{ idx === 0 ? 'Kepala Rumah Tangga (Wajib)' : 'Anggota Keluarga' }}
                                    <span v-if="idx === 0" class="ml-1 text-[10px] text-gray-400 normal-case tracking-normal font-normal">(Status lebih detail bisa diubah di Detail KK)</span>
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                                <!-- Data Pokok -->
                                <div class="relative">
                                    <input v-model="anggota.no_reg" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">No. Registrasi</label>
                                </div>
                                <div class="relative">
                                    <input v-model="anggota.nik" type="text" maxlength="16" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer font-mono" placeholder=" " required />
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">NIK (No KTP) <span class="text-red-500">*</span></label>
                                </div>
                                <div class="relative md:col-span-2">
                                    <input v-model="anggota.nama_anggota" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " required />
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Nama Lengkap <span class="text-red-500">*</span></label>
                                </div>

                                <div class="relative">
                                    <select v-model="anggota.status_dalam_keluarga" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer appearance-none" required>
                                        <option value="Kepala Rumah Tangga">Kepala Rumah Tangga</option>
                                        <option value="Istri">Istri</option>
                                        <option value="Anak">Anak</option>
                                        <option value="Menantu">Menantu</option>
                                        <option value="Cucu">Cucu</option>
                                        <option value="Orang Tua">Orang Tua</option>
                                        <option value="Mertua">Mertua</option>
                                        <option value="Anggota Keluarga">Anggota Keluarga</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 font-medium">Status Dlm Keluarga <span class="text-red-500">*</span></label>
                                </div>
                                <div class="relative">
                                    <select v-model="anggota.status_perkawinan" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer appearance-none">
                                        <option value="Lajang">Lajang</option>
                                        <option value="Menikah">Menikah</option>
                                        <option value="Janda">Janda</option>
                                        <option value="Duda">Duda</option>
                                    </select>
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 font-medium">Status Perkawinan</label>
                                </div>
                                <div class="relative">
                                    <select v-model="anggota.jenis_kelamin" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer appearance-none" required>
                                        <option value="L">Laki-Laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 font-medium">Jenis Kelamin <span class="text-red-500">*</span></label>
                                </div>
                                <div class="relative">
                                    <select v-model="anggota.agama" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer appearance-none">
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen Protestan">Kristen Protestan</option>
                                        <option value="Kristen Katolik">Kristen Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Konghucu">Konghucu</option>
                                    </select>
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 font-medium">Agama</label>
                                </div>
                                
                                <div class="relative">
                                    <input v-model="anggota.tempat_lahir" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Tempat Lahir</label>
                                </div>
                                <div class="relative">
                                    <input v-model="anggota.tanggal_lahir" type="date" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" required />
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 font-medium">Tanggal Lahir <span class="text-red-500">*</span></label>
                                </div>
                                <div class="relative">
                                    <input v-model="anggota.umur" type="number" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Umur</label>
                                </div>
                                <div class="relative">
                                    <input v-model="anggota.jabatan" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Jabatan PKK</label>
                                </div>

                                <!-- Pendidikan & Pekerjaan -->
                                <div class="relative md:col-span-2">
                                    <select v-model="anggota.pendidikan_terakhir" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer appearance-none">
                                        <option value="">- Pilih -</option>
                                        <option value="Tidak Tamat SD">Tidak Tamat SD</option>
                                        <option value="SD/MI">SD/MI</option>
                                        <option value="SMP">SMP</option>
                                        <option value="SMU/SMK">SMU/SMK</option>
                                        <option value="Diploma">Diploma</option>
                                        <option value="S1">S1</option>
                                        <option value="S2">S2</option>
                                        <option value="S3">S3</option>
                                    </select>
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 font-medium">Pendidikan Terakhir</label>
                                </div>
                                <div class="relative">
                                    <select v-model="anggota.pekerjaan" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer appearance-none">
                                        <option value="">- Pilih -</option>
                                        <option value="Tidak Bekerja">Tidak Bekerja</option>
                                        <option value="Petani">Petani</option>
                                        <option value="Nelayan">Nelayan</option>
                                        <option value="Pedagang">Pedagang</option>
                                        <option value="PNS">PNS</option>
                                        <option value="Swasta">Swasta</option>
                                        <option value="Wiraswasta">Wiraswasta</option>
                                        <option value="TNI/Polri">TNI/Polri</option>
                                        <option value="Pelajar/Mahasiswa">Pelajar/Mahasiswa</option>
                                        <option value="Ibu Rumah Tangga">Ibu Rumah Tangga</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 font-medium">Pekerjaan</label>
                                </div>
                                <div class="relative">
                                    <input v-model="anggota.pekerjaan_utama" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Pekerjaan Utama</label>
                                </div>

                                <!-- Data Wilayah Individu -->
                                <div class="md:col-span-4 mt-6 border-t border-gray-100 pt-6">
                                    <span class="text-sm font-bold text-gray-700 uppercase tracking-wider flex items-center gap-2"><i class="fa-solid fa-map-pin text-emerald-500"></i> Alamat & Identitas Wilayah</span>
                                </div>
                                <div class="relative">
                                    <input v-model="anggota.dasa_wisma" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Dasa Wisma</label>
                                </div>
                                <div class="relative">
                                    <input v-model="anggota.nama_kepala_rumah_tangga" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Kepala Rumah Tangga</label>
                                </div>
                                <div class="relative md:col-span-2">
                                    <input v-model="anggota.alamat_jalan" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Alamat / Jalan</label>
                                </div>
                                <div class="flex gap-2">
                                    <div class="relative w-1/2">
                                        <input v-model="anggota.rt" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />
                                        <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">RT</label>
                                    </div>
                                    <div class="relative w-1/2">
                                        <input v-model="anggota.rw" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />
                                        <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">RW</label>
                                    </div>
                                </div>
                                <div class="relative">
                                    <input v-model="anggota.desa_kelurahan" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Desa/Kelurahan</label>
                                </div>
                                <div class="relative">
                                    <input v-model="anggota.kecamatan" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Kecamatan</label>
                                </div>
                                <div class="relative">
                                    <input v-model="anggota.kabupaten_kota" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />
                                    <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Kab/Kota</label>
                                </div>
                                
                                <!-- Data PKK Khusus -->
                                <div class="md:col-span-4 mt-6 border-t border-emerald-100 pt-6">
                                    <span class="text-sm font-bold text-emerald-700 uppercase tracking-wider bg-emerald-50 px-3 py-1.5 rounded-lg flex items-center gap-2 w-fit"><i class="fa-solid fa-leaf"></i> Data Khusus Warga PKK</span>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 hover:border-emerald-200 transition-colors">
                                    <label class="block text-xs font-semibold text-gray-700 mb-2">Akseptor KB?</label>
                                    <select v-model="anggota.akseptor_kb" class="w-full rounded-xl border-gray-300 p-2.5 text-sm bg-white focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                    <div v-if="anggota.akseptor_kb === '1'" class="mt-3 pt-3 border-t border-gray-200">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Akseptor KB</label>
                                        <input v-model="anggota.jenis_akseptor_kb" type="text" class="w-full rounded-xl border border-gray-300 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none">
                                    </div>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 hover:border-emerald-200 transition-colors">
                                    <label class="block text-xs font-semibold text-gray-700 mb-2">Aktif Posyandu?</label>
                                    <select v-model="anggota.aktif_posyandu" class="w-full rounded-xl border-gray-300 p-2.5 text-sm bg-white focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                    <div v-if="anggota.aktif_posyandu === '1'" class="mt-3 pt-3 border-t border-gray-200">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Frekuensi Posyandu</label>
                                        <input v-model="anggota.frekuensi_posyandu" type="text" class="w-full rounded-xl border border-gray-300 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none">
                                    </div>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 hover:border-emerald-200 transition-colors">
                                    <label class="block text-xs font-semibold text-gray-700 mb-2">Ikut Kelompok Belajar?</label>
                                    <select v-model="anggota.ikut_kelompok_belajar" class="w-full rounded-xl border-gray-300 p-2.5 text-sm bg-white focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                    <div v-if="anggota.ikut_kelompok_belajar === '1'" class="mt-3 pt-3 border-t border-gray-200">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Paket Belajar</label>
                                        <select v-model="anggota.jenis_paket_belajar" class="w-full rounded-xl border border-gray-300 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none">
                                            <option value="">-Pilih-</option>
                                            <option value="Paket A">Paket A</option>
                                            <option value="Paket B">Paket B</option>
                                            <option value="Paket C">Paket C</option>
                                            <option value="KF">KF</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 hover:border-emerald-200 transition-colors">
                                    <label class="block text-xs font-semibold text-gray-700 mb-2">Ikut Koperasi?</label>
                                    <select v-model="anggota.ikut_koperasi" class="w-full rounded-xl border-gray-300 p-2.5 text-sm bg-white focus:ring-emerald-500 focus:border-emerald-500 shadow-sm">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                    <div v-if="anggota.ikut_koperasi === '1'" class="mt-3 pt-3 border-t border-gray-200">
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Koperasi</label>
                                        <input v-model="anggota.jenis_koperasi" type="text" class="w-full rounded-xl border border-gray-300 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none">
                                    </div>
                                </div>

                                <!-- Boolean fields only -->
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 hover:border-emerald-200 transition-colors">
                                    <label class="mb-1.5 block text-xs font-semibold text-gray-600">Nama Kepala Rumah Tangga <span class="text-red-500">*</span></label>
                                    <select v-model="anggota.ikut_bina_keluarga_balita" class="w-full rounded-xl border-gray-300 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 outline-none">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 hover:border-emerald-200 transition-colors">
                                    <label class="block text-xs font-semibold text-gray-700 mb-2">Memiliki Tabungan?</label>
                                    <select v-model="anggota.memiliki_tabungan" class="w-full rounded-xl border-gray-300 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 outline-none">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 hover:border-emerald-200 transition-colors md:col-span-2">
                                    <label class="block text-xs font-semibold text-gray-700 mb-2">Ikut PAUD Sejenis?</label>
                                    <select v-model="anggota.ikut_paud_sejenis" class="w-full rounded-xl border-gray-300 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 outline-none">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FORM FOOTER (Navigation Buttons) -->
                <div class="bg-gray-50 border-t border-gray-100 p-6 flex items-center justify-between">
                    <button v-if="step > 1" type="button" @click="prevStep" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-gray-300 bg-white text-gray-700 font-semibold hover:bg-gray-100 transition-colors shadow-sm">
                        <i class="fa-solid fa-arrow-left"></i> Sebelumnya
                    </button>
                    <div v-else></div>
                    
                    <button v-if="step < 3" type="button" @click="nextStep" class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition-colors shadow-md">
                        Selanjutnya <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    
                    <button v-if="step === 3" type="submit" :disabled="form.processing" class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold hover:from-emerald-600 hover:to-teal-600 transition-all shadow-md transform hover:scale-[1.02] disabled:opacity-75 disabled:scale-100">
                        <i v-if="!form.processing" class="fa-solid fa-floppy-disk"></i>
                        <span v-if="form.processing" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Data KK' }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
