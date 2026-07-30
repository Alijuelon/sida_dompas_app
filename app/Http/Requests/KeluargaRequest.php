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
                'regex:/^[0-9]{6}(0[1-9]|[12][0-9]|3[01])(0[1-9]|1[0-2])\d{6}$/',
                $keluargaId
                    ? Rule::unique('keluargas')->ignore($keluargaId)
                    : 'unique:keluargas,no_kk',
            ],
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
            'sehat_layak_huni'      => ['required', 'boolean'],
            'memiliki_tempat_sampah'=> ['required', 'boolean'],
            'memiliki_spal'         => ['required', 'boolean'],
            'memiliki_jamban'       => ['required', 'boolean'],
            'menempel_stiker_p4k'   => ['required', 'boolean'],
            'jenis_stiker'          => ['nullable', 'required_if:menempel_stiker_p4k,true,1', 'string', 'max:255'],
            'sumber_air'            => ['required', 'array', 'min:1'],
            'sumber_air.*'          => ['required', 'string', Rule::in(['PDAM', 'Sumur', 'Sungai', 'Mata Air', 'Air Hujan', 'Lainnya'])],
            'sumber_air_lainnya'    => ['nullable', 'string', 'max:255'],
            'makanan_pokok'         => ['required', 'string', 'max:255'],
            'ikut_up2k'             => ['required', 'boolean'],
            'ikut_pekarangan'       => ['required', 'boolean'],
            'ikut_industri'         => ['required', 'boolean'],
            'ikut_kerja_bakti'      => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'dasawisma_id.required'         => 'Dasawisma harus dipilih.',
            'dasawisma_id.exists'           => 'Dasawisma tidak ditemukan.',
            'no_kk.required'                => 'Nomor KK wajib diisi.',
            'no_kk.size'                    => 'Nomor KK harus tepat 16 digit.',
            'no_kk.regex'                   => 'Format No. KK tidak valid. Harus 16 digit angka sesuai format kependudukan.',
            'no_kk.unique'                  => 'Nomor KK sudah terdaftar dalam sistem.',
            'nama_kepala_keluarga.required' => 'Nama kepala keluarga wajib diisi.',
            'nama_kepala_keluarga.max'      => 'Nama kepala keluarga maksimal 255 karakter.',
            'sumber_air.required'           => 'Sumber air wajib dipilih minimal satu.',
            'sumber_air.min'                => 'Sumber air wajib dipilih minimal satu.',
            'sumber_air.*.in'               => 'Pilihan sumber air tidak valid.',
            'sumber_air_lainnya.required_if'=> 'Keterangan sumber air lainnya wajib diisi jika memilih "Lainnya".',
            'jenis_stiker.required_if'      => 'Jenis stiker wajib diisi jika menempel stiker P4K/PMI/PMK.',
            'makanan_pokok.required'        => 'Makanan pokok wajib dipilih.',
            'rt.required'                   => 'RT wajib diisi.',
            'rw.required'                   => 'RW wajib diisi.',
            'dusun_lingkungan.required'     => 'Dusun/Lingkungan wajib dipilih.',
        ];
    }

    public function attributes(): array
    {
        return [
            'dasawisma_id'         => 'Dasawisma',
            'no_kk'                => 'Nomor KK',
            'nama_kepala_keluarga' => 'Nama Kepala Keluarga',
            'sumber_air'           => 'Sumber Air',
            'sumber_air_lainnya'   => 'Keterangan Sumber Air Lainnya',
            'jenis_stiker'         => 'Jenis Stiker',
            'makanan_pokok'        => 'Makanan Pokok',
        ];
    }
}
