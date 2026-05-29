<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAgentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $agentId = $this->route('agent')?->id ?? $this->route('agent');

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('agents', 'slug')->ignore($agentId)],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'whatsapp_phone' => ['nullable', 'string', 'max:32'],
            'designation' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:5000'],
            'city' => ['nullable', 'string', 'max:120'],
            'rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'reviews_count' => ['nullable', 'integer', 'min:0'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:50'],
            'languages' => ['nullable', 'array'],
            'languages.*' => ['string', 'max:64'],
            'is_active' => ['boolean'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'property_ids' => ['nullable', 'array'],
            'property_ids.*' => ['integer', 'exists:properties,id'],
        ];
    }
}
