<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory;

    protected $fillable = [
        'niche',
        'name',
        'default_price',
        'default_duration_minutes',
        'default_interval_weeks',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'default_price' => 'decimal:2',
            'default_duration_minutes' => 'integer',
            'default_interval_weeks' => 'integer',
            'active' => 'boolean',
        ];
    }

    /**
     * @return HasMany<Appointment, $this>
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
