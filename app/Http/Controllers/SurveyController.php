<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\Answer;
use App\Models\RatingAnswer;
use App\Models\YesNoAnswer;
use App\Models\MultipleChoiceAnswer;
use App\Models\SelectOneAnswer;
use App\Models\FreeFormAnswer;
use App\Models\SurveyResponse;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
    public function view(Survey $survey) {
        $responses = $survey->survey_responses->where('user_id', Auth::user()->id);
        return view('results', ['responses' => $responses]);
    }

    public function store(Survey $survey) {
        foreach($survey->survey_responses as $response)
        {
            if($response->user == Auth::user())
            {
                return view('error', ['name' => 'alreadydone', 'survey' => $survey]);
            }
        }

        $responses = [];
        $response = SurveyResponse::create([
            'survey_id' => $survey->id,
            'user_id' => Auth::user()->id
        ]);
        foreach($survey->questions as $question) {
            //For now I think I will only do client side validation
            switch($question->type) {
                case 'rating':
                    $rating = request($question->label);
                    $answer = Answer::create([
                        'survey_response_id' => $response->id,
                        'question_id' => $question->id,
                        'type' => 'rating',
                    ]);
                    RatingAnswer::create([
                        'answer_id' => $answer->id,
                        'rating' => $rating,
                    ]);
                    break;
                case 'yes-no':
                    $choice = request('selected' . $question->id);
                    if ($choice !== null)
                    {
                        $answer = Answer::create([
                            'survey_response_id' => $response->id,
                            'question_id' => $question->id,
                            'type' => 'yes-no',
                        ]);
                        YesNoAnswer::create([
                            'answer_id' => $answer->id,
                            'choice' => $choice,
                        ]); 
                    }
                    break;
                case 'multiple-choice': //test this
                    $choices = request('selected' . $question->id);
                    if ($choices)
                    {
                        $answer = Answer::create([
                            'survey_response_id' => $response->id,
                            'question_id' => $question->id,
                            'type' => 'multiple-choice',
                        ]);
                        $choices = explode(",", $choices);
                        foreach($choices as $choice) {
                            MultipleChoiceAnswer::create([
                                'answer_id' => $answer->id,
                                'option_id' => $choice,
                            ]);
                        }
                    }
                    break;
                case 'select-one':
                    $choice = request($question->label);
                    if ($choice)
                    {
                        $answer = Answer::create([
                            'survey_response_id' => $response->id,
                            'question_id' => $question->id,
                            'type' => 'select-one',
                        ]);
                        SelectOneAnswer::create([
                            'answer_id' => $answer->id,
                            'option_id' => $choice,
                        ]);
                    }
                    break;
                case 'free-form':
                    $body = request($question->label);
                    if ($body)
                    {
                        $answer = Answer::create([
                            'survey_response_id' => $response->id,
                            'question_id' => $question->id,
                            'type' => 'free-form',
                        ]);
                        FreeFormAnswer::create([
                            'answer_id' => $answer->id,
                            'body' => $body,
                        ]);
                    }
                    break;
            }
        }
        $responses[] = $response;
        return view('results', ['responses' => $responses]);
    }

    public function update(Survey $survey) {
        $response = [];
        foreach($survey->survey_responses as $sresponse)
        {
            if($sresponse->user == Auth::user())
            {
                $response = $sresponse;
            }
        }

        foreach($response->answers as $answer) {
            switch($answer->type) {
                case 'rating':
                    $rating = request($answer->question->label);
                    $answer->rating_answer->update([
                        'rating' => $rating,
                    ]);
                    break;
                case 'yes-no':
                    $choice = request('selected' . $answer->question->id);
                    if ($choice !== null)
                    {
                        $answer->yes_no_answer->update([
                            'choice' => $choice,
                        ]); 
                    }
                    break;
                case 'multiple-choice':
                    $allchoices = request('selected' . $answer->question->id);
                    if ($allchoices)
                    {
                        $choices = explode(",", $allchoices);
                        
                        foreach($answer->multiple_choice_answers as $mca)
                        {
                            if(str_contains($allchoices, (string)$mca->option->id))
                            {
                                unset($choices[array_search($mca->option->id, $choices)]);
                                break;
                            }
                            $mca->delete();
                        }
                        
                        foreach($choices as $choice) {
                            MultipleChoiceAnswer::create([
                                'answer_id' => $answer->id,
                                'option_id' => $choice,
                            ]);
                        }
                    }
                    //Trying to make this work...
                    cache()->forget('answer_' . $answer->id);
                    $answer = $answer;
                    // $answer->multiple_choice_answers = $answer->multiple_choice_answers->fresh(); 
                    break; //the changes i make here are not reflecting...
                case 'select-one':
                    $choice = request($answer->question->label);
                    if ($choice)
                    {
                        $answer->select_one_answer->update([
                            'option_id' => $choice,
                        ]);
                    }
                    break;
                case 'free-form':
                    $body = request($answer->question->label);
                    if ($body)
                    {
                        $answer->free_form_answer->update([
                            'body' => $body,
                        ]);
                    }
                    break;
            }
        }
        $response = SurveyResponse::where('survey_id', $survey->id)->where('user_id', Auth::user()->id)->get();
        $responses[] = $response;
        
        return view('results', ['responses' => $response]);
    }
}
