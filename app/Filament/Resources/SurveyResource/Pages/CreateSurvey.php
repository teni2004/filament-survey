<?php

namespace App\Filament\Resources\SurveyResource\Pages;

use App\Filament\Resources\SurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSurvey extends CreateRecord
{
    protected static string $resource = SurveyResource::class;

    protected function getRedirectUrl(): string
    {
        // Redirect to a custom route or URL after creating a survey
        return $this->getResource()::getUrl('preview', ['record' => $this->record]);
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Custom logic before creating the survey

        // Create the survey
        $survey = static::getModel()::create($data);

        // Custom logic after creating the survey
        // For example, updating the survey's link to include its ID
        $survey->update([
            'preview_url' => 'http://filament-survey.test/admin/surveys/' . $survey->id . '/preview',
            'shareable_url' => 'http://filament-survey.test/admin/surveys/' . $survey->id
        ]);

        return $survey;
    }
}
