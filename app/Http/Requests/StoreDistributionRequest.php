<?php

namespace App\Http\Requests;

use App\Models\Distribution;
use Illuminate\Foundation\Http\FormRequest;

class StoreDistributionRequest extends FormRequest
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
            'building_id' => ['required', 'exists:buildings,id'],
            'leaflet_id' => ['required', 'exists:leaflets,id'],
        ];
    }

    public function store(): Distribution
    {
        return Distribution::create([
            'user_id' => auth()->id(),
            'building_id' => $this->input('building_id'),
            'leaflet_id' => $this->input('leaflet_id'),
            'began_at' => now(),
        ]);
    }
}
