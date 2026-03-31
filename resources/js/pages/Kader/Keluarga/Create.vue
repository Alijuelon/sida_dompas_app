<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps<{
    dasawismas: Array<{ id: number; nama_dasawisma: string; rt: string; rw: string }>;
}>();

const step = ref(1);

const anggotaTemplate = () => ({
    nik: '',
    nama_anggota: '',
    jenis_kelamin: 'L',
    tanggal_lahir: '',
    agama: '',
    pendidikan: '',
    pekerjaan: '',
    status_dalam_keluarga: 'Kepala Keluarga',
    status_perkawinan: '',
});

const form = useForm({
    dasawisma_id: '',
    no_kk: '',
    nama_kepala_keluarga: '',
    anggota: [anggotaTemplate()],
});

function tambahAnggota() {
    const t = anggotaTemplate();
    t.status_dalam_keluarga = '';
    form.anggota.push(t);
}

function hapusAnggota(idx: number) {
    if (form.anggota.length > 1) form.anggota.splice(idx, 1);
}

function nextStep() {
    if (!form.dasawisma_id || !form.no_kk || !form.nama_kepala_keluarga) {
        alert('Lengkapi semua data KK terlebih dahulu!');
        return;
    }
    step.value = 2;
}

function submit() {
    form.post('/kader/keluarga');
}

const agamaOptions = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
const pendidikanOptions = ['Tidak Sekolah', 'SD', 'SMP', 'SMA/SMK', 'D1/D2/D3', 'S1', 'S2', 'S3'];
const pekerjaanOptions = ['Tidak Bekerja', 'Petani', 'Nelayan', 'Pedagang', 'PNS', 'Swasta', 'Wiraswasta', 'TNI/Polri', 'Pelajar/Mahasiswa', 'Ibu Rumah Tangga', 'Lainnya'];
const statusKKOptions = ['Kepala Keluarga', 'Istri', 'Anak', 'Menantu', 'Cucu', 'Orang Tua', 'Mertua', 'Lainnya'];
const statusKawinOptions = ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'];

/* Shared input & select classes */
const inputCls  = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-400 shadow-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition';
const selectCls = 'w-full rounded-xl border border-gray-200 bg-white px-3.5 py-2.5 text-sm text-gray-800 shadow-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-100 transition appearance-none';
const labelCls  = 'mb-1.5 block text-xs font-semibold text-gray-600 uppercase tracking-wide';
</script>

