<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Team;
use App\Models\Survey;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Survey>
 */
class SurveyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->id,
            'name' => fake()->word() . ' Survey',
            'published' => '1', //maybe i can make this random between 1 and 0 later
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Survey $survey) {
            // Associate teams with the survey
            $team = Team::factory()->create(); // Example: Create 3 teams
            $survey->teams()->attach($team->id);
        });
    }
}
