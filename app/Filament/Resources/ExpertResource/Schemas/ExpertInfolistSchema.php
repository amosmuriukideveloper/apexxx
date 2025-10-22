<?php

namespace App\Filament\Resources\ExpertResource\Schemas;

use Filament\Infolists;

class ExpertInfolistSchema
{
    public static function getSchema(): array
    {
        return [
            Infolists\Components\Section::make('Expert Profile')
                ->schema([
                    Infolists\Components\ImageEntry::make('profile_photo')
                        ->circular()
                        ->size(120),
                    Infolists\Components\TextEntry::make('name')
                        ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                        ->weight('bold'),
                    Infolists\Components\TextEntry::make('email')
                        ->copyable()
                        ->icon('heroicon-o-envelope'),
                    Infolists\Components\TextEntry::make('phone')
                        ->icon('heroicon-o-phone'),
                    Infolists\Components\TextEntry::make('specialization')
                        ->badge()
                        ->color('primary'),
                ])->columns(2),
            
            Infolists\Components\Section::make('Expertise & Experience')
                ->schema([
                    Infolists\Components\TextEntry::make('expertise_areas')
                        ->badge()
                        ->separator(',')
                        ->columnSpanFull(),
                    Infolists\Components\TextEntry::make('years_of_experience')
                        ->suffix(' years')
                        ->icon('heroicon-o-briefcase'),
                    Infolists\Components\TextEntry::make('bio')
                        ->columnSpanFull()
                        ->prose(),
                ])->columns(2),
            
            Infolists\Components\Section::make('Status & Verification')
                ->schema([
                    Infolists\Components\TextEntry::make('application_status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'pending' => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                        }),
                    Infolists\Components\TextEntry::make('status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'active' => 'success',
                            'suspended' => 'danger',
                        }),
                    Infolists\Components\IconEntry::make('documents_verified')
                        ->boolean()
                        ->label('Documents Verified'),
                    Infolists\Components\IconEntry::make('available')
                        ->boolean()
                        ->label('Available for Projects'),
                    Infolists\Components\TextEntry::make('approved_at')
                        ->dateTime()
                        ->visible(fn ($record) => $record->approved_at),
                    Infolists\Components\TextEntry::make('approver.name')
                        ->visible(fn ($record) => $record->approved_by),
                    Infolists\Components\TextEntry::make('rejection_reason')
                        ->visible(fn ($record) => $record->application_status === 'rejected')
                        ->columnSpanFull()
                        ->color('danger'),
                ])->columns(3),
            
            Infolists\Components\Section::make('Performance Metrics')
                ->schema([
                    Infolists\Components\TextEntry::make('rating')
                        ->icon('heroicon-o-star')
                        ->color('warning')
                        ->suffix('/5.0'),
                    Infolists\Components\TextEntry::make('total_projects_completed')
                        ->icon('heroicon-o-document-check')
                        ->color('success'),
                    Infolists\Components\TextEntry::make('total_earnings')
                        ->money('USD')
                        ->icon('heroicon-o-currency-dollar')
                        ->color('success'),
                ])->columns(3),
        ];
    }
}
