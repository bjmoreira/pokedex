<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * EN: Factory for the Captured model. / PT: Factory para o model Captured.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Captured>
 */
class CapturedFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'pokemon_id' => fake()->numberBetween(1, 151),
            'nickname' => fake()->optional()->firstName(),
            'level' => (string) fake()->numberBetween(1, 100),
            'detail_note' => fake()->optional()->sentence(),
        ];
    }
}
