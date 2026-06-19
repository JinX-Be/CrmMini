<?php

namespace App\Models;

use App\Support\FieldDefinition;
use Illuminate\Database\Eloquent\Model;

/**
 * Persisted (owner-editable) custom-field definition for a niche.
 *
 * Maps to the App\Support\FieldDefinition value object that the rest of the app
 * consumes via the FieldDefinitionRepository.
 */
class CustomFieldDefinition extends Model
{
    protected $fillable = [
        'niche',
        'key',
        'label',
        'type',
        'options',
        'required',
        'help_text',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'required' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Allowed field types.
     *
     * @return array<int, string>
     */
    public static function types(): array
    {
        return ['text', 'number', 'select', 'checkbox', 'textarea'];
    }

    /**
     * Convert to the value object consumed by forms and validation.
     */
    public function toValueObject(): FieldDefinition
    {
        return FieldDefinition::fromArray([
            'key' => $this->key,
            'label' => $this->label,
            'type' => $this->type,
            'options' => $this->options ?? [],
            'required' => $this->required,
            'help_text' => $this->help_text,
            'sort_order' => $this->sort_order,
        ]);
    }
}
