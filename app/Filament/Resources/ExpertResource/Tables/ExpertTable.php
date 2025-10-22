<?php

namespace App\Filament\Resources\ExpertResource\Tables;

use Filament\Tables;
use App\Models\Expert;
use Filament\Notifications\Notification;

class ExpertTable
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
            Tables\Columns\TextColumn::make('specialization')
                ->searchable()
                ->badge(),
            Tables\Columns\TextColumn::make('expertise_areas')
                ->badge()
                ->separator(',')
                ->limit(2)
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
            Tables\Columns\IconColumn::make('available')
                ->boolean()
                ->label('Available'),
            Tables\Columns\TextColumn::make('rating')
                ->badge()
                ->icon('heroicon-o-star')
                ->color('warning')
                ->suffix('/5'),
            Tables\Columns\TextColumn::make('total_projects_completed')
                ->label('Projects')
                ->alignCenter()
                ->sortable(),
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
                ->placeholder('All experts')
                ->trueLabel('Verified only')
                ->falseLabel('Not verified'),
            Tables\Filters\TernaryFilter::make('available')
                ->label('Availability')
                ->placeholder('All experts')
                ->trueLabel('Available only')
                ->falseLabel('Unavailable'),
            Tables\Filters\Filter::make('high_rated')
                ->label('High Rated (4.0+)')
                ->query(fn ($query) => $query->where('rating', '>=', 4.0)),
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
                ->visible(fn (Expert $record) => $record->application_status === 'pending')
                ->action(function (Expert $record) {
                    $record->update([
                        'application_status' => 'approved',
                        'approved_by' => auth()->id(),
                        'approved_at' => now(),
                        'status' => 'active',
                    ]);
                    
                    Notification::make()
                        ->success()
                        ->title('Expert Approved')
                        ->body("Expert {$record->name} has been successfully approved.")
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
                ->visible(fn (Expert $record) => $record->application_status === 'pending')
                ->action(function (Expert $record, array $data) {
                    $record->update([
                        'application_status' => 'rejected',
                        'rejection_reason' => $data['rejection_reason'],
                        'status' => 'suspended',
                    ]);
                    
                    Notification::make()
                        ->warning()
                        ->title('Expert Rejected')
                        ->body("Expert {$record->name} application has been rejected.")
                        ->send();
                }),
            Tables\Actions\Action::make('suspend')
                ->icon('heroicon-o-no-symbol')
                ->color('warning')
                ->requiresConfirmation()
                ->visible(fn (Expert $record) => $record->status === 'active')
                ->action(function (Expert $record) {
                    $record->update(['status' => 'suspended']);
                    
                    Notification::make()
                        ->warning()
                        ->title('Expert Suspended')
                        ->send();
                }),
            Tables\Actions\Action::make('activate')
                ->icon('heroicon-o-check')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (Expert $record) => $record->status === 'suspended')
                ->action(function (Expert $record) {
                    $record->update(['status' => 'active']);
                    
                    Notification::make()
                        ->success()
                        ->title('Expert Activated')
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
                            ->title('Experts Approved')
                            ->send();
                    }),
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ];
    }
}
