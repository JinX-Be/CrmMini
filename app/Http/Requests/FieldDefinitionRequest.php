<?php

namespace App\Http\Requests;

use App\Models\CustomFieldDefinition;
use App\Support\Niche;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class FieldDefinitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage-fields') ?? false;
    }

    /**
     * Normalise inputs before validation.
     */
    protected function prepareForValidation(): void
    {
        // Options come from a textarea (one per line) -> array.
        $options = $this->input('options');
        if (is_string($options)) {
            $options = collect(preg_split('/\r\n|\r|\n/', $options))
                ->map(fn ($o) => trim($o))
                ->filter()
                ->values()
                ->all();
        }

        $this->merge([
            'required' => $this->boolean('required'),
            'options' => $options ?: [],
            // Key is only settable on create, and always a stable slug.
            'key' => $this->isCreating()
                ? Str::slug((string) $this->input('key', $this->input('label')), '_')
                : $this->route('field')->key,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $niche = app(Niche::class)->key();

        $rules = [
            'label' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(CustomFieldDefinition::types())],
            'options' => ['array'],
            'options.*' => ['string', 'max:255'],
            'required' => ['boolean'],
            'help_text' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];

        // Select fields must have at least one option.
        if ($this->input('type') === 'select') {
            $rules['options'] = ['array', 'min:1'];
        }

        if ($this->isCreating()) {
            $rules['key'] = [
                'required', 'string', 'max:255', 'regex:/^[a-z0-9_]+$/',
                Rule::unique('custom_field_definitions', 'key')->where('niche', $niche),
            ];
        }

        return $rules;
    }

    public function isCreating(): bool
    {
        return $this->route('field') === null;
    }

    public function messages(): array
    {
        return [
            'key.regex' => 'The key may only contain lowercase letters, numbers and underscores.',
            'options.min' => 'Add at least one option for a select field (one per line).',
        ];
    }
}
