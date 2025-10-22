<?php

namespace App\Filament\Resources\ContentCreatorResource\Pages;

use App\Filament\Resources\ContentCreatorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewContentCreator extends ViewRecord
{
    protected static string $resource = ContentCreatorResource::class;

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
                Infolists\Components\Tabs::make('Content Creator Details')
                    ->tabs([
                        Infolists\Components\Tabs\Tab::make('Profile')
                            ->icon('heroicon-o-user')
                            ->schema(\App\Filament\Resources\ContentCreatorResource\Schemas\ContentCreatorInfolistSchema::getSchema()),
                        
                        Infolists\Components\Tabs\Tab::make('Courses')
                            ->icon('heroicon-o-book-open')
                            ->badge(fn ($record) => $record->total_courses)
                            ->schema([
                                Infolists\Components\Section::make('Course Statistics')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('total_courses')
                                            ->label('Total Courses')
                                            ->icon('heroicon-o-book-open'),
                                        Infolists\Components\TextEntry::make('total_students')
                                            ->label('Total Students Enrolled')
                                            ->icon('heroicon-o-users'),
                                        Infolists\Components\TextEntry::make('total_earnings')
                                            ->money('USD')
                                            ->label('Total Earnings')
                                            ->icon('heroicon-o-currency-dollar'),
                                    ])->columns(3),
                            ]),
                        
                        Infolists\Components\Tabs\Tab::make('Documents')
                            ->icon('heroicon-o-document')
                            ->badge(fn ($record) => $record->documents()->count())
                            ->schema([
                                Infolists\Components\Section::make('Verification Documents')
                                    ->schema([
                                        Infolists\Components\RepeatableEntry::make('documents')
                                            ->schema([
                                                Infolists\Components\TextEntry::make('document_type')
                                                    ->badge(),
                                                Infolists\Components\TextEntry::make('file_name'),
                                                Infolists\Components\IconEntry::make('verified')
                                                    ->boolean(),
                                                Infolists\Components\TextEntry::make('verified_at')
                                                    ->dateTime(),
                                            ])
                                            ->columns(4),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
