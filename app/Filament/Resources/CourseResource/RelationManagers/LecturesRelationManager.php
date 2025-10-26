<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class LecturesRelationManager extends RelationManager
{
    protected static string $relationship = 'lectures';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $title = 'Lectures';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Lecture Information')
                    ->schema([
                        Forms\Components\Select::make('section_id')
                            ->label('Section')
                            ->relationship('section', 'title', fn ($query) => $query->where('course_id', $this->getOwnerRecord()->id))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('title')
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->rows(2),
                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->columnSpan(2),
                        
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpan(2),
                        
                        Forms\Components\Select::make('type')
                            ->options([
                                'video' => 'Video',
                                'article' => 'Article',
                                'quiz' => 'Quiz',
                                'assignment' => 'Assignment',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('type', $state)),
                        
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Order')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Video Content')
                    ->schema([
                        Forms\Components\FileUpload::make('video_path')
                            ->label('Video File')
                            ->disk('public')
                            ->directory('course-videos')
                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg'])
                            ->maxSize(512000) // 500MB
                            ->columnSpan(2),
                        
                        Forms\Components\TextInput::make('video_duration')
                            ->label('Duration (seconds)')
                            ->numeric()
                            ->suffix('s'),
                        
                        Forms\Components\Toggle::make('is_preview')
                            ->label('Free Preview')
                            ->helperText('Allow non-enrolled users to watch this lecture'),
                    ])
                    ->visible(fn (Forms\Get $get) => $get('type') === 'video')
                    ->columns(2),
                
                Forms\Components\Section::make('Article Content')
                    ->schema([
                        Forms\Components\RichEditor::make('content')
                            ->label('Article Content')
                            ->columnSpan(2)
                            ->fileAttachmentsDirectory('lecture-attachments'),
                    ])
                    ->visible(fn (Forms\Get $get) => $get('type') === 'article')
                    ->columns(2),
                
                Forms\Components\Section::make('Attachments')
                    ->schema([
                        Forms\Components\FileUpload::make('attachments')
                            ->label('Resource Files')
                            ->disk('public')
                            ->directory('lecture-attachments')
                            ->multiple()
                            ->reorderable()
                            ->maxFiles(10)
                            ->columnSpan(2),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                $this->getOwnerRecord()
                    ->sections()
                    ->with('lectures')
                    ->get()
                    ->flatMap(fn ($section) => $section->lectures)
                    ->toQuery()
            )
            ->columns([
                Tables\Columns\TextColumn::make('section.title')
                    ->label('Section')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => substr($record->description, 0, 50)),
                
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'video',
                        'success' => 'article',
                        'warning' => 'quiz',
                        'info' => 'assignment',
                    ]),
                
                Tables\Columns\TextColumn::make('video_duration')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => gmdate('i:s', $state))
                    ->visible(fn ($record) => $record->type === 'video'),
                
                Tables\Columns\IconColumn::make('is_preview')
                    ->label('Free Preview')
                    ->boolean()
                    ->visible(fn ($record) => $record->type === 'video'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'video' => 'Video',
                        'article' => 'Article',
                        'quiz' => 'Quiz',
                        'assignment' => 'Assignment',
                    ]),
                
                Tables\Filters\SelectFilter::make('section')
                    ->relationship('section', 'title'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
