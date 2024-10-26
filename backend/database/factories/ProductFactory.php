<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => Category::factory(), // Associate with a new category
            'name' => $this->faker->word, // Generate a random word as product name
            'description' => $this->faker->sentence, // Generate a random sentence
            'price' => $this->faker->randomFloat(2, 1, 1000), // Random price between 1 and 1000
            'stock' => $this->faker->numberBetween(0, 100), // Random stock between 0 and 100
        ];
    }
}
