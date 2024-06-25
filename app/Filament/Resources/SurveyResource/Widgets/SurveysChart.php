<?php

namespace App\Filament\Resources\SurveyResource\Widgets;

use Filament\Widgets\ChartWidget;
use App\Filament\Resources\SurveyResource\Pages\ListSurveys;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class SurveysChart extends ChartWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string {
        return ListSurveys::class;
    }

    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
