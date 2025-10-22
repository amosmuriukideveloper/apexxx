<?php

namespace App\Filament\Resources\ProjectResource\Schemas;

use Filament\Infolists;

class ProjectInfolistSchema
{
    public static function getSchema(): array
    {
        return [
            Infolists\Components\Section::make('Project Information')
                ->schema([
                    Infolists\Components\TextEntry::make('project_number')
                        ->badge()
                        ->color('primary'),
                    Infolists\Components\TextEntry::make('status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'awaiting_assignment' => 'warning',
                            'assigned' => 'info',
                            'in_progress' => 'primary',
                            'under_review' => 'secondary',
                            'revision_required' => 'danger',
                            'completed' => 'success',
                            'cancelled' => 'gray',
                        }),
                    Infolists\Components\TextEntry::make('title')
                        ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                        ->columnSpanFull(),
                    Infolists\Components\TextEntry::make('description')
                        ->columnSpanFull()
                        ->prose(),
                ])->columns(2),
            
            Infolists\Components\Section::make('Project Details')
                ->schema([
                    Infolists\Components\TextEntry::make('project_type')
                        ->badge(),
                    Infolists\Components\TextEntry::make('complexity_level')
                        ->badge(),
                    Infolists\Components\TextEntry::make('subject_area'),
                    Infolists\Components\TextEntry::make('word_count')
                        ->suffix(' words'),
                    Infolists\Components\TextEntry::make('page_count')
                        ->suffix(' pages'),
                    Infolists\Components\TextEntry::make('deadline')
                        ->dateTime()
                        ->color(fn ($record) => $record->deadline < now() ? 'danger' : 'success'),
                ])->columns(3),
            
            Infolists\Components\Section::make('Participants')
                ->schema([
                    Infolists\Components\TextEntry::make('student.name')
                        ->label('Student')
                        ->icon('heroicon-o-user'),
                    Infolists\Components\TextEntry::make('expert.name')
                        ->label('Assigned Expert')
                        ->icon('heroicon-o-academic-cap')
                        ->default('Not assigned yet'),
                    Infolists\Components\TextEntry::make('admin.name')
                        ->label('Admin')
                        ->icon('heroicon-o-shield-check')
                        ->visible(fn ($record) => $record->admin_id),
                ])->columns(3),
            
            Infolists\Components\Section::make('Financial Information')
                ->schema([
                    Infolists\Components\TextEntry::make('cost')
                        ->money('USD')
                        ->icon('heroicon-o-currency-dollar'),
                    Infolists\Components\TextEntry::make('platform_commission')
                        ->money('USD')
                        ->color('warning'),
                    Infolists\Components\TextEntry::make('expert_earnings')
                        ->money('USD')
                        ->color('success'),
                    Infolists\Components\TextEntry::make('payment_status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'pending' => 'warning',
                            'paid' => 'success',
                            'refunded' => 'danger',
                        }),
                ])->columns(4),
            
            Infolists\Components\Section::make('Quality Metrics')
                ->schema([
                    Infolists\Components\TextEntry::make('turnitin_score')
                        ->suffix('%')
                        ->color(fn ($state) => $state > 20 ? 'danger' : 'success')
                        ->visible(fn ($record) => $record->turnitin_score !== null),
                    Infolists\Components\TextEntry::make('ai_detection_score')
                        ->suffix('%')
                        ->color(fn ($state) => $state > 15 ? 'danger' : 'success')
                        ->visible(fn ($record) => $record->ai_detection_score !== null),
                    Infolists\Components\TextEntry::make('quality_score')
                        ->suffix('/100')
                        ->visible(fn ($record) => $record->quality_score !== null),
                    Infolists\Components\TextEntry::make('student_rating')
                        ->icon('heroicon-o-star')
                        ->suffix('/5')
                        ->color('warning')
                        ->visible(fn ($record) => $record->student_rating !== null),
                ])->columns(4)
                ->visible(fn ($record) => $record->turnitin_score || $record->quality_score || $record->student_rating),
            
            Infolists\Components\Section::make('Timestamps')
                ->schema([
                    Infolists\Components\TextEntry::make('created_at')
                        ->dateTime()
                        ->label('Created'),
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
                ])->columns(5)
                ->collapsible(),
        ];
    }
}
