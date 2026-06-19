<?php

namespace App\Support\FieldDefinitions;

use Illuminate\Support\Collection;

/**
 * Supplies the custom-field definitions for a niche.
 *
 * Swap the bound implementation (config -> database) to change where fields
 * come from. Consumers never depend on the source.
 */
interface FieldDefinitionRepository
{
    /**
     * @return \Illuminate\Support\Collection<int, \App\Support\FieldDefinition>
     */
    public function forNiche(string $niche): Collection;
}
