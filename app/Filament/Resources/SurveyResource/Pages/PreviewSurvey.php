<?php

namespace App\Filament\Resources\SurveyResource\Pages;

use App\Filament\Resources\SurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class PreviewSurvey extends ViewRecord
{
    protected static string $resource = SurveyResource::class;
    protected static string $view = 'filament.resources.survey-resource.pages.preview-survey';
}
