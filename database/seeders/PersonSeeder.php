<?php

namespace Database\Seeders;

use App\Models\Person;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    public function run()
    {
        $people = [
            ['name' => 'John Doe', 'is_active' => true],
            ['name' => 'Jane Smith', 'is_active' => true],
            ['name' => 'Bob Johnson', 'is_active' => true],
            ['name' => 'George Floyd', 'is_active' => true],
            ['name' => 'Charlie Kirk', 'is_active' => true],
        ];

        foreach ($people as $person) {
            Person::create($person);
        }

        $this->command->info('People seeded successfully!');
    }
}
