<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Anggota Keluarga</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        
        .input-floating {
            position: relative;
        }
        .input-floating input, .input-floating select {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 1.25rem 0.75rem 0.5rem 0.75rem; /* adjusted padding for label space */
            width: 100%;
            background: #f8fafc;
            transition: all 0.2s;
            font-size: 0.875rem;
            color: #334155;
        }
        .input-floating input:focus, .input-floating select:focus {
            outline: none;
            border-color: #10b981; /* emerald-500 */
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }
        .input-floating label {
            position: absolute;
            top: 0.4rem;
            left: 0.75rem;
            font-size: 0.7rem;
            color: #64748b;
            transition: all 0.2s;
            pointer-events: none;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="text-slate-800 antialiased min-h-screen flex flex-col items-center py-10 px-4">

    <div class="w-full max-w-5xl">
        <!-- HEADER -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="bg-emerald-100 text-emerald-600 w-12 h-12 rounded-2xl flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-user-pen text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Edit Anggota Keluarga</h2>
                    <p class="text-sm text-slate-500 mt-1">Aplikasi Pendataan Warga TP-PKK Berbasis Website Pada Dasawisma Desa Dompas</p>
                </div>
            </div>
            <a href="{{ route('kader.keluarga.show', $anggotaKeluarga->keluarga_id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-emerald-600 transition-colors shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Batal & Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-semibold text-red-800">Terdapat error pada pengisian formulir:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('kader.anggota.update', $anggotaKeluarga->id) }}" method="POST" class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            @csrf
            @method('PUT')

            <div class="p-6 md:p-8 space-y-8">
                
                <!-- Data Diri Pokok -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <div class="md:col-span-4 mb-2 border-b border-slate-100 pb-4">
                        <span class="text-sm font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-id-card text-emerald-500"></i> Data Diri Pokok
                        </span>
                    </div>

                    <div class="input-floating">
                        <label>No. Registrasi</label>
                        <input type="text" name="no_reg" value="{{ old('no_reg', $anggotaKeluarga->no_reg) }}">
                    </div>
                    <div class="input-floating">
                        <label>NIK (No KTP) <span class="text-red-500">*</span></label>
                        <input type="text" name="nik" value="{{ old('nik', $anggotaKeluarga->nik) }}" required maxlength="16" class="font-mono">
                    </div>
                    <div class="input-floating md:col-span-2">
                        <label>Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_anggota" value="{{ old('nama_anggota', $anggotaKeluarga->nama_anggota) }}" required>
                    </div>

                    <div class="input-floating">
                        <label>Status Dlm Keluarga <span class="text-red-500">*</span></label>
                        <select name="status_dalam_keluarga" required>
                            <option value="">- Pilih -</option>
                            @foreach(['Kepala Keluarga', 'Istri', 'Anak', 'Menantu', 'Cucu', 'Orang Tua', 'Mertua', 'Anggota Keluarga', 'Lainnya'] as $s)
                                <option value="{{ $s }}" {{ old('status_dalam_keluarga', $anggotaKeluarga->status_dalam_keluarga) == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-floating">
                        <label>Status Perkawinan</label>
                        <select name="status_perkawinan">
                            <option value="">- Pilih -</option>
                            @foreach(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'] as $s)
                                <option value="{{ $s }}" {{ old('status_perkawinan', $anggotaKeluarga->status_perkawinan) == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-floating">
                        <label>Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" required>
                            <option value="L" {{ old('jenis_kelamin', $anggotaKeluarga->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ old('jenis_kelamin', $anggotaKeluarga->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="input-floating">
                        <label>Agama</label>
                        <select name="agama">
                            <option value="">- Pilih -</option>
                            @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agm)
                                <option value="{{ $agm }}" {{ old('agama', $anggotaKeluarga->agama) == $agm ? 'selected' : '' }}>{{ $agm }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="input-floating">
                        <label>Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $anggotaKeluarga->tempat_lahir) }}">
                    </div>
                    <div class="input-floating">
                        <label>Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $anggotaKeluarga->tanggal_lahir?->format('Y-m-d')) }}" required>
                    </div>
                    <div class="input-floating">
                        <label>Umur</label>
                        <input type="number" name="umur" value="{{ old('umur', $anggotaKeluarga->umur) }}">
                    </div>
                    <div class="input-floating">
                        <label>Jabatan PKK</label>
                        <input type="text" name="jabatan" value="{{ old('jabatan', $anggotaKeluarga->jabatan) }}">
                    </div>

                    <!-- Pendidikan & Pekerjaan -->
                    <div class="input-floating md:col-span-2">
                        <label>Pendidikan Terakhir</label>
                        <select name="pendidikan_terakhir">
                            <option value="">- Pilih -</option>
                            @foreach(['Tidak Sekolah', 'SD', 'SMP', 'SMA/SMK', 'D1/D2/D3', 'S1', 'S2', 'S3'] as $pend)
                                <option value="{{ $pend }}" {{ old('pendidikan_terakhir', $anggotaKeluarga->pendidikan_terakhir) == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="input-floating md:col-span-2">
                        <label>Pekerjaan Utama</label>
                        <input type="text" name="pekerjaan_utama" value="{{ old('pekerjaan_utama', $anggotaKeluarga->pekerjaan_utama) }}">
                    </div>

                    <!-- Data Wilayah Individu -->
                    <div class="md:col-span-4 mt-8 border-b border-slate-100 pb-4">
                        <span class="text-sm font-bold text-slate-700 uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-map-pin text-emerald-500"></i> Alamat & Identitas Wilayah
                        </span>
                    </div>

                    <div class="input-floating">
                        <label>Dasa Wisma</label>
                        <input type="text" name="dasa_wisma" value="{{ old('dasa_wisma', $anggotaKeluarga->dasa_wisma) }}">
                    </div>
                    <div class="input-floating">
                        <label>Kepala Rumah Tangga</label>
                        <input type="text" name="nama_kepala_rumah_tangga" value="{{ old('nama_kepala_rumah_tangga', $anggotaKeluarga->nama_kepala_rumah_tangga) }}">
                    </div>
                    <div class="input-floating md:col-span-2">
                        <label>Alamat / Jalan</label>
                        <input type="text" name="alamat_jalan" value="{{ old('alamat_jalan', $anggotaKeluarga->alamat_jalan) }}">
                    </div>
                    <div class="flex gap-2">
                        <div class="input-floating w-1/2">
                            <label>RT</label>
                            <input type="text" name="rt" value="{{ old('rt', $anggotaKeluarga->rt) }}">
                        </div>
                        <div class="input-floating w-1/2">
                            <label>RW</label>
                            <input type="text" name="rw" value="{{ old('rw', $anggotaKeluarga->rw) }}">
                        </div>
                    </div>
                    <div class="input-floating">
                        <label>Desa/Kelurahan</label>
                        <input type="text" name="desa_kelurahan" value="{{ old('desa_kelurahan', $anggotaKeluarga->desa_kelurahan) }}">
                    </div>
                    <div class="input-floating">
                        <label>Kecamatan</label>
                        <input type="text" name="kecamatan" value="{{ old('kecamatan', $anggotaKeluarga->kecamatan) }}">
                    </div>
                    <div class="input-floating">
                        <label>Kab/Kota</label>
                        <input type="text" name="kabupaten_kota" value="{{ old('kabupaten_kota', $anggotaKeluarga->kabupaten_kota) }}">
                    </div>
                    
                    <!-- Data PKK Khusus -->
                    <div class="md:col-span-4 mt-8 border-b border-emerald-100 pb-4">
                        <span class="text-sm font-bold text-emerald-700 uppercase tracking-wider bg-emerald-50 px-3 py-1.5 rounded-lg flex items-center gap-2 inline-flex">
                            <i class="fa-solid fa-leaf"></i> Data Khusus Warga PKK
                        </span>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 hover:border-emerald-200 transition-colors">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Akseptor KB?</label>
                        <select onchange="toggleField(this, 'kb_wrapper')" name="akseptor_kb" class="w-full rounded-xl border-slate-200 p-2.5 text-sm bg-white focus:ring-emerald-500 focus:border-emerald-500 shadow-sm outline-none">
                            <option value="0" {{ !old('akseptor_kb', $anggotaKeluarga->akseptor_kb) ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ old('akseptor_kb', $anggotaKeluarga->akseptor_kb) ? 'selected' : '' }}>Ya</option>
                        </select>
                        <div id="kb_wrapper" class="{{ old('akseptor_kb', $anggotaKeluarga->akseptor_kb) ? '' : 'hidden' }} mt-3 pt-3 border-t border-slate-200">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Akseptor KB</label>
                            <input type="text" name="jenis_akseptor_kb" value="{{ old('jenis_akseptor_kb', $anggotaKeluarga->jenis_akseptor_kb) }}" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none">
                        </div>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 hover:border-emerald-200 transition-colors">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Aktif Posyandu?</label>
                        <select onchange="toggleField(this, 'posyandu_wrapper')" name="aktif_posyandu" class="w-full rounded-xl border-slate-200 p-2.5 text-sm bg-white focus:ring-emerald-500 focus:border-emerald-500 shadow-sm outline-none">
                            <option value="0" {{ !old('aktif_posyandu', $anggotaKeluarga->aktif_posyandu) ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ old('aktif_posyandu', $anggotaKeluarga->aktif_posyandu) ? 'selected' : '' }}>Ya</option>
                        </select>
                        <div id="posyandu_wrapper" class="{{ old('aktif_posyandu', $anggotaKeluarga->aktif_posyandu) ? '' : 'hidden' }} mt-3 pt-3 border-t border-slate-200">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Frekuensi Posyandu</label>
                            <input type="text" name="frekuensi_posyandu" value="{{ old('frekuensi_posyandu', $anggotaKeluarga->frekuensi_posyandu) }}" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none">
                        </div>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 hover:border-emerald-200 transition-colors">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Ikut Kelompok Belajar?</label>
                        <select onchange="toggleField(this, 'belajar_wrapper')" name="ikut_kelompok_belajar" class="w-full rounded-xl border-slate-200 p-2.5 text-sm bg-white focus:ring-emerald-500 focus:border-emerald-500 shadow-sm outline-none">
                            <option value="0" {{ !old('ikut_kelompok_belajar', $anggotaKeluarga->ikut_kelompok_belajar) ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ old('ikut_kelompok_belajar', $anggotaKeluarga->ikut_kelompok_belajar) ? 'selected' : '' }}>Ya</option>
                        </select>
                        <div id="belajar_wrapper" class="{{ old('ikut_kelompok_belajar', $anggotaKeluarga->ikut_kelompok_belajar) ? '' : 'hidden' }} mt-3 pt-3 border-t border-slate-200">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Paket Belajar</label>
                            <select name="jenis_paket_belajar" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none">
                                <option value="">-Pilih-</option>
                                <option value="Paket A" {{ old('jenis_paket_belajar', $anggotaKeluarga->jenis_paket_belajar) == 'Paket A' ? 'selected' : '' }}>Paket A</option>
                                <option value="Paket B" {{ old('jenis_paket_belajar', $anggotaKeluarga->jenis_paket_belajar) == 'Paket B' ? 'selected' : '' }}>Paket B</option>
                                <option value="Paket C" {{ old('jenis_paket_belajar', $anggotaKeluarga->jenis_paket_belajar) == 'Paket C' ? 'selected' : '' }}>Paket C</option>
                                <option value="KF" {{ old('jenis_paket_belajar', $anggotaKeluarga->jenis_paket_belajar) == 'KF' ? 'selected' : '' }}>KF</option>
                            </select>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 hover:border-emerald-200 transition-colors">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Ikut Koperasi?</label>
                        <select onchange="toggleField(this, 'koperasi_wrapper')" name="ikut_koperasi" class="w-full rounded-xl border-slate-200 p-2.5 text-sm bg-white focus:ring-emerald-500 focus:border-emerald-500 shadow-sm outline-none">
                            <option value="0" {{ !old('ikut_koperasi', $anggotaKeluarga->ikut_koperasi) ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ old('ikut_koperasi', $anggotaKeluarga->ikut_koperasi) ? 'selected' : '' }}>Ya</option>
                        </select>
                        <div id="koperasi_wrapper" class="{{ old('ikut_koperasi', $anggotaKeluarga->ikut_koperasi) ? '' : 'hidden' }} mt-3 pt-3 border-t border-slate-200">
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Koperasi</label>
                            <input type="text" name="jenis_koperasi" value="{{ old('jenis_koperasi', $anggotaKeluarga->jenis_koperasi) }}" class="w-full rounded-xl border border-slate-200 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 outline-none">
                        </div>
                    </div>

                    <!-- Boolean fields only -->
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 hover:border-emerald-200 transition-colors">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Bina Keluarga Balita?</label>
                        <select name="ikut_bina_keluarga_balita" class="w-full rounded-xl border-slate-200 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 outline-none">
                            <option value="0" {{ !old('ikut_bina_keluarga_balita', $anggotaKeluarga->ikut_bina_keluarga_balita) ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ old('ikut_bina_keluarga_balita', $anggotaKeluarga->ikut_bina_keluarga_balita) ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 hover:border-emerald-200 transition-colors">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Memiliki Tabungan?</label>
                        <select name="memiliki_tabungan" class="w-full rounded-xl border-slate-200 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 outline-none">
                            <option value="0" {{ !old('memiliki_tabungan', $anggotaKeluarga->memiliki_tabungan) ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ old('memiliki_tabungan', $anggotaKeluarga->memiliki_tabungan) ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 hover:border-emerald-200 transition-colors md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">Ikut PAUD Sejenis?</label>
                        <select name="ikut_paud_sejenis" class="w-full rounded-xl border-slate-200 p-2.5 text-sm bg-white shadow-sm focus:border-emerald-500 outline-none">
                            <option value="0" {{ !old('ikut_paud_sejenis', $anggotaKeluarga->ikut_paud_sejenis) ? 'selected' : '' }}>Tidak</option>
                            <option value="1" {{ old('ikut_paud_sejenis', $anggotaKeluarga->ikut_paud_sejenis) ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- FORM FOOTER -->
            <div class="bg-slate-50 border-t border-slate-100 p-6 flex items-center justify-end">
                <button type="submit" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold hover:from-emerald-600 hover:to-teal-600 transition-all shadow-md transform hover:scale-[1.02]">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

<script>
    function toggleField(selectElem, targetId) {
        const target = document.getElementById(targetId);
        if (selectElem.value == '1') {
            target.classList.remove('hidden');
        } else {
            target.classList.add('hidden');
            const inputs = target.querySelectorAll('input, select');
            inputs.forEach(i => i.value = '');
        }
    }
</script>
</body>
</html>
