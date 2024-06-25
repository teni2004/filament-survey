<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResponseResource\Pages;
use App\Filament\Resources\SurveyResponseResource\RelationManagers;
use App\Models\SurveyResponse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Hidden;
use App\Models\Survey;

class SurveyResponseResource extends Resource
{
    protected static ?string $model = SurveyResponse::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Survey';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make()
                    ->relationship('survey')
                    ->schema([
                        TextInput::make('name')
                            ->label('Survey Name')
                            ->columnSpanFull()
                    ]),
                Repeater::make('Answers')->columnSpanFull()
                    ->relationship('answers')
                    ->schema([
                        Fieldset::make('Question')
                            ->relationship('question')
                                ->schema([
                                    TextInput::make('text'),
                                ]),
                        Hidden::make('type'),
                        Fieldset::make('Answer')
                            ->relationship('rating_answer')
                                ->schema([
                                    TextInput::make('rating'),
                                ])->hidden(fn ($get) => $get('type') !== 'rating'),
                        Fieldset::make('Answer')
                            ->relationship('free_form_answer')
                                ->schema([
                                    TextInput::make('body'),
                                ])->hidden(fn ($get) => $get('type') !== 'free-form'),
                        Fieldset::make('Answer')
                            ->relationship('yes_no_answer')
                                ->schema([
                                    TextInput::make('choice')
                                ])->hidden(fn ($get) => $get('type') !== 'yes-no'),
                        Fieldset::make('Answer')
                            ->relationship('select_one_answer')
                            ->schema([
                                    Fieldset::make()
                                    ->relationship('option')
                                    ->schema([
                                        TextInput::make('text'),
                                    ])
                            ])->hidden(fn ($get) => $get('type') !== 'select-one'),

                        Repeater::make('Answers')
                            ->relationship('multiple_choice_answers')
                            ->schema([
                                Fieldset::make()
                                ->relationship('option')
                                    ->schema([
                                        TextInput::make('text'),
                                    ])
                            ])->hidden(fn ($get) => $get('type') !== 'multiple-choice'),
                    ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('survey_id')
                    ->label('Survey Id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('survey.name')
                    ->label('Survey Name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('user_id')
                    ->label('User Id')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('survey_id')->relationship('survey', 'name')
                    ->label('Responses to'),
                SelectFilter::make('team_id')->relationship('survey.teams', 'name')
                    ->label('Team')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveyResponses::route('/'),
            'create' => Pages\CreateSurveyResponse::route('/create'),
            'edit' => Pages\EditSurveyResponse::route('/{record}/edit'),
        ];
    }
}
