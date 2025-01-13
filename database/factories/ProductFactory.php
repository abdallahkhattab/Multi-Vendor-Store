<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\Dashboard\Store;
use App\Models\Dashboard\Category;
use App\Models\Dashboard\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(15),
            'image' => $this->faker->imageUrl(800, 600),
            'price' => $this->faker->randomFloat(1, 1, 499),
            'compare_price' => $this->faker->randomFloat(1, 500, 999),
            'featured' => rand(0, 1),
            'category_id' => optional(Category::inRandomOrder()->first())->id,
            'store_id' => optional(Store::inRandomOrder()->first())->id,
        ];
    }
}
