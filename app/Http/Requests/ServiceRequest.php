<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['active' => $this->boolean('active')]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'default_price' => ['required', 'numeric', 'min:0'],
            'default_duration_minutes' => ['required', 'integer', 'min:5'],
            'default_interval_weeks' => ['nullable', 'integer', 'min:1'],
            'active' => ['boolean'],
        ];
    }
}
