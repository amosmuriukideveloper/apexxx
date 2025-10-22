<?php

namespace App\Filament\Resources\ProjectResource\Tables;

use Filament\Tables;
use App\Models\Project;
use App\Models\Expert;
use Filament\Notifications\Notification;
use Filament\Forms;

class ProjectTable
{
    public static function getColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('project_number')
                ->searchable()
                ->sortable()
                ->copyable(),
            Tables\Columns\TextColumn::make('title')
                ->searchable()
                ->limit(30)
                ->tooltip(fn ($record) => $record->title),
            Tables\Columns\TextColumn::make('student.name')
                ->label('Student')
                ->searchable()
                ->toggleable(),
            Tables\Columns\TextColumn::make('expert.name')
                ->label('Expert')
                ->default('Unassigned')
                ->searchable()
                ->toggleable(),
            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'warning' => 'awaiting_assignment',
                    'info' => 'assigned',
                    'primary' => 'in_progress',
                    'secondary' => 'under_review',
                    'danger' => 'revision_required',
                    'success' => 'completed',
                    'gray' => 'cancelled',
                ]),
            Tables\Columns\TextColumn::make('project_type')
                ->badge()
                ->toggleable(),
            Tables\Columns\BadgeColumn::make('complexity_level')
                ->colors([
                    'success' => 'basic',
                    'warning' => 'intermediate',
                    'danger' => 'advanced',
                ]),
            Tables\Columns\TextColumn::make('cost')
                ->money('USD')
                ->sortable(),
            Tables\Columns\BadgeColumn::make('payment_status')
                ->colors([
                    'warning' => 'pending',
                    'success' => 'paid',
                    'danger' => 'refunded',
                ])
                ->toggleable(),
            Tables\Columns\TextColumn::make('deadline')
                ->dateTime()
                ->sortable()
                ->color(fn ($record) => $record->deadline < now() && !in_array($record->status, ['completed', 'cancelled']) ? 'danger' : null),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Submitted')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function getFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'awaiting_assignment' => 'Awaiting Assignment',
                    'assigned' => 'Assigned',
                    'in_progress' => 'In Progress',
                    'under_review' => 'Under Review',
                    'revision_required' => 'Revision Required',
                    'completed' => 'Completed',
                    'cancelled' => 'Cancelled',
                ]),
            Tables\Filters\SelectFilter::make('payment_status')
                ->options([
                    'pending' => 'Pending',
                    'paid' => 'Paid',
                    'refunded' => 'Refunded',
                ]),
            Tables\Filters\SelectFilter::make('complexity_level')
                ->options([
                    'basic' => 'Basic',
                    'intermediate' => 'Intermediate',
                    'advanced' => 'Advanced',
                ]),
            Tables\Filters\Filter::make('overdue')
                ->label('Overdue Projects')
                ->query(fn ($query) => $query->where('deadline', '<', now())
                    ->whereNotIn('status', ['completed', 'cancelled'])),
            Tables\Filters\Filter::make('unassigned')
                ->label('Unassigned')
                ->query(fn ($query) => $query->whereNull('expert_id')),
        ];
    }

    public static function getActions(): array
    {
        return [
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\ActionGroup::make([
                Tables\Actions\Action::make('submit_project')
                    ->label('Submit Project')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->url(fn (Project $record) => 
                        \App\Filament\Resources\ProjectResource::getUrl('submit', ['record' => $record])
                    )
                    ->visible(fn (Project $record) => 
                        in_array($record->status, ['in_progress', 'revision_required'])
                    ),
                Tables\Actions\Action::make('review_submission')
                    ->label('Review Submission')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('primary')
                    ->url(fn (Project $record) => 
                        \App\Filament\Resources\ProjectResource::getUrl('review-submission', ['record' => $record])
                    )
                    ->visible(fn (Project $record) => $record->status === 'under_review'),
                Tables\Actions\Action::make('assign')
                    ->label('Assign Expert')
                    ->icon('heroicon-o-user-plus')
                    ->color('info')
                    ->visible(fn (Project $record) => $record->status === 'awaiting_assignment' && !$record->expert_id)
                    ->form([
                        Forms\Components\Select::make('expert_id')
                            ->label('Select Expert')
                            ->options(Expert::where('application_status', 'approved')
                                ->where('status', 'active')
                                ->where('available', true)
                                ->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),
                    ])
                    ->action(function (Project $record, array $data) {
                        $record->update([
                            'expert_id' => $data['expert_id'],
                            'status' => 'assigned',
                            'assigned_at' => now(),
                        ]);
                        
                        Notification::make()
                            ->success()
                            ->title('Expert Assigned')
                            ->body('Project has been assigned to expert.')
                            ->send();
                    }),
            ]),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ];
    }
}
