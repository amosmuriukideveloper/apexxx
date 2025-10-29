<?php

namespace App\Filament\Tutor\Resources\MyTutoringSessionResource\Pages;

use App\Filament\Tutor\Resources\MyTutoringSessionResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewSession extends ViewRecord
{
    protected static string $resource = MyTutoringSessionResource::class;
    
    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Student Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('student.name')
                            ->label('Student Name'),
                        Infolists\Components\TextEntry::make('student.email')
                            ->label('Email')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('current_knowledge_level')
                            ->label('Knowledge Level')
                            ->badge()
                            ->formatStateUsing(fn ($state) => ucfirst($state)),
                    ])
                    ->columns(3),
                
                Infolists\Components\Section::make('Session Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('subject.name')
                            ->label('Subject')
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('specific_topic')
                            ->label('Specific Topic'),
                        Infolists\Components\TextEntry::make('duration')
                            ->formatStateUsing(fn ($state) => $state . ' minutes'),
                        Infolists\Components\TextEntry::make('base_price')
                            ->label('Your Earnings')
                            ->money('USD'),
                        Infolists\Components\TextEntry::make('learning_goals')
                            ->label('Student Learning Goals')
                            ->columnSpan(4),
                        Infolists\Components\TextEntry::make('specific_help_areas')
                            ->label('Areas Student Needs Help With')
                            ->columnSpan(4),
                    ])
                    ->columns(4),
                
                Infolists\Components\Section::make('Schedule')
                    ->schema([
                        Infolists\Components\TextEntry::make('preferred_date')
                            ->label('Scheduled Date')
                            ->date('F d, Y')
                            ->badge(),
                        
                        Infolists\Components\TextEntry::make('preferred_time')
                            ->label('Time')
                            ->time('H:i A')
                            ->color('success')
                            ->placeholder(fn ($record) => 'Proposed: ' . $record->preferred_date->format('F d, Y H:i A')),
                        Infolists\Components\TextEntry::make('google_meet_link')
                            ->label('Meeting Link')
                            ->url(fn ($record) => $record->google_meet_link)
                            ->openUrlInNewTab()
                            ->placeholder('Will be available after confirmation'),
                    ])
                    ->columns(2),
                
                Infolists\Components\Section::make('Student Materials')
                    ->schema([
                        Infolists\Components\TextEntry::make('attachments')
                            ->label('Attached Files')
                            ->placeholder('No files attached')
                            ->formatStateUsing(function ($state) {
                                if (!$state) return 'No files';
                                $files = is_string($state) ? json_decode($state, true) : $state;
                                return count($files) . ' file(s) attached';
                            }),
                        Infolists\Components\TextEntry::make('additional_notes')
                            ->label('Student Notes')
                            ->placeholder('No additional notes'),
                    ])
                    ->columns(2),
                
                Infolists\Components\Section::make('Session Notes')
                    ->schema([
                        Infolists\Components\TextEntry::make('session_notes')
                            ->label('Your Session Notes')
                            ->markdown()
                            ->placeholder('Session notes will appear here after completion'),
                        Infolists\Components\TextEntry::make('session_recording_link')
                            ->label('Recording Link')
                            ->url(fn ($record) => $record->session_recording_link)
                            ->openUrlInNewTab()
                            ->placeholder('Not available'),
                    ])
                    ->visible(fn ($record) => $record->status === 'completed'),
            ]);
    }
}
