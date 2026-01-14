<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Helm>
 */
class HelmFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'helmet_name' => $this->faker->randomElement([
                "Arai RX-7V",
                "Shoies X16",
                "KYT"
            ]),
            'condition' => $this->faker->randomElement(['good', 'very_good', 'excellent']),
            'status' => $this->faker->randomElement(['available', 'rented', 'maintenance']),
            'daily_price' => $this->faker->numberBetween(20000, 800000),
            'late_fee_per_day' => $this->faker->numberBetween(200000, 1500000)
        ];
    }
}
