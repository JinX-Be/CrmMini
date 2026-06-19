<?php

namespace App\Support;

use Illuminate\Support\Arr;
use InvalidArgumentException;

/**
 * Represents the niche that is active for the current request.
 *
 * The rest of the application depends on this object, never on how the niche
 * was resolved. Today it is resolved from config (see ResolveNiche); later it
 * can be resolved from the hostname without touching any consumer.
 */
class Niche
{
    /**
     * @param  array<string, mixed>  $definition
     */
    public function __construct(
        protected string $key,
        protected array $definition,
    ) {}

    /**
     * Build a Niche from its config key.
     */
    public static function fromKey(string $key): self
    {
        $definition = config("niche.definitions.{$key}");

        if (! is_array($definition)) {
            throw new InvalidArgumentException("Unknown niche [{$key}].");
        }

        return new self($key, $definition);
    }

    public function key(): string
    {
        return $this->key;
    }

    public function label(): string
    {
        return $this->definition['label'] ?? ucfirst(str_replace('_', ' ', $this->key));
    }

    public function theme(): string
    {
        return $this->definition['theme'] ?? '#2563eb';
    }

    /**
     * Niche-specific terminology, e.g. term('customers') => "Clients".
     */
    public function term(string $key, ?string $default = null): string
    {
        return $this->definition['terms'][$key] ?? $default ?? ucfirst($key);
    }

    /**
     * The custom-field schema applied to customers in this niche.
     *
     * @return array<int, array<string, mixed>>
     */
    public function customerFields(): array
    {
        return $this->definition['customer_fields'] ?? [];
    }

    /**
     * The list of valid custom-field keys for this niche.
     *
     * @return array<int, string>
     */
    public function customerFieldKeys(): array
    {
        return Arr::pluck($this->customerFields(), 'key');
    }
}
