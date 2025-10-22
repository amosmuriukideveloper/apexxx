<?php

namespace App\Filament\Resources\ContentCreatorResource\Tables;

use Filament\Tables;
use App\Models\ContentCreator;
use Filament\Notifications\Notification;

class ContentCreatorTable
{
    public static function getColumns(): array
    {
        return [
            Tables\Columns\ImageColumn::make('profile_photo')
                ->circular()
                ->defaultImageUrl(url('/images/default-avatar.png')),
            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->sortable()
                ->weight('medium'),
            Tables\Columns\TextColumn::make('email')
                ->searchable()
                ->copyable()
                ->icon('heroicon-o-envelope'),
            Tables\Columns\TextColumn::make('expertise_areas')
                ->badge()
                ->separator(',')
                ->limit(3)
                ->color('primary')
                ->tooltip(fn ($record) => implode(', ', $record->expertise_areas ?? [])),
            Tables\Columns\BadgeColumn::make('application_status')
                ->colors([
                    'warning' => 'pending',
                    'success' => 'approved',
                    'danger' => 'rejected',
                ]),
            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'success' => 'active',
                    'danger' => 'suspended',
                ]),
            Tables\Columns\IconColumn::make('documents_verified')
                ->boolean()
                ->label('Verified'),
            Tables\Columns\TextColumn::make('total_courses')
                ->label('Courses')
                ->alignCenter()
                ->sortable()
                ->icon('heroicon-o-book-open'),
            Tables\Columns\TextColumn::make('total_students')
                ->label('Students')
                ->alignCenter()
                ->sortable()
                ->icon('heroicon-o-users'),
            Tables\Columns\TextColumn::make('total_earnings')
                ->money('USD')
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function getFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('application_status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ]),
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'active' => 'Active',
                    'suspended' => 'Suspended',
                ]),
            Tables\Filters\TernaryFilter::make('documents_verified')
                ->label('Documents Verified')
                ->placeholder('All creators')
                ->trueLabel('Verified only')
                ->falseLabel('Not verified'),
            Tables\Filters\Filter::make('has_courses')
                ->label('Has Published Courses')
                ->query(fn ($query) => $query->where('total_courses', '>', 0)),
        ];
    }

    public static function getActions(): array
    {
        return [
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\Action::make('approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (ContentCreator $record) => $record->application_status === 'pending')
                ->action(function (ContentCreator $record) {
                    $record->update([
                        'application_status' => 'approved',
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                        'status' => 'active',
                    ]);
                    
                    Notification::make()
                        ->success()
                        ->title('Content Creator Approved')
                        ->body("Content Creator {$record->name} has been successfully approved.")
                        ->send();
                }),
            Tables\Actions\Action::make('reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->form([
                    \Filament\Forms\Components\Textarea::make('rejection_reason')
                        ->required()
                        ->rows(3)
                        ->label('Rejection Reason'),
                ])
                ->visible(fn (ContentCreator $record) => $record->application_status === 'pending')
                ->action(function (ContentCreator $record, array $data) {
                    $record->update([
                        'application_status' => 'rejected',
                        'rejection_reason' => $data['rejection_reason'],
                        'status' => 'suspended',
                    ]);
                    
                    Notification::make()
                        ->warning()
                        ->title('Content Creator Rejected')
                        ->body("Content Creator {$record->name} application has been rejected.")
                        ->send();
                }),
            Tables\Actions\Action::make('suspend')
                ->icon('heroicon-o-no-symbol')
                ->color('warning')
                ->requiresConfirmation()
                ->visible(fn (ContentCreator $record) => $record->status === 'active')
                ->action(function (ContentCreator $record) {
                    $record->update(['status' => 'suspended']);
                    
                    Notification::make()
                        ->warning()
                        ->title('Content Creator Suspended')
                        ->send();
                }),
            Tables\Actions\Action::make('activate')
                ->icon('heroicon-o-check')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (ContentCreator $record) => $record->status === 'suspended')
                ->action(function (ContentCreator $record) {
                    $record->update(['status' => 'active']);
                    
                    Notification::make()
                        ->success()
                        ->title('Content Creator Activated')
                        ->send();
                }),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\BulkAction::make('approve_selected')
                    ->label('Approve Selected')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            if ($record->application_status === 'pending') {
                                $record->update([
                                    'application_status' => 'approved',
                                    'approved_by' => auth()->id(),
                                    'approved_at' => now(),
                                    'status' => 'active',
                                ]);
                            }
                        }
                        
                        Notification::make()
                            ->success()
                            ->title('Content Creators Approved')
                            ->send();
                    }),
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ];
    }
}
