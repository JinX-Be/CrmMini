<?php

namespace App\Providers;

use App\Models\User;
use App\Support\FieldDefinitions\DatabaseFieldDefinitions;
use App\Support\FieldDefinitions\FieldDefinitionRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Custom-field definitions are now owner-editable, stored in the
        // database. (Swap for ConfigFieldDefinitions to read from config.)
        // Singleton so the per-niche lookup is queried once per request.
        $this->app->singleton(FieldDefinitionRepository::class, DatabaseFieldDefinitions::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Only owners may manage custom-field definitions.
        Gate::define('manage-fields', fn (User $user) => $user->isOwner());
    }
}
