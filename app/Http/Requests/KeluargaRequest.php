<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KeluargaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isKader();
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
