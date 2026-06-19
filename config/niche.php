<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Active niche resolution
    |--------------------------------------------------------------------------
    |
    | Phase 0-4: the active niche is taken from the NICHE env value.
    | Phase 5: ResolveNiche middleware will instead map the request hostname
    | (or subdomain) to a niche key using the "domains" map below. The rest of
    | the app only ever reads the *resolved* niche, so promoting from config to
    | URL resolution requires no changes outside the middleware.
    |
    */

    'active' => env('NICHE', 'window_cleaning'),

    // Phase 5: hostname => niche key. Example:
    // 'windows.example.com' => 'window_cleaning',
    // 'solar.example.com'   => 'solar_cleaning',
    'domains' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Niche definitions
    |--------------------------------------------------------------------------
    |
    | Each niche shares the same CRM engine (customers, appointments, invoices,
    | reminders). Only the presentation and the customer custom-field schema
    | differ. Add a new niche by adding an entry here — no engine changes.
    |
    | Custom field types: text, number, select, checkbox, textarea.
    |
    */

    'definitions' => [

        'window_cleaning' => [
            'label' => 'Window Cleaning',
            'theme' => '#2563eb', // blue
            'terms' => [
                'customer' => 'Customer',
                'customers' => 'Customers',
                'service' => 'Service',
            ],
            'customer_fields' => [
                ['key' => 'num_windows', 'label' => 'Number of windows', 'type' => 'number'],
                ['key' => 'floors', 'label' => 'Floors', 'type' => 'number'],
                ['key' => 'access_notes', 'label' => 'Access notes', 'type' => 'textarea'],
                ['key' => 'frequency_weeks', 'label' => 'Preferred frequency (weeks)', 'type' => 'number'],
            ],
        ],

        // Example future niches (kept here as a template, not yet active):

        'solar_cleaning' => [
            'label' => 'Solar Panel Cleaning',
            'theme' => '#f59e0b', // amber
            'terms' => [
                'customer' => 'Customer',
                'customers' => 'Customers',
                'service' => 'Service',
            ],
            'customer_fields' => [
                ['key' => 'num_panels', 'label' => 'Number of panels', 'type' => 'number'],
                ['key' => 'roof_type', 'label' => 'Roof type', 'type' => 'text'],
                ['key' => 'roof_pitch', 'label' => 'Roof pitch', 'type' => 'text'],
                ['key' => 'access_notes', 'label' => 'Access notes', 'type' => 'textarea'],
            ],
        ],

        'dog_grooming' => [
            'label' => 'Dog Grooming',
            'theme' => '#16a34a', // green
            'terms' => [
                'customer' => 'Client',
                'customers' => 'Clients',
                'service' => 'Groom',
            ],
            'customer_fields' => [
                ['key' => 'dog_name', 'label' => 'Dog name', 'type' => 'text'],
                ['key' => 'breed', 'label' => 'Breed', 'type' => 'text'],
                ['key' => 'coat_type', 'label' => 'Coat type', 'type' => 'select', 'options' => ['Short', 'Medium', 'Long', 'Curly', 'Double']],
                ['key' => 'temperament_notes', 'label' => 'Temperament notes', 'type' => 'textarea'],
            ],
        ],

    ],

];
