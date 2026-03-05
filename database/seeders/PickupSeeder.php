<?php

namespace Database\Seeders;

use App\Models\Pickup;
use App\Models\PickupLine;
use App\Models\Product;
use App\Models\Floor;
use App\Models\User;
use Illuminate\Database\Seeder;

class PickupSeeder extends Seeder
{
    public function run()
    {
        // Get existing users, floors, and products
        $users = User::all();
        $floors = Floor::all();
        $products = Product::all();

        if ($users->isEmpty() || $floors->isEmpty() || $products->isEmpty()) {
            $this->command->info('Please run other seeders first (User, Floor, Product)');
            return;
        }

        // Create multiple pickups with different floors
        for ($i = 1; $i <= 15; $i++) {
            // Randomly select user and floor
            $user = $users->random();
            $floor = $floors->random();

            // Create a Pickup
            $pickup = Pickup::create([
                'pickup_no' => 'PU-2026-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'pickup_date' => now()->subDays(rand(0, 30)),
                'requested_by' => $user->id,
                'floor_id' => $floor->id,
                'notes' => 'Sample pickup ' . $i,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);

            // Create 1-3 PickupLines (items)
            $numItems = rand(1, min(3, $products->count()));
            $randomProducts = $products->random($numItems);

            foreach ($randomProducts as $product) {
                PickupLine::create([
                    'pickup_id' => $pickup->id,
                    'product_id' => $product->id,
                    'qty' => rand(1, 10),
                    'notes' => 'Item for pickup ' . $i,
                    'created_by' => $user->id,
                    'updated_by' => $user->id,
                ]);
            }
        }

        $this->command->info('Pickups with multiple floors seeded successfully!');
    }
}
