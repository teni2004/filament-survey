<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use App\Enums\QuestionTypeEnum;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';
    //protected static ?string $navigationGroup = 'Survey';
    //protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                Select::make('survey_id')
                                    ->relationship('survey', 'name')
                                    ->searchable()
                                    ->required(),
                                TextInput::make('text')
                                    ->required(),
                                TextInput::make('label')
                                    ->required(),
                                Select::make('type')
                                    ->options([
                                        'rating' => QuestionTypeEnum::RATING->value,
                                        'yes-no' => QuestionTypeEnum::YESNO->value,
                                        'multiple-choice' => QuestionTypeEnum::MULTIPLECHOICE->value,
                                        'select-one' => QuestionTypeEnum::SELECTONE->value,
                                        'free-form' => QuestionTypeEnum::FREEFORM->value
                                    ])->required()
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('survey.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('text')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
