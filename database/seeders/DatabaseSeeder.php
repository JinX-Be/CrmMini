<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Import config-declared custom fields into the database.
        $this->call(FieldDefinitionSeeder::class);

        User::updateOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Demo Owner',
                'password' => Hash::make('password'),
                'role' => 'owner',
            ],
        );

        // A couple of demo window-cleaning customers (idempotent on email).
        $demo = [
            [
                'name' => 'Marie Dubois',
                'email' => 'marie.dubois@example.com',
                'phone' => '+32 478 12 34 56',
                'address' => "Rue des Fleurs 12\n1000 Brussels",
                'notes' => 'Prefers morning appointments.',
                'custom_fields' => [
                    'num_windows' => 14,
                    'floors' => 2,
                    'access_notes' => 'Key under the mat; dog in garden.',
                    'frequency_weeks' => 8,
                ],
            ],
            [
                'name' => 'Tom Peeters',
                'email' => 'tom.peeters@example.com',
                'phone' => '+32 471 98 76 54',
                'address' => "Lange Steenstraat 88\n9000 Ghent",
                'notes' => null,
                'custom_fields' => [
                    'num_windows' => 22,
                    'floors' => 3,
                    'access_notes' => 'Ring twice.',
                    'frequency_weeks' => 6,
                ],
            ],
        ];

        foreach ($demo as $row) {
            Customer::updateOrCreate(
                ['email' => $row['email']],
                $row + ['niche' => 'window_cleaning'],
            );
        }
    }
}
