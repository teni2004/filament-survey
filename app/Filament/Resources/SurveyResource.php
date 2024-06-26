<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Filament\Resources\SurveyResource\RelationManagers;
use App\Models\Survey;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use App\Enums\SurveyStatusEnum;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use App\Models\Question;
use App\Enums\QuestionTypeEnum;
use Filament\Forms\Components\Fieldset;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\SelectFilter;
use App\Models\User;
use App\Models\Team;
use Filament\Actions;
use Webbingbrasil\FilamentCopyActions\Tables\Actions\CopyAction;
use Illuminate\Support\Str;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Survey';
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Survey Details')
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->live(onBlur: true)
                                ->unique(ignoreRecord:true)
                                ->columnSpanFull(),
                            Hidden::make('user_id')
                                ->default(Auth::id()) // Default to the authenticated user's ID
                                ->dehydrated(true),
                            Select::make('teams')
                                ->relationship('teams', 'name')
                                ->options(Team::query()->pluck('name', 'id'))
                                ->multiple()
                                ->required(),
                            Toggle::make('published')
                                ->default(true)
                                ->required()
                                ->inline(false)
                                ->helperText('Enable or disable survey visibility'),
                        ])->columns(2),
                    Step::make('Survey Questions')
                        ->schema([
                            Repeater::make('questions')
                                ->relationship()
                                ->schema([
                                    Grid::make(1)
                                        ->schema([
                                            TextInput::make('text')
                                            ->required()
                                            ->afterStateUpdated(function(string $operation, $state, Forms\Set $set) {
                                                if ($operation !== 'create' && $operation !== 'edit') {
                                                    return;
                                                }
                                                $set('label', Str::random(rand(4, 10)));
                                            }),
                                        ]),
                                    Grid::make(2)
                                        ->schema([  
                                            TextInput::make('label')
                                            ->required()
                                            ->unique(ignoreRecord:true)
                                            ->disabled()
                                            ->dehydrated()
                                            ->helperText('Short autogenerated label to identify the question'),
                                            Select::make('type')
                                                ->options([
                                                    'rating' => QuestionTypeEnum::RATING->value,
                                                    'yes-no' => QuestionTypeEnum::YESNO->value,
                                                    'multiple-choice' => QuestionTypeEnum::MULTIPLECHOICE->value,
                                                    'select-one' => QuestionTypeEnum::SELECTONE->value,
                                                    'free-form' => QuestionTypeEnum::FREEFORM->value
                                                ])->required()->reactive()
                                                ->afterStateUpdated(function ($state, callable $set) {
                                                    if ($state === 'rating') {
                                                        $set('required', true);
                                                    }
                                                }),
                                        ]),
                                    Grid::make(1)
                                        ->schema([  
                                            Toggle::make('required')
                                                ->disabled(fn ($get) => $get('type') === 'rating')
                                                ->dehydrated(true),
                                        ]),
                                    Repeater::make('options')
                                        ->relationship()
                                        ->schema([
                                            TextInput::make('text')
                                                ->required(),
                                        ])->hidden(fn ($get) => !in_array($get('type'), ['multiple-choice', 'select-one'])),
                                    Fieldset::make('Rating Option')
                                        ->relationship('rating_options')
                                        ->schema ([
                                            TextInput::make('min_value')
                                                ->label('Minimum Value')
                                                ->default(0)
                                                ->disabled()
                                                ->dehydrated() 
                                                ->numeric()
                                                ->required(),
                                            TextInput::make('max_value')
                                                ->label('Maximum Value')
                                                ->rules(['min:1', 'max:100'])
                                                ->gt('min_value')
                                                ->numeric()
                                                ->required(),
                                        ])->hidden(fn ($get) => $get('type') !== 'rating')
                                ])->required()
                        ])
                    
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('teams')
                    ->label('Teams')
                    ->getStateUsing(function ($record) {
                        $teams = $record->teams->pluck('name')->toArray();
                        return empty($teams) ? ['No team assigned'] : $teams;
                    })
                    ->colors([
                        'gray',
                        'danger' => fn ($state) => in_array('No team assigned', $state),
                    ])
                    ->icons([
                        'heroicon-o-exclamation-triangle' => fn ($state) => in_array('No team assigned', $state),
                    ]),
                TextColumn::make('preview_url')
                    ->label('Preview URL')
                    ->url(fn ($record) => $record->preview_url)
                    ->html(),
            ])
            ->filters([
                SelectFilter::make('id')
                    ->label('Name')
                    ->options(Survey::query()->pluck('name', 'id')),
                SelectFilter::make('user_id')->relationship('user', 'name')
                    ->label('Created By'),
                SelectFilter::make('teams')->relationship('teams', 'name')
                    ->label('Team')
                    ->options(Team::query()->pluck('name', 'id'))
            ])
            ->actions([
                CopyAction::make()
                    ->copyable(fn($record) => $record->shareable_url)
                    ->label('Copy shareable link')
                    ->successNotificationMessage('Link copied!'),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Actions\Action::make('preview')
                        ->icon('heroicon-o-clipboard')
                        ->url(fn($record): string => route('preview-survey', ['survey' => $record])),
                    Actions\Action::make('view stats')
                        ->icon('heroicon-o-chart-bar-square')
                        ->url(fn($record): string => route('stats', ['survey' => $record]))
                ])
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
            'index' => Pages\ListSurveys::route('/'),
            'create' => Pages\CreateSurvey::route('/create'),
            'edit' => Pages\EditSurvey::route('/{record}/edit'),
            'preview' => Pages\PreviewSurvey::route('/{record}/view')
        ];
    }
}