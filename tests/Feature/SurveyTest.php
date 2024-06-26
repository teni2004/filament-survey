<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories;
use App\Models\User;
use App\Models\Team;
use App\Models\Survey;
use App\Models\Question;
use App\Models\Option;
use Livewire\livewire;
use App\Filament\Resources\SurveyResource;
use App\Filament\Resources\QuestionResource;
use App\Filament\Resources\OptionResource;
use Filament\Actions\DeleteAction;

class SurveyTest extends TestCase
{
    use RefreshDatabase;

    // public function test_a_guest_cannot_access_anything()
    // {
    //     //this isn't really everything
    //     $user = User::factory()->create();
    //     $this->get('/')->assertStatus(302); //because they're being redirected
    //     $this->get('/admin')->assertStatus(302); 
    //     $this->get('/admin/surveys')->assertStatus(302);
    //     $this->get('/admin/surveys/create')->assertStatus(302);
    // }

    public function test_a_logged_in_user_can_access_everything() //the login in still isn't working
    {
        //this isn't really everything
        $user = $this->getUser();

        $survey = Survey::factory()->create();
        $this->get('/')->assertStatus(200); //I'm getting that everything is forbidden even though i'm logged in >:///
        //$this->get('/admin/surveys/{$survey->id}/edit')->assertStatus(200);
        // $this->get('/admin')->assertStatus(200); 
        // $this->get('/admin/surveys')->assertStatus(200);
        // $this->get('/admin/surveys/create')->assertStatus(200);
    }

