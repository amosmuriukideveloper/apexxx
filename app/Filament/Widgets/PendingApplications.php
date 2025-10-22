<?php

namespace App\Filament\Widgets;

use App\Models\ApplicationForm;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingApplications extends BaseWidget
{
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                ApplicationForm::query()
                    ->where('status', 'pending')
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('applicant_type')
                    ->label('Type')
                    ->badge()
                    ->colors([
                        'primary' => 'expert',
                        'success' => 'tutor',
                        'warning' => 'content_creator',
                    ]),
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('years_of_experience')
                    ->label('Experience')
                    ->suffix(' years')
                    ->sortable(),
                Tables\Columns\TextColumn::make('expertise_areas')
                    ->badge()
                    ->separator(',')
                    ->limit(3)
                    ->listWithLineBreaks(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'under_review',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('review')
                    ->url(fn (ApplicationForm $record): string => 
                        route('filament.admin.resources.application-forms.view', $record)
                    )
                    ->icon('heroicon-o-eye')
                    ->label('Review'),
            ]);
    }
}
