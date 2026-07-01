<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFamilyRequest extends FormRequest
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
            'dasa_wisma' => ['required', 'string', 'max:255'],
            'rt' => ['required', 'string', 'max:5'],
            'rw' => ['required', 'string', 'max:5'],
            'dusun' => ['required', 'in:Murni,Lestari'],
            'head_of_family' => ['required', 'string', 'max:255'],
            'total_members' => ['required', 'integer', 'min:1'],
            'total_male' => ['required', 'integer', 'min:0'],
            'total_female' => ['required', 'integer', 'min:0'],
            'members' => ['required', 'array', 'size:' . $this->input('total_members', 0)],
            'members.*.registration_number' => ['required', 'string', 'max:255'],
            'members.*.name' => ['required', 'string', 'max:255'],
            'members.*.family_relation_status' => ['required', 'string'],
            'members.*.gender' => ['required', 'in:L,P'],
            'members.*.date_of_birth' => ['required', 'date'],
            'members.*.age' => ['required', 'integer', 'min:0'],
            'members.*.education' => ['required', 'string'],
            'members.*.occupation' => ['required', 'string'],
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function ($validator) {
                $totalMembers = (int) $this->input('total_members', 0);
                $totalMale = (int) $this->input('total_male', 0);
                $totalFemale = (int) $this->input('total_female', 0);
                
                if (($totalMale + $totalFemale) !== $totalMembers) {
                    $validator->errors()->add(
                        'total_members',
                        'Total akumulasi Laki-laki dan Perempuan harus sama dengan Jumlah Anggota Keluarga.'
                    );
                }
            }
        ];
    }
}
