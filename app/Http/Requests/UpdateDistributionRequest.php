<?php

namespace App\Http\Requests;

use App\Models\Distribution;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDistributionRequest extends FormRequest
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
            'building_id' => ['sometimes', 'exists:buildings,id'],
            'leaflet_id' => ['sometimes', 'exists:leaflets,id'],
            'began_at' => ['nullable', 'date'],
            'ended_at' => ['nullable', 'date', 'after_or_equal:began_at'],
        ];
    }

    public function applyChanges(Distribution $distribution): Distribution
    {
        $distribution->update($this->validated());

        return $distribution;
    }
}
