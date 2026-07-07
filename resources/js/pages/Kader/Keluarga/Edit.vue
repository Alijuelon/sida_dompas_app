<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    keluarga: any;
    dasawismas: Array<{ id: number; nama_dasawisma: string; rt: string; rw: string }>;
}>();

const step = ref(1);

const form = useForm({
    no_kk: props.keluarga.no_kk || '',
    nama_kepala_keluarga: props.keluarga.nama_kepala_keluarga || '',
    dasawisma_id: props.keluarga.dasawisma_id || '',
    rt: props.keluarga.rt || '',
    rw: props.keluarga.rw || '',
    dusun_lingkungan: props.keluarga.dusun_lingkungan || '',
    desa: props.keluarga.desa || 'Dompas',
    kecamatan: props.keluarga.kecamatan || 'Bukit Batu',
    kabupaten: props.keluarga.kabupaten || 'Bengkalis',
    provinsi: props.keluarga.provinsi || 'Riau',
    jumlah_kk: props.keluarga.jumlah_kk ?? 1,
    jumlah_laki_laki: props.keluarga.jumlah_laki_laki ?? 0,
    jumlah_perempuan: props.keluarga.jumlah_perempuan ?? 0,
    jumlah_balita: props.keluarga.jumlah_balita ?? 0,
    jumlah_pus: props.keluarga.jumlah_pus ?? 0,
    jumlah_wus: props.keluarga.jumlah_wus ?? 0,
    jumlah_buta: props.keluarga.jumlah_buta ?? 0,
    jumlah_ibu_hamil: props.keluarga.jumlah_ibu_hamil ?? 0,
    jumlah_ibu_menyusui: props.keluarga.jumlah_ibu_menyusui ?? 0,
    jumlah_lansia: props.keluarga.jumlah_lansia ?? 0,
    jumlah_berkebutuhan_khusus: props.keluarga.jumlah_berkebutuhan_khusus ?? 0,
    sehat_layak_huni: !!props.keluarga.sehat_layak_huni,
    memiliki_tempat_sampah: !!props.keluarga.memiliki_tempat_sampah,
    memiliki_spal: !!props.keluarga.memiliki_spal,
    memiliki_jamban: !!props.keluarga.memiliki_jamban,
    menempel_stiker_p4k: !!props.keluarga.menempel_stiker_p4k,
    sumber_air: props.keluarga.sumber_air || '',
    makanan_pokok: props.keluarga.makanan_pokok || '',
    ikut_up2k: !!props.keluarga.ikut_up2k,
    ikut_pekarangan: !!props.keluarga.ikut_pekarangan,
    ikut_industri: !!props.keluarga.ikut_industri,
    ikut_kerja_bakti: !!props.keluarga.ikut_kerja_bakti,
});

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

function nextStep() {
    form.clearErrors();
    if (step.value === 1) {
        let hasError = false;
        
        if (!form.no_kk) { form.setError('no_kk', 'Nomor KK wajib diisi.'); hasError = true; }
        else if (form.no_kk.length !== 16) { form.setError('no_kk', 'Nomor KK harus tepat 16 digit.'); hasError = true; }
        
        if (!form.nama_kepala_keluarga) { form.setError('nama_kepala_keluarga', 'Nama Kepala Keluarga wajib diisi.'); hasError = true; }
        if (!form.dasawisma_id) { form.setError('dasawisma_id', 'Dasawisma wajib dipilih.'); hasError = true; }
        
        if (hasError) {
            showErrorToast('Mohon lengkapi field yang ditandai bintang (*) sebelum melanjutkan.');
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return;
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
    form.put(`/kader/keluarga/${props.keluarga.id}`);
}

const progressWidth = () => {
    if (step.value === 1) return '0%';
    return '100%';
};
</script>

<template>
    <Head :title="`Edit KK - ${keluarga.nama_kepala_keluarga}`" />
    <AppLayout>
        <!-- Local Toast Alert -->


        <div class="mx-auto max-w-5xl">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Data Kartu Keluarga (KK)</h2>
                    <p class="text-sm text-gray-500 mt-1">Sistem Informasi Dasawisma (SIDA) Dompas</p>
                </div>
                <a :href="`/kader/keluarga/${keluarga.id}`" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-emerald-600 transition-colors shadow-sm">
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
                <div class="flex items-center justify-between relative px-20">
                    <div class="absolute left-20 right-20 top-1/2 transform -translate-y-1/2 h-1 bg-gray-200 rounded-full z-0"></div>
                    <div class="absolute left-20 top-1/2 transform -translate-y-1/2 h-1 bg-emerald-600 rounded-full z-0 transition-all duration-500 ease-in-out" :style="{ width: progressWidth(), right: '5rem' }"></div>
                    
                    <div class="relative z-10 flex flex-col items-center">
                        <div :class="['w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md transition-colors duration-300 ring-4 ring-gray-50', step >= 1 ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-500']">
                            <i v-if="step > 1" class="fa-solid fa-check"></i>
                            <span v-else>1</span>
                        </div>
                        <span :class="['mt-2 text-xs font-semibold px-1', step >= 1 ? 'text-emerald-700' : 'text-gray-500']">Data Wilayah</span>
                    </div>
                    
                    <div class="relative z-10 flex flex-col items-center">
                        <div :class="['w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md transition-colors duration-300 ring-4 ring-gray-50', step >= 2 ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-500']">
                            2
                        </div>
                        <span :class="['mt-2 text-xs font-semibold px-1', step >= 2 ? 'text-emerald-700' : 'text-gray-500']">Rekap Keluarga</span>
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
                            <p class="text-sm text-gray-500">Ubah data pokok kartu keluarga dan wilayah.</p>
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
                            <p class="text-sm text-gray-500">Perbarui jumlah status dan kategori dalam keluarga.</p>
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
                            <label class="text-xs font-semibold text-gray-600 mb-2 text-center h-8 flex items-center">Balita</label>
                            <input v-model="form.jumlah_balita" type="number" class="w-20 text-center rounded-xl border border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 bg-white p-2 font-mono text-lg font-semibold text-emerald-700">
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

                <!-- FORM FOOTER (Navigation Buttons) -->
                <div class="bg-gray-50 border-t border-gray-100 p-6 flex items-center justify-between">
                    <button v-if="step > 1" type="button" @click="prevStep" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl border border-gray-300 bg-white text-gray-700 font-semibold hover:bg-gray-100 transition-colors shadow-sm">
                        <i class="fa-solid fa-arrow-left"></i> Sebelumnya
                    </button>
                    <div v-else></div>
                    
                    <button v-if="step < 2" type="button" @click="nextStep" class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition-colors shadow-md">
                        Selanjutnya <i class="fa-solid fa-arrow-right"></i>
                    </button>
                    
                    <button v-if="step === 2" type="submit" :disabled="form.processing" class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold hover:from-emerald-600 hover:to-teal-600 transition-all shadow-md transform hover:scale-[1.02] disabled:opacity-75 disabled:scale-100">
                        <i v-if="!form.processing" class="fa-solid fa-floppy-disk"></i>
                        <span v-if="form.processing" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
