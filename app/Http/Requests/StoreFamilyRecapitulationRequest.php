<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFamilyRecapitulationRequest extends FormRequest
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
            'recaps' => ['required', 'array'],
            'recaps.*.family_id' => ['required', 'exists:families,id'],
            
            // Aggregated values
            'recaps.*.toddlers_male' => ['required', 'integer', 'min:0'],
            'recaps.*.toddlers_female' => ['required', 'integer', 'min:0'],
            'recaps.*.pus' => ['required', 'integer', 'min:0'],
            'recaps.*.wus' => ['required', 'integer', 'min:0'],
            'recaps.*.pregnant' => ['required', 'integer', 'min:0'],
            'recaps.*.breastfeeding' => ['required', 'integer', 'min:0'],
            'recaps.*.elderly' => ['required', 'integer', 'min:0'],
            'recaps.*.blind' => ['required', 'integer', 'min:0'],
            'recaps.*.special_needs' => ['required', 'integer', 'min:0'],
            
            // House criteria
            'recaps.*.is_healthy_house' => ['required', 'boolean'],
            'recaps.*.has_trash_bin' => ['required', 'boolean'],
            'recaps.*.has_spal' => ['required', 'boolean'],
            'recaps.*.has_p4k_sticker' => ['required', 'boolean'],
            
            // Food & Water
            'recaps.*.water_source' => ['required', 'string', 'max:255'],
            'recaps.*.staple_food' => ['required', 'string', 'max:255'],
            
            // Activities
            'recaps.*.activity_up2k' => ['required', 'boolean'],
            'recaps.*.activity_pekarangan' => ['required', 'boolean'],
            'recaps.*.activity_industri' => ['required', 'boolean'],
            'recaps.*.activity_kerja_bakti' => ['required', 'boolean'],
        ];
    }
}
