<?php

use Illuminate\Support\Facades\Route;
use App\Models\Survey;
use App\Http\Controllers\SurveyController;
use Filament\Http\Middleware\Authenticate;
use App\Models\SurveyResponse;
use App\Http\Middleware\RedirectIfAuthenticatedToAdmin;

Route::middleware(RedirectIfAuthenticatedToAdmin::class)->group(function () {
    Route::get('/', function () {
        $surveys = null;
        $taken_surveys = [];

        $teams = Auth::user()->teams()->get(); 
        $surveyIds = [];

        foreach ($teams as $team) {
            foreach ($team->surveys as $survey) {
                if ($survey->published && !in_array($survey->id, $surveyIds)) {
                    $surveys[] = $survey;
                    $surveyIds[] = $survey->id;
                }
            }
        }
        
        if ($surveys !== null && !empty($surveys))
        {
            $takenIds = [];
            $responses = Auth::user()->responses()->get();
            foreach($responses as $response) {
                foreach($surveys as $survey) 
                {
                    if ($survey->id === $response->survey->id && !in_array($survey->id, $takenIds))
                    {
                        $taken_surveys[] = $survey;
                        $takenIds[] = $survey->id;
                    }
                }
            }
        }

        return view('index', ['surveys' => $surveys, 'taken' => $taken_surveys]);
    });


    Route::get('/admin/surveys/{survey}/preview', function (Survey $survey) {
        return view('preview', ['survey' => $survey]);
    })->name('preview-survey');

    Route::post('/admin/surveys/{survey}/preview-results', function (Survey $survey) {
        return view('preview-results', ['survey' => $survey]);
    });

    Route::get('/admin/surveys/{survey}', function (Survey $survey) {
        return view('survey', ['survey' => $survey]);
    })->name('take-survey');

    Route::get('/admin/surveys/{survey}/edit-response', function (Survey $survey) {
        $answers= SurveyResponse::where('survey_id', $survey->id)->where('user_id', Auth::user()->id)->get()[0]->answers[0];
        //dd($answers);
        return view('edit', ['survey' => $survey, 'answers' => $answers]);
    });

    //Actual submission
    Route::post('/admin/surveys/{survey}/results', [SurveyController::class, 'store'])->name('store');
    Route::get('/admin/surveys/{survey}/results', [SurveyController::class, 'view']);
    Route::patch('/admin/surveys/{survey}/results', [SurveyController::class, 'update'])->name('update');

    Route::get('/admin/surveys/{survey}/stats', function (Survey $survey) {

        function formatNumber($number, $decimals) {
            return strpos($number, '.') === false || intval(substr($number, strpos($number, '.') + 1)) === 0
                ? number_format($number, 0)
                : number_format($number, $decimals);
        }

        $questions = $survey->questions;
        //dd($questions);

        $statistics = [];

        foreach($questions as $question)
        {
            switch($question->type)
            {
                case 'rating':
                    $total = 0;
                    $counter = 0;
                    foreach($survey->responses as $response)
                    {
                        foreach($response->answers as $answer)
                        {
                            if($answer->question == $question)
                            {
                                $total += $answer->rating_answer->rating;
                                $counter++;
                            }
                        }
                    }
                    $average = formatNumber($total/$counter, 1) . '/' . $question->rating_options->max_value;
                    $statistics[$question->id] = $average;
                    //dump($statistics[$question->id][0]); //why is this 4??
                    break;
                case 'yes-no':
                    $yes = 0;
                    $no = 0;
                    foreach($survey->responses as $response)
                    {
                        foreach($response->answers as $answer)
                        {
                            if($answer->question == $question)
                            {
                                ($answer->yes_no_answer->choice ? $yes++ : $no++);
                            }
                        }
                    }
                    $yespercent = formatNumber($yes / ($yes + $no) * 100, 2) . '%';
                    $nopercent = formatNumber($no / ($yes + $no) * 100, 2) . '%';
                    $statistics[$question->id] = [$yespercent, $nopercent];
                    break;
                case 'multiple-choice':
                    $options = []; //i have to initialize all the relevant options
                    $total = 0;
                    foreach($question->options as $option)
                    {
                        $options[$option->id] = [$option->text, 0];
                    }
                    foreach($survey->responses as $response)
                    {
                        foreach($response->answers as $answer)
                        {
                            if($answer->question->id == $question->id)
                            {
                                foreach($answer->multiple_choice_answers as $mca)
                                {
                                    $current = $options[$mca->option->id][1];
                                    $options[$mca->option->id][1] = $current + 1;
                                    $total++;
                                }
                            }
                        }
                    }
                    foreach($question->options as $opt)
                    {
                        $options[$opt->id][1] = ($total == 0 ? 0 . '%' : formatNumber($options[$opt->id][1]/$total * 100, 2) . '%');
                    }
                    $statistics[$question->id] = $options;
                    break;
                
                case 'select-one':
                    $options = []; //i have to initialize all the relevant options
                    $total = 0;
                    foreach($question->options as $option)
                    {
                        $options[$option->id] = [$option->text, 0];
                    }
                    foreach($survey->responses as $response)
                    {
                        foreach($response->answers as $answer)
                        {
                            if($answer->question->id == $question->id)
                            {
                                $soa = $answer->select_one_answer;
                                $current = $options[$soa->option->id][1];
                                $options[$soa->option->id][1] = $current + 1;
                                $total++;
                            }
                        }
                    }
                    foreach($question->options as $opt)
                    {
                        $options[$opt->id][1] = ($total == 0 ? 0 . '%' : formatNumber($options[$opt->id][1]/$total * 100, 2) . '%');
                    }
                    $statistics[$question->id] = $options;
                    break;
            }
        }

        return view('stats', ['survey' => $survey, 'statistics' => $statistics]);
    })->name('stats');

});