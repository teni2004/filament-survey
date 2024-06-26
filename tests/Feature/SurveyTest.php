<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories;
use App\Models\User;
use App\Models\Team;
use App\Models\Question;
use App\Models\Survey;
use Livewire\livewire;
use App\Filament\Resources\SurveyResource;
use App\Filament\Resources\QuestionResource;

class SurveyTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_create_a_survey(): void
    {
        $user = $this->getUser();
        $team = Team::factory()->create();

        //When they hit the endpoint /admin/surveys/create to create a new survey while passing the necessary data
        Livewire::test(SurveyResource\Pages\CreateSurvey::class)
        ->set('data.questions', null)
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
        //Given that a user is logged in and a survey exists that they created
        $user = $this->getUser();
        $survey = Survey::factory()->create();
        // $survey->update([ //this is currently unecessary because as of now any user can edit any survey
        //     'user_id' => $user->id
        // ]);

        //When the user visits the edit page and makes a change
        Livewire::test(SurveyResource\Pages\EditSurvey::class, [
            'record' => $survey->getRouteKey(),
        ])
        ->set('data.questions', null)
        ->fillForm([
            'name' => 'Seasickness Awareness Survey',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

        //That change should be reflected in the database
        $this->assertDatabaseHas('surveys', [
            'name' => 'Seasickness Awareness Survey', 
            'published' => 1
        ]);
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
            'selected' . $question->id => 0, //User selects no
            // Include any necessary form data here - maybe look into what this would include? it looks tuff but is probably worth trying for like an hour
        ]);
        $response->assertStatus(200);
        $response->assertSee("No"); //Results are displayed with No
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

    // public function test_a_user_can_create_an_option()
    // {

    // }
}