<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Size;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'sku' => $this->faker->unique()->bothify('SKU-####'),
            'name' => $this->faker->words(3, true),
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'size_id' => Size::inRandomOrder()->first()->id ?? Size::factory(),
            'unit' => $this->faker->randomElement(['pcs','box','kg']),
            'min_stock' => $this->faker->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}