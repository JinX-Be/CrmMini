<?php

namespace App\Support\FieldDefinitions;

use App\Models\CustomFieldDefinition;
use Illuminate\Support\Collection;

/**
 * Reads custom-field definitions from the custom_field_definitions table.
 *
 * Bound as a singleton so the per-niche lookup is queried once per request.
 */
class DatabaseFieldDefinitions implements FieldDefinitionRepository
{
    /** @var array<string, \Illuminate\Support\Collection<int, \App\Support\FieldDefinition>> */
    protected array $cache = [];

    public function forNiche(string $niche): Collection
    {
        return $this->cache[$niche] ??= CustomFieldDefinition::query()
            ->where('niche', $niche)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (CustomFieldDefinition $row) => $row->toValueObject())
            ->values();
    }
}
