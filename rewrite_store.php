<?php
$content = file_get_contents('app/Http/Controllers/Kader/KeluargaController.php');

$old_validation = <<<'V'
        $validated = $request->validate([
            'dasawisma_id'          => ['required', 'exists:dasawismas,id'],
            'no_kk'                 => ['required', 'string', 'size:16', 'regex:/^\d{16}$/', 'unique:keluargas,no_kk'],
            'nama_kepala_keluarga'  => ['required', 'string', 'max:255'],
            'rt'                    => ['required', 'string', 'max:10'],
            'rw'                    => ['required', 'string', 'max:10'],
            'dusun_lingkungan'      => ['required', 'string', 'max:255'],
            
            // Kolom boolean Kriteria Rumah (form step 2)
            'sehat_layak_huni'      => ['required', 'boolean'],
            'memiliki_tempat_sampah'=> ['required', 'boolean'],
            'memiliki_spal'         => ['required', 'boolean'],
            'memiliki_jamban'       => ['required', 'boolean'],
            'menempel_stiker_p4k'   => ['required', 'boolean'],
            'jenis_stiker'          => ['nullable', 'required_if:menempel_stiker_p4k,true,1', 'string', 'max:255'],
            'sumber_air'            => ['required', 'array', 'min:1'],
            'sumber_air.*'          => ['required', 'string', 'in:PDAM,Sumur,Sungai,Mata Air,Air Hujan,Lainnya'],
            'sumber_air_lainnya'    => ['nullable', 'string', 'max:255'],
            'makanan_pokok'         => ['required', 'string', 'max:255'],
            'ikut_up2k'             => ['required', 'boolean'],
            'ikut_pekarangan'       => ['required', 'boolean'],
            'ikut_industri'         => ['required', 'boolean'],
            'ikut_kerja_bakti'      => ['required', 'boolean'],
            'anggota'               => ['required', 'array', 'min:1'],
V;

$new_validation = <<<'V'
        $isKader = \Illuminate\Support\Facades\Auth::user()->isKader();
        
        $rules = [
            'dasawisma_id'          => ['required', 'exists:dasawismas,id'],
            'no_kk'                 => ['required', 'string', 'size:16', 'regex:/^\d{16}$/', 'unique:keluargas,no_kk'],
            'nama_kepala_keluarga'  => ['required', 'string', 'max:255'],
            'rt'                    => ['required', 'string', 'max:10'],
            'rw'                    => ['required', 'string', 'max:10'],
            'dusun_lingkungan'      => ['required', 'string', 'max:255'],
            
            // Kolom boolean Kriteria Rumah (form step 2)
            'sehat_layak_huni'      => ['nullable', 'boolean'],
            'memiliki_tempat_sampah'=> ['nullable', 'boolean'],
            'memiliki_spal'         => ['nullable', 'boolean'],
            'memiliki_jamban'       => ['nullable', 'boolean'],
            'menempel_stiker_p4k'   => ['nullable', 'boolean'],
            'jenis_stiker'          => ['nullable', 'required_if:menempel_stiker_p4k,true,1', 'string', 'max:255'],
            'sumber_air'            => [$isKader ? 'required' : 'nullable', 'array', 'min:1'],
            'sumber_air.*'          => ['required', 'string', 'in:PDAM,Sumur,Sungai,Mata Air,Air Hujan,Lainnya'],
            'sumber_air_lainnya'    => ['nullable', 'string', 'max:255'],
            'makanan_pokok'         => [$isKader ? 'required' : 'nullable', 'string', 'max:255'],
            'ikut_up2k'             => ['nullable', 'boolean'],
            'ikut_pekarangan'       => ['nullable', 'boolean'],
            'ikut_industri'         => ['nullable', 'boolean'],
            'ikut_kerja_bakti'      => ['nullable', 'boolean'],
            'anggota'               => ['required', 'array', 'min:1'],
V;

if (strpos($content, trim($old_validation)) !== false) {
    $content = str_replace(trim($old_validation), trim($new_validation), $content);
}

$old_validate_call = <<<'V'
        $validated = $request->validate([
V;
$new_validate_call = <<<'V'
        $validated = $request->validate($rules, [
V;
// Replace only the first instance that follows our new logic. Actually, we should be careful.
// The easiest way is just let regex handle it.

$content2 = preg_replace('/\$validated = \$request->validate\(\[/', '$validated = $request->validate($rules, [', $content, 1);

file_put_contents('app/Http/Controllers/Kader/KeluargaController.php', $content2);
