<?php

namespace App\Filament\Tutor\Pages;

use App\Models\TutoringRequest;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms;
use Illuminate\Support\Facades\Auth;

class PendingRequests extends Page implements HasTable
{
    use InteractsWithTable;
    
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $navigationLabel = 'Pending Requests';
    protected static ?string $navigationGroup = 'Sessions';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.tutor.pages.simple-page';
    
    public static function getNavigationBadge(): ?string
    {
        if (!Auth::check()) {
            return null;
        }
        
        $count = TutoringRequest::where('tutor_id', Auth::id())
            ->where('status', 'pending_tutor_response')
            ->count();
            
        return $count > 0 ? (string) $count : null;
    }
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                TutoringRequest::query()
                    ->where('tutor_id', Auth::id())
                    ->where('status', 'pending_tutor_response')
            )
            ->columns([
                Tables\Columns\TextColumn::make('request_number')
                    ->label('Request #')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Student')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('subject.name')
                    ->label('Subject')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('specific_topic')
                    ->label('Topic')
                    ->limit(40)
                    ->wrap(),
                
                Tables\Columns\TextColumn::make('preferred_date')
                    ->label('Requested Date')
                    ->dateTime('M d, H:i')
                    ->sortable()
                    ->description(fn ($record) => $record->preferred_date->diffForHumans()),
                
                Tables\Columns\TextColumn::make('duration')
                    ->formatStateUsing(fn ($state) => $state . ' min'),
                
                Tables\Columns\TextColumn::make('base_price')
                    ->label('Pay')
                    ->money('USD'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Received')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('respond')
                    ->label('Respond')
                    ->icon('heroicon-o-hand-raised')
                    ->color('primary')
                    ->form([
                        Forms\Components\Radio::make('response')
                            ->label('Your Response')
                            ->options([
                                'accept' => 'Accept Requested Time',
                                'suggest' => 'Suggest Different Time',
                                'decline' => 'Decline Request',
                            ])
                            ->required()
                            ->live(),
                        
                        Forms\Components\DateTimePicker::make('suggested_date')
                            ->label('Suggest Alternative Date & Time')
                            ->native(false)
                            ->minDate(now())
                            ->visible(fn (Forms\Get $get) => $get('response') === 'suggest')
                            ->required(fn (Forms\Get $get) => $get('response') === 'suggest'),
                        
                        Forms\Components\Textarea::make('reason')
                            ->label('Reason for Declining')
                            ->rows(3)
                            ->visible(fn (Forms\Get $get) => $get('response') === 'decline')
                            ->required(fn (Forms\Get $get) => $get('response') === 'decline'),
                    ])
                    ->action(function (array $data, $record) {
                        if ($data['response'] === 'accept') {
                            $record->update([
                                'status' => 'scheduled',
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Request Accepted')
                                ->success()
                                ->body('Session confirmed!')
                                ->send();
                        } elseif ($data['response'] === 'suggest') {
                            $record->update([
                                'status' => 'pending_student_response',
                                'tutor_suggested_date' => $data['suggested_date'],
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Alternative Time Suggested')
                                ->info()
                                ->send();
                        } else {
                            $record->update([
                                'status' => 'cancelled',
                                'cancellation_reason' => $data['reason'],
                                'cancelled_by' => 'tutor',
                            ]);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Request Declined')
                                ->warning()
                                ->send();
                        }
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No pending requests')
            ->emptyStateDescription('You have no tutoring requests waiting for your response.')
            ->emptyStateIcon('heroicon-o-inbox');
    }
}
