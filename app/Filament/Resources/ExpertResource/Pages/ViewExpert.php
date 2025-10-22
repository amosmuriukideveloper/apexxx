<?php

namespace App\Filament\Resources\ExpertResource\Pages;

use App\Filament\Resources\ExpertResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewExpert extends ViewRecord
{
    protected static string $resource = ExpertResource::class;

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
                Infolists\Components\Tabs::make('Expert Details')
                    ->tabs([
                        Infolists\Components\Tabs\Tab::make('Profile')
                            ->icon('heroicon-o-user')
                            ->schema(\App\Filament\Resources\ExpertResource\Schemas\ExpertInfolistSchema::getSchema()),
                        
                        Infolists\Components\Tabs\Tab::make('Projects')
                            ->icon('heroicon-o-briefcase')
                            ->badge(fn ($record) => $record->projects()->count())
                            ->schema([
                                Infolists\Components\Section::make('Assigned Projects')
                                    ->schema([
                                        Infolists\Components\RepeatableEntry::make('projects')
                                            ->schema([
                                                Infolists\Components\TextEntry::make('project_number')
                                                    ->label('Project #'),
                                                Infolists\Components\TextEntry::make('title'),
                                                Infolists\Components\TextEntry::make('status')
                                                    ->badge(),
                                                Infolists\Components\TextEntry::make('deadline')
                                                    ->dateTime(),
                                                Infolists\Components\TextEntry::make('expert_earnings')
                                                    ->money('USD'),
                                            ])
                                            ->columns(5),
                                    ]),
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
                                                Infolists\Components\TextEntry::make('total_projects_completed')
                                                    ->icon('heroicon-o-check-badge')
                                                    ->color('success')
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
