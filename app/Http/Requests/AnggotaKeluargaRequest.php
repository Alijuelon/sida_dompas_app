<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnggotaKeluargaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isKader();
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
