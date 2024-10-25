<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBuildingRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'region_id' => ['required', 'exists:regions,region_number'],
            'postcode' => ['required', 'string', 'max:6'],
            'district' => ['string', 'max:255'],
            'locality' => ['required', 'string', 'max:255'],
            'street' => ['string', 'max:255'],
            'building_number' => ['string', 'max:255'],
        ];
    }
}
