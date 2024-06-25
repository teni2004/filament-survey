<?php

namespace App\Filament\Resources\SurveyResponseResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Resources\SurveyResponseResource\Pages\ListSurveyResponses;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use App\Models\Survey;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string {
        return ListSurveyResponses::class;
    }

    protected function getStats(): array
    {
        // $responses = $this->getPageTableQuery()->get();
        // $surveys = [];
        // $surveyIds = [];
        // foreach($responses as $response)
        // {
        //     if (!in_array($response->survey->id, $surveyIds))
        //     {
        //         $surveys[] = $response->survey;
        //         $surveyIds[] = $response->survey->id;
        //     }
        // }
        // //dd($surveys);
        // $allsurveys = Survey::query()->get();

        // $userIds = [];
        // $totalsent = 0;
        // foreach($allsurveys as $survey)
        // {
        //     foreach($survey->teams as $team)
        //     {
        //         foreach($team->users as $user)
        //         {
        //             if(!in_array($user->id, $userIds))
        //             {
        //                 $totalsent++;
        //                 $userIds[] = $user->id;
        //             }
        //         }
        //     }
        //     $userIds = [];
        // }

        // $sent = 0;
        // foreach($surveys as $survey)
        // {
        //     foreach($survey->teams as $team)
        //     {
        //         foreach($team->users as $user)
        //         {
        //             if(!in_array($user->id, $userIds))
        //             {
        //                 $sent++;
        //                 $userIds[] = $user->id;
        //             }
        //         }
        //     }
        //     $userIds = [];
        // }
        $received = $this->getPageTableQuery()->count();
        return [
            //Stat::make('Surveys sent', ($surveys < $allsurveys ? $sent : $totalsent)),
            Stat::make('Responses received', $received),
        ];
    }
}
