<?php

namespace Database\Seeders;

use App\Models\CustomFieldDefinition;
use Illuminate\Database\Seeder;

/**
 * Imports the custom-field definitions declared in config/niche.php into the
 * custom_field_definitions table. Idempotent on (niche, key), so it doubles as
 * the bridge from the old config-driven setup to the DB-backed one.
 */
class FieldDefinitionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('niche.definitions', []) as $niche => $definition) {
            foreach ($definition['customer_fields'] ?? [] as $i => $field) {
                CustomFieldDefinition::updateOrCreate(
                    ['niche' => $niche, 'key' => $field['key']],
                    [
                        'label' => $field['label'] ?? $field['key'],
                        'type' => $field['type'] ?? 'text',
                        'options' => $field['options'] ?? null,
                        'required' => (bool) ($field['required'] ?? false),
                        'help_text' => $field['help_text'] ?? null,
                        'sort_order' => (int) ($field['sort_order'] ?? $i),
                    ],
                );
            }
        }
    }
}
