<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EnrollmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';
    protected static ?string $recordTitleAttribute = 'user.name';
    protected static ?string $title = 'Enrollments';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('amount_paid')
                    ->label('Amount Paid')
                    ->money('USD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->badge(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'active',
                        'primary' => 'completed',
                        'warning' => 'dropped',
                        'danger' => 'refunded',
                    ]),
                
                Tables\Columns\TextColumn::make('progress_percentage')
                    ->label('Progress')
                    ->formatStateUsing(function ($record) {
                        $totalLectures = $record->course->total_lectures;
                        if ($totalLectures == 0) return '0%';
                        
                        $completed = $record->completedLectures()->count();
                        $percentage = round(($completed / $totalLectures) * 100);
                        return $percentage . '%';
                    })
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        intval($state) >= 80 => 'success',
                        intval($state) >= 50 => 'warning',
                        default => 'danger',
                    }),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Enrolled At')
                    ->dateTime()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Not Completed'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'dropped' => 'Dropped',
                        'refunded' => 'Refunded',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('view_progress')
                    ->label('View Progress')
                    ->icon('heroicon-o-chart-bar')
                    ->url(fn ($record) => route('filament.admin.resources.courses.view-enrollment', [
                        'course' => $record->course_id,
                        'enrollment' => $record->id,
                    ]))
                    ->openUrlInNewTab(),
            ])
            ->headerActions([])
            ->bulkActions([]);
    }
}
