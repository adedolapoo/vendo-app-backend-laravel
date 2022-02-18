<?php
namespace Modules\Products\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Products\Entities\Product;

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
            'name' => $this->faker->name(),
            'cost' => $this->faker->numberBetween(0, 100),
            'amount_available' => 10,
            'seller_id' => 1,
        ];
    }
}

