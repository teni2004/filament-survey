<?php

namespace App\Filament\Resources\SurveyResource\Pages;

use App\Filament\Resources\SurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use App\Filament\Resources\SurveyResource\Widgets\StatsOverview;
use App\Filament\Resources\SurveyResource\Widgets\SurveysChart;
use App\Models\Survey;

class ListSurveys extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = SurveyResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
            //SurveysChart::class
        ];
    }
}
