<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Team;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    private function getTeam()
    {
        if (rand(0,4)) {
            return Team::inRandomOrder()->first() ?? Team::factory()->create();
        }
        else {
            return Team::factory()->create();
        }
    }

    private function attachTeams($user)
    {
        $attachedTeamIds = [];
            $teamNo = rand(1,3);
            for ($i = 0; $i < $teamNo; $i++)
            {
                do {
                    $team = Team::inRandomOrder()->first();
                } while (in_array($team->id, $attachedTeamIds));

                $user->teams()->attach($team->id);
            
                $attachedTeamIds[] = $team->id;
            }
    }

    public function definition(): array
    {
        $name = fake()->unique()->firstName();

        return [
            'name' => $name,
            'email' => strtolower($name) . '@givelify.com',
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            $this->attachTeams($user);
        });
    }
}
