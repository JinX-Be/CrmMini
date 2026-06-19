<?php

namespace App\Http\Requests;

use App\Support\Niche;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];

        // Add a rule per niche custom field (rules defined on the value object).
        foreach ($this->niche()->customerFields() as $field) {
            $rules['custom.'.$field->key] = $field->validationRules();
        }

        return $rules;
    }

    /**
     * Build the persisted attributes (standard columns + filtered custom_fields).
     *
     * @return array<string, mixed>
     */
    public function customerData(): array
    {
        $niche = $this->niche();

        $custom = collect($this->input('custom', []))
            ->only($niche->customerFieldKeys())
            ->all();

        return [
            'niche' => $niche->key(),
            'name' => $this->input('name'),
            'email' => $this->input('email'),
            'phone' => $this->input('phone'),
            'address' => $this->input('address'),
            'notes' => $this->input('notes'),
            'custom_fields' => $custom,
        ];
    }

    protected function niche(): Niche
    {
        return app(Niche::class);
    }
}
