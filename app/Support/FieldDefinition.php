<?php

namespace App\Support;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * A single niche custom-field definition.
 *
 * This value object is the contract the rest of the app depends on. Today it is
 * built from config (ConfigFieldDefinitions); later it can be built from a
 * database row (DatabaseFieldDefinitions) with no change to forms, validation,
 * or storage — the consumers only ever see FieldDefinition objects.
 */
class FieldDefinition
{
    /**
     * @param  array<int, string>  $options  Choices for the "select" type.
     */
    public function __construct(
        public readonly string $key,
        public readonly string $label,
        public readonly string $type = 'text',
        public readonly array $options = [],
        public readonly bool $required = false,
        public readonly ?string $helpText = null,
        public readonly int $sortOrder = 0,
    ) {}

    /**
     * Build from an associative array (config row or, later, a DB row).
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            key: $data['key'],
            label: $data['label'] ?? Str::headline($data['key']),
            type: $data['type'] ?? 'text',
            options: array_values($data['options'] ?? []),
            required: (bool) ($data['required'] ?? false),
            helpText: $data['help_text'] ?? $data['helpText'] ?? null,
            sortOrder: (int) ($data['sort_order'] ?? $data['sortOrder'] ?? 0),
        );
    }

    /**
     * Laravel validation rules for this field's input (`custom.<key>`).
     *
     * @return array<int, mixed>
     */
    public function validationRules(): array
    {
        $rules = [$this->required ? 'required' : 'nullable'];

        return array_merge($rules, match ($this->type) {
            'number' => ['numeric'],
            'checkbox' => ['boolean'],
            'select' => [Rule::in($this->options)],
            default => ['string', 'max:2000'],
        });
    }
}
