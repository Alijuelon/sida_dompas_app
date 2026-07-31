import re

content = """<template>
    <AppLayout title="Edit Anggota Keluarga" :breadcrumbs="[
        { name: 'Dashboard', href: '/kader/dashboard' },
        { name: 'Data KK & Anggota', href: '/kader/keluarga' },
        { name: 'Detail KK', href: `/kader/keluarga/${anggotaKeluarga.keluarga_id}` },
        { name: 'Edit Anggota Keluarga', href: '#' }
    ]">
        <div class="mx-auto max-w-5xl py-6 sm:px-6 lg:px-8">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Edit Anggota Warga PKK</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Perbarui detail anggota keluarga atas nama <strong class="text-gray-700">{{ anggotaKeluarga.nama_anggota }}</strong>
                    </p>
                </div>
                <a :href="`/kader/keluarga/${anggotaKeluarga.keluarga_id}`"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-600 shadow-sm transition hover:bg-gray-50 hover:text-emerald-600">
                    <i class="fa-solid fa-arrow-left"></i> Batal & Kembali
                </a>
            </div>

            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm md:p-8">
                <!-- Info Section -->
                <div class="mb-8 flex flex-col items-start gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="rounded-xl border border-emerald-100 bg-emerald-50/50 p-4 w-full sm:w-auto">
                        <p class="text-sm font-semibold text-emerald-800">
                            Total Anggota Aktif di KK ini: <strong>{{ totalAnggotaAktif }}</strong> Orang
                        </p>
                        <p class="mt-1 text-xs italic text-emerald-600">
                            *Penjelasan: Jumlah di atas merupakan total anggota keluarga yang masih aktif dan didata dalam kegiatan PKK.
                        </p>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-8">
                    <!-- DATA DIRI POKOK -->
                    <div>
                        <div class="mb-4 flex items-center gap-3 border-b border-gray-100 pb-2">
                            <div class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-600">
                                1
                            </div>
                            <h3 class="font-bold text-gray-700">DATA DIRI POKOK</h3>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="relative">
                                <input v-model="form.no_reg" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">No. Registrasi</label>
                            </div>
                            <div class="relative">
                                <input v-model="form.nik" type="text" maxlength="16" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer font-mono" placeholder=" " required />
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">NIK (No KTP) <span class="text-red-500">*</span></label>
                                <p v-if="form.errors.nik" class="mt-1 text-xs text-red-500">{{ form.errors.nik }}</p>
                            </div>
                            <div class="relative md:col-span-2">
                                <input v-model="form.nama_anggota" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " required />
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Nama Lengkap <span class="text-red-500">*</span></label>
                                <p v-if="form.errors.nama_anggota" class="mt-1 text-xs text-red-500">{{ form.errors.nama_anggota }}</p>
                            </div>

                            <div class="relative">
                                <select v-model="form.status_dalam_keluarga" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer appearance-none" required>
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
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Status Dlm Keluarga <span class="text-red-500">*</span></label>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>

                            <div class="relative">
                                <select v-model="form.status_perkawinan" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer appearance-none" required>
                                    <option value="Belum Kawin">Belum Kawin</option>
                                    <option value="Kawin">Kawin</option>
                                    <option value="Cerai Hidup">Cerai Hidup</option>
                                    <option value="Cerai Mati">Cerai Mati</option>
                                </select>
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Status Perkawinan <span class="text-red-500">*</span></label>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>

                            <div class="relative">
                                <select v-model="form.jenis_kelamin" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer appearance-none" required>
                                    <option value="L">Laki-Laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>

                            <div class="relative">
                                <select v-model="form.agama" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer appearance-none" required>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Agama <span class="text-red-500">*</span></label>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>

                            <div class="relative">
                                <input v-model="form.tempat_lahir" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " required />
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Tempat Lahir <span class="text-red-500">*</span></label>
                            </div>

                            <div class="relative">
                                <input v-model="form.tanggal_lahir" type="date" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" required />
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Tanggal Lahir <span class="text-red-500">*</span></label>
                            </div>

                            <div class="relative">
                                <input v-model="form.umur" type="number" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " required />
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Umur <span class="text-red-500">*</span></label>
                            </div>

                            <div class="relative">
                                <input v-model="form.jabatan" type="text" :disabled="form.jenis_kelamin === 'L'" :class="{'opacity-50 cursor-not-allowed bg-gray-100': form.jenis_kelamin === 'L'}" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Jabatan PKK</label>
                            </div>

                            <div class="relative md:col-span-2">
                                <select v-model="form.pendidikan" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer appearance-none" required>
                                    <option value="">- Pilih -</option>
                                    <option value="Tidak Sekolah">Tidak Sekolah</option>
                                    <option value="SD">SD</option>
                                    <option value="SMP">SMP</option>
                                    <option value="SMA/SMK">SMA/SMK</option>
                                    <option value="D1/D2/D3">D1/D2/D3</option>
                                    <option value="S1">S1</option>
                                    <option value="S2">S2</option>
                                    <option value="S3">S3</option>
                                </select>
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                            
                            <div class="relative md:col-span-2">
                                <select v-model="form.pekerjaan" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer appearance-none" required>
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
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Pekerjaan Utama <span class="text-red-500">*</span></label>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ALAMAT & IDENTITAS WILAYAH -->
                    <div class="pt-6">
                        <div class="mb-4">
                            <h3 class="font-bold text-gray-700 uppercase">ALAMAT & IDENTITAS WILAYAH</h3>
                        </div>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            <div class="relative">
                                <input v-model="form.dasa_wisma" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " required />
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Dasa Wisma <span class="text-red-500">*</span></label>
                            </div>
                            <div class="relative">
                                <input v-model="form.nama_kepala_rumah_tangga" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " required />
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Kepala Rumah Tangga <span class="text-red-500">*</span></label>
                            </div>
                            <div class="relative md:col-span-2">
                                <input v-model="form.alamat_jalan" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " />
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Alamat / Jalan</label>
                            </div>
                            <div class="relative">
                                <input v-model="form.rt" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " required />
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">RT <span class="text-red-500">*</span></label>
                            </div>
                            <div class="relative">
                                <input v-model="form.rw" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " required />
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">RW <span class="text-red-500">*</span></label>
                            </div>
                            <div class="relative">
                                <input v-model="form.desa_kelurahan" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " required />
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Desa/Kelurahan <span class="text-red-500">*</span></label>
                            </div>
                            <div class="relative">
                                <input v-model="form.kecamatan" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " required />
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Kecamatan <span class="text-red-500">*</span></label>
                            </div>
                            <div class="relative">
                                <input v-model="form.kabupaten_kota" type="text" class="block rounded-xl px-3 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 peer" placeholder=" " required />
                                <label class="absolute text-sm text-gray-500 duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] start-3 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-medium">Kab/Kota <span class="text-red-500">*</span></label>
                            </div>
                        </div>
                    </div>

                    <!-- DATA KHUSUS WARGA PKK -->
                    <div class="pt-6">
                        <div class="mb-4">
                            <span class="inline-flex rounded-lg bg-emerald-50 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-emerald-700">
                                DATA KHUSUS WARGA PKK
                            </span>
                        </div>
                        <fieldset :disabled="isAdmin" :class="{'contents': !isAdmin, 'opacity-70 cursor-not-allowed': isAdmin}">
                            <div v-if="isAdmin" class="mb-5 rounded-xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
                                <div class="flex">
                                    <div class="shrink-0">
                                        <i class="fa-solid fa-lock text-amber-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-amber-800">
                                            <strong class="font-bold">Area Terkunci:</strong> Data Khusus Warga PKK di bawah ini hanya dapat diisi dan diubah oleh Kader yang turun langsung ke lapangan.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 transition-colors hover:border-emerald-200">
                                    <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-700">Akseptor KB?</label>
                                    <select v-model="form.akseptor_kb" class="w-full rounded-xl border-gray-200 bg-white p-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                    <div v-if="form.akseptor_kb === '1'" class="mt-3 border-t border-gray-200 pt-3">
                                        <label class="mb-1 block text-xs font-semibold text-gray-600">Jenis Akseptor KB</label>
                                        <input v-model="form.jenis_akseptor_kb" type="text" class="w-full rounded-xl border border-gray-200 bg-white p-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" />
                                    </div>
                                </div>

                                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 transition-colors hover:border-emerald-200">
                                    <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-700">Aktif Posyandu?</label>
                                    <select v-model="form.aktif_posyandu" class="w-full rounded-xl border-gray-200 bg-white p-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                    <div v-if="form.aktif_posyandu === '1'" class="mt-3 border-t border-gray-200 pt-3">
                                        <label class="mb-1 block text-xs font-semibold text-gray-600">Frekuensi Posyandu</label>
                                        <input v-model="form.frekuensi_posyandu" type="number" class="w-full rounded-xl border border-gray-200 bg-white p-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" />
                                    </div>
                                </div>

                                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 transition-colors hover:border-emerald-200">
                                    <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-700">Ikut Kelompok Belajar?</label>
                                    <select v-model="form.ikut_kelompok_belajar" class="w-full rounded-xl border-gray-200 bg-white p-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                    <div v-if="form.ikut_kelompok_belajar === '1'" class="mt-3 border-t border-gray-200 pt-3">
                                        <label class="mb-1 block text-xs font-semibold text-gray-600">Jenis Paket Belajar</label>
                                        <select v-model="form.jenis_paket_belajar" class="w-full rounded-xl border border-gray-200 bg-white p-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                                            <option value="">-Pilih-</option>
                                            <option value="Paket A">Paket A</option>
                                            <option value="Paket B">Paket B</option>
                                            <option value="Paket C">Paket C</option>
                                            <option value="KF">KF</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 transition-colors hover:border-emerald-200">
                                    <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-700">Ikut Koperasi?</label>
                                    <select v-model="form.ikut_koperasi" class="w-full rounded-xl border-gray-200 bg-white p-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                    <div v-if="form.ikut_koperasi === '1'" class="mt-3 border-t border-gray-200 pt-3">
                                        <label class="mb-1 block text-xs font-semibold text-gray-600">Jenis Koperasi</label>
                                        <input v-model="form.jenis_koperasi" type="text" class="w-full rounded-xl border border-gray-200 bg-white p-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" />
                                    </div>
                                </div>

                                <!-- Booleans -->
                                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 transition-colors hover:border-emerald-200">
                                    <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-700">Bina Keluarga Balita?</label>
                                    <select v-model="form.ikut_bina_keluarga_balita" class="w-full rounded-xl border-gray-200 bg-white p-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                </div>
                                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 transition-colors hover:border-emerald-200">
                                    <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-700">Memiliki Tabungan?</label>
                                    <select v-model="form.memiliki_tabungan" class="w-full rounded-xl border-gray-200 bg-white p-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                </div>
                                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 transition-colors hover:border-emerald-200 md:col-span-2">
                                    <label class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-700">Ikut PAUD Sejenis?</label>
                                    <select v-model="form.ikut_paud_sejenis" class="w-full rounded-xl border-gray-200 bg-white p-2.5 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    
                    <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-6">
                        <a :href="`/kader/keluarga/${anggotaKeluarga.keluarga_id}`" class="rounded-xl border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit" :disabled="form.processing"
                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700 disabled:opacity-60">
                            <i class="fa-solid fa-save"></i>
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { computed, watch } from 'vue';

const props = defineProps<{
    anggotaKeluarga: any;
    keluargaAktifCount: number;
}>();

const page = usePage();
const isAdmin = computed(() => page.props.auth.user.role === 'admin');

const totalAnggotaAktif = computed(() => {
    // If the user being edited is active now but they are modifying it, we just show the static passed count 
    // Or we dynamically compute based on what they type. But for simplicity, we'll just show the static count.
    return props.keluargaAktifCount;
});

const form = useForm({
    nik: props.anggotaKeluarga.nik || '',
    nama_anggota: props.anggotaKeluarga.nama_anggota || '',
    jenis_kelamin: props.anggotaKeluarga.jenis_kelamin || 'L',
    tempat_lahir: props.anggotaKeluarga.tempat_lahir || '',
    tanggal_lahir: props.anggotaKeluarga.tanggal_lahir ? props.anggotaKeluarga.tanggal_lahir.split('T')[0] : '',
    umur: props.anggotaKeluarga.umur || '',
    jabatan: props.anggotaKeluarga.jabatan || '',
    pendidikan: props.anggotaKeluarga.pendidikan_terakhir || props.anggotaKeluarga.pendidikan || '',
    pekerjaan: props.anggotaKeluarga.pekerjaan_utama || props.anggotaKeluarga.pekerjaan || '',
    status_perkawinan: props.anggotaKeluarga.status_perkawinan || 'Belum Kawin',
    status_dalam_keluarga: props.anggotaKeluarga.status_dalam_keluarga || 'Kepala Rumah Tangga',
    agama: props.anggotaKeluarga.agama || 'Islam',
    
    no_reg: props.anggotaKeluarga.no_reg || '',
    dasa_wisma: props.anggotaKeluarga.dasa_wisma || '',
    nama_kepala_rumah_tangga: props.anggotaKeluarga.nama_kepala_rumah_tangga || '',
    alamat_jalan: props.anggotaKeluarga.alamat_jalan || '',
    rt: props.anggotaKeluarga.rt || '',
    rw: props.anggotaKeluarga.rw || '',
    desa_kelurahan: props.anggotaKeluarga.desa_kelurahan || 'Dompas',
    kecamatan: props.anggotaKeluarga.kecamatan || 'Bukit Batu',
    kabupaten_kota: props.anggotaKeluarga.kabupaten_kota || 'Bengkalis',
    provinsi: props.anggotaKeluarga.provinsi || 'Riau',
    
    akseptor_kb: props.anggotaKeluarga.akseptor_kb ? '1' : '0',
    jenis_akseptor_kb: props.anggotaKeluarga.jenis_akseptor_kb || '',
    aktif_posyandu: props.anggotaKeluarga.aktif_posyandu ? '1' : '0',
    frekuensi_posyandu: props.anggotaKeluarga.frekuensi_posyandu || '',
    ikut_kelompok_belajar: props.anggotaKeluarga.ikut_kelompok_belajar ? '1' : '0',
    jenis_paket_belajar: props.anggotaKeluarga.jenis_paket_belajar || '',
    ikut_koperasi: props.anggotaKeluarga.ikut_koperasi ? '1' : '0',
    jenis_koperasi: props.anggotaKeluarga.jenis_koperasi || '',
    
    ikut_bina_keluarga_balita: props.anggotaKeluarga.ikut_bina_keluarga_balita ? '1' : '0',
    memiliki_tabungan: props.anggotaKeluarga.memiliki_tabungan ? '1' : '0',
    ikut_paud_sejenis: props.anggotaKeluarga.ikut_paud_sejenis ? '1' : '0',
});

// Jabatan logic
watch(() => form.jenis_kelamin, (newVal) => {
    if (newVal === 'L') {
        form.jabatan = '';
    }
});

function submit() {
    form.put(`/kader/anggota/${props.anggotaKeluarga.id}`);
}
</script>
"""

with open('resources/js/pages/Kader/Anggota/Edit.vue', 'w') as f:
    f.write(content)

