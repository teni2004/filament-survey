<?php

namespace App\Filament\Resources\SurveyResource\Pages;

use App\Filament\Resources\SurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSurvey extends EditRecord
{
    protected static string $resource = SurveyResource::class;

    protected function getHeaderActions(): array
    {

        return [
            Actions\DeleteAction::make(),
            Actions\Action::make('preview')
            ->url(fn(): string => route('preview-survey', ['survey' => $this->record])),
            Actions\Action::make('view stats')
            ->url(fn(): string => route('stats', ['survey' => $this->record]))
        ];
    }
}
