<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCitizenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Header
            'dasa_wisma' => ['nullable', 'string', 'max:255'],
            'family_id' => ['nullable', 'exists:families,id'],
            
            // Identity
            'nik' => ['required', 'string', 'size:16', 'unique:citizens,nik'],
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:Laki-laki,Perempuan'],
            'place_of_birth' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'marital_status' => ['required', 'in:Menikah,Lajang,Janda,Duda'],
            'family_status' => ['required', 'in:Kepala Rumah Tangga,Anggota Keluarga'],
            'religion' => ['required', 'string', 'max:255'],
            
            // Address
            'street_name' => ['required', 'string', 'max:255'],
            'rt' => ['required', 'string', 'max:5'],
            'rw' => ['required', 'string', 'max:5'],
            
            // Social Status
            'education' => ['required', 'string'],
            'occupation' => ['required', 'string'],
            
            // Programs
            'is_akseptor_kb' => ['required', 'boolean'],
            'kb_type' => ['required_if:is_akseptor_kb,true,1', 'nullable', 'string', 'max:255'],
            
            'is_active_posyandu' => ['required', 'boolean'],
            'posyandu_frequency' => ['required_if:is_active_posyandu,true,1', 'nullable', 'string', 'max:255'],
            
            'has_bkb' => ['required', 'boolean'],
            'has_tabungan' => ['required', 'boolean'],
            
            'is_learning_group' => ['required', 'boolean'],
            'learning_group_type' => ['required_if:is_learning_group,true,1', 'nullable', 'in:Paket A,Paket B,Paket C,KF'],
            
            'is_paud' => ['required', 'boolean'],
            
            'is_koperasi' => ['required', 'boolean'],
            'koperasi_type' => ['required_if:is_koperasi,true,1', 'nullable', 'string', 'max:255'],
        ];
    }
}
