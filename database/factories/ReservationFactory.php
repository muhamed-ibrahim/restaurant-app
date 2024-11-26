<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'table_id' => $this->faker->numberBetween(1, 10),
            'user_id' => $this->faker->numberBetween(1, 30),
            'from_time' => $fromTime = $this->faker->dateTimeBetween('now', '+1 week'),
            'to_time' => Carbon::instance($fromTime)->addHours(rand(1, 3)),
        ];
    }
}
