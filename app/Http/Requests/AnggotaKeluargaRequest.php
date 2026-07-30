<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnggotaKeluargaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isKader() || auth()->user()->isAdmin());
    }

    public function rules(): array
    {
        $anggotaId = $this->route('anggotaKeluarga')?->id;

        return [
            'no_reg'                => ['required', 'string', 'max:50'],
            'nik'                   => [
                'required', 'string', 'size:16',
                'regex:/^[0-9]{6}(0[1-9]|[12][0-9]|[3-7][0-9])(0[1-9]|1[0-2])\d{6}$/',
                $anggotaId
                    ? Rule::unique('anggota_keluargas')->ignore($anggotaId)
                    : 'unique:anggota_keluargas,nik',
            ],
            'nama_anggota'          => ['required', 'string', 'max:255'],
            'jenis_kelamin'         => ['required', Rule::in(['L', 'P'])],
            'tanggal_lahir'         => ['required', 'date', 'before:today'],
            'agama'                 => ['required', 'string', 'max:50'],
            'pendidikan'            => ['required', 'string', 'max:100'],
            'pekerjaan'             => ['required', 'string', 'max:100'],
            'status_dalam_keluarga' => ['required', 'string', 'max:50'],
            'status_perkawinan'     => ['required', 'string', 'max:50'],
            'dasa_wisma'            => ['required', 'string', 'max:255'],
            'nama_kepala_rumah_tangga' => ['required', 'string', 'max:255'],
            'jabatan'               => ['nullable', 'string', 'max:255'],
            'tempat_lahir'          => ['required', 'string', 'max:255'],
            'umur'                  => ['required', 'integer', 'min:0'],
            'alamat_jalan'          => ['required', 'string', 'max:255'],
            'rt'                    => ['required', 'string', 'max:10'],
            'rw'                    => ['required', 'string', 'max:10'],
            'desa_kelurahan'        => ['required', 'string', 'max:255'],
            'kecamatan'             => ['required', 'string', 'max:255'],
            'kabupaten_kota'        => ['required', 'string', 'max:255'],
            'provinsi'              => ['required', 'string', 'max:255'],
            'pendidikan_terakhir'   => ['required', 'string', 'max:255'],
            'pekerjaan_utama'       => ['required', 'string', 'max:255'],
            'akseptor_kb'           => ['required', 'boolean'],
            'jenis_akseptor_kb'     => ['nullable', 'required_if:akseptor_kb,true,1', 'string', 'max:255'],
            'aktif_posyandu'        => ['required', 'boolean'],
            'frekuensi_posyandu'    => ['nullable', 'required_if:aktif_posyandu,true,1', 'string', 'max:255'],
            'ikut_bina_keluarga_balita' => ['required', 'boolean'],
            'memiliki_tabungan'     => ['required', 'boolean'],
            'keterangan_tabungan'   => ['nullable', 'required_if:memiliki_tabungan,true,1', 'string', 'max:255'],
            'ikut_kelompok_belajar' => ['required', 'boolean'],
            'jenis_paket_belajar'   => ['nullable', 'required_if:ikut_kelompok_belajar,true,1', 'string', 'max:255'],
            'ikut_paud_sejenis'     => ['required', 'boolean'],
            'ikut_koperasi'         => ['required', 'boolean'],
            'jenis_koperasi'        => ['nullable', 'required_if:ikut_koperasi,true,1', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required'          => 'NIK wajib diisi.',
            'nik.size'              => 'NIK harus tepat 16 digit.',
            'nik.regex'             => 'Format NIK tidak valid. Harus 16 digit angka sesuai format kependudukan.',
            'nik.unique'            => 'NIK sudah terdaftar dalam keluarga lain.',
            'nama_anggota.required' => 'Nama anggota wajib diisi.',
            'jenis_kelamin.required'=> 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in'      => 'Jenis kelamin tidak valid.',
            'tanggal_lahir.required'=> 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date'    => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.before'  => 'Tanggal lahir harus di masa lalu.',
            'keterangan_tabungan.required_if' => 'Keterangan tabungan wajib diisi jika memiliki tabungan.',
            'no_reg.required'       => 'No. Registrasi wajib diisi.',
            'agama.required'        => 'Agama wajib dipilih.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'umur.required'         => 'Umur wajib diisi.',
            'alamat_jalan.required' => 'Alamat/Jalan wajib diisi.',
            'status_dalam_keluarga.required' => 'Status dalam keluarga wajib dipilih.',
            'status_perkawinan.required'     => 'Status perkawinan wajib dipilih.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nik'                   => 'NIK',
            'nama_anggota'          => 'Nama Anggota',
            'jenis_kelamin'         => 'Jenis Kelamin',
            'tanggal_lahir'         => 'Tanggal Lahir',
            'agama'                 => 'Agama',
            'pendidikan'            => 'Pendidikan',
            'pekerjaan'             => 'Pekerjaan',
            'status_dalam_keluarga' => 'Status dalam Keluarga',
            'status_perkawinan'     => 'Status Perkawinan',
            'keterangan_tabungan'   => 'Keterangan Tabungan',
        ];
    }
}
