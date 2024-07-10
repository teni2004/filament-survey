<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RatingOption>
 */
class RatingOptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'min_value' => 0,
            'max_value' => [5, 10, 10, 10, 10, 100][array_rand([5, 10, 10, 10, 10, 100])],
        ];
    }
}