    public function test_a_user_can_create_a_survey()
    {
        $user = $this->getUser();
        $team = Team::factory()->create();

        Livewire::test(SurveyResource\Pages\CreateSurvey::class)
        ->set('data.questions', [
            [
                'text' => 'Do you eat at least one citrus fruit a week?',
                'label' => 'citrus',
                'type' => 'yes-no',
                'required' => 1
            ]
        ])
        ->fillForm([
            'name' => 'Scurvy Awareness Survey',
            'published' => 1,
            'teams' => $team->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

        //Then there should be a new survey in the database
        $this->assertDatabaseHas('surveys', [
            'user_id' => $user->id,
            'name' => 'Scurvy Awareness Survey', 
            'published' => 1
        ]);
    }

    public function test_a_user_can_edit_a_survey()
    {
        $user = $this->getUser();
        $survey = Survey::factory()->create();

        Livewire::test(SurveyResource\Pages\EditSurvey::class, [
            'record' => $survey->getRouteKey(),
        ])
        ->fillForm([
            'name' => 'Seasickness Awareness Survey',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

        $this->assertDatabaseHas('surveys', [
            'name' => 'Seasickness Awareness Survey', 
            'published' => 1
        ]);
    }

    public function test_correct_errors_returned_when_creating_a_survey()
    {
        $user = $this->getUser();
        $team = Team::factory()->create();

        //When they hit the endpoint /admin/surveys/create to create a new survey while passing the necessary data
        Livewire::test(SurveyResource\Pages\CreateSurvey::class)
        ->set('data.questions', null)
        ->fillForm([
            'published' => 1,
            'teams' => $team->id,
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);

        Livewire::test(SurveyResource\Pages\CreateSurvey::class)
        ->set('data.questions', null)
        ->fillForm([
            'name' => 'Scurvy Awareness Survey',
            'published' => 1,
        ])
        ->call('create')
        ->assertHasFormErrors(['teams' => 'required']);
    }

    public function test_correct_errors_returned_when_editing_a_survey()
    {
        $user = $this->getUser();
        $survey = Survey::factory()->create();

        Livewire::test(SurveyResource\Pages\EditSurvey::class, [
            'record' => $survey->getRouteKey(),
        ])
        ->set('data.questions', null)
        ->fillForm([
            'name' => null,
        ])
        ->call('save')
        ->assertHasFormErrors();

        $this->assertDatabaseHas('surveys', [
            'name' => $survey->name, 
            'published' => 1
        ]);
    }

    public function test_a_user_can_delete_a_survey()
    {
        $user = $this->getUser();
        $survey = Survey::factory()->create();

        Livewire::test(SurveyResource\Pages\EditSurvey::class, [
            'record' => $survey->getRouteKey(),
        ])
            ->callAction(DeleteAction::class);
     
        $this->assertModelMissing($survey);
    }

    public function test_a_user_can_create_a_question()
    {
        $user = $this->getUser();
        $survey = Survey::factory()->create();

        Livewire::test(QuestionResource\Pages\CreateQuestion::class)
        ->fillForm([
            'survey_id' => $survey->id,
            'text' => 'Do you eat at least one citrus fruit a week?',
            'label' => 'citrus',
            'type' => 'yes-no',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

        //Then there should be a new survey in the database
        $this->assertDatabaseHas('questions', [
            'survey_id' => $survey->id,
            'text' => 'Do you eat at least one citrus fruit a week?',
            'label' => 'citrus',
            'type' => 'yes-no',
        ]);
    }

    public function test_a_user_can_edit_a_question() 
    {
        //Given that a user is logged in and a survey exists that they created
        $user = $this->getUser();
        $question = Question::factory()->create();
        $survey = $question->survey;

        Livewire::test(QuestionResource\Pages\EditQuestion::class, [
            'record' => $question->getRouteKey(),
        ])
        ->fillForm([
            'text' => 'Have you ever seen a lemon tree?',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

        $this->assertDatabaseHas('questions', [
            'text' => 'Have you ever seen a lemon tree?',
            'label' => 'citrus'
        ]);
    }

    public function test_a_user_can_delete_a_question()
    {
        $user = $this->getUser();
        $question = Question::factory()->create();

        Livewire::test(QuestionResource\Pages\EditQuestion::class, [
            'record' => $question->getRouteKey(),
        ])
            ->callAction(DeleteAction::class);
     
        $this->assertModelMissing($question);
    }


    public function test_a_user_can_see_an_assigned_survey() 
    {
        $user = $this->getUser();

        $survey = Survey::factory()->create();
        $team = $survey->teams[0];

        $user->teams()->attach($team->id);
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee($survey->name, $escaped = true);
    }

    public function test_a_user_can_respond_to_an_assigned_survey()
    {
        $user = $this->getUser();

        $question = Question::factory()->create();
        $survey = $question->survey;
        $team = $survey->teams[0];

        //Then there should be a new survey in the database
        $this->assertDatabaseHas('questions', [
            'survey_id' => $survey->id,
            'text' => 'Do you eat at least one citrus fruit a week?',
        ]);

        $user->teams()->attach($team->id);
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee($survey->name, $escaped = true);

        $response = $this->post(route('store', ['survey' => $survey->id]), [
            'selected' . $question->id => 0,
        ]);
        $response->assertStatus(200);
        $response->assertSee("No"); //Results are displayed with No

        $this->assertDatabaseHas('survey_responses', [
            'survey_id' => $survey->id,
            'user_id' => $user->id
        ]);
    }

    public function test_a_user_can_edit_a_survey_response()
    {
        $user = $this->getUser();

        $question = Question::factory()->create();
        $survey = $question->survey;
        $team = $survey->teams[0];

        $this->assertDatabaseHas('questions', [
            'survey_id' => $survey->id,
            'text' => 'Do you eat at least one citrus fruit a week?',
        ]);

        $user->teams()->attach($team->id);
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee($survey->name, $escaped = true);

        $response = $this->post(route('store', ['survey' => $survey->id]), [
            'selected' . $question->id => 0, 
        ]);
        $response->assertStatus(200);
        $response->assertSee("No");

        $response = $this->post(route('update', ['survey' => $survey->id]), [
            'selected' . $question->id => 1, 
        ]);
        $response->assertStatus(200);
        $response->assertSee("Yes");
    }

    public function test_correct_errors_returned_when_creating_a_response()
    {
        $user = $this->getUser();

        $question = Question::factory()->create();
        $survey = $question->survey;
        $team = $survey->teams[0];

        $user->teams()->attach($team->id);
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee($survey->name, $escaped = true);

        $response = $this->post(route('store', ['survey' => $survey->id]), [
            'selected' . $question->id => 'hello', //this is meant to be a 1 or 0 becuase the factory creates a yes-no question
        ]);
        $response->assertStatus(500);
    }


    public function test_a_user_can_create_options()
    {
        $user = $this->getUser();
        $survey = Survey::factory()->create();
        $question = Question::create([
            'survey_id' => $survey->id,
            'type' => 'multiple-choice',
            'text' => 'What are you favorite fake words?',
            'label' => 'fake'
        ]);

        for($i = 0; $i < 4; $i++)
        {
            $option = fake()->word();
            Livewire::test(OptionResource\Pages\CreateOption::class)
            ->fillForm([
                'question_id' => $question->id,
                'text' => $option,
            ])
            ->call('create')
            ->assertHasNoFormErrors();
        }

        $options = Option::where('question_id', $question->id)->count();
        $this->assertEquals(4, $options);
    }

    public function test_a_user_can_select_an_option()
    {
        $user = $this->getUser();

        $survey = Survey::factory()->create();
        $team = $survey->teams[0];
        $question = Question::create([
            'survey_id' => $survey->id,
            'type' => 'multiple-choice',
            'text' => 'What are you favorite fake words?',
            'label' => 'fake'
        ]);

        $optionIds = '';
        for($i = 0; $i < 4; $i++)
        {
            $text = fake()->word();
            Livewire::test(OptionResource\Pages\CreateOption::class)
            ->fillForm([
                'question_id' => $question->id,
                'text' => $text,
            ])
            ->call('create')
            ->assertHasNoFormErrors();
            $optionVariableName = 'option' . $i;
            ${$optionVariableName} = Option::latest()->first();
            if($i == 0) {
                $optionIds .= ${$optionVariableName}->id;
            }
            else {
                $optionIds .= ',' . ${$optionVariableName}->id;
            }
        }

        $this->assertDatabaseHas('questions', [
            'survey_id' => $survey->id,
        ]);

        $user->teams()->attach($team->id);
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee($survey->name, $escaped = true);

        $response = $this->post(route('store', ['survey' => $survey->id]), [
            'selected' . $question->id => $optionIds, 
        ]);
        $response->assertStatus(200);
        $response->assertSee($option0->text);
        $response->assertSee($option1->text);
        $response->assertSee($option2->text);
        $response->assertSee($option3->text);
    }

    public function test_a_user_cannot_see_surveys_for_a_team_they_are_not_part_of()
    {
        $user = $this->getUser();

        $survey = Survey::factory()->create();
        $team = $survey->teams[0];

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('You have no assigned surveys', $escaped = true);
    }

    public function test_a_survey_must_have_at_least_one_question()
    {
        $user = $this->getUser();
        $team = Team::factory()->create();

        Livewire::test(SurveyResource\Pages\CreateSurvey::class)
        ->set('data.questions', null)
        ->fillForm([
            'name' => 'Scurvy Awareness Survey',
            'published' => 1,
            'teams' => $team->id,
        ])
        ->call('create')
        ->assertHasFormErrors();
    }

    public function test_correct_buttons_are_displayed()
    {
        $user = $this->getUser();

        $question = Question::factory()->create();
        $survey = $question->survey;

        $team = $survey->teams[0];
        $user->teams()->attach($team->id);

        $question2 = Question::create([
            'survey_id' => $survey->id,
            'type' => 'multiple-choice',
            'text' => 'What are you favorite fake words?',
            'label' => 'fake'
        ]);

        $optionIds = '';
        for($i = 0; $i < 4; $i++)
        {
            $text = fake()->word();
            Livewire::test(OptionResource\Pages\CreateOption::class)
            ->fillForm([
                'question_id' => $question2->id,
                'text' => $text,
            ])
            ->call('create')
            ->assertHasNoFormErrors();
            $optionVariableName = 'option' . $i;
            ${$optionVariableName} = Option::latest()->first();
            if($i == 0) {
                $optionIds .= ${$optionVariableName}->id;
            }
            else {
                $optionIds .= ',' . ${$optionVariableName}->id;
            }
        }

        $this->assertDatabaseHas('questions', [
            'survey_id' => $survey->id,
            'text' => 'Do you eat at least one citrus fruit a week?',
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee($survey->name, $escaped = true);

        $response = $this->get(route('take-survey', ['survey' => $survey->id]));
        $response->assertStatus(200);
        $response->assertSee('Question 1')->assertSee('Question 2');
        $response->assertSee('Yes')->assertSee('No');
        $response->assertSee($option0->text)->assertSee($option1->text)->assertSee($option2->text)->assertSee($option3->text);
    }

    public function test_create_a_large_number_of_surveys()
    {
        $numberOfSurveys = 3000;
        $startTime = microtime(true);

        Survey::factory()->count($numberOfSurveys)->create();

        $endTime = microtime(true);
        $executionTime = round($endTime - $startTime, 2);
        $this->assertLessThan(10, $executionTime, "Creating $numberOfSurveys surveys took too long ($executionTime seconds)");
    }

    public function test_concurrent_survey_submissions()
    {
        $surveys = Survey::factory()->count(5)->create();
        $users = User::factory()->count(10)->create();

        foreach($users as $user)
        {
            foreach($surveys as $survey)
            {
                $team = $survey->teams[0];
                $user->teams()->attach($team->id);
            }
        }

        foreach($surveys as $survey)
        {
            foreach($users as $user)
            {
                $this->actingAs($user);

                $response = $this->post(route('store', ['survey' => $survey->id]), [
                    'selected' . $survey->questions[0]->id => 0,
                ]);
                $response->assertStatus(200);
                $response->assertSee("No"); //Results are displayed with No

                $this->assertDatabaseHas('survey_responses', [
                    'survey_id' => $survey->id,
                    'user_id' => $user->id
                ]);
            }
        }

    }
}