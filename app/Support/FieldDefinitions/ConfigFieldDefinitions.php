<?php

namespace App\Support\FieldDefinitions;

use App\Support\FieldDefinition;
use Illuminate\Support\Collection;

/**
 * Reads custom-field definitions from config/niche.php.
 *
 * To make fields user-editable at runtime later, add a DatabaseFieldDefinitions
 * implementation (backed by a field_definitions table + admin UI) and rebind
 * FieldDefinitionRepository to it in AppServiceProvider. Nothing else changes.
 */
class ConfigFieldDefinitions implements FieldDefinitionRepository
{
    public function forNiche(string $niche): Collection
    {
        return collect(config("niche.definitions.{$niche}.customer_fields", []))
            ->map(fn (array $field) => FieldDefinition::fromArray($field))
            ->sortBy(fn (FieldDefinition $field) => $field->sortOrder)
            ->values();
    }
}
