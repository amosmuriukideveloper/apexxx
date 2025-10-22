<?php

namespace App\Filament\Resources\ApplicationFormResource\Tables;

use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class ApplicationFormTable
{
    public static function getColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('full_name')
                ->searchable()
                ->sortable(),
            Tables\Columns\BadgeColumn::make('applicant_type')
                ->colors([
                    'primary' => 'Expert',
                    'success' => 'Tutor',
                    'warning' => 'ContentCreator',
                ]),
            Tables\Columns\TextColumn::make('email')
                ->searchable()
                ->copyable(),
            Tables\Columns\TextColumn::make('phone')
                ->searchable(),
            Tables\Columns\TextColumn::make('years_of_experience')
                ->suffix(' years')
                ->alignCenter(),
            Tables\Columns\TextColumn::make('expertise_areas')
                ->badge()
                ->separator(',')
                ->limit(3),
            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'warning' => 'pending',
                    'info' => 'under_review',
                    'success' => 'approved',
                    'danger' => 'rejected',
                ]),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Submitted')
                ->dateTime()
                ->sortable(),
        ];
    }

    public static function getFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'pending' => 'Pending',
                    'under_review' => 'Under Review',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ]),
            Tables\Filters\SelectFilter::make('applicant_type')
                ->options([
                    'Expert' => 'Expert',
                    'Tutor' => 'Tutor',
                    'ContentCreator' => 'Content Creator',
                ]),
            Tables\Filters\Filter::make('created_at')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('from'),
                    \Filament\Forms\Components\DatePicker::make('until'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                }),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            Tables\Actions\BulkAction::make('approve')
                ->label('Bulk Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(function ($records) {
                    foreach ($records as $record) {
                        $record->update(['status' => 'approved']);
                    }
                }),
            Tables\Actions\BulkAction::make('reject')
                ->label('Bulk Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function ($records) {
                    foreach ($records as $record) {
                        $record->update(['status' => 'rejected']);
                    }
                }),
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ];
    }
}
