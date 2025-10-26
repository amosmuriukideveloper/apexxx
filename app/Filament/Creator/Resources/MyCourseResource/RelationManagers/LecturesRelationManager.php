<?php

namespace App\Filament\Creator\Resources\MyCourseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class LecturesRelationManager extends RelationManager
{
    protected static string $relationship = 'lectures';
    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('section_id')
                    ->label('Section')
                    ->relationship('section', 'title')
                    ->required()
                    ->searchable(),
                
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\Textarea::make('description')
                    ->rows(3),
                
                Forms\Components\Select::make('type')
                    ->options([
                        'video' => 'Video',
                        'article' => 'Article',
                        'quiz' => 'Quiz',
                        'assignment' => 'Assignment',
                    ])
                    ->required()
                    ->live(),
                
                Forms\Components\FileUpload::make('video_path')
                    ->label('Video File')
                    ->disk('public')
                    ->directory('course-videos')
                    ->acceptedFileTypes(['video/mp4', 'video/webm'])
                    ->visible(fn (Forms\Get $get) => $get('type') === 'video'),
                
                Forms\Components\RichEditor::make('content')
                    ->label('Article Content')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'article'),
                
                Forms\Components\TextInput::make('video_duration')
                    ->label('Duration (seconds)')
                    ->numeric()
                    ->visible(fn (Forms\Get $get) => $get('type') === 'video'),
                
                Forms\Components\Toggle::make('is_preview')
                    ->label('Free Preview')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'video'),
                
                Forms\Components\TextInput::make('sort_order')
                    ->label('Order')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('section.title')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->weight('bold'),
                
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'video',
                        'success' => 'article',
                        'warning' => 'quiz',
                        'info' => 'assignment',
                    ]),
                
                Tables\Columns\IconColumn::make('is_preview')
                    ->boolean()
                    ->label('Preview'),
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
