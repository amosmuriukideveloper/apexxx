<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Auth;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Tabs::make('Project Details')
                    ->tabs([
                        Infolists\Components\Tabs\Tab::make('Overview')
                            ->icon('heroicon-o-information-circle')
                            ->schema(\App\Filament\Resources\ProjectResource\Schemas\ProjectInfolistSchema::getSchema()),
                        
                        Infolists\Components\Tabs\Tab::make('Materials')
                            ->icon('heroicon-o-document')
                            ->badge(fn ($record) => $record->materials()->count())
                            ->schema([
                                Infolists\Components\Section::make('Project Materials')
                                    ->description('Files uploaded by the student')
                                    ->schema([
                                        Infolists\Components\RepeatableEntry::make('materials')
                                            ->schema([
                                                Infolists\Components\TextEntry::make('file_name'),
                                                Infolists\Components\TextEntry::make('file_type')
                                                    ->badge(),
                                                Infolists\Components\TextEntry::make('file_size')
                                                    ->formatStateUsing(fn ($state) => number_format($state / 1024, 2) . ' KB'),
                                                Infolists\Components\TextEntry::make('uploaded_by.name')
                                                    ->label('Uploaded By'),
                                                Infolists\Components\TextEntry::make('created_at')
                                                    ->dateTime(),
                                            ])
                                            ->columns(5),
                                    ]),
                            ]),
                        
                        Infolists\Components\Tabs\Tab::make('Submissions')
                            ->icon('heroicon-o-paper-airplane')
                            ->badge(fn ($record) => $record->submissions()->count())
                            ->schema([
                                Infolists\Components\Section::make('Project Submissions')
                                    ->description('All submissions by the expert')
                                    ->schema([
                                        Infolists\Components\RepeatableEntry::make('submissions')
                                            ->schema([
                                                Infolists\Components\TextEntry::make('submission_type')
                                                    ->badge(),
                                                Infolists\Components\TextEntry::make('version_number')
                                                    ->label('Version'),
                                                Infolists\Components\TextEntry::make('turnitin_score')
                                                    ->suffix('%')
                                                    ->color(fn ($state) => $state > 20 ? 'danger' : 'success'),
                                                Infolists\Components\TextEntry::make('ai_detection_score')
                                                    ->suffix('%')
                                                    ->color(fn ($state) => $state > 15 ? 'danger' : 'success'),
                                                Infolists\Components\TextEntry::make('admin_review_status')
                                                    ->badge(),
                                                Infolists\Components\TextEntry::make('created_at')
                                                    ->dateTime(),
                                            ])
                                            ->columns(6),
                                    ]),
                            ]),
                        
                        Infolists\Components\Tabs\Tab::make('History')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                Infolists\Components\Section::make('Project Timeline')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('created_at')
                                            ->label('Created')
                                            ->dateTime(),
                                        Infolists\Components\TextEntry::make('assigned_at')
                                            ->dateTime()
                                            ->visible(fn ($record) => $record->assigned_at),
                                        Infolists\Components\TextEntry::make('started_at')
                                            ->dateTime()
                                            ->visible(fn ($record) => $record->started_at),
                                        Infolists\Components\TextEntry::make('submitted_at')
                                            ->dateTime()
                                            ->visible(fn ($record) => $record->submitted_at),
                                        Infolists\Components\TextEntry::make('completed_at')
                                            ->dateTime()
                                            ->visible(fn ($record) => $record->completed_at),
                                    ])->columns(5),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
