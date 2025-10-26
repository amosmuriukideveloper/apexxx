<?php

namespace App\Filament\Resources\RelationManagers;

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
                    ->searchable()
                    ->preload(),
                
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(2),
                
                Forms\Components\Select::make('type')
                    ->options([
                        'video' => 'Video',
                        'article' => 'Article',
                        'quiz' => 'Quiz',
                        'assignment' => 'Assignment',
                    ])
                    ->required()
                    ->reactive(),
                
                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->columnSpan(2),
                
                Forms\Components\FileUpload::make('video_path')
                    ->label('Video File')
                    ->disk('public')
                    ->directory('lecture-videos')
                    ->acceptedFileTypes(['video/mp4', 'video/webm'])
                    ->maxSize(512000) // 500MB
                    ->visible(fn (Forms\Get $get) => $get('type') === 'video')
                    ->columnSpan(2),
                
                Forms\Components\RichEditor::make('content')
                    ->label('Article Content')
                    ->visible(fn (Forms\Get $get) => $get('type') === 'article')
                    ->columnSpan(2),
                
                Forms\Components\TextInput::make('video_duration')
                    ->label('Duration (seconds)')
                    ->numeric()
                    ->visible(fn (Forms\Get $get) => $get('type') === 'video'),
                
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Order within section'),
                
                Forms\Components\Toggle::make('is_preview')
                    ->label('Free Preview')
                    ->helperText('Allow non-enrolled students to view this')
                    ->columnSpan(2),
                
                Forms\Components\FileUpload::make('attachments')
                    ->label('Attachments')
                    ->multiple()
                    ->disk('public')
                    ->directory('lecture-attachments')
                    ->maxFiles(5)
                    ->columnSpan(2),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('section.title')
                    ->label('Section')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('type')
                    ->colors([
                        'primary' => 'video',
                        'success' => 'article',
                        'warning' => 'quiz',
                        'info' => 'assignment',
                    ]),
                
                Tables\Columns\IconColumn::make('is_preview')
                    ->label('Preview')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('video_duration')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => $state ? gmdate('i:s', $state) : '-'),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'video' => 'Video',
                        'article' => 'Article',
                        'quiz' => 'Quiz',
                        'assignment' => 'Assignment',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_preview')
                    ->label('Free Preview'),
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
