<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Team;
use App\Models\Question;
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

    private function getUserId()
    {
        if (rand(0,1)) {
            return User::factory()->create()->id;
        }
        else {
            return User::inRandomOrder()->first()->id ?? User::factory()->create()->id;
        }
    }

    private function getPublishedBool()
    {
        if (rand(0,4)) {
            return "1";
        }
        else {
            return "0";
        }
    }

    private function attachTeams($survey)
    {
        $attachedTeamIds = [];
            $teamNo = rand(1,3);
            for ($i = 0; $i < $teamNo; $i++)
            {
                do {
                    $team = Team::inRandomOrder()->first();
                } while (in_array($team->id, $attachedTeamIds));

                $survey->teams()->attach($team->id);
            
                $attachedTeamIds[] = $team->id;
            }
    }

    private function createQuestions($survey)
    {
        $questionNo = rand(1,5);
        for ($i = 0; $i < $questionNo; $i++)
        {
            $question = Question::factory()->create([
                'survey_id' => $survey->id
            ]);
        }
    }

    public function definition(): array
    {
        return [
            'user_id' => $this->getUserId(),
            'name' => ucfirst(fake()->word()) . ' Survey',
            'published' => $this->getPublishedBool(),
        ];
    }

    public function configure()
    {
   
        return $this->afterCreating(function (Survey $survey) {
            $this->attachTeams($survey);
            $this->createQuestions($survey);
        });
    }
}
