<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KeluargaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (auth()->user()->isKader() || auth()->user()->isAdmin());
    }

    public function rules(): array
    {
        $keluargaId = $this->route('keluarga')?->id;

        return [
            'dasawisma_id'          => ['required', 'exists:dasawismas,id'],
            'no_kk'                 => [
                'required', 'string', 'size:16',
                $keluargaId
                    ? Rule::unique('keluargas')->ignore($keluargaId)
                    : 'unique:keluargas,no_kk',
            ],
            'nama_kepala_keluarga'  => ['required', 'string', 'max:255'],
            'rt'                    => ['nullable', 'string', 'max:10'],
            'rw'                    => ['nullable', 'string', 'max:10'],
            'dusun_lingkungan'      => ['nullable', 'string', 'max:255'],
            'desa'                  => ['nullable', 'string', 'max:255'],
            'kecamatan'             => ['nullable', 'string', 'max:255'],
            'kabupaten'             => ['nullable', 'string', 'max:255'],
            'provinsi'              => ['nullable', 'string', 'max:255'],
            'jumlah_kk'             => ['nullable', 'integer', 'min:0'],
            'jumlah_laki_laki'      => ['nullable', 'integer', 'min:0'],
            'jumlah_perempuan'      => ['nullable', 'integer', 'min:0'],
            'jumlah_balita_laki'    => ['nullable', 'integer', 'min:0'],
            'jumlah_balita_perempuan'=> ['nullable', 'integer', 'min:0'],
            'jumlah_pus'            => ['nullable', 'integer', 'min:0'],
            'jumlah_wus'            => ['nullable', 'integer', 'min:0'],
            'jumlah_buta'           => ['nullable', 'integer', 'min:0'],
            'jumlah_ibu_hamil'      => ['nullable', 'integer', 'min:0'],
            'jumlah_ibu_menyusui'   => ['nullable', 'integer', 'min:0'],
            'jumlah_lansia'         => ['nullable', 'integer', 'min:0'],
            'jumlah_berkebutuhan_khusus' => ['nullable', 'integer', 'min:0'],
            'sehat_layak_huni'      => ['nullable', 'boolean'],
            'memiliki_tempat_sampah'=> ['nullable', 'boolean'],
            'memiliki_spal'         => ['nullable', 'boolean'],
            'memiliki_jamban'       => ['nullable', 'boolean'],
            'menempel_stiker_p4k'   => ['nullable', 'boolean'],
            'sumber_air'            => ['nullable', 'string', 'max:255'],
            'makanan_pokok'         => ['nullable', 'string', 'max:255'],
            'ikut_up2k'             => ['nullable', 'boolean'],
            'ikut_pekarangan'       => ['nullable', 'boolean'],
            'ikut_industri'         => ['nullable', 'boolean'],
            'ikut_kerja_bakti'      => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'dasawisma_id.required'         => 'Dasawisma harus dipilih.',
            'dasawisma_id.exists'           => 'Dasawisma tidak ditemukan.',
            'no_kk.required'                => 'Nomor KK wajib diisi.',
            'no_kk.size'                    => 'Nomor KK harus 16 digit.',
            'no_kk.unique'                  => 'Nomor KK sudah terdaftar dalam sistem.',
            'nama_kepala_keluarga.required' => 'Nama kepala keluarga wajib diisi.',
            'nama_kepala_keluarga.max'      => 'Nama kepala keluarga maksimal 255 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'dasawisma_id'         => 'Dasawisma',
            'no_kk'                => 'Nomor KK',
            'nama_kepala_keluarga' => 'Nama Kepala Keluarga',
        ];
    }
}
