<?php
$file = 'app/Http/Controllers/Kader/KeluargaController.php';
$content = file_get_contents($file);

// Find the start of the store method
$start_pos = strpos($content, 'public function store(Request $request): RedirectResponse');

// Find the start of the validate call inside store
$validate_start = strpos($content, '$validated = $request->validate', $start_pos);

// Find the end of the validate array (matching brackets)
$open_brackets = 0;
$in_array = false;
$validate_end = -1;

for ($i = $validate_start; $i < strlen($content); $i++) {
    if ($content[$i] == '[') {
        $open_brackets++;
        $in_array = true;
    } elseif ($content[$i] == ']') {
        $open_brackets--;
    }
    
    if ($in_array && $open_brackets == 0) {
        // we found the end of the main array
        // it should be followed by ]);
        $validate_end = strpos($content, ');', $i) + 2;
        break;
    }
}

if ($validate_end === -1) {
    die("Could not parse validate array\n");
}

$old_validation_block = substr($content, $validate_start, $validate_end - $validate_start);

$new_validation_block = <<<'V'
        $isKader = \Illuminate\Support\Facades\Auth::user()->isKader();
        
        $rules = [
            'dasawisma_id'          => ['required', 'exists:dasawismas,id'],
            'no_kk'                 => ['required', 'string', 'size:16', 'regex:/^\d{16}$/', 'unique:keluargas,no_kk'],
            'nama_kepala_keluarga'  => ['required', 'string', 'max:255'],
            'rt'                    => ['required', 'string', 'max:10'],
            'rw'                    => ['required', 'string', 'max:10'],
            'dusun_lingkungan'      => ['required', 'string', 'max:255'],
            'desa'                  => ['required', 'string', 'max:255'],
            'kecamatan'             => ['required', 'string', 'max:255'],
            'kabupaten'             => ['required', 'string', 'max:255'],
            'provinsi'              => ['required', 'string', 'max:255'],
            'jumlah_kk'             => ['required', 'integer', 'min:0'],
            'jumlah_laki_laki'      => ['required', 'integer', 'min:0'],
            'jumlah_perempuan'      => ['required', 'integer', 'min:0'],
            'jumlah_balita_laki'    => ['required', 'integer', 'min:0'],
            'jumlah_balita_perempuan'=> ['required', 'integer', 'min:0'],
            'jumlah_pus'            => ['required', 'integer', 'min:0'],
            'jumlah_wus'            => ['required', 'integer', 'min:0'],
            'jumlah_buta'           => ['required', 'integer', 'min:0'],
            'jumlah_ibu_hamil'      => ['required', 'integer', 'min:0'],
            'jumlah_ibu_menyusui'   => ['required', 'integer', 'min:0'],
            'jumlah_lansia'         => ['required', 'integer', 'min:0'],
            'jumlah_berkebutuhan_khusus' => ['required', 'integer', 'min:0'],
            'sehat_layak_huni'      => ['nullable', 'boolean'],
            'memiliki_tempat_sampah'=> ['nullable', 'boolean'],
            'memiliki_spal'         => ['nullable', 'boolean'],
            'memiliki_jamban'       => ['nullable', 'boolean'],
            'menempel_stiker_p4k'   => ['nullable', 'boolean'],
            'jenis_stiker'          => ['nullable', 'required_if:menempel_stiker_p4k,true,1', 'string', 'max:255'],
            'sumber_air'            => [$isKader ? 'required' : 'nullable', 'array', 'min:1'],
            'sumber_air.*'          => ['nullable', 'string', 'in:PDAM,Sumur,Sungai,Mata Air,Air Hujan,Lainnya'],
            'sumber_air_lainnya'    => ['nullable', 'string', 'max:255'],
            'makanan_pokok'         => [$isKader ? 'required' : 'nullable', 'string', 'max:255'],
            'ikut_up2k'             => ['nullable', 'boolean'],
            'ikut_pekarangan'       => ['nullable', 'boolean'],
            'ikut_industri'         => ['nullable', 'boolean'],
            'ikut_kerja_bakti'      => ['nullable', 'boolean'],
            'anggota'               => ['required', 'array', 'min:1'],
            'anggota.*.no_reg'      => ['required', 'string', 'max:50'],
            'anggota.*.nik'         => ['required', 'string', 'size:16', 'regex:/^\d{16}$/', 'distinct', 'unique:anggota_keluargas,nik'],
            'anggota.*.nama_anggota'=> ['required', 'string', 'max:255'],
            'anggota.*.jenis_kelamin'       => ['required', 'in:L,P'],
            'anggota.*.tanggal_lahir'       => ['required', 'date'],
            'anggota.*.agama'               => ['required', 'string', 'max:50'],
            'anggota.*.pendidikan'          => ['nullable', 'string', 'max:100'],
            'anggota.*.pekerjaan'           => ['nullable', 'string', 'max:100'],
            'anggota.*.status_dalam_keluarga' => ['required', 'string', 'max:50'],
            'anggota.*.status_perkawinan'   => ['required', 'string', 'max:50'],
            'anggota.*.dasa_wisma'          => ['nullable', 'string', 'max:255'],
            'anggota.*.nama_kepala_rumah_tangga' => ['nullable', 'string', 'max:255'],
            'anggota.*.jabatan'             => ['nullable', 'string', 'max:255'],
            'anggota.*.tempat_lahir'        => ['required', 'string', 'max:255'],
            'anggota.*.umur'                => ['required', 'integer', 'min:0'],
            'anggota.*.alamat_jalan'        => ['nullable', 'string', 'max:255'],
            'anggota.*.rt'                  => ['nullable', 'string', 'max:10'],
            'anggota.*.rw'                  => ['nullable', 'string', 'max:10'],
            'anggota.*.desa_kelurahan'      => ['nullable', 'string', 'max:255'],
            'anggota.*.kecamatan'           => ['nullable', 'string', 'max:255'],
            'anggota.*.kabupaten_kota'      => ['nullable', 'string', 'max:255'],
            'anggota.*.provinsi'            => ['nullable', 'string', 'max:255'],
            'anggota.*.akseptor_kb'         => ['nullable', 'boolean'],
            'anggota.*.jenis_akseptor_kb'   => ['nullable', 'string', 'max:100'],
            'anggota.*.aktif_posyandu'      => ['nullable', 'boolean'],
            'anggota.*.frekuensi_posyandu'  => ['nullable', 'string', 'max:100'],
            'anggota.*.ikut_bina_keluarga_balita' => ['nullable', 'boolean'],
            'anggota.*.memiliki_tabungan'   => ['nullable', 'boolean'],
            'anggota.*.keterangan_tabungan' => ['nullable', 'string', 'max:255'],
            'anggota.*.ikut_kelompok_belajar' => ['nullable', 'boolean'],
            'anggota.*.jenis_paket_belajar' => ['nullable', 'string', 'max:100'],
            'anggota.*.ikut_paud_sejenis'   => ['nullable', 'boolean'],
            'anggota.*.ikut_koperasi'       => ['nullable', 'boolean'],
            'anggota.*.jenis_koperasi'      => ['nullable', 'string', 'max:100'],
            'anggota.*.berkebutuhan_khusus' => ['nullable', 'boolean'],
        ];

        $validated = $request->validate($rules, [
            'no_kk.regex'       => 'Format No. KK tidak valid. Harus 16 digit angka.',
            'no_kk.unique'      => 'Nomor KK sudah terdaftar.',
            'anggota.*.nik.size'=> 'NIK harus 16 digit.',
            'anggota.*.nik.regex'    => 'Format NIK tidak valid. Harus 16 digit angka.',
            'anggota.*.nik.unique'   => 'NIK sudah terdaftar.',
            'anggota.*.nik.distinct' => 'NIK anggota tidak boleh sama.',
            'anggota.min'       => 'Minimal harus ada 1 anggota keluarga.',
            'sumber_air.required' => 'Sumber air wajib dipilih minimal satu.',
            'makanan_pokok.required' => 'Makanan pokok wajib diisi.',
            'jenis_stiker.required_if' => 'Jenis stiker wajib diisi jika Anda menempel stiker P4K.',
        ]);
V;

// replace in content
$content = substr_replace($content, $new_validation_block, $validate_start, $validate_end - $validate_start);

file_put_contents($file, $content);

// Also let's fix up the fact that if an admin creates it, sumber_air is empty, so when doing $sumberAir = implode(',', $validated['sumber_air']); it will fail if undefined
echo "Done";
