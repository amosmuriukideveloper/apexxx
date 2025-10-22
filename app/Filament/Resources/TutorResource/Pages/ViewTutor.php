<?php

namespace App\Filament\Resources\TutorResource\Pages;

use App\Filament\Resources\TutorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewTutor extends ViewRecord
{
    protected static string $resource = TutorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
            Actions\Action::make('suspend')
                ->icon('heroicon-o-no-symbol')
                ->color('warning')
                ->requiresConfirmation()
                ->visible(fn ($record) => $record->status === 'active')
                ->action(fn ($record) => $record->update(['status' => 'suspended'])),
            Actions\Action::make('activate')
                ->icon('heroicon-o-check')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn ($record) => $record->status === 'suspended')
                ->action(fn ($record) => $record->update(['status' => 'active'])),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Tabs::make('Tutor Details')
                    ->tabs([
                        Infolists\Components\Tabs\Tab::make('Profile')
                            ->icon('heroicon-o-user')
                            ->schema(\App\Filament\Resources\TutorResource\Schemas\TutorInfolistSchema::getSchema()),
                        
                        Infolists\Components\Tabs\Tab::make('Sessions')
                            ->icon('heroicon-o-video-camera')
                            ->badge(fn ($record) => $record->total_sessions_completed)
                            ->schema([
                                Infolists\Components\Section::make('Tutoring Sessions')
                                    ->description('All tutoring sessions for this tutor')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('total_sessions_completed')
                                            ->label('Total Sessions')
                                            ->icon('heroicon-o-check-badge'),
                                        Infolists\Components\TextEntry::make('rating')
                                            ->label('Average Rating')
                                            ->icon('heroicon-o-star')
                                            ->suffix('/5.0'),
                                        Infolists\Components\TextEntry::make('total_earnings')
                                            ->label('Total Earnings')
                                            ->money('USD')
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
                        
                        Infolists\Components\Tabs\Tab::make('Performance')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                Infolists\Components\Section::make('Performance Stats')
                                    ->schema([
                                        Infolists\Components\Grid::make(3)
                                            ->schema([
                                                Infolists\Components\TextEntry::make('rating')
                                                    ->icon('heroicon-o-star')
                                                    ->color('warning')
                                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large),
                                                Infolists\Components\TextEntry::make('total_sessions_completed')
                                                    ->label('Sessions')
                                                    ->icon('heroicon-o-check-badge')
                                                    ->color('info')
                                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large),
                                                Infolists\Components\TextEntry::make('total_earnings')
                                                    ->money('USD')
                                                    ->icon('heroicon-o-currency-dollar')
                                                    ->color('success')
                                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large),
                                            ]),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
