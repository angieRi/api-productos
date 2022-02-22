<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence(5),
            'image' =>$this->faker->image(null, 360, 360, 'phone', true, true, 'computer', true),
            'brand' => $this->faker->company,
            'price' => $this->faker->randomFloat(2,500,10000),
            'price_sale' => $this->faker->randomFloat(2,200,5000),
            'category' => $this->faker->company,
            'stock' =>  $this->faker->numberBetween(0,1500),
        ];

    }
}