<template>
    <Head title="Input KK Baru" />
    <AppLayout>
        <div class="mx-auto max-w-3xl">
            <!-- Header -->
            <div class="mb-6">
                <a href="/kader/keluarga" class="mb-3 inline-flex items-center gap-1.5 text-sm font-medium text-gray-400 hover:text-emerald-600 transition">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Daftar KK
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Input Data KK Baru</h1>
                <p class="mt-0.5 text-sm text-gray-500">Data akan otomatis dikirimkan ke admin untuk verifikasi</p>
            </div>

            <!-- Step Indicator -->
            <div class="mb-8 flex items-center gap-3">
                <div class="flex items-center gap-2.5">
                    <div :class="step >= 1 ? 'bg-emerald-600 text-white shadow-sm' : 'bg-gray-100 text-gray-400 border border-gray-200'"
                        class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold transition-all">1</div>
                    <span class="text-sm font-semibold text-gray-700">Data Kartu Keluarga</span>
                </div>
                <div class="h-px flex-1 rounded-full" :class="step >= 2 ? 'bg-emerald-400' : 'bg-gray-200'"></div>
                <div class="flex items-center gap-2.5">
                    <div :class="step >= 2 ? 'bg-emerald-600 text-white shadow-sm' : 'bg-gray-100 text-gray-400 border border-gray-200'"
                        class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold transition-all">2</div>
                    <span class="text-sm font-semibold text-gray-700">Data Anggota Keluarga</span>
                </div>
            </div>

            <form @submit.prevent="submit">
                <!-- ============ STEP 1 ============ -->
                <div v-if="step === 1" class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
                    <!-- Card header -->
                    <div class="border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-teal-50 px-6 py-4">
                        <h2 class="text-base font-bold text-gray-800">Langkah 1 — Data Kartu Keluarga</h2>
                        <p class="mt-0.5 text-xs text-gray-500">Isi informasi KK dengan lengkap dan benar</p>
                    </div>
                    <div class="space-y-5 px-6 py-6">
                        <!-- Dasawisma -->
                        <div>
                            <label :class="labelCls">Dasawisma <span class="text-red-500 normal-case">*</span></label>
                            <div class="relative">
                                <select v-model="form.dasawisma_id" :class="selectCls">
                                    <option value="" class="text-gray-400">-- Pilih Dasawisma --</option>
                                    <option v-for="ds in dasawismas" :key="ds.id" :value="ds.id" class="text-gray-800">
                                        {{ ds.nama_dasawisma }} (RT {{ ds.rt }}/RW {{ ds.rw }})
                                    </option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            <p v-if="form.errors.dasawisma_id" class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ form.errors.dasawisma_id }}
                            </p>
                        </div>

                        <!-- No KK -->
                        <div>
                            <label :class="labelCls">Nomor KK <span class="text-red-500 normal-case">*</span></label>
                            <input v-model="form.no_kk" type="text" maxlength="16" placeholder="16 digit nomor KK" :class="inputCls" class="font-mono" />
                            <p v-if="form.errors.no_kk" class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ form.errors.no_kk }}
                            </p>
                        </div>

                        <!-- Nama KK -->
                        <div>
                            <label :class="labelCls">Nama Kepala Keluarga <span class="text-red-500 normal-case">*</span></label>
                            <input v-model="form.nama_kepala_keluarga" type="text" placeholder="Nama lengkap sesuai KK" :class="inputCls" />
                            <p v-if="form.errors.nama_kepala_keluarga" class="mt-1.5 flex items-center gap-1 text-xs text-red-600">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ form.errors.nama_kepala_keluarga }}
                            </p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center gap-3 border-t border-gray-100 bg-gray-50/50 px-6 py-4">
                        <button type="button" @click="nextStep"
                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 active:scale-95">
                            Selanjutnya
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                        <a href="/kader/keluarga" class="text-sm font-medium text-gray-500 transition hover:text-gray-700">Batal</a>
                    </div>
                </div>

                <!-- ============ STEP 2 ============ -->
                <div v-if="step === 2" class="space-y-4">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="text-base font-bold text-gray-800">Langkah 2 — Data Anggota Keluarga</h2>
                            <p class="text-xs text-gray-500">Minimal 1 anggota (kepala keluarga)</p>
                        </div>
                        <button type="button" @click="tambahAnggota"
                            class="inline-flex items-center gap-1.5 rounded-xl border border-emerald-200 bg-emerald-50 px-3.5 py-2 text-xs font-bold text-emerald-700 transition hover:bg-emerald-100 active:scale-95">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Anggota
                        </button>
                    </div>

                    <div v-for="(anggota, idx) in form.anggota" :key="idx"
                        class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-gray-100">
                        <!-- Card header -->
                        <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/70 px-5 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-100 text-xs font-bold text-emerald-700">
                                    {{ idx + 1 }}
                                </div>
                                <span class="text-sm font-semibold text-gray-700">
                                    {{ idx === 0 ? 'Kepala Keluarga' : `Anggota ke-${idx + 1}` }}
                                </span>
                            </div>
                            <button v-if="form.anggota.length > 1" type="button" @click="hapusAnggota(idx)"
                                class="rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-100">
                                Hapus
                            </button>
                        </div>

                        <div class="grid gap-4 p-5 md:grid-cols-2">
                            <div>
                                <label :class="labelCls">NIK (16 digit) <span class="text-red-500 normal-case">*</span></label>
                                <input v-model="anggota.nik" type="text" maxlength="16" placeholder="NIK 16 digit" :class="inputCls" class="font-mono" />
                                <p v-if="form.errors[`anggota.${idx}.nik`]" class="mt-1 text-xs text-red-600">{{ form.errors[`anggota.${idx}.nik`] }}</p>
                            </div>
                            <div>
                                <label :class="labelCls">Nama Lengkap <span class="text-red-500 normal-case">*</span></label>
                                <input v-model="anggota.nama_anggota" type="text" placeholder="Nama lengkap" :class="inputCls" />
                            </div>
                            <div>
                                <label :class="labelCls">Jenis Kelamin <span class="text-red-500 normal-case">*</span></label>
                                <div class="relative">
                                    <select v-model="anggota.jenis_kelamin" :class="selectCls">
                                        <option value="L" class="text-gray-800">Laki-laki</option>
                                        <option value="P" class="text-gray-800">Perempuan</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label :class="labelCls">Tanggal Lahir <span class="text-red-500 normal-case">*</span></label>
                                <input v-model="anggota.tanggal_lahir" type="date" :class="inputCls" />
                            </div>
                            <div>
                                <label :class="labelCls">Agama</label>
                                <div class="relative">
                                    <select v-model="anggota.agama" :class="selectCls">
                                        <option value="" class="text-gray-400">-- Pilih --</option>
                                        <option v-for="a in agamaOptions" :key="a" :value="a" class="text-gray-800">{{ a }}</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label :class="labelCls">Pendidikan</label>
                                <div class="relative">
                                    <select v-model="anggota.pendidikan" :class="selectCls">
                                        <option value="" class="text-gray-400">-- Pilih --</option>
                                        <option v-for="p in pendidikanOptions" :key="p" :value="p" class="text-gray-800">{{ p }}</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label :class="labelCls">Pekerjaan</label>
                                <div class="relative">
                                    <select v-model="anggota.pekerjaan" :class="selectCls">
                                        <option value="" class="text-gray-400">-- Pilih --</option>
                                        <option v-for="pk in pekerjaanOptions" :key="pk" :value="pk" class="text-gray-800">{{ pk }}</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label :class="labelCls">Status dalam Keluarga</label>
                                <div class="relative">
                                    <select v-model="anggota.status_dalam_keluarga" :class="selectCls">
                                        <option value="" class="text-gray-400">-- Pilih --</option>
                                        <option v-for="s in statusKKOptions" :key="s" :value="s" class="text-gray-800">{{ s }}</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label :class="labelCls">Status Perkawinan</label>
                                <div class="relative">
                                    <select v-model="anggota.status_perkawinan" :class="selectCls">
                                        <option value="" class="text-gray-400">-- Pilih --</option>
                                        <option v-for="sk in statusKawinOptions" :key="sk" :value="sk" class="text-gray-800">{{ sk }}</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Step 2 -->
                    <div class="flex items-center gap-3 rounded-2xl bg-white px-6 py-4 shadow-sm ring-1 ring-gray-100">
                        <button type="submit" :disabled="form.processing"
                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700 disabled:opacity-60 active:scale-95">
                            <svg v-if="!form.processing" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ form.processing ? 'Menyimpan...' : 'Simpan & Kirim Verifikasi' }}
                        </button>
                        <button type="button" @click="step = 1"
                            class="inline-flex items-center gap-1.5 rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50 active:scale-95">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Kembali
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
