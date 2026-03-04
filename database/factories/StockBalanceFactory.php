<?php

namespace Database\Factories;

use App\Models\StockBalance;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockBalanceFactory extends Factory
{
    protected $model = StockBalance::class;

    public function definition()
    {
        return [
            'product_id' => Product::inRandomOrder()->first()->id ?? Product::factory(),
            'qty_on_hand' => $this->faker->numberBetween(0, 200),
            'location' => $this->faker->randomElement(['Gudang A','Gudang B']),
        ];
    }
}