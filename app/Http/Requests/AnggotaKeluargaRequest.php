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
            'nik'                   => [
                'required', 'string', 'size:16',
                $anggotaId
                    ? Rule::unique('anggota_keluargas')->ignore($anggotaId)
                    : 'unique:anggota_keluargas,nik',
            ],
            'nama_anggota'          => ['required', 'string', 'max:255'],
            'jenis_kelamin'         => ['required', Rule::in(['L', 'P'])],
            'tanggal_lahir'         => ['required', 'date', 'before:today'],
            'agama'                 => ['nullable', 'string', 'max:50'],
            'pendidikan'            => ['nullable', 'string', 'max:100'],
            'pekerjaan'             => ['nullable', 'string', 'max:100'],
            'status_dalam_keluarga' => ['nullable', 'string', 'max:50'],
            'status_perkawinan'     => ['nullable', 'string', 'max:50'],
            'dasa_wisma'            => ['nullable', 'string', 'max:255'],
            'nama_kepala_rumah_tangga' => ['nullable', 'string', 'max:255'],
            'jabatan'               => ['nullable', 'string', 'max:255'],
            'tempat_lahir'          => ['nullable', 'string', 'max:255'],
            'umur'                  => ['nullable', 'integer', 'min:0'],
            'alamat_jalan'          => ['nullable', 'string', 'max:255'],
            'rt'                    => ['nullable', 'string', 'max:10'],
            'rw'                    => ['nullable', 'string', 'max:10'],
            'desa_kelurahan'        => ['nullable', 'string', 'max:255'],
            'kecamatan'             => ['nullable', 'string', 'max:255'],
            'kabupaten_kota'        => ['nullable', 'string', 'max:255'],
            'provinsi'              => ['nullable', 'string', 'max:255'],
            'pendidikan_terakhir'   => ['nullable', 'string', 'max:255'],
            'pekerjaan_utama'       => ['nullable', 'string', 'max:255'],
            'akseptor_kb'           => ['nullable', 'boolean'],
            'jenis_akseptor_kb'     => ['nullable', 'string', 'max:255'],
            'aktif_posyandu'        => ['nullable', 'boolean'],
            'frekuensi_posyandu'    => ['nullable', 'string', 'max:255'],
            'ikut_bina_keluarga_balita' => ['nullable', 'boolean'],
            'memiliki_tabungan'     => ['nullable', 'boolean'],
            'ikut_kelompok_belajar' => ['nullable', 'boolean'],
            'jenis_paket_belajar'   => ['nullable', 'string', 'max:255'],
            'ikut_paud_sejenis'     => ['nullable', 'boolean'],
            'ikut_koperasi'         => ['nullable', 'boolean'],
            'jenis_koperasi'        => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nik.required'          => 'NIK wajib diisi.',
            'nik.size'              => 'NIK harus tepat 16 digit.',
            'nik.unique'            => 'NIK sudah terdaftar dalam keluarga lain.',
            'nama_anggota.required' => 'Nama anggota wajib diisi.',
            'jenis_kelamin.required'=> 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.in'      => 'Jenis kelamin tidak valid.',
            'tanggal_lahir.required'=> 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date'    => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.before'  => 'Tanggal lahir harus di masa lalu.',
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
        ];
    }
}
