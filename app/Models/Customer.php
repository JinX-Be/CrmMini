<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;

    protected $fillable = [
        'niche',
        'name',
        'email',
        'phone',
        'address',
        'notes',
        'custom_fields',
    ];

    protected function casts(): array
    {
        return [
            'custom_fields' => 'array',
        ];
    }

    /**
     * Read a single niche custom-field value by key.
     */
    public function customField(string $key, mixed $default = null): mixed
    {
        return data_get($this->custom_fields, $key, $default);
    }
}
