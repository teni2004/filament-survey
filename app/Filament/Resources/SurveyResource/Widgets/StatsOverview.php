<?php

namespace App\Filament\Resources\SurveyResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Resources\SurveyResource\Pages\ListSurveys;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string {
        return ListSurveys::class;
    }

    function formatNumber($number) {
        return strpos($number, '.') === false || intval(substr($number, strpos($number, '.') + 1)) === 0
            ? number_format($number, 0)
            : number_format($number, 2);
    }

    protected function getStats(): array
    {
        $surveys = $this->getPageTableQuery()->get();
        $userIds = [];
        $totalsent = 0;
        foreach($surveys as $survey)
        {
            foreach($survey->teams as $team)
            {
                foreach($team->users as $user)
                {
                    if(!in_array($user->id, $userIds))
                    {
                        $totalsent++;
                        $userIds[] = $user->id;
                    }
                }
            }
            $userIds = [];
        }

        $totalresponses = 0;
        foreach($surveys as $survey)
        {
            $totalresponses += count($survey->survey_responses);
        }

        $rate = ($totalresponses > 0 ? $this->formatNumber($totalresponses/$totalsent*100) : 0) . '%';

        return [
            Stat::make('Total sent', $totalsent),
            Stat::make('Total responses', $totalresponses),
            Stat::make('Response rate', $rate),
        ];
    }
}
