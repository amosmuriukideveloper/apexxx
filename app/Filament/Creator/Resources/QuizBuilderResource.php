<?php

namespace App\Filament\Creator\Resources;

use App\Filament\Creator\Resources\QuizBuilderResource\Pages;
use App\Models\CourseQuiz;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class QuizBuilderResource extends Resource
{
    protected static ?string $model = CourseQuiz::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Quizzes';
    protected static ?string $navigationGroup = 'My Content';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Quiz';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('lecture.section.course', function ($query) {
                $query->where('creator_id', Auth::id());
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Quiz Information')
                    ->schema([
                        Forms\Components\Select::make('lecture_id')
                            ->label('Lecture')
                            ->relationship(
                                'lecture',
                                'title',
                                fn ($query) => $query->whereHas('section.course', function ($q) {
                                    $q->where('creator_id', Auth::id());
                                })
                            )
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpan(2),
                        
                        Forms\Components\TextInput::make('passing_score')
                            ->label('Passing Score (%)')
                            ->numeric()
                            ->default(70)
                            ->minValue(0)
                            ->maxValue(100)
                            ->suffix('%')
                            ->required(),
                        
                        Forms\Components\TextInput::make('time_limit')
                            ->label('Time Limit (minutes)')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Leave empty for no time limit'),
                        
                        Forms\Components\TextInput::make('max_attempts')
                            ->label('Maximum Attempts')
                            ->numeric()
                            ->default(3)
                            ->minValue(1)
                            ->required(),
                        
                        Forms\Components\Toggle::make('randomize_questions')
                            ->label('Randomize Questions')
                            ->default(false)
                            ->helperText('Questions will appear in random order'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Questions')
                    ->schema([
                        Forms\Components\Repeater::make('questions')
                            ->relationship('questions')
                            ->schema([
                                Forms\Components\RichEditor::make('question')
                                    ->required()
                                    ->columnSpan(2),
                                
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'multiple_choice' => 'Multiple Choice',
                                        'true_false' => 'True/False',
                                        'short_answer' => 'Short Answer',
                                        'essay' => 'Essay',
                                    ])
                                    ->required()
                                    ->live()
                                    ->default('multiple_choice'),
                                
                                Forms\Components\TextInput::make('points')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->minValue(1),
                                
                                Forms\Components\Textarea::make('explanation')
                                    ->label('Explanation (Optional)')
                                    ->rows(2)
                                    ->helperText('Shown after answering')
                                    ->columnSpan(2),
                                
                                Forms\Components\Repeater::make('answers')
                                    ->relationship('answers')
                                    ->schema([
                                        Forms\Components\TextInput::make('answer')
                                            ->required()
                                            ->columnSpan(2),
                                        
                                        Forms\Components\Toggle::make('is_correct')
                                            ->label('Correct Answer')
                                            ->default(false),
                                    ])
                                    ->columns(3)
                                    ->visible(fn (Forms\Get $get) => in_array($get('type'), ['multiple_choice', 'true_false']))
                                    ->defaultItems(4)
                                    ->addActionLabel('Add Answer Option')
                                    ->columnSpan(2),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->addActionLabel('Add Question')
                            ->collapsible()
                            ->cloneable()
                            ->itemLabel(fn (array $state): ?string => $state['question'] ?? 'New Question')
                            ->columnSpan('full'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lecture.section.course.title')
                    ->label('Course')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('lecture.title')
                    ->label('Lecture')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('questions_count')
                    ->label('Questions')
                    ->counts('questions')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('passing_score')
                    ->label('Pass Score')
                    ->suffix('%'),
                
                Tables\Columns\TextColumn::make('time_limit')
                    ->label('Time Limit')
                    ->formatStateUsing(fn ($state) => $state ? "{$state} min" : 'No limit'),
                
                Tables\Columns\TextColumn::make('max_attempts')
                    ->label('Max Attempts'),
                
                Tables\Columns\IconColumn::make('randomize_questions')
                    ->label('Randomize')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('course')
                    ->relationship('lecture.section.course', 'title'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuizBuilders::route('/'),
            'create' => Pages\CreateQuizBuilder::route('/create'),
            'edit' => Pages\EditQuizBuilder::route('/{record}/edit'),
        ];
    }
}
