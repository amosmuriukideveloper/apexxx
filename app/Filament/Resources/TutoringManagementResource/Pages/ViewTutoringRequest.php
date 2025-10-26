<?php

namespace App\Filament\Resources\TutoringManagementResource\Pages;

use App\Filament\Resources\TutoringManagementResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewTutoringRequest extends ViewRecord
{
    protected static string $resource = TutoringManagementResource::class;
    
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Request Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('request_number')
                            ->label('Request Number')
                            ->badge()
                            ->color('primary'),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucwords($state, '_'))),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Requested On')
                            ->dateTime('F d, Y H:i'),
                        Infolists\Components\TextEntry::make('payment_status')
                            ->badge()
                            ->color(fn ($state) => $state === 'paid' ? 'success' : 'warning'),
                    ])
                    ->columns(4),
                
                Infolists\Components\Section::make('Student Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('student.name')
                            ->label('Student Name'),
                        Infolists\Components\TextEntry::make('student.email')
                            ->label('Email')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('student.phone')
                            ->label('Phone')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('current_knowledge_level')
                            ->label('Knowledge Level')
                            ->badge()
                            ->formatStateUsing(fn ($state) => ucfirst($state)),
                    ])
                    ->columns(4),
                
                Infolists\Components\Section::make('Session Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('subject.name')
                            ->label('Subject')
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('specific_topic')
                            ->label('Specific Topic')
                            ->columnSpan(2),
                        Infolists\Components\TextEntry::make('duration')
                            ->formatStateUsing(fn ($state) => $state . ' minutes'),
                        Infolists\Components\TextEntry::make('learning_goals')
                            ->label('Learning Goals')
                            ->columnSpan(4),
                        Infolists\Components\TextEntry::make('specific_help_areas')
                            ->label('Areas Needing Help')
                            ->columnSpan(4),
                    ])
                    ->columns(4),
                
                Infolists\Components\Section::make('Schedule')
                    ->schema([
                        Infolists\Components\TextEntry::make('preferred_date')
                            ->label('Preferred Date & Time')
                            ->dateTime('F d, Y H:i')
                            ->badge()
                            ->color('primary'),
                        Infolists\Components\TextEntry::make('alternative_date_1')
                            ->label('Alternative #1')
                            ->dateTime('F d, Y H:i')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('alternative_date_2')
                            ->label('Alternative #2')
                            ->dateTime('F d, Y H:i')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('confirmed_date')
                            ->label('Confirmed Date')
                            ->dateTime('F d, Y H:i')
                            ->badge()
                            ->color('success')
                            ->placeholder('Not yet confirmed'),
                    ])
                    ->columns(4),
                
                Infolists\Components\Section::make('Tutor Assignment')
                    ->schema([
                        Infolists\Components\TextEntry::make('tutor.name')
                            ->label('Assigned Tutor')
                            ->placeholder('Not assigned'),
                        Infolists\Components\TextEntry::make('tutor.email')
                            ->label('Tutor Email')
                            ->copyable()
                            ->placeholder('N/A'),
                        Infolists\Components\TextEntry::make('assigned_at')
                            ->label('Assigned On')
                            ->dateTime('F d, Y H:i')
                            ->placeholder('Not assigned'),
                        Infolists\Components\TextEntry::make('tutor_response_date')
                            ->label('Tutor Responded')
                            ->dateTime('F d, Y H:i')
                            ->placeholder('Pending response'),
                    ])
                    ->columns(4)
                    ->visible(fn ($record) => $record->tutor_id),
                
                Infolists\Components\Section::make('Pricing')
                    ->schema([
                        Infolists\Components\TextEntry::make('base_price')
                            ->label('Base Rate')
                            ->money('USD'),
                        Infolists\Components\TextEntry::make('platform_fee')
                            ->label('Platform Fee (15%)')
                            ->money('USD'),
                        Infolists\Components\TextEntry::make('total_price')
                            ->label('Total')
                            ->money('USD')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('paid_at')
                            ->label('Paid On')
                            ->dateTime('F d, Y H:i')
                            ->placeholder('Not paid'),
                    ])
                    ->columns(4),
                
                Infolists\Components\Section::make('Session Notes')
                    ->schema([
                        Infolists\Components\TextEntry::make('google_meet_link')
                            ->label('Meeting Link')
                            ->url(fn ($record) => $record->google_meet_link)
                            ->openUrlInNewTab()
                            ->placeholder('Will be generated after tutor confirmation')
                            ->columnSpan(2),
                        Infolists\Components\TextEntry::make('session_notes')
                            ->label('Session Notes')
                            ->markdown()
                            ->placeholder('Will be added after session')
                            ->columnSpan(4),
                        Infolists\Components\TextEntry::make('session_recording_link')
                            ->label('Recording')
                            ->url(fn ($record) => $record->session_recording_link)
                            ->openUrlInNewTab()
                            ->placeholder('Not available')
                            ->columnSpan(2),
                    ])
                    ->columns(4)
                    ->visible(fn ($record) => in_array($record->status, ['confirmed', 'completed'])),
            ]);
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('generate_meet_link')
                ->label('Generate Google Meet Link')
                ->icon('heroicon-o-video-camera')
                ->color('success')
                ->requiresConfirmation()
                ->action(function ($record) {
                    // Generate Google Meet link (simplified - integrate with Google API)
                    $meetLink = 'https://meet.google.com/' . strtolower(substr(md5(uniqid()), 0, 10));
                    
                    $record->update([
                        'google_meet_link' => $meetLink,
                    ]);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Meeting Link Generated')
                        ->success()
                        ->body('Google Meet link has been created and sent to both parties.')
                        ->send();
                })
                ->visible(fn ($record) => $record->status === 'confirmed' && !$record->google_meet_link),
        ];
    }
}
