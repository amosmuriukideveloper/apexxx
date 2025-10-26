<?php

namespace App\Filament\Student\Resources\TutoringRequestResource\Pages;

use App\Filament\Student\Resources\TutoringRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewTutoringRequest extends ViewRecord
{
    protected static string $resource = TutoringRequestResource::class;
    
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Session Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('request_number')
                            ->label('Request Number'),
                        Infolists\Components\TextEntry::make('subject.name')
                            ->label('Subject')
                            ->badge(),
                        Infolists\Components\TextEntry::make('specific_topic')
                            ->label('Topic'),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucwords($state, '_'))),
                    ])
                    ->columns(2),
                
                Infolists\Components\Section::make('Schedule')
                    ->schema([
                        Infolists\Components\TextEntry::make('confirmed_date')
                            ->label('Scheduled Date & Time')
                            ->dateTime('F d, Y H:i')
                            ->placeholder('Not yet confirmed'),
                        Infolists\Components\TextEntry::make('duration')
                            ->formatStateUsing(fn ($state) => $state . ' minutes'),
                        Infolists\Components\TextEntry::make('google_meet_link')
                            ->label('Meeting Link')
                            ->url(fn ($record) => $record->google_meet_link)
                            ->openUrlInNewTab()
                            ->placeholder('Will be available after confirmation'),
                    ])
                    ->columns(2),
                
                Infolists\Components\Section::make('Learning Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('learning_goals')
                            ->label('Learning Goals')
                            ->columnSpan(2),
                        Infolists\Components\TextEntry::make('current_knowledge_level')
                            ->label('Knowledge Level')
                            ->formatStateUsing(fn ($state) => ucfirst($state)),
                        Infolists\Components\TextEntry::make('specific_help_areas')
                            ->label('Areas Needing Help')
                            ->columnSpan(2),
                    ])
                    ->columns(2),
                
                Infolists\Components\Section::make('Tutor Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('tutor.name')
                            ->label('Assigned Tutor')
                            ->placeholder('Not yet assigned'),
                        Infolists\Components\TextEntry::make('tutor.email')
                            ->label('Tutor Email')
                            ->placeholder('N/A'),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => $record->tutor_id),
                
                Infolists\Components\Section::make('Session Materials')
                    ->schema([
                        Infolists\Components\TextEntry::make('session_notes')
                            ->label('Session Notes')
                            ->markdown()
                            ->placeholder('Will be available after session')
                            ->columnSpan(2),
                        Infolists\Components\TextEntry::make('session_recording_link')
                            ->label('Recording')
                            ->url(fn ($record) => $record->session_recording_link)
                            ->openUrlInNewTab()
                            ->placeholder('Not available')
                            ->columnSpan(2),
                    ])
                    ->visible(fn ($record) => $record->status === 'completed'),
            ]);
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('join_session')
                ->label('Join Session')
                ->icon('heroicon-o-video-camera')
                ->color('success')
                ->url(fn ($record) => $record->google_meet_link)
                ->openUrlInNewTab()
                ->visible(fn ($record) => $record->status === 'confirmed' && $record->google_meet_link),
            
            Actions\Action::make('cancel')
                ->label('Cancel Request')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function ($record) {
                    $record->update(['status' => 'cancelled']);
                    \Filament\Notifications\Notification::make()
                        ->title('Request Cancelled')
                        ->success()
                        ->send();
                })
                ->visible(fn ($record) => in_array($record->status, ['pending_assignment', 'pending_tutor_response'])),
        ];
    }
}
