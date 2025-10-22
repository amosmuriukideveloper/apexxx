<?php

namespace App\Filament\Resources\ApplicationFormResource\Pages;

use App\Filament\Resources\ApplicationFormResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewApplicationForm extends ViewRecord
{
    protected static string $resource = ApplicationFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn ($record) => $record->status === 'pending')
                ->action(function ($record) {
                    $record->update([
                        'status' => 'approved',
                        'reviewed_by' => auth()->id(),
                        'reviewed_at' => now(),
                    ]);
                }),
            Actions\Action::make('reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->form([
                    \Filament\Forms\Components\Textarea::make('review_notes')
                        ->required()
                        ->rows(3),
                ])
                ->visible(fn ($record) => $record->status === 'pending')
                ->action(function ($record, array $data) {
                    $record->update([
                        'status' => 'rejected',
                        'reviewed_by' => auth()->id(),
                        'reviewed_at' => now(),
                        'review_notes' => $data['review_notes'],
                    ]);
                }),
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Applicant Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('full_name'),
                        Infolists\Components\TextEntry::make('email')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('phone'),
                        Infolists\Components\TextEntry::make('applicant_type')
                            ->badge(),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'under_review' => 'info',
                                'approved' => 'success',
                                'rejected' => 'danger',
                            }),
                    ])->columns(2),
                
                Infolists\Components\Section::make('Educational Background')
                    ->schema([
                        Infolists\Components\TextEntry::make('education_level'),
                        Infolists\Components\TextEntry::make('institution'),
                        Infolists\Components\TextEntry::make('field_of_study'),
                        Infolists\Components\TextEntry::make('years_of_experience')
                            ->suffix(' years'),
                    ])->columns(2),
                
                Infolists\Components\Section::make('Expertise & Links')
                    ->schema([
                        Infolists\Components\TextEntry::make('expertise_areas')
                            ->badge()
                            ->separator(',')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('why_join')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('sample_work_url')
                            ->url(fn ($record) => $record->sample_work_url)
                            ->openUrlInNewTab(),
                        Infolists\Components\TextEntry::make('linkedin_profile')
                            ->url(fn ($record) => $record->linkedin_profile)
                            ->openUrlInNewTab(),
                    ])->columns(2),
                
                Infolists\Components\Section::make('Review Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('reviewer.name')
                            ->label('Reviewed By')
                            ->visible(fn ($record) => $record->reviewed_by),
                        Infolists\Components\TextEntry::make('reviewed_at')
                            ->dateTime()
                            ->visible(fn ($record) => $record->reviewed_at),
                        Infolists\Components\TextEntry::make('review_notes')
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record->review_notes),
                    ])->columns(2)
                    ->visible(fn ($record) => in_array($record->status, ['approved', 'rejected'])),
            ]);
    }
}
