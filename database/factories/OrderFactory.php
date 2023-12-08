<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Fill with fake data
            // 'total' => $this->faker->numberBetween(100, 5000),
            // 'address' => $this->faker->address(),
            // 'recipient' => $this->faker->name(),
        ];
    }
}
