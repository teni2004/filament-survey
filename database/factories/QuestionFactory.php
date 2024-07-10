<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Survey;
use App\Models\Question;
use App\Models\Option;
use App\Models\RatingOption;
use Illuminate\Support\Str;
use App\Enums\QuestionTypeEnum;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    private function getRandomType(): QuestionTypeEnum {
        $cases = QuestionTypeEnum::cases();
        return $cases[array_rand($cases)];
    }

    private function createOptions($question) {
        switch ($question->type) 
            {
                case QuestionTypeEnum::RATING:
                    $rating_option = RatingOption::factory()->create([
                        'question_id' => $question->id 
                    ]);
                    $question->rating_options()->save($rating_option);
                    break;
                case QuestionTypeEnum::MULTIPLECHOICE:
                    $optionNo = rand(2,6);
                    for ($i = 0; $i < $optionNo; $i++)
                    {
                        Option::factory()->create([
                            'question_id' => $question->id 
                        ]);
                    }
                    break;
                case QuestionTypeEnum::SELECTONE:
                    $optionNo = rand(2,6);
                    for ($i = 0; $i < $optionNo; $i++)
                    {
                        Option::factory()->create([
                            'question_id' => $question->id 
                        ]);
                    }
                    break;
            }
    }

    public function definition(): array
    {
        $type = $this->getRandomType();

        return [
            'text' => rtrim(fake()->sentence(), '.') . '?',
            'label' => Str::random(rand(4, 10)),
            'type' => $type,
            'required' => $type == QuestionTypeEnum::RATING ? 1 : rand(0,1)
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Question $question) {
            $this->createOptions($question);
        });
    }
}
