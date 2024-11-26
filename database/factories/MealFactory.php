<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Meal>
 */
class MealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'price' => $this->faker->randomFloat(2,20,500),
            'description' => $this->faker->sentence(5),
            'quantity_available' => $this->faker->numberBetween(1, 100),
            'discount' => $this->faker->randomFloat(2, 0, 20),



        ];
    }
}
